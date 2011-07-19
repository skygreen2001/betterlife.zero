{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body} 
    <div align="center">
        <h1>请注册您的账户</h1>
        <font color="{$color|default:'white'}">{$message|default:''}</font><br/>
        <form method="POST">
        <label>用户名</label><br/>
        <input type="text" name="name" /><br/>
        <label>密码</label><br/>
        <input type="password" name="password" /><br/> 
        <input type="submit" name="submit" value="注册"/>
        </form>
        <a href="{$url_base}index.php?go=betterlife.auth.login">登录</a> 
    </div>
{/block}