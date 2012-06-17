Ext.namespace("Betterlife.Admin.View.Admin");
Bb = Betterlife.Admin.View.Admin;
Bb.Admin={};
/**
 * View:系统管理人员显示组件   
 */
Bb.Admin.View={ 
	/**
	 * 视图：产品图片视图
	 */
	AdminView:Ext.extend(parent.parent.Ext.Panel, {	
		constructor : function(config) {
			config = Ext.apply({
				headerAsText : false,autoScroll : true,ref:'dataview',
				defaults : {autoScroll : true},
				tpl: [
					  '<table class="viewdoblock">', 
						 '<tr class="entry"><td class="head">用户名</td><td class="content">{username}</td></tr>',
						 '<tr class="entry"><td class="head">真实姓名</td><td class="content">{realname}</td></tr>',
						 '<tr class="entry"><td class="head">扮演角色</td><td class="content">{roletypeShow}</td></tr>',
						 '<tr class="entry"><td class="head">视野</td><td class="content">{seescope}</td></tr>',
					 '</table>' 
				]
			}, config);
			Bb.Admin.View.AdminView.superclass.constructor.call(this, config); 
		}
	}),  
	/**
	 * 窗口:显示系统管理人员信息
	 */
	Window:Ext.extend(parent.parent.Ext.Window,{ 
		constructor : function(config) { 
			config = Ext.apply({
				title:"查看管理员信息",constrainHeader:true,maximizable: true,
				width : 605,height:350,minWidth:450,minHeight:300,
				layout : 'fit',resizable:true,plain : true,bodyStyle : 'padding:5px;',collapsible: true,//closeAction : "hide",modal:true,
				items:[new Bb.Admin.View.AdminView()],
				listeners: {
					hide:function(w){if (parent.Bb.Config){parent.Bb.Config.ViewOnlyWindow=null;}/**parent.Ext.getBody().unmask();*/}   
				},
				buttons: [{
					text: '更多',scope:this,handler:function() {Bb.Admin.Function.openLinkListAdmins();}
				},{
					text: '关闭',scope:this,handler:function() {this.hide();}
				}]
			}, config);  
			Bb.Admin.View.Window.superclass.constructor.call(this, config);   
		}        
	})
};

Bb.Admin.Function={
	openLinkListAdmins:function(){
		var targeturl="index.php?go=admin.index.admin";
		if (parent.parent.Bb.Navigation){
			parent.parent.Bb.Navigation.AddTabbyUrl(parent.Ext.getCmp('centerPanel'),'系统管理人员',targeturl,"admin"); 
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
	var admin_param=Ext.urlDecode(window.location.search.substring(1));
	var ow=false;
	if (admin_param&&admin_param.admin_id) admin_id=admin_param.admin_id;
	if (admin_param&&admin_param.ow) ow=admin_param.ow.length==4?true:false;
	if(typeof(admin_id)=="undefined"){Ext.Msg.alert('提示', '无符合查询条件的系统管理人员！');return;}
	
	if ((ow==false)||(parent.Bb.Config==null)||(parent.Bb.Config.ViewOnlyWindow==null)){
		Bb.Admin.ViewAdminWindow = new Bb.Admin.View.Window();
		if ((ow==true)&&parent.Bb.Config)parent.Bb.Config.ViewOnlyWindow=Bb.Admin.ViewAdminWindow;
	} else Bb.Admin.ViewAdminWindow=parent.Bb.Config.ViewOnlyWindow; 
	if (Bb.Admin.ViewAdminWindow){
		Bb.Admin.ViewAdminWindow.show();
		//parent.Ext.getBody().mask();
		ExtServiceAdmin.viewAdmin(admin_id,function(provider, response) {   
			if (response.result.data) Bb.Admin.ViewAdminWindow.dataview.update(response.result.data);
			else {
				Bb.Admin.ViewAdminWindow.dataview.update("");                        
				Ext.Msg.alert('提示', '无符合查询条件的系统管理人员！');
			}
		});
	}
});     