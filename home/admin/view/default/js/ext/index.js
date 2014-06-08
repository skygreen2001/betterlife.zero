Ext.namespace("BetterlifeNet.Admin");
Bn = BetterlifeNet.Admin;         
/**
 * 全局配置
 */  
Bn.Config={
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
     * 1:CkEditor,2:KindEditor,3:xhEditor,4:UEditor
     * 配合Action的变量配置$online_editor
     */
    OnlineEditor:4,
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
            Bn.Config.OnlineEditor=Ext.util.Cookies.get('OnlineEditor');
        }
        if (Ext.util.Cookies.get('operator')!=null){
            Bn.Config.Operator=Ext.util.Cookies.get('operator');
        }
        if (Ext.util.Cookies.get('admin_id')!=null){
            Bn.Config.Admin_id=Ext.util.Cookies.get('admin_id');
        }
    }
};

Ext.onReady(function(){
    Ext.QuickTips.init();   
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
    var centerPanel=Bn.Layout.CenterPanel; 
    if (!Bn.Config.IsTabHeaderShow){
        centerPanel=Bn.Layout.CenterPanel_NoTabs;
    } 
    Bn.Viewport = new Ext.Viewport({
        layout: 'border',
        items: [      
          Bn.Layout.HeaderPanel,
          Bn.Layout.LeftPanel,centerPanel//,
          //Bn.Layout.FooterPanel,              
          //Bn.Layout.RightPanel           
        ]
    });
    Bn.Config.Init();
    Bn.Layout.Init();  
    Bn.Viewport.doLayout(); 
    setTimeout(function(){
        Ext.get('loading').remove();
        Ext.get('loading-mask').fadeOut({remove:true});
    }, 250);
        
    //让浏览器的后退前进跟从Tab访问的历史记录
    Ext.History.init();    
    
    // Handle this change event in order to restore the UI to the appropriate history state
    Ext.History.on('change', function(token){
        if(token){
            var parts = token.split(Bn.Config.TokenDelimiter);
            var tabPanel = Ext.getCmp(parts[0]);
            var tabId = parts[1];
            tabPanel.show();
            tabPanel.setActiveTab(tabId);
        }
    });      
});