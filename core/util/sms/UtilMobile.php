<?php
  
/**
 +---------------------------------<br/>
 * 功能:处理手机信息相关的工具类<br/>
 +---------------------------------
 * @category inesa
 * @package util.common
 * @author skygreen
 */
class UtilMobile extends Util
{
    /**
     * @desc 生成n个随机手机号
     * @param int $num 生成的手机号数
     * @author niujiazhu
     * @return array
     */
    public static function randMobile($num = 1){
        //手机号2-3位为数组
        $numberPlace = array(30,31,32,33,34,35,36,37,38,39,50,51,58,59,89);
        for ($i = 0; $i < $num; $i++){
            $mobile = 1;
            $mobile .= $numberPlace[rand(0,count($numberPlace)-1)];
            $mobile .= str_pad(rand(0,99999999),8,0,STR_PAD_LEFT);
            $result[] = $mobile;
        }
        return $result;
    }
    
    /**
    * 验证是否手机号码
    * 
    * @param mixed $phonenum
    */
    public static function isMobile($phonenum)
    {
        if(preg_match("/1[3458]{1}\d{9}$/",$phonenumber)){
            return true;
        }
        return false;
    }
}
?>
