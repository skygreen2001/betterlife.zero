<?php
/**
* 输出日期时间的格式
*/
class EnumDateTimeFORMAT extends Enum
{
    const TIMESTAMP=0;
    const DATE=1;
    const STRING=2;
}  

/**
 +---------------------------------<br/>
 * 功能:处理文件目录相关的事宜方法。<br/>
 +---------------------------------
 * @category betterlife
 * @package util.common
 * @author skygreen
 */
class UtilDateTime extends Util {    
    /**
     * 标准日期时间格式：年-月-日 时:分:秒
     */
    const TIMEFORMAT_YMDHIS="Y-m-d H:i:s";
    /**
     * 标准日期时间格式：年-月-日 时:分:秒
     */
    const TIMEFORMAT_YMD="Y-m-d";
    /**
     * 设置当前为中国时区的时间。
     */
    public static function ChinaTime(){
       date_default_timezone_set('Asia/Shanghai');
    }
    
    /**
     +----------------------------------------------------------<br/>
     * 获取现在的时间显示<br/>
     * 格式：年-月-日 小时:分钟:秒<br/> 
     +----------------------------------------------------------<br/>      
     */
    public static function now($type=EnumDateTimeFormat::TIMESTAMP) {
        date_default_timezone_set('Asia/Shanghai');
        $now= date(self::TIMEFORMAT_YMDHIS);
        switch ($type){
            case EnumDateTimeFORMAT::TIMESTAMP: 
                return UtilDateTime::dateToTimestamp($now);      
            case EnumDateTimeFORMAT::DATE:
                return $now;        
            case EnumDateTimeFORMAT::STRING:
                return $now."";        
        }
        return $now;
    }

    /**
     * 将timestamp转换成DataTime时间格式。
     * @param int $timestamp 时间戳
     * @return string 日期时间格式年-月-日 时:分:秒
     */
    public static function timestampToDateTime($timestamp,$format=self::TIMEFORMAT_YMDHIS){
        return date($format, $timestamp);
    }
    
    /**
     * 将日期时间格式年-月-日 时:分:秒转成时间戳
     * @param string $str 日期时间格式年-月-日 时:分:秒
     * @return 时间戳
     */
    public static function dateToTimestamp($str=''){      
        if (empty($str)){
            $str=self::now();
        }
        @list($date, $time) = explode(' ', $str); 
        list($year, $month, $day) = explode('-', $date); 
        if(empty($time)){
            $timestamp = mktime(0, 0, 0, $month, $day, $year); 
        }else{
            list($hour, $minute, $second) = explode(':', $time); 
            $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
        }
        return $timestamp;
    }
    
    /**
     +----------------------------------------------------------<br/>
     * 是否为闰年<br/>
     +----------------------------------------------------------
     * @static
     * @access public
     * @param int $year  年数
     * @return string
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public static function isLeapYear($year='') {
        if(empty($year)) {
            $year = $this->year;
        }
        return ((($year % 4) == 0) && (($year % 100) != 0) || (($year % 400) == 0));
    }


    /**
     +----------------------------------------------------------<br/>
     *  判断日期 所属 干支 生肖 星座<br/>
     *  type 参数：XZ 星座 GZ 干支 SX 生肖<br/>
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $type  获取信息类型
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public static function magicInfo($year,$month,$day,$type="SX") {
        $result = '';
        $m      =   $month;
        $y      =   $year;
        $d      =   $day;
        switch ($type) {
            case 'XZ'://星座
                $XZDict = array('摩羯','宝瓶','双鱼','白羊','金牛','双子','巨蟹','狮子','处女','天秤','天蝎','射手');
                $Zone   = array(1222,122,222,321,421,522,622,722,822,922,1022,1122,1222);
                if((100*$m+$d)>=$Zone[0]||(100*$m+$d)<$Zone[1])
                    $i=0;
                else
                    for($i=1;$i<12;$i++) {
                        if((100*$m+$d)>=$Zone[$i]&&(100*$m+$d)<$Zone[$i+1])
                            break;
                    }
                $result = $XZDict[$i].'座';
                break;
            case 'GZ'://干支
                $GZDict = array(
                        array('甲','乙','丙','丁','戊','己','庚','辛','壬','癸'),
                        array('子','丑','寅','卯','辰','巳','午','未','申','酉','戌','亥')
                );
                $i= $y -1900+36 ;
                $result = $GZDict[0][$i%10].$GZDict[1][$i%12];
                break;
            case 'SX'://生肖
                $SXDict = array('鼠','牛','虎','兔','龙','蛇','马','羊','猴','鸡','狗','猪');
                $result = $SXDict[($y-4)%12];
                break;
        }
        return $result;
    }    
}
//echo UtilDateTime::magicInfo("1979", "3", "10","XZ")
?>
