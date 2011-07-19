<?php
class Data_Take_Normal {
    const USER_AGENT_IE="Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; Tablet PC 2.0)";
    const USER_AGENT_FIREFOX="Mozilla/5.0 (Windows; U; Windows NT 6.1; zh-CN; rv:1.9.1.11) Gecko/20100701 Firefox/3.5.1";

    public static $user_agent=self::USER_AGENT_IE;
    public static $host="zh.wikipedia.org";
    public static $Accept_language="zh-cn";
    public static $Accept_Encoding="gzip, deflate";

    /**
     *
     * 获取指定文件内容
     * @param mixed $url                                               =
     */
    public static function getHtmlContent_file($filename) {

    }


    /**
     *
     * 获取User_Agent浏览器信息
     *
     */
    public static function getUserAgent() {
        return @$_SERVER['HTTP_USER_AGENT'];
    }

    /**
     *
     * 获取指定url内容
     * @param mixed $url                                               =
     */
    public static function getHtmlContent_fopen($url) {
        $content="";
        $reg = '/^http:\/\/[^\/].+$/';
        if (!preg_match($reg, $url)) die($url ." invalid");
        ini_set('user_agent',self::$user_agent);
        $fp = fopen($url, "r") or die("Open url: ". $url ." failed.");
        while($fc = fread($fp, 8192)) {
            $content .= $fc;
        }
        fclose($fp);
        if (empty($content)) {
            die("Get url: ". $url ." content failed.");
        }
        return $content;
    }

    /**
     * 获取网页内容
     *
     * @param mixed $url
     * 示例如下:
     *     http://www.dianping.com/shanghai
     * @return string
     */
    public static function getHtmlContent($url) {
        $reg = '/^http:\/\/[^\/].+$/';
        if (!preg_match($reg, $url)) die($url ." invalid");
        ini_set('user_agent',self::$user_agent);
        $content = file_get_contents($url);
//        $opts = array(
//                'http'=>array(
//                        'method'=>"GET",
//                        'header'=>"Host:".self::$host."\r\n".
//                                "Accept-language:".self::$Accept_language."\r\n".
//                                "User-Agent:".self::$user_agent."\r\n".
//                                "Accept:*//*"
//                )
//        );
//        $context = stream_context_create($opts);
//        $content = file_get_contents($url,false,$context);   

//        $content = htmlspecialchars($content);
        return $content;
    }
    /**
     *
     * 需要打开注释:
     *     extension=php_curl.dll
     *
     * @param mixed $url
     * @return object
     */
    public static function getHtmlContent_Curl($url) {
        $reg = '/^http:\/\/[^\/].+$/';
        if (!preg_match($reg, $url)) die($url ." invalid");
        // create a new cURL resource
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, self::$user_agent);
        // Supported encodings are "identity", "deflate", and "gzip". If an empty string, "", is set, a header containing all supported encoding types is sent.
        curl_setopt($ch,CURLOPT_ENCODING , "deflate");
        // grab URL and pass it to the browser
        $content = curl_exec($ch);
        // close cURL resource, and free up system resources
        curl_close($ch);
        return $content;
    }


    /**
     * 可以将网页保存在同一个目录里
     * 遍历目录获取文件内容
     * @param mixed $save_file
     * @param mixed $dir
     */
    public static function getHtmlContent_byDir($save_file, $dir) {
        ini_set('user_agent',self::$user_agent);
        $dp = opendir($dir);
        if (file_exists($save_file)) @unlink($save_file);
        $fp = fopen($save_file, "a+") or die("Open save file ". $save_file ." failed");
        $content="";
        while(($file = readdir($dp)) != false) {
            if ($file!="." && $file!=".."&&!is_dir($dir.DIRECTORY_SEPARATOR.$file)) {
                echo "Read file ". $file ."...<br/>";
                $file_content = file_get_contents($dir.DIRECTORY_SEPARATOR.$file);
                echo " OK<br/>";
                $content.=$file_content;
                fwrite($fp, $file_content);
            }
        }
        fclose($fp);
        return  $content;
    }

    /**
     * 使用socket获取指定网页
     * 示例：
     *     获取网页数据：http://sh.ganji.com/fang5/
     *         Data_Take_Normal::getHtmlContent_bySocket("fang5/","sh.ganji.com");
     */
    public static function getHtmlContent_bySocket($url,$host) {
        $fp = fsockopen($host, 80, $errno, $errstr, 30) or die("Open ". $url ." failed");
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $header = "GET /".$url." HTTP/1.1\r\n";
            $header .= "Accept:*/*\r\n";
            $header .= "Accept-Language:".self::$Accept_language."\r\n";
//        $header .= "Accept-Encoding:".self::$Accept_Encoding."\r\n";
            $header .= "User-Agent:".self::$user_agent."\r\n";
            $header .= "Host:".$host."\r\n";
            $header .= "Connection: Keep-Alive\r\n";
            //$header .= "Cookie: cnzz02=2; rtime=1; ltime=1148456424859; cnzz_eid=56601755-\r\n\r\n";
            $header .= "Connection: Close\r\n\r\n";
            $contents="";
            fwrite($fp, $header);
            while (!feof($fp)) {
                $contents .= fgets($fp, 8192);
            }
            fclose($fp);
            return $contents;
        }
    }


    /**
     * 获取指定内容里的url
     * @param mixed $host_url
     * @param mixed $file_contents
     */
    public static function getHtmlContent_Url_ByContent($host_url, $file_contents) {
        //$reg = '/^(#|javascript.*?|ftp:\/\/.+|http:\/\/.+|.*?href.*?|play.*?|index.*?|.*?asp)+$/i';
        //$reg = '/^(down.*?\.html|\d+_\d+\.htm.*?)$/i';
        $rex = "/([hH][rR][eE][Ff])\s*=\s*['\"]*([^>'\"\s]+)[\"'>]*\s*/i";
        $reg = '/^(down.*?\.html)$/i';
        preg_match_all ($rex, $file_contents, $r);
        $result = "";
        foreach($r as $c) {
            if (is_array($c)) {
                foreach($c as $d) {
                    if (preg_match($reg, $d)) {
                        $result .= $host_url . $d."\n";
                    }
                }
            }
        }
        return $result;
    }


}
?>
