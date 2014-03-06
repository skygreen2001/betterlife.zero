<?php
/*
 * PHP function to image-watermark an image
 * http://salman-w.blogspot.com/2008/11/watermark-your-images-with-another.html
 *
 * Writes the given watermark image on the specified image
 * and saves the result as another image
 *
 *
 */
require_once ("../../../init.php");
/**
 * 创建水印的类型
 * 0:文字水印
 * 1:图片水印
 * 2:多行文字水印
 * 默认是文字水印
 */
$type_create_watermark=1;
//图片水印:是否上传文件]
$is_watermark_file_upload=true;
if(isset($_GET["type"]))$type_create_watermark=$_GET["type"];
if(isset($_GET["f"]))$is_watermark_file_upload=$_GET["f"];

switch ($type_create_watermark) {
   case 0:
		/********************************* 添加文字水印 *******************************/
		$result=UtilWatermark::watermark_text(
		Gc::$upload_path."watermark".DIRECTORY_SEPARATOR."originals".DIRECTORY_SEPARATOR."1.jpg",
		Gc::$upload_path."watermark".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."1.jpg",Gc::$site_name);
		if ($result === false) {
			echo '<br>对图片进行水印处理时发生错误.';
		} else {
			if (!UtilWatermark::$is_delete_source_image)
				echo '<br>原图片保存为  : <a href="' .Gc::$upload_url. $result["origin_url"] . '" target="_blank">' . $result["origin_file_path"] . '</a>';
			echo '<br>水印图片保存为: <a href="' .Gc::$upload_url. $result["url"] . '" target="_blank">' . $result["file_path"] . '</a>';
		}
		break;
   case 1:
		/********************************* 添加图片水印 ******************************/
		if($is_watermark_file_upload){
			if (isset($_FILES["upload_file"])&&!empty($_FILES["upload_file"]))
			{
				$result = UtilWatermark::process_create_watermark($_FILES,'upload_file',"watermark.png");
				if ($result === false) {
					echo '<br>对图片进行水印处理时发生错误.';
				} else {
					if (!UtilWatermark::$is_delete_source_image)
						echo '<br>原图片保存为  : <a href="' .Gc::$upload_url. $result["origin_url"] . '" target="_blank">' . $result["origin_file_path"] . '</a>';
					echo '<br>水印图片保存为: <a href="' .Gc::$upload_url. $result["url"] . '" target="_blank">' . $result["file_path"] . '</a>';
				}
			}else{
				$html=<<<FORMCONTENT
				<form method="post" enctype="multipart/form-data">
					请选择需要添加水印的图片<br>
					<input type="file" name="upload_file"><br>
					<input type="submit" value="Submit File">
				</form>
FORMCONTENT;
				echo $html;
			}
		}else{
			$result = UtilWatermark::create_watermark(Gc::$upload_path."watermark".DIRECTORY_SEPARATOR."originals".DIRECTORY_SEPARATOR."1.jpg",
													  Gc::$upload_path."watermark".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."1.jpg",
													  "watermark.png");
			if ($result === false) {
				echo '<br>对图片进行水印处理时发生错误.';
			} else {
				if (!UtilWatermark::$is_delete_source_image)
					echo '<br>原图片保存为  : <a href="' .Gc::$upload_url. $result["origin_url"] . '" target="_blank">' . $result["origin_file_path"] . '</a>';
				echo '<br>水印图片保存为: <a href="' .Gc::$upload_url. $result["url"] . '" target="_blank">' . $result["file_path"] . '</a>';
			}

		}
	 	break;
   case 2:
		/********************************* 添加多行文字水印 ******************************/
		$result = UtilWatermark::createWordsWatermark(
			Gc::$upload_path."watermark".DIRECTORY_SEPARATOR."originals".DIRECTORY_SEPARATOR."1.jpg",
			Gc::$upload_path."watermark".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."1.jpg",
			'完美生活|betterlife',
			1,"255,0,0"
		);
		if ($result === false) {
			echo '<br>对图片进行水印处理时发生错误.';
		} else {
			if (!UtilWatermark::$is_delete_source_image)
				echo '<br>原图片保存为  : <a href="' .Gc::$upload_url. $result["origin_url"] . '" target="_blank">' . $result["origin_file_path"] . '</a>';
			echo '<br>水印图片保存为: <a href="' .Gc::$upload_url. $result["url"] . '" target="_blank">' . $result["file_path"] . '</a>';
		}
	 	break;
}
?>
