<?php
/**
 +---------------------------------------<br/>
 * 控制器:功能信息<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Functions extends ActionModel
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
        $functionss = Functions::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
        $this->view->set("functionss",$functionss);
    }
    /**
     * 查看功能信息
     */
    public function view()
    {
        $functionsId=$this->data["id"]; 
        $functions = Functions::get_by_id($functionsId); 
        $this->view->set("functions",$functions);
    }
    /**
     * 编辑功能信息
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $functions = $this->model->Functions;
            $id= $functions->getId(); 
            if (!empty($id)){
              $functions->update(); 
            }else{
              $id=$functions->save();  
            }
            $this->redirect("functions","view","id=$id");
        }else{
            $functionsId=$this->data["id"];
            $functions = Functions::get_by_id($functionsId);
            $this->view->set("functions",$functions); 
            //加载在线编辑器的语句要放在:$this->view->viewObject[如果有这一句]之后。
            $this->load_onlineditor('url');
        }
    }
    /**
     * 删除功能信息
     */
    public function delete()
    {
        $functionsId=$this->data["id"]; 
        $isDelete = Functions::deleteByID($functionsId); 
        $this->redirect("functions","lists",$this->data);
    }
}

?>