<?php
/**
 +---------------------------------------<br/>
 * 控制器:消息<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Msg extends ActionModel
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
            //加载在线编辑器的语句要放在:$this->view->viewObject[如果有这一句]之后。
            $this->load_onlineditor('content');
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