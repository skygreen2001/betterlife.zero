    Ext.namespace("betterlife.admin");
    bb = betterlife.admin;  
    
    Ext.Loader.setConfig({enabled: true});
    Ext.Loader.setPath('Ext.ux', '../ux/');   
    Ext.require([
        'Ext.tab.*',
        'Ext.ux.TabCloseMenu',//Tab标题头右键菜单选项生成  
        'Ext.ux.TabScrollerMenu'
    ]);
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
            split: true,              
            layout:'fit',   
            collapsible: true, 
            autoShow:true,
            collapseMode: 'mini',
            title:'',
            margins: '0 0 1 0'
            }];
           
    //页面左部              
    bb.Layout.leftPanel=[{
                region: 'west',
                id: 'west-panel',
                title: '功能区',
                split: true,
                width: 200,
                minSize: 100,
                maxSize: 400,
                collapseMode: 'mini',
                collapsible: true,
                margins: '0 0 3 2',
                layout: {
                    type: 'accordion',
                    animate: true
                }
            }];          
        
    //页面内容区
    if (bb.Config.IsTabHeaderShow){             
        bb.Layout.centerPanel=Ext.createWidget('tabpanel',{                 
                    region: 'center', // a center region is ALWAYS required for border layout                
                    id: 'centerPanel',
                    activeTab: 1,     // first tab initially active
                    resizeTabs:true, // turn on tab resizing
                    minTabWidth: 105,
                    tabWidth:105,
                    enableTabScroll:true,
                    margins: '0 3 3 0',
                    defaults: {
                        autoScroll:true,
                        bodyPadding:10
                    },
                    items: [{
                        contentEl: 'centerArea',
                        title: '首页',
                        iconCls: 'tabs',
                        autoScroll: true
                    },{
                        //contentEl: 'center2',
                        title: '查询',
                        iconCls: 'tabs',
                        html:"<a id='hideit' href='#'>隐藏左侧</a><iframe scrolling='auto' width='100%' height='100%'  frameborder='0' src='http://www.g.cn'> </iframe>",
                        closable: true, 
                        autoScroll: true
                    }],                
                    plugins:[                 
                        Ext.create('Ext.ux.TabCloseMenu', {
                            extraItemsTail: [
                                '-',
                                {
                                    text: '关闭',
                                    checked: true,
                                    hideOnClick: true,
                                    handler: function (item) {
                                        currentItem.tab.setClosable(item.checked);
                                    }
                                }
                            ],
                            listeners: {
                                aftermenu: function () {
                                    currentItem = null;
                                },
                                beforemenu: function (menu, item) {
                                    var menuitem = menu.child('*[text="关闭"]');
                                    currentItem = item;
                                    menuitem.setChecked(item.closable);
                                }
                            }
                        }),{
                            ptype: 'tabscrollermenu',
                            maxText  : 15,
                            pageSize : 5
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
    }
    //页面右部    
    bb.Layout.rightPanel=[{
                region: 'east',
                id: 'right-panel',   
                title: '',
                collapsible: true,
                split: true,
                collapseMode: 'mini',
                width: 225, // give east and west regions a width
                minSize: 175,
                maxSize: 400,
                margins: '0 5 0 0',
                layout: 'fit', // specify layout manager for items
                items:            // this TabPanel is wrapped by another Panel so the title will be applied
                Ext.createWidget('tabpanel',{
                    border: false, // already wrapped so don't add another border
                    activeTab: 1, // second tab initially active
                    tabPosition: 'bottom',
                    items: [{
                        html: '<p>技术人员CMS框架的最爱.</p>',
                        title: 'BetterLife',
                        autoScroll: true
                    }, new Ext.grid.property.Grid({
                        title: '属性',
                        //resizable:true,
                        closable: true,
                        headerPosition:'top',
                        hideHeaders :true,
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
                region: 'south',
                id: 'footer-panel',   
                contentEl: 'south',
                collapseMode: 'mini',
                split: true,  
                layout: 'fit',
                height: 50,
                minSize: 100,
                maxSize: 200,
                collapsible: true,
                autoScroll:true,
                title: '状态栏',
                margins: '0 0 0 0'
            }];
        
        
        