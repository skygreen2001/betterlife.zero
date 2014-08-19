<?php
/**
 +--------------------------------<br/>
 * 定义 UEditor 在线编辑器<br/>
 +--------------------------------
 * @category betterlife
 * @package util.view
 * @subpackage onlinediotr
 * @author skygreen
 * @link http://fex.baidu.com/ueditor/ [ueditor API 文档]
 * @link http://www.comsharp.com/GetKnowledge/zh-CN/It_News_k1067.aspx [百度 UEditor Web 编辑器同 CMS 集成全攻略]
 */
class UtilUeditor extends Util
{

	/**
	 * 设置标准toolbar
	 */
	public static function toolbar_normal()
	{
		return "[
					[

                      'fontfamily', 'fontsize', 'paragraph', 'forecolor', 'backcolor','bold', 'italic', 'underline', 'fontborder', 'strikethrough','|',
                      'lineheight', 'indent', 'touppercase', 'tolowercase','superscript', 'subscript','insertorderedlist', 'insertunorderedlist', '|',
                    ],
                    [ 'link', 'unlink','simpleupload', 'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map','spechars','wordimage','|',
            		  'undo','redo', 'removeformat', 'formatmatch', 'autotypeset','background','template','snapscreen','preview', 'searchreplace','source','fullscreen'
            		]

				]";
	}

	/**
	 * 预加载UEditor的JS函数
	 * 如何阻止div标签自动转换为p标签:http://fex-team.github.io/ueditor/#qa-allowDivToP
	 * @param string $textarea_id 在线编辑器所在的内容编辑区域TextArea的ID
	 * @param ViewObject $viewobject 表示层显示对象,只在Web框架中使用
	 * @param string form_id  在线编辑器所在的Form的ID
	 * @param string $configString 配置字符串
	 */
	public static function loadJsFunction($textarea_id,$viewObject=null,$form_id=null,$configString="")
	{
		$is_toolbar_full=false;
		if ($is_toolbar_full){
			UtilJavascript::loadJsContentReady($viewObject,"
				var ue_{$textarea_id};
				function pageInit_ue_{$textarea_id}()
				{
					ue_{$textarea_id}=UE.getEditor('{$textarea_id}',{
						allowDivTransToP: false
					});
				}
				"
			);
		}else{
			if (empty($configString)){
				$configString=self::toolbar_normal();
			}
			UtilJavascript::loadJsContentReady($viewObject,"
				var ue_{$textarea_id};
				function pageInit_ue_{$textarea_id}()
				{
					ue_{$textarea_id}=UE.getEditor('{$textarea_id}',{
						toolbars:$configString,
						allowDivTransToP: false
					});
				}
				"
			);
		}
	}

}

?>
