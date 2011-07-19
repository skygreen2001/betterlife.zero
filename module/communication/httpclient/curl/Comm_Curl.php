<?php
/**
 +---------------------------------<br/>
 * 通过php的Curl extension模拟实现与第三方通信。<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package module.communication
 * @subpackage curl
 * @author skygreen
 */
class Comm_Curl {
    /**
    * 发送Get请求
    * 
    * @param string $url 请求Url
    * @param array $headers 请求头信息。
    * @param mixed $callback 回调函数
    * @return mixed 返回内容
    */
    public static function get($url,$headers = null,$response_type=EnumResponseType::XML) {
        $cUrl=new cUrl($response_type);
        return $cUrl->get($url,$headers);
    }
    
    /**
    * 发送Post请求
    * @param string $url 请求Url
    * @param string $data 发送传递的数据
    * @param array $headers 请求头信息。
    * @param mixed $callback 回调函数
    * @return mixed 返回内容
    */
    public static function post( $url, $data, $headers = null, $callback = null,$response_type=EnumResponseType::XML)
    {
        $cUrl=new cUrl($response_type);
        return $cUrl->post($url, $data, $headers);
    }
    
    /**
     * 发送Put请求
     * @param string $url
     * @param mixed $data
     * @param mixed $headers
     * @return mixed 
     */
    public static function put($url,$data, $headers = null,$callback = null,$response_type=EnumResponseType::XML) {
        $cUrl=new cUrl($response_type);
        return $cUrl->put($url, $data, $headers);
    }       
    
    /**
     * 发送Delete请求
     * @param string $url
     * @param mixed $data
     * @param mixed $headers
     * @return mixed 
     */
    public static function delete($url,$data, $headers = null,$callback = null,$response_type=EnumResponseType::XML) {
        $cUrl=new cUrl($response_type);
        return $cUrl->delete($url, $data, $headers);
    }    
    
}
