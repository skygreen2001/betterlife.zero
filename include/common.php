<?php
/**
 * 直接执行SQL语句
 * @param mixed $sql SQL查询语句
 * @param string|class|bool $object 需要生成注入的对象实体|类名称
 * @return array 默认返回数组,如果$object指定数据对象，返回指定数据对象列表，$object=true，返回stdClass列表。
 */
function sqlExecute($sqlstring,$object=null)
{
	if ($object){
		if (is_bool($object))$object=null;
		return Manager_Db::newInstance()->currentdao()->sqlExecute($sqlstring,$object);
	}else{
		$lists=Manager_Db::newInstance()->currentdao()->sqlExecute($sqlstring,$object);
		if ($lists){
			foreach ($lists as $key=>$data) {
				$lists[$key]=(array) $data;
			}
		}
		return $lists;
	}
}

/**
 * 设置处理所有未捕获异常的用户定义函数
 */
function e_me($exception) 
{
	ExceptionMe::recordUncatchedException($exception);
	e_view();
}

/**
 * 显示异常处理缩写表示
 */
function e_view() 
{
	if (Gc::$dev_debug_on) {
		echo ExceptionMe::showMessage(ExceptionMe::VIEW_TYPE_HTML_TABLE);
	}
}

/**
 * 查看字符串里是否包含指定字符串
 * @param mixed $subject
 * @param mixed $needle
 */
function contain($subject,$needle)
{
	if (empty($subject))return false;
	if (strpos(strtolower($subject),strtolower($needle))!== false) {
		return true;
	}else {
		return false;
	}
}

/**
 * 查看字符串里是否包含数组中任意一个指定字符串
 * @param mixed $subject
 * @param mixed $array
 */
function contains($subject,$array)
{
	$result=false;
	if (!empty($array)&&is_array($array)){
		foreach ($array as $element){
			if (contain($subject,$element)){
				return true;
			}
		}
	}
	return $result;
}

/**
 * 需要的字符是否在目标字符串的开始
 * @param string $haystack 目标字符串
 * @param string $needle 需要的字符
 * @param bool $strict 是否严格区分字母大小写
 * @return bool true:是，false:否。
 */
function startWith($haystack, $needle,$strict=true)
{
	if (!$strict){
		$haystack=strtoupper($haystack);
		$needle=strtoupper($needle);
	}
	return strpos($haystack, $needle) === 0;
}

/**
 * 需要的字符是否在目标字符串的结尾
 * @param string $haystack 目标字符串
 * @param string $needle 需要的字符
 * @param bool $strict 是否严格区分字母大小写
 * @return bool true:是，false:否。
 */
function endWith($haystack, $needle,$strict=true)
{ 
	if (!$strict){
		$haystack=strtoupper($haystack);
		$needle=strtoupper($needle);
	}
	return (strpos(strrev($haystack), strrev($needle)) === 0);
}

/** 
 * js escape php 实现 
 * 参考：PHP实现javascript的escape和unescape函数【http://js8.in/941.html】 
 * @param $string the sting want to be escaped 
 * @param $in_encoding       
 * @param $out_encoding      
 */
function escape($string, $in_encoding = 'UTF-8',$out_encoding = 'UCS-2')
{
	$return = '';
	if (function_exists('mb_get_info')) {
		for($x = 0; $x < mb_strlen ( $string, $in_encoding ); $x ++) {
			$str = mb_substr ( $string, $x, 1, $in_encoding );
			if (strlen ( $str ) > 1) { // 多字节字符
				$return .= '%u' . strtoupper ( bin2hex ( mb_convert_encoding ( $str, $out_encoding, $in_encoding ) ) );
			} else {
				$return .= '%' . strtoupper ( bin2hex ( $str ) );
			}
		}
	}
	return $return;
}

/**
 * js unescape php 实现
 * 参考：PHP实现javascript的escape和unescape函数【http://js8.in/941.html】
 * @param $string the sting want to be escaped
 * @param $in_encoding
 * @param $out_encoding
 */
function unescape($str)
{
	$ret = '';
	$len = strlen($str);
	for ($i = 0; $i < $len; $i ++)
	{
		if ($str[$i] == '%' && $str[$i + 1] == 'u')
		{
			$val = hexdec(substr($str, $i + 2, 4));
			if ($val < 0x7f)
				$ret .= chr($val);
			else
				if ($val < 0x800)
					$ret .= chr(0xc0|($val>>6)).chr(0x80|($val & 0x3f)); 
				else
					$ret .= chr(0xe0|($val>>12)).chr(0x80|(($val >> 6) & 0x3f)).chr(0x80|($val&0x3f));
			$i += 5; 
		} else
			if ($str[$i] == '%')
			{
				$ret .= urldecode(substr($str, $i, 3));
				$i += 2;
			} else
				$ret .= $str[$i];
	}
	return $ret;
}

/**
 * 专供Flex调试使用的Debug工具
 * @link http://www.adobe.com/cn/devnet/flex/articles/flex_php_05.html
 * @param mixed $var
 */
function logMe($var) 
{
	$filename = dirname(__FILE__) . '/__log.txt';
	if (!$handle = fopen($filename, 'a')) {
		echo "Cannot open file ($filename)";
		return;
	}

	$toSave = var_export($var, true);
	fwrite($handle, "[" . date("y-m-d H:i:s") . "]");
	fwrite($handle, "\n");
	fwrite($handle, $toSave);
	fwrite($handle, "\n");
	fclose($handle);
}

/**
 * 是否直接显示出来
 * @param type $s
 * @param type $isEcho 
 */
function print_pre($s,$isEcho=false)
{
	if ($isEcho){
		print "<pre>";print_r($s);print "</pre>";
	}else{
		return "<pre>".var_export($s,true)."</pre>";
	}
}

?>
