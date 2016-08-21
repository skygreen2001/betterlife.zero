<?php
/**
 *---------------------------------------<br/>
 * 枚举类型:优先级  <br/>
 *---------------------------------------<br/>
 * @category betterlife
 * @package domain
 * @subpackage enum
 * @author skygreen skygreen2001@gmail.com
 */
class EnumPriority extends Enum
{
    /**
     * 优先级:严重错误
     */
    const EMERG='0';
    /**
     * 优先级:警戒性错误
     */
    const ALERT='1';
    /**
     * 优先级:临界值错误
     */
    const CRIT='2';
    /**
     * 优先级:一般错误
     */
    const ERR='3';
    /**
     * 优先级:警告性错误
     */
    const WARN='4';
    /**
     * 优先级:通知
     */
    const NOTICE='5';
    /**
     * 优先级:信息
     */
    const INFO='6';
    /**
     * 优先级:调试
     */
    const DEBUG='7';
    /**
     * 优先级:SQL
     */
    const SQL='8';

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
        switch($priority){
            case self::EMERG:
                return "严重错误";
            case self::ALERT:
                return "警戒性错误";
            case self::CRIT:
                return "临界值错误";
            case self::ERR:
                return "一般错误";
            case self::WARN:
                return "警告性错误";
            case self::NOTICE:
                return "通知";
            case self::INFO:
                return "信息";
            case self::DEBUG:
                return "调试";
            case self::SQL:
                return "SQL";
        }
        return "未知";
    }

    /**
     * 根据优先级显示文字获取优先级<br/>
     * @param mixed $priorityShow 优先级显示文字
     */
    public static function priorityByShow($priorityShow)
    {
        switch($priorityShow){
            case "严重错误":
                return self::EMERG;
            case "警戒性错误":
                return self::ALERT;
            case "临界值错误":
                return self::CRIT;
            case "一般错误":
                return self::ERR;
            case "警告性错误":
                return self::WARN;
            case "通知":
                return self::NOTICE;
            case "信息":
                return self::INFO;
            case "调试":
                return self::DEBUG;
            case "SQL":
                return self::SQL;
        }
        return self::EMERG;
    }

    /**
     * 通过枚举值获取枚举键定义<br/>
     */
    public static function priorityEnumKey($priority)
    {
        switch($priority){
            case '0':
                return "EMERG";
            case '1':
                return "ALERT";
            case '2':
                return "CRIT";
            case '3':
                return "ERR";
            case '4':
                return "WARN";
            case '5':
                return "NOTICE";
            case '6':
                return "INFO";
            case '7':
                return "DEBUG";
            case '8':
                return "SQL";
        }
        return "EMERG";
    }

}
?>
