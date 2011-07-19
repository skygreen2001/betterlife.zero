<?php
/**
 * 设置处理所有未捕获异常的用户定义函数
 */
function e_me($exception) {
    ExceptionMe::recordUncatchedException($exception);
    e_view();
}

/**
 * 显示异常处理缩写表示
 */
function e_view() {
    if (Gc::$dev_debug_on) {
        echo ExceptionMe::showMessage(ExceptionMe::VIEW_TYPE_HTML_TABLE);
    }
}
 
/**
 * 查看字符串里是否包含指定字符串
 * @param mixed $subject
 * @param mixed $needle
 */
function contain($subject,$needle) {
    if (strpos($subject,$needle)!== false) {
        return true;
    }else {
        return false;
    }
}

/**
 * 需要的字符是否在目标字符串的开始
 * @param string $haystack 目标字符串
 * @param string $needle 需要的字符
 * @param bool $strict 是否严格区分字母大小写
 * @return bool true:是，false:否。
 */
function startWith($haystack, $needle,$strict=true) {
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
function endWith($haystack, $needle,$strict=true){
    if ($strict){
        return ereg($needle."$", $haystack);
    }else{
        return eregi($needle."$", $haystack);        
    }
}

function print_pre($s)
{
    print "<pre>";print_r($s);print "</pre>";
}

?>
