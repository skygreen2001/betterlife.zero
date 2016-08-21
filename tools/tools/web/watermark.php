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

if(isset($_REQUEST["type"])){
    if(isset($_REQUEST["type"]))$type_create_watermark=$_REQUEST["type"];
    if(isset($_REQUEST["d"]))$direction=$_REQUEST["d"];
    if(isset($_REQUEST["f"]))$is_watermark_file_upload=$_REQUEST["f"];
    $default_img_filename="1.jpg";
    $source_file_path=Gc::$upload_path."watermark".DS."originals".DS.$default_img_filename;
    if($is_watermark_file_upload){
        if (isset($_FILES["upload_file"])&&!empty($_FILES["upload_file"])){
            $source_file_path     = UtilWatermark::upload_watermark_source_files($_FILES,'upload_file',"watermark.png",$direction);
            $default_img_filename = basename($source_file_path);
        }
    }

    switch ($type_create_watermark) {
       case 0:
            /********************************* 添加文字水印 *******************************/
            $result=UtilWatermark::watermark_text(
                $source_file_path,
                Gc::$upload_path."watermark".DS."images".DS.$default_img_filename,
                Gc::$site_name,$direction);
            break;
       case 1:
            /********************************* 添加图片水印 ******************************/
            $result = UtilWatermark::create_watermark(
                $source_file_path,
                Gc::$upload_path."watermark".DS."images".DS.$default_img_filename,
                "watermark.png",$direction);
             break;
       case 2:
            /********************************* 添加多行文字水印 ******************************/
            $result = UtilWatermark::createWordsWatermark(
                $source_file_path,
                Gc::$upload_path."watermark".DS."images".DS.$default_img_filename,
                '完美生活|betterlife',
                $direction,"255,0,0"
            );
             break;
    }
    if ($result === false) {
        echo '<br>对图片进行水印处理时发生错误.';
    } else {
        if (!UtilWatermark::$is_delete_source_image)
            echo '<br />原图片保存为  : <a href="' .Gc::$upload_url. $result["origin_url"] . '" target="_blank">' . $result["origin_file_path"] . '</a>';
        echo '<br />水印图片保存为: <a href="' .Gc::$upload_url. $result["url"] . '" target="_blank">' . $result["file_path"] . '</a>';
        echo "<br /><a href='".Gc::$url_base."tools/tools/web/watermark.php'>返回</a>";
    }
}else{
    $html=<<<FORMCONTENT
    <body><br/><br/><br/><br/><br/>
        <div align='center' height='450'>
        <form method="post" enctype="multipart/form-data">
            <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;生成水印的模式:</label><select name="type"><option value='0'>文字水印</option><option value='1'>图片水印</option><option value='2'>多行文字水印</option></select><br /><br />
            <label>&nbsp;&nbsp;&nbsp;生成水印的所在的方位:</label><select name="direction">
                <option value='1'>右下角[SouthEast]</option><option value='2'>正下角[South]</option><option value='3'>左下角[SouthWest]</option>
                <option value='4'>中右角[East]</option><option value='5'>中部[Center]</option><option value='6'>中左角[West]</option>
                <option value='7'>右上角[NorthEast]</option><option value='8'>正上角[North]</option><option value='9'>左上角[NorthWest]</option>
            </select><br /><br />
            <label>请选择需要添加水印的图片:&nbsp;</label><input type="file" name="upload_file"><br /><br />
            <input type="submit" value="确定">
        </form>
        </div>
    </body>
FORMCONTENT;
    echo UtilCss::form_css();
    echo $html;
}

?>
