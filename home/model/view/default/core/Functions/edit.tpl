{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">  
	<div><h1>编辑功能信息</h1></div>
	<form name="FunctionsForm" method="post"><input type="hidden" name="id" value="{$Functions.id}"/>           
	<table class="viewdoblock">                                                                                                                 
        <tr class="entry"><td class="head">允许访问的URL权限</th><td class="content"><input type="text" class="edit" name="url" value="{$Functions.url}"/></td></tr>       
		<tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
	</table>
	</form>                                                            
	<div align="center"><my:a href='{$url_base}index.php?go=model.Functions.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.Functions.view&id={$Functions.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看功能信息</my:a></div>    
</div>
{/block}