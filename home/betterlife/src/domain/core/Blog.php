<?php
/**
 +---------------------------------------<br/>
 * 博客<br/>
 +---------------------------------------
 * @category betterlife
 * @package core
 * @author skygreen skygreen2001@gmail.com
 */
class Blog extends DataObject
{
	//<editor-fold defaultstate="collapsed" desc="定义部分">
	/**
	 * 标识
	 * @var int
	 * @access public
	 */
	public $blog_id;
	/**
	 * 用户标识
	 * @var int
	 * @access public
	 */
	public $user_id;
	/**
	 * 博客名称
	 * @var string
	 * @access public
	 */
	public $blog_name;
	/**
	 * 博客内容
	 * @var string
	 * @access public
	 */
	public $content;
	//</editor-fold>       
	static $has_many=array(
	  "comments"=>"Comment"
	);

	static $belong_has_one=array(
	  "user"=>"User"
	);
	
	/**
	 * 数据库使用SqlServer，需使用字符转换:GBK->UTF8
	 */
	public function getBlog_nameShow() {
		$name=UtilString::gbk2utf8($this->name);         
		return $name;   
	}
	
	/**
	 * 数据库使用SqlServer，需使用字符转换:GBK->UTF8
	 */
	public function getContentShow() {
		$content=UtilString::gbk2utf8($this->content);         
		return $content;
	}    
					 
	/**
	* 当前登录用户是否可编辑该博客
	* @return bool true 可以
	*/
	public function canEdit(){
		if (HttpSession::get("user_id")==$this->user_id) {
			return true;
		}       
		return false;
	}
	
	/**
	* 当前登录用户是否可删除该博客
	* @return bool true 可以
	*/
	public function canDelete(){
		if (HttpSession::get("user_id")==$this->user_id) {
			return true;
		}       
		return false;
	}   
}
?>