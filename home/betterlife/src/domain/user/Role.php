<?php
/**
 +---------------------------------------<br/>
 * 角色<br/>
 +---------------------------------------
 * @category betterlife
 * @package domain.user
 * @author skygreen
 */
class Role extends DataObject {
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 角色名称
     * @var string
     * @access private 
     */
    private $name;
    //</editor-fold>
    
    static $belongs_many_many=array(
       "users"=>"User",
    );
    
    //<editor-fold defaultstate="collapsed" desc="setter和getter">
    public function setName($name){
        $this->name=$name;
    }

    public function getName(){
        return $this->name;
    }
    //</editor-fold>

}
?>
