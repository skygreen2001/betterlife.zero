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
        
        Ext.getCmp('west-panel').add(bb.Layout.LeftMenuGroups);
        Ext.getCmp('west-panel').doLayout();    
        
        // get a reference to the HTML element with id "hideit" and add a click listener to it 
        if (Ext.get("hideit")){
            Ext.get("hideit").on('click', function(){
                // get a reference to the Panel that was created with id = 'west-panel' 
                var w = Ext.getCmp('west-panel');
                // expand or collapse that Panel based on its collapsed property state
                w.collapsed ? w.expand() : w.collapse();
            });
        }
        
        var navEs=Ext.get('west-panel').select('a');
        navEs.on('click', bb.Navigaion.HyperlinkClicked);
        
        viewport.doLayout();
        setTimeout(function(){
            Ext.get('loading').remove();
            Ext.get('loading-mask').fadeOut({remove:true});
        }, 250);        
    });