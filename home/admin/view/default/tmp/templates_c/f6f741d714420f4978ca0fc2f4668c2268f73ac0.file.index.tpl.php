<?php /* Smarty version Smarty-3.0.7, created on 2011-07-06 06:02:11
         compiled from "home\admin\view\default\core\index\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:158064e13fa63c6c539-18174073%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f6f741d714420f4978ca0fc2f4668c2268f73ac0' => 
    array (
      0 => 'home\\admin\\view\\default\\core\\index\\index.tpl',
      1 => 1309831368,
      2 => 'file',
    ),
    'd7f8c928066259ceda6c51dee208d1ef79d7114e' => 
    array (
      0 => 'C:\\wamp\\www\\betterlife\\home\\admin\\view\\default\\/layout/normal/layout.tpl',
      1 => 1309307211,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '158064e13fa63c6c539-18174073',
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
    
    <div id="loading-mask"></div>
    <div id="loading">
        <div class="loading-indicator"><img src="<?php echo $_smarty_tpl->getVariable('url_base')->value;?>
common/js/ajax/ext/resources/images/extanim32.gif" width="32" height="32" style="margin-right:8px;" align="absmiddle"/>正在加载中...</div>
    </div>
    <div id="header" class='x-hide-display'>
        <table>
          <tr>
            <td align="center" colspan="2"><img style="margin-left: 5px" src="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
resources/images/logo.png"></td>
          </tr>  
          <tr>
            <td><div id="toolbar"></div></td>
            <td><span style="float:right; margin-top: 15px;margin-right: 10px;color: #CCC"><a href="http://www.itfollow.com/zp/case.asp?leixing=1" target="_blank" style="padding:5px">成功案例</a>|<a href="http://www.itfollow.com/" style="padding:5px" target="_blank">ITFollow.com</a></span></td>
          </tr>
        </table>
    </div>
    <?php  $_smarty_tpl->tpl_vars['menuGroup'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('menuGroups')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['menuGroup']->key => $_smarty_tpl->tpl_vars['menuGroup']->value){
?> 
        <div id='<?php echo $_smarty_tpl->tpl_vars['menuGroup']->value['id'];?>
' class="x-hide-display">
            <?php if (($_smarty_tpl->tpl_vars['menuGroup']->value['lang']!='')){?><div class="<?php echo $_smarty_tpl->tpl_vars['menuGroup']->value['lang'];?>
"><?php }?>
                <?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['menuGroup']->value['menus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['menu']->key => $_smarty_tpl->tpl_vars['menu']->value){
?> 
                    <p><a href="<?php echo $_smarty_tpl->tpl_vars['menu']->value['address'];?>
" title="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['menu']->value['title'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['menu']->value['name'] : $tmp);?>
" <?php if (($_smarty_tpl->tpl_vars['menu']->value['lang']!='')){?>class="menuIcon <?php echo $_smarty_tpl->tpl_vars['menu']->value['lang'];?>
"<?php }else{ ?>class="menuIcon"<?php }?>><?php echo $_smarty_tpl->tpl_vars['menu']->value['name'];?>
</a></p>
                <?php }} ?>
            <?php if (($_smarty_tpl->tpl_vars['menuGroup']->value['lang']!='')){?></div><?php }?>
        </div>
    <?php }} ?>
    <div id="centerArea" class="x-hide-display">
        <br/><br/><br/><br/><br/><br/><br/><br/><br/>
        <div align="center">        
            <h1>欢迎来到<span class="en-head">BetterLife CMS</span>后台管理中心</h1><br/><br/>
            <h2 id="content-head">推荐开发工具:</h2>
            <p id="indexPage">
               部署：<span class="en">Wamp(windows+apache+mysql+php)</span><br/>
               开发：<span class="en">NetBeans + xDebug</span>|<span class="en">PhpEd + Dbg Debugger</span><br/>
               模板：<span class="en">Flexy|Smarty.</span>
            </p>
        </div>
        <br/><br/><br/><br/><br/><br/><br/><br/><br/>
    </div>
    <div id="south" class="x-hide-display">
        <p>这是状态栏位</p>
    </div>

  </body>
</html>