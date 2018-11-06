<?php
/**
 * Created by PhpStorm.
 * User: Mengchenchen
 * Date: 2018/8/14/014
 * Time: 11:35
 * Blog: https://mengchenchen.github.io
 */

namespace Mengcc;

class Doc
{
	protected $params;
	protected $files;

	public $path;
	public $namespace;
	public $api_name;
	public $view_path_and_name;

	public static $methodsCSS = [
		'patch'  => 'label label-default',
		'get'    => 'label label-primary',
		'post'   => 'label label-success',
		'put'    => 'label label-info',
		'any'    => 'label label-warning',
		'delete' => 'label label-danger',
	];

	public function __construct($config = [])
	{
		foreach (['path', 'api_name', 'namespace'] as $key => $value) {
			if (!isset($config[$value]))
				$this->dd('Doc初始化参数缺失');
			$this->$value = $config[$value];
		}
		// 开始扫描文件
		$this->scanFile($this->path);
		$this->init();
	}

	// 初始化
	public function init()
	{
		foreach ($this->files as $file) {
			$class  = new \ReflectionClass($this->namespace . $file);
			$method = $class->getMethods();
			foreach ($method as &$property) {
				$comments = explode('@', $property->getDocComment());
				if (!isset($comments[1]))
					continue;
				if (!strpos($comments[1], 'Doc') == 3)
					continue;
				call_user_func([$this, 'encode'], $comments[1]);
			}
		}
	}

	// 解析参数
	public function encode()
	{
		$params = str_replace([" ", "　", "\t", "\n", "\r", 'MccDoc', '(', ')', '*'], '', func_get_args()[0]);
		if (!$paramsToArr = json_decode($params, true))
			$this->dd('Json格式错误' . $params);
		$paramsToArr['class']   = self::$methodsCSS[strtolower($paramsToArr['method'])];
		$group                  = isset($paramsToArr['group']) ? $paramsToArr['group'] : '未分组';
		$this->params[$group][] = $paramsToArr;
	}

	/**
	 * 获取所有文档参数数据
	 *
	 * @return mixed
	 * @author mengchenchen
	 */
	public function getAllData()
	{
		return $this->params;
	}

	/**
     * 解析 前段可以处理的参数数据
     *
	 * @return array
	 * @author mengchenchen
	 */
	public function getParamsEncode()
	{
		$params = [];
		foreach ($this->params as $key => $value) {
			$params[] = [
				'key'   => $key,
				'value' => $value,
			];
		}
		return $params;
	}

	/**
     * 获取所有文件
     *
	 * @return mixed
	 * @author mengchenchen
	 */
	public function getFiles()
	{
		return $this->files;
	}

	/**
     *  生成页面
     *
	 * @param string $path
	 * @param string $name
	 * @return bool
	 * @author mengchenchen
	 */
	public function view($path = '/', $name = 'mcc-doc')
	{
		/**
		 * 导航栏
		 */
		$nav = '';
		foreach (array_keys($this->params) as $item) {
			$nav .= '<li><a href = "#' . $item . '">' . $item . '</a></li>';
		}

		$params = json_encode($this->getParamsEncode(), JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);

		$html = <<<HTML
<!DOCTYPE html>
<html lang = "zh-CN">
<head>
    <meta charset = "utf-8">
    <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name = "description" content = "">
    <meta name = "author" content = "">
    <link rel = "icon" href = "../../favicon.ico">
    <title>MccDoc</title>
    <!-- Bootstrap core CSS -->
    <link href = "https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel = "stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script src = "https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
</head>

<body>
<nav class = "navbar navbar-default navbar-static-top">
    <div class = "container">
        <div class = "navbar-header">
            <a class = "navbar-brand" href = "#">MccDoc</a>
        </div>
        <div id = "navbar" class = "navbar-collapse collapse">
            <ul class = "nav navbar-nav">
                <li>
                    <a href = "#">更多...</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class = "container" id="app">
    <div v-for="(group, index) in records">
        <h3><a v-bind:name="group.key">{{ group.key }}</a></h3>
        <template v-for = "item in group.value">
        	<div class="panel-group" id="accordion">
        		<div class = "panel panel-default">
        			<div class = "panel-heading">
            			<h4 class = "panel-title">
            			<span v-bind:class = "item.class" style="width: 60px;display: inline-block;height: 23px;line-height: 20px;"> {{ item.method }} </span>
            			<a data-toggle = "collapse" data-parent = "#accordion" v-bind:href = "'#'+item.name">{{ item.name }}</a>
            			</h4>
        			</div>
        			<div v-bind:id= "item.name" class = "panel-collapse collapse">
            			<div class = "panel-body">
            				<div v-if="item.params" class="panel panel-default">
                        <div class="panel-heading">请求地址：{{ item.url }}</div>
                        <div class="panel-body">
                        <form class="form-horizontal" v-bind:action="item.url" method="post">
                                <template v-for = "(desc,param) in item.params">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{ param }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" v-bind:name="param">
                                            <span class="help-block">{{ desc }}</span>
                                        </div>
                                    </div>
                               </template>
	                            <div class="form-group">
	                               <div class="col-sm-offset-2 col-sm-10">
	                                    <button v-on:click="submit" type="button" class="btn btn-default ajaxSubmit">submit</button>
	                               </div>
	                            </div>
                        </form>
                        </div>
            				</div>
            			</div>
        			</div>
        		</div>
        	</div>
        </template>
    </div>
</div>

<script src = "https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js"></script>
<script>
    var app = new Vue({
        el:'#app',
        data:{
            records: {$params}
        },
				methods: {
					submit: function(event){
						console.log(this)
					}
				}
    })
</script>
</body>
</html>
HTML;
		try {
			$this->mkdirs($path);
			file_put_contents($path . '/' . $name . '.html', $html);
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
     * 结束程序
     *
	 * @param $params
	 * @author mengchenchen
	 */
	private function dd($params)
	{
		$color      = '#2295bc';
		$background = '#e4e7e7'; ?>
        <pre style = "direction: ltr;background:<?= $background; ?>;color:<?= $color; ?>;
            max-width: 90%;margin: 30px auto;overflow:auto;
            font-family: Monaco,Consolas, 'Lucida Console',monospace;font-size: 16px;padding: 20px;"><?php print_r($params);
		die("</pre>");
	}

	/**
     * 遍历并引入文件
     *
	 * @param $path
	 * @author mengchenchen
	 */
	protected function scanFile($path)
	{
		foreach (scandir($path) as $file) {
			if ($file != '.' && $file != '..') {
				if (is_dir($path . '/' . $file)) {
					$this->scanFile($path . '/' . $file);
				} else {
					if (strpos(basename($file), 'Controller') > 0) {
						require_once($this->path . '/' . basename($file));
						$this->files[] = str_replace('.php', '', basename($file));
					}
				}
			}
		}
	}

	/**
     * 创建多层目录
     *
	 * @param $dir
	 * @param int $mode
	 * @return bool
	 * @author mengchenchen
	 */
	protected function mkDirs($dir, $mode = 0777)
	{
		if (is_dir($dir) || (@mkdir($dir, $mode) && chmod($dir, $mode)))
			return TRUE;
		if (!$this->mkDirs(dirname($dir), $mode))
			return FALSE;
		return @mkdir($dir, $mode);
	}
}
