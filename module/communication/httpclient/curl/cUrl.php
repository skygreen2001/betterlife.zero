<?php

/**
  +---------------------------------<br/>
 * cUrl核心功能基本实现类<br/>
  +---------------------------------
 * @category betterlife
 * @package module.communication
 * @subpackage curl
 * @author skygreen
 */
class cUrl {
    private $headers;
    private $response_type;
    private $user_agent;
    private $compression;
    private $cookie_file;
    private $proxy;
    private $cookies;

    public function cURL($response_type=EnumResponseType::XML,$cookies=FALSE,$cookie='cookies.txt',$compression='gzip',$proxy='') {
        $this->headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
        $this->headers[] = 'Connection: Keep-Alive';
        $this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
        $this->response_type=$response_type;
        $this->headers[] = 'response_type:'.$response_type;
        $this->user_agent = 'Betterlife Curl Client';
        $this->compression=$compression;
        $this->proxy=$proxy;
        $this->cookies=$cookies;
        //if ($this->cookies == TRUE) $this->cookie($cookie);
    }

    public function cookie($cookie_file) {
        if (file_exists($cookie_file)) {
            $this->cookie_file=$cookie_file;
        } else {
            fopen($cookie_file,'w') or $this->error('The cookie file could not be opened. Make sure this directory has the correct permissions');
            $this->cookie_file=$cookie_file;
            fclose($this->cookie_file);
        }
    }
    
    /**
     * Http Get
     * @param string $url
     * @param mixed $headers
     * @return mixed 
     */
    public function get($url,$headers = null) {
        $process = curl_init($url);
        if ($process){
            if (!empty($headers)){                
                $this->headers=$headers;
                $this->headers[] = 'response_type:'.  $this->response_type;
            }
            curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
            curl_setopt($process, CURLOPT_HEADER, 0);
            curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
            if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
            if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
            curl_setopt($process,CURLOPT_ENCODING , $this->compression);
            curl_setopt($process, CURLOPT_TIMEOUT, 5);
            if ($this->proxy) curl_setopt($cUrl, CURLOPT_PROXY, 'proxy_ip:proxy_port');
            curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($process, CURLOPT_FOLLOWLOCATION, true);
            $return = curl_exec($process);
            $status = curl_getinfo($process, CURLINFO_HTTP_CODE); 
            if ( $status != 200 ) {
                LogMe::log("发送Put请求失败：".$url."?".$data.print_r($headers));
            }            
            curl_close($process);
            return $return;
        }else{
            return false;
        }
    }
    
    /**
     * Http Post
     * @param string $url
     * @param mixed $data
     * @param mixed $headers
     * @return mixed 
     */
    public function post($url,$data, $headers = null) {
        $process = curl_init($url);        
        if ($process){ 
            $data = (is_array($data)) ? http_build_query($data) : $data;   
            if (!empty($headers)){
                $this->headers=$headers;
                $this->headers[] = 'response_type:'.  $this->response_type;
            }        
            curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
            if (Gc::$dev_debug_on){
                ///是否在输出里包含头信息
                //curl_setopt($process, CURLOPT_HEADER, 1);
            }
            curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
            if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
            if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
            curl_setopt($process, CURLOPT_ENCODING , $this->compression);
            curl_setopt($process, CURLOPT_TIMEOUT, 30);
            if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy);
            curl_setopt($process, CURLOPT_POSTFIELDS, $data);
            curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($process, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($process, CURLOPT_POST, true);        
            $return = curl_exec($process);
            $status = curl_getinfo($process, CURLINFO_HTTP_CODE); 
            if ( $status != 200 ) {
                LogMe::log("发送Post请求失败：".$url."?".$data.print_r($headers));
            }            
            curl_close($process);
            return $return;
        }else{
            return false;
        }
    }
        
    /**
     * Http Put
     * @param string $url
     * @param mixed $data
     * @param mixed $headers
     * @return mixed 
     */
    public function put($url,$data, $headers = null) {
        $process = curl_init($url);
        if ($process){
            $data = (is_array($data)) ? http_build_query($data) : $data;         
            if (!empty($headers)){
                $this->headers=$headers;
                $this->headers[] = 'response_type:'.  $this->response_type;
            }        
            curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
            if (Gc::$dev_debug_on){
                ///是否在输出里包含头信息
                //curl_setopt($process, CURLOPT_HEADER, 1);
            }

            curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
            if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
            if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
            curl_setopt($process, CURLOPT_ENCODING , $this->compression);
            curl_setopt($process, CURLOPT_TIMEOUT, 30);
            if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy);
            curl_setopt($process, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($process, CURLOPT_POSTFIELDS, $data);
            curl_setopt($process, CURLOPT_FOLLOWLOCATION, true);
            $return = curl_exec($process);
            $status = curl_getinfo($process, CURLINFO_HTTP_CODE); 
            if ( $status != 200 ) {
                LogMe::log("发送Put请求失败：".$url."?".$data.print_r($headers));
            }
            curl_close($process);
            return $return;
        }else{
            return false;
        }
    }        
        
    /**
     * Http Delete
     * @param string $url
     * @param mixed $data
     * @param mixed $headers
     * @return mixed 
     */
    public function delete($url,$data, $headers = null) {
        $process = curl_init($url);
        if ($process){
            $data = (is_array($data)) ? http_build_query($data) : $data;         
            if (!empty($headers)){
                $this->headers=$headers;                
                $this->headers[] = 'response_type:'.  $this->response_type;
            }        
            curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
            if (Gc::$dev_debug_on){
                ///是否在输出里包含头信息
                //curl_setopt($process, CURLOPT_HEADER, 1);
            }

            curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
            if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
            if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
            curl_setopt($process, CURLOPT_ENCODING , $this->compression);
            curl_setopt($process, CURLOPT_TIMEOUT, 30);
            if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy);
            curl_setopt($process, CURLOPT_CUSTOMREQUEST, "Delete");
            curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($process, CURLOPT_POSTFIELDS, $data);
            curl_setopt($process, CURLOPT_FOLLOWLOCATION, true);
            $return = curl_exec($process);
            $status = curl_getinfo($process, CURLINFO_HTTP_CODE); 
            if ( $status != 200 ) {
                LogMe::log("发送Delete请求失败：".$url."?".$data.print_r($headers));
            }
            curl_close($process);
            return $return;
        }else{
            return false;
        }
    }        
    
    public function error($error) {
        echo "<center><div style='width:500px;border: 3px solid #FFEEFF; padding: 3px; background-color: #FFDDFF;font-family: verdana; font-size: 10px'><b>cURL Error</b><br>$error</div></center>";
        die;
    }
}

?>
