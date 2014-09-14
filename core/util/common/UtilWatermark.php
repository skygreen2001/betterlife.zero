<?php
/**
 +---------------------------------<br/>
 * 功能:处理图片水印的方法。<br/>
 +---------------------------------
 * 参考:PHP function to image-watermark an image
 *      http://salman-w.blogspot.com/2008/11/watermark-your-images-with-another.html
 * 水印图片应当是以下推荐的格式:
 * PNG-8 (recommended) [Colors: 256 or less,Transparency: On/Off]
 *      因为imagecopymerge不能很好处理PNG-24的图片
 * GIF  [Colors: 256 or less,Transparency: On/Off]
 * JPEG [Colors: True color,Transparency: n/a]
 * 如果是采用Photoshop保存水印的图片，推荐采用以下设置:
 * -- 菜单选择:"Save for Web"
 * -- File Format: PNG-8, non-interlaced
 * -- Color Reduction: Selective, 256 colors
 * -- Dithering: Diffusion, 88%
 * -- Transparency: On, Matte: None
 * -- Transparency Dither: Diffusion Transparency Dither, 100%
 *
 * @category betterlife
 * @package util.common
 * @author skygreen
 */
class UtilWatermark
{
	/**
	 * 水印透明度的设置
	 */
	const WATERMARK_OVERLAY_OPACITY=20;
	/**
	 * 水印输出的质量
	 */
	const WATERMARK_OUTPUT_QUALITY=100;
	/**
	 * 上传好的原图片
	 * @var mixed
	 */
	private static $uploaded_image_destination;
	/**
	 * 上传处理好水印的图片存储的文件目录
	 * @var mixed
	 */
	private static $processed_image_destination;
	/**
	 * 水印图片的文件名
	 * @var mixed
	 */
	private static $watermark_overlay_image='watermark.png';
	/**
	 * 是否总是以jpg作为输出图片格式
	 * @var mixed
	 */
	private static $is_always_output_jpg=false;
	/**
	 * 是否删除源图片[未添加水印的图片]
	 * @var bool
	 */
	public static $is_delete_source_image=false;

	/**
	 * 初始化
	 */
	private static function init()
	{
		self::$uploaded_image_destination=Gc::$upload_path.'watermark'.DIRECTORY_SEPARATOR.'originals'.DIRECTORY_SEPARATOR;
		self::$processed_image_destination=Gc::$upload_path.'watermark'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR;
	}

	/**
	 * 上传图片函数
	 * @param mixed $files 上传的文件对象,同html form提交服务器对象$_FILES
	 * @param sting $uploadFieldName 上传文件的input组件的名称
	 * @param string $watermark_image_filename 水印图片的路径
	 * @param int $direction 水印所在的方位，默认为右下角
	 * 9:左上角[NorthWest] 8:正上角[North] 7:右上角[NorthEast]
	 * 6:中左角[West]      5:中部[Center]  4:中右角[East]
	 * 3:左下角[SouthWest] 2:正下角[South] 1:右下角[SouthEast]
	 * @return mixed bool 生成失败;array[file_path:生成水印图片的物理路径,url:生成水印图片的网络路径,origin_file_path:原图片物理路径,origin_url:原图片网络路径]
	 */
	public static function upload_watermark_source_files($files,$uploadFieldName="upload_file",$watermark_image_filename="watermark.png",$direction=1)
	{
		if (empty(self::$uploaded_image_destination))self::init();
		if (!file_exists($watermark_image_filename)){
			LogMe::log("水印图片不存在:".$watermark_image_filename);
			exit;
		}
		$temp_file_path = $files[$uploadFieldName]['tmp_name'];
		$temp_file_name = $files[$uploadFieldName]['name'];
		list(, , $temp_type) = getimagesize($temp_file_path);
		if ($temp_type === NULL) {
			return false;
		}
		switch ($temp_type) {
			case IMAGETYPE_GIF:
			case IMAGETYPE_JPEG:
			case IMAGETYPE_PNG:
				break;
			default:
				return false;
		}

		$uploaded_file_path = self::$uploaded_image_destination . $temp_file_name;
		if (self::$is_always_output_jpg){
			$processed_file_path = self::$processed_image_destination . preg_replace('/\\.[^\\.]+$/', '.jpg', $temp_file_name);
		}else{
			$processed_file_path = self::$processed_image_destination . $temp_file_name;

		}
		UtilFileSystem::createDir(dirname($uploaded_file_path));
		UtilFileSystem::createDir(dirname($processed_file_path));
		move_uploaded_file($temp_file_path, $uploaded_file_path);
		return $uploaded_file_path;

//		$result = self::create_watermark($uploaded_file_path, $processed_file_path,$watermark_image_filename, $direction);
//		return $result;
	}

	/**
	 * 给图片添加文字水印 可控制位置，旋转，多行文字
	 * @param string $source_file_path  图片地址
	 * @param string $output_file_path  新图片地址 默认使用后缀命名图片
	 * @param array $watermark_content 水印文字（多行以'|'分割）
	 * @param int $direction 水印位置:水印所在的方位，默认为右下角
	 * 9:左上角[NorthWest] 8:正上角[North] 7:右上角[NorthEast]
	 * 6:中左角[West]      5:中部[Center]  4:中右角[East]
	 * 3:左下角[SouthWest] 2:正下角[South] 1:右下角[SouthEast]
	 * @param string $font_color //字体颜色;如:255,255,255
	 * @return mixed bool 生成失败;array[file_path:生成水印图片的物理路径,url:生成水印图片的网络路径,origin_file_path:原图片物理路径,origin_url:原图片网络路径]
	 */
	public static function createWordsWatermark($source_file_path, $output_file_path, $watermark_content, $direction = 1,$font_color="255,255,255")
	{
		$font_type = Gc::$upload_path."font".DIRECTORY_SEPARATOR."msyh.ttc"; //字体
		if (!file_exists($font_type))die("请在指定路径下放置指定字体文件，默认是微软雅黑字体:".$font_type);

		$font_size = 22; //字体大小
		$angle=0;//旋转角度  允许值:0-90 270-360不含

		$imageCreateFunctionArr = array('image/jpeg' => 'imagecreatefromjpeg', 'image/png' => 'imagecreatefrompng', 'image/gif' => 'imagecreatefromgif');
		$imageOutputFunctionArr = array('image/jpeg' => 'imagejpeg', 'image/png' => 'imagepng', 'image/gif' => 'imagegif');

		$imgsize = getimagesize($source_file_path);
		if (empty($imgsize)) return false; //not image
		list($imgWidth, $imgHeight, $source_type,$size_desc) = $imgsize;
		$mime = $imgsize['mime'];//获取图片的mime类型

		if (!isset($imageCreateFunctionArr[$mime])) return false; //do not have create img function
		if (!isset($imageOutputFunctionArr[$mime])) return false; //do not have output img function

		$imageCreateFun = $imageCreateFunctionArr[$mime];

		$image = $imageCreateFun($source_file_path);
		/*
		 * 参数判断
		 */
		$font_color = explode(',', $font_color);
		$text_color = imagecolorallocatealpha($image, intval($font_color[0]), intval($font_color[1]), intval($font_color[2]),100-self::WATERMARK_OVERLAY_OPACITY); //文字水印颜色
		$direction = intval($direction) > 0 && intval($direction) < 10 ? intval($direction) : 1; //文字水印所在的位置
		$font_size = intval($font_size) > 0 ? intval($font_size) : 14;
		$angle = ($angle >= 0 && $angle < 90 || $angle > 270 && $angle < 360) ? $angle : 0; //判断输入的angle值有效性

		$watermark_content = explode('|', $watermark_content);

		/**
		 *  根据文字所在图片的位置方向，计算文字的坐标
		 * 首先获取文字的宽，高， 写一行文字，超出图片后是不显示的
		 */
		$textLength = count($watermark_content) - 1;
		$maxtext = 0;
		foreach ($watermark_content as $val) {
			$maxtext = strlen($val) > strlen($maxtext) ? $val : $maxtext;
		}
		$textSize = imagettfbbox($font_size, 0, $font_type, $maxtext);
		$textWidth = $textSize[2] - $textSize[1]; //文字的最大宽度
		$textHeight = $textSize[1] - $textSize[7]; //文字的高度
		$lineHeight = $textHeight + 3; //文字的行高

		//是否可以添加文字水印 只有图片的可以容纳文字水印时才添加
		if ($textWidth + 40 > $imgWidth || $lineHeight * $textLength + 40 > $imgHeight)return false; //图片太小了，无法添加文字水印
		switch ($direction) {
		   case 2: //中下部
			 $porintLeft = floor(($imgWidth - $textWidth) / 2);
			 $pointTop = $imgHeight - $textLength * $lineHeight - 20;
			 break;
		   case 3://左下部
			 $porintLeft = 20;
			 $pointTop = $imgHeight - $textLength * $lineHeight - 20;
			 break;
		   case 4://右中部
			 $porintLeft = $imgWidth - $textWidth - 60;
			 $pointTop = floor(($imgHeight - $textLength * $lineHeight) / 2);
			 break;
		   case 5://正中部
			 $porintLeft = floor(($imgWidth - $textWidth) / 2);
			 $pointTop = floor(($imgHeight - $textLength * $lineHeight) / 2);
			 break;
		   case 6:  //左中部
			 $porintLeft = 20;
			 $pointTop = floor(($imgHeight - $textLength * $lineHeight) / 2);
			 break;
		   case 7://右上部
			 $porintLeft = $imgWidth - $textWidth - 60;
			 $pointTop = 40;
			 break;
		   case 8://上中部
			 $porintLeft = floor(($imgWidth - $textWidth) / 2);
			 $pointTop = 40;
			 break;
		   case 9: //左上角
			 $porintLeft = 20;
			 $pointTop = 40;
			 break;
		   default://右下部
			 $porintLeft = $imgWidth - $textWidth - 60;
			 $pointTop = $imgHeight - $textLength * $lineHeight - 20;
			 break;
		}

		//如果有angle旋转角度，则重新设置 top ,left 坐标值
		if ($angle != 0) {
			if ($angle < 90) {
				$diffTop = ceil(sin($angle * M_PI / 180) * $textWidth);

				if (in_array($direction, array(7, 8, 9))) {// 上部 top 值增加
					$pointTop += $diffTop;
				} elseif (in_array($direction, array(4, 5, 6))) {// 中部 top 值根据图片总高判断
					if ($textWidth > ceil($imgHeight / 2)) {
						$pointTop += ceil(($textWidth - $imgHeight / 2) / 2);
					}
				}
			} elseif ($angle > 270) {
				$diffTop = ceil(sin((360 - $angle) * M_PI / 180) * $textWidth);

				if (in_array($direction, array(1, 2, 3))) {// 上部 top 值增加
					$pointTop -= $diffTop;
				} elseif (in_array($direction, array(4, 5, 6))) {// 中部 top 值根据图片总高判断
					if ($textWidth > ceil($imgHeight / 2)) {
						$pointTop = ceil(($imgHeight - $diffTop) / 2);
					}
				}
			}
		}

		foreach ($watermark_content as $key => $val) {
			imagettftext($image, $font_size, $angle, $porintLeft, $pointTop + $key * $lineHeight, $text_color, $font_type, $val);
		}


		if (self::$is_always_output_jpg){
			imagejpeg($image, $output_file_path,self:: WATERMARK_OUTPUT_QUALITY);
		}else{
			$imageOutputFunction = $imageOutputFunctionArr[$mime];
			if ($source_type==IMAGETYPE_PNG){
				$imageOutputFunction($image, $output_file_path);
			}else{
				$imageOutputFunction($image, $output_file_path,self:: WATERMARK_OUTPUT_QUALITY);
			}
		}
		imagedestroy($image);// 释放内存

		$uploaded_url =str_replace(Gc::$upload_path,"",$source_file_path);
		$uploaded_url=str_replace(DIRECTORY_SEPARATOR,"/",$uploaded_url);
		$processed_url=str_replace(Gc::$upload_path,"",$output_file_path);
		$processed_url=str_replace(DIRECTORY_SEPARATOR,"/",$processed_url);
		return array("file_path"=>$output_file_path,"url"=>$processed_url,"origin_file_path"=>$source_file_path,"origin_url"=>$uploaded_url);
	}

	/**
	 * @param mixed $source_file_path 源图片路径
	 * @param mixed $output_file_path 处理后输出图片路径
	 * @param array $watermark_content 水印文字
	 * @param int $direction 水印位置:水印所在的方位，默认为右下角
	 * 9:左上角[NorthWest] 8:正上角[North] 7:右上角[NorthEast]
	 * 6:中左角[West]      5:中部[Center]  4:中右角[East]
	 * 3:左下角[SouthWest] 2:正下角[South] 1:右下角[SouthEast]
	 * @param string $font_color //字体颜色;如:255,255,255
	 * @return mixed bool 生成失败;array[file_path:生成水印图片的物理路径,url:生成水印图片的网络路径,origin_file_path:原图片物理路径,origin_url:原图片网络路径]
	 */
	public static function watermark_text($source_file_path, $output_file_path,$watermark_content,$direction=1,$font_color="255,255,255")
	{
		$font_type = Gc::$upload_path."font".DIRECTORY_SEPARATOR."msyh.ttc"; //字体
		if (!file_exists($font_type))die("请在指定路径下放置指定字体文件，默认是微软雅黑字体:".$font_type);
		$font_size = 22; //字体大小
		$angle=0;//旋转角度

		UtilFileSystem::createDir(dirname($source_file_path));
		UtilFileSystem::createDir(dirname($output_file_path));

		if (empty($watermark_content))$watermark_content=Gc::$site_name;
		$imgsize=getimagesize($source_file_path);
		list($width, $height, $source_type,$size_desc) = $imgsize;
		if ($source_type === NULL)return false;

		$imageCreateFunctionArr = array('image/jpeg' => 'imagecreatefromjpeg', 'image/png' => 'imagecreatefrompng', 'image/gif' => 'imagecreatefromgif');
		$imageOutputFunctionArr = array('image/jpeg' => 'imagejpeg', 'image/png' => 'imagepng', 'image/gif' => 'imagegif');

		$mime=$imgsize["mime"];

		$image = imagecreatetruecolor($width, $height);
		$font_color = imagecolorallocatealpha($image, intval($font_color[0]), intval($font_color[1]), intval($font_color[2]) ,100-self::WATERMARK_OVERLAY_OPACITY);

		$imageCreateFunction = $imageCreateFunctionArr[$mime];
		$image_src = $imageCreateFunction($source_file_path);

		imagecopyresampled($image, $image_src, 0, 0, 0, 0, $width, $height, $width, $height);

		$offset_x=ceil($width/20);
		$offset_y=ceil($height/150);

		$textSize = imagettfbbox($font_size, $angle, $font_type, $watermark_content);
		$overlay_width = $textSize[2] - $textSize[1]; //文字的最大宽度
		$overlay_height = $textSize[1] - $textSize[7]; //文字的高度
		$overlay_height = $overlay_height + 3; //文字的行高

		if (($direction>3)&&($offset_y<$overlay_height))$offset_y+= $overlay_height;

		//是否可以添加文字水印 只有图片的可以容纳文字水印时才添加
		if ($overlay_width + 40 > $width || $overlay_height + 40 > $height)return false; //图片太小了，无法添加文字水印

		switch ($direction) {
		   case 2:
			 //ALIGN BOTTOM
			 imagettftext($image, $font_size, $angle, ($width - $overlay_width)/2+$offset_x,$height - $overlay_height-$offset_y, $font_color, $font_type, $watermark_content);
			 break;
		   case 3:
			 //ALIGN BOTTOM, LEFT
			 imagettftext($image, $font_size, $angle, 0+$offset_x,$height - $overlay_height-$offset_y, $font_color, $font_type, $watermark_content);
			 break;
		   case 4:
			 //ALIGN Center, Right
			 imagettftext($image, $font_size, $angle, $width - $overlay_width-$offset_x,($height - $overlay_height)/2, $font_color, $font_type, $watermark_content);
			 break;
		   case 5:
			 //ALIGN Center, Center
			 imagettftext($image, $font_size, $angle,($width - $overlay_width)/2+$offset_x,($height - $overlay_height)/2, $font_color, $font_type, $watermark_content);
			 break;
		   case 6:
			 //ALIGN Center, LEFT
			 imagettftext($image, $font_size, $angle, 0+$offset_x,($height - $overlay_height)/2+$offset_y, $font_color, $font_type, $watermark_content);
			 break;
		   case 7:
			 //ALIGN TOP, RIGHT
			 imagettftext($image, $font_size, $angle,$width - $overlay_width-$offset_x,0+$offset_y, $font_color, $font_type, $watermark_content);
			 break;
		   case 8:
			 //ALIGN TOP
			 imagettftext($image, $font_size, $angle, ($width - $overlay_width)/2+$offset_x,0+$offset_y, $font_color, $font_type, $watermark_content);
			 break;
		   case 9:
			 //ALIGN TOP, LEFT
			 imagettftext($image, $font_size, $angle, 0+$offset_x,0+$offset_y, $font_color, $font_type, $watermark_content);
			 break;
		   default:
			 //ALIGN BOTTOM, RIGHT
			 imagettftext($image, $font_size, $angle, $width - $overlay_width-$offset_x,$height - $overlay_height-$offset_y, $font_color, $font_type, $watermark_content);
			 break;
		}

		if (self::$is_always_output_jpg){
			imagejpeg($image, $output_file_path,self:: WATERMARK_OUTPUT_QUALITY);
		}else{
			$imageOutputFunction = $imageOutputFunctionArr[$mime];
			if ($source_type==IMAGETYPE_PNG){
				$imageOutputFunction($image, $output_file_path);
			}else{
				$imageOutputFunction($image, $output_file_path,self:: WATERMARK_OUTPUT_QUALITY);
			}
		}
		imagedestroy($image);
		if(self::$is_delete_source_image)unlink($source_file_path);
		$uploaded_url =str_replace(Gc::$upload_path,"",$source_file_path);
		$uploaded_url=str_replace(DIRECTORY_SEPARATOR,"/",$uploaded_url);
		$processed_url=str_replace(Gc::$upload_path,"",$output_file_path);
		$processed_url=str_replace(DIRECTORY_SEPARATOR,"/",$processed_url);
		return array("file_path"=>$output_file_path,"url"=>$processed_url,"origin_file_path"=>$source_file_path,"origin_url"=>$uploaded_url);
	}

	/**
	 * 处理在原图像上加水印
	 * @param mixed $source_file_path 源图片路径
	 * @param mixed $output_file_path 处理后输出图片路径
	 * @param string $watermark_image_filename 水印图片的路径
	 * @param int $direction 水印所在的方位，默认为右下角
	 * 9:左上角[NorthWest] 8:正上角[North] 7:右上角[NorthEast]
	 * 6:中左角[West]      5:中部[Center]  4:中右角[East]
	 * 3:左下角[SouthWest] 2:正下角[South] 1:右下角[SouthEast]
	 * @return mixed bool 生成失败;array[file_path:生成水印图片的物理路径,url:生成水印图片的网络路径,origin_file_path:原图片物理路径,origin_url:原图片网络路径]
	 */
	public static function create_watermark($source_file_path, $output_file_path,$watermark_image_filename="watermark.png",$direction=1)
	{
		self::$watermark_overlay_image=$watermark_image_filename;
		if (!file_exists(self::$watermark_overlay_image)){
			LogMe::log("水印图片不存在:".self::$watermark_overlay_image);
			exit;
		}
		UtilFileSystem::createDir(dirname($source_file_path));
		UtilFileSystem::createDir(dirname($output_file_path));
		$imgsize=getimagesize($source_file_path);
		list($source_width, $source_height, $source_type,$size_desc) = $imgsize;
		$mime=$imgsize["mime"];
		if ($source_type === NULL)return false;

		$imageCreateFunctionArr = array('image/jpeg' => 'imagecreatefromjpeg', 'image/png' => 'imagecreatefrompng', 'image/gif' => 'imagecreatefromgif');
		$imageOutputFunctionArr = array('image/jpeg' => 'imagejpeg', 'image/png' => 'imagepng', 'image/gif' => 'imagegif');
		$imageCreateFunction = $imageCreateFunctionArr[$mime];
		$source_gd_image = $imageCreateFunction($source_file_path);

		$imgsize_overlay=getimagesize(self::$watermark_overlay_image);
		$mime_overlay=$imgsize_overlay["mime"];
		$imageCreate_overlayFunction = $imageCreateFunctionArr[$mime_overlay];

		$overlay_gd_image = $imageCreate_overlayFunction(self::$watermark_overlay_image);
		$overlay_width = imagesx($overlay_gd_image);
		$overlay_height = imagesy($overlay_gd_image);

		$offset_x=ceil($source_width/50);
		$offset_y=ceil($source_height/50);

		switch ($direction) {
		   case 2:
			 //ALIGN BOTTOM
			 imagecopymerge(
				$source_gd_image,$overlay_gd_image,($source_width - $overlay_width)/2+$offset_x,$source_height - $overlay_height-$offset_y,0,0,$overlay_width,$overlay_height,self::WATERMARK_OVERLAY_OPACITY
			 );
			 break;
		   case 3:
			 //ALIGN BOTTOM, LEFT
			 imagecopymerge(
				$source_gd_image,$overlay_gd_image,0+$offset_x,$source_height - $overlay_height-$offset_y,0,0,$overlay_width,$overlay_height,self::WATERMARK_OVERLAY_OPACITY
			 );
			 break;
		   case 4:
			 //ALIGN Center, Right
			 imagecopymerge(
				$source_gd_image,$overlay_gd_image,$source_width - $overlay_width-$offset_x,($source_height - $overlay_height)/2,0,0,$overlay_width,$overlay_height,self::WATERMARK_OVERLAY_OPACITY
			 );
			 break;
		   case 5:
			 //ALIGN Center, Center
			 imagecopymerge(
				$source_gd_image,$overlay_gd_image,($source_width - $overlay_width)/2+$offset_x,($source_height - $overlay_height)/2,0,0,$overlay_width,$overlay_height,self::WATERMARK_OVERLAY_OPACITY
			 );
			 break;
		   case 6:
			 //ALIGN Center, LEFT
			 imagecopymerge(
				$source_gd_image,$overlay_gd_image,0+$offset_x,($source_height - $overlay_height)/2+$offset_y,0,0,$overlay_width,$overlay_height,self::WATERMARK_OVERLAY_OPACITY
			 );
			 break;
		   case 7:
			 //ALIGN TOP, RIGHT
			 imagecopymerge(
				$source_gd_image,$overlay_gd_image,$source_width - $overlay_width-$offset_x,0+$offset_y,0,0,$overlay_width,$overlay_height,self::WATERMARK_OVERLAY_OPACITY
			 );
			 break;
		   case 8:
			 //ALIGN TOP
			 imagecopymerge(
				$source_gd_image,$overlay_gd_image,($source_width - $overlay_width)/2+$offset_x,0+$offset_y,0,0,$overlay_width,$overlay_height,self::WATERMARK_OVERLAY_OPACITY
			 );
			 break;
		   case 9:
			 //ALIGN TOP, LEFT
			 imagecopymerge(
				$source_gd_image,$overlay_gd_image,0+$offset_x,0+$offset_y,0,0,$overlay_width,$overlay_height,self::WATERMARK_OVERLAY_OPACITY
			 );
			 break;
		   default:
			 //ALIGN BOTTOM, RIGHT
			 imagecopymerge(
				$source_gd_image,$overlay_gd_image,$source_width - $overlay_width-$offset_x,$source_height - $overlay_height-$offset_y,0,0,$overlay_width,$overlay_height,self::WATERMARK_OVERLAY_OPACITY
			 );
			 break;
		}

		if (self::$is_always_output_jpg){
			imagejpeg($source_gd_image, $output_file_path,self:: WATERMARK_OUTPUT_QUALITY);
		}else{
			$imageOutputFunction = $imageOutputFunctionArr[$mime];
			if ($source_type==IMAGETYPE_PNG){
				$imageOutputFunction($source_gd_image, $output_file_path);
			}else{
				$imageOutputFunction($source_gd_image, $output_file_path,self:: WATERMARK_OUTPUT_QUALITY);
			}
		}
		imagedestroy($source_gd_image);
		imagedestroy($overlay_gd_image);

		if(self::$is_delete_source_image)unlink($source_gd_image);

		$uploaded_url =str_replace(Gc::$upload_path,"",$source_file_path);
		$uploaded_url=str_replace(DIRECTORY_SEPARATOR,"/",$uploaded_url);
		$processed_url=str_replace(Gc::$upload_path,"",$output_file_path);
		$processed_url=str_replace(DIRECTORY_SEPARATOR,"/",$processed_url);
		return array("file_path"=>$output_file_path,"url"=>$processed_url,"origin_file_path"=>$source_file_path,"origin_url"=>$uploaded_url);
	}
}
?>
