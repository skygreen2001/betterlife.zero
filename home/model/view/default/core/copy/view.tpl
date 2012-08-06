{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>查看系统管理人员</h1></div>     
	<table class="viewdoblock">   
        <tr class="entry"><td class="head"></th><td class="content">{$copy.admin_id}</td></tr> 
        <tr class="entry"><td class="head">用户名</th><td class="content">{$copy.username}</td></tr> 
        <tr class="entry"><td class="head">真实姓名</th><td class="content">{$copy.realname}</td></tr> 
        <tr class="entry"><td class="head">密码</th><td class="content">{$copy.password}</td></tr> 
        <tr class="entry"><td class="head">扮演角色</th><td class="content">{$copy.roletype}</td></tr> 
        <tr class="entry"><td class="head">角色标识</th><td class="content">{$copy.roleid}</td></tr> 
        <tr class="entry"><td class="head">视野</th><td class="content">{$copy.seescope}</td></tr>          
	</table>                                                            
	<div align="center"><my:a href='index.php?go=model.copy.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='index.php?go=model.copy.edit&id={$copy.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改系统管理人员</my:a></div>    
</div>
{/block}