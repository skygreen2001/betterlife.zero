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
        $this->view->countAdmins=$count;
        if($count>0){            $bb_page=UtilPage::init($nowpage,$count);
            $admins = Admin::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
            foreach ($admins as $admin) {
                $department_instance=null;
                if ($admin->department_id){
                    $department_instance=Department::get_by_id($admin->department_id);
                    $admin['department_name']=$department_instance->department_name;
                }
            }
            $this->view->set("admins",$admins);
        }
    }
    /**
     * 查看系统管理人员
     */
    public function view()
    {
        $adminId=$this->data["id"];
        $admin = Admin::get_by_id($adminId);
        $department_instance=null;
        if ($admin->department_id){
            $department_instance=Department::get_by_id($admin->department_id);
            $admin['department_name']=$department_instance->department_name;
        }
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
            $isRedirect=true;
            if (!empty($id)){
                $admin->update();
            }else{
                $id=$admin->save();
            }
            if ($isRedirect){
                $this->redirect("admin","view","id=$id");
                exit;
            }
        }
        $adminId=$this->data["id"];
        $admin = Admin::get_by_id($adminId);
        $this->view->set("admin",$admin);
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