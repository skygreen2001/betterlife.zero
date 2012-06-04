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
	 * 加载文件上传功能
	 * @link http://urliv.com/577.html
	 */
	public static function ckFinder()
	{                                      
	   $out= "    <script type=\"text/javascript\" src=\"".Gc::$url_base."common/js/onlineditor/ckfinder/ckfinder.js\"></script>\n";    
	   return $out;
	}
	
	/**
	* 显示CKEditor编辑器Html  
	* @param string $textarea_id 在线编辑器所在的内容编辑区域TextArea的ID
	* @param string $content     在线编辑器的内容                  
	*/
	public static function editorHtml($textarea_id,$content="")
	{
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
		$result=$CKEditor->editor($textarea_id, $content,$config);    
		$result.=self::ckFinder().UtilAjax::loadJsContentSentence("CKFinder.setupCKEditor(null,\"".Gc::$url_base."common/js/onlineditor/ckfinder/\");");
		return $result;
		//return $CKEditor->editor($textarea_id, $content,$config);     
	} 
	/**
	 * 加载CKEditor JS库
	 */
	public static function load()
	{         
		if (self::$CKEditor==null){
			$CKEditor = new CKEditor();
		}                         
		return $CKEditor->load();  
	}     
	
	/**                                
	 * 加载CKEditor JS库<br/>
	 * 用Ckeditor编辑器替换Html中的Textarea 
	 * @param string $textarea_id 在线编辑器所在的内容编辑区域TextArea的ID     
	 * @param bool $isLoadCkFinder 是否加载CkFinder
	 */
	public static function loadReplace($textarea_id,$isLoadCkFinder=true)
	{   
		$result=self::load().UtilAjax::loadJsContentSentence(self::replace($textarea_id,$isLoadCkFinder));  
		if ($isLoadCkFinder){  
			$result.=self::ckFinder($isLoadCkFinder);
		}
		return $result;       
	}                   
	
	/**
	 * 用Ckeditor编辑器替换Html中的Textarea 
	 * @param string $textarea_id 在线编辑器所在的内容编辑区域TextArea的ID                      
	 */
	public static function replace($textarea_id,$isLoadCkFinder=true)
	{
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
		//$config['width'] = 640;
		//$config['resize_enabled'] = false;   
		$config['toolbarStartupExpanded'] = true;
		$config['startupOutlineBlocks'] = true;    
		$config['removeDialogTabs'] = 'image:Link;image:advanced';    
		$jsContent= "var editor_$textarea_id = CKEDITOR.replace('".$textarea_id."', ".$CKEditor->jsEncode($config).");";  
		$suffix_cr="_$textarea_id";
		$jsContent="\r\n". 
				   "        function ckeditor_replace$suffix_cr()\r\n".
				   "        {\r\n".   
				   "            ".$jsContent."\r\n".
				   "            CKFinder.setupCKEditor(null,\"".Gc::$url_base."common/js/onlineditor/ckfinder/\");\r\n".
				   "".
				   "        }\r\n";                                                        
		return $jsContent;  
	}            
}
?>