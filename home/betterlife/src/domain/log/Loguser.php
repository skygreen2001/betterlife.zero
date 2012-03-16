<?php
/**
 +---------------------------------------<br/>
 * 用户日志<br/>
 +---------------------------------------
 * @category betterlife
 * @package domain.log
 * @author skygreen
 */
class Loguser extends DataObject {
	//<editor-fold defaultstate="collapsed" desc="定义部分">
	/**
	 * 用户编号
	 * @var int
	 * @access private 
	 */
	private $userId;

	/**
	 * 类型；枚举类型：
	 * 1.吃饭
	 * 2.干活
	 * 3.睡觉
	 * @var enum
	 * @access private 
	 */
	private $type;

	/**
	 * 一般日志类型决定了内容；这一栏一般没有内容
	 * @var string
	 * @access private 
	 */
	private $content;
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="setter和getter">
	public function setUserId($userId){
		$this->userId=$userId;
	}

	public function getUserId(){
		return $this->userId;
	}

	public function setType($type){
		$this->type=$type;
	}

	public function getType(){
		return $this->type;
	}

	public function setContent($content){
		$this->content=$content;
	}

	public function getContent(){
		return $this->content;
	}
	//</editor-fold>       
	/**
	 * 规格说明
	 * 表中不存在的默认列定义:updateTime
	 * @var mixed
	 */
	public $field_spec=array(
		EnumDataSpec::REMOVE=>array(
			'updateTime'
		)
	);

	/** 
	 * 显示类型<br/>
	 * 1:登录-LOGIN<br/>
	 * 2:写日志-BLOG<br/>
	 * 3:写评论-COMMENT<br/>
	 */
	public function getUserTypeShow()
	{
		return self::userTypeShow($this->userType);
	}

	/** 
	 * 显示类型<br/>
	 * 1:登录-LOGIN<br/>
	 * 2:写日志-BLOG<br/>
	 * 3:写评论-COMMENT<br/>
	 */
	public static function userTypeShow($userType)
	{
		return EnumUserType::userTypeShow($userType);
	}
}
?>