<?php
    require_once("../../init.php");
    $urlbase=UtilNet::urlbase();
    if(isset($_POST)&&isset($_POST["code"])){
        $code=$_POST["code"];
        $file=$_GET["f"];
        file_put_contents($file,$code);
    }
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>编辑源代码</title>   
    <script language="Javascript" type="text/javascript" src="<?php echo $urlbase; ?>/common/js/onlineditor/edit_area/edit_area_full.js"></script>
    <script language="Javascript" type="text/javascript">
        // initialisation
        editAreaLoader.init({
            id: "code"    // id of the textarea to transform        
            ,start_highlight: true    // if start with highlight
            ,allow_resize: "both"
            ,allow_toggle: true
            ,word_wrap: true
            ,language: "en"
            ,syntax: "php"    
        });
    </script>

<?php  echo UtilCss::form_css()."\r\n"; ?>
</head>
<body>
    <div align="center">
<?php  
  $edit_filename=$_GET["f"];
  $content=file_get_contents($edit_filename);
  echo '<a href="viewfiles.php">返回</a><br/>'; 
?>            
        <form method="post">                               
            <textarea id="code" style="height: 350px; width: 100%;" name="code"><?php echo $content; ?></textarea>  
            <input type="hidden" name="f" value="<?php echo $edit_filename ?>" />
            <input type="submit" align="middle" value="确定" /> 
        </form>
    </div>
</body>
