Ext.namespace("BetterlifeNet.Admin.View.User");
BnView = BetterlifeNet.Admin.View;
BnView.User={};
/**
 * View:系统管理人员显示组件
 */
BnView.User.View={
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
			BnView.User.View.UserView.superclass.constructor.call(this, config);
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
				items:[new BnView.User.View.UserView()],
				listeners: {
					hide:function(w){if (parent.Bn&&parent.Bn.Config){parent.Bn.Config.ViewOnlyWindow=null;}}
				},
				buttons: [{
					text: '更多',scope:this,handler:function() {BnView.User.Function.openLinkListUsers();}
				},{
					text: '关闭',scope:this,handler:function() {this.hide();}
				}]
			}, config);
			BnView.User.View.Window.superclass.constructor.call(this, config);
		}
	})
};

BnView.User.Function={
	openLinkListUsers:function(){
		var targeturl="index.php?go=admin.betterlife.user&user_id="+BnView.User.user_id;
		if (parent.Bn){
			parent.Bn.Navigation.AddTabbyUrl(parent.Bn.Viewport.center,'用户',targeturl,"user");
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
	if ((ow==false)||(parent.Bn.Config==null)||(parent.Bn.Config.ViewOnlyWindow==null)){
		BnView.User.ViewUserWindow = new BnView.User.View.Window();
		if ((ow==true)&&parent.Bn.Config)parent.Bn.Config.ViewOnlyWindow=BnView.User.ViewUserWindow;
	} else {
		if (parent.Bn.Config.ViewOnlyWindow.title=="查看用户信息"){
			BnView.User.ViewUserWindow=parent.Bn.Config.ViewOnlyWindow;
		}else{
			BnView.User.ViewUserWindow = new BnView.User.View.Window();
			if ((ow==true)&&parent.Bn.Config)parent.Bn.Config.ViewOnlyWindow=BnView.User.ViewUserWindow;
		}
	}
	if (BnView.User.ViewUserWindow){
		BnView.User.ViewUserWindow.show();
		BnView.User.user_id=user_id;
		ExtServiceUser.viewUser(user_id,function(provider, response) {
			if (response.result.data) BnView.User.ViewUserWindow.dataview.update(response.result.data);
			else {
				BnView.User.ViewUserWindow.dataview.update("");
				Ext.Msg.alert('提示', '无符合查询条件的用户！');
			}
		});
	}
});