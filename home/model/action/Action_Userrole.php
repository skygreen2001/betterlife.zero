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
        $bb_page=UtilPage::init($nowpage,$count);
        $this->view->countUserroles=$count;
        $userroles = Userrole::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
        $this->view->set("userroles",$userroles);
    }
    /**
     * 查看用户角色
     */
    public function view()
    {
        $userroleId=$this->data["id"]; 
        $userrole = Userrole::get_by_id($userroleId); 
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