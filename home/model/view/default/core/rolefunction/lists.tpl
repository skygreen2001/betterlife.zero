{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>角色拥有功能列表(共计{$countRolefunctions}个)</h1></div>     
	<table class="viewdoblock">
		<tr class="entry">
            <th class="header">角色编号</th>
            <th class="header">功能编号</th>                                  
			<th class="header">操作</th>
		</tr>       
		{foreach item=rolefunction from=$rolefunctions}     
		<tr class="entry">                            
            <td class="content">{$rolefunction.roleId}</td>
            <td class="content">{$rolefunction.functionId}</td>
			<td class="btnCol"><my:a href="{$url_base}index.php?go=model.rolefunction.view&id={$rolefunction.id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.rolefunction.edit&id={$rolefunction.id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.rolefunction.delete&id={$rolefunction.id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
		</tr> 
		{/foreach}                                                           
	</table> 
	&nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.rolefunction.lists' /><br/>   
	<div align="center"><my:a href='{$url_base}index.php?go=model.rolefunction.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>    
</div>
{/block}