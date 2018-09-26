<?php
header("Content-Type:text/html;charset=utf-8");
include './vendor/autoload.php';

$doc = new \Mengcc\Doc([
	'path'      => './tests/Controllers/Api',
	'api_name'  => 'mcc',
	'namespace' => '\Mengcc\Tests\Controllers\Api\\',
]);

$doc->view('D://');
echo(json_encode($doc->getAllData()));