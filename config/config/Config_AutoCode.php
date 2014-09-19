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
     * 是否服务端使用C# ASP.Net MVC 3
     */
    const IS_CSHARP_NET_SERVER=false;
}
?>
