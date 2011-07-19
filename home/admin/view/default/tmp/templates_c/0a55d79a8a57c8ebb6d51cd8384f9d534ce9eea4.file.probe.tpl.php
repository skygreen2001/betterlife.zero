<?php /* Smarty version Smarty-3.0.7, created on 2011-07-08 13:03:17
         compiled from "home\admin\view\default\core\system\probe.tpl" */ ?>
<?php /*%%SmartyHeaderCode:313644e1700152e4bd6-08746435%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0a55d79a8a57c8ebb6d51cd8384f9d534ce9eea4' => 
    array (
      0 => 'home\\admin\\view\\default\\core\\system\\probe.tpl',
      1 => 1309827244,
      2 => 'file',
    ),
    '0ef18b25f66ed820e129830ca6cabfddfdd9ec6f' => 
    array (
      0 => 'D:\\wamp\\www\\betterlife\\home\\admin\\view\\default\\/layout/normal/layout.tpl',
      1 => 1309307212,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '313644e1700152e4bd6-08746435',
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
    

<?php if (($_smarty_tpl->getVariable('module')->value=='bCheck')){?>
B-Check
<?php }else{ ?>
<a href="<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
index.php?go=admin.system.probe&module=source">B-Check</a>
<?php }?>
|
<?php if (($_smarty_tpl->getVariable('module')->value=='probe')){?>
iProber
<?php }else{ ?>
<a href="<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
index.php?go=admin.system.probe&module=probe">iProber</a>
<?php }?>
|
<?php if (($_smarty_tpl->getVariable('module')->value=='probe1')){?>
iProber 1
<?php }else{ ?>
<a href="<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
index.php?go=admin.system.probe&module=probe1">iProber 1</a>
<?php }?>
|
<?php if (($_smarty_tpl->getVariable('module')->value=='probe2')){?>
iProber 2
<?php }else{ ?>
<a href="<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
index.php?go=admin.system.probe&module=probe2">iProber 2</a>
<?php }?>
<iframe src="<?php echo $_smarty_tpl->getVariable('redirect_module_url')->value;?>
" scrolling='auto' width='100%' height='700'  frameborder='0' />

  </body>
</html>