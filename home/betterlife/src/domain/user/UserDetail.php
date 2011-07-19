<?php
/**
 +---------------------------------------<br/>
 * 用户详细信息<br/>
 +---------------------------------------
 * @category betterlife
 * @package domain.user
 * @author skygreen
 */
class UserDetail extends DataObject {
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 用户编号
     * @var int
     * @access private 
     */
    private $userId;

    /**
     * 邮件地址
     * @var string
     * @access private 
     */
    private $email;

    /**
     * 手机号码
     * @var string
     * @access private 
     */
    private $cellphone;
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="setter和getter">
    public function setUserId($userId){
        $this->userId=$userId;
    }

    public function getUserId(){
        return $this->userId;
    }

    public function setEmail($email){
        $this->email=$email;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setCellphone($cellphone){
        $this->cellphone=$cellphone;
    }

    public function getCellphone(){
        return $this->cellphone;
    }
    //</editor-fold>

}
?>
