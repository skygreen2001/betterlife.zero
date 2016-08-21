{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    <div id="loading-mask" style=""></div>
    <div id="loading">
        <div class="loading-indicator"><img src="{$url_base}common/js/ajax/ext/resources/images/extanim32.gif" width="32" height="32" style="margin-right:8px;" align="absmiddle"/>正在加载中...</div>
    </div>
    <div class="container" style="width:500px">
        <div id="resourceLibrary-grid"></div>
    </div>
{/block}
