{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    <div id="loading-mask"></div>
    <div id="loading">
        <div class="loading-indicator"><img src="common/js/ajax/ext/resources/images/extanim32.gif" width="32" height="32" style="margin-right:8px;" align="absmiddle"/>正在加载中...</div>
    </div>
   <div id="win1" class="x-hide-display"></div>
   {$editorHtml}
{/block}
