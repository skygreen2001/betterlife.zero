Ext.namespace("Betterlife.Admin.View.User");
BbView = Betterlife.Admin.View;
BbView.User={};
/**
 * View:系统管理人员显示组件
 */
BbView.User.View={
	/**
	 * 视图：产品图片视图
	 */
	UserView:Ext.extend(parent.Ext.Panel, {
		constructor : function(config) {
			config = Ext.apply({
				headerAsText : false,autoScroll : true,ref:'dataview',
				defaults : {autoScroll : true},
				tpl: [
					  '<table class="viewdoblock">',
						 '<tr class="entry"><td class="head">部门</td><td class="content">{department_name}</td></tr>',
						 '<tr class="entry"><td class="head">用户名</td><td class="content">{username}</td></tr>',
						 '<tr class="entry"><td class="head">邮箱地址</td><td class="content">{email}</td></tr>',
					  '</table>'
				]
			}, config);
			BbView.User.View.UserView.superclass.constructor.call(this, config);
		}
	}),
	/**
	 * 窗口:显示系统管理人员信息
	 */
	Window:Ext.extend(parent.Ext.Window,{
		constructor : function(config) {
			config = Ext.apply({
				title:"查看用户信息",constrainHeader:true,maximizable: true,collapsible: true,
				width : 605,height:350,minWidth:450,minHeight:300,
				layout : 'fit',resizable:true,plain : true,bodyStBbe : 'padding:5px;',
				items:[new BbView.User.View.UserView()],
				listeners: {
					hide:function(w){if (parent.Bb&&parent.Bb.Config){parent.Bb.Config.ViewOnlyWindow=null;}}
				},
				buttons: [{
					text: '更多',scope:this,handler:function() {BbView.User.Function.openLinkListUsers();}
				},{
					text: '关闭',scope:this,handler:function() {this.hide();}
				}]
			}, config);
			BbView.User.View.Window.superclass.constructor.call(this, config);
		}
	})
};

BbView.User.Function={
	openLinkListUsers:function(){
		var targeturl="index.php?go=admin.betterlife.user&user_id="+BbView.User.user_id;
		if (parent.Bb){
			parent.Bb.Navigation.AddTabbyUrl(parent.Bb.Viewport.center,'用户',targeturl,"user");
		}else{
			window.open(targeturl);
		}
	}
}

/**
 * Controller:主程序
 */
Ext.onReady(function(){
	Ext.QuickTips.init();
	Ext.Direct.addProvider(Ext.app.REMOTING_API);
	var user_param=Ext.urlDecode(window.location.search.substring(1));
	var ow=false;
	if (user_param&&user_param.user_id) user_id=user_param.user_id;
	if (user_param&&user_param.ow) ow=user_param.ow.length==4?true:false;
	if(typeof(user_id)=="undefined"){Ext.Msg.alert('提示', '无符合查询条件的用户！');return;}
	if ((ow==false)||(parent.Bb.Config==null)||(parent.Bb.Config.ViewOnlyWindow==null)){
		BbView.User.ViewUserWindow = new BbView.User.View.Window();
		if ((ow==true)&&parent.Bb.Config)parent.Bb.Config.ViewOnlyWindow=BbView.User.ViewUserWindow;
	} else {
		if (parent.Bb.Config.ViewOnlyWindow.title=="查看用户信息"){
			BbView.User.ViewUserWindow=parent.Bb.Config.ViewOnlyWindow;
		}else{
			BbView.User.ViewUserWindow = new BbView.User.View.Window();
			if ((ow==true)&&parent.Bb.Config)parent.Bb.Config.ViewOnlyWindow=BbView.User.ViewUserWindow;
		}
	}
	if (BbView.User.ViewUserWindow){
		BbView.User.ViewUserWindow.show();
		BbView.User.user_id=user_id;
		ExtServiceUser.viewUser(user_id,function(provider, response) {
			if (response.result.data) BbView.User.ViewUserWindow.dataview.update(response.result.data);
			else {
				BbView.User.ViewUserWindow.dataview.update("");
				Ext.Msg.alert('提示', '无符合查询条件的用户！');
			}
		});
	}
});