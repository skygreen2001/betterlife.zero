<?php
/**
 +---------------------------------------<br/>
 * 用户角色关系表<br/>
 +---------------------------------------
 * @category betterlife
 * @package domain.user.relation
 * @author skygreen
 */
class Userrole extends DataObject {
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 用户编号
     * @var int
     * @access private 
     */
    private $userId;

    /**
     * 角色编号
     * @var int
     * @access private 
     */
    private $roleId;
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="setter和getter">
    public function setUserId($userId){
        $this->userId=$userId;
    }

    public function getUserId(){
        return $this->userId;
    }

    public function setRoleId($roleId){
        $this->roleId=$roleId;
    }

    public function getRoleId(){
        return $this->roleId;
    }
    //</editor-fold>
}
?>