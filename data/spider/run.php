<?php
header("Content-Type:text/html; charset=UTF-8");
require_once("init.php");  
// Place_China::place();

// echo Data_Take_Normal::getUserAgent();
// $content=Data_Take_Normal::getHtmlContent("http://shanghai.anjuke.com/v2/sale/W0QQdsmZmmQQpZ1QQp1Z10");

//$content=Data_Take_Normal::getHtmlContent_fopen("http://www.dianping.com/citylist"); 

//$content=Data_Take_Normal::getHtmlContent_byDir("D:\\test.txt","D:\\wamp\\www\\apts\\core\\db");

//$content=Data_Take_Normal::getHtmlContent_bySocket("fang5/","sh.ganji.com");
//$content=Data_Take_Normal::get_content_url("http://www.dianping.com/citylist",$content);
// echo $content;

//echo(Data_Take_Normal::getHtmlContent_Curl("http://www.dianping.com/shanghai/food"));
// echo(Data_Take_Normal::getHtmlContent_Curl("http://www.google.com.hk/"));
//http://www.dianping.com/shanghai/food

data_take_normal();
die();
function data_take_normal() {
    $items=Data_SimpleHtmlDom::sample_beimai();
    UtilCss::report_info();
    echo "<table class=".UtilCss::CSS_REPORT_TABLE.">";
    echo "<tr>";
    echo "<td>";
    echo "图片";
    echo "</td>";
    echo "<td>";
    echo "名称";
    echo "</td>";
    echo "<td>";
    echo "链接";
    echo "</td>";
    echo "</tr>";
    foreach ($items as $item) {
        echo "<tr>";
        echo "<td>";
        echo "<img alt='' src='".$item->getImgUrl()."' />";
        echo "</td>";
        echo "<td>";
        echo $item->getName();
        echo "</td>";
        echo "<td>";
        echo "<a href='".$item->getDetailUrl()."' target='_blank'>详情</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

?>