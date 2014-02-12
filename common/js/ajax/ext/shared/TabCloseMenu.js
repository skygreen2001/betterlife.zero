/**禁止浏览器自带的右键功能,Firefox除外。*/
Ext.getBody().on("contextmenu", Ext.emptyFn, null, {preventDefault: true});
/**
 * @class Ext.ux.TabCloseMenu
 * @extends Object
 * Plugin (ptype = 'tabclosemenu') for adding a close context menu to tabs. Note that the menu respects
 * the closable configuration on the tab. As such, commands like remove others and remove all will not
 * remove items that are not closable.
 *
 * @constructor
 * @param {Object} config The configuration options
 * @ptype tabclosemenu
 */
Ext.ux.TabCloseMenu = Ext.extend(Object, {
	/**
	 * @cfg {String} closeTabText
	 * The text for closing the current tab. Defaults to <tt>'Close Tab'</tt>.
	 */
	closeTabText: '关闭标签',//Close Tab

	/**
	 * @cfg {String} closeOtherTabsText
	 * The text for closing all tabs except the current one. Defaults to <tt>'Close Other Tabs'</tt>.
	 */
	closeOtherTabsText: '关闭其他',//'Close Other Tabs'
	/**
	 * 是否开发模式
	 */
	isDev:true,
	/**
	 * @cfg {String} closeAllTabsText
	 * <p>The text for closing all tabs. Defaults to <tt>'Close All Tabs'</tt>.
	 */
	closeAllTabsText: '关闭所有',//'Close All Tabs'

	/**
	 * Chrome:在新标签页中打开链接(T)
	 * IE:在新选项卡中打开(W)
	 * Firefox:在新标签页中打开(T)
	 */
	openInNewIETabText:'在新标签页中打开',
	/**
	 * 刷新Tab页当前的链接
	 */
	refreshTabText:'刷新',

	/**
	 * @cfg {Boolean} showCloseAll
	 * Indicates whether to show the 'Close All' option. Defaults to <tt>true</tt>.
	 */
	showCloseAll: true,

	constructor : function(config){
		Ext.apply(this, config || {});
	},

	//public
	init : function(tabs){
		this.tabs = tabs;
		tabs.tabCloseMenu=this;
		tabs.on({
			scope: this,
			contextmenu: this.onContextMenu,
			destroy: this.destroy
		});
	},

	destroy : function(){
		Ext.destroy(this.menu);
		delete this.menu;
		delete this.tabs;
		delete this.active;
	},

	// private
	onContextMenu : function(tabs, item, e){
		if (Ext.isGecko){
			e.preventDefault();
		}
		this.active = item;
		var m = this.createMenu(),
		disableAll = true,
		disableOthers = true,
		closeAll = m.getComponent('closeall');
		m.setWidth(150);
		m.getComponent('close').setDisabled(!item.closable);
		tabs.items.each(function(){
			if(this.closable){
				disableAll = false;
				if(this != item){
					disableOthers = false;
					return false;
				}
			}
		});
		m.getComponent('closeothers').setDisabled(disableOthers);
		if(closeAll){
			closeAll.setDisabled(disableAll);
		}
		e.stopEvent();
		m.showAt(e.getPoint());
	},

	createMenu : function(){
		if(!this.menu){
			var items = [{
				itemId: 'close',
				text: this.closeTabText,
				scope: this,
				handler: this.onClose
			}];
			if(this.showCloseAll){
				items.push('-');
			}
			items.push({
				itemId: 'closeothers',
				text: this.closeOtherTabsText,
				scope: this,
				handler: this.onCloseOthers
			});
			if(this.showCloseAll){
				items.push({
					itemId: 'closeall',
					text: this.closeAllTabsText,
					scope: this,
					handler: this.onCloseAll
				});
			}
			if (this.isDev){
				items.push({
					itemId: 'openInNewIETab',
					text: this.openInNewIETabText,
					scope: this,
					handler: this.onOpenInNewIETab
				});
			}
			items.push({
				itemId: 'refreshTab',
				text: this.refreshTabText,
				scope: this,
				handler: this.onRefreshTab
			});
			this.menu = new Ext.menu.Menu({
				items: items
			});
		}
		return this.menu;
	},

	onClose : function(){
		this.tabs.remove(this.active);
	},

	onCloseOthers : function(){
		this.doClose(true);
	},

	onCloseAll : function(){
		this.doClose(false);
	},

	onOpenInNewIETab:function(){
		window.open(this.tabs.activeTab.url,"_blank");
	},

	onRefreshTab:function(){
		if (window.frames){
			var frames_id="frm"+this.tabs.activeTab.id.replace(/cp-/, "");
			if (Ext.get(frames_id)){
				Ext.get(frames_id).dom.src=this.tabs.activeTab.url+"&".concat(Math.random());
			}
		}
	},

	doClose : function(excludeActive){
		var items = [];
		this.tabs.items.each(function(item){
			if(item.closable){
				if(!excludeActive || item != this.active){
					items.push(item);
				}
			}
		}, this);
		Ext.each(items, function(item){
			this.tabs.remove(item);
		}, this);
	}
});

Ext.preg('tabclosemenu', Ext.ux.TabCloseMenu);
Ext.override(Ext.grid.GridView, {
	templates: {
		cell: new Ext.Template(
					'<td class="x-grid3-col x-grid3-cell x-grid3-td-{id} {css}" style="{style}" tabIndex="0" {cellAttr}>',
					'<div class="x-grid3-cell-inner x-grid3-col-{id}" {attr}>{value}</div>',
					"</td>"
			)
	}
});