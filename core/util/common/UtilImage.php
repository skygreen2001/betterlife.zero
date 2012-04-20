<?php
/**
 +---------------------------------<br/>
 * 功能:处理图形图像相关的事宜方法。<br/>
 +---------------------------------
 * @category betterlife
 * @package util.common
 * @author skygreen
 */
class UtilImage 
{
	/**
	 +----------------------------------------------------------<br/>
	 * 取得图像信息<br/>
	 +----------------------------------------------------------
	 * @static
	 * @access public
	 +----------------------------------------------------------
	 * @param string $image 图像文件名
	 +----------------------------------------------------------
	 * @return mixed
	 +----------------------------------------------------------
	 */
	public static function getImageInfo($img) 
	{
		$imageInfo = getimagesize($img);
		if( $imageInfo!== false) {
			$imageType = strtolower(substr(image_type_to_extension($imageInfo[2]),1));
			$imageSize = filesize($img);
			$info = array(
					"width"=>$imageInfo[0],
					"height"=>$imageInfo[1],
					"type"=>$imageType,
					"size"=>$imageSize,
					"mime"=>$imageInfo['mime']
			);
			return $info;
		}else {
			return false;
		}
	}

	/**
	 +----------------------------------------------------------<br/>
	 * 显示服务器图像文件<br/>
	 * 支持URL方式<br/>
	 +----------------------------------------------------------
	 * @static
	 * @access public
	 +----------------------------------------------------------
	 * @param string $imgFile 图像文件名
	 * @param string $text 文字字符串
	 * @param string $width 图像宽度
	 * @param string $height 图像高度
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 */
	public static function showImg($imgFile,$text='',$width=80,$height=30) 
	{
		//获取图像文件信息
		$info = self::getImageInfo($imgFile);
		if($info !== false) {
			$createFun  =   str_replace('/','createfrom',$info['mime']);
			$im = $createFun($imgFile);
			if($im) {
				$ImageFun= str_replace('/','',$info['mime']);
				if(!empty($text)) {
					$tc  = imagecolorallocate($im, 0, 0, 0);
					imagestring($im, 3, 5, 5, $text, $tc);
				}
				if($info['type']=='png' || $info['type']=='gif') {
					imagealphablending($im, false);//取消默认的混色模式
					imagesavealpha($im,true);//设定保存完整的 alpha 通道信息
				}
				header("Content-type: ".$info['mime']);
				$ImageFun($im);
				imagedestroy($im);
				return ;
			}
		}
		//获取或者创建图像文件失败则生成空白PNG图片
		$im  = imagecreatetruecolor($width, $height);
		$bgc = imagecolorallocate($im, 255, 255, 255);
		$tc  = imagecolorallocate($im, 0, 0, 0);
		imagefilledrectangle($im, 0, 0, 150, 30, $bgc);
		imagestring($im, 4, 5, 5, "NO PIC", $tc);
		self::output($im);
		return ;
	} 

	/**
	 * 从中心开始往外裁剪     
	 * @param mixed $src_file 源文件，若不指定，则为相对路径
	 * @param mixed $dim_file 目标文件，若不指定，则为相对路径 
	 * @param mixed $dimWidth 目标尺寸，若不写"%"，则为指定大小
	 * @param mixed $dimHeight 目标尺寸，若不写"%"，则为指定大小  
	 * @return 图片
	 * @else return  false
	 */
	public static function cenCutImg($src_file,$dim_file,$dimWidth,$dimHeight)
	{
		//获取原图信息
		$info =self::getImageInfo($src_file);
		
		if($info !==false){
			//设置src_file的信息
			$srcWidth = $info['width'];
			$srcHeight= $info['height'];
			$type = empty($type)?$info['type']:$type;
			$type = strtolower($type);
			unset($info);
			
			//计算是按照具体尺寸还是 %修改图片
			if (contain($dimWidth,"%"))
			{
				$dimWidth=substr($dimWidth,0,(strlen($dimWidth)-1));
				$dimWidth=$srcWidth*$maxWidth/100;
			}    
			else
				$mWidth=$dimWidth;
	
			if (contain($dimHeight,"%"))
			{
				$dimHeight=substr($dimHeight,0,(strlen($dimHeight)-1));
				$dimHeight=$srcHeight*$dimHeight/100;
			}    
			else
				$dimHeight=$dimHeight; 
					  
			//判断裁剪的大小是否溢出，若溢出，则按照原始大小作为裁剪大小
			if($dimWidth>$srcWidth)
				$dimWidth=$srcWidth;
			if($dimHeight>$srcHeight)
				$dimHeight=$srcHeight;
				
			//设定以中心位置散开后，开始裁剪的起始位置
			$cut_x=($srcWidth-$dimWidth)/2;
			$cut_y=($srcHeight-$dimHeight)/2;

			// 载入原图
			$createFun = 'ImageCreateFrom'.($type=='jpg'?'jpeg':$type);
			$srcImg = $createFun($src_file);
			
			//创建图片
			if($type!='gif' && function_exists("imagecreatetruecolor")) {  
				$dim = imagecreatetruecolor($dimWidth, $dimHeight);
			} else {  
				$dim = imagecreate($$dimWidthxx, $dimHeight);
			}            

			if('gif'==$type || 'png'==$type) {
				$background_color  =  imagecolorallocate($dim,  0,255,0);  //  指派一个绿色
				imagecolortransparent($dim,$background_color);  //  设置为透明色，若注释掉该行则输出绿色的图
			}
			
			//图片开始裁剪
			imagecopy ( $dim , $srcImg , "0" , "0" ,$cut_x ,$cut_y, $srcWidth , $srcHeight ) ;

			//$gray=ImageColorAllocate($thumbImg,255,0,0);
			//ImageString($thumbImg,2,5,5,"ThinkPHP",$gray);
			// 生成图片
			$imageFun = 'image'.($type=='jpg'?'jpeg':$type);
			$dim_file_dir=dirname($dim_file);
			UtilFileSystem::createDir($dim_file_dir);   
			$imageFun($dim,$dim_file);
			imagedestroy($dim);
			imagedestroy($srcImg);
			return $dim_file;
		}
		return false;     
	}    
	
	/**
	 +----------------------------------------------------------<br/>
	 * 生成缩略图<br/>
	 +----------------------------------------------------------
	 * @static
	 * @access public
	 +----------------------------------------------------------
	 * @param string $image  原图文件名          
	 * @param string $thumbname 缩略图文件名 example d://abc.jpg 则指向d盘下面
	 * @param string $type 图像格式.如:jpg,png,gif
	 * @param string $maxWidth 宽度 若带"%"  则表示比例
	 * @param string $maxHeight 高度
	 * @param string $position 缩略图保存目录 //该参数为空
	 * @param boolean $interlace 启用隔行扫描,默认true
	 * @param boolean $isStrict 是否严格按尺寸来缩放，默认false是取宽高中的最小值成比例缩放
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 */
	public static function thumb($image,$thumbname,$type='',$maxWidth=200,$maxHeight=50,$interlace=true,$isStrict=false) 
	{
		// 获取原图信息
		$info  = self::getImageInfo($image);
		if($info !== false) {
			$srcWidth  = $info['width'];
			$srcHeight = $info['height'];
			$type = empty($type)?$info['type']:$type;
			$type = strtolower($type);
			$interlace  =  $interlace? 1:0;
			unset($info);
			
			//计算是按照具体尺寸还是 %修改图片
			if (contain($maxWidth,"%"))
			{
				$maxWidth=substr($maxWidth,0,(strlen($maxWidth)-1));
				$mWidth=$srcWidth*$maxWidth/100;
			}    
			else
				$mWidth=$maxWidth;
	
			if (contain($maxHeight,"%"))
			{
				$maxHeight=substr($maxHeight,0,(strlen($maxHeight)-1));
				$mHeight=$srcHeight*$maxHeight/100;
			}    
			else
				$mHeight=$maxHeight;           
			$scale = min($mWidth/$srcWidth, $mHeight/$srcHeight); // 计算缩放比例
			if ($isStrict){
				$width   =  $mWidth;
				$height  =  $mHeight;
			}else{
				if($scale>=1) {
					// 超过原图大小不再缩略
					$width   =  $srcWidth;
					$height  =  $srcHeight;
				}else {
					// 缩略图尺寸
					$width  = (int)($srcWidth*$scale);
					$height = (int)($srcHeight*$scale);
				}
			}

			// 载入原图
			$createFun = 'ImageCreateFrom'.($type=='jpg'?'jpeg':$type);
			$srcImg = $createFun($image);

			//创建缩略图
			if($type!='gif' && function_exists('imagecreatetruecolor'))
				$thumbImg = imagecreatetruecolor($width, $height);
			else
				$thumbImg = imagecreate($width, $height);

			// 复制图片
			if(function_exists("ImageCopyResampled"))
				imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth,$srcHeight);
			else
				imagecopyresized($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height,  $srcWidth,$srcHeight);
			if('gif'==$type || 'png'==$type) {
				//imagealphablending($thumbImg, false);//取消默认的混色模式
				//imagesavealpha($thumbImg,true);//设定保存完整的 alpha 通道信息
				$background_color  =  imagecolorallocate($thumbImg,  0,255,0);  //  指派一个绿色
				imagecolortransparent($thumbImg,$background_color);  //  设置为透明色，若注释掉该行则输出绿色的图
			}

			// 对jpeg图形设置隔行扫描
			if('jpg'==$type || 'jpeg'==$type)     imageinterlace($thumbImg,$interlace);

			//$gray=ImageColorAllocate($thumbImg,255,0,0);
			//ImageString($thumbImg,2,5,5,"ThinkPHP",$gray);
			// 生成图片
			$imageFun = 'image'.($type=='jpg'?'jpeg':$type);
			$image_dir=dirname($thumbname);
			UtilFileSystem::createDir($image_dir);
			$imageFun($thumbImg,$thumbname);
			imagedestroy($thumbImg);
			imagedestroy($srcImg);
			return $thumbname;
		}
		return false;
	}

	/**
	 +----------------------------------------------------------<br/>
	 * 根据给定的字符串生成图像<br/>
	 +----------------------------------------------------------
	 * @static
	 * @access public
	 +----------------------------------------------------------
	 * @param string $string  字符串
	 * @param string $font 字体信息 fontface,fontsize 或者 array(fontface,fontsize)
	 * @param string $size  图像大小 width,height 或者 array(width,height)
	 * @param string $type 图像格式 默认PNG
	 * @param integer $disturb 是否干扰 1 点干扰 2 线干扰 3 复合干扰 0 无干扰
	 * @param bool $border  是否加边框 array(color)
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	public static function buildString($string,$rgb=array(),$filename='',$type='png',$disturb=1,$border=true,$font='simhei.ttf,8',$size=array(48,22)) 
	{
		if(is_string($size)) {
			$size=explode(',',$size);
		}
		$width	=$size[0];
		$height	=$size[1];
		if(is_string($font)) {
			$font=explode(',',$font);
		}
		$fontface=$font[0];
		$fontsize=$font[1];
		$length	=strlen($string);
		$width = ($length*9+10)>$width?$length*9+10:$width;
		$height	=22;
		if ( $type!='gif' && function_exists('imagecreatetruecolor')) {
			$im = @imagecreatetruecolor($width,$height);
		}else {
			$im = @imagecreate($width,$height);
		}
		if(empty($rgb)) {
			$color = imagecolorallocate($im, 102, 104, 104);
		}else {
			$color = imagecolorallocate($im, $rgb[0], $rgb[1], $rgb[2]);
		}
		$backColor = imagecolorallocate($im, 255,255,255);    //背景色（随机）
		$borderColor = imagecolorallocate($im, 100, 100, 100);                    //边框色
		$pointColor = imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));                 //点颜色

		@imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
		@imagerectangle($im, 0, 0, $width-1, $height-1, $borderColor);
		@imagestring($im, 5, 5, 3, $string, $color);
		if(!empty($disturb)) {
			// 添加干扰
			if($disturb==1||$disturb==3) {
				for($i=0;$i<25;$i++) {
					imagesetpixel($im,mt_rand(0,$width),mt_rand(0,$height),$pointColor);
				}
			}elseif($disturb==2 || $disturb==3) {
				for($i=0;$i<10;$i++) {
					imagearc($im,mt_rand(-10,$width),mt_rand(-10,$height),mt_rand(30,300),mt_rand(20,200),55,44,$pointColor);
				}
			}
		}
		self::output($im,$type,$filename);
	}

	/**
	 +----------------------------------------------------------<br/>
	 * 生成图像验证码<br/>
	 +----------------------------------------------------------
	 * @static
	 * @access public
	 +----------------------------------------------------------
	 * @param string $length  位数
	 * @param string $mode  类型
	 * @param string $type 图像格式
	 * @param string $width  宽度
	 * @param string $height  高度
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	public static function buildImageVerify($length=4,$mode=1,$type='png',$width=48,$height=22,$verifyName='verify') 
	{
		session_start();
		$randval = UtilString::rand_string($length,$mode);
		$_SESSION[$verifyName]= md5($randval);
		$width = ($length*10+10)>$width?$length*10+10:$width;
		if ( $type!='gif' && function_exists('imagecreatetruecolor')) {
			$im = @imagecreatetruecolor($width,$height);
		}else {
			$im = @imagecreate($width,$height);
		}
		$r = Array(225,255,255,223);
		$g = Array(225,236,237,255);
		$b = Array(225,236,166,125);
		$key = mt_rand(0,3);

		$backColor=  imagecolorallocate($im,64,64,64);
		$borderColor = imagecolorallocate($im, 100, 100, 100);                    //边框色
		$pointColor = imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));                 //点颜色

		@imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
		@imagerectangle($im, 0, 0, $width-1, $height-1, $borderColor);
		$stringColor = imagecolorallocate($im,255,255,255);
		// 干扰
		for($i=0;$i<$length;$i++) {
			imagestring($im,5,$i*10+5,mt_rand(1,8),$randval{$i}, $stringColor);
		}
		self::output($im,$type);
	}
	
	/**
	 +----------------------------------------------------------<br/>
	 * 生成图像验证码<br/>
	 +----------------------------------------------------------
	 * @static
	 * @access public
	 +----------------------------------------------------------
	 * @param string $length  位数
	 * @param string $mode  类型
	 * @param string $type 图像格式
	 * @param string $width  宽度
	 * @param string $height  高度
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	public static function buildImageVerifyAdvanced($length=4,$mode=1,$type='png',$width=48,$height=22,$verifyName='verify') 
	{
		session_start();
		$randval = UtilString::rand_string($length,$mode);
		$_SESSION[$verifyName]= md5($randval);
		$width = ($length*10+10)>$width?$length*10+10:$width;
		if ( $type!='gif' && function_exists('imagecreatetruecolor')) {
			$im = @imagecreatetruecolor($width,$height);
		}else {
			$im = @imagecreate($width,$height);
		}
		$r = Array(225,255,255,223);
		$g = Array(225,236,237,255);
		$b = Array(225,236,166,125);
		$key = mt_rand(0,3);

		$backColor = imagecolorallocate($im, $r[$key],$g[$key],$b[$key]);    //背景色（随机）
		$borderColor = imagecolorallocate($im, 100, 100, 100);                    //边框色
		$pointColor = imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));                 //点颜色

		@imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
		@imagerectangle($im, 0, 0, $width-1, $height-1, $borderColor);
		$stringColor = imagecolorallocate($im,mt_rand(0,200),mt_rand(0,120),mt_rand(0,120));
		// 干扰
		for($i=0;$i<10;$i++) {
			$fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			imagearc($im,mt_rand(-10,$width),mt_rand(-10,$height),mt_rand(30,300),mt_rand(20,200),55,44,$fontcolor);
		}
		for($i=0;$i<25;$i++) {
			$fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			imagesetpixel($im,mt_rand(0,$width),mt_rand(0,$height),$pointColor);
		}
		for($i=0;$i<$length;$i++) {
			imagestring($im,5,$i*10+5,mt_rand(1,8),$randval{$i}, $stringColor);
		}
//        @imagestring($im, 5, 5, 3, $randval, $stringColor);
		self::output($im,$type);
	}

	/**
	 +----------------------------------------------------------<br/>
	 * 生成中文验证码<br/>
	 +----------------------------------------------------------
	 * @static
	 * @access public
	 +----------------------------------------------------------
	 * @param ints $length 位数
	 * @param string $type 图像格式
	 * @param string $width  宽度
	 * @param string $height  高度
	 * @param string $fontface 字体信息 fontface,fontsize 或者 array(fontface,fontsize)
	 * @param Image $verifyName 中文验证码
	 */
	public static function GBVerify($length=4,$type='png',$width=180,$height=50,$fontface='simhei.ttf',$verifyName='verify') 
	{
		$code = UtilString::rand_string($length,4);
		$width = ($length*45)>$width?$length*45:$width;
		$_SESSION[$verifyName]= md5($code);
		$im=imagecreatetruecolor($width,$height);
		$borderColor = imagecolorallocate($im, 100, 100, 100);                    //边框色
		$bkcolor=imagecolorallocate($im,250,250,250);
		imagefill($im,0,0,$bkcolor);
		@imagerectangle($im, 0, 0, $width-1, $height-1, $borderColor);
		// 干扰
		for($i=0;$i<15;$i++) {
			$fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			imagearc($im,mt_rand(-10,$width),mt_rand(-10,$height),mt_rand(30,300),mt_rand(20,200),55,44,$fontcolor);
		}
		for($i=0;$i<255;$i++) {
			$fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			imagesetpixel($im,mt_rand(0,$width),mt_rand(0,$height),$fontcolor);
		}
		if(!is_file($fontface)) {
			$fontface = dirname(__FILE__)."/".$fontface;
		}
		for($i=0;$i<$length;$i++) {
			$fontcolor=imagecolorallocate($im,mt_rand(0,120),mt_rand(0,120),mt_rand(0,120)); //这样保证随机出来的颜色较深。
			$codex= UtilString::msubstr($code,$i,1);
			imagettftext($im,mt_rand(16,20),mt_rand(-60,60),40*$i+20,mt_rand(30,35),$fontcolor,$fontface,$codex);
		}
		self::output($im,$type);
	}

	/**
	 +----------------------------------------------------------<br/>
	 * 把图像转换成字符显示<br/>
	 +----------------------------------------------------------
	 * @static
	 * @access public
	 +----------------------------------------------------------
	 * @param string $image  要显示的图像
	 * @param string $type  图像类型，默认自动获取
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	public static function showASCIIImg($image,$string='',$type='') 
	{
		$info  = self::getImageInfo($image);
		if($info !== false) {
			$type = empty($type)?$info['type']:$type;
			unset($info);
			// 载入原图
			$createFun = 'ImageCreateFrom'.($type=='jpg'?'jpeg':$type);
			$im     = $createFun($image);
			$dx = imagesx($im);
			$dy = imagesy($im);
			$i	=	0;
			$out   =  '<span style="padding:0px;margin:0;line-height:100%;font-size:1px;">';
			set_time_limit(0);
			for($y = 0; $y < $dy; $y++) {
				for($x=0; $x < $dx; $x++) {
					$col = imagecolorat($im, $x, $y);
					$rgb = imagecolorsforindex($im,$col);
					$str	 =	 empty($string)?'*':$string[$i++];
					$out .= sprintf('<span style="margin:0px;color:#%02x%02x%02x">'.$str.'</span>',$rgb['red'],$rgb['green'],$rgb['blue']);
				}
				$out .= "<br>\n";
			}
			$out .=  '</span>';
			imagedestroy($im);
			return $out;
		}
		return false;
	}

	/**
	 +----------------------------------------------------------<br/>
	 * 生成高级图像验证码<br/>
	 +----------------------------------------------------------
	 * @static
	 * @access public
	 +----------------------------------------------------------
	 * @param string $type 图像格式
	 * @param string $width  宽度
	 * @param string $height  高度
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	public static function showAdvVerify($type='png',$width=180,$height=40,$verifyName='verifyCode') 
	{
		$rand	=	range('a','z');
		shuffle($rand);
		$verifyCode	=	array_slice($rand,0,10);
		$letter = implode(" ",$verifyCode);
		$_SESSION[$verifyName] = $verifyCode;
		$im = imagecreate($width,$height);
		$r = array(225,255,255,223);
		$g = array(225,236,237,255);
		$b = array(225,236,166,125);
		$key = mt_rand(0,3);
		$backColor = imagecolorallocate($im, $r[$key],$g[$key],$b[$key]);
		$borderColor = imagecolorallocate($im, 100, 100, 100);                    //边框色
		imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
		imagerectangle($im, 0, 0, $width-1, $height-1, $borderColor);
		$numberColor = imagecolorallocate($im, 255,rand(0,100), rand(0,100));
		$stringColor = imagecolorallocate($im, rand(0,100), rand(0,100), 255);
		// 添加干扰
		/*
		for($i=0;$i<10;$i++){
			$fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			imagearc($im,mt_rand(-10,$width),mt_rand(-10,$height),mt_rand(30,300),mt_rand(20,200),55,44,$fontcolor);
		}
		for($i=0;$i<255;$i++){
			$fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			imagesetpixel($im,mt_rand(0,$width),mt_rand(0,$height),$fontcolor);
		}*/
		imagestring($im, 5, 5, 1, "0 1 2 3 4 5 6 7 8 9", $numberColor);
		imagestring($im, 5, 5, 20, $letter, $stringColor);
		self::output($im,$type);
	}

	/**
	 +----------------------------------------------------------<br/>
	 * 生成UPC-A条形码<br/>
	 +----------------------------------------------------------
	 * @static
	 * @access public
	 +----------------------------------------------------------
	 * @param string $code UPC-A编码
	 * @param string $type 图像格式
	 * @param string $lw  单元宽度
	 * @param string $hi  条码高度
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	public static function UPCA($code,$type='png',$lw=2,$hi=100) 
	{
		static $Lencode = array('0001101','0011001','0010011','0111101','0100011',
		'0110001','0101111','0111011','0110111','0001011');
		static $Rencode = array('1110010','1100110','1101100','1000010','1011100',
		'1001110','1010000','1000100','1001000','1110100');
		$ends = '101';
		$center = '01010';
		/* UPC-A Must be 11 digits, we compute the checksum. */
		if (strlen($code) != 11 ) {
			die("UPC-A Must be 11 digits.");
		}
		/* Compute the EAN-13 Checksum digit */
		$ncode = '0'.$code;
		$even = 0;
		$odd = 0;
		for ($x=0;$x<12;$x++) {
			if ($x % 2) {
				$odd += $ncode[$x];
			} else {
				$even += $ncode[$x];
			}
		}
		$code.=(10 - (($odd * 3 + $even) % 10)) % 10;
		/* Create the bar encoding using a binary string */
		$bars=$ends;
		$bars.=$Lencode[$code[0]];
		for($x=1;$x<6;$x++) {
			$bars.=$Lencode[$code[$x]];
		}
		$bars.=$center;
		for($x=6;$x<12;$x++) {
			$bars.=$Rencode[$code[$x]];
		}
		$bars.=$ends;
		/* Generate the Barcode Image */
		if ( $type!='gif' && function_exists('imagecreatetruecolor')) {
			$im = imagecreatetruecolor($lw*95+30,$hi+30);
		}else {
			$im = imagecreate($lw*95+30,$hi+30);
		}
		$fg = ImageColorAllocate($im, 0, 0, 0);
		$bg = ImageColorAllocate($im, 255, 255, 255);
		ImageFilledRectangle($im, 0, 0, $lw*95+30, $hi+30, $bg);
		$shift=10;
		for ($x=0;$x<strlen($bars);$x++) {
			if (($x<10) || ($x>=45 && $x<50) || ($x >=85)) {
				$sh=10;
			} else {
				$sh=0;
			}
			if ($bars[$x] == '1') {
				$color = $fg;
			} else {
				$color = $bg;
			}
			ImageFilledRectangle($im, ($x*$lw)+15,5,($x+1)*$lw+14,$hi+5+$sh,$color);
		}
		/* Add the Human Readable Label */
		ImageString($im,4,5,$hi-5,$code[0],$fg);
		for ($x=0;$x<5;$x++) {
			ImageString($im,5,$lw*(13+$x*6)+15,$hi+5,$code[$x+1],$fg);
			ImageString($im,5,$lw*(53+$x*6)+15,$hi+5,$code[$x+6],$fg);
		}
		ImageString($im,4,$lw*95+17,$hi-5,$code[11],$fg);
		/* Output the Header and Content. */
		self::output($im,$type);
	}

	private static function output($im,$type='png',$filename='') 
	{
		header("Content-type: image/".$type);
		$ImageFun='image'.$type;
		if(empty($filename)) {
			$ImageFun($im);
		}else {
			$ImageFun($im,$filename);
		}
		imagedestroy($im);
	}
}

//UtilImage::buildString("Nothing", "255,17,136","c:\\loveyou.png");
?>
