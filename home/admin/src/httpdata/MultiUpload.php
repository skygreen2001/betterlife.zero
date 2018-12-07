<?php
    /**
    +---------------------------------------<br/>
    * 获取临时上传文件/商品图片
    +---------------------------------------
    * @category kmall
    * @package admin.src.httpdata
    * @author fxf 924197212@qq.com
    */
    require_once ("../../../../init.php");
// Code for Session Cookie workaround
    if (isset($_POST["PHPSESSID"])) {
        session_id($_POST["PHPSESSID"]);
    } else if (isset($_GET["PHPSESSID"])) {
        session_id($_GET["PHPSESSID"]);
    }

    session_start();

    $POST_MAX_SIZE = ini_get('post_max_size');
    $unit = strtoupper(substr($POST_MAX_SIZE, -1));
    $multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

    if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
        header("HTTP/1.1 500 Internal Server Error");
        echo "POST exceeded maximum allowed size.";
        exit(0);
    }

// Settings
    $save_path = Gc::$upload_path."tempimages".DIRECTORY_SEPARATOR;
    if (!is_dir($save_path)) {
        mkdir($save_path, 0777, true);
    }
    $upload_name = "Filedata";
    $max_file_size_in_bytes = 2147483647;                // 2GB in bytes
    $extension_whitelist = array("doc", "txt", "jpg", "jpeg",  "gif", "png");
    $valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';

// Other variables
    $MAX_FILENAME_LENGTH = 260;
    $file_name = "";
    $file_extension = "";
    $uploadErrors = array(
        0=>"There is no error, the file uploaded with success",
        1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
        2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
        3=>"The uploaded file was only partially uploaded",
        4=>"No file was uploaded",
        6=>"Missing a temporary folder"
    );

    if (!isset($_FILES[$upload_name])) {
        HandleError("No upload found in \$_FILES for " . $upload_name);
        exit(0);
    } else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
        HandleError($uploadErrors[$_FILES[$upload_name]["error"]]);
        exit(0);
    } else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
        HandleError("Upload failed is_uploaded_file test.");
        exit(0);
    } else if (!isset($_FILES[$upload_name]['name'])) {
        HandleError("File has no name.");
        exit(0);
    }

    $file_size = $_FILES[$upload_name]["size"];

    if (!$file_size || $file_size > $max_file_size_in_bytes) {
        HandleError("File exceeds the maximum allowed size");
        exit(0);
    }

    if ($file_size <= 0) {
        HandleError("File size outside allowed lower bound");
        exit(0);
    }

    //文件后缀
    $suffix = end(explode('.',basename($_FILES[$upload_name]['name'])));
    //随机生成文件名
    $diffpart = date("YmdHis").UtilNumber::randNumber(1000,9999);
    //文件名(带后缀)
    $file_name =  $diffpart.".".$suffix;
    //$file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES[$upload_name]['name']));
    if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
        HandleError("Invalid file name");
        exit(0);
    }

    if (file_exists($save_path . $file_name)) {
        HandleError("File with this name already exists");
        exit(0);
    }

// Validate file extension
    $path_info = pathinfo($_FILES[$upload_name]['name']);
    $file_extension = $path_info["extension"];
    $is_valid_extension = false;
    foreach ($extension_whitelist as $extension) {
        if (strcasecmp($file_extension, $extension) == 0) {
            $is_valid_extension = true;

            break;
        }
    }
    if (!$is_valid_extension) {
        HandleError("Invalid file extension");
        exit(0);
    }

    if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$file_name)) {
        HandleError("File could not be saved.");
        exit(0);
    }else{

    }

    echo $file_name;
    exit(0);

function HandleError($message) {
    header("HTTP/1.1 500 Internal Server Error");
    echo $message;
}

?>
