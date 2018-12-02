<?php
$title = "一键生成指定表前后台所有模板";
$url_base = UtilNet::urlbase();
$form_css = UtilCss::form_css();
$show_table_lists = "";
if (!empty($inputArr)){
    foreach ($inputArr as $key => $value) {
        $show_table_lists .= "        <option value=\"$key\">$value</option>\r\n";
    }

$show_table_lists_model = <<<SHOWTABLELIST
      <br>
      <label>选择需要生成的表</label>
      <select multiple="multiple" size="8" style="height:320px;width:415px;" name="table_names[]">
      $show_table_lists
      </select>
SHOWTABLELIST;

    $show_table_lists = $show_table_lists_model;
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
        <form>
            <div>
                <label>输出文件路径</label>
                <input class="input_save_dir" id="save_dir" type="text" name="save_dir" value="$default_dir" />
                $show_table_lists
            </div>
            <input class="btnSubmit" type="submit" value="生成" /><br/><br/><br/>
        </form>
        </div>
    </body>
</html>
USERINPUT;

?>
