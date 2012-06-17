Ext.namespace("Betterlife.Admin.View.User");
Bb = Betterlife.Admin.View.User;
Bb.User={};
/**
 * View:系统管理人员显示组件   
 */
Bb.User.View={ 
	/**
	 * 视图：产品图片视图
	 */
	UserView:Ext.extend(parent.parent.Ext.Panel, {    
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
			Bb.User.View.UserView.superclass.constructor.call(this, config); 
		}
	}),  
	/**
	 * 窗口:显示系统管理人员信息
	 */
	Window:Ext.extend(parent.parent.Ext.Window,{ 
		constructor : function(config) { 
			config = Ext.apply({
				title:"查看用户信息",constrainHeader:true,maximizable: true,collapsible: true,
				width : 605,height:350,minWidth:450,minHeight:300,
				layout : 'fit',resizable:true,plain : true,bodyStBbe : 'padding:5px;',
				items:[new Bb.User.View.UserView()],
				listeners: {
					hide:function(w){if (parent.Bb.Config){parent.Bb.Config.ViewOnlyWindow=null;}}   			  
				},
				buttons: [{
					text: '更多',scope:this,handler:function() {Bb.User.Function.openLinkListUsers();}
				},{
					text: '关闭',scope:this,handler:function() {this.hide();}
				}]
			}, config);  
			Bb.User.View.Window.superclass.constructor.call(this, config);   
		}        
	})
};

Bb.User.Function={
	openLinkListUsers:function(){
		var targeturl="index.php?go=admin.index.user&user_id="+Bb.User.user_id;
		if (parent.parent.Bb.Navigation){
			parent.parent.Bb.Navigation.AddTabbyUrl(parent.Ext.getCmp('centerPanel'),'用户',targeturl,"user"); 
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
		Bb.User.ViewUserWindow = new Bb.User.View.Window();
		if ((ow==true)&&parent.Bb.Config)parent.Bb.Config.ViewOnlyWindow=Bb.User.ViewUserWindow;
	} else Bb.User.ViewUserWindow=parent.Bb.Config.ViewOnlyWindow; 
	if (Bb.User.ViewUserWindow){
		Bb.User.ViewUserWindow.show();
		Bb.User.user_id=user_id;
		ExtServiceUser.viewUser(user_id,function(provider, response) {   
			if (response.result.data) Bb.User.ViewUserWindow.dataview.update(response.result.data);
			else {
				Bb.User.ViewUserWindow.dataview.update("");                        
				Ext.Msg.alert('提示', '无符合查询条件的用户！');
			}
		});
	}
});     