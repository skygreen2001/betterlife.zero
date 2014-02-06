{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 {if ($online_editor=='KindEditor')}<script>
     showHtmlEditor("address");</script>{/if}
 {if ($online_editor=='CKEditor')}
 {$editorHtml}
 <script>$(function(){
    ckeditor_replace_address();});</script>
 {/if}
 {if ($online_editor=='xhEditor')}<script>$(function(){
    pageInit_address();});</script>
 {/if}
 <div class="block">
    <div><h1>编辑用户详细信息</h1></div>
    <form name="userdetailForm" method="post" enctype="multipart/form-data"><input type="hidden" name="userdetail_id" value="{$userdetail.userdetail_id}"/>
    <table class="viewdoblock">
        <tr class="entry"><th class="head">用户标识</th><td class="content"><input type="text" class="edit" name="user_id" value="{$userdetail.user_id}"/></td></tr>
        <tr class="entry"><th class="head">真实姓名</th><td class="content"><input type="text" class="edit" name="realname" value="{$userdetail.realname}"/></td></tr>
        <tr class="entry"><th class="head">地区标识</th><td class="content"><input type="text" class="edit" name="region_id" value="{$userdetail.region_id}"/></td></tr>
        <tr class="entry"><th class="head">头像</th><td class="content"><p></p>
        <input type="file" id="profile" style="width:100%;" name="profileUpload" accept="image/png, image/gif, image/jpg, image/jpeg" value="{$userdetail.profile}"/></td></tr>
        <tr class="entry"><th class="head">QQ号</th><td class="content"><input type="text" class="edit" name="qq" value="{$userdetail.qq}"/></td></tr>
        <tr class="entry"><th class="head">会员性别</th><td class="content"><input type="text" class="edit" name="sex" value="{$userdetail.sex}"/></td></tr>
        <tr class="entry"><th class="head">生日</th><td class="content"><input type="text" class="edit" name="birthday" value="{$userdetail.birthday}"/></td></tr>
        <tr class="entry"><th class="head">家庭住址</th><td class="content">
        <textarea id="address" name="address" style="width:93%;height:300px;visibility:hidden;">{$userdetail.address}</textarea>
        </td></tr>
        <tr class="entry"><td class="content" colspan="2" align="center"><input id="btn" type="submit" value="提交" class="btnSubmit" /></td></tr>
    </table>
    </form>
    <div align="center"><my:a href='{$url_base}index.php?go=model.userdetail.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.userdetail.view&id={$userdetail.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看用户详细信息</my:a></div>
</div>
{/block}