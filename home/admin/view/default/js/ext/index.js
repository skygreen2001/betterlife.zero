Ext.namespace("Betterlife.Admin");
Bb = Betterlife.Admin;         
/**
 * 全局配置
 */
Bb.Config=Ext.emptyFn;
Bb.Config={
    /**
     *是否显示Tab头
     */        
    IsTabHeaderShow:true
};

Ext.onReady(function(){    
    Ext.QuickTips.init();
    
    // NOTE: This is an example showing simple state management. During development,
    // it is generally best to disable state management as dynamically-generated ids
    // can change across page loads, leading to unpredictable results.  The developer
    // should ensure that stable state ids are set for stateful components in real apps.
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
    
    Bb.Viewport = new Ext.Viewport({
        layout: 'border',
        items: [      
          // create instance immediately 
          Bb.Layout.HeaderPanel,
          Bb.Layout.LeftPanel,
          Bb.Layout.CenterPanel//,
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
});