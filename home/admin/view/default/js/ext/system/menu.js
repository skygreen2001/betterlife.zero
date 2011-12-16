Ext.namespace("Betterlife.Admin.Menu");

Bb = Betterlife.Admin;

/**
 * 菜单
 */
Bb.Menu={
	/**
	 * 全局配置
	 */
	Config:{
    /**
     *分页:每页显示记录数
     */        
    PageSize:10
	}
};       

/**
* 菜单分组数据模型
*/
Bb.Menu.GroupStore = new Ext.data.JsonStore({
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
* 菜单数据模型
*/
Bb.Menu.Store = new Ext.data.Store({
    id:'menuStore',    
    reader: new Ext.data.JsonReader({
		totalProperty: 'totalCount',
		successProperty: 'success',  
		root: 'data',       
		remoteSort: true,                
		fields : [{name: 'name',type: 'string'},
			{name: 'address',type: 'string'},  
			{name: 'lang',type: 'string'},  
			{name: 'iconCls',type: 'string'},  
			{name: 'title',type: 'string'}
       ]}         
	),
    writer: new Ext.data.JsonWriter({
		encode: false   // <-- don't return encoded JSON -- causes Ext.Ajax#request to send data using jsonData config rather than HTTP params
	}),
	listeners : {
		/**
		 * 保证分页也将查询条件带上
		 */			
		'beforeload' : function(store, options) {
			if (Ext.isReady) {
				Ext.apply(options.params, Bb.Library.Form.getForm().getValues());
			}
		}
	}    
});     
   
/**
* 菜单分组
*/       
Bb.Menu.GroupGrid = Ext.extend(Ext.grid.EditorGridPanel, {
	constructor : function(config) {
		config = Ext.apply({	
	    store: Bb.Menu.GroupStore,      
	    height:180,                 
	    layout: 'fit',
	    autoScroll:true,  
	    clicksToEdit: 2,
	    headerAsText:false,//是否显示标题栏   
	    plugins : [this.editor],
	    cm:this.cm,
    	stripeRows:true,  
    	sm:this.sm,
    	tbar: new Ext.Toolbar(['-', {
        ref: '../addBtn',
        text: '新增',
      	scope:this,	 
        iconCls: 'silk-add',
        handler:function() {
            var p = new Ext.data.Record({
                id:'',
                name:'',
                lang:'',
                iconCls:''
            });
            this.editor.stopEditing();
            this.store.insert(0,p);
            this.editor.startEditing(0, 0);
        }
      },'-', {
        text: '删除',
        disabled: true,
      	scope:this,	        
        iconCls: 'silk-delete',
        id: 'removeBtn',
        handler:function() {
            Ext.Msg.confirm('信息','确定要删除？',function(btn) {
                if(btn == 'yes') {     
                    var cell = this.getSelectionModel().getSelectedCell();   
                    var record = Bb.Menu.GroupStore.getAt(cell[0]);
                    Bb.Menu.GroupStore.remove(record);
                }
            },this);
        }
      }, '-'])}, config);
	  Bb.Menu.GroupGrid.superclass.constructor.call(this, config);
	},	
	/**
	 * 编辑器
	 */
	editor : new Ext.ux.grid.RowEditor({
		saveText : '修改',
		// 保证选择左侧的选择框时，不影响启动显示RowEdior
		onRowClick : function(g, rowIndex, e) {
			if (!e.getTarget('.x-grid3-row-checker')) {
				this.constructor.prototype.onRowClick.apply(this, arguments);
			}
		}// ,clicksToEdit: 2
	}),
    cm:new Ext.grid.ColumnModel([   
	    {header: "名称", width: 250, sortable: true, dataIndex: 'name', editor: new Ext.form.TextField({})},
	    {header: "标识", width: 250, sortable: true, dataIndex: 'id', editor: new Ext.form.TextField({})},
	    {header: "中英文", width: 250, sortable: true, dataIndex: 'lang',  editor: new Ext.form.ComboBox({
		    emptyText : '',
            mode : 'local',        
		    /**    
		     *  菜单分组中英文
		     */
            store : new Ext.data.SimpleStore({
			    fields : ['value', 'text'],
			    data : [['cn', 'cn'], ['en', 'en']]
		    }),  
            triggerAction : 'all',
            valueField : 'value',//值
            displayField : 'text'//显示文本
		})},
	    {header: "图标类", width: 250, sortable: true, dataIndex: 'iconCls', editor: new Ext.form.TextField({})}		    
	]),  
    sm: new Ext.grid.RowSelectionModel({
      singleSelect: true,
      scope:this,
      listeners: {
          rowselect: function(sm, row, rec) {
              //Bb.Menu.Store.load();      
              if (rec.data.id){    
                  /**
                   * 菜单分组查询分组表单
                   */                              
                  MenuService.GetMenusByGroupId(rec.data.id,function(provider, response){    
                      var result= new Array();
                      result['data']=response.result.data;                                       
                      Bb.Menu.Store.loadData(result);          
                  });              
              }                      
          },
          ////判断删除按钮是否可以激活
          selectionchange:function(sm) {
          	Ext.getCmp('removeBtn').setDisabled(sm.getCount() < 1);
          }
      }
  })                         
});   

/**
 * 主程序
 */
Ext.onReady(function(){
    Ext.QuickTips.init();
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
    Ext.Direct.addProvider(Ext.app.REMOTING_API);  
              
    Bb.Menu.Store.proxy=new Ext.data.DirectProxy({ 
        api: {
           read:MenuService.QueryPageMenu
        }
    });       
        
    /**
     * 菜单查询Form
     */
    Bb.Menu.Form = new Ext.form.FormPanel({      
        labelAlign: 'left',      
        labelWidth:64,   
        frame:false,   
        height:55,
        bodyStyle : 'padding:5px 5px 0',                         
        headerAsText:false,//是否显示标题栏              
        api: {
            // The server-side must mark the submit handler a-s a 'formHandler'
            submit: MenuService.QueryPageMenuForm//表单数据提交，config.php中要加'formHandler'=>true
        },  
        items:[{
            xtype: 'fieldset',    
            layout:"column",       
            style:'border:0;padding-bottom:0;margin-top:5',    
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
                }]},{                    
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
                    value: Bb.Menu.Config.PageSize
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
                                    //Bb.Menu.Store.load({
                                    //  params:params   
                                    //});                    
                                    Bb.Menu.Form.getForm().submit({
                                        success:function(form, action) {//表单提交成功后,调用的函数.参数分为两个,一个是提交的表单对象,另一个是JSP返回的参数值对象
                                            var result= new Array();
                                            result['data']=action.result.data;
                                            result['totalCount']=action.result.totalCount;   
                                            Bb.Menu.Store.loadData(result);
                                        },
                                        failure: function(form, action) {
                                            Ext.Msg.alert('提示', '查询失败！');
                                        }
                                    });
                                });
                            }
                        }   
                    }]
                }         
            ]     
        }]
    });  
                                                                 
    /**
     * 菜单Grid
     */
    Bb.Menu.Grid = new Ext.grid.GridPanel({  
        region: 'center',// a center region is ALWAYS required for border layout    
        contentEl: 'center',    
        store: Bb.Menu.Store,      
        height:460,                 
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
                width    : 400, 
                dataIndex: 'address'
            },
            {
                header: '说明', 
                sortable: true,
                width    : 200,  
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
        tbar:{
			xtype : 'container',
			layout : 'anchor',  
			height : 80, 
			defaults : {  
				anchor : '100%'
			},        
            items : [
                Bb.Menu.Form,
                new Ext.Toolbar({  
                    height : 27,    
			        items : [{
					        ref : '../reverseSelectBtn',
					        scope: this,  
					        text : '反选',
					        handler : this.onReverseSelect
				        }, {
					        xtype : 'tbseparator'
				        }, {
					        ref : '../addBtn',
					        scope: this,  
					        text : '新增',
					        iconCls : 'silk-add'
				        }, {
					        xtype : 'tbseparator'
				        }, {
					        id : 'removeButton',
					        scope: this,  
					        text : '删除',
					        disabled : true,
					        iconCls : 'silk-delete'
				        }, {
					        xtype : 'tbseparator'
				        }, {
					        ref : '../saveBtn',
					        scope: this,  
					        iconCls : 'icon-user-save',
					        text : '提交'
				        }, {
					        xtype : 'tbseparator'
				    }]
        })]},
        bbar: new Ext.PagingToolbar({
            pageSize: Bb.Menu.Config.PageSize,
            store: Bb.Menu.Store,
            autoShow:true,
            displayInfo: true,
            displayMsg: '当前显示 {0} - {1}条记录/共 {2}条记录。',
            emptyMsg: "无显示数据"
        })                             
    }); 
          
    /**
     * 菜单分组所在的容器面板
     */    
    Bb.Menu.GroupPanel=new Ext.Panel({
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
            new Bb.Menu.GroupGrid()
        ] 
    });       

    Bb.Menu.Viewport = new Ext.Viewport({
        layout: 'border',   
        items: [
          Bb.Menu.GroupPanel,   
          Bb.Menu.Grid
        ]
    });
    
    Bb.Menu.Viewport.doLayout();
    
    //菜单分组初始化
    MenuService.AllMenuGroup(function(provider, response){           
        Bb.Menu.GroupStore.loadData(response.result.data);                                   
    });     
    
    setTimeout(function(){
        Ext.get('loading').remove();
        Ext.get('loading-mask').fadeOut({
            remove:true
        });
    }, 250);
});
                     