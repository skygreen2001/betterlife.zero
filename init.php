<?php
header("Content-Type:text/html; charset=UTF-8");
if ( !defined('__DIR__') ) define('__DIR__', dirname(__FILE__));  
require_once 'Gc.php';//加载全局变量文件
require_once 'core/main/Initializer.php'; 

/**
 * 相当于__autoload加载方式
 * 但是当第三方如Flex调用时__autoload无法通过其ZendFrameWork加载模式；
 * 需要通过spl_autoload_register的方式进行加载,方能在调用的时候进行加载
 * @param string $class_name 类名
 */
function class_autoloader($class_name) 
{
	Initializer::autoload($class_name);
}

spl_autoload_register("class_autoloader");

if (!isset($is_loadclass)||$is_loadclass==true) {
	Initializer::initialize();
}
?>
