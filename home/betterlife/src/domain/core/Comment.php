<?php
/**
 +---------------------------------------<br/>
 * 评论<br/>
 +---------------------------------------
 * @category betterlife
 * @package core
 * @author skygreen skygreen2001@gmail.com
 */
class Comment extends DataObject
{
	//<editor-fold defaultstate="collapsed" desc="定义部分">
	/**
	 * 标识
	 * @var int
	 * @access public
	 */
	public $comment_id;
	/**
	 * 评论者标识
	 * @var int
	 * @access public
	 */
	public $user_id;
	/**
	 * 评论
	 * @var string
	 * @access public
	 */
	public $comment;
	/**
	 * 博客标识
	 * @var int
	 * @access public
	 */
	public $blog_id;
	//</editor-fold>

	static $belong_has_one=array(
	  "user"=>"User"
	);   

					 
	/**
	* 当前登录用户是否可编辑该评论
	* @return bool true 可以
	*/
	public function canEdit(){
		if (HttpSession::get("user_id")==$this->user_id) {
			return true;
		}       
		return false;
	}
	
	/**
	* 当前登录用户是否可删除该评论
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
