{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
{if ($module=='source')}
源码文件管理
{else}
<a href="{$url_base}index.php?go=admin.system.filemanager&module=source">源码文件管理</a>
{/if}
|
{if ($module=='image')}
图片文件上传
{else}
<a href="{$url_base}index.php?go=admin.system.filemanager&module=image">图片文件上传</a>
{/if}
|
{if ($module=='files')}
文件管理
{else}
<a href="{$url_base}index.php?go=admin.system.filemanager&module=files">文件管理</a>
{/if}
<iframe src="{$redirect_module_url}" scrolling='auto' width='100%' height='700'  frameborder='0' />
{/block}