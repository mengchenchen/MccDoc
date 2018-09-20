# MccDoc - API文档生成 [![Build Status](https://img.shields.io/travis/Seldaek/monolog.svg)](https://travis-ci.org/Seldaek/monolog)

市面上的接口文档生成都要求php7.0以上版本，而且大多数都基于某某个框架，比如laravel、CI、YII等等。由于当前公司项目是十年前的项目，php版本低、结构乱，需要对接的第三方接口较多，此项目也就应运而生了。

MccDoc的特点在于简单易上手依赖较小，只要保证你的 PHP版本 > 5.4 就可以了。

## 安装

如果你装了composer，复制下面这段话就可以获取到最新版本的MccDoc了

``` base
$ composer require mengchenchen/MccDoc
```

如果你没有配置composer也不要紧，点击下面的按钮直接下载项目。

[![Total Downloads](https://img.shields.io/packagist/dt/monolog/monolog.svg)](https://github.com/mengchenchen/MccDoc/archive/master.zip )

## 基本使用

演示：首先我们在 app/controllers/UserController.php 创建文件

```php
namespace app\Controllers;
class UserController{
    public static $API_NAME = '用户中心';
    /**
    * @MccDoc({
    * "name":"获取用户信息",
    * "url":"/api/user/info",
    * "method":"GET",
    * "description":"获取用户信息",
    * })@
    */
    public function info(){
        ...
    }
    
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
    public function edit(){
        $name = $_POST['name'];
        $age = $_POST['age'];
        ....
    }
}
```

然后在你需要生成文档的程序中加入

```php
// 这里我们需要首先引入MccDoc类
// 如果你使用了composer,并确保require autoload用直接使用use
// use Mengchenchen/MccDoc;
// 如果你没有使用Composer,用下面的这个方法引入MccDoc所在路径
require 'MccDoc/MccDoc.php';

$mccdoc = new MccDoc();
// 设置控制器的目录，将会遍历该目录下的所有php文件
$mccdoc->path = 'app/controllers';
// 文档的展示页面
$mccdoc->display = 'app/views/doc.php';
```

在浏览器访问文件查看效果

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

