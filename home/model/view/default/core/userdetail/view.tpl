{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
 <style>
/* table-cell方式 */
.wrap_2_outer { border:1px solid #ccc; width:400px; height:400px; display:table-cell; font-size:0; text-align:center; vertical-align:middle; *position:relative;padding:0; overflow:hidden; }
.wrap_2_inner { text-align:center; vertical-align:middle; width:400px9; *width:auto;font-size:0; *position:absolute;*top:50%;*left:50%;}
.wrap_2_inner img { max-height:400px; max-width:400px; *position:relative;*bottom:50%;*right:50%;margin:0 auto;}
      
 </style>


<div class="block">
    <div><h1>查看用户详细信息</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><td class="head">标识</th><td class="content">{$userdetail.userdetail_id}</td></tr> 
        <tr class="entry"><td class="head">用户标识</th><td class="content">{$userdetail.user_id}</td></tr> 
        <tr class="entry"><td class="head">真实姓名</th><td class="content">{$userdetail.realname}</td></tr> 
        <tr class="entry"><td class="head">地区标识</th><td class="content">{$userdetail.region_id}</td></tr> 
        <tr class="entry"><td class="head">头像</th><td class="content">
        <div class="wrap_2_outer"><div class="wrap_2_inner"><img src="{$uploadImg_url|cat:$userdetail.profile}" alt="头像"></div></div>
            <br/>存储相对路径:{$userdetail.profile}</td></tr> 
        <tr class="entry"><td class="head">家庭住址</th><td class="content">{$userdetail.address}</td></tr> 
        <tr class="entry"><td class="head">QQ号</th><td class="content">{$userdetail.qq}</td></tr> 
        <tr class="entry"><td class="head">会员性别</th><td class="content">{$userdetail.sex}</td></tr> 
        <tr class="entry"><td class="head">生日</th><td class="content">{$userdetail.birthday}</td></tr> 
    </table>
    <div align="center"><my:a href='index.php?go=model.userdetail.lists&pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='index.php?go=model.userdetail.edit&id={$userdetail.id}&pageNo={$smarty.get.pageNo|default:"1"}'>修改用户详细信息</my:a></div>
</div>
{/block}