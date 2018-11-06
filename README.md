# MccDoc - API文档生成 [![Build Status](https://img.shields.io/travis/Seldaek/monolog.svg)](https://travis-ci.org/Seldaek/monolog)

市面上的接口文档生成都要求 php7+ 以上版本，而且大多数文档生成工具都基于某某个框架，例：
* swgger (php 7+，基于框架)
* apidoc (npm 驱动) 

由于当前公司项目是十年前的项目，php 版本低、结构乱，需要对接的第三方接口较多，此项目也就应运而生了。

MccDoc 的特点在于简单易上手依赖较小，只要保证你的 PHP版本 > 5.4 就可以了。

## 安装

如果你装了 composer ，复制下面这段话就可以获取到最新版本的 MccDoc 了

``` base
$ composer require mengcc/mcc-doc
```

如果你没有配置 composer 也不要紧，点击下面的按钮直接下载项目。

[![Total Downloads](https://img.shields.io/packagist/dt/monolog/monolog.svg)](https://github.com/mengchenchen/MccDoc/archive/master.zip )

## 基本使用
```php

class UserController{

    /**
    * @MccDoc({
    * "name":"获取用户信息",
    * "url":"/api/user/info",
    * "method":"GET",
    * "description":"获取用户信息",
    * })@
    */
    public function info(){}
    
    /**
    * @MccDoc({
    * "name":"编辑用户",
    * "url":"/api/user/edit",
    * "method":"POST",
    * "description":"编辑用户",
    * "params":{
    *    "name":"小明",
    *    "age":20
    *  },
    * })@
    */
    public function edit(){}
}
```
demo.php
```php
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
```

## 参数

```php
/**
* @MccDoc({
* "name":"接口名称",
* "url":"接口地址",
* "method":"请求方式",
* "description":"描述",
* "params":{
*    "order_ids":"订单id集合"
*  },
* })@
*/
```

| 参数        | 是否必填 | 描述                                                         |
| ----------- | -------- | ------------------------------------------------------------ |
| name        | 是       | 接口名称，方便阅读                                           |
| url         | 是       | 请求地址，提供直接发送请求测试                               |
| method      | 是       | HTTP请求方式，支持 ['get', 'post', 'put', 'delete', 'any', 'patch'] |
| description | 否       | 针对接口进行描述                                             |
| params      | 否       | 发送请求捎带的数据参数，格式 JSON,{"参数名称":"参数介绍",....} |

