{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">  
	<div><h1>查看地区</h1></div>     
	<table class="viewdoblock">   
        <tr class="entry"><td class="head">标识</th><td class="content">{$region.region_id}</td></tr> 
        <tr class="entry"><td class="head">父地区标识</th><td class="content">{$region.parent_id}</td></tr> 
        <tr class="entry"><td class="head">地区名称</th><td class="content">{$region.region_name}</td></tr> 
        <tr class="entry"><td class="head">地区类型</th><td class="content">{$region.region_type}</td></tr>          
	</table>                                                            
	<div align="center"><my:a href='index.php?go=model.region.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='index.php?go=model.region.edit&id={$region.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改地区</my:a></div>    
</div>
{/block}