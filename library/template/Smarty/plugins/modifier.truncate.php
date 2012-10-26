<?php 
/**
 * Smarty truncate modifier plugin
 * 参考:Smarty的truncate完美截取中文或中英文混合的字符（解决中文与英文长度不一致的问题）:http://www.justwinit.cn/post/3380/
 * Type:     modifier<br>
 * Name:     truncate<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *               optionally splitting in the middle of a word, and
 *               appending the $etc string or inserting $etc into the middle.
 * 
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php truncate (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com> 
 * @param string $string input string
 * @param integer $length lenght of truncated text
 * @param string $etc end string
 * @param boolean $break_words truncate at word boundary
 * @param boolean $middle truncate in the middle of text
 * @return string truncated string
 */
function smarty_modifier_truncate($string,$sublen=80,$etc='...',$break_words=false,$middle=false)
{  
    $start=0;  
    $code="UTF-8";  
    if($code=='UTF-8'){  
        //如果有中文则减去中文的个数  
        $cncount=cncount($string);  
        if($cncount>($sublen/2)){  
            $sublen=ceil($sublen/2);  
        }else{  
           $sublen=$sublen-$cncount;  
       }  
         
       $pa="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";  
       preg_match_all($pa,$string,$t_string);  
         
       if(count($t_string[0])-$start>$sublen)  
           return join('',array_slice($t_string[0],$start,$sublen))."...";  
       return join('',array_slice($t_string[0],$start,$sublen));  
   }else{  
       $start=$start*2;  
       $sublen=$sublen*2;  
       $strlen=strlen($string);  
       $tmpstr='';  
         
       for($i=0;$i<$strlen;$i++){  
           if($i>=$start&&$i<($start+$sublen)){  
               if(ord(substr($string,$i,1))>129){  
                   $tmpstr.=substr($string,$i,2);  
               }else{  
                   $tmpstr.=substr($string,$i,1);  
               }  
           }  
           if(ord(substr($string,$i,1))>129)  
               $i++;  
       }  
       if(strlen($tmpstr)<$strlen)  
           $tmpstr.="...";  
       return $tmpstr;  
   }
}  

function cncount($str){  
   $len=strlen($str);  
   $cncount=0;  
     
   for($i=0;$i<$len;$i++){  
       $temp_str=substr($str,$i,1);  
         
       if(ord($temp_str)>127){  
           $cncount++;  
       }  
   }  
     
   return ceil($cncount/3);  
}  

/**
 * Smarty truncate modifier plugin
 * 原解决方法:
 * 已解决在Linux服务器上中文乱码的问题
 * 参考了:在linux apache下smarty truncate函数乱码的解决:http://www.speedphp.com/bbs/thread-3255-1-1.html
 * Type:     modifier<br>
 * Name:     truncate<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *               optionally splitting in the middle of a word, and
 *               appending the $etc string or inserting $etc into the middle.
 * 
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php truncate (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com> 
 * @param string $string input string
 * @param integer $length lenght of truncated text
 * @param string $etc end string
 * @param boolean $break_words truncate at word boundary
 * @param boolean $middle truncate in the middle of text
 * @return string truncated string

function smarty_modifier_truncate($string, $length = 80, $etc = '...',
                                  $break_words = false, $middle = false)
{
    if ($length == 0)return '';
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {  
        return windows_smarty_modifier_truncate($string, $length, $etc, $break_words, $middle);  
    } else {  
        return linux_smarty_modifier_truncate($string, $length, $etc, $break_words, $middle);  
    }  
}

function linux_smarty_modifier_truncate($string, $length = 80, $etc = '...',
    $break_words = false, $middle = false)
{
    if (mystrlen($string) > $length) {
        $length -= min($length, mystrlen($etc));
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', mysubstr($string, 0, $length+1));
        }
        if(!$middle) {
            return mysubstr($string, 0, $length) . $etc;
        } else {
            return mysubstr($string, 0, $length/2) . $etc . mysubstr($string, -$length/2);
        }
    } else {
        return $string;
    }
}

function windows_smarty_modifier_truncate($string, $length = 80, $etc = '...',
    $break_words = false, $middle = false)
{
    if (is_callable('mb_strlen')) {
        if (mb_detect_encoding($string, 'UTF-8, ISO-8859-1') === 'UTF-8') {
            // $string has utf-8 encoding
            if (mb_strlen($string) > $length) {
                $length -= min($length, mb_strlen($etc));
                if (!$break_words && !$middle) {
                    $string = preg_replace('/\s+?(\S+)?$/u', '', mb_substr($string, 0, $length + 1));
                } 
                if (!$middle) {
                    return mb_substr($string, 0, $length) . $etc;
                } else {
                    return mb_substr($string, 0, $length / 2) . $etc . mb_substr($string, - $length / 2);
                } 
            } else {
                return $string;
            } 
        } 
    } 
    // $string has no utf-8 encoding
    if (strlen($string) > $length) {
        $length -= min($length, strlen($etc));
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
        } 
        if (!$middle) {
            return substr($string, 0, $length) . $etc;
        } else {
            return substr($string, 0, $length / 2) . $etc . substr($string, - $length / 2);
        } 
    } else {
        return $string;
    } 
} 

function mysubstr($str, $start, $len) {
    $step=is_utf8($str)?2:1;
    $tmpstr = "";
    $strlen = $start + $len;
    for($i = 0; $i < $strlen; $i++) {
        if(ord(substr($str, $i, 1)) > 0xa0) {
           $tmpstr .= substr($str, $i, $step+1);
           $i+=$step;
           $strlen+=$step;
        } else
           $tmpstr .= substr($str, $i, 1);
    }
    return $tmpstr;
}

function mystrlen($str){
    $step=is_utf8($str)?2:1;
    $strlen = $mystrlen=strlen($str);
    for($i = 0; $i < $strlen; $i++) {
       if(ord(substr($str, $i, 1)) > 0xa0) {
        $mystrlen-=$step;
        $i+=$step;
       }
    }
    return $mystrlen;
}

function is_utf8($string) {
     return preg_match('%^(?:
         [\x09\x0A\x0D\x20-\x7E]            # ASCII
       | [\xC2-\xDF][\x80-\xBF]            # non-overlong 2-byte
       | \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
       | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
       | \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
       | \xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
       | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
       | \xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
   )*$%xs', $string);
}
 */
?>