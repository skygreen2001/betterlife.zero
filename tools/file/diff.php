<?php
require_once("../../init.php");
require_once("Text/Text_Diff.php");
require_once("Text/Diff/Text_Diff_Renderer.php");
require_once("Text/Diff/Renderer/Text_Diff_Renderer_inline.php");

$old_file=$_GET["old_file"];
if(file_exists($old_file)){
    $old_content=file_get_contents($old_file);
}else{
    //die("原文件不存在！");
}
$new_file=$_GET["new_file"];
if(file_exists($new_file)){
    $new_content=file_get_contents($new_file);
}else{
    die("新文件不存在！");
}

if(is_string($old_content))$old_content=explode("\n",$old_content);
if(is_string($new_content))$new_content=explode("\n",$new_content);
$diff = new Text_Diff('auto', array($old_content,$new_content));
$renderer = new Text_Diff_Renderer_inline();
$contents=$renderer->render($diff);
if(empty($contents))$contents= htmlspecialchars(file_get_contents($new_file));
$show=<<<COF
    <style type="text/css">
        del {
            background: none repeat scroll 0 0 pink;
        }
        ins {
            background: none repeat scroll 0 0 lightgreen;
            text-decoration: none;
        }
    </style>
    <div class="content">
        <pre class="diff">$contents</pre>
    </div>
COF;
echo $show;
?>
