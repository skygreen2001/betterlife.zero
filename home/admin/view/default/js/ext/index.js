Ext.namespace("Betterlife.Admin");
Bb = Betterlife.Admin;         
/**
 * 全局配置
 */  
Bb.Config={
	/**
	 *是否显示Tab头
	 */        
	IsTabHeaderShow:true, 
	/**
	 * 访问历史记录的组件标识分割符
	 */
	TokenDelimiter:':',
	/**
	 * 操作者 
	 */
	Operator:'',
	/**
	 * 在线编辑器类型。
	 * 1:CkEditor,2:KindEditor,3:xhEditor
	 * 配合Action的变量配置$online_editor
	 */
	OnlineEditor:1, 
	/**
	 * 提供第三方公用使用的窗口，一个应用只有一个这样的窗口 
	 */
	ViewOnlyWindow:null,
	ViewOnlyWindows:{},
	/**
	 * 初始化
	 */
	Init:function(){
		if (Ext.util.Cookies.get('OnlineEditor')!=null){
			Bb.Config.OnlineEditor=Ext.util.Cookies.get('OnlineEditor');
		}
		if (Ext.util.Cookies.get('operator')!=null){
			Bb.Config.Operator=Ext.util.Cookies.get('operator');
		}
		if (Ext.util.Cookies.get('admin_id')!=null){
			Bb.Config.Admin_id=Ext.util.Cookies.get('admin_id');
		}
	}
};

Ext.onReady(function(){
	Ext.QuickTips.init();   
	Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
	var centerPanel=Bb.Layout.CenterPanel; 
	if (!Bb.Config.IsTabHeaderShow){
		centerPanel=Bb.Layout.CenterPanel_NoTabs;
	} 
	Bb.Viewport = new Ext.Viewport({
		layout: 'border',
		items: [      
		  Bb.Layout.HeaderPanel,Bb.Layout.LeftPanel,centerPanel//,
		  //Bb.Layout.FooterPanel,              
		  //Bb.Layout.RightPanel           
		]
	});
	Bb.Config.Init();
	Bb.Layout.Init();  
	Bb.Viewport.doLayout(); 
	setTimeout(function(){
		Ext.get('loading').remove();
		Ext.get('loading-mask').fadeOut({remove:true});
	}, 250);
		
	//让浏览器的后退前进跟从Tab访问的历史记录
	Ext.History.init();    
	
	// Handle this change event in order to restore the UI to the appropriate history state
	Ext.History.on('change', function(token){
		if(token){
			var parts = token.split(Bb.Config.TokenDelimiter);
			var tabPanel = Ext.getCmp(parts[0]);
			var tabId = parts[1];
			tabPanel.show();
			tabPanel.setActiveTab(tabId);
		}
	});      
});