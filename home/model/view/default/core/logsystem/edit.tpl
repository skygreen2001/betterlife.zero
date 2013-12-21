{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <div class="block">
    <div><h1>编辑系统日志</h1></div>
    <form name="logsystemForm" method="post"><input type="hidden" name="logsystem_id" value="{$logsystem.logsystem_id}"/>
    <table class="viewdoblock">
        <tr class="entry"><td class="head">日志记录时间</th><td class="content"><input type="text" class="edit" name="logtime" value="{$logsystem.logtime}"/></td></tr>
        <tr class="entry"><td class="head">分类</th><td class="content"><input type="text" class="edit" name="ident" value="{$logsystem.ident}"/></td></tr>
        <tr class="entry"><td class="head">优先级</th><td class="content"><input type="text" class="edit" name="priority" value="{$logsystem.priority}"/></td></tr>
        <tr class="entry"><td class="head">日志内容</th><td class="content"><input type="text" class="edit" name="message" value="{$logsystem.message}"/></td></tr>
        <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
    </table>
    </form>
    <div align="center"><my:a href='{$url_base}index.php?go=model.logsystem.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.logsystem.view&id={$logsystem.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看系统日志</my:a></div>
</div>
{/block}