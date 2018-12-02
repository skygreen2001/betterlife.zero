<?php
$url_base = UtilNet::urlbase();
$form_css = UtilCss::form_css();
$default_dir=Gc::$nav_root_path."model".DS;
$show_select_lists = "";
if (!empty($inputArr)){
    foreach ($inputArr as $key => $value) {
        $selectd="";
        if ($default_value==$key) $selectd = 'selected="selected"';
        $show_select_lists .= "      <option value=\"$key\" $selectd>$value</option>";
    }
$show_select_lists_model = <<<SHOWTABLELIST
      <br>
      <label class="mode">生成模式</label>
      <select name="type">
      $show_select_lists
      </select>
SHOWTABLELIST;

    $show_select_lists = $show_select_lists_model;
}

$userinput_model = <<<USERINPUT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link rel="icon" href="{$url_base}favicon.ico" mce_href="favicon.ico" type="image/x-icon">
        $form_css
    </head>
    <body>
        <h1 align="center">$title</h1>
        <div class="container" align="center">
        <form id="autocodeForm" target="_blank">
            <div>
                <label>输出文件路径</label>
                <input class="input_save_dir" type="text" name="save_dir" value="$default_dir" id="save_dir" />
                $show_select_lists
            </div>
            <input class="btnSubmit" type="submit" value="生成" /><br/><br/><br/>
            $more_content
        </form>
        </div>
    </body>
</html>
USERINPUT;

?>
