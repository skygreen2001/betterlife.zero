Ext.namespace("betterlife.admin");

bb = betterlife.admin;
       
/**
* 菜单对象        
*/    
bb.Menu=Ext.emptyFn;  
/**
* 菜单分组Data Store
*/
bb.Menu.MenuGroupStore = new Ext.data.JsonStore({
    fields: [
    {
        name: 'id',
        type: 'string'
    },
    {
        name: 'name',
        type: 'string'
    },
    {
        name: 'lang',
        type: 'string'
    },
    {
        name: 'iconCls',
        type: 'string'
    }]
});  
/**
* 菜单Data Store
*/
bb.Menu.MenuStore = new Ext.data.JsonStore({
    fields: [
    {
        name: 'name',
        type: 'string'
    },
    {
        name: 'address',
        type: 'string'
    },
    {
        name: 'lang',
        type: 'string'
    },
    {
        name: 'iconCls',
        type: 'string'
    },  
    {
        name: 'title',
        type: 'string'
    }]
});      
/**
* 菜单分组Grid
*/
bb.Menu.MenuGroupGrid = new Ext.grid.GridPanel({
    store: bb.Menu.MenuGroupStore,     
    title:'菜单分组', 
    frame: true,                   
    collapsible: true, 
    collapseMode: 'mini',          
    columns: [
        {
            header: '标识',
            sortable: true,
            dataIndex: 'id'
        },///renderer: pctChange  格式化列，类似labelFunction
        {
            header: '名称',
            sortable: true,
            dataIndex: 'name'
        },
        {
            header: '中英文',
            sortable: true,
            dataIndex: 'lang'
        },
        {
            header: '图标类',
            sortable: true,
            dataIndex: 'iconCls'    
        }
    ],
    sm: new Ext.grid.RowSelectionModel({
        singleSelect: true,
        listeners: {
            rowselect: function(sm, row, rec) {
            }
        }
    }),
    stripeRows: true,                                                                                             
    height:180,
    autoHeight:true,
    // config options for stateful behavior
    headerAsText:false
});        
/**
 * 菜单分组查询Form表单
 */
Ext.onReady(function(){
    Ext.QuickTips.init();
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
    Ext.Direct.addProvider(Ext.app.REMOTING_API);  
    
    bb.Menu.MenuForm = new Ext.form.FormPanel({
        labelAlign: 'left',
        frame:true,//True表示为面板的边框外框可自定义的，false表示为边框可1px的点线,这个属性很重要，不显式地设置为true时，FormPanel中的指定的items和buttons的背景色会不一致
        labelWidth: 55,
        headerAsText:false,//是否显示标题栏
        bodyStyle:'padding:5px 5px 0',
        height:85,
        api: {
            // The server-side must mark the submit handler a-s a 'formHandler'
            submit: MenuService.AllMenuGroup//表单数据提交，config.php中要加'formHandler'=>true
        },
        items: [{
            layout:'column',
            items:[{
                columnWidth:.33,
                layout:'form',
                items:[{
                    fieldLabel: '标识',
                    anchor:'90%',
                    xtype:'textfield',
                    name: 'id'

                }]
            },{
                columnWidth:.33,
                layout:'form',
                items:[{
                    fieldLabel: '名称',
                    anchor:'90%',
                    xtype:'textfield',
                    name: 'name'

                }]
            },
            {
                columnWidth:.33,
                layout:'form',
                items:[{
                    xtype:'button',
                    id:'btn',
                    text:'搜索',
                    width:100,
                    style:'cursor:pointer;margin:0 0 0 100px',
                    listeners:{
                        render:function(){
                            Ext.fly(this.el).on('click',function(){
                                bb.Menu.MenuGroupForm.getForm().submit({
                                    success:function(form, action) {//表单提交成功后,调用的函数.参数分为两个,一个是提交的表单对象,另一个是JSP返回的参数值对象
                                        bb.Menu.MenuGroupStore.loadData(action.result.data);
                                    },
                                    failure: function(form, action) {
                                        Ext.Msg.alert('提示', '保存成功');
                                    }
                                });
                            });
                        }
                    }
                }]
            }]            
        }]
    });  
    
    bb.Menu.centerPanel=new Ext.Panel({
            region: 'center',// a center region is ALWAYS required for border layout
            id: 'Menu_centerPanel',         
            contentEl: 'center',     
            defaults:{
                margins:'5 5 5 5'
            },//定义边距与间距
            layout:{
                type:'vbox',
                align:'stretch'
            },
            items:[bb.Menu.MenuGroupGrid] //,bb.Menu.MenuForm
        });
        
    var viewport = new Ext.Viewport({
        layout: 'border',   
        items: [
          bb.Menu.centerPanel
        ]
    });
    
    viewport.doLayout();
    
    MenuService.AllMenuGroup(function(provider, response){           
        bb.Menu.MenuGroupStore.loadData(response.result.data);                                   
    });
    
//    bb.Menu.MenuGroupForm.getForm().submit({
//        success:function(form, action) {
//            bb.Menu.MenuGroupStore.loadData(action.result.data);
//        },
//        failure: function(form, action) {
//            Ext.Msg.alert('提示', '查询出现故障！');
//        }
//   });
    
    setTimeout(function(){
        Ext.get('loading').remove();
        Ext.get('loading-mask').fadeOut({
            remove:true
        });
    }, 250);
});
                     