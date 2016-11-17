<!DOCTYPE html>
<html lang="zh">
  <head>
{include file="$templateDir/layout/normal/header.tpl"}
    <link rel="icon" href="favicon.ico" mce_href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="{$template_url}resources/css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="{$template_url}resources/css/bootswatch.min.css">
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/public.css" />
    <link rel="icon" href="favicon.ico" mce_href="favicon.ico" type="image/x-icon" />
    <script type="text/javascript" src="{$url_base}misc/js/ajax/jquery/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="{$template_url}js/public.js"></script>
{$viewObject->css_ready|default:""}
    <!-- Latest compiled and minified CSS -->
{$viewObject->js_ready|default:""}
  </head>
  {php}
     flush();
  {/php}
  <body>
    {block name=body}{/block}
  </body>
</html>