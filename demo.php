<?php

include './vendor/autoload.php';

// 可以设置编码
header("Content-Type:text/html;charset=utf-8");

/**
 * path：将要扫描的控制器路径
 * api_name: 设置要生成的文档名称
 * namespace：设置要反射的命名空间
 */
$doc = new \Mengcc\Doc([
	'path'      => './tests/Controllers/Api',
	'api_name'  => 'mcc',
	'namespace' => '\Mengcc\Tests\Controllers\Api\\',
]);

// 获取所有需要解析的参数
$doc->getAllData();
// echo(json_encode($doc->getAllData()));

// 获取所有文件信息
$doc->getFiles();
// echo(json_encode($doc->getFiles()));

// 生成静态 html文件，生成到 D 根目录下，命名为 mcc-doc
$doc->view('D://', 'mcc-doc');


