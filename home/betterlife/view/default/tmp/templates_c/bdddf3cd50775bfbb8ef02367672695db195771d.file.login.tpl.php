<?php /* Smarty version Smarty-3.0.7, created on 2012-12-12 10:47:00
         compiled from "/Users/pupu/www/betterlife/home/betterlife/view/default/core/auth/login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:163269004350c7f024b47c71-39250859%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bdddf3cd50775bfbb8ef02367672695db195771d' => 
    array (
      0 => '/Users/pupu/www/betterlife/home/betterlife/view/default/core/auth/login.tpl',
      1 => 1355057761,
      2 => 'file',
    ),
    '72bb315de82dd0cba578be355d1d62a99aaa7467' => 
    array (
      0 => '/Users/pupu/www/betterlife/home/betterlife/view/default//layout/normal/layout.tpl',
      1 => 1355057761,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '163269004350c7f024b47c71-39250859',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_block_php')) include '/Users/pupu/www/betterlife/library/template/Smarty/plugins/block.php.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">           
  <head>
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('templateDir')->value)."/layout/normal/header.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>  
	<link rel="icon" href="favicon.ico" mce_href="favicon.ico" type="image/x-icon" />      
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
resources/css/public.css" />
	<link rel="icon" href="favicon.ico" mce_href="favicon.ico" type="image/x-icon" />          
	<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
common/js/ajax/jquery/jquery-1.7.1.js"></script>    
	<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
js/public.js"></script>         
<?php echo (($tmp = @$_smarty_tpl->getVariable('viewObject')->value->css_ready)===null||$tmp==='' ? '' : $tmp);?>

<?php echo (($tmp = @$_smarty_tpl->getVariable('viewObject')->value->js_ready)===null||$tmp==='' ? '' : $tmp);?>

  </head>
  <?php $_smarty_tpl->smarty->_tag_stack[] = array('php', array()); $_block_repeat=true; smarty_block_php(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	 flush();
  <?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_php(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

  <body>
	
	<div class="contentBox" align="center">
		<form method="POST">
		<h1>请登录</h1>
		<font color="red"><?php echo $_smarty_tpl->getVariable('message')->value;?>
</font>
		<div>           
		   <label>用户名</label><br/><input class="inputNormal" type="text" name="username" style="width:260px;" /><br/>
		   <label>密码</label><br/><input class="inputNormal" type="password" name="password" /><br/>
		</div>
		<input type="submit" name="Submit" value="登录" class="btnSubmit" />
		</form>
		<my:a href="<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
index.php?go=betterlife.auth.register">注册</my:a>
	</div>
	<div align="center">[测试帐户]用户名:admin,密码:admin<br/>[测试帐户]用户名:china,密码:iloveu</center>

  </body>
</html>