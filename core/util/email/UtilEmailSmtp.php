<?php
/**      
 +---------------------------------------<br/>
 * 发邮件的类<br/>
 * 调用UtilEmailSmtp()发邮件<br/>
 *   $smtpemailto = "724190363@qq.com";<br/>
 *   $smtpuser = "sssameeting@126.com";<br/>
 *   $mailsubject = "测试邮件";<br/>
 *   $mailbody = UtilString::utf82gbk("怎么办我想想");<br/>
 *   $mailtype = "HTML";<br/>
 *   $Email = new UtilEmailSmtp();<br/>
 *   if($Email->sendmail($smtpemailto, $smtpuser, $mailsubject, $mailbody,$mailtype)){<br/>
 *       $mailinfo = "邮件已发送";<br/>
 *   }else{<br/>
 *       $mailinfo = "发送不成功";<br/>
 *   }<br/>
 +---------------------------------------<br/>
 * @category betterlife
 * @package util.email
 * @author qiulin
 */
class UtilEmailSmtp extends Util {
   //private static  $auth=false;

    /* Private static Variables */
    private static $smtp_port = 25;
    private static $time_out = 30;
    private static $host_name = "localhost";
    private static $log_file ="";
    private static $relay_host = "smtp.126.com";
    private static $debug = false;
    private static $auth = true;
    private static $user = "sssameeting@126.com";
    private static $pass = "123456";

    /* Public Variables */
    public $sock;

    /* Constractor */
    function __construct() {
        $this->sock = false;
    }

    /* Main Function */
    public function sendmail($to, $from, $subject = "", $body = "", $mailtype = "html", $cc = "", $bcc = "", $additional_headers = "") {
        $mail_from = $this->get_address($this->strip_comment($from));
        $body = ereg_replace("(^|(\r\n))(\\.)", "\\1.\\3", $body);
        $header= "MIME-Version:1.0\r\n";
        if($mailtype=="HTML") {
            $header .= "Content-Type:text/html charset=\"gbk\"\r\n";// charset=\"utf-8\"
        }
        $header .= "To: ".$to."\r\n";
        if ($cc != "") {
            $header .= "Cc: ".$cc."\r\n";
        }
        $header .= "Disposition-Notification-To：".$from ."\r\n";
        $header .= "From: $from<".$from.">\r\n";
        $header .= "Subject: ".$subject."\r\n";
        $header .= $additional_headers;
        $header .= "Date: ".date("r")."\r\n";
        $header .= "X-Mailer:By Redhat (PHP/".phpversion().")\r\n";
        list($msec, $sec) = explode(" ", microtime());
        $header .= "Message-ID: <".date("YmdHis", $sec).".".($msec*1000000).".".$mail_from.">\r\n";
        $TO = explode(",", $this->strip_comment($to));

        if ($cc != "") {
            $TO = array_merge($TO, explode(",", $this->strip_comment($cc)));
        }

        if ($bcc != "") {
            $TO = array_merge($TO, explode(",", $this->strip_comment($bcc)));
        }

        $sent = TRUE;
        foreach ($TO as $rcpt_to) {
            $rcpt_to = $this->get_address($rcpt_to);
            if (!$this->smtp_sockopen($rcpt_to)) {
                $this->log_write("Error: Cannot send email to ".$rcpt_to."\n");
                $sent = FALSE;
                continue;
            }
            if ($this->smtp_send(self::$host_name, $mail_from, $rcpt_to, $header, $body)) {
                $this->log_write("E-mail has been sent to <".$rcpt_to.">\n");
            } else {
                $this->log_write("Error: Cannot send email to <".$rcpt_to.">\n");
                $sent = FALSE;
            }
            fclose($this->sock);
            $this->log_write("Disconnected from remote host\n");
        }
        echo "<br>";
        echo $header;
        return $sent;
    }

    /* Private Functions */

    public function smtp_send($helo, $from, $to, $header, $body = "") {
        if (!$this->smtp_putcmd("HELO", $helo)) {
            return $this->smtp_error("sending HELO command");
        }

        if(self::$auth) {
            if (!$this->smtp_putcmd("AUTH LOGIN", base64_encode(self::$user))) {
                return $this->smtp_error("sending HELO command");
            }

            if (!$this->smtp_putcmd("", base64_encode(self::$pass))) {
                return $this->smtp_error("sending HELO command");
            }
        }

        if (!$this->smtp_putcmd("MAIL", "FROM:<".$from.">")) {
            return $this->smtp_error("sending MAIL FROM command");
        }

        if (!$this->smtp_putcmd("RCPT", "TO:<".$to.">")) {
            return $this->smtp_error("sending RCPT TO command");
        }

        if (!$this->smtp_putcmd("DATA")) {
            return $this->smtp_error("sending DATA command");
        }

        if (!$this->smtp_message($header, $body)) {
            return $this->smtp_error("sending message");
        }

        if (!$this->smtp_eom()) {
            return $this->smtp_error("sending <CR><LF>.<CR><LF> [EOM]");
        }

        if (!$this->smtp_putcmd("QUIT")) {
            return $this->smtp_error("sending QUIT command");
        }

        return TRUE;
    }

    public function smtp_sockopen($address) {
        if (empty(self::$relay_host)) {
            return $this->smtp_sockopen_mx($address);
        } else {
            return $this->smtp_sockopen_relay();
        }
    }

    public function smtp_sockopen_relay() {
        $this->log_write("Trying to ".self::$relay_host.":".self::$smtp_port."\n");
        $this->sock = @fsockopen(self::$relay_host, self::$smtp_port, $errno, $errstr, self::$time_out);
        if (!($this->sock && $this->smtp_ok())) {
            $this->log_write("Error: Cannot connenct to relay host ".self::$relay_host."\n");
            $this->log_write("Error: ".$errstr." (".$errno.")\n");
            return FALSE;
        }
        $this->log_write("Connected to relay host ".self::$relay_host."\n");
        return TRUE;
        ;
    }

    public function smtp_sockopen_mx($address) {
        $domain = ereg_replace("^.+@([^@]+)$", "\\1", $address);
        if (!@getmxrr($domain, $MXHOSTS)) {
            $this->log_write("Error: Cannot resolve MX \"".$domain."\"\n");
            return FALSE;
        }
        foreach ($MXHOSTS as $host) {
            $this->log_write("Trying to ".$host.":".self::$smtp_port."\n");
            $this->sock = @fsockopen($host, self::$smtp_port, $errno, $errstr, self::$time_out);
            if (!($this->sock && $this->smtp_ok())) {
                $this->log_write("Warning: Cannot connect to mx host ".$host."\n");
                $this->log_write("Error: ".$errstr." (".$errno.")\n");
                continue;
            }
            $this->log_write("Connected to mx host ".$host."\n");
            return TRUE;
        }
        $this->log_write("Error: Cannot connect to any mx hosts (".implode(", ", $MXHOSTS).")\n");
        return FALSE;
    }

    public function smtp_message($header, $body) {
        fputs($this->sock, $header."\r\n".$body);
        $this->smtp_debug("> ".str_replace("\r\n", "\n"."> ", $header."\n> ".$body."\n> "));

        return TRUE;
    }

    public function smtp_eom() {
        fputs($this->sock, "\r\n.\r\n");
        $this->smtp_debug(". [EOM]\n");

        return $this->smtp_ok();
    }

    public function smtp_ok() {
        $response = str_replace("\r\n", "", fgets($this->sock, 512));
        $this->smtp_debug($response."\n");

        if (!ereg("^[23]", $response)) {
            fputs($this->sock, "QUIT\r\n");
            fgets($this->sock, 512);
            $this->log_write("Error: Remote host returned \"".$response."\"\n");
            return FALSE;
        }
        return TRUE;
    }

    public function smtp_putcmd($cmd, $arg = "") {
        if (!empty ($arg)) {
            if(empty($cmd)) $cmd = $arg;
            else $cmd = $cmd." ".$arg;
        }

        fputs($this->sock, $cmd."\r\n");
        $this->smtp_debug("> ".$cmd."\n");

        return $this->smtp_ok();
    }

    public function smtp_error($string) {
        $this->log_write("Error: Error occurred while ".$string.".\n");
        return FALSE;
    }

    public function log_write($message) {
        $this->smtp_debug($message);

        if (empty (self::$log_file)) {
            return TRUE;
        }

        $message = date("M d H:i:s ").get_current_user()."[".getmypid()."]: ".$message;
        if (!@file_exists(self::$log_file) || !($fp = @fopen(self::$log_file, "a"))) {
            $this->smtp_debug("Warning: Cannot open log file \"".self::$log_file."\"\n");
            return FALSE;
        }
        flock($fp, LOCK_EX);
        fputs($fp, $message);
        fclose($fp);

        return TRUE;
    }

    public function strip_comment($address) {
        $comment = "\\([^()]*\\)";
        while (ereg($comment, $address)) {
            $address = ereg_replace($comment, "", $address);
        }

        return $address;
    }

    public function get_address($address) {
        $address = ereg_replace("([ \t\r\n])+", "", $address);
        $address = ereg_replace("^.*<(.+)>.*$", "\\1", $address);

        return $address;
    }

    public function smtp_debug($message) {
        if ($this->debug) {
            echo $message."<br>";
        }
    }

    public function get_attach_type($image_tag) { //

        $filedata = array();

        $img_file_con=fopen($image_tag,"r");
        unset($image_data);
        while ($tem_buffer=AddSlashes(fread($img_file_con,filesize($image_tag))))
            $image_data.=$tem_buffer;
        fclose($img_file_con);

        $filedata['context'] = $image_data;
        $filedata['filename']= basename($image_tag);
        $extension=substr($image_tag,strrpos($image_tag,"."),strlen($image_tag)-strrpos($image_tag,"."));
        switch($extension) {
            case ".gif":
                $filedata['type'] = "image/gif";
                break;
            case ".gz":
                $filedata['type'] = "application/x-gzip";
                break;
            case ".htm":
                $filedata['type'] = "text/html";
                break;
            case ".html":
                $filedata['type'] = "text/html";
                break;
            case ".jpg":
                $filedata['type'] = "image/jpeg";
                break;
            case ".tar":
                $filedata['type'] = "application/x-tar";
                break;
            case ".txt":
                $filedata['type'] = "text/plain";
                break;
            case ".zip":
                $filedata['type'] = "application/zip";
                break;
            default:
                $filedata['type'] = "application/octet-stream";
                break;
        }
        return $filedata;
    }

}
?>
