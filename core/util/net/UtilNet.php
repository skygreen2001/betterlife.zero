<?php
/**
 +---------------------------------<br/>
 * 工具类：网络<br/>
 +---------------------------------
 * @category betterlife
 * @package util.net
 * @author skygreen
 */
class UtilNet extends Util
{
    /**
    * 从网络Url地址中获取域名或者Ip地址
    * @param $url 网络Url地址
    * @return 域名或者Ip地址
    */
    public static function host($url)
    {
        // get host name from URL
        preg_match('@^(?:http://)?([^/]+)@i',
            $url, $matches);
        $host = $matches[1];

        // get last two segments of host name
        //preg_match('/[^.]+\.[^.]+$/', $host, $matches);
        //echo "domain name is: {$matches[0]}\n";
        //return $matches[0];
        return $host;
    }

    /**
     * 获取域名或者Ip地址
     * @return 域名或者Ip地址
     */
    public static function hostname()
    {
        $addrs = array();
        if(isset($_SERVER['HTTP_X_FORWARDED_HOST'])){
            $addrs = array_reverse( explode( ',',  $_SERVER['HTTP_X_FORWARDED_HOST'] ) );
        }
        return isset($addrs[0])?trim($addrs[0]):$_SERVER['HTTP_HOST'];
    }

    /**
     * 获取网站的根路径
    *  @param string $with_file 如指定文件名。
     * @return 网站的根路径
     */
    public static function urlbase()
    {
        $with_file=$_SERVER["SCRIPT_FILENAME"];
        $file_sub_dir=dirname($with_file).DIRECTORY_SEPARATOR;
        $file_sub_dir=str_replace("/", DIRECTORY_SEPARATOR, $file_sub_dir);
        if(contain($file_sub_dir,Gc::$nav_root_path)){
            $file_sub_dir=str_replace(Gc::$nav_root_path, "", $file_sub_dir);
        }else{
            $file_sub_dir=str_replace($_SERVER["DOCUMENT_ROOT"], "", $file_sub_dir);
        }
        $file_sub_dir=str_replace(DIRECTORY_SEPARATOR, "/", $file_sub_dir);
        $url_base=Gc::$url_base;
        if($file_sub_dir!="/")$url_base=str_replace($file_sub_dir, "", $url_base);
        if(!endwith($url_base,"/"))$url_base.="/";
        return $url_base;
    }

    /**
    * 获取指定文件的Url基本路径
    * @param string $with_file 如指定文件名，则路径会带上文件名。
    * @return Url基本路径
    */
    public static function base_url($with_file=false)
    {
        if(isset($_SERVER['HTTPS']) && strpos('on',$_SERVER['HTTPS'])){
            $baseurl = 'https://'.$_SERVER['HTTP_HOST'];
            if($_SERVER['SERVER_PORT']!=443)$baseurl.=':'.$_SERVER['SERVER_PORT'];
        }else{
            $baseurl = 'http://'.$_SERVER['HTTP_HOST'];
            if($_SERVER['SERVER_PORT']!=80)$baseurl.=':'.$_SERVER['SERVER_PORT'];
        }
        if($with_file){
            $baseurl.=$_SERVER['SCRIPT_NAME'];
        }else{
            $baseDir = dirname($_SERVER['SCRIPT_NAME']);
            $baseurl.=($baseDir == '\\' ? '' : $baseDir).'/';
        }
        return $baseurl;
    }


    /**
     * 下载数据到文件。
     * @param string $fname  文件名
     * @param string $data 数据
     * @param string $mimeType MIME类型。
     */
    public static function download($fname='data',$data=null,$mimeType='application/force-download')
    {
        if(headers_sent($file,$line)){
            echo 'Header already sent @ '.$file.':'.$line;
            exit();
        }

        //header('Cache-Control: no-cache;must-revalidate'); //fix ie download bug
        header('Pragma: no-cache, no-store');
        header("Expires: Wed, 26 Feb 1997 08:21:57 GMT");

        if(strpos($_SERVER["HTTP_USER_AGENT"],'MSIE')){
            $fname = urlencode($fname);
            header('Content-type: '.$mimeType);
        }else{
            header('Content-type: '.$mimeType.';charset=utf-8');
        }
        header("Content-Disposition: attachment; filename=\"".$fname.'"');
        //header( "Content-Description: File Transfer");

        if($data){
            header('Content-Length: '.strlen($data));
            echo $data;
            exit();
        }
    }

    /**
     * 在网络上发送文件内容
     */
    function sendfile($file)
    {
        $handle = fopen($file, "r");
        while($buffer = fread($handle,102400)){
            echo $buffer;
            flush();
        }
        fclose($handle);
    }

    /**
     * 创建html标签
     * @param array $params 属性
     * @param string $tag 标签名
     * @param bool $finish 是否结束
     * @return string html标签字符串
     */
    public static function buildTag($params,$tag,$finish=true)
    {
        foreach($params as $k=>$v){
            if(!is_null($v) && !is_array($v)){
                if($k=='value'){
                    $v=htmlspecialchars($v);
                }
                $ret[]=$k.'="'.$v.'"';
            }
        }
        return '<'.$tag.' '.implode(' ',$ret).($finish?' /':'').'>';
    }

    /**
     * 将url query字符串转换成数组
     * Returns the url query as associative array
     * @example http://php.net/manual/en/function.parse-url.php
     * @param    string    query
     * @return    array    params
     */
    public static function parse_urlquery($query)
    {
        $query  = html_entity_decode($query);
        $queryParts = explode('&', $query);

        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }

        return $params;
    }

    /**
     * 获取客户端IP地址
     */
    public static function client_ip()
    {
        //php获取ip的算法
        if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"])
        {
            $ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
        }
        elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"])
        {
            $ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
        }
        elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"])
        {
            $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
        }
        elseif (getenv("HTTP_X_FORWARDED_FOR"))
        {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        }
        elseif (getenv("HTTP_CLIENT_IP"))
        {
            $ip = getenv("HTTP_CLIENT_IP");
        }
        elseif (getenv("REMOTE_ADDR"))
        {
            $ip = getenv("REMOTE_ADDR");
        }
        else
        {
            $ip = "Unknown";
        }
        //echo "你的IP:".$ip ;
        return $ip;
    }
}

?>
