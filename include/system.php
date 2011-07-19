<?php

/**
 * 加载指定模块下的模块文件
 * @var string $moduleName 模块名
 * @var string $module_dir 模块目录
 * @var array $excludes 排除在外要加载的子文件夹
 */
function load_module($moduleName,$module_dir,$excludes=null) {
    $require_dirs=UtilFileSystem::getSubDirsInDirectory($module_dir);
    ///需要包含本目录下的文件。
    
    $tmps=UtilFileSystem::getFilesInDirectory($module_dir);
    foreach ($tmps as $tmp) {
        Initializer::$moduleFiles[$moduleName][basename($tmp,".php")]=$tmp;
    }
    
    if (!empty($excludes)) {
        foreach ($excludes as $exclude) {
            if (array_key_exists($exclude, $require_dirs)) {
                unset ($require_dirs[$exclude]);
            }
        }
    }
    foreach ($require_dirs as $dir) {
        $tmps=UtilFileSystem::getAllFilesInDirectory($dir);
        foreach ($tmps as $tmp) {
            Initializer::$moduleFiles[$moduleName][basename($tmp,".php")]=$tmp;
        }
    }
}


/**
 * 获取对象实体|对象名称的反射类。
 * @param mixed $object 对象实体|对象名称
 * @return 对象实体|对象名称的反射类
 */
function object_reflection($object){
    $class=null;
    if (is_object($object)) {
        $class=new ReflectionClass($object);
    }else{
        if (is_string($object)){
            if (class_exists($object)) {
                $class=new ReflectionClass($object);
            }            
        }
    }
    return $class;
}

function ping_url($url,$data=null){
    $url = parse_url($url);
    if (array_key_exists('query',$url)){
        parse_str($url['query'],$out);
    }
    if (($data!=null)&&(is_array($data))){
        $out=array_merge($out,$data);
    }
    if (isset($out)){
        $url['query'] = '?'.http_build_query($out);
    }
    $host=gethostbyname($url['host']);
    $fp = fsockopen($host, isset($url['port'])?$url['port']:80, $errno, $errstr, 2);
    if (!$fp) {
        return false;
    } else {
        if (array_key_exists('query',$url)){
            $fullUrl="{$url['path']}{$url['query']}";
        }else{
            $fullUrl="{$url['path']}";
        }
        $out = "GET $fullUrl HTTP/1.1\r\n";
        $out .= "Host: {$url['host']}\r\n";
        $out .= "Connection: Close\r\n\r\n";
        fwrite($fp, $out);
        $content="";
        while (!feof($fp)) {
            $content.=fgets($fp, 128);
        }
        return $content;
    }
}

/**
 +----------------------------------------------------------
 * 字符串命名风格转换
 * type
 * =0 将Java风格转换为C的风格
 * =1 将C风格转换为Java的风格
 +----------------------------------------------------------
 * @access protected
 +----------------------------------------------------------
 * @param string $name 字符串
 * @param integer $type 转换类型
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function parse_name($name,$type=0) {
    if($type) {
        return ucfirst(preg_replace("/_([a-zA-Z])/e", "strtoupper('\\1')", $name));
    }else {
        $name = preg_replace("/[A-Z]/", "_\\0", $name);
        return strtolower(trim($name, "_"));
    }
}

?>
