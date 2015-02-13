
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

	/**
	 * 判断是否属于手机
	 */
	public static function is_mobile()
	{
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
		$is_mobile = false;
		foreach ($mobile_agents as $device) {
			if (stristr($user_agent, $device)) {
				$is_mobile = true;
				break;
			}
		}
		return $is_mobile;
	}
}
?>
