<?php
/**
 +---------------------------------------<br/>
 * 控制器:系统日志<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Logsystem extends ActionModel
{
    /**
     * 系统日志列表
     */
    public function lists()
    {
        if ($this->isDataHave(UtilPage::$linkUrl_pageFlag)){
            $nowpage=$this->data[UtilPage::$linkUrl_pageFlag];
        }else{
            $nowpage=1;
        }
        $count=Logsystem::count();
        $this->view->countLogsystems=$count;
        if($count>0){            $bb_page=UtilPage::init($nowpage,$count);
            $logsystems = Logsystem::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
            $this->view->set("logsystems",$logsystems);
        }
    }
    /**
     * 查看系统日志
     */
    public function view()
    {
        $logsystemId=$this->data["id"];
        $logsystem = Logsystem::get_by_id($logsystemId);
        $this->view->set("logsystem",$logsystem);
    }
    /**
     * 编辑系统日志
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $logsystem = $this->model->Logsystem;
            $id= $logsystem->getId();
            $isRedirect=true;
            if (!empty($id)){
                $logsystem->update();
            }else{
                $id=$logsystem->save();
            }
            if ($isRedirect){
                $this->redirect("logsystem","view","id=$id");
                exit;
            }
        }
        $logsystemId=$this->data["id"];
        $logsystem = Logsystem::get_by_id($logsystemId);
        $this->view->set("logsystem",$logsystem);
    }
    /**
     * 删除系统日志
     */
    public function delete()
    {
        $logsystemId=$this->data["id"];
        $isDelete = Logsystem::deleteByID($logsystemId);
        $this->redirect("logsystem","lists",$this->data);
    }
}

?>