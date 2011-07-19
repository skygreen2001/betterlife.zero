{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}

{if ($module=='bCheck')}
B-Check
{else}
<a href="{$url_base}index.php?go=admin.system.probe&module=source">B-Check</a>
{/if}
|
{if ($module=='probe')}
iProber
{else}
<a href="{$url_base}index.php?go=admin.system.probe&module=probe">iProber</a>
{/if}
|
{if ($module=='probe1')}
iProber 1
{else}
<a href="{$url_base}index.php?go=admin.system.probe&module=probe1">iProber 1</a>
{/if}
|
{if ($module=='probe2')}
iProber 2
{else}
<a href="{$url_base}index.php?go=admin.system.probe&module=probe2">iProber 2</a>
{/if}
<iframe src="{$redirect_module_url}" scrolling='auto' width='100%' height='700'  frameborder='0' />
{/block}