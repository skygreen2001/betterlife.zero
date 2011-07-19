<?php
/**
 +---------------------------------<br/>
 * 接口:Javascript Ajax 框架的工具类实现接口
 +---------------------------------<br/>
 * @category betterlife
 * @package util.view.ajax
 * @author zhouyuepu
 */
interface IUtilAjax {
    /**
     * 动态加载Ajax Javascript Framework库
     * @param string $version Ajax框架的运行版本
     */
    public static function load($version="");
    
    /**
     * 发送Ajax请求的语句
     * @param string $url 通信的Url地址。
     * @param array $dataArray 传送的数据。
     * @param array $method HTTP的标准协议动词【get|post|put|delete】。
     * @param enum $response_type 返回的数据类型
     * @param string $callback Javascript调用的回执方法名。
     * @return 发送Ajax请求的语句
     */
    public static function ajaxRequstStatement($url,$dataArray,$method,$response_type=EnumResponseType::XML,$callback=null);
    
    /**
     * 生成Javascript的回调函数
     * @param string $local_service_flag 对象名称
     * @param string $callback 回调函数
     * @param enum $response_type 返回的数据类型
     * @return string 回调函数
     */
    public static function callbackForJsFramework($local_service_flag,$response_type=EnumResponseType::XML);
}

?>
