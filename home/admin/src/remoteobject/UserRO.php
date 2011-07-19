<?php
    
  /**
  * 用户
  */
  class UserRO extends RemoteObject 
  {
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * @var mixed 用户的唯一标识 
     */
    public $id;      
    /**
     * 部门编号
     * @var int
     * @access private 
     */
    public $departmentId;

    /**
     * 用户名
     * @var string
     * @access private 
     */
    public $name;

    /**
     * 用户密码
     * @var string
     * @access private 
     */
    public $password;
    //</editor-fold>
  }
  
?>
