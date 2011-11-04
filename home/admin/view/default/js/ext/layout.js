Ext.namespace("Betterlife.Admin");

Bb = Betterlife.Admin;
/**
 * 页面布局格局
 */
Bb.Layout = Ext.emptyFn;
/**
 * 布局：页面头部
 */
Bb.Layout.HeaderPanel = [{
			region : 'north',
			contentEl : 'header',
			id : 'header-panel',
			height : 120,
			collapseMode : 'mini',
			split : true,
			layout : 'fit',
			collapsible : true,
			autoShow : true,
			title : '',
			margins : '0 0 1 0'
		}];
/**
 * 布局：页面左部
 */
Bb.Layout.LeftPanel = [{
			region : 'west',
			id : 'west-panel', // see Ext.getCmp() below
			title : '功能区',
			collapseMode : 'mini',
			split : true,
			width : 200,
			minSize : 100,
			maxSize : 400,
			collapsible : true,
			margins : '0 0 3 3',
			layout : {
				type : 'accordion',
				animate : true
			}
		}];
/**
 * 布局：页面内容区
 */
if (Bb.Config.IsTabHeaderShow) {
	Bb.Layout.CenterPanel = new Ext.TabPanel({
		region : 'center', // a center region is ALWAYS required for border
							// layout
		id : 'centerPanel',
		deferredRender : false,
		activeTab : 1, // first tab initially active
		resizeTabs : true, // turn on tab resizing
		minTabWidth : 115,
		tabWidth : 135,
		enableTabScroll : true,
		margins : '0 3 3 0',
		defaults : {
			autoScroll : true
		},
		plugins : [
				// Ext.ux.AddTaBbutton,
				new Ext.ux.TabCloseMenu(),// Tab标题头右键菜单选项生成
				// Tab标题头右侧下拉选择指定页面
				new Ext.ux.TabScrollerMenu({
							maxText : 15,
							pageSize : 5
						})],
		items : [{
					contentEl : 'centerArea',
					title : '首页',
					iconCls : 'tabs',
					autoScroll : true
				}, {
					title : '查询',
					html : "<a id='hideit' href='#'>隐藏左侧</a><iframe scrolling='auto' width='100%' height='100%'  frameborder='0' src='http://www.g.cn'> </iframe>",
					closable : true,
					iconCls : 'tabs',
					autoScroll : true
				}]
	});
} 
else{	
	Bb.Layout.CenterPanel = [{
				region : 'center',
				contentEl : 'centerArea',
				id : 'centerPanel',
				height : '100%',
				layout : 'fit',
				title : '',
				margins : '0 3 3 0'
			}];
}

/**
 * 布局：页面右部 
 */
Bb.Layout.RightPanel = [{
	region : 'east',
	id : 'right-panel',
	title : '',
	collapsible : true,
	collapseMode : 'mini',
	split : true,
	width : 225, // give east and west regions a width
	minSize : 175,
	maxSize : 400,
	margins : '0 5 0 0',
	layout : 'fit', // specify layout manager for items
	items : // this TabPanel is wrapped by another Panel so the title will be
			// applied
	new Ext.TabPanel({
				border : false, // already wrapped so don't add another border
				activeTab : 1, // second tab initially active
				tabPosition : 'bottom',
				items : [{
							html : '<p>技术人员CMS框架的最爱.</p>',
							title : 'BetterLife',
							autoScroll : true
						}, new Ext.grid.PropertyGrid({
									title : '属性',
									closable : true,
									source : {
										"(名称)" : "BetterLife",
										"友好" : true,
										"专业" : true,
										"专注" : true,
										"创建时间" : new Date(Date
												.parse('06/01/2010')),
										"邮箱" : "skygreen2001@gmail.com",
										"版本" : 1.0,
										"介绍" : "提供专业服务、并向企业客户提供IT服务为重点的盈利公司。"
									}
								})]
			})
}];

/**
 * 布局：页面底部 
 */ 
Bb.Layout.FooterPanel = [{
			// lazily created panel (xtype:'panel' is default)
			region : 'south',
			id : 'footer-panel',
			contentEl : 'south',
			split : true,
			collapseMode : 'mini',
			layout : 'fit',
			height : 50,
			minSize : 100,
			maxSize : 200,
			collapsible : true,
			autoScroll : true,
			title : '状态栏',
			margins : '0 0 0 0'
		}];

/**
 * Layout初始化 
 */
Bb.Layout.Init = function() {
	Ext.getCmp('west-panel').add(Bb.Layout.LeftMenuGroups);
	Ext.getCmp('west-panel').doLayout();
	// get a reference to the HTML element with id "hideit" and add a click
	// listener to it
	if (Ext.get("hideit")) {
		Ext.get("hideit").on('click', function() {
					// get a reference to the Panel that was created with id =
					// 'west-panel'
					var w = Ext.getCmp('west-panel');
					// expand or collapse that Panel based on its collapsed
					// property state
					if (w.collapsed) {
						Ext.get("hideit").update("隐藏左侧");
						w.expand();
					} else {
						Ext.get("hideit").update("显示左侧");
						w.collapse();
					}
				});
	}

	var navEs = Ext.get('west-panel').select('a');
	navEs.on('click', Bb.Navigaion.HyperlinkClicked);
};

