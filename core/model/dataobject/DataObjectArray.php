<?php
/**
 * 数组对象
 * 可以以数组的方式访问数据对象
 * @category betterlife
 * @package core.model
 * @subpackage dataobject
 * @author skygreen
 */
class DataObjectArray extends Object implements ArrayAccess
{
	//<editor-fold defaultstate="collapsed" desc="魔术方法">
	/**
	* 从数组创建对象。
	* @param mixed $array
	* @return DataObject
	*/
	public function __construct($array=null)
	{
		if (!empty($array)&&is_array($array))
			foreach ($array as $key=>$value)
				$this->$key=$value;
	}

	/**
	 * 可设定对象未定义的成员变量[但不建议这样做]<br/>
	 * 类定义变量访问权限设定需要是pulbic
	 * @param mixed $property 属性名
	 * @return mixed 属性值
	 */
	public function __get($property)
	{
		if (property_exists($this,$property)) return $this->$property;
		return null;
	}

	/**
	 * 可设定对象未定义的成员变量[但不建议这样做]<br/>
	 * 类定义变量访问权限设定需要是pulbic
	 * @param mixed $property 属性名
	 * @param mixed $value 属性值
	 */
	public function __set($property, $value)
	{
		return $this->$property=$value;
	}

	 /**
	 * 打印当前对象的数据结构
	 * @return string 描述当前对象。
	 */
	public function __toString() {
		return DataObjectFunc::toString($this);
	}
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="定义数组进入对象方式">
	public function offsetExists($key)
	{
		if ($this->$key) return true;
		return method_exists($this,$key);
	}
	public function offsetGet($key)
	{
		return $this->$key;
	}
	public function offsetSet($key, $value)
	{
		$this->$key=$value;
	}
	public function offsetUnset($key)
	{
		unset($this->$key);
	}
	//</editor-fold>
}
?>
