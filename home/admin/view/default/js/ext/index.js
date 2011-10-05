    Ext.namespace("betterlife.admin");
    bb = betterlife.admin;         
    /**
     * 全局配置
     */
    bb.Config=Ext.emptyFn;
    bb.Config={
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
        
        var viewport = new Ext.Viewport({
            layout: 'border',
            items: [      
              // create instance immediately 
              bb.Layout.headerPanel,
              bb.Layout.leftPanel,
              bb.Layout.centerPanel//,
              //bb.Layout.footerPanel,              
              //bb.Layout.rightPanel           
            ]
        });                                  

        bb.Layout.Init();
        
        viewport.doLayout();
        
        setTimeout(function(){
            Ext.get('loading').remove();
            Ext.get('loading-mask').fadeOut({remove:true});
        }, 250);        
    });