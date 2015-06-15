<!DOCTYPE html>
<html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">
  <head>
{include file="$templateDir/layout/normal/header.tpl"}
	<link rel="icon" href="favicon.ico" mce_href="favicon.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="{$template_url}resources/css/public.css" />
	<link rel="icon" href="favicon.ico" mce_href="favicon.ico" type="image/x-icon">
	<script type="text/javascript" src="{$url_base}common/js/ajax/jquery/jquery-1.11.0.js"></script>
    {*<script src="{$url_base}common/js/ajax/jquery/jquery-1.11.0.min.js"></script>  *}
  <link rel="stylesheet" href="{$template_url}resources/css/bootstrap.min.css" media="screen">
  <link rel="stylesheet" href="{$template_url}resources/css/bootswatch.min.css">
	<script type="text/javascript" src="{$template_url}js/public.js"></script>
{$viewObject->css_ready|default:""}
{$viewObject->js_ready|default:""}
	{* 此处原为本应用框架加载Ext必须加载的文件。先已通过Gzip动态加载生成。*}
  </head>
  {php}
	 flush();
  {/php}
  <body>
	{block name=body}{/block}
  </body>
</html>