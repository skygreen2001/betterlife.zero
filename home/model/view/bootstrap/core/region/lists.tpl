{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>地区列表(共计{$countRegions}个)</h1></div>
    <form name="regionForm" method="POST">
    <ul class="nav nav-pills">
        <li><div class="form-group"><label for="region_name" class="col-lg-2 control-label" style="text-align:left;width:auto;">地区名称</label><input type="text" class="form-control" name="region_name" id="region_name" placeholder="中国" value="{$region->region_name}" style="width:auto;"></div>
        </li>
        <li><label for="region_type" class="col-lg-2 control-label" style="text-align:left;width:auto;">地区类型</label>
        <select class="form-control" name="region_type" id="region_type" style="width:auto;">
            <option value="-1">全部</option>
            <option value="1" {if $region_type==1} selected = "selected"{/if}>国家</option>
            <option value="2" {if $region_type==2} selected = "selected"{/if}>省</option>
            <option value="3" {if $region_type==3} selected = "selected"{/if}>市</option>
            <option value="4" {if $region_type==4} selected = "selected"{/if}>区</option>
        </select></li>
      <li><button type="submit" class="btn btn-primary">&nbsp;&nbsp;&nbsp;&nbsp;查询&nbsp;&nbsp;&nbsp;&nbsp;</button></li>
    </ul>
    </form>
    <table class="viewdoblock">
        <tr class="entry">
            <th class="header">标识</th>
            <th class="header">父地区</th>
            <th class="header">父地区[全]</th>
            <th class="header">地区名称</th>
            <th class="header">地区类型</th>
            <th class="header">目录层级</th>
            <th class="header">操作</th>
        </tr>
        {foreach item=region from=$regions}
        <tr class="entry">
            <td class="content">{$region.region_id}</td>
            <td class="content">{$region.region_name_parent}</td>
            <td class="content">{$region.regionShowAll}</td>
            <td class="content">{$region.region_name}</td>
            <td class="content">{$region.region_typeShow}</td>
            <td class="content">{$region.level}</td>
            <td class="btnCol"><my:a href="{$url_base}index.php?go=model.region.view&id={$region.region_id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.region.edit&id={$region.region_id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.region.delete&id={$region.region_id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
        </tr>
        {/foreach}
    </table>
    &nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.region.lists&region_type={$region_type}' /><br/>
    <div align="center"><my:a href='{$url_base}index.php?go=model.region.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>
</div>
{/block}