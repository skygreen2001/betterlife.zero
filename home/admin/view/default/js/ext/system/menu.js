Ext.namespace("betterlife.admin.menu");

bb = betterlife.admin.menu;

/**
 * 全局配置
 */
bb.Config=Ext.emptyFn;
bb.Config={
    /**
     *分页:每页显示记录数
     */        
    pageSize:10
};       
      
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

var MenuReader = new Ext.data.JsonReader
(
   {
        totalProperty: 'totalCount',
        successProperty: 'success',  
        root: 'data',       
        remoteSort: true,                                                     
    }, 
    [                                     
        {name: 'name',type: 'string'},
        {name: 'address',type: 'string'},  
        {name: 'lang',type: 'string'},  
        {name: 'iconCls',type: 'string'},  
        {name: 'title',type: 'string'}
    ]    
);
// The new DataWriter component.
var MenuWriter = new Ext.data.JsonWriter({
    encode: false   // <-- don't return encoded JSON -- causes Ext.Ajax#request to send data using jsonData config rather than HTTP params
});
/**
* 菜单Data Store
*/
bb.Menu.MenuStore = new Ext.data.Store({
    id:'menuStore',    
    reader: MenuReader,
    writer: MenuWriter    
});     

/**    
*  菜单分组中英文
*/
var langStore = new Ext.data.SimpleStore({
    fields : ['value', 'text'],
    data : [['cn', 'cn'], ['en', 'en']]
});      
var combo = new Ext.form.ComboBox({
        emptyText : '',
        mode : 'local',
        store : langStore,  
        triggerAction : 'all',
        valueField : 'value',//值
        displayField : 'text'//显示文本
});
var MenuGroupGridCM=new Ext.grid.ColumnModel([   
    {header: "名称", width: 250, sortable: true, dataIndex: 'name', editor: new Ext.form.TextField({})},
    {header: "标识", width: 250, sortable: true, dataIndex: 'id', editor: new Ext.form.TextField({})},
    {header: "中英文", width: 250, sortable: true, dataIndex: 'lang',  editor: combo},
    {header: "图标类", width: 250, sortable: true, dataIndex: 'iconCls', editor: new Ext.form.TextField({})},
    
]);
 
/**
* 菜单分组Grid
*/       
bb.Menu.MenuGroupGrid = new Ext.grid.EditorGridPanel({
    store: bb.Menu.MenuGroupStore,      
    height:180,                 
    layout: 'fit',
    autoScroll:true,  
    clicksToEdit: 2,
    headerAsText:false,//是否显示标题栏         
    cm:MenuGroupGridCM,
                     
    stripeRows:true,     
    tbar: new Ext.Toolbar(['-', {
        ref: '../addBtn',
        text: '新增',
        iconCls: 'silk-add',
        handler:function() {
            var p = new Ext.data.Record({
                id:'',
                name:'',
                lang:'',
                iconCls:''
            });
            bb.Menu.MenuGroupGrid.stopEditing();
            bb.Menu.MenuGroupStore.insert(0,p);
            bb.Menu.MenuGroupGrid.startEditing(0, 0);
        }
        },'-', {
        ref: '../removeBtn',
        text: '删除',
        disabled: true,
        iconCls: 'silk-delete',
        handler:function() {
            Ext.Msg.confirm('信息','确定要删除？',function(btn) {
                if(btn == 'yes') {
                    var sm = grid.getSelectionModel();
                    var cell = sm.getSelectedCell();   
                    var record = store.getAt(cell[0]);
                    store.remove(record);
                }
            });
        }
        }, '-']),
    sm: new Ext.grid.RowSelectionModel({
        singleSelect: true,
        listeners: {
            rowselect: function(sm, row, rec) {
                //bb.Menu.MenuStore.load();      
                if (rec.data.id){    
                    /**
                     * 菜单分组查询分组表单
                     */                              
                    MenuService.GetMenusByGroupId(rec.data.id,function(provider, response){    
                        var result= new Array();
                        result['data']=response.result.data;                                       
                        bb.Menu.MenuStore.loadData(result);          
                    });              
                }                      
            }
        }
    })                         
});   

Ext.onReady(function(){
    Ext.QuickTips.init();
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
    Ext.Direct.addProvider(Ext.app.REMOTING_API);  

    bb.Menu.MenuProxy=new Ext.data.DirectProxy({ 
        api: {
           read:MenuService.QueryPageMenu
        }
    });                                    
    bb.Menu.MenuStore.proxy=bb.Menu.MenuProxy;       
    
    bb.Menu.MenuStore.on('beforeload', function(store,options){
        var params=bb.Menu.MenuForm.getForm().getValues();             
        Ext.apply(options.params,params);
    });
           
    bb.Menu.MenuForm = new Ext.form.FormPanel({      
        labelAlign: 'left',      
        labelWidth:64,   
        height:20,
        frame:true,      
        autoHeight:true,                         
        headerAsText:false,//是否显示标题栏     
        defaults: {          
            anchor:'0',    
        },                                 
        api: {
            // The server-side must mark the submit handler a-s a 'formHandler'
            submit: MenuService.QueryPageMenuForm//表单数据提交，config.php中要加'formHandler'=>true
        },
        items:[{
            xtype: 'fieldset',    
            layout:"column",       
            style:'border:0;padding-bottom:0',    
            autoHeight:true,    
            collapsible: false,   
            defaults: {
                flex  : 1
            },                                
            items: [{                  
                    anchor:"90%",
                    xtype:"container",
                    autoEl:{},   
                    labelAlign: 'right',           
                    labelWidth:40,   
                    layout:"form",  
                    items:[{
                        fieldLabel: '名称',
                        xtype:"textfield",     
                        labelStyle: 'white-space:nowrap;',
                        width: 260,    
                        name: 'name'    
                    }]
                },{                    
                    layout:"form",  
                    anchor:"90%",
                    xtype:"container",
                    autoEl:{},    
                    items:[{
                        fieldLabel: '&nbsp;&nbsp;菜单地址',         
                        xtype:"textfield",
                        labelStyle: 'white-space:nowrap',
                        width: 260,     
                        name: 'address'
                    }]   
                },{
                    xtype: 'hidden',
                    id: 'limit',
                    name: 'limit',
                    value: bb.Config.pageSize
                },{                    
                    layout:'form',
                    anchor:"90%",
                    xtype:"container",
                    autoEl:{},    
                    items:[{                    
                        xtype:'button',
                        id:'btn',
                        text:'搜索',     
                        width:100,    
                        style:'align:left;cursor:pointer;margin-left:5px;',
                        listeners:{
                            render:function(){
                                Ext.fly(this.el).on('click',function(){
                                    //bb.Menu.MenuStore.load({
                                    //  params:params   
                                    //});           
                                                             
                                    bb.Menu.MenuForm.getForm().submit({
                                        success:function(form, action) {//表单提交成功后,调用的函数.参数分为两个,一个是提交的表单对象,另一个是JSP返回的参数值对象
                                            var result= new Array();
                                            result['data']=action.result.data;
                                            result['totalCount']=action.result.totalCount;   
                                            bb.Menu.MenuStore.loadData(result);
                                        },
                                        failure: function(form, action) {
                                            Ext.Msg.alert('提示', '查询失败！');
                                        }
                                    });
                                });
                            }
                        }   
                    }]      
                }]     
            }]
    });  
                                                                 
    /**
    * 菜单Grid
    */
    bb.Menu.MenuGrid = new Ext.grid.GridPanel({  
        region: 'center',// a center region is ALWAYS required for border layout    
        contentEl: 'center',    
        store: bb.Menu.MenuStore,      
        height:440,                 
        layout: 'fit',  
        autoScroll:true,
                split: true, 
                frame: true,  
        headerAsText:false,//是否显示标题栏                                                                                                                
        columns: [    
            {
                header: '名称',
                sortable: true,
                width    : 200,  
                dataIndex: 'name'
            },           
            {
                header: '菜单地址',
                sortable: true,
                width    : 600, 
                dataIndex: 'address'
            },
            {
                header: '说明', 
                sortable: true,
                width    : 450,  
                dataIndex: 'title'
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
        tbar:bb.Menu.MenuForm,
        bbar: new Ext.PagingToolbar({
            pageSize: bb.Config.pageSize,
            store: bb.Menu.MenuStore,
            autoShow:true,
            displayInfo: true,
            displayMsg: '当前显示 {0} - {1}条记录/共 {2}条记录。',
            emptyMsg: "无显示数据"
        })                             
    });       
    /**
    * 菜单分组所在的容器面板
    */    
    bb.Menu.menuGroupPanel=new Ext.Panel({
            region: 'north',// a center region is ALWAYS required for border layout
            id: 'menuGroupPanel',     
            title:'菜单分组',      
            contentEl: 'top',   
            height:190,     
            split: true, 
            frame: true,       
            collapseMode: 'mini',              
            collapsible: true,  
            autoScroll:true,    
            defaults:{
                margins:'5 5 5 5'
            },//定义边距与间距
            layout:{
                type:'fit'
            },
            items:[
                bb.Menu.MenuGroupGrid
            ] //,bb.Menu.MenuForm
        });       

    var viewport = new Ext.Viewport({
        layout: 'border',   
        items: [
          bb.Menu.menuGroupPanel,   
          bb.Menu.MenuGrid
        ]
    });
    
    viewport.doLayout();
    
    //菜单分组初始化
    MenuService.AllMenuGroup(function(provider, response){           
        bb.Menu.MenuGroupStore.loadData(response.result.data);                                   
    });     
    
    setTimeout(function(){
        Ext.get('loading').remove();
        Ext.get('loading-mask').fadeOut({
            remove:true
        });
    }, 250);
});
                     