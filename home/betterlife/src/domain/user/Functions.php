<?php
/**
 +---------------------------------------<br/>
 * 功能信息<br/>
 +---------------------------------------
 * @category betterlife
 * @package user
 * @author skygreen skygreen2001@gmail.com
 */
class Functions extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识<br/>
     * 权限编号
     * @var int
     * @access public
     */
    public $functions_id;
    /**
     * 允许访问的URL权限
     * @var string
     * @access public
     */
    public $url;
    //</editor-fold>
}
?>