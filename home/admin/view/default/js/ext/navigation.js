Ext.namespace("Betterlife.Admin");
Bb = Betterlife.Admin;

/**
 * 导航功能
 */
Bb.Navigaion = Ext.emptyFn;
/**
 * 在指定的TabPanel组件上添加Tab 【如果已经添加了则激活该Tab】
 * 
 * @param contentPanel
 *            TabPanel component组件
 * @param title
 *            Tab头标题
 * @param html
 *            Tab页面内的内容
 * @param id
 *            添加的Tab的标识
 */
Bb.Navigaion.AddTab = function(contentPanel, title, html, id) {
	var cpId = 'cp-' + id;
	var tab = Ext.getCmp(cpId);
	if (tab) {
		contentPanel.setActiveTab(tab);
	} else {
		contentPanel.add({
					title : title,
					html : html,
					closable : true
				}).show();
	}
};

/**
 * 页面导航在Tab内嵌一个Ifame组件
 */
Bb.Navigaion.IFrameComponent = Ext.extend(Ext.BoxComponent, {
	onRender : function(ct, position) {
		this.el = ct.createChild({
					tag : 'iframe',
					id : 'iframe-' + this.id,
					frameBorder : 0,
					src : this.url
				});
	}
});

/**
 * 根据指定的Url在指定的TabPanel组件上添加Tab 【如果已经添加了则激活该Tab】
 * 
 * @param contentPanel
 *            TabPanel component组件
 * @param title
 *            Tab头标题
 * @param url
 *            指定的Url
 * @param id
 *            添加的Tab的标识
 */
Bb.Navigaion.AddTabbyUrl = function(contentPanel, title, url, id) {
	var cpId = 'cp-' + id;
	var tab = Ext.getCmp(cpId);
	var isComponent = false;
	if (!Bb.Config.IsTabHeaderShow) {
		var centerArea = Ext.get("centerArea");
		centerArea.setHeight(contentPanel.getHeight());
		centerArea.setWidth(contentPanel.getWidth());
		centerArea
				.update("<iframe scrolling='auto' width='100%' height='100%'  frameborder='0' src='"
						+ url + "'> </iframe>");
		contentPanel.setTitle(title, "tabs");
		return;
	}
	if (tab) {
		contentPanel.setActiveTab(tab);
	} else {
		if (isComponent) {
			tab = new Ext.Panel({
						id : cpId,
						title : title,
						closable : true,
						// layout to fit child component
						layout : 'fit',
						iconCls : 'tabs',
						border : false,
						// add iframe as the child component
						items : [new Bb.Navigaion.IFrameComponent({
									id : id,
									url : url
								})]
					});
			contentPanel.add(tab).show();
		} else {
			contentPanel.add({
				id : cpId,
				title : title,
				iconCls : 'tabs',
				html : "<iframe scrolling='auto' width='100%' height='100%'  frameborder='0' src='"
						+ url + "'> </iframe>",
				closable : true
			}).show();
		}
	}
};

/**
 * 改写超链接默认事件，使新打开的页面都显示在指定的TabPanel组件上。
 */
Bb.Navigaion.HyperlinkClicked = function(e) {
	e.preventDefault();
	var linkTarget = e.target;
	var title = "";
	if (Ext.isIE) {
		title = linkTarget.innerText;
	} else {
		title = linkTarget.text;
	}
	Bb.Navigaion.AddTabbyUrl(Ext.getCmp('centerPanel'), title, linkTarget.href,
			linkTarget.id);
};