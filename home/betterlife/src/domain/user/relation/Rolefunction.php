<?php
/**
 +---------------------------------------<br/>
 * 角色拥有功能关系表<br/>
 +---------------------------------------
 * @category betterlife
 * @package domain.user.relation
 * @author skygreen
 */
class Rolefunction extends DataObject {
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 角色编号
     * @var int
     * @access private 
     */
    private $roleId;

    /**
     * 功能编号
     * @var int
     * @access private 
     */
    private $functionId;
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="setter和getter">
    public function setRoleId($roleId){
        $this->roleId=$roleId;
    }

    public function getRoleId(){
        return $this->roleId;
    }

    public function setFunctionId($functionId){
        $this->functionId=$functionId;
    }

    public function getFunctionId(){
        return $this->functionId;
    }
    //</editor-fold>
}
?>