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

    <title>孟晨晨自动文档生成器</title>

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
            <a class = "navbar-brand" href = "#">MccDoc接口文档</a>
        </div>
        <div id = "navbar" class = "navbar-collapse collapse">
            <ul class = "nav navbar-nav">
				<?php
				foreach (array_keys($records) as $item)
					echo '<li><a href = "#' . $item . '">' . $item . '</a></li>';
				?>
                <li>
                    <a href = "#">更多...</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class = "container">


    <div class = "jumbotron">
        <h1>灯保姆对接外部接口文档</h1>
        <p>灯保姆对接外部接口文档</p>
        <ul>
            <li>控制器目录：dengbaomu_gitee/app/Http/Controllers/Api</li>
            <li>支持请求方式：['get', 'post', 'put', 'delete', 'any', 'patch']</li>
            <li>作者：孟晨晨 邮箱：mandlandc@gmail.com</li>
        </ul>
        <p>
            <a class = "btn btn-lg btn-primary" href = "#navbar" role = "button">开始</a>
        </p>
    </div>

	<?php
	foreach ($records as $k => $v) {
		echo '<h3><a name="' . $k . '">' . $k . '</a></h3>';
		echo '<div class = "panel-group" id = "accordion">';
		foreach ($v as $m) {
			$url = 'http://' . $_SERVER['HTTP_HOST'] . $m['url'];
			echo '<div class = "panel panel-default">';
			echo '<div class = "panel-heading">';
			echo '<h4 class = "panel-title">';
			echo '<span class = "label ' . $methods_color[$m['method']] . '" style="width: 60px;display: inline-block;height: 23px;line-height: 20px;">' . $m['method'] . '</span>&nbsp;';
			echo '<a data-toggle = "collapse" data-parent = "#accordion" href = "#' . $m['name'] . '">';
			echo $m['name'] . '</a>';
			echo '</h4>';
			echo '</div>';
			echo '<div id = "' . $m['name'] . '" class = "panel-collapse collapse">';
			echo '<div class = "panel-body">';
			if (isset($m['params'])) {
				echo '<div class="panel panel-default">';
				echo '<div class="panel-heading">请求地址：' . $url . '</div>';
				echo '<div class="panel-body">';
				echo '<form class="form-horizontal" action="' . $url . '" method="' . $m['method'] . '">';
				foreach ($m['params'] as $key => $value) {
					echo '<div class="form-group">';
					echo '<label class="col-sm-2 control-label">' . $key . '</label>';
					echo '<div class="col-sm-10">';
					if (is_array($value)) {
						echo '<textarea class="form-control" name="" id="" cols="30" rows="10">';
						echo json_encode($value, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
						echo '</textarea>';
					} else {
						echo '<input type="text" class="form-control"">';
						echo '<span id="helpBlock2" class="help-block">' . $value . '</span>';
					}
					echo '</div></div>';
				}
				echo '<div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-default ajaxSubmit">submit</button>
                        </div>
                      </div>';
				echo '</form>';
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	?>
</div>

<script src = "https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src = "https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type = "text/javascript" src = "http://malsup.github.io/jquery.form.js"></script>
<script>
    $(function () {
        $('.ajaxSubmit').each(function () {
            $(this).click(function () {
                $(this).ajaxSubmit({
                    beforeSubmit: function () {
                        alert("我在提交表单之前被调用！");
                    },
                    success: function (data) {
                        //alert("我在提交表单成功之后被调用");
                        if (typeof(data) == "string") {
                            data = eval('(' + data + ')');
                            //alert(data); object
                            handle(data);
                        } else {
                            handle(data);
                        }

                    }
                });
            });
        });
    });

    //处理返回数据
    function handle(data) {
        if (data.status == 200) {
            alert(data.message);
            //处理逻辑
        } else {
            alert(data.message);
            //处理逻辑
        }
    }
</script>
<div></div>
</body>
</html>