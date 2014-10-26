<?php
/**
 +---------------------------------------<br/>
 * 通知<br/>
 +---------------------------------------
 * @category betterlife
 * @package msg
 * @author skygreen skygreen2001@gmail.com
 */
class Notice extends DataObject
{
	//<editor-fold defaultstate="collapsed" desc="定义部分">
	/**
	 * 编号
	 * @var int
	 * @access public
	 */
	public $notice_id;
	/**
	 * 通知分类
	 * @var int
	 * @access public
	 */
	public $noticeType;
	/**
	 * 标题
	 * @var string
	 * @access public
	 */
	public $title;
	/**
	 * 通知内容
	 * @var string
	 * @access public
	 */
	public $notice_content;
	//</editor-fold>

	/**
	 * 一对多关系
	 */
	static $has_many=array(
		"usernotices"=>"Usernotice"
	);

	/**
	 * 从属于多对多关系
	 */
	static $belongs_many_many=array(
		"users"=>"User"
	);

}
?>