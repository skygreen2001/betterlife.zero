<?php
/**
 +--------------------------------<br/>
 * 定义 CKEeditor 在线编辑器<br/>
 +--------------------------------
 * @category betterlife
 * @package util.view
 * @subpackage onlinediotr
 * @author skygreen
 */
class UtilCKEeditor extends Util 
{
    private static $CKEditor;
    /**
    * 显示CKEditor编辑器Html  
    * @param string $textarea_id 在线编辑器所在的内容编辑区域TextArea的ID
    * @param string $content     在线编辑器的内容                  
    */
    public static function editorHtml($textarea_id,$content=""){
        if (self::$CKEditor==null){
            $CKEditor = new CKEditor();
        }
        $CKEditor->returnOutput=true;
        $config = array();
        $config['toolbar'] = array(
           array("Font", "FontSize", "TextColor", "BGColor"),
           array(  '-', 'Bold', 'Italic', 'Underline', 'Strike' ),
           array("JustifyLeft", "JustifyCenter", "JustifyRight"),
           array( 'Link', 'Unlink','Image', 'Source', 'Maximize' )
        );
        $config['width'] = 640;
        //$config['resize_enabled'] = false;

        $config['toolbarStartupExpanded'] = true;
        $config['startupOutlineBlocks'] = true;
        
             
        $config['removeDialogTabs'] = 'image:Link;image:advanced';
        return $CKEditor->editor($textarea_id, $content,$config);     
    }          
}
?>