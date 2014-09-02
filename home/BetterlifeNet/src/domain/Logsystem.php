<?php
/**
 +---------------------------------------<br/>
 * 系统日志<br/>
 +---------------------------------------
 * @category betterlife
 * @package 
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
    public $ID;
    /**
     * 日志记录时间
     * @var date
     * @access public
     */
    public $Logtime;
    /**
     * 分类<br/>
     * 标志或者分类
     * @var string
     * @access public
     */
    public $Ident;
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
    public $Priority;
    /**
     * 日志内容
     * @var string
     * @access public
     */
    public $Message;
    //</editor-fold>
    /**
     * 规格说明
     * 表中不存在的默认列定义:CommitTime,UpdateTime
     * @var mixed
     */
    public $field_spec=array(
        EnumDataSpec::REMOVE=>array(
            'CommitTime',
            'UpdateTime'
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
        return self::PriorityShow($this->Priority);
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
    public static function PriorityShow($Priority)
    {
        return EnumPriority::PriorityShow($Priority);
    }

}
?>