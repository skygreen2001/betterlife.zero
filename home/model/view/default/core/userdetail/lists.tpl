{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>用户详细信息列表(共计{$countUserdetails}个)</h1></div>     
	<table class="viewdoblock">
		<tr class="entry">
            <th class="header">用户编号</th>
            <th class="header">邮件地址</th>
            <th class="header">手机号码</th>                                  
			<th class="header">操作</th>
		</tr>       
		{foreach item=userdetail from=$userdetails}     
		<tr class="entry">                            
            <td class="content">{$userdetail.userId}</td>
            <td class="content">{$userdetail.email}</td>
            <td class="content">{$userdetail.cellphone}</td>
			<td class="btnCol"><my:a href="{$url_base}index.php?go=model.userdetail.view&id={$userdetail.id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.userdetail.edit&id={$userdetail.id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.userdetail.delete&id={$userdetail.id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
		</tr> 
		{/foreach}                                                           
	</table> 
	&nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.userdetail.lists' /><br/>   
	<div align="center"><my:a href='{$url_base}index.php?go=model.userdetail.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>    
</div>
{/block}