<?php
/**
 +---------------------------------------<br/>
 * 控制器:用户角色<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Userrole extends ActionModel
{
    /**
     * 用户角色列表
     */
    public function lists()
    {
        if ($this->isDataHave(UtilPage::$linkUrl_pageFlag)){
            $nowpage=$this->data[UtilPage::$linkUrl_pageFlag];
        }else{
            $nowpage=1;
        }
        $count=Userrole::count();
        $this->view->countUserroles=$count;
        if($count>0){            $bb_page=UtilPage::init($nowpage,$count);
            $userroles = Userrole::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
            foreach ($userroles as $userrole) {
                $user_instance=null;
                if ($userrole->user_id){
                    $user_instance=User::get_by_id($userrole->user_id);
                    $userrole['username']=$user_instance->username;
                }
                $role_instance=null;
                if ($userrole->role_id){
                    $role_instance=Role::get_by_id($userrole->role_id);
                    $userrole['role_name']=$role_instance->role_name;
                }
            }
            $this->view->set("userroles",$userroles);
        }
    }
    /**
     * 查看用户角色
     */
    public function view()
    {
        $userroleId=$this->data["id"];
        $userrole = Userrole::get_by_id($userroleId);
        $user_instance=null;
        if ($userrole->user_id){
            $user_instance=User::get_by_id($userrole->user_id);
            $userrole['username']=$user_instance->username;
        }
        $role_instance=null;
        if ($userrole->role_id){
            $role_instance=Role::get_by_id($userrole->role_id);
            $userrole['role_name']=$role_instance->role_name;
        }
        $this->view->set("userrole",$userrole);
    }
    /**
     * 编辑用户角色
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $userrole = $this->model->Userrole;
            $id= $userrole->getId();
            $isRedirect=true;
            if (!empty($id)){
                $userrole->update();
            }else{
                $id=$userrole->save();
            }
            if ($isRedirect){
                $this->redirect("userrole","view","id=$id");
                exit;
            }
        }
        $userroleId=$this->data["id"];
        $userrole = Userrole::get_by_id($userroleId);
        $this->view->set("userrole",$userrole);
    }
    /**
     * 删除用户角色
     */
    public function delete()
    {
        $userroleId=$this->data["id"];
        $isDelete = Userrole::deleteByID($userroleId);
        $this->redirect("userrole","lists",$this->data);
    }
}

?>