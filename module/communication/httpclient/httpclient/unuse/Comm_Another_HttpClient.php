<?php  
  /**
  +---------------------------------<br/>
  * 另一种使用HttpClient实现第三方通信的方案<br/>
  +---------------------------------
  * @category betterlife
  * @package module.communication.httpclient
  * @subpackage unuse
  * @author skygreen
  */
class Comm_Another_HttpClient
{
    
    /**
    * 发送Post请求
    * 
    * @param string $url 请求Url
    * @param string $data 发送传递的数据
    * @param array $headers 请求头信息。
    * @param mixed $callback 回调函数
    * @return mixed 返回内容
    */
    public static function post( $url, $data, $headers = null, $callback = null)
    {
        return self::action(EnumHttpMethod::POST, $url, $data, $headers,$callback);
    }
    
   /**
    * 发送Put请求
    * @param string $url 请求Url
    * @param string $data 发送传递的数据
    * @param array $headers 请求头信息。
    * @param mixed $callback 回调函数
    * @return mixed 返回内容
    */
    public static function put( $url, $data, $headers = null, $callback = null )
    {
        return self::action(EnumHttpMethod::PUT, $url, $data, $headers,$callback);
    }    
    
    /**
    * 发送Delete请求
    * @param string $url 请求Url
    * @param string $data 发送传递的数据
    * @param array $headers 请求头信息。
    * @param mixed $callback 回调函数
    * @return mixed 返回内容
    */
    public static function delete( $url, $data, $headers = null, $callback = null )
    {
        return self::action(EnumHttpMethod::DELETE, $url, $data, $headers,$callback);
    }  
    
    /**
    * 发送Delete请求
    * @param string $url 请求Url
    * @param string $data 发送传递的数据
    * @param array $headers 请求头信息。
    * @param mixed $callback 回调函数
    * @return mixed 返回内容
    */
    public static function action($method=EnumHttpMethod::POST, $url="", $data="", $headers = null, $callback = null )
    {
        $http_client = new HttpClient( HTTP_V11, true );
        $http_client->host =UtilNet::host($url);
        $sub_url_arr=explode($http_client->host,$url);
        $sub_url=$sub_url_arr[1];
        $status=$http_client->$method($sub_url,$data,$headers,$url);
        if ( $status == HTTP_STATUS_OK ) {
            $result=$http_client->get_response_body();
        } else {
            LogMe::log( "发送请求到$url 发生错误 !\n" );
            $result=null;
        }
        $http_client->disconnect();
        unset( $http_client );
        return $result;
    }    

    /**
    * 发送Get请求
    * 
    * @param string $url 请求Url
    * @param array $headers 请求头信息。
    * @return mixed 返回内容
    */
    public static function get($url,$headers = null)
    {        // Grab a RDF file from phpdeveloper.org and display it
        $http_client = new HttpClient( HTTP_V11, false);
        $http_client->host =UtilNet::host($url);
        $sub_url_arr=explode($http_client->host,$url);
        $sub_url=$sub_url_arr[1];
        if ( $http_client->get($sub_url,$headers) == HTTP_STATUS_OK){
            $result=$http_client->get_response_body();
        }else {
            LogMe::log( "发送请求到$url 发生错误 !\r\nServer returned".$http_client->status);
            $result=null;
        }
        unset( $http_client );
        return $result;
    }
    
    public static function samplePost(){
        $form = array(
                    'value' => 1,
                    'date' => '05/20/02',
                    'date_fmt' => 'us',
                    'result' => 1,
                    'lang' => 'eng',
                    'exch' => 'USD',
                    'Currency' => 'EUR',
                    'format' => 'HTML',
                    'script' => '../convert/fxdaily',
                    'dest' => 'Get Table'
                );

        $http_client = new HttpClient( HTTP_V11, true );
        $http_client->host = 'www.oanda.com';
        $status = $http_client->post( '/convert/fxdaily', $form, 'http://www.oanda.com/convert/fxdaily' );
        if ( $status == HTTP_STATUS_OK ) {
            print( $http_client->get_response_body() );
        } else {
            print( "An error occured while requesting your file !\n" );
            print_r( $http_client );
        }
        $http_client->disconnect();
        unset( $http_client );
    }
    
    public static function sampleGet(){
        // Grab a RDF file from phpdeveloper.org and display it
        $http_client = new HttpClient( HTTP_V11, false);
        $http_client->host = 'www.phpdeveloper.org';
        
        if ( $http_client->get( '/phpdev.rdf' ) == HTTP_STATUS_OK)
            print( $http_client->get_response_body() );
        else
            print( 'Server returned ' .  $http_client->status );

        unset( $http_client );
    }
    
    public static function sampleProxy(){
        $http_client = new HttpClient( HTTP_V11, false );
        $http_client->host = 'www.yahoo.com';
        $http_client->use_proxy( 'ns.crs.org.ni', 3128 );
        if ($http_client->get( '/' ) == HTTP_STATUS_OK)
            print_r( $http_client );
        else
            print('Server returned ' . $http_client->status );
        unset( $http_client );
    }
    
    public static function sample_multipart_post(){
        $fields =   array(  'user' => 'GuinuX',
                    'password' => 'mypass',
                    'lang' => 'US'
            );
        
        $files = array();
        $files[] =  array(  'name' => 'myfile1',
                        'content-type' => 'text/plain',
                        'filename' => 'D:\\wamp\\www\\betterlife\\test.php',
                        'data' => 'Hello from File 1 !!!'
            );  
        
        $files[] =  array(  'name' => 'myfile2',
                            'content-type' => 'text/plain',
                            'filename' => __FILE__,
                            'data' => "bla bla bla\nbla bla"
            );  
        
            
        $http_client = new HttpClient( HTTP_V11, false );
        $http_client->host = 'localhost';
        if ($http_client->multipart_post( Gc::$url_base.'data/exchange/enjoyoung/demo.php', $fields, $files ) == HTTP_STATUS_OK )
               print($http_client->get_response_body());
            else
               print('Server returned status code : ' . $http_client->status);
        unset( $http_client );
    }
}
?>
