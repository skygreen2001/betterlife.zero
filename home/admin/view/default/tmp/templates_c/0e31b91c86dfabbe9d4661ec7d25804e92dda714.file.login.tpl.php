<?php /* Smarty version Smarty-3.0.7, created on 2012-12-12 10:53:20
         compiled from "/Users/pupu/www/betterlife/home/admin/view/default/core/index/login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:173858304650c7f1a036ae54-41748432%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0e31b91c86dfabbe9d4661ec7d25804e92dda714' => 
    array (
      0 => '/Users/pupu/www/betterlife/home/admin/view/default/core/index/login.tpl',
      1 => 1355057761,
      2 => 'file',
    ),
    '0946053418d233b633bd6770cedb943e6c0a21d8' => 
    array (
      0 => '/Users/pupu/www/betterlife/home/admin/view/default//layout/normal/layout.tpl',
      1 => 1355057761,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '173858304650c7f1a036ae54-41748432',
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
<?php echo (($tmp = @$_smarty_tpl->getVariable('viewObject')->value->css_ready)===null||$tmp==='' ? '' : $tmp);?>

<?php echo (($tmp = @$_smarty_tpl->getVariable('viewObject')->value->js_ready)===null||$tmp==='' ? '' : $tmp);?>

  </head>
  <?php $_smarty_tpl->smarty->_tag_stack[] = array('php', array()); $_block_repeat=true; smarty_block_php(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

   flush();
  <?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_php(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
  
  <body>
  
	<form method="POST">
	<table class="content">
		<tr class="left">
			<td class="leftContent" align="center"><img src="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
resources/images/logo.png" class="logoLeft" alt="<?php echo $_smarty_tpl->getVariable('site_name')->value;?>
后台管理" /></td>
			<td class="right">
				<table class="right">
					<tr align="center"><td class="title" align="center"><b><?php echo $_smarty_tpl->getVariable('site_name')->value;?>
后台管理</b></td></tr>
					<tr align="center"><td><font color="#ff0000"><?php echo (($tmp = @$_smarty_tpl->getVariable('message')->value)===null||$tmp==='' ? '' : $tmp);?>
</font></td></tr>
					<tr align="center"><td><label>用户名&nbsp;&nbsp;&nbsp;</label><input class="inputNormal" type="text" name="username" /><br/></td></tr>
					<tr align="center"><td><label>密&nbsp;&nbsp;码&nbsp;&nbsp;&nbsp;</label><input class="inputNormal" type="password" name="password" /><br/></td></tr>
					<tr align="center"><td><label>图形验证码</label><input class="inputVerify" name="validate" id="validate" size="15" type="text" /><img src="<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
home/admin/src/httpdata/validate.php" name="validateCode" id="validateCode" onclick="changeCode();" style="cursor: pointer;vertical-align:top;"/></td></tr>
					<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="cursor: pointer;" onclick="changeCode();">看不清楚？换张图片</a></td></tr>
					<tr><td align="center"><input type="submit" name="Submit" value="登录" class="btnSubmit" /></td></tr>
				</table>
			</td>
		</tr>
	</table>
	</form>
	<div align="center">[测试账号]管理员名:admin,密码:admin。</div>
	<script>
		function changeCode(){
			document.getElementById('validateCode').src="<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
home/admin/src/httpdata/validate.php?"+Math.random();
		}
	</script>    

  </body>
</html>