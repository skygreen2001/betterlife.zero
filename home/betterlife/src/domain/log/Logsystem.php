<?php
/**
 +---------------------------------------<br/>
 * 系统日志<br/>
 +---------------------------------------
 * @category betterlife
 * @package log
 * @author skygreen skygreen2001@gmail.com
 */
class Logsystem extends DataObject
{
	//<editor-fold defaultstate="collapsed" desc="定义部分">
	/**
	 * 标识
	 * @var int
	 * @access public
	 */
	public $logsystem_id;
	/**
	 * 日志记录时间
	 * @var date
	 * @access public
	 */
	public $logtime;
	/**
	 * 分类<br/>
	 * 标志或者分类
	 * @var string
	 * @access public
	 */
	public $ident;
	/**
	 * 优先级<br/>
	 * 0:严重错误-EMERG<br/>
	 * 1:警戒性错误-ALERT<br/>
	 * 2:临界值错误-CRIT<br/>
	 * 3:一般错误-ERR<br/>
	 * 4:警告性错误-WARN<br/>
	 * 5:通知-NOTICE<br/>
	 * 6:信息-INFO<br/>
	 * 7:调试-DEBUG<br/>
	 * 8:SQL-SQL
	 * @var enum
	 * @access public
	 */
	public $priority;
	/**
	 * 日志内容
	 * @var string
	 * @access public
	 */
	public $message;
	//</editor-fold>
	/**
	 * 规格说明
	 * 表中不存在的默认列定义:commitTime,updateTime
	 * @var mixed
	 */
	public $field_spec=array(
		EnumDataSpec::REMOVE=>array(
			'commitTime',
			'updateTime'
		)
	);

	/**
	 * 显示优先级<br/>
	 * 0:严重错误-EMERG<br/>
	 * 1:警戒性错误-ALERT<br/>
	 * 2:临界值错误-CRIT<br/>
	 * 3:一般错误-ERR<br/>
	 * 4:警告性错误-WARN<br/>
	 * 5:通知-NOTICE<br/>
	 * 6:信息-INFO<br/>
	 * 7:调试-DEBUG<br/>
	 * 8:SQL-SQL<br/>
	 */
	public function getPriorityShow()
	{
		return self::priorityShow($this->priority);
	}

	/**
	 * 显示优先级<br/>
	 * 0:严重错误-EMERG<br/>
	 * 1:警戒性错误-ALERT<br/>
	 * 2:临界值错误-CRIT<br/>
	 * 3:一般错误-ERR<br/>
	 * 4:警告性错误-WARN<br/>
	 * 5:通知-NOTICE<br/>
	 * 6:信息-INFO<br/>
	 * 7:调试-DEBUG<br/>
	 * 8:SQL-SQL<br/>
	 */
	public static function priorityShow($priority)
	{
		return EnumPriority::priorityShow($priority);
	}

}
?>