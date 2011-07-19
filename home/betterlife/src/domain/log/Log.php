<?php
/**
 +---------------------------------------<br/>
 * 日志<br/>
 +---------------------------------------
 * @category betterlife
 * @package domain.log
 * @author skygreen
 */
class Log extends DataObject {
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 日志记录时间
     * @var timestamp
     * @access private 
     */
    private $logtime;

    /**
     * 标志或者分类
     * @var string
     * @access private 
     */
    private $ident;

    /**
     * 优先级
     * 0:严重错误
     * 1:警戒性错误
     * 2:临界值错误
     * 3:一般错误
     * 4:警告性错误
     * 5:通知
     * 6:信息
     * 7:调试
     * 8:SQL
     * @var enum
     * @access private 
     */
    private $priority;

    /**
     * 日志内容
     * @var string
     * @access private 
     */
    private $message;
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="setter和getter">
    public function setLogtime($logtime){
        $this->logtime=$logtime;
    }

    public function getLogtime(){
        return $this->logtime;
    }

    public function setIdent($ident){
        $this->ident=$ident;
    }

    public function getIdent(){
        return $this->ident;
    }

    public function setPriority($priority){
        $this->priority=$priority;
    }

    public function getPriority(){
        return $this->priority;
    }

    public function setMessage($message){
        $this->message=$message;
    }

    public function getMessage(){
        return $this->message;
    }
    //</editor-fold>
}
?>