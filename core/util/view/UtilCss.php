<?php
/**
 +--------------------------------<br/>
 * 定义样式<br/>
 +--------------------------------
 * @category betterlife
 * @package util.view
 * @author skygreen
 */
class UtilCss extends Util
{
    /**
     * 对CSS进行Gzip操作的路径
     * @var string
     */
    private static $CSS_GZIP="misc/js/gzip.php?css=";
    /**
     * JS框架名称键名称
     * @var string
     */
    private static $CSS_FLAG_GROUP="g";
    /**
     * JS框架版本键名称
     * @var type
     */
    private static $CSS_FLAG_VERSION="v";
    /**
     * 加载过的css文件。
     * $value css文件名
     * @var array
     */
    public static $CssLoaded=array();

    public static $color_b = "#77cc6d";

    const CSS_REPORT_TABLE="report";
    /**
     * 动态加载Ext Required CSS文件
     * @param ViewObject $viewobject 表示层显示对象
     * @param string $version javascript框架的版本号
     */
    public static function loadExt($viewObject=null,$version="3.3.0")
    {
        UtilAjax::init();
        $g_flag_ext=EnumJsFramework::JS_FW_EXTJS;
        $ext_resource_root="resources/css/";
        if ($viewObject){
            self::loadCssReady($viewObject,$ext_resource_root."ext-all.css",true,$g_flag_ext,$version);
            self::loadCssReady($viewObject,$ext_resource_root."xtheme-gray.css",true,$g_flag_ext,$version);
            self::loadCssReady($viewObject,$ext_resource_root."ext-patch.css",true,$g_flag_ext,$version);
            self::loadCssReady($viewObject,"shared/tabscroller/TabScrollerMenu.css",true,$g_flag_ext,$version);
        }else{
            self::loadCss($ext_resource_root."ext-all.css",true,$g_flag_ext,$version);
            self::loadCss($ext_resource_root."xtheme-gray.css",true,$g_flag_ext,$version);
            self::loadCss($ext_resource_root."ext-patch.css",true,$g_flag_ext,$version);
            self::loadCss("shared/tabscroller/TabScrollerMenu.css",true,$g_flag_ext,$version);
        }
    }

    /**
     * 动态加载CSS文件
     * @param string $cssFile Css文件名
     * @param string $cssFlag 分组标识，如都是Ext的Css文件
     * @param string $version javascript框架的版本号
     * @param string $charset 字符集
     */
    public static function loadCss($cssFile,$isGzip=false,$cssFlag=null,$version="",$charset="utf-8")
    {
        echo self::loadCssSentence($cssFile,$isGzip,$cssFlag,$version,$charset);
    }

    /**
     * 预加载[不直接输出]:动态加载CSS文件
     * @param ViewObject $viewobject 表示层显示对象
     * @param string $cssFile Css文件名
     * @param string $cssFlag 分组标识，如都是Ext的Css文件
     * @param string $version javascript框架的版本号
     * @param string $charset 字符集
     */
    public static function loadCssReady($viewobject,$cssFile,$isGzip=false,$cssFlag=null,$version="",$charset="utf-8")
    {
        if ($viewobject instanceof ViewObject){
            if (!isset($viewobject->css_ready)||empty($viewobject->css_ready)){
                $viewobject->css_ready="";
            }
            $viewobject->css_ready.=self::loadCssSentence($cssFile,$isGzip,$cssFlag,$version,$charset);
        }
    }

    /**
     * 动态加载CSS文件的语句
     * @param string $cssFile Css文件名
     * @param string $cssFlag 分组标识，如都是Ext的Css文件
     * @param string $version javascript框架的版本号
     * @param string $charset 字符集
     */
    public static function loadCssSentence($cssFile,$isGzip=false,$cssFlag=null,$version="",$charset="utf-8")
    {
        $result="";
        if (isset($cssFile)){
            $url_base=UtilNet::urlbase();
            if ($isGzip){
                if (isset($cssFlag)){
                    $cssFile.="&".self::$CSS_FLAG_GROUP."=".$cssFlag;
                }
                if (!empty($version)){
                    $cssFile.="&".self::$CSS_FLAG_VERSION."=".$version;
                }
                if (in_array($cssFile, self::$CssLoaded)){
                    return ;
                }
                $css_gzip=self::$CSS_GZIP;
                if (contain($cssFile,Gc::$url_base)){
                    $file_sub_dir=str_replace("/", DS, dirname($_SERVER["SCRIPT_FILENAME"])).DS;
                    if(contain($file_sub_dir,Gc::$nav_root_path)){
                        $isLocalCssFile=str_replace(Gc::$url_base,Gc::$nav_root_path,$cssFile);
                    }else{
                        $isLocalCssFile=str_replace(Gc::$url_base,$file_sub_dir,$cssFile);
                    }

                    if (is_server_windows()){
                        $isLocalCssFile=str_replace("/","\\",$isLocalCssFile);
                    }
                    if (contain($isLocalCssFile,"resources".DIRECTORY_SEPARATOR."css")){
                        $isLocalCssFile=substr($isLocalCssFile,0,strpos($isLocalCssFile,"resources".DIRECTORY_SEPARATOR."css")+9);
                    }
                    $isLocalGzip=$isLocalCssFile.DIRECTORY_SEPARATOR."gzip.php";
                    if (file_exists($isLocalGzip)){
                        if(contain($file_sub_dir,Gc::$nav_root_path)){
                            $css_gzip=str_replace(Gc::$nav_root_path,"",$isLocalGzip)."?css=";
                        }else{
                            $css_gzip=str_replace($_SERVER["DOCUMENT_ROOT"]."/","",$isLocalGzip)."?css=";
                        }
                        $css_gzip=str_replace("\\","/",$css_gzip);
                    }
                }else{
                    if (contain(strtolower(php_uname()),"darwin")){
                        $file_sub_dir=str_replace("/", DS, dirname($_SERVER["SCRIPT_FILENAME"])).DS;
                        $css_gzip=str_replace($_SERVER["DOCUMENT_ROOT"]."/", "", $file_sub_dir).$css_gzip;
                        $start_str=substr($css_gzip, 0,strpos($css_gzip, "/"));
                        $url_basei=substr($url_base, 0,strlen($url_base)-1);
                        $end_str=substr($url_basei,strrpos($url_basei, "/")+1);
                        if($start_str==$end_str)$css_gzip=str_replace($end_str."/","",$css_gzip);
                    }
                }
                $result= "     <link rel=\"stylesheet\" type=\"text/css\" href=\"".$url_base.$css_gzip.$cssFile."\" />\r\n";
            }else{
                if (in_array($cssFile, self::$CssLoaded)){
                    return ;
                }
                if (startWith($cssFile, "http")){
                    $result= "     <link rel=\"stylesheet\" type=\"text/css\" href=\"".$cssFile."\" />\r\n";
                }else{
                    if (contain(strtolower(php_uname()),"darwin")){
                        $file_sub_dir=str_replace("/", DS, dirname($_SERVER["SCRIPT_FILENAME"])).DS;
                        $cssFile=str_replace($_SERVER["DOCUMENT_ROOT"]."/", "", $file_sub_dir).$cssFile;

                        $start_str=substr($cssFile, 0,strpos($cssFile, "/"));
                        $url_basei=substr($url_base, 0,strlen($url_base)-1);
                        $end_str=substr($url_basei,strrpos($url_basei, "/")+1);
                        if($start_str==$end_str)$cssFile=str_replace($end_str."/","",$cssFile);
                    }
                    $result= "     <link rel=\"stylesheet\" type=\"text/css\" href=\"".$url_base.$cssFile."\" />\r\n";
                }
            }
            self::$CssLoaded[]=$cssFile;
        }
        return $result;
    }

    /**
     * 动态加载应用指定的Css内容的语句。
     * @param string $cssContent：Css内容的语句
     */
    public static function loadCssContent($cssContent)
    {
        echo self::loadCssContentSentence($cssContent);
    }

    /**
     * 预加载[不直接输出]:动态加载应用指定的Css内容的语句。
     * @param ViewObject $viewobject 表示层显示对象,只在Web框架中使用,一般结合loadJsReady使用
     * @param string $cssContent：Js内容的语句
     */
    public static function loadCssContentReady($viewobject,$cssContent)
    {
        if ($viewobject instanceof ViewObject){
            if (!isset($viewobject->css_ready)||empty($viewobject->css_ready)){
                $viewobject->css_ready="";
            }
            $viewobject->css_ready.=self::loadCssContentSentence($cssContent);
        }
    }

    /**
     * 动态加载应用指定的Js内容的语句。
     * @param string $cssContent：Css内容的语句
     */
    public static function loadCssContentSentence($cssContent)
    {
        $result="     <style type=\"text/css\">\r\n";
        $result.="        ".$cssContent."\r\n";
        $result.="     </style>\r\n";
        return $result;
    }

    /**
     * 列表信息样式报表
     * @see http://www.somacon.com/p141.php
     * {self::CSS_REPORT_TABLE}
     */
    public static function report_info()
    {
        echo '<style type="text/css">
            table.'.self::CSS_REPORT_TABLE.' {
                border: 1px solid '. self::$color_b .';
                background-color: #fff;
                margin:0 auto;
            }
            table.'.self::CSS_REPORT_TABLE.' th {
                padding: 5px;
                border-right:1px solid '. self::$color_b .';
                border-bottom:1px solid '. self::$color_b .';
            }
            table.'.self::CSS_REPORT_TABLE.' td {
                padding: 5px 10px;
                border-right:1px solid '. self::$color_b .';
                border-bottom:1px solid '. self::$color_b .';
                word-break: break-all;
            }
            table.'.self::CSS_REPORT_TABLE.' th:last-child, table.'.self::CSS_REPORT_TABLE.' td:last-child {
                border-right: 0px;
            }
            tbody table.'.self::CSS_REPORT_TABLE.' tr:last-child td {
                border-bottom: 0px;
            }
            </style>';
    }

    /**
     * 代码生成预览样式报表
     */
    public static function preview_report_info()
    {
        echo '<style type="text/css">
            table {
                border-collapse: separate;
                border-spacing: 0;
            }
            table, td, th {
                vertical-align: middle;
                padding: 6px 0px;
            }
            table.preview {
                border-collapse: collapse;
                margin-bottom: 1.4em;
                width: 80%;
            }
            table.preview th {
                text-align: center;
                font-weight: bold;
            }
            table.preview td {
                text-align:center;
                padding: 6px 12px;
            }
            table.preview, table.preview th,table.preview td {
                border: 1px solid '. self::$color_b .';
            }
            table.preview td:first-child {
                min-width: 90px;
                padding: 6px 0px;
            }
            table.preview td:last-child {
                min-width: 160px;
                padding: 6px 0px;
            }
            table.preview a:hover{
                padding-bottom: 2px;
                border-bottom: 1px solid '. self::$color_b .';
            }
        </style>';
    }

    /**
     * 定义form的样式
     */
    public static function form_css()
    {
        $showResult = '<style type="text/css">
                html,body {
                  font:normal 15px SimSun,sans-serif;
                  border:0 none;
                  overflow:auto;
                  line-height:1.5em;
                  margin:5px 0 0;
                  padding:0;
                }
                .container {
                  width:800px;
                  margin:0 auto;
                }
                h1,h2,h3 {
                  font:bold 150% Arial,sans-serif,Microsoft YaHei UI,Microsoft YaHei, SimSun,sans-serif,STXihei;
                }
                h1{
                  margin-top: 150px;
                }
                p#indexPage {
                  line-height:2em;
                  width:500px;
                  padding-left:30px;
                  text-align:left;
                }
                a {
                  color:#555;
                  cursor: pointer;
                }
                a:link {
                  text-decoration: none;
                }
                a:visited {
                  text-decoration: none;
                }
                a:hover, a:focus {
                  color:'. self::$color_b .';
                }
                .after_link{
                  margin-left: -25px;
                }
                input,button,select,textarea{
                  outline: none;
                }
                input[type="checkbox"]{
                  cursor: pointer;
                }
                form{
                  line-height:1.5em;
                }
                label {
                  vertical-align:middle;
                  width:200px;
                  height:35px;
                  text-align:right;
                  display:inline-block;
                  margin:32px 16px 6px;
                }
                form#autocodeForm label.mode{
                  margin-top: 0px;
                }
                form#autocodeForm select{
                  margin-top: -20px;
                }
                input[type=text],input[type=password]{
                  border:1px solid #fff;
                  text-align:center;
                  width:200px;
                  height:28px;
                  line-height:28px;
                  color:white;
                  background:gray;

                  margin:10px 0px 0px 0px;
                  font-size: 14px;
                  color: #555;
                  vertical-align: middle;
                  background-color: #fff;
                  border: 1px solid #ccc;
                  border-radius: 4px;
                  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
                  box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
                  -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
                  transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
                }
                input.input_save_dir{
                  width:412px;
                  text-align:left;
                  padding-left:10px;
                }
                select{
                  width:423px;
                  text-align:left;
                  padding:6px 0px 4px 10px;
                  font-size:14px;
                  height:28px;
                  line-height:28px;
                  vertical-align:bottom;
                  box-sizing: content-box;
                  -moz-box-sizing:content-box;
                  -webkit-box-sizing:content-box;
                  margin:0px;
                  font-size: 14px;
                  color: #555;
                  vertical-align: middle;
                  background-color: #fff;
                  border: 1px solid #ccc;
                  border-radius: 4px;
                  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
                  box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
                  -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
                  transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
                }
                input[type=button]{
                  border:1px solid;
                  text-align:center;
                  width:80px;
                  height:28px;
                }
                input:hover {
                  border:1px solid '. self::$color_b .';
                  color:#000;
                  background:#FFF;
                }
                .btnSubmit{
                  margin-top:15px;
                  width:126px;
                  height:38px;
                  color:#FFF;
                  cursor:pointer;

                  font-size: 15px;
                  font-weight: 600;
                  background-color:'. self::$color_b .';
                  border:1px solid;
                  border-color:#fff;
                  border-radius: 6px;
                }
                .container .btnSubmit{
                  margin-left:120px;
                  font-weight:600;
                }
                .btnSubmit:hover{
                  color:#000;
                  background-color:#fff;
                }
                .more_java{
                  color: #666;
                  margin-left:120px;
                }
                .more_java:hover{
                  color:'. self::$color_b .';
                }

                .prj-onekey label{
                  margin-top:20px;
                }
                .prj-onekey input{
                  margin-top:0px;
                }
                @media screen and (-webkit-min-device-pixel-ratio:0) {
                  .btnSubmit{
                    -webkit-border-radius: 6px;
                    -webkit-box-shadow: 0px 1px 3px rgba(0,0,0,0.5);
                  }
                }
            </style>';
        return $showResult;
    }

}

?>
