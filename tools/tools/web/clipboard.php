<?php
/**
 * 剪贴板小工具 
 */
require_once ("../../../init.php");                   
if (isset($_REQUEST["s"]))
{    
	echo file_get_contents(Gc::$upload_path."clipboard.txt");
}else if (isset($_POST["content"])&&!empty($_POST["content"]))
{
	file_put_contents(Gc::$upload_path."clipboard.txt",$_POST["content"]);
	echo  $_POST["content"];
	echo "<br/><a href='".Gc::$url_base."clipboard.php"."'>继续</a><br/>";
}else {
	echo  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		   <html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">';
	echo "<head>\r\n";     
	echo UtilCss::form_css()."\r\n";
	$url_base=UtilNet::urlbase();
	echo "</head>";     
	echo "<body>";   
	echo "<h1 align='center'>剪贴板</h1>";
	echo "<div align='center' height='450'>";
	echo "<form  method ='post'>";  
	echo "  <div style='line-height:1.5em;'>";   
	echo "      <label style='padding-bottom:345px;'>剪切内容:</label><textarea name=\"content\" style=\"width: 690px; height: 372px;\"></textarea><br/><br/>";
	echo "  </div>";
	echo "  <input type=\"submit\" value='生成' /><br/>";
	echo "</form>";
	echo "</div>";
	echo "</body>";      
	echo "</html>";  
}    
?>
