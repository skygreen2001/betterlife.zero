<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
{* xhtml1-transitional.dtd *}
<html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">           
  <head>
    {include file="$templateDir/layout/normal/header.tpl"}
{$viewObject->css_ready|default:""}
{$viewObject->js_ready|default:""}
    {* 此处原为本应用框架加载Ext必须加载的文件。先已通过Gzip动态加载生成。*}
  </head>
  <body>
    {block name=body}{/block}
  </body>
</html>