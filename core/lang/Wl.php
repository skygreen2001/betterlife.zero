<?php
/**
 +-------------------------------------------<br/>
 * 通用语言<br/>
 * 开发期默认和中文一致<br/>
 +-------------------------------------------<br/>
 * @category betterlife
 * @package core.lang
 * @author skygreen <skygreen2001@gmail.com>
 */
class Wl {
	const INFO_REDIRECT_PART1="系统将在";
	const INFO_REDIRECT_PART2="秒之后自动跳转到";
	const INFO_DB_CHARACTER="当前数据库客户端连接字符集是：";

	const ERROR_INFO_CONNECT_FAIL="数据库连接失败，错误信息 :";//Failed to connect, the error message is :
	const ERROR_INFO_SEARCH_NONE="无法找到数据库！";
	const ERROR_INFO_INSERT_ID="新增对象ID已经存在！";
	const ERROR_INFO_NEED_OBJECT_CLASSNAME="操作的引用参数需要是对象或者类名！";
	const ERROR_INFO_EXTENDS_CLASS="该数据库操作的对象类需要继承DataObject！";
	const ERROR_INFO_DB_HANDLE="数据库操作失败！";
	const ERROR_INFO_UPDATE_ID="更新对象设定ID不正确！";
	const ERROR_INFO_UPDATE_VALUE_NULL="传入的更新值不能为空";
	const ERROR_INFO_MODEL_EXISTS="无法找到 Model";
	const ERROR_INFO_OBJECT_UNKNOWN="对象未定义，请检查是否拼写错误";
	const ERROR_INFO_CONTROLLER_UNKNOWN="未找到Controller";
	const ERROR_INFO_VIEW_UNKNOWN="目录下无法找到View文件:";

	const LOG_INFO_PROFILE_RUN="网页运行";
	const LOG_INFO_PROFILE_INIT="初始化工作";
	const LOG_INFO_PROFILE_WEBURL="负责WEB URL的转发";

	const EXCEPTION_REPORT_INFO="异常信息";
	const EXCEPTION_REPORT_ADDITION="补充异常信息";
	const EXCEPTION_REPORT_CLASS="类";
	const EXCEPTION_REPORT_FUNCTION="函数";
	const EXCEPTION_REPORT_FILE="文件";
	const EXCEPTION_REPORT_LINE="行";
	const EXCEPTION_REPORT_PARAMETER="引用参数";
	const EXCEPTION_REPORT_DETAIL="详细信息";
	const EXCEPTION_REPORT_TRACKTIME="跟踪时间";
	const EXCEPTION_REPORT_TRACKINFO="跟踪信息";
	const EXCEPTION_REPORT_TYPE="异常类型";
}
?>
