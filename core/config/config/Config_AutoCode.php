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
	const JSFILE_DIRECT_CORE=true;
	/**
	 * 一对多关系显示是否完整,默认只生成Grid，完整模式包括增删改分页
	 */
	const RELATION_VIEW_FULL=true;
	/**
	 * 生成的Combo每次输入点选都重新查询服务端数据
	 */
	const COMBO_REFRESH=true;
	/**
	 * 完整生成模式，需要时间较长，需调整php.ini中的执行时间参数
	 * 主要是因为中间表的has_many生成后台显示js花费时间较长。
	 */
	const AUTOCONFIG_CREATE_FULL=true;
	/**
	 * 每次都生成代码生成配置文件
	 */
	const ALWAYS_AUTOCODE_XML_NEW=false;
	/**
	 * 显示前期报告
	 */
	const SHOW_PREVIEW_REPORT=true;
	/**
	 * 显示前台生成报告
	 */
	const SHOW_REPORT_FRONT=true;
	/**
	 * Model转换成后台Admin
	 * 		工程重用选择类型:通用版后
	 * 		代码生成会转向AutoCodePreviewReportLike
	 */
	const AFTER_MODEL_CONVERT_ADMIN=false;
	/**
	 * 工程重用为MINI后,只需要生成实体类
	 */
	const ONLY_DOMAIN=false;

}
?>
