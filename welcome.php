<?php
require_once ("Gc.php");
require_once("core/include/common.php");
if(!contains($_SERVER['HTTP_HOST'],array("127.0.0.1","localhost"))){
    header("location:".Gc::$url_base."index.php?go=".Gc::$appName.".index.index");
    die();
}
?>
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
    font-size: 13px;
    font-family:'Microsoft YaHei',"微软雅黑",Arial, sans-serif,'Open Sans';
    margin:0;
    padding:0;
    border:0 none;
}
p {
    margin:5px;
}
.en{
    font-family:Arial,verdana,Geneva,Helvetica,sans-serif;
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
    margin: 150px 0 0 0;
}
#main {
    width: 100%;
    height: 100%;
    align: center;
}
#inbox {
    width: 480px;
    margin: 0 auto;
}
#footnav {
    width: 300px;
    text-align: center;
    margin: 10px auto 0px auto;
}
div#content{
    border-style:outset;
    border-color: black;
    border-width: thin;
    font-size: 16px;
    width: 480px;
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
            <h1 align="center">欢迎来到<span class="en-head"><?php echo Gc::$site_name ?></span>网站框架</h1>
            <div align="center">
                <div id="content" align="center">
                    <p><a target="_blank" href="<?php echo Gc::$url_base?>index.php?go=<?php echo Gc::$appName ?>.index.index">网站前台</a></p>
                    <p><a target="_blank" href="<?php echo Gc::$url_base?>index.php?go=admin.index.index">网站后台</a></p>
                    <p><a target="_blank" href="<?php echo Gc::$url_base?>index.php?go=model.index.index">通用模板</a></p>
                </div>
            </div>
        </div>
        <div id="footnav"><?php $help_url="http://skygreen2001.gitbooks.io/betterlife-cms-framework/content/index.html" ?>
            <a href="<?php echo Gc::$url_base?>tools/dev/index.php" target="_blank">工程重用</a>|<a href="<?php echo Gc::$url_base?>tools/tools/db/manual/db_normal.php" target="_blank">数据库说明书</a>|<a href="<?php echo Gc::$url_base?>tools/tools/autocode/db_onekey.php" target="_blank">一键生成</a>|<a href="<?php echo $help_url ?>" target="_blank">帮助</a>
        </div>
    </div>
</body>
</html>
