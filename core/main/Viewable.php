<?php

/**
 +---------------------------------<br/>
 * 所有显示工具类的父类<br/>
 +---------------------------------
 * @category betterlife
 * @package web.back
 * @author skygreen
 */
class Viewable extends BBObject implements ArrayAccess
{
    //<editor-fold defaultstate="collapsed" desc="定义数组进入对象方式">
    public function offsetExists($key)
    {
        $method="get".ucfirst($key);
        return method_exists($this,$method);
    }
    public function offsetGet($key)
    {
        $method="get".ucfirst($key);
        return $this->$method();
    }
    public function offsetSet($key, $value)
    {
        $method="set".ucfirst($key);
        $this->$method($value);
//        $this->$key = $value;
    }
    public function offsetUnset($key)
    {
        unset($this->$key);
    }
    //</editor-fold>
}

?>
