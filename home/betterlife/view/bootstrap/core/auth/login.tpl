{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    <form method="POST">
    <div class="col-lg-6" style="position: absolute;width:600px;height:300px;left:50%;top:300px;margin-left:-300px;margin-top:-150px;">
        <h2></h2>
        <div class="bs-component">
            <div class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title"><span style="font-family: Arial">Betterlife CMS</span> 框架前台</h3>
                        </div>
                        <div class="modal-body" style="height:120px;"><nobr>
                           <label>用户名</label><input class="inputNormal inputLogin" type="text" name="username" /><br/><br/>
                           <label>密&nbsp;码</label><input class="inputNormal inputLogin" type="password" name="password" /><br/>
                           <br/><br/><font style="margin-left:80px;" color="red">{$message}</font></nobr>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" name="Submit" value="登录" class="btnSubmit" />
                            <button type="button" class="btn btn-primary" style="width:100px;margin-right: 20px;" onclick="javascript:window.location.href='{$url_base}index.php?go={$appName}.auth.register'">注册</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div><div style="width:50%; margin:0 auto;" align="left">[测试帐户]用户名:admin,密码:admin<br/>[测试帐户]用户名:china,密码:iloveu</div></div>
    </div>
    </form>
{/block}
