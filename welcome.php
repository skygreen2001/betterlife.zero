<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">    
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Lang" content="zh_CN">
<meta name="author" content="skygreen">
<meta http-equiv="Reply-to" content="skygreen2001@gmail.com">
<meta name="description" content="<?php echo Gc::$site_name?>后台管理">
<meta name="keywords" content="<?php echo Gc::$site_name?>后台管理">
<meta name="creation-date" content="12/01/2010">
<meta name="revisit-after" content="15 days">
<title><?php echo Gc::$site_name?>后台管理</title>
<style type="text/css">
html, body {
	font:normal 12px SimSun,sans-serif;
	margin:0;
	padding:0;
	border:0 none;
	overflow:hidden;
	height:100%;
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
	margin-top: 200px;
}
div#content{
	border-style:outset;
	border-color: black;
	border-width: thin;    
	font-size: 14px;
	width: 300px;
	height: 150px;
	padding-top:50px;
	margin-top: 20px;
}
</style>
<link rel="icon" href="favicon.ico" mce_href="favicon.ico" type="image/x-icon"> 
</head>
<body>
<?php require_once ("Gc.php"); ?>         
<h1 align="center">欢迎来到&nbsp;&nbsp;<span class="en-head"><?php echo Gc::$site_name?></span></h1>
<div align="center">
	<div id="content" align="center">
		<p><a href="<?php echo Gc::$url_base?>index.php?go=betterlife.auth.login">网站前台</a></p>
		<p><a href="<?php echo Gc::$url_base?>index.php?go=admin.index.index">网站后台</a></p> 
	</div>
</div>
</body>
</html>
