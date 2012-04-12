<?php
/**
 +---------------------------------------<br/>
 * Mailer objects are responsible for actually sending emails.<br/>
 * The default Mailer class will use PHP's mail() function.<br/>
 * @see http://www.silverstripe.com<br/>
 *      class:Mailer|Email<br/>
 +---------------------------------------<br/>
 * @category betterlife
 * @package util.email
 * @author silverstripe
 */
class UtilEmail extends Util {
	/**
	 * @desc Validates the email address. Returns true of false
	 */
	public static function validEmailAddress($address) {
		return ereg('^([a-zA-Z0-9_+\.\-]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$', $address);
	}
	
	
	/**
	 * 验证输入的邮件地址是否合法
	 * @param string $email 需要验证的邮件地址
	 * @return bool
	 */
	public function is_email($user_email)
	{
		$chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,5}\$/i";
		if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false)
		{
			if (preg_match($chars, $user_email))return true; else return false;
		}
		else return false;
	}
	
	/**
	 * Checks for RFC822-valid email format.
	 *
	 * @param string $str
	 * @return boolean
	 *
	 * @see http://code.iamcal.com/php/rfc822/rfc822.phps
	 * @copyright Cal Henderson <cal@iamcal.com>
	 * 	This code is licensed under a Creative Commons Attribution-ShareAlike 2.5 License
	 * 	http://creativecommons.org/licenses/by-sa/2.5/
	 */
	public static function is_valid_address($email) {
		$qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';
		$dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';
		$atom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c'.
				'\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';
		$quoted_pair = '\\x5c[\\x00-\\x7f]';
		$domain_literal = "\\x5b($dtext|$quoted_pair)*\\x5d";
		$quoted_string = "\\x22($qtext|$quoted_pair)*\\x22";
		$domain_ref = $atom;
		$sub_domain = "($domain_ref|$domain_literal)";
		$word = "($atom|$quoted_string)";
		$domain = "$sub_domain(\\x2e$sub_domain)*";
		$local_part = "$word(\\x2e$word)*";
		$addr_spec = "$local_part\\x40$domain";

		return preg_match("!^$addr_spec$!", $email) ? 1 : 0;
	}


	/**
	 * Send a plain-text email.
	 *
	 * @param string $to
	 * @param string $from
	 * @param string §subject
	 * @param string $plainContent
	 * @param bool $attachedFiles
	 * @param array $customheaders
	 * @return bool
	 */
	public static function sendPlain($to, $from, $subject, $plainContent, $attachedFiles = false, $customheaders = false) {
		return self::plaintextEmail($to, $from, $subject, $plainContent, $attachedFiles, $customheaders);
	}

	/**
	 * Send a multi-part HTML email.
	 *
	 * @return bool
	 */
	public static function sendHTML($to, $from, $subject, $htmlContent, $attachedFiles = false, $customheaders = false, $plainContent = false, $inlineImages = false) {
		return self::htmlEmail($to, $from, $subject, $htmlContent, $attachedFiles, $customheaders, $plainContent, $inlineImages);
	}





	/*
 * Sends an email as a both HTML and plaintext
 *   $attachedFiles should be an array of file names
 *    - if you pass the entire $_FILES entry, the user-uploaded filename will be preserved
 *   use $plainContent to override default plain-content generation
 *
 * @return bool
	*/
	private static function htmlEmail($to, $from, $subject, $htmlContent, $attachedFiles = false, $customheaders = false, $plainContent = false, $inlineImages = false) {
		if ($customheaders && is_array($customheaders) == false) {
			echo "htmlEmail($to, $from, $subject, ...) could not send mail: improper \$customheaders passed:<BR>";
			dieprintr($headers);
		}


		$subjectIsUnicode = (strpos($subject,"&#") !== false);
		$bodyIsUnicode = (strpos($htmlContent,"&#") !== false);
		$plainEncoding = "";

		// We generate plaintext content by default, but you can pass custom stuff
		$plainEncoding = '';
		if(!$plainContent) {
			$plainContent = Convert::xml2raw($htmlContent);
			if(isset($bodyIsUnicode) && $bodyIsUnicode) $plainEncoding = "base64";
		}


		// If the subject line contains extended characters, we must encode the
		$subject = Convert::xml2raw($subject);
		if(isset($subjectIsUnicode) && $subjectIsUnicode)
			$subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";


		// Make the plain text part
		$headers["Content-Type"] = "text/plain; charset=\"utf-8\"";
		$headers["Content-Transfer-Encoding"] = $plainEncoding ? $plainEncoding : "quoted-printable";

		$plainPart = self::processHeaders($headers, ($plainEncoding == "base64") ? chunk_split(base64_encode($plainContent),60) : wordwrap($plainContent,120));

		// Make the HTML part
		$headers["Content-Type"] = "text/html; charset=\"utf-8\"";


		// Add basic wrapper tags if the body tag hasn't been given
		if(stripos($htmlContent, '<body') === false) {
			$htmlContent =
					"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n" .
					"<HTML><HEAD>\n" .
					"<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">\n" .
					"<STYLE type=3Dtext/css></STYLE>\n\n".
					"</HEAD>\n" .
					"<BODY bgColor=#ffffff>\n" .
					$htmlContent .
					"\n</BODY>\n" .
					"</HTML>";
		}

		if($inlineImages) {
			$htmlPart = self::wrapImagesInline($htmlContent);
		} else {
			$headers["Content-Transfer-Encoding"] = "quoted-printable";
			$htmlPart = self::processHeaders($headers, wordwrap(self::QuotedPrintable_encode($htmlContent),120));
		}

		list($messageBody, $messageHeaders) = self::encodeMultipart(array($plainPart,$htmlPart), "multipart/alternative");

		// Messages with attachments are handled differently
		if($attachedFiles && is_array($attachedFiles)) {

			// The first part is the message itself
			$fullMessage = self::processHeaders($messageHeaders, $messageBody);
			$messageParts = array($fullMessage);

			// Include any specified attachments as additional parts
			foreach($attachedFiles as $file) {
				if(isset($file['tmp_name']) && isset($file['name'])) {
					$messageParts[] = self::encodeFileForEmail($file['tmp_name'], $file['name']);
				} else {
					$messageParts[] = self::encodeFileForEmail($file);
				}
			}

			// We further wrap all of this into another multipart block
			list($fullBody, $headers) = self::encodeMultipart($messageParts, "multipart/mixed");

			// Messages without attachments do not require such treatment
		} else {
			$headers = $messageHeaders;
			$fullBody = $messageBody;
		}

		// Email headers
		$headers["From"] = self::validEmailAddr($from);

		// Messages with the X-SilverStripeMessageID header can be tracked
		if(isset($customheaders["X-SilverStripeMessageID"]) && defined('BOUNCE_EMAIL')) {
			$bounceAddress = BOUNCE_EMAIL;
		} else {
			$bounceAddress = $from;
		}

		// Strip the human name from the bounce address
		if(ereg('^([^<>]*)<([^<>]+)> *$', $bounceAddress, $parts)) $bounceAddress = $parts[2];

		// $headers["Sender"] 		= $from;
		$headers["X-Mailer"]	= X_MAILER;
		if (!isset($customheaders["X-Priority"])) $headers["X-Priority"]	= 3;

		$headers = array_merge((array)$headers, (array)$customheaders);

		// the carbon copy header has to be 'Cc', not 'CC' or 'cc' -- ensure this.
		if (isset($headers['CC'])) {
			$headers['Cc'] = $headers['CC'];
			unset($headers['CC']);
		}
		if (isset($headers['cc'])) {
			$headers['Cc'] = $headers['cc'];
			unset($headers['cc']);
		}

		// the carbon copy header has to be 'Bcc', not 'BCC' or 'bcc' -- ensure this.
		if (isset($headers['BCC'])) {
			$headers['Bcc']=$headers['BCC'];
			unset($headers['BCC']);
		}
		if (isset($headers['bcc'])) {
			$headers['Bcc']=$headers['bcc'];
			unset($headers['bcc']);
		}


		// Send the email
		$headers = self::processHeaders($headers);
		$to = self::validEmailAddr($to);

		// Try it without the -f option if it fails
		if(!($result = @mail($to, $subject, $fullBody, $headers, "-f$bounceAddress"))) {
			$result = mail($to, $subject, $fullBody, $headers);
		}

		return $result;
	}

	/*
 * Send a plain text e-mail
	*/
	private static function plaintextEmail($to, $from, $subject, $plainContent, $attachedFiles, $customheaders = false) {
		$subjectIsUnicode = false;
		$plainEncoding = false; // Not ensurely where this is supposed to be set, but defined it false for now to remove php notices

		if ($customheaders && is_array($customheaders) == false) {
			echo "htmlEmail($to, $from, $subject, ...) could not send mail: improper \$customheaders passed:<BR>";
			dieprintr($headers);
		}

		if(strpos($subject,"&#") !== false) $subjectIsUnicode = true;

		// If the subject line contains extended characters, we must encode it
		$subject = Convert::xml2raw($subject);
		if($subjectIsUnicode)
			$subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";


		// Make the plain text part
		$headers["Content-Type"] = "text/plain; charset=\"utf-8\"";
		$headers["Content-Transfer-Encoding"] = $plainEncoding ? $plainEncoding : "quoted-printable";

		$plainContent = ($plainEncoding == "base64") ? chunk_split(base64_encode($plainContent),60) : self::QuotedPrintable_encode($plainContent);

		// Messages with attachments are handled differently
		if(is_array($attachedFiles)) {
			// The first part is the message itself
			$fullMessage = self::processHeaders($headers, $plainContent);
			$messageParts = array($fullMessage);

			// Include any specified attachments as additional parts
			foreach($attachedFiles as $file) {
				if(isset($file['tmp_name']) && isset($file['name'])) {
					$messageParts[] = self::encodeFileForEmail($file['tmp_name'], $file['name']);
				} else {
					$messageParts[] =self::encodeFileForEmail($file);
				}
			}


			// We further wrap all of this into another multipart block
			list($fullBody, $headers) = self::encodeMultipart($messageParts, "multipart/mixed");

			// Messages without attachments do not require such treatment
		} else {
			$fullBody = $plainContent;
		}

		// Email headers
		$headers["From"] 		= self::validEmailAddr($from);

		// Messages with the X-SilverStripeMessageID header can be tracked
		if(isset($customheaders["X-SilverStripeMessageID"]) && defined('BOUNCE_EMAIL')) {
			$bounceAddress = BOUNCE_EMAIL;
			// Get the human name from the from address, if there is one
			if(ereg('^([^<>]+)<([^<>])> *$', $from, $parts))
				$bounceAddress = "$parts[1]<$bounceAddress>";
		} else {
			$bounceAddress = $from;
		}

		// $headers["Sender"] 		= $from;
		$headers["X-Mailer"]	= X_MAILER;
		if(!isset($customheaders["X-Priority"])) {
			$headers["X-Priority"]	= 3;
		}

		$headers = array_merge((array)$headers, (array)$customheaders);

		// the carbon copy header has to be 'Cc', not 'CC' or 'cc' -- ensure this.
		if (isset($headers['CC'])) {
			$headers['Cc'] = $headers['CC'];
			unset($headers['CC']);
		}
		if (isset($headers['cc'])) {
			$headers['Cc'] = $headers['cc'];
			unset($headers['cc']);
		}

		// Send the email
		$headers = self::processHeaders($headers);
		$to = self::validEmailAddr($to);

		// Try it without the -f option if it fails
		if(!$result = @mail($to, $subject, $fullBody, $headers, "-f$bounceAddress"))
			$result = mail($to, $subject, $fullBody, $headers);

		if($result)
			return array($to,$subject,$fullBody,$headers);

		return false;
	}


	private static function encodeMultipart($parts, $contentType, $headers = false) {
		$separator = "----=_NextPart_" . ereg_replace('[^0-9]','',rand() * 10000000000);


		$headers["MIME-Version"] = "1.0";
		$headers["Content-Type"] = "$contentType; boundary=\"$separator\"";
		$headers["Content-Transfer-Encoding"] = "7bit";

		if($contentType == "multipart/alternative") {
			//$baseMessage = "This is an encoded HTML message.  There are two parts: a plain text and an HTML message, open whatever suits you better.";
			$baseMessage = "\nThis is a multi-part message in MIME format.";
		} else {
			//$baseMessage = "This is a message containing attachments.  The e-mail body is contained in the first attachment";
			$baseMessage = "\nThis is a multi-part message in MIME format.";
		}


		$separator = "\n--$separator\n";
		$body = "$baseMessage\n" .
				$separator . implode("\n".$separator, $parts) . "\n" . trim($separator) . "--";

		return array($body, $headers);
	}

	/**
	 * Return a multipart/related e-mail chunk for the given HTML message and its linked images
	 * Decodes absolute URLs, accessing the appropriate local images
	 */
	private static  function wrapImagesInline($htmlContent) {
		global $_INLINED_IMAGES;
		$_INLINED_IMAGES = null;

		// Make the HTML part
		$headers["Content-Type"] = "text/html; charset=\"utf-8\"";
		$headers["Content-Transfer-Encoding"] = "quoted-printable";
		$multiparts[] = self::processHeaders($headers, self::QuotedPrintable_encode($replacedContent));

		// Make all the image parts
		global $_INLINED_IMAGES;
		foreach($_INLINED_IMAGES as $url => $cid) {
			$multiparts[] = self::encodeFileForEmail($url, false, "inline", "Content-ID: <$cid>\n");
		}

		// Merge together in a multipart
		list($body, $headers) = self::encodeMultipart($multiparts, "multipart/related");
		return self::processHeaders($headers, $body);
	}

	/*
 * Combine headers w/ the body into a single string.
	*/
	private static function processHeaders($headers, $body = false) {
		$res = '';
		if(is_array($headers)) while(list($k, $v) = each($headers))
				$res .= "$k: $v\n";
		if($body) $res .= "\n$body";
		return $res;
	}

	/**
	 * Encode the contents of a file for emailing, including headers
	 *
	 * $file can be an array, in which case it expects these members:
	 *   'filename'        - the filename of the file
	 *   'contents'        - the raw binary contents of the file as a string
	 *  and can optionally include these members:
	 *   'mimetype'        - the mimetype of the file (calculated from filename if missing)
	 *   'contentLocation' - the 'Content-Location' header value for the file
	 *
	 * $file can also be a string, in which case it is assumed to be the filename
	 *
	 * h5. contentLocation
	 *
	 * Content Location is one of the two methods allowed for embedding images into an html email. It's also the simplest, and best supported
	 *
	 * Assume we have an email with this in the body:
	 *
	 *   <img src="http://example.com/image.gif" />
	 *
	 * To display the image, an email viewer would have to download the image from the web every time it is displayed. Due to privacy issues, most
	 * viewers will not display any images unless the user clicks 'Show images in this email'. Not optimal.
	 *
	 * However, we can also include a copy of this image as an attached file in the email. By giving it a contentLocation of "http://example.com/image.gif"
	 * most email viewers will use this attached copy instead of downloading it. Better, most viewers will show it without a 'Show images in this email'
	 * conformation.
	 *
	 * Here is an example of passing this information through Email.php:
	 *
	 *   $email = new Email();
	 *   $email->attachments[] = array(
	 *     'filename' => BASE_PATH . "/themes/mytheme/images/header.gif",
	 *     'contents' => file_get_contents(BASE_PATH . "/themes/mytheme/images/header.gif"),
	 *     'mimetype' => 'image/gif',
	 *     'contentLocation' => Director::absoluteBaseURL() . "/themes/mytheme/images/header.gif"
	 *   );
	 *
	 */
	private static function encodeFileForEmail($file, $destFileName = false, $disposition = NULL, $extraHeaders = "") {
		if(!$file) {
			user_error("encodeFileForEmail: not passed a filename and/or data", E_USER_WARNING);
			return;
		}

		if (is_string($file)) {
			$file = array('filename' => $file);
			$fh = fopen($file['filename'], "rb");
			if ($fh) {
				while(!feof($fh)) $file['contents'] .= fread($fh, 10000);
				fclose($fh);
			}
		}

		// Build headers, including content type
		if(!$destFileName) $base = basename($file['filename']);
		else $base = $destFileName;

		$mimeType = $file['mimetype'] ? $file['mimetype'] : self::getMimeType($file['filename']);
		if(!$mimeType) $mimeType = "application/unknown";

		if (empty($disposition)) $disposition = isset($file['contentLocation']) ? 'inline' : 'attachment';

		// Encode for emailing
		if (substr($file['mimetype'], 0, 4) != 'text') {
			$encoding = "base64";
			$file['contents'] = chunk_split(base64_encode($file['contents']));
		} else {
			// This mime type is needed, otherwise some clients will show it as an inline attachment
			$mimeType = 'application/octet-stream';
			$encoding = "quoted-printable";
			$file['contents'] = self::QuotedPrintable_encode($file['contents']);
		}

		$headers = "Content-type: $mimeType;\n\tname=\"$base\"\n".
				"Content-Transfer-Encoding: $encoding\n".
				"Content-Disposition: $disposition;\n\tfilename=\"$base\"\n" ;

		if ( isset($file['contentLocation']) ) $headers .= 'Content-Location: ' . $file['contentLocation'] . "\n" ;

		$headers .= $extraHeaders . "\n";

		// Return completed packet
		return $headers . $file['contents'];
	}

	private static function QuotedPrintable_encode($quotprint) {
		$quotprint = (string) str_replace('\r\n',chr(13).chr(10),$quotprint);
		$quotprint = (string) str_replace('\n',  chr(13).chr(10),$quotprint);
		$quotprint = (string) preg_replace("~([\x01-\x1F\x3D\x7F-\xFF])~e", "sprintf('=%02X', ord('\\1'))", $quotprint);
		//$quotprint = (string) str_replace('\=0D=0A',"=0D=0A",$quotprint);
		$quotprint = (string) str_replace('=0D=0A',"\n",$quotprint);
		$quotprint = (string) str_replace('=0A=0D',"\n",$quotprint);
		$quotprint = (string) str_replace('=0D',"\n",$quotprint);
		$quotprint = (string) str_replace('=0A',"\n",$quotprint);
		return (string) $quotprint;
	}

	private static function validEmailAddr($emailAddress) {
		$emailAddress = trim($emailAddress);
		$angBrack = strpos($emailAddress, '<');

		if($angBrack === 0) {
			$emailAddress = substr($emailAddress, 1, strpos($emailAddress,'>')-1);

		} else if($angBrack) {
			$emailAddress = str_replace('@', '', substr($emailAddress, 0, $angBrack))
					.substr($emailAddress, $angBrack);
		}

		return $emailAddress;
	}

	/*
 * Get mime type based on extension
	*/
	private static function getMimeType($filename) {
		global $global_mimetypes;
		if(!$global_mimetypes) self::loadMimeTypes();
		$ext = strtolower(substr($filename,strrpos($filename,'.')+1));
		return $global_mimetypes[$ext];
	}

	/*
 * Load the mime-type data from the system file
	*/
	private static function loadMimeTypes() {
		$mimetypePathCustom = '/etc/mime.types';
		$mimetypePathGeneric = Director::baseFolder() . '/sapphire/email/mime.types';
		$mimeTypes = file_exists($mimetypePathGeneric) ?  file($mimetypePathGeneric) : file($mimetypePathCustom);
		foreach($mimeTypes as $typeSpec) {
			if(($typeSpec = trim($typeSpec)) && substr($typeSpec,0,1) != "#") {
				$parts = preg_split("/[ \t\r\n]+/", $typeSpec);
				if(sizeof($parts) > 1) {
					$mimeType = array_shift($parts);
					foreach($parts as $ext) {
						$ext = strtolower($ext);
						$mimeData[$ext] = $mimeType;
					}
				}
			}
		}

		global $global_mimetypes;
		$global_mimetypes = $mimeData;
		return $mimeData;
	}
}
?>
