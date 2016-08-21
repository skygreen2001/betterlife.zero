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
* Model:菜单数据模型
*/
Bb.Menu.Store = {
    /**
    * 菜单分组数据模型
    */
    groupStore:new Ext.data.JsonStore({
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
    }),
    /**
    * 菜单数据模型
    */
    menuStore:new Ext.data.Store({
        id:'menuStore',
        reader: new Ext.data.JsonReader({
            totalProperty: 'totalCount',
            successProperty: 'success',
            root: 'data',
            remoteSort: true,
            fields : [{name: 'name',type: 'string'},
                {name: 'address',type: 'string'},
                {name: 'menuGroup_id',type: 'string'},
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
                    Ext.apply(options.params, Bb.Menu.View.GetForm.getForm().getValues());
                }
            }
        }
    })
}

/**
* View:菜单显示组件
*/
Bb.Menu.View={
    /**
    * 当前选择的菜单分组编号
    */
    current_menuGroup_id:'',
    /**
    * 当前创建的新建窗口
    */
    current_savewindow:null,
    /**
    * 菜单分组
    */
    GroupGrid:Ext.extend(Ext.grid.GridPanel, {//EditorGridPanel
        constructor : function(config) {
            config = Ext.apply({
                store: Bb.Menu.Store.groupStore,
                height:180,
                layout: 'fit',
                autoScroll:true,
                // clicksToEdit: 2,
                headerAsText:false,//是否显示标题栏
                // plugins : [this.editor],
                cm:this.cm,
                stripeRows:true,
                sm:this.sm //,
                // tbar: new Ext.Toolbar(['-', {
                //     ref: '../addBtn',
                //     text: '新增',
          //             scope:this,
                //     iconCls: 'icon-add',
                //     handler:function() {
                //         var p = new Ext.data.Record({
                //             id:'',
                //             name:'',
                //             lang:'',
                //             iconCls:''
                //         });
                //         this.editor.stopEditing();
                //         this.store.insert(0,p);
                //         this.editor.startEditing(0, 0);
                //     }
                // },'-', {
                //     text: '删除',
                //     disabled: true,
                //         scope:this,
                //     iconCls: 'icon-delete',
                //     id: 'removeBtn',
                //     handler:function() {
                //         Ext.Msg.confirm('信息','确定要删除？',function(btn) {
                //             if(btn == 'yes') {
                //                 var deleteRow = this.getSelectionModel().getSelected();
                //                 // var record = Bb.Menu.Store.groupStore.getAt(0);
                //                 Bb.Menu.Store.groupStore.remove(deleteRow);
                //             }
                //         },this);
                //     }
                // },'-'
                // ,{
                //     ref : '../saveBtn',
                //     scope: this,
                //     iconCls : 'icon-commit',
                //     text : '提交'
                // },'-'
                // ])
            }, config);
            Bb.Menu.View.GroupGrid.superclass.constructor.call(this, config);
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
            },
            listeners:{
                afteredit:function(roweditor, changes, record, rowIndex){
                    // alert(record.data.name);
                 }
            }
            // ,clicksToEdit: 2
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
                      Bb.Menu.View.current_menuGroup_id=rec.data.id;
                      // Bb.Menu.View.SaveForm.cmenuGroup_id.setValue(rec.data.id);

                      Bb.Menu.View.SaveForm.cmenuGroup_id.setValue(rec.data.id);
                      Bb.Menu.View.SaveForm.menuGroup_id.setValue(rec.data.id);
                      this.grid.getMenusByGroupId(rec.data.id);
                  }
              },
              ////判断删除按钮是否可以激活
              selectionchange:function(sm) {
                  Ext.getCmp('removeBtn').setDisabled(sm.getCount() < 1);
              }
          }
        }),
        getMenusByGroupId:function($menugroup_id){
          /**
           * 菜单分组查询分组表单
           */
          ServiceMenu.getMenusByGroupId($menugroup_id,function(provider, response){
              var result= new Array();
              result['data']=response.result.data;
              Bb.Menu.Store.menuStore.loadData(result);
          });
        }
    }),

    /**
     * 菜单分组所在的容器面板
     */
    GroupPanel:Ext.extend(Ext.Panel,{
        constructor : function(config) {
            config = Ext.apply({
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
                    new Bb.Menu.View.GroupGrid({id:'groupGrid'})
                ]
            });
            Bb.Menu.View.GroupPanel.superclass.constructor.call(this, config);
        }
    }),

    /**
     * 菜单查询Form
     */
    GetForm:new Ext.form.FormPanel({
        labelAlign: 'left',
        labelWidth:64,
        frame:false,
        height:55,
        bodyStyle : 'padding:5px 5px 0',
        headerAsText:false,//是否显示标题栏
        api: {},
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
                    anchor:"80%",
                    xtype:"container",
                    items:[{
                        xtype:'button',
                        id:'btn',
                        text:'搜索',
                        width:80,
                        scope: this,
                        style:'align:left;cursor:pointer;margin-left:5px;',
                        listeners:{
                            render:function(){
                                Ext.fly(this.el).on('click',function(){
                                    //Bb.Menu.Store.load({
                                    //  params:params
                                    //});
                                    Bb.Menu.View.GetForm.getForm().submit({
                                        success:function(form, action) {//表单提交成功后,调用的函数.参数分为两个,一个是提交的表单对象,另一个是JSP返回的参数值对象
                                            var result= new Array();
                                            result['data']=action.result.data;
                                            result['totalCount']=action.result.totalCount;
                                            Bb.Menu.Store.menuStore.loadData(result);
                                        },
                                        failure: function(form, action) {
                                            Ext.Msg.alert('提示', '查询失败！');
                                        }
                                    });
                                },this);
                            }
                        }}]
                },{
                    layout:'form',
                    anchor:"80%",
                    style : 'padding:0 5px 0 3px',
                    xtype:"container",
                    items:[{
                            xtype : 'button',
                            id : 'reset',
                            text : '重置',
                            width : 80,
                            handler : function() {
                                Bb.Menu.View.GetForm.getForm().reset();
                            }
                    }]
                }
            ]
        }]
    }),

    /**
     * 菜单Grid
     */
    Grid:Ext.extend(Ext.grid.GridPanel,{
        constructor : function(config) {
            config = Ext.apply({
                region: 'center',// a center region is ALWAYS required for border layout
                contentEl: 'center',
                store: Bb.Menu.Store.menuStore,
                height:460,
                layout: 'fit',
                autoScroll:true,
                split: true,
                frame: true,
                headerAsText:false,//是否显示标题栏
                stripeRows: true,
                cm : new Ext.grid.ColumnModel({
                    columns : [
                        this.sm,
                        {
                            header: '名称',
                            sortable: true,
                            width    : 200,
                            dataIndex: 'name'
                        },
                        {
                            header: '菜单分组',
                            sortable: true,
                            width    : 200,
                            dataIndex: 'menuGroup_id'
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
                    ]
                }),
                tbar:{
                    xtype : 'container',
                    layout : 'anchor',
                    height : 80,
                    defaults : {
                        anchor : '100%'
                    },
                    items : [
                        Bb.Menu.View.GetForm,
                        new Ext.Toolbar({
                            height : 27,
                            items : [{
                                    text: '反选',
                                    iconCls : 'icon-reverse',
                                    scope: this,
                                    handler: function(){
                                        this.onReverseSelect();
                                    }
                                }, '-',{
                                    ref : '../../addBtn',
                                    scope: this,
                                    text : '新增',
                                    iconCls : 'icon-add',
                                    handler : this.onAdd
                                }, '-',{
                                    ref : '../../editBtn',
                                    scope: this,
                                    text : '修改',
                                    disabled : true,
                                    iconCls : 'icon-edit',
                                    handler: function(){
                                        this.onUpdate();
                                    }
                                }, '-',{
                                    id : 'removeButton',
                                    scope: this,
                                    text : '删除',
                                    disabled : true,
                                    iconCls : 'icon-delete',
                                    handler: function(){
                                        this.deleteMenu();
                                    }
                                },
                                // '-',{
                                //     ref : '../../saveBtn',
                                //     scope: this,
                                //     iconCls : 'icon-commit',
                                //     text : '提交'
                                // },
                                '-']
                })]},
                bbar: new Ext.PagingToolbar({
                    pageSize: Bb.Menu.Config.PageSize,
                    store: Bb.Menu.Store.menuStore,
                    autoShow:true,
                    displayInfo: true,
                    displayMsg: '当前显示 {0} - {1}条记录/共 {2}条记录。',
                    emptyMsg: "无显示数据"
                })
            }, config);

            //菜单分组初始化
            ServiceMenuGroup.allMenuGroup(function(provider, response){
                Bb.Menu.Store.groupStore.loadData(response.result.data);
            });
            Bb.Menu.View.Grid.superclass.constructor.call(this, config);
        },
        /**
         * 行选择器
         */
        sm : new Ext.grid.CheckboxSelectionModel({
            // handleMouseDown : Ext.emptyFn,
            listeners : {
                'rowselect' : function(sm, rowIndex, record) {
                    // console.log('rowselect',rowIndex)
                },
                'rowdeselect' : function(sm, rowIndex, record) {
                    // console.log('rowdeselect',rowIndex)
                },
                'selectionchange' : function(sm) {
                    // 判断删除按钮是否可以激活
                    Ext.getCmp("removeButton").setDisabled(sm.getCount() < 1);
                    this.grid.editBtn.setDisabled(sm.getCount() != 1);
                    // console.log('selectionchange',sm.getSelections().length);
                }
            }
        }),
        /**
         * 反选
         */
        onReverseSelect:function() {
            for (var i = this.getView().getRows().length - 1; i >= 0; i--) {
                if (this.sm.isSelected(i)) {
                    this.sm.deselectRow(i);
                }else {
                    this.sm.selectRow(i, true);
                }
            }
        },
        /**
         * 新增
         */
        onAdd : function(btn, ev) {
            if (!Bb.Menu.View.current_savewindow){
                Bb.Menu.View.current_savewindow=new Bb.Menu.View.SaveWindow();
            }
            Bb.Menu.View.current_savewindow.saveBtn.setText('保 存');
            Bb.Menu.View.current_savewindow.setTitle('添加菜单');
            Bb.Menu.View.current_savewindow.savetype=0;
            Bb.Menu.View.current_savewindow.show();
        },
        /**
         * 修改
         */
        onUpdate : function(btn,ev) {
            if (!Bb.Menu.View.current_savewindow){
                Bb.Menu.View.current_savewindow=new Bb.Menu.View.SaveWindow();
            }
            Bb.Menu.View.current_savewindow.editForm.form.loadRecord(this.getSelectionModel().getSelected());
            Bb.Menu.View.current_savewindow.savetype=1;
            Bb.Menu.View.current_savewindow.saveBtn.setText('修 改');
            Bb.Menu.View.current_savewindow.setTitle('修改菜单');
            Bb.Menu.View.current_savewindow.show();

        },
        /**
         * 删除菜单
         */
        deleteMenu : function() {
            Ext.Msg.confirm('提示', '确认要删除所选的博客吗?', this.confirmDeleteMenu,this);
        },
        /**
         * 确认删除菜单
         */
        confirmDeleteMenu : function(btn) {
            if (btn == 'yes') {
                var del_menu_ids ="";
                var selectedRows    = this.getSelectionModel().getSelections();
                for ( var flag = 0; flag < selectedRows.length; flag++) {
                    del_menu_ids=del_menu_ids+selectedRows[flag].data.name+",";
                }
                var del_menugroup_id=selectedRows[0].data.menuGroup_id;
                ServiceMenu.deleteByIds(del_menugroup_id,del_menu_ids);
                Ext.Msg.alert("提示", "删除成功！");
                Ext.getCmp('groupGrid').getMenusByGroupId(Bb.Menu.View.current_menuGroup_id);
            }
        }
    }),

    /**
     * 表单：新建菜单
     */
    SaveForm:new Ext.form.FormPanel({
        labelWidth : 80,
        width : 350,ref:'editForm',
        bodyStYle : 'padding:5px 5px 0',
        labelAlign : "center",
        align : "center",
        autoWidth : true,
        api: {},
        defaults : {
            xtype : 'textfield',
            width : 270
        },
        items : [{
            fieldLabel : '菜单分组',
            name : 'cmenuGroup_id',
            ref:'cmenuGroup_id',
            xtype:'displayfield'
        },
        {
            name : 'menuGroup_id',
            ref:'menuGroup_id',
            xtype:'hidden'
        },
        {
            fieldLabel : '名称(<font color=red>*</font>)',
            allowBlank : false,
            name : 'name'
        },
          //       {
        //     id : 'cmenuGroup_id',
        //     name : 'menuGroup_id',
        //     ref:'cmenuGroup_id',
        //     fieldLabel : '菜单分组(<font color=red>*</font>)',
        //     xtype : 'combo',
        //     triggerAction : 'all',
        //     hideTrigger : false,
        //     mode : 'local',
        //     typeAhead : true,
        //     store : Bb.Menu.Store.groupStore,
        //     emptyText : '请选择菜单分组',
        //     valueField : 'id',// 值
        //     displayField : 'name',// 显示文本
        //     editable : true,// 是否允许输入
        //     allowBlank : false,
        //     selectOnFocus : true,
        //     onSelect : function(record, index) {
        //         if (this.fireEvent('beforeselect', this, record, index) !== false) {
        //             this.setValue(record.data[this.valueField
        //                     || this.displayField]);
        //             this.collapse();
        //             this.fireEvent('select', this, record, index);
        //         }
        //         Bb.Menu.View.current_menuGroup_id=record.data.id;
        //     },
        // },
        {
            fieldLabel : '网址(<font color=red>*</font>)',
            allowBlank : false,
            name : 'address'
        }, {
            fieldLabel : '描述',
            name : 'title'
        }]
    }),

    /**
     * 窗口：新建菜单
     */
    SaveWindow:Ext.extend(Ext.Window,{
        constructor : function(config) {
            config = Ext.apply({
                width : 400,
                height : 385,
                minWidth : 400,
                minHeight : 300,
                closeAction : "hide",
                title : '添加菜单',
                layout : 'fit',
                plain : true,
                buttonAlign : 'center',
                items : [Bb.Menu.View.SaveForm],
                listeners:{
                    beforehide:function(){
                        this.editForm.getForm().reset();
                    }
                },
                /**
                 * 自定义类型:保存类型
                 * 0:保存窗口,1:修改窗口
                 */
                savetype:0,
                buttons : [{
                    text: "",ref : "../saveBtn",
                    scope:this,
                    handler : function() {
                        saveWindow=this;
                        if (this.savetype==0){
                            this.editForm.api.submit=ServiceMenu.save;
                            Bb.Menu.View.SaveForm.getForm().submit({
                                success : function(form, action) {// 表单提交成功后,调用的函数.参数分为两个,一个是提交的表单对象,另一个是PHP返回的参数值对象
                                    Ext.MessageBox.alert("提示","保存成功！");
                                    Ext.getCmp('groupGrid').getMenusByGroupId(Bb.Menu.View.current_menuGroup_id);
                                    saveWindow.hide();
                                    Bb.Menu.View.SaveForm.getForm().reset();
                                },
                                failure : function(form, action) {
                                    Ext.Msg.alert('提示', '失败');
                                }
                            });

                        }else{
                            this.editForm.api.submit=ServiceMenu.update;
                            Bb.Menu.View.SaveForm.getForm().submit({
                                success : function(form, action) {// 表单提交成功后,调用的函数.参数分为两个,一个是提交的表单对象,另一个是PHP返回的参数值对象
                                    Ext.MessageBox.alert("提示","修改成功！");
                                    Ext.getCmp('groupGrid').getMenusByGroupId(Bb.Menu.View.current_menuGroup_id);
                                    saveWindow.hide();
                                    Bb.Menu.View.SaveForm.getForm().reset();
                                },
                                failure : function(form, action) {
                                    Ext.Msg.alert('提示', '失败');
                                }
                            });

                        }
                    }
                }, {
                    text : "取 消",
                    scope:this,
                    handler : function() {
                        this.hide();
                    }
                }]
            }, config);
            Bb.Menu.View.SaveWindow.superclass.constructor.call(this, config);
        }
    })
}

/**
 * Controller:主程序
 */
Ext.onReady(function(){
    Ext.QuickTips.init();
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
    Ext.Direct.addProvider(Ext.app.REMOTING_API);
    Bb.Menu.Store.menuStore.proxy=new Ext.data.DirectProxy({
        api: {
           read:ServiceMenu.queryPageMenu
        }
    });
    Bb.Menu.View.SaveForm.api.submit=ServiceMenu.save;
    Bb.Menu.View.GetForm.api.submit=ServiceMenu.queryPageMenuForm;



    Bb.Menu.Viewport = new Ext.Viewport({
        layout: 'border',
        items: [
          new Bb.Menu.View.GroupPanel(),
          new Bb.Menu.View.Grid()
        ]
    });

    Bb.Menu.Viewport.doLayout();

    setTimeout(function(){
        Ext.get('loading').remove();
        Ext.get('loading-mask').fadeOut({
            remove:true
        });
    }, 250);
});
