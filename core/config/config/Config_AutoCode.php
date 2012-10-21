<?php
/**
 +---------------------------------------<br/>
 * 批量生成代码的配置<br/>
 +---------------------------------------
 * @category ele
 * @package core.config.common
 * @author skygreen
 */
class Config_AutoCode extends ConfigBB
{
	/**
	 * 是否生成的JS文件都在一个文件夹里 
	 */
	const JSFILE_DIRECT_CORE=false;
	/**
	 * 一对多关系显示是否完整,默认只生成Grid，完整模式包括增删改分页 
	 */
	const RELATION_VIEW_FULL=false;
}
?>
