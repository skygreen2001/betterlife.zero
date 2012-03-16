{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>查看功能信息</h1></div>     
	<table class="viewdoblock">   
        <tr class="entry"><td class="head">允许访问的URL权限</th><td class="content">{$Functions.url}</td></tr>          
	</table>                                                            
	<div align="center"><my:a href='index.php?go=model.Functions.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='index.php?go=model.Functions.edit&id={$Functions.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改功能信息</my:a></div>    
</div>
{/block}