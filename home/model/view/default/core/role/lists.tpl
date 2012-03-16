{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>角色列表(共计{$countRoles}个)</h1></div>     
	<table class="viewdoblock">
		<tr class="entry">
            <th class="header">角色名称</th>                                  
			<th class="header">操作</th>
		</tr>       
		{foreach item=role from=$roles}     
		<tr class="entry">                            
            <td class="content">{$role.name}</td>
			<td class="btnCol"><my:a href="{$url_base}index.php?go=model.role.view&id={$role.id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.role.edit&id={$role.id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.role.delete&id={$role.id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
		</tr> 
		{/foreach}                                                           
	</table> 
	&nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.role.lists' /><br/>   
	<div align="center"><my:a href='{$url_base}index.php?go=model.role.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>    
</div>
{/block}