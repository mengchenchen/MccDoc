<?php
/**
 * Created by PhpStorm.
 * User: Mengchenchen
 * Date: 2018/8/14/014
 * Time: 11:35
 * Blog: https://mengchenchen.github.io
 */

namespace Mengcc;

use app\Controllers\Api\NvcController;

class Doc
{
	public $path;
	public $namespace;
	public $api_name;
	public $view_path_and_name;

	protected $params;
	protected $files;

	/**
	 * Doc constructor.
	 * @param array $config
	 * @throws ReflectionException
	 */
	public function __construct($config = [])
	{
		if ($config) {
			$this->init($config);
		}
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
	 * 程序入口
	 *
	 * @param $config
	 * @throws ReflectionException
	 * @author mengchenchen
	 */
	public function init($config)
	{
		$key = ['path', 'api_name', 'namespace'];
		array_walk($key, function ($item) use ($config) {
			if (!isset($config[$item])) {
				$this->dd('Doc初始化参数缺失');
			}
			$this->$item = $config[$item];
		});
		$this->scanFile($this->path);
		foreach ($this->files as $file) {
			$class   = new \ReflectionClass($this->namespace . $file);
			$methods = $class->getMethods();
			foreach ($methods as &$property) {
				$comments = explode('@', $property->getDocComment());
				if (isset($comments[1]) && strpos($comments[1], 'Doc') == 3) {
					$mccDoc = str_replace([" ", "　", "\t", "\n", "\r", 'MccDoc', '(', ')', '*'], '', $comments[1]);
					call_user_func([$this, 'encode'], $mccDoc);
				}
			}
		}
	}


	/**
	 * 生成视图文件
	 * 设置路径和名称
	 *
	 * @param $path
	 * @param $name
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
		/**
		 * 接口列表
		 */
		$list = '';
		foreach ($this->params as $group => $item) {
			$list .= '<h3><a name="' . $group . '">' . $group . '</a></h3>';
			$list .= '<table class = "table table-hover">';
			$list .= '<tr>
                        <th width="100px">请求方式</th>
                        <th width="200px">名称</th>
                        <th width="600px">url</th>
                        <th width="80px">操作</th>
                     </tr>';
			foreach ($item as $api) {
				$params = isset($api['params']) ? json_encode($api['params']) : '无';
				$list   .= "<tr>";
				$list   .= '<td>' . $api['method'] . '</td>';
				$list   .= '<td>' . $api['name'] . '</td>';
				$list   .= '<td class="params"><span style="display: none">' . $params . '</span>' . $api['url'] . '</td>';
				$list   .= '<td><button class="btn btn-xs btn-primary">测试</button></td>';
				$list   .= "</tr>";
			}
			$list .= '</table>';
		}
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
    <link href = "../../assets/css/ie10-viewport-bug-workaround.css" rel = "stylesheet">

    <!-- Custom styles for this template -->
    <link href = "navbar-static-top.css" rel = "stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src = "../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script type = "text/javascript" defer = "" async = "" src = "https://track.uc.cn/uaest.js"></script>
    <script src = "../../assets/js/ie-emulation-modes-warning.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src = "https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src = "https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        body {
            font-family: "微软雅黑 Light";
        }
    </style>

</head>

<body>

<!-- Static navbar -->
<nav class = "navbar navbar-default navbar-static-top">
    <div class = "container">
        <div class = "navbar-header">
            <a class = "navbar-brand" href = "#">{$this->api_name}</a>
        </div>
        <div id = "navbar" class = "navbar-collapse collapse">
            <ul class = "nav navbar-nav">
               {$nav}
            </ul>
        </div>
    </div>
</nav>

<div class = "container">
    <div class = "jumbotron">
        <h1>{$this->api_name}</h1>
        <ul>
            <li>当前版本为1.0</li>
            <li>支持请求方式：['get', 'post', 'put', 'delete', 'any', 'patch']</li>
            <li>作者：孟晨晨 邮箱：mandlandc@gmail.com</li>
        </ul>
    </div>
    {$list}
</div>

<script src = "https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src = "https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.bootcss.com/layer/3.1.0/layer.js" type="text/javascript"></script>
<script type='text/javascript'>
  
</script>

<div></div>
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
	 * 解析json并获取参数
	 *
	 * @author mengchenchen
	 */
	public function encode()
	{
		$params = func_get_args()[0];
		if (!$paramsToArr = json_decode($params, true)) {
			$this->dd('Json格式错误' . $params);
		}
		$this->params[isset($paramsToArr['group']) ? $paramsToArr['group'] : '未分组'][] = $paramsToArr;
	}

	/**
	 * 遍历所有Api接口控制器文件
	 *
	 * @param $path
	 * @return array
	 * @author mengchenchen
	 */
	public function scanFile($path)
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
	 * 递归创建目录
	 *
	 * @param $dir
	 * @param int $mode
	 * @return bool
	 * @author mengchenchen
	 */
	function mkdirs($dir, $mode = 0777)
	{
		if (is_dir($dir) || (@mkdir($dir, $mode) && chmod($dir, $mode)))
			return TRUE;
		if (!mkdirs(dirname($dir), $mode))
			return FALSE;

		return @mkdir($dir, $mode);
	}

	/**
	 * 打印并且结束
	 *
	 * @param $params
	 * @author mengchenchen
	 */
	public function dd($params)
	{
		$color      = '#2295bc';
		$background = '#e4e7e7'; ?>
        <pre style = "direction: ltr;background:<?= $background; ?>;color:<?= $color; ?>;
            max-width: 90%;margin: 30px auto;overflow:auto;
            font-family: Monaco,Consolas, 'Lucida Console',monospace;font-size: 16px;padding: 20px;"><?php print_r($params);
		echo "</pre>";
		die;
	}
}