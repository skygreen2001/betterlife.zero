<?php

/**
 +---------------------------------<br/>
 * 菜单<br/>
 +---------------------------------
 * @category betterlife
 * @package web.back
 * @subpackage menu
 * @author skygreen
 */
class Menu extends Viewable
{
	/**
	 * 标识
	 * @var string 
	 */
	private $id;
	/**
	 * 菜单名称
	 * @var string 
	 */
	private $name;
	/**
	 * 菜单地址
	 * @var string 
	 */
	private $address; 
	/**
	 * 语言文字种类
	 * @var string 
	 */
	private $lang;
	/**
	 * 菜单分组的图标Css样式
	 * @var string 
	 */
	private $iconCls;
	/**
	 * 菜单内容介绍说明
	 * @var string 
	 */
	private $title; 
	/**
	* 所属菜单分组
	* @var mixed
	*/
	private $menuGroup_id;   
	
	public function setId($id)
	{
		$this->id=$id;
	}

	public function getId()
	{
		return $this->id;
	}
																												
	public function setName($name)
	{
		$this->name=$name;
	}

	public function getName()
	{
		return $this->name;
	}
	
	public function setAddress($address)
	{
		$this->address=$address;
	}

	public function getAddress()
	{
		return $this->address;
	}    
		
	public function setIconCls($iconCls)
	{
		$this->iconCls=$iconCls;
	}
	
	public function getIconCls()
	{
		return $this->iconCls;
	}
	
	public function setLang($lang)
	{
		$this->lang=$lang;
	}

	public function getLang()
	{
		return $this->lang;
	}          
	
	public function setTitle($title)
	{
		$this->title=$title;
	}

	public function getTitle()
	{
		return $this->title;
	}  
	
	public function setMenuGroup_id($menuGroup_id)
	{
		$this->menuGroup_id=$menuGroup_id;
	}

	public function getMenuGroup_id()
	{
		return $this->menuGroup_id;
	}        
}

?>
