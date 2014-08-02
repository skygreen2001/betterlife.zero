<?php
require_once("phpmailer/class.phpmailer.php");
/**
 * 邮件发送
 * @category betterlie
 * @package util.email
 * @subpackage phpmailer
 */
class UtilEmailer
{
	/**
	 * 邮件服务器域名
	 */
	private static $host="smtp.qiye.163.com";
	/**
	 * 邮件服务器端口
	 */
	private static $port=25;
	/**
	 * 用户名
	 */
	private static $username="zhouyuepu@xun-ao.com";
	/**
	 * 密码
	 */
	private static $password="zaq12wsx";
	/**
	 * 发送邮件
	 * @param string $fromaddress 发件人地址
	 * @param string $fromname 发件人姓名
	 * @param string $toaddress 收件人地址
	 * @param string $toname 收件人姓名
	 * @param string $subject 邮件标题
	 * @param string $content 邮件内容
	 */
	public static function sendEmail($fromaddress,$fromname,$toaddress,$toname,$subject,$content)
	{
		$mail = new PHPMailer(); //建立邮件发送类
		$mail->Host = self::$host; //您的企业邮局域名
		$mail->Port= self::$port; //端口
		$mail->Username = self::$username; //邮局用户名(请填写完整的email地址)
		$mail->Password = self::$password; //邮局密码
		$mail->From = $fromaddress; //邮件发送者email地址
		$mail->FromName = $fromname;
		$mail->AddAddress($toaddress, $toname); //接收邮件的email信箱("收件人email地址","收件人姓名")
		$mail->Subject = $subject;//"=?utf-8?B?" . base64_encode($subject) . "?="; //邮件标题
		$mail->Body = $content; //邮件内容
		$mail->IsHTML(true); //是否使用HTML格式
		$mail->CharSet = "UTF-8"; //编码格式
		$mail->IsSMTP(); //使用SMTP方式发送
		$mail->SMTPAuth = true; //启用SMTP验证功能
		//$mail->AddAttachment($attach); //添加附件
		//$mail->AddReplyTo("", ""); //回复地址、名称
		//$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息
		return array(
			"success"=>$mail->Send(),
			"info"=>$mail->ErrorInfo
		);
	}
}
?>