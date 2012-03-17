{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>用户列表(共计{$countUsers}个)</h1></div>     
	<table class="viewdoblock">
		<tr class="entry">
            <th class="header">标识</th>
            <th class="header">部门标识</th>
            <th class="header">用户名</th>
            <th class="header">用户密码</th>                                  
			<th class="header">操作</th>
		</tr>       
		{foreach item=user from=$users}     
		<tr class="entry">                            
            <td class="content">{$user.user_id}</td>
            <td class="content">{$user.department_id}</td>
            <td class="content">{$user.username}</td>
            <td class="content">{$user.password}</td>
			<td class="btnCol"><my:a href="{$url_base}index.php?go=model.user.view&id={$user.id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.user.edit&id={$user.id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.user.delete&id={$user.id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
		</tr> 
		{/foreach}                                                           
	</table> 
	&nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.user.lists' /><br/>   
	<div align="center"><my:a href='{$url_base}index.php?go=model.user.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>    
</div>
{/block}