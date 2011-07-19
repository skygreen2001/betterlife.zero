<?php /* Smarty version Smarty-3.0.7, created on 2011-07-08 13:03:21
         compiled from "home\admin\view\default\core\system\filemanager.tpl" */ ?>
<?php /*%%SmartyHeaderCode:259004e17001912ad05-58578226%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7b8e3db92424ac5dd774bd1c5cc37a08a4bfd64b' => 
    array (
      0 => 'home\\admin\\view\\default\\core\\system\\filemanager.tpl',
      1 => 1309770158,
      2 => 'file',
    ),
    '0ef18b25f66ed820e129830ca6cabfddfdd9ec6f' => 
    array (
      0 => 'D:\\wamp\\www\\betterlife\\home\\admin\\view\\default\\/layout/normal/layout.tpl',
      1 => 1309307212,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '259004e17001912ad05-58578226',
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
    
<?php if (($_smarty_tpl->getVariable('module')->value=='source')){?>
源码文件管理
<?php }else{ ?>
<a href="<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
index.php?go=admin.system.filemanager&module=source">源码文件管理</a>
<?php }?>
|
<?php if (($_smarty_tpl->getVariable('module')->value=='image')){?>
图片文件上传
<?php }else{ ?>
<a href="<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
index.php?go=admin.system.filemanager&module=image">图片文件上传</a>
<?php }?>
|
<?php if (($_smarty_tpl->getVariable('module')->value=='files')){?>
文件管理
<?php }else{ ?>
<a href="<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
index.php?go=admin.system.filemanager&module=files">文件管理</a>
<?php }?>
<iframe src="<?php echo $_smarty_tpl->getVariable('redirect_module_url')->value;?>
" scrolling='auto' width='100%' height='700'  frameborder='0' />

  </body>
</html>