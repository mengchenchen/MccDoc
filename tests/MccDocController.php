<?php
/**
 * Created by PhpStorm.
 * User: Mengchenchen
 * Date: 2018/8/14/014
 * Time: 11:35
 * Blog: https://mengchenchen.github.io
 */

namespace app\Http\Controllers;

use app\Http\Controller;
use app\Http\View;

class MccDocController extends Controller
{

	protected $files;

	public function index()
	{
		$this->view = View::make('Doc.doc')->fetch_with([
			'records'       => $this->getMccDocComment(),
			'methods_color' => [
				'PATCH'  => 'label-default',
				'GET'    => 'label-primary',
				'POST'   => 'label-success',
				'PUT'    => 'label-info',
				'ANY'    => 'label-warning',
				'DELETE' => 'label-danger',
			],
		]);
	}

	/**
	 * 获取所有注释文档
	 *
	 * @return array
	 * @throws \ReflectionException
	 * @author mengchenchen
	 */
	public function getMccDocComment()
	{
		$config = config('api');
		//		dpre($config);
		$all_params = [];
		$files      = $this->scanFile(BASE_PATH . $config['path']);
		foreach ($files as $file) {
			$class = new \ReflectionClass($config['namespace'] . $file);
			if (!$api_name = $class->getStaticPropertyValue('API_NAME'))
				dpre('缺少参数 API_NAME');
			$methods = $class->getMethods();
			foreach ($methods as &$property) {
				$comments = explode('@', $property->getDocComment());
				if (isset($comments[1]) && strpos($comments[1], 'Doc') == 3) {
					$mccDoc = str_replace([" ", "　", "\t", "\n", "\r", 'MccDoc', '(', ')', '*'], '', $comments[1]);
					call_user_func(function ($params) use (&$all_params, $api_name) {
						if (!$paramsToArr = json_decode($params, true))
							dpre('Json格式错误' . $params);
						$all_params[$api_name][] = $paramsToArr;
					}, $mccDoc);
				}
			}
		}
		return $all_params;
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
		$result = [];
		$files  = scandir($path);
		foreach ($files as $file) {
			if ($file != '.' && $file != '..') {
				if (is_dir($path . '/' . $file))
					$this->scanFile($path . '/' . $file);
				else
					if (strpos(basename($file), 'Controller') > 0)
						$result[] = str_replace(['.php'], '', basename($file));
			}
		}
		return $result;
	}
}