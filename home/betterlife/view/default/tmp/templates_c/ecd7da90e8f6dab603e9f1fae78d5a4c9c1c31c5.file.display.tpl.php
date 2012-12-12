<?php /* Smarty version Smarty-3.0.7, created on 2012-12-12 13:54:22
         compiled from "/Users/pupu/www/betterlife/home/betterlife/view/default/core/blog/display.tpl" */ ?>
<?php /*%%SmartyHeaderCode:34497388550c81c0e844791-86937981%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ecd7da90e8f6dab603e9f1fae78d5a4c9c1c31c5' => 
    array (
      0 => '/Users/pupu/www/betterlife/home/betterlife/view/default/core/blog/display.tpl',
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
  'nocache_hash' => '34497388550c81c0e844791-86937981',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_block_php')) include '/Users/pupu/www/betterlife/library/template/Smarty/plugins/block.php.php';
if (!is_callable('smarty_modifier_date_format')) include '/Users/pupu/www/betterlife/library/template/Smarty/plugins/modifier.date_format.php';
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
	 
	<div class="contentBox">
		<b><my:a href="<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
index.php?go=betterlife.auth.logout">退出</my:a></b><br/><br/>
		<b>共计<?php echo $_smarty_tpl->getVariable('countBlogs')->value;?>
 篇博客</b>
		<?php if ($_smarty_tpl->getVariable('blogs')->value){?>
		<?php  $_smarty_tpl->tpl_vars['blog'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('blogs')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['blog']->key => $_smarty_tpl->tpl_vars['blog']->value){
?>         
		<div id='blog<?php echo $_smarty_tpl->tpl_vars['blog']->value['blog_id'];?>
' class="block"> 
			<b><my:a href='<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
index.php?go=betterlife.comment.comment&blog_id=<?php echo $_smarty_tpl->tpl_vars['blog']->value['blog_id'];?>
&pageNo=<?php echo (($tmp = @$_GET['pageNo'])===null||$tmp==='' ? "1" : $tmp);?>
'><?php echo $_smarty_tpl->tpl_vars['blog']->value['blog_name'];?>
</my:a>
			<?php if ($_smarty_tpl->tpl_vars['blog']->value['canEdit']){?>[<my:a href="<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
index.php?go=betterlife.blog.write&blog_id=<?php echo $_smarty_tpl->tpl_vars['blog']->value['blog_id'];?>
&pageNo=<?php echo (($tmp = @$_GET['pageNo'])===null||$tmp==='' ? "1" : $tmp);?>
">改</my:a>]<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['blog']->value['canDelete']){?>[<my:a href="<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
index.php?go=betterlife.blog.delete&blog_id=<?php echo $_smarty_tpl->tpl_vars['blog']->value['blog_id'];?>
&pageNo=<?php echo (($tmp = @$_GET['pageNo'])===null||$tmp==='' ? "1" : $tmp);?>
">删</my:a>]<?php }?>
			</b><br/>
			<?php echo nl2br($_smarty_tpl->tpl_vars['blog']->value['content']);?>
<br/><br/>
			由 <?php echo $_smarty_tpl->tpl_vars['blog']->value['user']['username'];?>
 在 <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['blog']->value['commitTime'],'%Y-%m-%d %H:%M');?>
 发表<br/>
			评论数:<?php echo $_smarty_tpl->getVariable('viewObject')->value->count_comments($_smarty_tpl->tpl_vars['blog']->value['blog_id']);?>
<br/>
		</div>
		<?php }} ?><br/>       
		<my:page src='<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
index.php?go=betterlife.blog.display' /><br/>
		<b><my:a href='<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
index.php?go=betterlife.blog.write&pageNo=<?php echo (($tmp = @$_GET['pageNo'])===null||$tmp==='' ? "1" : $tmp);?>
'>新建博客</my:a></b><br/>
		<?php }else{ ?>              
		无博客，您是第一位!
		<?php }?> 
	</div>

  </body>
</html>