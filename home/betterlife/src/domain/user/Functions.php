<?php
/**
 +---------------------------------------<br/>
 * 功能信息<br/>
 +---------------------------------------
 * @category betterlife
 * @package domain.user
 * @author skygreen
 */
class Functions extends DataObject 
{
	//<editor-fold defaultstate="collapsed" desc="定义部分">
	/**
	 * 允许访问的URL权限
	 * @var string
	 * @access private 
	 */
	private $url;
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="setter和getter">
	public function setUrl($url){
		$this->url=$url;
	}

	public function getUrl(){
		return $this->url;
	}
	//</editor-fold>
}
?>