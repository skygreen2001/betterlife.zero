<?php
/**
 +--------------------------------<br/>
 * 定义 KindEditor 在线编辑器<br/>
 +--------------------------------
 * @category betterlife
 * @package util.view
 * @subpackage onlinediotr
 * @author skygreen
 * @link http://kindeditor.net/
 * KindEditor文档: http://kindeditor.net/doc3.php?cmd=config
 */
class UtilKindEditor extends Util 
{

    /**
     * 设置标准toolbar
     */
    public static function toolbar_normal()
    {
        $skinType="skinType:'default',";//tinymcd|default
        return $skinType."items:['source', '|', 'fullscreen', 'undo', 'redo', 'print', 'cut', 'copy', 'paste','plainpaste', 'wordpaste', '|',
                 'justifyleft', 'justifycenter', 'justifyright','justifyfull', 'insertorderedlist', 'insertunorderedlist',
                 'indent', 'outdent', 'subscript','superscript', '|', 'selectall', '-','title', 'fontname', 'fontsize', '|', 
                 'textcolor', 'bgcolor', 'bold','italic', 'underline', 'strikethrough', 'removeformat', '|', 'image',
                 'flash', 'media', 'advtable', 'hr', 'emoticons', 'link', 'unlink', '|', 'about']";
    }

    /**
     * 预加载UEditor的JS函数
     * @param string $textarea_id 在线编辑器所在的内容编辑区域TextArea的ID
     * @param ViewObject $viewobject 表示层显示对象,只在Web框架中使用    
     * @param string form_id  在线编辑器所在的Form的ID    
     * @param string $configString 配置字符串            
     */
    public static function loadJsFunction($textarea_id,$viewObject=null,$form_id=null,$configString="")
    {
        UtilCss::loadCssReady($viewObject,"common/js/onlineditor/kindeditor/themes/default/default.css");
        if (UtilAjax::$IsDebug){
            UtilJavascript::loadJsReady($viewObject, "common/js/onlineditor/kindeditor/kindeditor.js");
        }else{
            UtilJavascript::loadJsReady($viewObject, "common/js/onlineditor/kindeditor/kindeditor-min.js");
        }

        UtilJavascript::loadJsReady($viewObject, "common/js/onlineditor/kindeditor/lang/zh_CN.js");

        $is_toolbar_full=false;
        
        if (empty($configString)){
            $configString=self::toolbar_normal();
        }
        $configString="";
        return "{".$configString."}";
    } 

}

?>
