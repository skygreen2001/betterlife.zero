<?php
/**
 +---------------------------------<br/>
 * 控制器:查看页面
 +---------------------------------
 * @category betterlife
 * @package  web.back.admin 
 * @subpackage action
 * @author skygreen
 */
class Action_View extends ActionExt
{ 
    /**
     * 控制器:系统管理人员
     */
     public function admin()
     {
         $this->init();
         $this->ExtDirectMode();
         $this->loadExtJs('view/admin.js');
     }
     
    /**
     * 控制器:系统管理人员
     */
     public function user()
     {
         $this->init();
         $this->ExtDirectMode();
         $this->loadExtJs('view/user.js');
     }

}
?>