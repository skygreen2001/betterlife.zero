<?php
/**
 +---------------------------------<br/>
 * 通过HttpClient与第三方通信<br/>
 +---------------------------------
 * @category betterlife
 * @package module.communication.httpclient
 * @author skygreen
 */
class Comm_HttpClient {
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
        $httpbase= new HttpExchangeBase($response_type);
        $result=$httpbase->post($url, $data, $headers,$callback);            
        unset( $httpbase );
        return $result;
    }

    /**
    * 发送Get请求
    * @param string $url 请求Url
    * @param array $headers 请求头信息。
    * @param mixed $callback 回调函数
    * @return mixed 返回内容
    */
    public static function get($url,$headers = null,$response_type=EnumResponseType::XML)
    {
        $httpbase= new HttpExchangeBase($response_type);
        $result=$httpbase->get($url, $headers);
        unset( $httpbase );
        return $result;
    }    
    
    /**
     * 发送Put请求
     * @param string $url
     * @param mixed $data
     * @param mixed $headers
     * @return mixed 
     */
    public static function put($url,$data, $headers = null,$callback = null,$response_type=EnumResponseType::XML) {
        $httpbase= new HttpExchangeBase($response_type);
        $result=$httpbase->put($url, $data, $headers,$callback);
        unset( $httpbase );
        return $result;        
    }       
    
    /**
     * 发送Delete请求
     * @param string $url
     * @param mixed $data
     * @param mixed $headers
     * @return mixed 
     */
    public static function delete($url,$data, $headers = null,$callback = null,$response_type=EnumResponseType::XML) {
        $httpbase= new HttpExchangeBase($response_type);
        $result=$httpbase->delete($url, $data, $headers,$callback);
        unset( $httpbase );
        return $result;     
    } 
    
    /**
    * ping 指定请求的Url地址，看是否该请求地址存在可响应。
    * @param mixed $url
    * @return bool 是否该请求地址存在可响应
    */
    public static function ping( $url )
    {
        $httpbase= new HttpExchangeBase();
        $result=$httpbase->ping($url);            
        unset( $httpbase );
        return $result;
    }  
        
    /**
    * 上传文件
    * @param string $url 请求url地址
    * @param mixed $files 需要上传的文件们。
    * @param mixed $data 需要上传的form数据。
    * @param mixed $headers 头信息
    * @param mixed $callback 回调函数
    * @return mixed 返回内容
    */
    public static function upload( $url, $files, $data, $headers = null){        
        $httpbase= new HttpExchangeBase();
        $result=$httpbase->upload( $url, $files, $data, $headers, $callback);
        unset( $httpbase );
        return $result;
    }   
}

?>
