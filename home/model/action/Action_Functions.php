<?php
/**
 +---------------------------------------<br/>
 * 控制器:功能信息<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.front.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Functions extends Action
{
    /**
     * 功能信息列表
     */
    public function lists()
    {
        if ($this->isDataHave(UtilPage::$linkUrl_pageFlag)){
          $nowpage=$this->data[UtilPage::$linkUrl_pageFlag];  
        }else{   
          $nowpage=1; 
        }
        $count=Functions::count();
        $bb_page=UtilPage::init($nowpage,$count);
        $this->view->countFunctionss=$count;
        $Functionss = Functions::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
        $this->view->set("Functionss",$Functionss);
    }
    /**
     * 查看功能信息
     */
    public function view()
    {
        $FunctionsId=$this->data["id"]; 
        $Functions = Functions::get_by_id($FunctionsId); 
        $this->view->set("Functions",$Functions);
    }
    /**
     * 编辑功能信息
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $Functions = $this->model->Functions;
            $id= $Functions->getId(); 
            if (!empty($id)){
              $Functions->update(); 
            }else{
              $id=$Functions->save();  
            }
            $this->redirect("Functions","view","id=$id");
        }else{
            $FunctionsId=$this->data["id"];
            $Functions = Functions::get_by_id($FunctionsId);
            $this->view->set("Functions",$Functions); 
        }
    }
    /**
     * 删除功能信息
     */
    public function delete()
    {
        $FunctionsId=$this->data["id"]; 
        $isDelete = Functions::deleteByID($FunctionsId); 
        $this->redirect("Functions","lists",$this->data);
    }
}

?>