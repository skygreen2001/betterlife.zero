{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 {if ($online_editor=='KindEditor')}<script>
     showHtmlEditor("address");</script>{/if}
 {if ($online_editor=='CKEditor')}
 {$editorHtml}
 <style>
    .bar {
        height: 18px;
        background: green;
    }
 </style>
 <script>
$(function () {
    $('#profile').fileupload({
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                $('<p/>').text(file.name).appendTo(document.body);
            });
        },add: function (e, data) {
            data.context = $('<button/>').text('Upload')
                .appendTo(document.body)
                .click(function () {
                    data.context = $('<p/>').text('Uploading...').replaceAll($(this));
                    data.submit();
                }); 
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .bar').css(
                'width',
                progress + '%'
            );
        }
    });
});
 $(function(){
    ckeditor_replace_address();});</script>
 {/if}
 {if ($online_editor=='xhEditor')}<script>$(function(){
    pageInit_address();});</script>
 {/if}
 <div class="block">
    <div><h1>编辑用户详细信息</h1></div>
    <form name="userdetailForm" method="post" enctype="multipart/form-data"><input type="hidden" name="userdetail_id" value="{$userdetail.userdetail_id}"/>
    <table class="viewdoblock">
        <tr class="entry"><td class="head">用户标识</th><td class="content"><input type="text" class="edit" name="user_id" value="{$userdetail.user_id}"/></td></tr>
        <tr class="entry"><td class="head">真实姓名</th><td class="content"><input type="text" class="edit" name="realname" value="{$userdetail.realname}"/></td></tr>
        <tr class="entry"><td class="head">地区标识</th><td class="content"><input type="text" class="edit" name="region_id" value="{$userdetail.region_id}"/></td></tr>
        <tr class="entry"><td class="head">头像</th><td class="content"><input type="file" multiple id="profile" name="profile" value="{$userdetail.profile}"/>
        <div id="progress"><div class="bar" style="width: 0%;"></div></div>
        </td></tr>
        <tr class="entry"><td class="head">QQ号</th><td class="content"><input type="text" class="edit" name="qq" value="{$userdetail.qq}"/></td></tr>
        <tr class="entry"><td class="head">会员性别</th><td class="content"><input type="text" class="edit" name="sex" value="{$userdetail.sex}"/></td></tr>
        <tr class="entry"><td class="head">生日</th><td class="content"><input type="text" class="edit" name="birthday" value="{$userdetail.birthday}"/></td></tr>
        <tr class="entry"><td class="head">家庭住址</th><td class="content">
        <textarea id="address" name="address" style="width:93%;height:300px;visibility:hidden;">{$userdetail.address}</textarea>
        </td></tr>
        <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
    </table>
    </form>
    <div align="center"><my:a href='{$url_base}index.php?go=model.userdetail.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.userdetail.view&id={$userdetail.id}&pageNo={$smarty.get.pageNo|default:"1"}'>查看用户详细信息</my:a></div>
</div>
{/block}