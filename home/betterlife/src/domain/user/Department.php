<?php
/**
 +---------------------------------------<br/>
 * 用户所属部门<br/>
 +---------------------------------------
 * @category betterlife
 * @package domain.user
 * @author skygreen
 */
class Department extends DataObject {
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 部门名称
     * @var string
     * @access private 
     */
    private $name;

    /**
     * 管理者
     * @var string
     * @access private 
     */
    private $manager;

    /**
     * 预算
     * @var int
     * @access private 
     */
    private $budget;

    /**
     * 实际开销
     * @var int
     * @access private 
     */
    private $actualexpenses;

    /**
     * 部门人员预估平均工资
     * @var int
     * @access private 
     */
    private $estsalary;

    /**
     * 部门人员实际平均工资
     * @var int
     * @access private 
     */
    private $actualsalary;
    //</editor-fold>
         
    static $has_many=array(
      "users"=>"User",
    );  
    
    //<editor-fold defaultstate="collapsed" desc="setter和getter">
    public function setName($name){
        $this->name=$name;
    }

    public function getName(){
        return $this->name;
    }

    public function setManager($manager){
        $this->manager=$manager;
    }

    public function getManager(){
        return $this->manager;
    }

    public function setBudget($budget){
        $this->budget=$budget;
    }

    public function getBudget(){
        return $this->budget;
    }

    public function setActualexpenses($actualexpenses){
        $this->actualexpenses=$actualexpenses;
    }

    public function getActualexpenses(){
        return $this->actualexpenses;
    }

    public function setEstsalary($estsalary){
        $this->estsalary=$estsalary;
    }

    public function getEstsalary(){
        return $this->estsalary;
    }

    public function setActualsalary($actualsalary){
        $this->actualsalary=$actualsalary;
    }

    public function getActualsalary(){
        return $this->actualsalary;
    }
    //</editor-fold>

}
?>
