<?php
require_once ("../../../init.php");                         
$isUpdate=false;
if (isset($_REQUEST["detect_dir"])&&!empty($_REQUEST["detect_dir"]))
{
    $detect_dir=$_REQUEST["detect_dir"];
    $files=UtilFileSystem::getAllFilesInDirectory($detect_dir,array("php","txt","srt"));
    if(array_key_exists("checkType",$_GET)&&($_GET["checkType"]=="2"))$isUpdate=true;
    foreach ($files as $file) {
        $contents=file_get_contents($file);
        $encode = mb_detect_encoding($contents, array("ASCII","GB2312","GBK","BIG5","UTF-8")); 
        if ($encode != "UTF-8"){
            echo $file."<br/>"; 
            if ($isUpdate){
                if($encode==false){
                    $contents = mb_convert_encoding( $contents, 'UTF-8','Unicode'); 
                }else{
                    $contents= iconv($encode,"UTF-8",$contents);
                }
                $isGood=file_put_contents($file,$contents); 
            }
        } 
    }

}else{
    $inputArr=array(
         "1"=>"否",
         "2"=>"是"
    );

    $title="检查文件内容是否utf-8格式";
    /**
     * javascript文件夹选择框的两种解决方案,这里选择了第一种
     * @link http://www.blogjava.net/supercrsky/archive/2008/06/17/208641.html
     */
    echo  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
            <html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">';
    echo "<head>\r\n";
    echo UtilCss::form_css()."\r\n";
    $url_base=UtilNet::urlbase();
    //echo "<script type='text/javascript' src='".$url_base."common/js/util/file.js'></script>";
    echo "</head>";
    echo "<body>";
    echo "<br/><br/><br/><br/><br/><h1 align='center'>$title</h1>";
    echo "<div align='center' height='450'>";
    echo "<form>";
    echo "  <div style='line-height:1.5em;'>";
    $default_dir=Gc::$upload_path;
    echo "      <label>输出文件路径:</label><input style=\"width:400px;text-align:left;padding-left:10px;\" type=\"text\" name=\"detect_dir\" value=\"$default_dir\" id=\"detect_dir\" />";
    /*        $browser=getbrowser();
    if (contain($browser,"Internet Explorer")){
        echo "            <input type=\"button\" onclick=\"browseFolder('save_dir')\" value=\"浏览...\" /><br/><br/>";
    }*/
    if (!empty($inputArr)){
        echo "<br/><br/>
                <label>&nbsp;&nbsp;&nbsp;是否文件内容转换成utf-8</label><select name=\"checkType\">";
        foreach ($inputArr as $key=>$value) {
            echo "        <option value='$key'>$value</option>";
        }
        echo "      </select>";
    }
    echo "  </div>";
    echo "  <input type=\"submit\" value='检查' /><br/>";

    /*        if (contain($browser,"Internet Explorer")){
        echo "  <p id='indexPage'>说明： <br/>
                * 可手动输入文件路径，也可选择浏览指定文件夹。<br/>
                * 如果您希望选择指定文件夹，特别注意的是,由于安全方面的问题,你还需要如下设置才能使本JS代码正确运行,否则会出现\"没有权限\"的问题。<br/>
                1.设置可信任站点（例如本地的可以为：http://localhost）<br/>
                2.其次：可信任站点安全级别自定义设置中：设置下面的选项<br/>
                \"对没有标记为安全的ActiveX控件进行初始化和脚本运行\"----\"启用\"</p>";
    }*/
    echo "</form>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
}
?>
