{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>功能信息列表(共计{$countFunctionss}个)</h1></div>     
	<table class="viewdoblock">
		<tr class="entry">
            <th class="header">允许访问的URL权限</th>                                  
			<th class="header">操作</th>
		</tr>       
		{foreach item=Functions from=$Functionss}     
		<tr class="entry">                            
            <td class="content">{$Functions.url}</td>
			<td class="btnCol"><my:a href="{$url_base}index.php?go=model.Functions.view&id={$Functions.id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.Functions.edit&id={$Functions.id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.Functions.delete&id={$Functions.id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
		</tr> 
		{/foreach}                                                           
	</table> 
	&nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.Functions.lists' /><br/>   
	<div align="center"><my:a href='{$url_base}index.php?go=model.Functions.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>    
</div>
{/block}