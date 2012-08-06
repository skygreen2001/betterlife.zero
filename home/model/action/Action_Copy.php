<?php
/**
 +---------------------------------------<br/>
 * 控制器:系统管理人员<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.front.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Copy extends Action
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
        $count=Copy::count();
        $bb_page=UtilPage::init($nowpage,$count);
        $this->view->countCopys=$count;
        $copys = Copy::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
        $this->view->set("copys",$copys);
    }
    /**
     * 查看系统管理人员
     */
    public function view()
    {
        $copyId=$this->data["id"]; 
        $copy = Copy::get_by_id($copyId); 
        $this->view->set("copy",$copy);
    }
    /**
     * 编辑系统管理人员
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $copy = $this->model->Copy;
            $id= $copy->getId(); 
            if (!empty($id)){
              $copy->update(); 
            }else{
              $id=$copy->save();  
            }
            $this->redirect("copy","view","id=$id");
        }else{
            $copyId=$this->data["id"];
            $copy = Copy::get_by_id($copyId);
            $this->view->set("copy",$copy); 
        }
    }
    /**
     * 删除系统管理人员
     */
    public function delete()
    {
        $copyId=$this->data["id"]; 
        $isDelete = Copy::deleteByID($copyId); 
        $this->redirect("copy","lists",$this->data);
    }
}

?>