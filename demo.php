<?php
include './vendor/autoload.php';

$doc = new \Mengcc\Doc([
	'path'      => './tests/Controllers/Api',
	'api_name'  => 'mcc',
	'namespace' => '\Mengcc\Tests\Controllers\Api\\',
]);

echo "<pre>";
var_dump($doc->getFiles());
var_dump($doc->getAllData());