<?php
$jsContent=<<<JSCONTENT
Ext.namespace("$appName.Admin.$classname");
$appName = $appName.Admin.$classname;
$appName.$classname={
    /**
     * 全局配置
     */
    Config:{
        /**
         *分页:每页显示记录数
         */        
        PageSize:15,
        /**
         *显示配置
         */  
        View:{
            /**
             * 显示{$table_comment}的视图相对{$table_comment}列表Grid的位置
             * 1:上方,2:下方,3:左侧,4:右侧,
             */
            Direction:2,
            /**
             *是否显示。
             */
            IsShow:0,
            /**
             * 是否固定显示{$table_comment}信息页(或者打开新窗口)
             */
            IsFix:0          
        }
    },
    /**
     * Cookie设置
     */
    Cookie:new Ext.state.CookieProvider(),
    /**
     * 初始化
     */
    Init:function(){
        if ($appName.$classname.Cookie.get('View.Direction')){
            $appName.$classname.Config.View.Direction=$appName.$classname.Cookie.get('View.Direction');
        }
        if ($appName.$classname.Cookie.get('View.IsFix')!=null){
            $appName.$classname.Config.View.IsFix=$appName.$classname.Cookie.get('View.IsFix');
        }
    }
}; 
/**
 * Model:数据模型   
 */
$appName.$classname.Store = { 
    /**
     * {$table_comment}
     */ 
    {$instancename}Store:new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            totalProperty: 'totalCount',
            successProperty: 'success',  
            root: 'data',remoteSort: true,                
            fields : [
$fields
            ]}         
        ),
        writer: new Ext.data.JsonWriter({
            encode: false 
        }),
        listeners : {    
            beforeload : function(store, options) {   
                if (Ext.isReady) {  
                    Ext.apply(options.params, $appName.$classname.View.Running.{$instancename}Grid.filter);//保证分页也将查询条件带上  
                }
            }
        }    
    })   
}
/**
 * View:{$table_comment}显示组件   
 */
$appName.$classname.View={ 
    /**
     * 编辑窗口：新建或者修改{$table_comment}
     */        
    EditWindow:Ext.extend(Ext.Window,{
        constructor : function(config) { 
            config = Ext.apply({ 
                /**
                 * 自定义类型:保存类型
                 * 0:保存窗口,1:修改窗口
                 */
                savetype:0,       
                closeAction : "hide",  
                constrainHeader:true,maximizable: true,collapsible: true,
                width : 450,height : 550,minWidth : 400,minHeight : 450,
                layout : 'fit',plain : true,buttonAlign : 'center',
                listeners:{
                    beforehide:function(){
                        this.editForm.form.getEl().dom.reset();                    
                    }  
                },
                items : [ 
                    new Ext.form.FormPanel({   
                        ref:'editForm',layout:'form',
                        labelWidth : 100,autoWidth : true,labelAlign : "center",
                        bodyStyle : 'padding:5px 5px 0',align : "center",
                        api : {},
                        defaults : {
                            xtype : 'textfield',anchor:'100%'
                        },
                        items : [ 
$fieldLabels        
                        ]
                    })                
                ],
                buttons : [ {         
                    text: "",ref : "../saveBtn",scope:this,
                    handler : function() {
                        if (!this.editForm.getForm().isValid()) {
                            return;
                        }
                        editWindow=this; 
                        if (this.savetype==0){    
                            this.editForm.api.submit=ExtService$classname.save;                   
                            this.editForm.getForm().submit({
                                success : function(form, action) {
                                    Ext.Msg.alert("提示", "保存成功！");
                                    $appName.$classname.View.Running.{$instancename}Grid.doSelect$classname();
                                    form.reset(); 
                                    editWindow.hide();
                                },
                                failure : function(form, action) {
                                    Ext.Msg.alert('提示', '失败');
                                }
                            });
                        }else{
                            this.editForm.api.submit=ExtService$classname.update;
                            this.editForm.getForm().submit({
                                success : function(form, action) {
                                    Ext.Msg.alert("提示", "修改成功！");
                                    $appName.$classname.View.Running.{$instancename}Grid.doSelect$classname();
                                    form.reset();
                                    editWindow.hide();
                                },
                                failure : function(form, action) {
                                    Ext.Msg.alert('提示', '失败');
                                }
                            });
                        }
                    }
                }, {
                    text : "取 消",scope:this,
                    handler : function() {  
                        this.hide();
                    }
                }, {
                    text : "重 置",ref:'../resetBtn',scope:this,
                    handler : function() {  
                        this.editForm.form.loadRecord($appName.$classname.View.Running.{$instancename}Grid.getSelectionModel().getSelected());
                    }                  
                }]    
            }, config);  
            $appName.$classname.View.EditWindow.superclass.constructor.call(this, config);     
        }
    }),
    /**
     * 显示{$table_comment}详情
     */
    {$classname}View:{
        /**
         * Tab页：容器包含显示与{$table_comment}所有相关的信息
         */  
        Tabs:Ext.extend(Ext.TabPanel,{ 
            constructor : function(config) { 
                config = Ext.apply({             
                    region : 'south',
                    collapseMode : 'mini',split : true,
                    activeTab: 1, tabPosition:"bottom",resizeTabs : true,     
                    header:false,enableTabScroll : true,tabWidth:100, margins : '0 3 3 0',
                    defaults : {
                        autoScroll : true
                    },
                    listeners:{
                        beforetabchange:function(tabs,newtab,currentTab){  
                            if (tabs.tabFix==newtab){            
                                if ($appName.$classname.View.Running.{$instancename}Grid.getSelectionModel().getSelected()==null){
                                    Ext.Msg.alert('提示', '请先选择{$table_comment}！');
                                    return false;
                                } 
                                $appName.$classname.Config.View.IsShow=1;
                                $appName.$classname.View.Running.{$instancename}Grid.show$classname();   
                                $appName.$classname.View.Running.{$instancename}Grid.tvpView.menu.mBind.setChecked(false);
                                return false;
                            }
                        }
                    },
                    items: [
                        {title: '取消固定',ref:'tabFix',iconCls:'icon-fix'}
                    ]
                }, config);
                $appName.$classname.View.{$classname}View.Tabs.superclass.constructor.call(this, config); 
                this.onAddItems();
            },
            /**
             * 根据布局调整Tabs的宽度或者高度以及折叠
             */
            enableCollapse:function(){
                if (($appName.$classname.Config.View.Direction==1)||($appName.$classname.Config.View.Direction==2)){
                    this.width =Ext.getBody().getViewSize().width;
                    this.height=Ext.getBody().getViewSize().height/2;
                }else{
                    this.width =Ext.getBody().getViewSize().width/2;  
                    this.height=Ext.getBody().getViewSize().height;                  
                }
                this.ownerCt.setSize(this.width,this.height);       
                if (this.ownerCt.collapsed)this.ownerCt.expand();         
                this.ownerCt.collapsed=false;
            },
            onAddItems:function(){
                this.add(
                    {title: '基本信息',ref:'tab{$classname}Detail',iconCls:'tabs',
                     tpl: [
                      '<div class="viewdoblock">', 
$viewdoblock                      
                     '</div>' 
                     ]
                    }
                );
                this.add(
                    {title: '其他',iconCls:'tabs'}
                );
            }       
        }),
        /**
         * 窗口:显示{$table_comment}信息
         */
        Window:Ext.extend(Ext.Window,{ 
            constructor : function(config) { 
                config = Ext.apply({
                    title:"查看{$table_comment}",constrainHeader:true,maximizable: true,minimizable : true, 
                    width : 600,height : 500,minWidth : 450,minHeight : 400,
                    layout : 'fit',resizable:true,plain : true,bodyStYle : 'padding:5px;',
                    closeAction : "hide",
                    items:[new $appName.$classname.View.{$classname}View.Tabs({ref:'winTabs',tabPosition:'top'})],
                    listeners: { 
                        minimize:function(w){
                            w.hide();
                            $appName.$classname.Config.View.IsShow=0;
                            $appName.$classname.View.Running.{$instancename}Grid.tvpView.menu.mBind.setChecked(true);
                        },
                        hide:function(w){
                            $appName.$classname.View.Running.{$instancename}Grid.tvpView.toggle(false);
                        }   
                    },
                    buttons: [{
                        text: '新增',scope:this,
                        handler : function() {this.hide();$appName.$classname.View.Running.{$instancename}Grid.add$classname();}
                    },{
                        text: '修改',scope:this,
                        handler : function() {this.hide();$appName.$classname.View.Running.{$instancename}Grid.update$classname();}
                    }]
                }, config);  
                $appName.$classname.View.{$classname}View.Window.superclass.constructor.call(this, config);   
            }        
        })
    },
    /**
     * 窗口：批量上传{$table_comment}
     */        
    UploadWindow:Ext.extend(Ext.Window,{ 
        constructor : function(config) { 
            config = Ext.apply({     
                title : '批量{$table_comment}上传',
                width : 400,height : 110,minWidth : 300,minHeight : 100,
                layout : 'fit',plain : true,bodyStYle : 'padding:5px;',buttonAlign : 'center',
                closeAction : "hide",
                items : [
                    new Ext.form.FormPanel({
                        ref:'uploadForm',fileUpload: true,
                        width: 500,labelWidth: 50,autoHeight: true,baseCls: 'x-plain',
                        frame:true,bodyStyle: 'padding: 10px 10px 10px 10px;',
                        defaults: {
                            anchor: '95%',allowBlank: false,msgTarget: 'side'
                        },              
                        items : [{
                            xtype : 'fileuploadfield',
                            fieldLabel : '文 件',name : 'upload_file',ref:'upload_file',
                            emptyText: '请上传{$table_comment}Excel文件',buttonText: '',
                            accept:'application/vnd.ms-excel',
                            buttonCfg: {iconCls: 'upload-icon'}
                        }]
                    })
                ],
                buttons : [{
                        text : '上 传',
                        scope:this,
                        handler : function() {
                            uploadWindow           =this;
                            validationExpression   =/\w+(.xlsx|.XLSX|.xls|.XLS)$/;
                            var isValidExcelFormat = new RegExp(validationExpression);
                            var result             = isValidExcelFormat.test(this.uploadForm.upload_file.getValue());
                            if (!result){
                                Ext.Msg.alert('提示', '请上传Excel文件，后缀名为xls或者xlsx！');
                                return;
                            }
                            if (this.uploadForm.getForm().isValid()) {
                                Ext.Msg.show({
                                    title : '请等待',msg : '文件正在上传中，请稍后...',
                                    animEl : 'loading',icon : Ext.Msg.WARNING,
                                    closable : true,progress : true,progressText : '',width : 300
                                });
                                this.uploadForm.getForm().submit({
                                    url : 'index.php?go=admin.upload.upload$classname',
                                    success : function(form, action) {
                                        Ext.Msg.alert('成功', '上传成功');
                                        uploadWindow.hide();
                                        uploadWindow.uploadForm.upload_file.setValue('');
                                        $appName.$classname.View.Running.{$instancename}Grid.doSelect$classname();
                                    },
                                    failure : function(form, action) {
                                        Ext.Msg.alert('错误', action.result.msg);
                                    }
                                });
                            }
                        }
                    },{
                        text : '取 消',
                        scope:this,
                        handler : function() {
                            this.uploadForm.upload_file.setValue('');
                            this.hide();
                        }
                    }]
                }, config);  
            $appName.$classname.View.UploadWindow.superclass.constructor.call(this, config);     
        }        
    }),
    /**
     * 视图：{$table_comment}列表
     */
    Grid:Ext.extend(Ext.grid.GridPanel, {
        constructor : function(config) {
            config = Ext.apply({
                /**
                 * 查询条件  
                 */
                filter:null,
                region : 'center',
                store : $appName.$classname.Store.{$instancename}Store,
                sm : this.sm,
                frame : true,trackMouseOver : true,enableColumnMove : true,columnLines : true,
                loadMask : true,stripeRows : true,headerAsText : false,                
                defaults : {
                    autoScroll : true
                },
                cm : new Ext.grid.ColumnModel({
                    defaults:{
                        width:120,sortable : true
                    },
                    columns : [
                        this.sm,        
$columns                                 
                    ]
                }),                       
                tbar : {
                    xtype : 'container',layout : 'anchor',
                    height : 27 * 2,style:'font-size:14px',
                    defaults : {
                        height : 27,anchor : '100%'
                    },
                    items : [                        
                        new Ext.Toolbar({
                            width : 100,
                            defaults : {
                               xtype : 'textfield'
                            },
                            items : [
$filterFields                                
                                {
                                    xtype : 'button',text : '查询',scope: this, 
                                    handler : function() {
                                        this.doSelect$classname();
                                    }
                                }, 
                                {
                                    xtype : 'button',text : '重置',scope: this,
                                    handler : function() {
$filterReset                                        
                                        this.filter={};
                                        this.doSelect$classname();
                                    }
                                }]
                        }), 
                        new Ext.Toolbar({
                            defaults:{
                              scope: this  
                            },
                            items : [
                                {
                                    text: '反选',iconCls : 'icon-reverse',                                             
                                    handler: function(){
                                        this.onReverseSelect();
                                    }
                                },'-',{
                                    text : '添加{$table_comment}',iconCls : 'icon-add',
                                    handler : function() {
                                        this.add$classname();
                                    }
                                },'-',{
                                    text : '修改{$table_comment}',ref: '../../btnUpdate',iconCls : 'icon-edit',disabled : true,  
                                    handler : function() {
                                        this.update$classname();
                                    }
                                },'-',{
                                    text : '删除{$table_comment}', ref: '../../btnRemove',iconCls : 'icon-delete',disabled : true,                                    
                                    handler : function() {
                                        this.delete$classname();
                                    }
                                },'-',{
                                    text : '导入',iconCls : 'icon-import', 
                                    handler : function() {
                                        this.import$classname();
                                    }
                                },'-',{
                                    text : '导出',iconCls : 'icon-export', 
                                    handler : function() { 
                                        this.export$classname();
                                    }
                                },'-',{ 
                                    xtype:'tbsplit',text: '查看{$table_comment}', ref:'../../tvpView',iconCls : 'icon-updown',
                                    enableToggle: true, disabled : true,  
                                    handler:function(){this.show$classname()},
                                    menu: {
                                        xtype:'menu',plain:true,
                                        items: [
                                            {text:'上方',group:'mlayout',checked:false,iconCls:'view-top',scope:this,handler:function(){this.onUpDown(1)}},
                                            {text:'下方',group:'mlayout',checked:true ,iconCls:'view-bottom',scope:this,handler:function(){this.onUpDown(2)}}, 
                                            {text:'左侧',group:'mlayout',checked:false,iconCls:'view-left',scope:this,handler:function(){this.onUpDown(3)}},
                                            {text:'右侧',group:'mlayout',checked:false,iconCls:'view-right',scope:this,handler:function(){this.onUpDown(4)}}, 
                                            {text:'隐藏',group:'mlayout',checked:false,iconCls:'view-hide',scope:this,handler:function(){this.hide$classname();$appName.$classname.Config.View.IsShow=0;}},'-', 
                                            {text: '固定',ref:'mBind',checked: true,scope:this,checkHandler:function(item, checked){this.onBindGrid(item, checked);$appName.$classname.Cookie.set('View.IsFix',$appName.$classname.Config.View.IsFix);}} 
                                        ]}
                                },'-']}
                    )]
                },                
                bbar: new Ext.PagingToolbar({          
                    pageSize: $appName.$classname.Config.PageSize,
                    store: $appName.$classname.Store.{$instancename}Store,
                    scope:this,autoShow:true,displayInfo: true,
                    displayMsg: '当前显示 {0} - {1}条记录/共 {2}条记录。',
                    emptyMsg: "无显示数据",
                    items: [
                        {xtype:'label', text: '每页显示'},
                        {xtype:'numberfield', value:$appName.$classname.Config.PageSize,minValue:1,width:35, 
                            style:'text-align:center',allowBlank: false,
                            listeners:
                            {
                                change:function(Field, newValue, oldValue){
                                    var num = parseInt(newValue);
                                    if (isNaN(num) || !num || num<1)
                                    {
                                        num = $appName.$classname.Config.PageSize;
                                        Field.setValue(num);
                                    }
                                    this.ownerCt.pageSize= num;
                                    $appName.$classname.Config.PageSize = num;
                                    this.ownerCt.ownerCt.doSelect$classname();
                                }, 
                                specialKey :function(field,e){
                                    if (e.getKey() == Ext.EventObject.ENTER){
                                        var num = parseInt(field.getValue());
                                        if (isNaN(num) || !num || num<1)
                                        {
                                            num = $appName.$classname.Config.PageSize;
                                        }
                                        this.ownerCt.pageSize= num;
                                        $appName.$classname.Config.PageSize = num;
                                        this.ownerCt.ownerCt.doSelect$classname();
                                    }
                                }
                            }
                        },
                        {xtype:'label', text: '个'}
                    ]
                })
            }, config);
            //初始化显示{$table_comment}列表
            this.doSelect$classname();
            $appName.$classname.View.Grid.superclass.constructor.call(this, config); 
            //创建在Grid里显示的{$table_comment}信息Tab页
            $appName.$classname.View.Running.viewTabs=new $appName.$classname.View.{$classname}View.Tabs();
            this.addListener('rowdblclick', this.onRowDoubleClick);
        },
        /**
         * 行选择器
         */
        sm : new Ext.grid.CheckboxSelectionModel({
            //handleMouseDown : Ext.emptyFn,
            listeners : {
                selectionchange:function(sm) {
                    // 判断删除和更新按钮是否可以激活
                    this.grid.btnRemove.setDisabled(sm.getCount() < 1);
                    this.grid.btnUpdate.setDisabled(sm.getCount() != 1);
                    this.grid.tvpView.setDisabled(sm.getCount() != 1); 
                },
                rowselect: function(sm, rowIndex, record) {  
                    this.grid.updateView$classname();                     
                    if (sm.getCount() != 1){
                        this.grid.hide$classname();
                        $appName.$classname.Config.View.IsShow=0;
                    }else{
                        if ($appName.$classname.View.IsSelectView==1){
                            $appName.$classname.View.IsSelectView=0;  
                            this.grid.show$classname();   
                        }     
                    }    
                },
                rowdeselect: function(sm, rowIndex, record) {  
                    if (sm.getCount() != 1){
                        if ($appName.$classname.Config.View.IsShow==1){
                            $appName.$classname.View.IsSelectView=1;    
                        }             
                        this.grid.hide$classname();
                        $appName.$classname.Config.View.IsShow=0;
                    }    
                }
            }
        }),
        /**
         * 双击选行
         */
        onRowDoubleClick:function(grid, rowIndex, e){  
            if (!$appName.$classname.Config.View.IsShow){
                this.sm.selectRow(rowIndex);
                this.show$classname();
                this.tvpView.toggle(true);
            }else{
                this.hide$classname();
                $appName.$classname.Config.View.IsShow=0;
                this.sm.deselectRow(rowIndex);
                this.tvpView.toggle(false);
            }
        },
        /**
         * 是否绑定在本窗口上
         */
        onBindGrid:function(item, checked){ 
            if (checked){             
               $appName.$classname.Config.View.IsFix=1; 
            }else{ 
               $appName.$classname.Config.View.IsFix=0;   
            }
            if (this.getSelectionModel().getSelected()==null){
                $appName.$classname.Config.View.IsShow=0;
                return ;
            }
            if ($appName.$classname.Config.View.IsShow==1){
               this.hide$classname(); 
               $appName.$classname.Config.View.IsShow=0;
            }
            this.show$classname();   
        },   
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
         * 查询符合条件的{$table_comment}
         */
        doSelect$classname : function() {
            if (this.topToolbar){
$filterdoSelect
$filterfilter
            }
            var condition = {'start':0,'limit':$appName.$classname.Config.PageSize};
            Ext.apply(condition,this.filter);
            ExtService$classname.queryPage$classname(condition,function(provider, response) {   
                if (response.result.data) {   
                    var result           = new Array();
                    result['data']       =response.result.data; 
                    result['totalCount'] =response.result.totalCount;
                    $appName.$classname.Store.{$instancename}Store.loadData(result); 
                } else {
                    $appName.$classname.Store.{$instancename}Store.removeAll();                        
                    Ext.Msg.alert('提示', '无符合条件的{$table_comment}！');
                }
            });
        }, 
        /**
         * 显示{$table_comment}视图
         * 显示{$table_comment}的视图相对{$table_comment}列表Grid的位置
         * 1:上方,2:下方,0:隐藏。
         */
        onUpDown:function(viewDirection){
            $appName.$classname.Config.View.Direction=viewDirection; 
            switch(viewDirection){
                case 1:
                    this.ownerCt.north.add($appName.$classname.View.Running.viewTabs);
                    break;
                case 2:
                    this.ownerCt.south.add($appName.$classname.View.Running.viewTabs);
                    break;
                case 3:
                    this.ownerCt.west.add($appName.$classname.View.Running.viewTabs);
                    break;
                case 4:
                    this.ownerCt.east.add($appName.$classname.View.Running.viewTabs);
                    break;    
            }  
            $appName.$classname.Cookie.set('View.Direction',$appName.$classname.Config.View.Direction);
            if (this.getSelectionModel().getSelected()!=null){
                if (($appName.$classname.Config.View.IsFix==0)&&($appName.$classname.Config.View.IsShow==1)){
                    this.show$classname();     
                }
                $appName.$classname.Config.View.IsFix=1;
                $appName.$classname.View.Running.{$instancename}Grid.tvpView.menu.mBind.setChecked(true,true);  
                $appName.$classname.Config.View.IsShow=0;
                this.show$classname();     
            }
        }, 
        /**
         * 显示{$table_comment}
         */
        show$classname : function(){
            if (this.getSelectionModel().getSelected()==null){
                Ext.Msg.alert('提示', '请先选择{$table_comment}！');
                $appName.$classname.Config.View.IsShow=0;
                this.tvpView.toggle(false);
                return ;
            } 
            if ($appName.$classname.Config.View.IsFix==0){
                if ($appName.$classname.View.Running.view_window==null){
                    $appName.$classname.View.Running.view_window=new $appName.$classname.View.{$classname}View.Window();
                }
                if ($appName.$classname.View.Running.view_window.hidden){
                    $appName.$classname.View.Running.view_window.show();
                    $appName.$classname.View.Running.view_window.winTabs.hideTabStripItem($appName.$classname.View.Running.view_window.winTabs.tabFix);   
                    this.updateView$classname();
                    this.tvpView.toggle(true);
                    $appName.$classname.Config.View.IsShow=1;
                }else{
                    this.hide$classname();
                    $appName.$classname.Config.View.IsShow=0;
                }
                return;
            }
            switch($appName.$classname.Config.View.Direction){
                case 1:
                    if (!this.ownerCt.north.items.contains($appName.$classname.View.Running.viewTabs)){
                        this.ownerCt.north.add($appName.$classname.View.Running.viewTabs);
                    }
                    break;
                case 2:
                    if (!this.ownerCt.south.items.contains($appName.$classname.View.Running.viewTabs)){
                        this.ownerCt.south.add($appName.$classname.View.Running.viewTabs);
                    }
                    break;
                case 3:
                    if (!this.ownerCt.west.items.contains($appName.$classname.View.Running.viewTabs)){
                        this.ownerCt.west.add($appName.$classname.View.Running.viewTabs);
                    }
                    break;
                case 4:
                    if (!this.ownerCt.east.items.contains($appName.$classname.View.Running.viewTabs)){
                        this.ownerCt.east.add($appName.$classname.View.Running.viewTabs);
                    }
                    break;    
            }  
            this.hide$classname();
            if ($appName.$classname.Config.View.IsShow==0){
                $appName.$classname.View.Running.viewTabs.enableCollapse();  
                switch($appName.$classname.Config.View.Direction){
                    case 1:
                        this.ownerCt.north.show();
                        break;
                    case 2:
                        this.ownerCt.south.show();
                        break;
                    case 3:
                        this.ownerCt.west.show();
                        break;
                    case 4:
                        this.ownerCt.east.show();
                        break;    
                }  
                this.updateView$classname();
                this.tvpView.toggle(true);
                $appName.$classname.Config.View.IsShow=1;
            }else{
                $appName.$classname.Config.View.IsShow=0;
            }
            this.ownerCt.doLayout();
        },
        /**
         * 隐藏{$table_comment}
         */
        hide$classname : function(){
            this.ownerCt.north.hide();
            this.ownerCt.south.hide();
            this.ownerCt.west.hide();   
            this.ownerCt.east.hide(); 
            if ($appName.$classname.View.Running.view_window!=null){
                $appName.$classname.View.Running.view_window.hide();
            }            
            this.tvpView.toggle(false);
            this.ownerCt.doLayout();
        },
        /**
         * 更新当前{$table_comment}显示信息
         */
        updateView$classname : function() {
            if ($appName.$classname.View.Running.view_window!=null){
                $appName.$classname.View.Running.view_window.winTabs.tab{$classname}Detail.update(this.getSelectionModel().getSelected().data);
            }
            $appName.$classname.View.Running.viewTabs.tab{$classname}Detail.update(this.getSelectionModel().getSelected().data);
        },
        /**
         * 新建{$table_comment}
         */
        add$classname : function() {  
            if ($appName.$classname.View.Running.edit_window==null){   
                $appName.$classname.View.Running.edit_window=new $appName.$classname.View.EditWindow();   
            }     
            $appName.$classname.View.Running.edit_window.resetBtn.setVisible(false);
            $appName.$classname.View.Running.edit_window.saveBtn.setText('保 存');
            $appName.$classname.View.Running.edit_window.setTitle('添加{$table_comment}');
            $appName.$classname.View.Running.edit_window.savetype=0;
            $appName.$classname.View.Running.edit_window.$tableFieldIdName.setValue("");
            $appName.$classname.View.Running.edit_window.show();   
        },   
        /**
         * 编辑供应商时先获得选中的{$table_comment}信息
         */
        update$classname : function() {
            if ($appName.$classname.View.Running.edit_window==null){   
                $appName.$classname.View.Running.edit_window=new $appName.$classname.View.EditWindow();   
            }            
            $appName.$classname.View.Running.edit_window.saveBtn.setText('修 改');
            $appName.$classname.View.Running.edit_window.resetBtn.setVisible(true);
            $appName.$classname.View.Running.edit_window.setTitle('修改{$table_comment}');
            $appName.$classname.View.Running.edit_window.editForm.form.loadRecord(this.getSelectionModel().getSelected());
            $appName.$classname.View.Running.edit_window.savetype=1;
            $appName.$classname.View.Running.edit_window.show();       
        },        
        /**
         * 删除{$table_comment}
         */
        delete$classname : function() {
            Ext.Msg.confirm('提示', '确实要删除所选的{$table_comment}吗?', this.confirmDelete$classname,this);
        }, 
        /**
         * 确认删除{$table_comment}
         */
        confirmDelete$classname : function(btn) {
            if (btn == 'yes') {  
                var del_{$instancename}_ids ="";
                var selectedRows    = this.getSelectionModel().getSelections();
                for ( var flag = 0; flag < selectedRows.length; flag++) {
                    del_{$instancename}_ids=del_{$instancename}_ids+selectedRows[flag].data.{$instancename}_id+",";
                }
                ExtService$classname.deleteByIds(del_{$instancename}_ids);
                this.doSelect$classname();
                Ext.Msg.alert("提示", "删除成功！");        
            }
        },
        /**
         * 导出{$table_comment}
         */
        export{$classname} : function() {            
            ExtService{$classname}.export{$classname}(this.filter,function(provider, response) {  
                if (response.result.data) {
                    window.open(response.result.data);
                }
            });                        
        },
        /**
         * 导入{$table_comment}
         */
        import$classname : function() { 
            if ($appName.$classname.View.current_uploadWindow==null){   
                $appName.$classname.View.current_uploadWindow=new $appName.$classname.View.UploadWindow();   
            }     
            $appName.$classname.View.current_uploadWindow.show();
        }                
    }),
    /**
     * 核心内容区
     */
    Panel:Ext.extend(Ext.form.FormPanel,{
        constructor : function(config) {
            $appName.$classname.View.Running.{$instancename}Grid=new $appName.$classname.View.Grid();           
            if ($appName.$classname.Config.View.IsFix==0){
                $appName.$classname.View.Running.{$instancename}Grid.tvpView.menu.mBind.setChecked(false,true);  
            }
            config = Ext.apply({ 
                region : 'center',layout : 'fit', frame:true,
                items: {
                    layout:'border',
                    items:[
                        $appName.$classname.View.Running.{$instancename}Grid, 
                        {region:'north',ref:'north',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true},
                        {region:'south',ref:'south',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true,items:[$appName.$classname.View.Running.viewTabs]}, 
                        {region:'west',ref:'west',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true}, 
                        {region:'east',ref:'east',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true} 
                    ]
                }
            }, config);   
            $appName.$classname.View.Panel.superclass.constructor.call(this, config);  
        }        
    }),
    /**
     * 当前运行的可视化对象
     */ 
    Running:{         
        /**
         * 当前{$table_comment}Grid对象
         */
        {$instancename}Grid:null,  
        /**
         * 显示{$table_comment}信息及关联信息列表的Tab页
         */
        viewTabs:null,
        /**
         * 当前创建的编辑窗口
         */
        edit_window:null,  
        /**
         * 当前的显示窗口
         */
        view_window:null                         
    }    
};
/**
 * Controller:主程序
 */
Ext.onReady(function(){
    Ext.QuickTips.init();
    Ext.state.Manager.setProvider($appName.$classname.Cookie);
    Ext.Direct.addProvider(Ext.app.REMOTING_API);     
    $appName.$classname.Init();
    /**
     * {$table_comment}数据模型获取数据Direct调用
     */        
    $appName.$classname.Store.{$instancename}Store.proxy=new Ext.data.DirectProxy({ 
        api: {read:ExtService$classname.queryPage$classname}
    });   
    /**
     * {$table_comment}页面布局
     */
    $appName.$classname.Viewport = new Ext.Viewport({
        layout : 'border',
        items : [new $appName.$classname.View.Panel()]
    });
    $appName.$classname.Viewport.doLayout();    
    setTimeout(function(){
        Ext.get('loading').remove();
        Ext.get('loading-mask').fadeOut({
            remove:true
        });
    }, 250);
});  
JSCONTENT;
?>
