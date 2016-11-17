{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
     <div class="block">
        <div><h1>{if $userrole}编辑{else}新增{/if}用户角色</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="userroleForm" method="post"><input type="hidden" name="userrole_id" value="{$userrole.userrole_id}"/>
        <table class="viewdoblock">
        {if $userrole}
        <tr class="entry"><th class="head">标识</th><td class="content">{$userrole.userrole_id}</td></tr>
        {/if}
        <tr class="entry"><th class="head">用户标识</th><td class="content"><input type="text" class="edit" name="user_id" value="{$userrole.user_id}"/></td></tr>
        <tr class="entry"><th class="head">角色标识</th><td class="content"><input type="text" class="edit" name="role_id" value="{$userrole.role_id}"/></td></tr>
            <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.userrole.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $userrole}
            |<my:a href='{$url_base}index.php?go=model.userrole.view&amp;id={$userrole.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看用户角色</my:a>
            {/if}
        </div>
    </div>

{/block}