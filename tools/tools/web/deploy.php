<?php
require_once("../../../init.php");
//header("Content-Type:text/html; charset=UTF-8");
$contents=file_get_contents("../../../welcome.php");
//$contents=str_replace("\r\n","<br/>",$contents);
if(startWith($contents,"<?php")){
    if(isset($_GET["recover"])){   
        $contents=substr($contents,stripos($contents,"?>")+2);
        $contents=ltrim($contents);
        if ($contents)file_put_contents("../../../welcome.php",$contents);
        echo "还原成功！显示welcome页面！<br/>";
        echo "<a href=\"".Gc::$url_base."tools/tools/web/deploy.php\">线上部署</a><br/>";
    }else{  
        echo "<a href=\"".Gc::$url_base."tools/tools/web/deploy.php?recover=yes\">还原</a>【至开发导航首页】<br>";
    }
    echo "<a href=\"".Gc::$url_base."\" target='_blank'>返回首页</a><br/>";
}else{
    $redirect_head=<<<REDIRECT_HEAD
<?php
require_once ("Gc.php");
header("location:".Gc::\$url_base."index.php?go=".Gc::\$appName.".index.index");
die();
?>

REDIRECT_HEAD;
    $contents=$redirect_head.$contents;
    file_put_contents("../../../welcome.php",$contents);
    //echo $contents;
    echo "部署成功，不再显示welcome页面，直接跳转到".Gc::$site_name."首页<br/>";
    echo "<a href=\"".Gc::$url_base."tools/tools/web/deploy.php?recover=yes\">还原</a>【至开发导航首页】<br>";
    echo "<a href=\"".Gc::$url_base."\" target='_blank'>返回首页</a><br/>";
}

?>
