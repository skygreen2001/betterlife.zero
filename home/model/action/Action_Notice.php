<?php
/**
 +---------------------------------------<br/>
 * 控制器:通知<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.front.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Notice extends Action
{
    /**
     * 通知列表
     */
    public function lists()
    {
        if ($this->isDataHave(UtilPage::$linkUrl_pageFlag)){
          $nowpage=$this->data[UtilPage::$linkUrl_pageFlag];  
        }else{   
          $nowpage=1; 
        }
        $count=Notice::count();
        $bb_page=UtilPage::init($nowpage,$count);
        $this->view->countNotices=$count;
        $notices = Notice::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
        $this->view->set("notices",$notices);
    }
    /**
     * 查看通知
     */
    public function view()
    {
        $noticeId=$this->data["id"]; 
        $notice = Notice::get_by_id($noticeId); 
        $this->view->set("notice",$notice);
    }
    /**
     * 编辑通知
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $notice = $this->model->Notice;
            $id= $notice->getId(); 
            if (!empty($id)){
              $notice->update(); 
            }else{
              $id=$notice->save();  
            }
            $this->redirect("notice","view","id=$id");
        }else{
            $noticeId=$this->data["id"];
            $notice = Notice::get_by_id($noticeId);
            $this->view->set("notice",$notice); 
        }
    }
    /**
     * 删除通知
     */
    public function delete()
    {
        $noticeId=$this->data["id"]; 
        $isDelete = Notice::deleteByID($noticeId); 
        $this->redirect("notice","lists",$this->data);
    }
}

?>