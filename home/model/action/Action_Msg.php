<?php
/**
 +---------------------------------------<br/>
 * 控制器:消息<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.front.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Msg extends Action
{
    /**
     * 消息列表
     */
    public function lists()
    {
        if ($this->isDataHave(UtilPage::$linkUrl_pageFlag)){
          $nowpage=$this->data[UtilPage::$linkUrl_pageFlag];  
        }else{   
          $nowpage=1; 
        }
        $count=Msg::count();
        $bb_page=UtilPage::init($nowpage,$count);
        $this->view->countMsgs=$count;
        $msgs = Msg::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
        $this->view->set("msgs",$msgs);
    }
    /**
     * 查看消息
     */
    public function view()
    {
        $msgId=$this->data["id"]; 
        $msg = Msg::get_by_id($msgId); 
        $this->view->set("msg",$msg);
    }
    /**
     * 编辑消息
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $msg = $this->model->Msg;
            $id= $msg->getId(); 
            if (!empty($id)){
              $msg->update(); 
            }else{
              $id=$msg->save();  
            }
            $this->redirect("msg","view","id=$id");
        }else{
            $msgId=$this->data["id"];
            $msg = Msg::get_by_id($msgId);
            $this->view->set("msg",$msg); 
        }
    }
    /**
     * 删除消息
     */
    public function delete()
    {
        $msgId=$this->data["id"]; 
        $isDelete = Msg::deleteByID($msgId); 
        $this->redirect("msg","lists",$this->data);
    }
}

?>