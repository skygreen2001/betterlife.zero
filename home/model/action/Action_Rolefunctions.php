<?php
/**
 +---------------------------------------<br/>
 * 控制器:角色拥有功能<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Rolefunctions extends ActionModel
{
    /**
     * 角色拥有功能列表
     */
    public function lists()
    {
        if ($this->isDataHave(TagPageService::$linkUrl_pageFlag)){
            $nowpage=$this->data[TagPageService::$linkUrl_pageFlag];
        }else{
            $nowpage=1;
        }
        $count=Rolefunctions::count();
        $this->view->countRolefunctionss=$count;
        if($count>0){
            $bb_page=TagPageService::init($nowpage,$count);
            $rolefunctionss = Rolefunctions::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
            foreach ($rolefunctionss as $rolefunctions) {
                $role_instance=null;
                if ($rolefunctions->role_id){
                    $role_instance=Role::get_by_id($rolefunctions->role_id);
                    $rolefunctions['role_name']=$role_instance->role_name;
                }
                $functions_instance=null;
                if ($rolefunctions->functions_id){
                    $functions_instance=Functions::get_by_id($rolefunctions->functions_id);
                    $rolefunctions['functions_name']=$functions_instance->name;
                }
            }
            $this->view->set("rolefunctionss",$rolefunctionss);
        }
    }
    /**
     * 查看角色拥有功能
     */
    public function view()
    {
        $rolefunctionsId=$this->data["id"];
        $rolefunctions = Rolefunctions::get_by_id($rolefunctionsId);
        $role_instance=null;
        if ($rolefunctions->role_id){
            $role_instance=Role::get_by_id($rolefunctions->role_id);
            $rolefunctions['role_name']=$role_instance->role_name;
        }
        $functions_instance=null;
        if ($rolefunctions->functions_id){
            $functions_instance=Functions::get_by_id($rolefunctions->functions_id);
            $rolefunctions['functions_name']=$functions_instance->name;
        }
        $this->view->set("rolefunctions",$rolefunctions);
    }
    /**
     * 编辑角色拥有功能
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $rolefunctions = $this->model->Rolefunctions;
            $id= $rolefunctions->getId();
            $isRedirect=true;
            if (!empty($id)){
                $rolefunctions->update();
            }else{
                $id=$rolefunctions->save();
            }
            if ($isRedirect){
                $this->redirect("rolefunctions","view","id=$id");
                exit;
            }
        }
        $rolefunctionsId=$this->data["id"];
        $rolefunctions = Rolefunctions::get_by_id($rolefunctionsId);
        $this->view->set("rolefunctions",$rolefunctions);
    }
    /**
     * 删除角色拥有功能
     */
    public function delete()
    {
        $rolefunctionsId=$this->data["id"];
        $isDelete = Rolefunctions::deleteByID($rolefunctionsId);
        $this->redirect("rolefunctions","lists",$this->data);
    }
}

?>