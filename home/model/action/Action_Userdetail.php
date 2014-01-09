<?php
/**
 +---------------------------------------<br/>
 * 控制器:用户详细信息<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Userdetail extends ActionModel
{
    /**
     * 用户详细信息列表
     */
    public function lists()
    {
        if ($this->isDataHave(UtilPage::$linkUrl_pageFlag)){
          $nowpage=$this->data[UtilPage::$linkUrl_pageFlag];  
        }else{   
          $nowpage=1; 
        }
        $count=Userdetail::count();
        $bb_page=UtilPage::init($nowpage,$count);
        $this->view->countUserdetails=$count;
        $userdetails = Userdetail::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
        $this->view->set("userdetails",$userdetails);
    }
    /**
     * 查看用户详细信息
     */
    public function view()
    {
        $userdetailId=$this->data["id"]; 
        $userdetail = Userdetail::get_by_id($userdetailId); 
        $this->view->set("userdetail",$userdetail);
    }
    /**
     * 编辑用户详细信息
     */
    public function edit()
    {
        $this->loadCss("common/js/ajax/jquery/css/fileupload.css");
        $this->loadJs("common/js/ajax/jquery/fileupload/jquery.ui.widget.js");
        $this->loadJs("common/js/ajax/jquery/fileupload/js/jquery.iframe-transport.js");
        $this->loadJs("common/js/ajax/jquery/fileupload/jquery.fileupload.js");
        if (!empty($_POST)) {
            $userdetail = $this->model->Userdetail;
            $id= $userdetail->getId(); 
            if (!empty($id)){
              $userdetail->update(); 
            }else{
              $id=$userdetail->save();  
            }
            $this->redirect("userdetail","view","id=$id");
        }else{
            $userdetailId=$this->data["id"];
            $userdetail = Userdetail::get_by_id($userdetailId);
            $this->view->set("userdetail",$userdetail); 
            //加载在线编辑器的语句要放在:$this->view->viewObject[如果有这一句]之后。
            $this->load_onlineditor('address');
        }
    }
    /**
     * 删除用户详细信息
     */
    public function delete()
    {
        $userdetailId=$this->data["id"]; 
        $isDelete = Userdetail::deleteByID($userdetailId); 
        $this->redirect("userdetail","lists",$this->data);
    }
}

?>