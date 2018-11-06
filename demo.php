<?php
header("Content-Type:text/html;charset=utf-8");
include './vendor/autoload.php';

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


