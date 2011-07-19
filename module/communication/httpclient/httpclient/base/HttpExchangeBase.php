<?php
/**
+---------------------------------<br/>
* 访问第三方网络请求<br/>
+---------------------------------
* @category betterlife
* @package module.communication.httpclient
* @subpackage base
* @author skygreen
*/
class HttpExchangeBase extends Object 
{   
    private $timeout = 10;
    private $defaultChunk = 4096;
    private $http_ver = "1.1";    
    private $proxyHost;
    private $proxyPort;
    private $responseCode;
    private $responseHeader;
    private $response_type;
    private $responseBody;
    
    /**
     +-------------------------------------------<br/>
     * 获取fsocket里的返回内容
     * google查询关键字：去掉fsockopen返回结果中的HTTP头(header)信息<br/>
     * 1.第一种解决方案<br/>
     *    【使用split或substr,strpos截断】在返回的内容中HTTP头信息与正文内容<br/>
     *    是以两个“换行回车”隔开的所以我们可以在此截断，取之后的内容。<br/>
     * 2.第二种解决方案<br/>
     *    【先取Content-Length,然后截取】在HTTP协议中，Content-Length字段是一个比较重要的字段，<br/>
     *     它标明了服务器返回数据的长度，这个长度是不包含HTTP头长度的，<br/>
     *     也就是说我们可以从 总长度－Content-Length 开始截取<br/>
     +-------------------------------------------<br/>
     * @link http://darkend.blog.163.com/blog/static/1756620152011065216996/
     * @param string $content 通过fsocket获取的返回信息包括头信息。
     * @return string 只有需要的返回内容，无头信息 
     */
    private function getReturnContent($content){
        if($content&&(strlen($content)>0)){
            //第二种解决方案
            preg_match("/Content-Length:.?(\d+)/", $content, $matches);
            if (is_array($matches)&&(count($matches)>1)){
                $length = $matches[1];
                $content = substr($content, strlen($content) - $length);
            }else{
                //第一种解决方案
                $resultArr= stristr($content,"\r\n\r\n"); 
                $reult=substr($resultArr,4,strlen($resultArr));
                return  $reult;                
            }
        }
        return $content;
    }
    
    public function HttpExchangeBase($response_type=EnumResponseType::XML){        
        $this->responseHeader['Pragma'] = "no-cache";
        $this->responseHeader['Cache-Control'] = "no-cache";
        $this->responseHeader['Connection'] = "close";
        $this->responseHeader['Referer'] = Gc::$url_base;
        $this->responseHeader['Content-Type'] = "application/x-www-form-urlencoded";
        $this->response_type=$response_type;
        $this->responseHeader['response_type'] =$this->response_type;
    }
    
    /**
    * 发送请求到第三方。
    * 
    * @param string $action 请求Action，可以为Post、Get方式。
    * @param string $url
    * @param array $headers 请求头信息
    * @param mixed $callback 回调函数
    * @param string $data 发送传递的数据
    * @return string 返回内容
    */
    private function action( $action, $url,$data = null,$headers = null, $callback = null)
    {
        $url_info = parse_url( $url );
        $out = strtoupper( $action )." ".( isset( $url_info['path'] ) ? $url_info['path'] : "/" ).( isset( $url_info['query'] ) ? "?".$url_info['query'] : "" )." HTTP/{$this->http_ver}\r\n";
        $host = isset( $url_info['port'] ) ? $url_info['host'].":".$url_info['port'] : $url_info['host'];
        $out .= "Host: ".$host."\r\n";

        $this->responseBody =& $responseBody;
        if (!empty($headers)){
            $this->responseHeader=$headers;
            $this->responseHeader[response_type] =$this->response_type;
        }        
        if ( $data )
        {
            if ( is_array( $data ) )
            {
                $data = http_build_query( $data );
            }
            $this->responseHeader['Content-length'] = strlen( $data );
        }                
        foreach ($this->responseHeader as $k => $v )
        {
            $out .= $k.":".$v."\r\n";
        }
        $out .= "\r\n".$data;
        $data = null;
        $responseHeader = array( );
        try{
            $host=gethostbyname($this->proxyHost ? $this->proxyHost : $url_info['host']);
            $fp = @fsockopen($host,$this->proxyPort?$this->proxyPort:isset( $url_info['port'])?$url_info['port']:80,$errno,$errstr,$this->timeout);
            if ($fp)
            {
                fwrite( $fp, $out );
                $out = null;
                $responseBody = "";
                $status = fgets( $fp, 512 );
                if ( preg_match( "/\\d{3}/", $status, $match ) )
                {
                    $this->responseCode = $match[0];
                }
                while ( !!feof( $fp ) || !( $raw = trim( fgets( $fp, 512 ) ) ) )
                {
                    $p = strpos( $raw, ":" );
                    if ( $p )
                    {
                        $responseHeader[strtolower( trim( substr( $raw, 0, $p ) ) )] = trim( substr( $raw, $p + 1 ) );
                        continue;
                    }
                    else
                    {
                        break;
                    }
                }
                if ( isset( $responseHeader['location'] ) )
                {
                    return $responseHeader['location']( $action, $responseHeader['location'], $this->responseHeader, $callback );
                }
                if ( !( $chunkmode = isset( $responseHeader['transfer-encoding'] ) && $responseHeader['transfer-encoding'] == "chunked" ) )
                {
                    $chunklen = $this->defaultChunk;
                }
                while ( !feof( $fp ) && ( !$chunkmode || ( $chunklen = hexdec( trim( $a = fgets( $fp, 30 ) ) ) ) ) )
                {
                    $content = fread( $fp, $chunklen );
                    $readlen = strlen( $content );
                    while ( $chunklen != $readlen )
                    {
                        $buffer = fread( $fp, $chunklen - $readlen );
                        if ( !strlen( $buffer ) )
                        {
                            break;
                        }
                        $readlen += strlen( $buffer );
                        $content .= $buffer;
                    }
                    if ( $callback )
                    {
                        if ( !call_user_func_array( $callback, array( $this, $content ) ) )
                        {
                            break;
                        }
                    }
                    else
                    {
                        $responseBody .= $content;
                    }
                    if ( $chunkmode )
                    {
                        fread( $fp, 2 );
                    }
                }
                fclose( $fp );
                if ( $callback )
                {
                    return $this->responseCode[0] == 2;
                }
                else
                {
                    if ( $this->responseCode[0] == 2 )
                    {
                        $responseBody=$this->getReturnContent($responseBody);
                        return $responseBody;
                    }
                    else
                    {
                        if (!UtilString::is_utf8($responseBody)){
                            LogMe::log(UtilString::gbk2utf8($responseBody));
                        }
                        $this->errorInfo = $responseBody;
                        return false;
                    }
                }
            }
            else
            {
                LogMe::log($errno.":".  UtilString::gbk2utf8($errstr));
                //LogMe::log_email($errno.":".$errstr);
                return false;
            }
        }catch(Exception $ex){
            LogMe::log_email($ex->getMessage());
            return false;
        }
    }
    
    /**
    * 发送Get请求
    * 
    * @param string $url 请求Url
    * @param array $headers 请求头信息。
    * @param mixed $callback 回调函数
    * @return mixed 返回内容
    */
    public function get( $url, $headers = null)
    {
        return $this->action( "get", $url, $headers);
    }

    /**
    * 发送Post请求
    * 
    * @param string $url 请求Url
    * @param string $data 发送传递的数据
    * @param array $headers 请求头信息。
    * @param mixed $callback 回调函数
    * @return mixed 返回内容
    */
    public function post( $url, $data, $headers = null, $callback = null )
    {
        return $this->action( "post", $url, $data, $headers, $callback);
    }
    
    /**
    * 发送Put请求
    * @param string $url 请求Url
    * @param string $data 发送传递的数据
    * @param array $headers 请求头信息。
    * @param mixed $callback 回调函数
    * @return mixed 返回内容
    */
    public function put( $url, $data, $headers = null, $callback = null )
    {
        return $this->action( "put", $url, $data, $headers, $callback);
    }    
    
    /**
    * 发送Delete请求
    * @param string $url 请求Url
    * @param string $data 发送传递的数据
    * @param array $headers 请求头信息。
    * @param mixed $callback 回调函数
    * @return mixed 返回内容
    */
    public function delete( $url, $data, $headers = null, $callback = null )
    {
        return $this->action( "delete", $url, $data, $headers, $callback);
    }    

    /**
    * ping 指定请求的Url地址，看是否该请求地址存在可响应。
    * 
    * @param mixed $url
    * @return string
    */
    public function ping( $url )
    {
        return $this->action("GET",$url,array($this,"_void"));
    }
    
    /**
    * 上传文件
    * 
    * @param string $url 请求url地址
    * @param mixed $files 需要上传的文件们。
    * @param mixed $data 需要上传的form数据。
    * @param mixed $headers 头信息
    * @param mixed $callback 回调函数
    * @return mixed
    */
    public function upload( $url, $files, $data, $headers = null, $callback = null )
    {
        $boundary = "----BetterlifeFormBoundaryFbui11wi16fe28";
        $headers['Content-Type'] = "multipart/form-data; boundary=".$boundary;
        $formData = array( );
        $this->_http_query( $formData, $data );
        $output = "";
        foreach ( $formData as $k => $v )
        {
            $output .= "--".$boundary."\r\n";
            $output .= "Content-Disposition: form-data; name=\"".str_replace( "\"", "\\\\\"", $k )."\"\r\n\r\n";
            $output .= $v."\r\n";
        }
        foreach ( $files as $k => $v )
        {
            $output .= "--".$boundary."\r\n";
            $output .= "Content-Disposition: form-data; name=\"".str_replace( "\"", "\\\\\"", $k )."\"; filename=\"".basename( $v )."\"\r\n";
            $mime = function_exists( "mime_content_type" ) ? mime_content_type( $v ) : "application/octet-stream";
            $output .= "Content-Type: ".$mime."\r\n\r\n";
            $output .= file_get_contents( $v )."\r\n";
        }
        $output .= "--".$boundary."--\r\n";
        return $this->action( "post", $url,$output,$headers,$callback);
    }  
    
    
    private function _http_query( &$return, $data, $prefix = null, $key = "" )
    {
        $ret = array( );
        foreach ( $data as $k => $v )
        {
            if ( is_int( $k ) && $prefix != null )
            {
                $k = $prefix.$k;
            }
            if ( empty( $key ) )
            {
                $k = $key."[".$k."]";
            }
            if ( is_array( $v ) || is_object( $v ) )
            {
                $this->_http_query( $return, $v, "", $k );
            }
            else
            {
                $return[$k] = $v;
            }
        }
    }

    private function _void( )
    {
        return false;
    }  
}
?>
