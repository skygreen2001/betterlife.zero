<?php
/**
 +---------------------------------<br/>
 * 工具类：条形码<br/>
 +---------------------------------
 * @category betterlife
 * @package util.thirdparty
 * @author skygreen
 */
class UtilBarCode extends Util
{
	private static $possibleCodes = array('BCGcodabar', 'BCGcode11', 'BCGcode39', 'BCGcode39extended', 'BCGcode93', 'BCGcode128', 'BCGean8', 'BCGean13', 'BCGgs1128', 'BCGi25', 'BCGisbn', 'BCGmsi', 'BCGs25', 'BCGupca', 'BCGupce', 'BCGupcext2', 'BCGupcext5', 'BCGothercode', 'BCGpostnet', 'BCGintelligentmail');
	private static $barcodeSupports = array(
	'setChecksum' => array('BCGcode39', 'BCGcode39extended', 'BCGi25', 'BCGs25', 'BCGmsi'),
	'setStart' => array('BCGcode128', 'BCGgs1128'),
	'barcodeIdentifier' => array('BCGintelligentmail'), // Requires also serviceType, mailerIdentifier, serialNumber
	'setLabel' => array('BCGothercode')
);

	/**
	 * 采用Code128算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function Code128($origin)
	{
		$barcodeName="BCGcode128";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用Code128算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function Codabar($origin)
	{
		$barcodeName="BCGcodabar";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用Code 39算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function Code39($origin)
	{
		$barcodeName="BCGcode39";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用Code 39 Extended算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function Code39Extended($origin)
	{
		$barcodeName="BCGcode39extended";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用Code 93算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function Code93($origin)
	{
		$barcodeName="BCGcode93";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用EAN-8算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function EAN8($origin)
	{
		$barcodeName="BCGean8";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用EAN-13算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function EAN13($origin)
	{
		$barcodeName="BCGean13";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用GS1-128(EAN-128)算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function EAN128($origin)
	{
		$barcodeName="BCGgs1128";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用ISBN-10 / ISBN-13算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function ISBN10_13($origin)
	{
		$barcodeName="BCGisbn";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用Interleaved 2 of 5算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function I25($origin)
	{
		$barcodeName="BCGi25";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用Standard 2 of 5算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function S25($origin)
	{
		$barcodeName="BCGs25";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用MSI Plessey算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function msi($origin)
	{
		$barcodeName="BCGmsi";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用UPC-A算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function upc_a($origin)
	{
		$barcodeName="BCGupca";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用UPC-E算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function upc_e($origin)
	{
		$barcodeName="BCGupce";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用UPC Extension 2 Digits算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function upc_ext2($origin)
	{
		$barcodeName="BCGupcext2";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用UPC Extension 5 Digits算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function upc_ext5($origin)
	{
		$barcodeName="BCGupcext5";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用PostNet算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function PostNet($origin)
	{
		$barcodeName="BCGpostnet";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用Intelligent Mail算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function intelligentmail($origin)
	{
		$barcodeName="BCGintelligentmail";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用Other Barcode算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function other($origin)
	{
		$barcodeName="BCGothercode";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 采用Code 11算法生成条形码
	 * @param mixed $origin 源输入值
	 */
	public static function Code11($origin)
	{
		$barcodeName="BCGcode11";
		self::coding($barcodeName,$origin);
	}

	/**
	 * 进行条形码编码
	 * @param mixed $code 条形码对象
	 * @param mixed $origin 源输入值
	 */
	private static function coding($barcodeName,$origin,$option_come=array())
	{
		if (!include_once('class/'.$barcodeName.'.barcode.php')) {
			return;
		}
		$code=new $barcodeName();
		// Loading Font

		$font = new BCGFontFile(Gc::$nav_root_path."module".DS."barcode".DS.'class/font/Arial.ttf', 18);
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);
		$drawException = null;
		try {

			// Since image.php supports all barcode, we must check here
			// Which one support which option
			foreach (self::$barcodeSupports as $option => $barcodeNames) {
				if (isset($option_come)) {
					if (in_array($barcodeName, $barcodeNames)) {
						$value = $option_come[$option];
						switch ($value) {
							case 'true':
								$value = true;
								break;
							case 'false':
								$value = false;
								break;
							case 'NULL':
								$value = null;
								break;
							default:
								if (is_numeric($value)) { // We accept only integer...
									$value = intval($value);
								}
						}

						switch ($option) {
							case 'setChecksum':
							case 'setStart':
							case 'setLabel':
								$code->$option($value);
								break;
							case 'barcodeIdentifier':
								// Make sure we have all we need
								if (!isset($option_come['serviceType']) || !isset($option_come['mailerIdentifier']) || !isset($option_come['serialNumber'])) {
									break;
								}
								$code->setTrackingCode(intval($option_come['barcodeIdentifier']), intval($option_come['serviceType']), intval($option_come['mailerIdentifier']), intval($option_come['serialNumber']));
								break;
						}

					}
				}
			}

			$code->setScale(2); // Resolution
			$code->setThickness(30); // Thickness
			$code->setForegroundColor($color_black); // Color of bars
			$code->setBackgroundColor($color_white); // Color of spaces
			$code->setFont($font); // Font (or 0)
			$code->parse($origin); // Text
			self::drawing($code);
		} catch(Exception $exception) {
			$drawException = $exception;
		}
	}

	/**
	 * 绘制条形码图片
	 * @param mixed $code 条形码对象
	 */
	private static function drawing($code)
	{
		$color_white = new BCGColor(255, 255, 255);
		/* Here is the list of the arguments
		1 - Filename (empty : display on screen)
		2 - Background color */
		$drawing = new BCGDrawing('', $color_white);
		if($drawException) {
			$drawing->drawException($drawException);
		} else {
			$drawing->setBarcode($code);
			$drawing->draw();
		}

		// Header that says it is an image (remove it if you save the barcode to a file)
		header('Content-Type: image/png');

		// Draw (or save) the image into PNG format.
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
	}
}
?>
