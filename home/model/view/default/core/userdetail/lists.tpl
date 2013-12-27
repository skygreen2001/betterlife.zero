{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>用户详细信息列表(共计{$countUserdetails}个)</h1></div>
    <table class="viewdoblock">
        <tr class="entry">
            <th class="header">标识</th>
            <th class="header">用户标识</th>
            <th class="header">真实姓名</th>
            <th class="header">地区标识</th>
            <th class="header">头像</th>
            <th class="header">家庭住址</th>
            <th class="header">QQ号</th>
            <th class="header">会员性别</th>
            <th class="header">生日</th>
            <th class="header">操作</th>
        </tr>
        {foreach item=userdetail from=$userdetails}
        <tr class="entry">
            <td class="content">{$userdetail.userdetail_id}</td>
            <td class="content">{$userdetail.user_id}</td>
            <td class="content">{$userdetail.realname}</td>
            <td class="content">{$userdetail.region_id}</td>
            <td class="content">{$userdetail.profile}</td>
            <td class="content">{$userdetail.address}</td>
            <td class="content">{$userdetail.qq}</td>
            <td class="content">{$userdetail.sex}</td>
            <td class="content">{$userdetail.birthday}</td>
            <td class="btnCol"><my:a href="{$url_base}index.php?go=model.userdetail.view&id={$userdetail.id}&pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.userdetail.edit&id={$userdetail.id}&pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.userdetail.delete&id={$userdetail.id}&pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
        </tr>
        {/foreach}
    </table>
    &nbsp;&nbsp;<my:page src='{$url_base}index.php?go=model.userdetail.lists' /><br/>
    <div align="center"><my:a href='{$url_base}index.php?go=model.userdetail.edit&pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a></div>
</div>
{/block}