{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>查看用户详细信息</h1></div>     
	<table class="viewdoblock">   
        <tr class="entry"><td class="head">标识</th><td class="content">{$userdetail.userdetail_id}</td></tr> 
        <tr class="entry"><td class="head">用户标识</th><td class="content">{$userdetail.user_id}</td></tr> 
        <tr class="entry"><td class="head">邮件地址</th><td class="content">{$userdetail.email}</td></tr> 
        <tr class="entry"><td class="head">手机号码</th><td class="content">{$userdetail.cellphone}</td></tr>          
	</table>                                                            
	<div align="center"><my:a href='index.php?go=model.userdetail.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='index.php?go=model.userdetail.edit&id={$userdetail.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改用户详细信息</my:a></div>    
</div>
{/block}