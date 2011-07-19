{* Flexy 语法写法  
<h1>请登录</h1>
<font color="red">{message}</font><br/>
<form method="POST">                    
用户名:<br/>
<input type="text" name="name"/><br/>
密码: <br/>
<input type="password" name="password" /><br/>
<input type="submit" name="Submit" value="登录" />
</form>
<a href="{url_base}index.php?g=betterlife&m=auth&a=register">注册</a>   

<!--Smarty 模板的写法-->*} 
{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    <div align="center">
        <form method="POST">
        <h1>请登录</h1>
        <font color="red">{$message}</font>
        <div>           
           <label>用户名</label><br/><input type="text" name="name" /><br/>
           <label>密码</label><br/><input type="password" name="password" /><br/>
        </div>
        <input type="submit" name="Submit" value="登录" />
        </form>
        <a href="{$url_base}index.php?go=betterlife.auth.register">注册</a>
    </div>
{/block}