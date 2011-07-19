<?php

/**
 +--------------------------------------------------<br/>
 * 功能参看PHP 5.3手册get_called_class方法<br/>
 * 以下方法是支持低于PHP 5.3版本的相同功能<br/>
 * 说明：<br/>
 *     该函数与引用经过改写<br/>
 *     主要是因为是应用中发现如果出现了换行，将无法找到静态引用的类<br/>
 *     主要针对以下情况：<br/>
 *     User::get(array("name"=>$user->getName(),<br/>
 *                   "password"=>md5($user->getPassword())));<br/>
 *     因为出现了换行 debug_backtrace的行号显示了下一行；就找不到上一行出现的类名了<br/>
 *     改写就是先找到debug_backtrace的行号，再向上找到第一个出现::的行，再进行匹配查找静态方法引用的类<br/>
 * line:progman at centrum dot sk<br/>
 * 10-Mar-2009 08:49<br/>
 * @see http://php.net/manual/en/function.get-called-class.php
 +--------------------------------------------------<br/>
 */
if (!function_exists('get_called_class')) {
    function get_called_class() {
        $bt = debug_backtrace();
        $lines = file($bt[1]['file']);
        $match_line_start_pos=$bt[1]['line']-1;
        if (!contain($lines[$match_line_start_pos],"::")) {
           $match_line_start_pos=$match_line_start_pos-1;
          while (!contain($lines[$match_line_start_pos],"::")){
            $match_line_start_pos=$match_line_start_pos-1;
            if($match_line_start_pos==-1){
                break;
            }
          }  
        }
        if ($match_line_start_pos>=0){
            preg_match('/([a-zA-Z0-9\_]+)::'.$bt[1]['function'].'/',
                    $lines[$match_line_start_pos],
                    $matches);
            return $matches[1];
        }else{
            return null;
        }
    }
}

/**
* 写字符串到文件。
*/
if(!function_exists('file_put_contents')){
    define('FILE_APPEND', 1);
    function file_put_contents($n, $d, $flag = false) {
        $mode = ($flag == FILE_APPEND || strtoupper($flag) == 'FILE_APPEND') ? 'a' : 'wb';
        $f = @fopen($n, $mode);
        if ($f === false) {
            return 0;
        } else {
            if (is_array($d)) $d = implode($d);
            flock($f, LOCK_EX);
            $bytes_written = fwrite($f, $d);
            flock($f, LOCK_UN);
            fclose($f);
            return $bytes_written;
        }
    }
}

/**
* 该函数只对Utf8编码的值进行Json编码。<br/>
* 返回值的JSON编码呈现。 <br/>
* @param mixed $value Utf8编码的值，除resource以外的类型，最常用的是array数组。
* @return 值的JSON编码呈现
*/
if(!function_exists('json_encode')){
    function json_encode($value) {
        switch(gettype($value)) {
        case 'double':
        case 'integer':
            return $value>0?$value:'"'.$value.'"';
        case 'boolean':
            return $value?'true':'false';
        case 'string':
            return '"'.str_replace(
                array("\n","\b","\t","\f","\r"),
                array('\n','\b','\t','\f','\r'),
                addslashes($value)
            ).'"';
        case 'NULL':
            return 'null';
        case 'object':
            return '"Object '.get_class($value).'"';
        case 'array':
            if (isVector($value)){
                if(!$value){
                    return $value;
                }
                foreach($value as $v){
                    $result[] = json_encode($v);
                }
                return '['.implode(',',$result).']';
            }else {
                $result = '{';
                foreach ($value as $k=>$v) {
                    if ($result != '{') $result .= ',';
                    $result .= json_encode($k).':'.json_encode($v);
                }
                return $result.'}';
            }
        default:
            return '"'.addslashes($value).'"';
        }
    }
}

/**
* 将Json编码的字符串转换成对象或者数组。
* @param string $json Json编码的字符串。
* @param mixed $assoc 当为true的时候,则转换为数组。
* @return mixed 对象或者数组
*/
if(!function_exists('json_decode')){
    function json_decode($json,$assoc){
        include_once(dirname(__FILE__).'/lib/json.php');
        $o = new Services_JSON();
        return $o->decode($json,$assoc);
    }
}

/**
 * 获取HTTP Request Header头信息
 */
if (!function_exists('apache_request_headers')) {
    function apache_request_headers() {
        foreach($_SERVER as $key=>$value) {
            if (substr($key,0,5)=="HTTP_") {
                $key=str_replace(" ","-",ucwords(strtolower(str_replace("_"," ",substr($key,5)))));
                $out[$key]=$value;
            }else{
                $out[$key]=$value;
    }
        }
        return $out;
    }
} 

?>
