<?php
/**
 +---------------------------------------<br/>
 * 用户<br/>
 +---------------------------------------
 * @category betterlife
 * @package domain.user
 * @author skygreen
 */
class User extends DataObject {
    
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 部门编号
     * @var int
     * @access private 
     */
    private $departmentId;

    /**
     * 用户名
     * @var string
     * @access private 
     */
    private $name;

    /**
     * 用户密码
     * @var string
     * @access private 
     */
    private $password;
    //</editor-fold>
    
    /**
     * 调用启动方法-》$this->userDetail();
     * @var array
     */
    static $has_one=array(    
        "userDetail"=> "UserDetail",            
    );

    static $belong_has_one=array(    
        "department"=> "Department",            
    );

    
    static $many_many=array(
       "roles"=>"Role",
    );

    //<editor-fold defaultstate="collapsed" desc="setter和getter">
    public function setDepartmentId($departmentId){
        $this->departmentId=$departmentId;
    }

    public function getDepartmentId(){
        return $this->departmentId;
    }

    public function setName($name){
        $this->name=$name;
    }

    public function getName(){
        return $this->name;
    }

    public function setPassword($password){
        $this->password=$password;
    }

    public function getPassword(){
        return $this->password;
    }
    //</editor-fold>

    
    public function getNameShow() {
        $name=UtilString::gbk2utf8($this->name);    
        return $name;
    }
    

}
?>
