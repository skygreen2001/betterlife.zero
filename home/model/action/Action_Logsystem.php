<?php
/**
 +---------------------------------------<br/>
 * 控制器:系统日志<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.front.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Logsystem extends Action
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
		$bb_page=UtilPage::init($nowpage,$count);
		$this->view->countLogsystems=$count;
		$logsystems = Logsystem::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
		$this->view->set("logsystems",$logsystems);
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