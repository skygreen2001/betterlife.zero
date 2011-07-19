<?php /* Smarty version Smarty-3.0.7, created on 2011-07-10 14:00:43
         compiled from "home\admin\view\default\core\system\librarymanager.tpl" */ ?>
<?php /*%%SmartyHeaderCode:72514e19b08b3b4748-64850932%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ed289a7c0a99ae171bd7fab131c4d61a0bc94361' => 
    array (
      0 => 'home\\admin\\view\\default\\core\\system\\librarymanager.tpl',
      1 => 1310306438,
      2 => 'file',
    ),
    '0ef18b25f66ed820e129830ca6cabfddfdd9ec6f' => 
    array (
      0 => 'D:\\wamp\\www\\betterlife\\home\\admin\\view\\default\\/layout/normal/layout.tpl',
      1 => 1309307212,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '72514e19b08b3b4748-64850932',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">           
  <head>
    <?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('templateDir')->value)."/layout/normal/header.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php echo (($tmp = @$_smarty_tpl->getVariable('viewObject')->value->css_ready)===null||$tmp==='' ? '' : $tmp);?>

<?php echo (($tmp = @$_smarty_tpl->getVariable('viewObject')->value->js_ready)===null||$tmp==='' ? '' : $tmp);?>

  </head>
  <body>
    
<div class="container" style="width:500px">
    <div id="resourceLibrary-grid"></div>

</div>

  </body>
</html>