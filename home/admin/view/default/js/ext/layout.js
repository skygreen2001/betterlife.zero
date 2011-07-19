    Ext.namespace("betterlife.admin");
    bb = betterlife.admin;  
    
    bb.Layout=Ext.emptyFn;
    /**
    * 页面布局格局
    */
    //页面头部
    bb.Layout.headerPanel=[{                    
            region:'north', 
            contentEl: 'header',          
            id: 'header-panel',
            height:120,
            collapseMode: 'mini',
            split: true,              
            layout:'fit',   
            collapsible: true, 
            autoShow:true,
            title:'',
            margins: '0 0 1 0'
            }];    
           
    //页面左部              
    bb.Layout.leftPanel=[{
                region: 'west',
                id: 'west-panel', // see Ext.getCmp() below
                title: '功能区',
                collapseMode: 'mini',                
                split: true,
                width: 200,
                minSize: 100,
                maxSize: 400,
                collapsible: true,
                margins: '0 0 3 3',
                layout: {
                    type: 'accordion',
                    animate: true
                }
            }];   

    //页面内容区
    if (bb.Config.IsTabHeaderShow){
        bb.Layout.centerPanel=new Ext.TabPanel({
                    region: 'center', // a center region is ALWAYS required for border layout                
                    id: 'centerPanel',
                    deferredRender: false,       
                    activeTab: 1,     // first tab initially active
                    resizeTabs:true, // turn on tab resizing
                    minTabWidth: 115,           
                    tabWidth:135,
                    enableTabScroll:true,
                    margins: '0 3 3 0',
                    defaults: {autoScroll:true},
                    plugins: [
                        //Ext.ux.AddTabButton,                    
                        new Ext.ux.TabCloseMenu(),//Tab标题头右键菜单选项生成
                        //Tab标题头右侧下拉选择指定页面
                        new Ext.ux.TabScrollerMenu({                
                            maxText  : 15,
                            pageSize : 5
                        })
                    ],
                   //addTabText: '+',
    //               createTab: function() { // Optional function which the plugin uses to create new tabs
    //                    return {
    //                        title: '网站收藏',
    //                        closable: true,
    //                        layout: 'fit',
    //                        autoScroll:true,
    //                        border:false,
    //                        html:"<iframe scrolling='auto' width='100%' height='100%'  frameborder='0' src='http://www.stumbleupon.com/favorites/reviews/'> </iframe>"                    
    //                    };
    //                },
                    items: [{
                            contentEl: 'centerArea',
                            title: '首页',     
                            iconCls:'tabs',           
                            autoScroll: true
                        }, {
                            title: '查询', 
                            html:"<a id='hideit' href='#'>隐藏左侧</a><iframe scrolling='auto' width='100%' height='100%'  frameborder='0' src='http://www.g.cn'> </iframe>",
                            closable: true, 
                            iconCls:'tabs', 
                            autoScroll: true
                    }]
        });
    }else{ 
        bb.Layout.centerPanel=[{                    
            region:'center', 
            contentEl: 'centerArea',          
            id: 'centerPanel',
            height:'100%',         
            layout:'fit',   
            title:'',
            margins: '0 3 3 0'
            }];   
//        bb.Layout.centerPanel=new Ext.Panel({
//            region: 'center', // a center region is ALWAYS required for border layout                
//            id: 'centerPanel',
//            iconCls:'tabs',   
//            title:'首页',
//            deferredRender: false,   
//            defaults: {
//                autoScroll:true,
//                margins:'5 5 5 5'
//            },
//            items: [{
//                contentEl: 'centerArea',     
//                autoScroll: true,             
//                layout: 'fit'
//            }]
//        })    
    }
        
    //页面右部    
    bb.Layout.rightPanel=[{
                region: 'east',
                id: 'right-panel',   
                title: '',
                collapsible: true,
                collapseMode: 'mini',
                split: true,
                width: 225, // give east and west regions a width
                minSize: 175,
                maxSize: 400,
                margins: '0 5 0 0',
                layout: 'fit', // specify layout manager for items
                items:            // this TabPanel is wrapped by another Panel so the title will be applied
                new Ext.TabPanel({
                    border: false, // already wrapped so don't add another border
                    activeTab: 1, // second tab initially active
                    tabPosition: 'bottom',
                    items: [{
                        html: '<p>技术人员CMS框架的最爱.</p>',
                        title: 'BetterLife',
                        autoScroll: true
                    }, new Ext.grid.PropertyGrid({
                        title: '属性',
                        closable: true,
                        source: {
                            "(名称)": "BetterLife",
                            "友好": true,
                            "专业": true,
                            "专注": true,
                            "创建时间": new Date(Date.parse('06/01/2010')),
                            "邮箱": "skygreen2001@gmail.com",
                            "版本": 1.0,
                            "介绍": "提供专业服务、并向企业客户提供IT服务为重点的盈利公司。"
                        }
                    })]
                })
            }];
            
    //页面底部                                                   
    bb.Layout.footerPanel=[{
                // lazily created panel (xtype:'panel' is default)
                region: 'south',
                id: 'footer-panel',   
                contentEl: 'south',
                split: true, 
                collapseMode: 'mini',                
                layout: 'fit',
                height: 50,
                minSize: 100,
                maxSize: 200,
                collapsible: true,
                autoScroll:true,
                title: '状态栏',
                margins: '0 0 0 0'
            }];
