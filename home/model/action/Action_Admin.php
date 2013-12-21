<?php
/**
 +---------------------------------------<br/>
 * 控制器:系统管理人员<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Admin extends ActionModel
{
    /**
     * 系统管理人员列表
     */
    public function lists()
    {
        if ($this->isDataHave(UtilPage::$linkUrl_pageFlag)){
          $nowpage=$this->data[UtilPage::$linkUrl_pageFlag];  
        }else{   
          $nowpage=1; 
        }
        $count=Admin::count();
        $bb_page=UtilPage::init($nowpage,$count);
        $this->view->countAdmins=$count;
        $admins = Admin::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
        $this->view->set("admins",$admins);
    }
    /**
     * 查看系统管理人员
     */
    public function view()
    {
        $adminId=$this->data["id"]; 
        $admin = Admin::get_by_id($adminId); 
        $this->view->set("admin",$admin);
    }
    /**
     * 编辑系统管理人员
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $admin = $this->model->Admin;
            $id= $admin->getId(); 
            if (!empty($id)){
              $admin->update(); 
            }else{
              $id=$admin->save();  
            }
            $this->redirect("admin","view","id=$id");
        }else{
            $adminId=$this->data["id"];
            $admin = Admin::get_by_id($adminId);
            $this->view->set("admin",$admin); 
        }
    }
    /**
     * 删除系统管理人员
     */
    public function delete()
    {
        $adminId=$this->data["id"]; 
        $isDelete = Admin::deleteByID($adminId); 
        $this->redirect("admin","lists",$this->data);
    }
}

?>