{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
	<div id="loading-mask"></div>
	<div id="loading">
		<div class="loading-indicator"><img src="{$url_base}common/js/ajax/ext/resources/images/extanim32.gif" width="32" height="32" style="margin-right:8px;" align="absmiddle"/>正在加载中...</div>
	</div>
	<div id="header" class='x-hide-display'>
		<table>
			<tr>
				<td align="center" colspan="2"><img style="margin-left: 5px" src="{$template_url}resources/images/logo.png"></td>
			</tr>  
			<tr>
				<td><div id="toolbar"></div></td>
				<td><span style="float:right; font-size: 14px; margin-top: 0;margin-right: 10px;color: #CCC"><a href="{$url_base}index.php?go=admin.index.logout" style="padding:5px">退出</a></span></td>
			</tr>
		</table>
	</div>
	{foreach item=menuGroup from=$menuGroups} 
	<div id='{$menuGroup.id}' class="x-hide-display">
	  {if ($menuGroup.lang neq "")}<div class="{$menuGroup.lang}">{/if}
		{foreach item=menu from=$menuGroup.menus} 
		<p><a id="{$menu.id}" href="{$menu.address}" title="{$menu.title|default:$menu.name}" {if ($menu.lang neq "")}class="menuIcon {$menu.lang}"{else}class="menuIcon"{/if}>{$menu.name}</a></p>{/foreach}

	  {if ($menuGroup.lang neq "")}</div>{/if}

	</div>
	{/foreach}
	<div id="centerArea" class="x-hide-display">
		<div align="center">        
			<h1>欢迎来到&nbsp;&nbsp;<span class="en-head">{$site_name}后台管理中心</span></h1><br/><br/>
			<h2 id="content-head">推荐开发工具:</h2>
			<p id="indexPage">
			   部署：<span class="en">Wamp(windows+apache+mysql+php)</span><br/>
			   开发：<span class="en">NetBeans + xDebug</span>|<span class="en">PhpEd + Dbg Debugger</span><br/>
			   模板：<span class="en">Flexy|Smarty.</span>
			</p>
		</div>
		<br/><br/><br/><br/><br/><br/><br/><br/><br/>
	</div>
	<div id="south" class="x-hide-display">
		<p>这是状态栏位</p>
	</div>
	<form id="history-form" class="x-hidden">
		<input type="hidden" id="x-history-field" /><iframe id="x-history-frame"></iframe>
	</form>
	<div id="viewFrame"><iframe id="frmview" name="frmview" width="0" height="0"></iframe></div>
{/block}
