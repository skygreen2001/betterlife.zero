<?php
/**
 +---------------------------------------<br/>
 * 控制器:角色拥有功能<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.front.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Rolefunction extends Action
{
    /**
     * 角色拥有功能列表
     */
    public function lists()
    {
        if ($this->isDataHave(UtilPage::$linkUrl_pageFlag)){
          $nowpage=$this->data[UtilPage::$linkUrl_pageFlag];  
        }else{   
          $nowpage=1; 
        }
        $count=Rolefunction::count();
        $bb_page=UtilPage::init($nowpage,$count);
        $this->view->countRolefunctions=$count;
        $rolefunctions = Rolefunction::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
        $this->view->set("rolefunctions",$rolefunctions);
    }
    /**
     * 查看角色拥有功能
     */
    public function view()
    {
        $rolefunctionId=$this->data["id"]; 
        $rolefunction = Rolefunction::get_by_id($rolefunctionId); 
        $this->view->set("rolefunction",$rolefunction);
    }
    /**
     * 编辑角色拥有功能
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $rolefunction = $this->model->Rolefunction;
            $id= $rolefunction->getId(); 
            if (!empty($id)){
              $rolefunction->update(); 
            }else{
              $id=$rolefunction->save();  
            }
            $this->redirect("rolefunction","view","id=$id");
        }else{
            $rolefunctionId=$this->data["id"];
            $rolefunction = Rolefunction::get_by_id($rolefunctionId);
            $this->view->set("rolefunction",$rolefunction); 
        }
    }
    /**
     * 删除角色拥有功能
     */
    public function delete()
    {
        $rolefunctionId=$this->data["id"]; 
        $isDelete = Rolefunction::deleteByID($rolefunctionId); 
        $this->redirect("rolefunction","lists",$this->data);
    }
}

?>