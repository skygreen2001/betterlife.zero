Ext.namespace("Betterlife.Admin");
Bb = Betterlife.Admin;         
/**
 * 全局配置
 */  
Bb.Config={
	/**
	 *是否显示Tab头
	 */        
	IsTabHeaderShow:true
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
		  Bb.Layout.HeaderPanel,
		  Bb.Layout.LeftPanel,
		  centerPanel//,
		  //Bb.Layout.FooterPanel,              
		  //Bb.Layout.RightPanel           
		]
	});
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
			var parts = token.split(tokenDelimiter);
			var tabPanel = Ext.getCmp(parts[0]);
			var tabId = parts[1];
			tabPanel.show();
			tabPanel.setActiveTab(tabId);
		}
	});      
});