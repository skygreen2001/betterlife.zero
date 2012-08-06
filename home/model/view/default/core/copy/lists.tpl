{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>系统管理人员列表(共计{$countCopys}个)</h1></div>     
	<table class="viewdoblock">
		<tr class="entry">
            <th class="header"></th>
            <th class="header">用户名</th>
            <th class="header">真实姓名</th>
            <th class="header">密码</th>
            <th class="header">扮演角色</th>
            <th class="header">角色标识</th>
            <th class="header">视野</th>                                  
			<th class="header">操作</th>
		</tr>       
		{foreach item=copy from=$copys}     
		<tr class="entry">                            
            <td class="content">{$copy.admin_id}</td>
            <td class="content">{$copy.username}</td>
            <td class="content">{$copy.realname}</td>
            <td class="content">{$copy.password}</td>
            <td class="content">{$copy.roletype}</td>
            <td class="content">{$copy.roleid}</td>
            <td class="content">{$copy.seescope}</td>
			<td class="btnCol"><my:a href="{$url_base}index.php?go=model.copy.view&id={$copy.id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.copy.edit&id={$copy.id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.copy.delete&id={$copy.id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
		</tr> 
		{/foreach}                                                           
	</table> 
	&nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.copy.lists' /><br/>   
	<div align="center"><my:a href='{$url_base}index.php?go=model.copy.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>    
</div>
{/block}