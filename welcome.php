<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Lang" content="zh_CN">
<meta name="author" content="skygreen">
<meta http-equiv="Reply-to" content="skygreen2001@gmail.com">
<?php require_once ("Gc.php");?>
<meta name="description" content="<?php echo Gc::$site_name?>">
<meta name="keywords" content="<?php echo Gc::$site_name?>">
<meta name="creation-date" content="12/01/2010">
<meta name="revisit-after" content="15 days">
<title><?php echo Gc::$site_name ?></title>
<style type="text/css">
body {
	font:normal 12px SimSun,sans-serif;
	margin:0;
	padding:0;
	border:0 none;
}
p {
	margin:5px;
}
.en-head{
	font:bold 0.8em Arial,verdana,Geneva,Helvetica,sans-serif;
}
.en{
	font-family:verdana,Geneva,Helvetica,Arial,sans-serif;
}

a {
	color: #1E4176;
}
a:link {
	color: #1E4176;
	text-decoration: none;
}

a:visited {
	color: #555;
	text-decoration: none;
}

a:hover {
	text-decoration: underline;
	color: #15428b;
}
h1{
	font:bold 200% STXingkai;
	margin-top: 160px;
}
#main {
    position: absolute;
    top: 50%;
    left: 50%;
    align: center;
}
#inbox {
    position: absolute;
    top: -50%;
    width: 360px;
    margin: -330px -180px;
}
#footnav {
	position: absolute;
	top: -50%;
	width: 300px;
	margin: 110px 0px 0px -120px;
}
div#content{
	border-style:outset;
	border-color: black;
	border-width: thin;
	font-size: 14px;
	width: 360px;
	height: 150px;
	padding-top:80px;
	margin-top: 20px;
}
</style>
<link rel="icon" href="favicon.ico" mce_href="favicon.ico" type="image/x-icon">
</head>
<body>
	<div id="main">
		<div id="inbox">
			<h1 align="center">欢迎来到&nbsp;&nbsp;<span class="en-head"><?php echo Gc::$site_name ?></span></h1>
			<div align="center">
				<div id="content" align="center">
					<p><a target="_blank" href="<?php echo Gc::$url_base?>index.php?go=<?php echo Gc::$appName ?>.index.index">网站前台</a></p>
					<p><a target="_blank" href="<?php echo Gc::$url_base?>index.php?go=admin.index.index">网站后台</a></p>
					<p><a target="_blank" href="<?php echo Gc::$url_base?>index.php?go=model.index.index">通用模板</a></p>
				</div>
			</div>
		</div>
		<div id="footnav"><?php $help_url="http://skygreen2001.gitbooks.io/betterlife-cms-framework/content/index.html" ?>
			<a href="<?php echo Gc::$url_base?>tools/dev/index.php" target="_blank">工程重用</a>|<a href="<?php echo Gc::$url_base?>tools/tools/db/manual/db_normal.php" target="_blank">数据库说明书</a>|<a href="<?php echo Gc::$url_base?>tools/tools/autoCode/db_onekey.php" target="_blank">一键生成</a>|<a style="cursor:pointer;" onclick="if (confirm('确认需要一键部署，该操作不可还原！')==true){window.location.href='<?php echo Gc::$url_base?>tools/tools/web/deploy.php'}" target="_blank">线上部署</a>|<a href="<?php echo $help_url ?>" target="_blank">帮助</a>
		</div>
	</div>
</body>
</html>
