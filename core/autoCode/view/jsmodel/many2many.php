<?php
/**
* 多对多关系，在主控方显示菜单可进行一选多
*/
$key_many=$key;
$key_many{0}=strtoupper($key_many{0});
$jsMany2ManyContent="";
$jsMany2ManyMenu="";
$jsMany2ManyShowHide="";
$jsMany2ManyRunningWindow="";
if (self::isMany2ManyByClassname($key_many))
{
    $tablename_many=self::getTablename($key_many);
    $fieldInfo_many=self::$fieldInfos[$tablename_many];
    $middle_instance_name=self::getInstancename($tablename_many);
    $owner_idcolumn="";
    $belong_class="";
    $belong_idcolumn="";
    foreach (array_keys($fieldInfo_many) as $fieldname)
    {
        if (!self::isNotColumnKeywork($fieldname))continue;
        if ($fieldname==self::keyIDColumn($key_many))continue;
        if (contain($fieldname,"_id")){
            $to_class=str_replace("_id", "", $fieldname);
            $to_class{0}=strtoupper($to_class{0});
            if (class_exists($to_class)){
                if ($to_class!=$classname){
                    $belong_class=$to_class;
                    $belong_idcolumn=$fieldname;
                }else{
                    $owner_idcolumn=$fieldname;
                }
            }
        }
    }
    $tablename_owner=self::getTablename($classname);
    $comment_owner=self::tableCommentKey($tablename_owner);
    $owner_instance_name=self::getInstancename($tablename_owner);
    $tablename_belong=self::getTablename($belong_class);
    $belong_instance_name=self::getInstancename($tablename_belong);
    $comment_belong=self::tableCommentKey($tablename_belong);
    $belong_fieldInfo=self::$fieldInfos[$tablename_belong];
    self::$relationStore=$relationStore;
    $fields_many=self::model_fields($tablename,$belong_class,$belong_instance_name,$belong_fieldInfo,false);
    $fields_many_fields=$fields_many['fields'];
    /**
     * 多对多选择Store
     * @var mixed
     */
    $jsMany2ManyStore=<<<MANY2MANYSTORE
,
    /**
     * {$comment_belong}
     */
    select{$belong_class}Store:new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            totalProperty: 'totalCount',
            successProperty: 'success',
            root: 'data',remoteSort: true,
            fields : [
$fields_many_fields
            ]}
        ),
        writer: new Ext.data.JsonWriter({
            encode: false
        }),
        listeners : {
            beforeload : function(store, options) {
                if (Ext.isReady) {
                    Ext.apply(options.params, $appName_alias.$classname.View.Running.select{$belong_class}Window.{$belong_instance_name}Grid.filter);//保证分页也将查询条件带上
                }
            },
            load : function(records,options){
                if (records&&records.data&&records.data.items) {
                    var selData    = $appName_alias.$classname.View.Running.select{$belong_class}Window.selData;//选中的{$comment_belong}
                    var data       = records.data.items;
                    //把已经推荐的{$comment_belong}选中
                    var sm=$appName_alias.$classname.View.Running.select{$belong_class}Window.{$belong_instance_name}Grid.sm;
                    var rows=$appName_alias.$classname.View.Running.select{$belong_class}Window.{$belong_instance_name}Grid.getView().getRows();
                    for(var i=0;i<rows.length;i++){
                        if(selData[data[i]['data'].$belong_idcolumn]){
                            sm.selectRow(i, true);
                        }
                    }
                }
            }
        }
    })
MANY2MANYSTORE;
    $relationStore.=$jsMany2ManyStore.$fields_many['relationStore_onlyForFieldLabels'];
    $m2m_columns=self::model_columns($tablename,$belong_class,$belong_fieldInfo,"    ");
    $m2m_filters=self::model_filters($appName_alias,$belong_class,$belong_instance_name,$belong_fieldInfo,"    ");
    //Ext "Grid" 中"tbar"包含的items中的items
    $m2m_filterFields   =$m2m_filters["filterFields"];
    $m2m_filterFields   =str_replace("{$appName_alias}.$belong_class.", "{$appName_alias}.$classname.", $m2m_filterFields);
    //重置语句
    $m2m_filterReset    =$m2m_filters["filterReset"];
    //查询中的语句
    $m2m_filterdoSelect =$m2m_filters["filterdoSelect"];
    if (!endWith($m2m_filterdoSelect,"{")){
        $m2m_filterdoSelect=substr($m2m_filterdoSelect,0,strlen($m2m_filterdoSelect)-2).",";
    }

    /**
     * 多对多选择Window,Grid
     * @var mixed
     */
    $jsMany2ManyContent=<<<MANY2MANY

    /**
     * 视图：{$comment_belong}
     */
    {$belong_class}View:{
        Select{$belong_class}Window:Ext.extend(Ext.Window,{
            constructor : function(config) {
                config = Ext.apply({
                    selData:null,//选中的{$comment_belong}
                    oldData:null,//已关联的{$comment_belong}
                    title:"选择{$comment_belong}",updateData:null,closeAction:"hide",constrainHeader:true,maximizable:true,collapsible:true,
                    width:720,minWidth:720,height:560,minHeight:450,layout:'fit',plain : true,buttonAlign : 'center',
                    defaults : {autoScroll : true,},
                    listeners:{
                        beforehide:this.doHide
                    },
                    items : [new $appName_alias.$classname.View.{$belong_class}View.{$belong_class}Grid({ref:"{$belong_instance_name}Grid"})],
                    buttons : [ {
                        text: "确定",ref : "../saveBtn",scope:this,
                        handler : function() {
                            var selData = this.selData;
                            var oldData = this.oldData;
                            //{$comment_owner}标识
                            var $owner_idcolumn=$appName_alias.$classname.View.Running.{$owner_instance_name}Grid.getSelectionModel().getSelected().data.{$owner_idcolumn};
                            var condition={'selData':selData,"oldData":oldData,"{$owner_idcolumn}":$owner_idcolumn};
                            Ext.Msg.show({
                                title: '请等待', msg: '操作进行中，请稍后...',
                                animEl: 'loading', icon: Ext.Msg.WARNING,
                                closable: true, progress: true, progressText: '', width: 300
                            });
                            ExtService{$classname}.update{$classname}{$belong_class}(condition,function(provider, response) {
                                if (response.result.success==true) {
                                    var msg = "操作成功！";
                                    if(response.result.del){
                                        msg += "<font color=red>取消</font>了<font color=red>"+response.result.del+"</font>件关联货品,";
                                    }
                                    if(response.result.add){
                                        msg += "<font color=green>添加</font>了<font color=green>"+response.result.add+"</font>件关联货品";
                                    }
                                    Ext.Msg.alert('提示', msg);
                                } else {
                                    $appName_alias.$classname.Store.select{$belong_class}Store.removeAll();
                                    Ext.Msg.alert('提示', '操作失败！');
                                }
                                $appName_alias.$classname.View.Running.select{$belong_class}Window.hideWindow();
                                $appName_alias.$classname.View.Running.{$owner_instance_name}Grid.doSelect{$classname}();
                            });
                        }
                    }, {
                        text : "取 消",scope:this,
                        handler : function() {
                            this.hide();
                        }
                    }]
                }, config);
                $appName_alias.$classname.View.{$belong_class}View.Select{$belong_class}Window.superclass.constructor.call(this, config);
            },
            /**
            * 根据选择的{$comment_belong},改变标题
            */
            changeTitle: function(){
                var title   = "选择 {$comment_belong}";
                var selData = this.selData;
                var count   = this.objElCount(selData);
                if(count){
                    title += "（已经选择了<font color=green>"+count+"</font>件{$comment_belong}）";
                }else{
                    title += "（尚未选择任何{$comment_belong}）";
                }
                this.setTitle(title);
            },
            /**
            * 判断自定义对象元素个数
            */
            objElCount: function(obj){
                var count = 0;
                for(var n in obj){count++}
                return count;
            },
            /**
             * 确认取消图片处理
             * con:选择窗口
             */
            doHide: function (con) {
                //window初始化时会调用,此时con为null
                if(con){
                    Ext.MessageBox.show({
                        title:'提示',msg:'确定要取消么?<br /><font color=red>(所做操作将不会保存!)</font>',buttons:Ext.MessageBox.YESNO,icon:Ext.MessageBox.QUESTION,
                        params:{con:con},
                        fn:function(btn,text,opt) {
                            if(btn=='yes') {
                                con.hideWindow();
                            }
                        }
                    });
                    return false;
                }
            },
            /**
             * 隐藏窗口
             */
            hideWindow: function () {
                //移除beforehide事件，为了防止hide时进入死循环
                this.un('beforehide',this.doHide);
                this.hide();
                this.addListener('beforehide',this.doHide);
            }
        }),
        {$belong_class}Grid:Ext.extend(Ext.grid.GridPanel,{
            constructor : function(config) {
                config = Ext.apply({
                    /**
                     * 查询条件
                     */
                    filter:null,{$owner_idcolumn}:null,region : 'center',store : $appName_alias.$classname.Store.select{$belong_class}Store,sm : this.sm,
                    trackMouseOver : true,enableColumnMove : true,columnLines : true,loadMask : true,stripeRows : true,headerAsText : false,
                    loadMask : {msg : '加载数据中，请稍候...'},
                    defaults : {autoScroll : true},
                    cm : new Ext.grid.ColumnModel({
                        defaults:{width:120,sortable : true},
                        columns : [
                            this.sm,
$m2m_columns
                        ]
                    }),
                    tbar : {
                        xtype : 'container',layout : 'anchor',
                        height : 27,style:'font-size:14px',
                        defaults : {
                            height : 27,anchor : '100%'
                        },
                        items : [
                            new Ext.Toolbar({
                                enableOverflow: true,width : 80,ref:'menus',
                                defaults : {
                                    xtype : 'textfield',
                                    listeners : {
                                       specialkey : function(field, e) {
                                            if (e.getKey() == Ext.EventObject.ENTER)this.ownerCt.ownerCt.ownerCt.doSelect{$belong_class}();
                                        }
                                    }
                                },
                                items : [
                                    {text: '全部',ref:'../../isSelect',xtype:'tbsplit',iconCls : 'icon-view',enableToggle: true,
                                         toggleHandler:function(item, checked){
                                            if (checked==true){
                                                $appName_alias.$classname.View.Running.select{$belong_class}Window.{$belong_instance_name}Grid.topToolbar.menus.select.setChecked(true);
                                            }else{
                                                $appName_alias.$classname.View.Running.select{$belong_class}Window.{$belong_instance_name}Grid.topToolbar.menus.all.setChecked(true);
                                            }
                                        },
                                        menu: {
                                            items: [
                                                {text: '全部',checked: true,group: 'isSelect',ref:'../../all',
                                                    checkHandler: function(item, checked){
                                                        if (checked){
                                                            $appName_alias.$classname.View.Running.select{$belong_class}Window.{$belong_instance_name}Grid.isSelect.setText(item.text);
                                                            $appName_alias.$classname.View.Running.select{$belong_class}Window.{$belong_instance_name}Grid.filter.selectType=0;
                                                            $appName_alias.$classname.View.Running.select{$belong_class}Window.{$belong_instance_name}Grid.doSelect{$belong_class}();
                                                        }
                                                    }
                                                },
                                                {text: '未选择',checked: false,group: 'isSelect',ref:'../../unselect',
                                                    checkHandler: function(item, checked){
                                                        if (checked){
                                                            $appName_alias.$classname.View.Running.select{$belong_class}Window.{$belong_instance_name}Grid.isSelect.setText(item.text);
                                                            $appName_alias.$classname.View.Running.select{$belong_class}Window.{$belong_instance_name}Grid.filter.selectType=2;
                                                            $appName_alias.$classname.View.Running.select{$belong_class}Window.{$belong_instance_name}Grid.doSelect{$belong_class}();
                                                        }
                                                    }
                                                },
                                                {text: '已选择',checked: false,group: 'isSelect',ref:'../../select',
                                                    checkHandler: function(item, checked){
                                                        if (checked){
                                                            $appName_alias.$classname.View.Running.select{$belong_class}Window.{$belong_instance_name}Grid.isSelect.setText(item.text);
                                                            $appName_alias.$classname.View.Running.select{$belong_class}Window.{$belong_instance_name}Grid.filter.selectType=1;
                                                            $appName_alias.$classname.View.Running.select{$belong_class}Window.{$belong_instance_name}Grid.doSelect{$belong_class}();
                                                        }
                                                    }
                                                }
                                             ]
                                        }
                                    },
$m2m_filterFields
                                    {
                                        xtype : 'button',text : '查询',scope: this,
                                        handler : function() {
                                            this.doSelect{$belong_class}();
                                        }
                                    },
                                    {
                                        xtype : 'button',text : '重置',scope: this,
                                        handler : function() {
$m2m_filterReset
                                            this.filter={};
                                            this.doSelect{$belong_class}();
                                        }
                                    }
                                ]
                            })
                        ]
                    },
                    bbar: new Ext.PagingToolbar({
                        pageSize: $appName_alias.$classname.Config.PageSize,
                        store: $appName_alias.$classname.Store.select{$belong_class}Store,
                        scope:this,autoShow:true,displayInfo: true,
                        displayMsg: '当前显示 {0} - {1}条记录/共 {2}条记录。',
                        emptyMsg: "无显示数据",
                        items: [
                            {xtype:'label', text: '每页显示'},
                            {xtype:'numberfield', value:$appName_alias.$classname.Config.PageSize,minValue:1,width:35,
                                style:'text-align:center',allowBlank:false,
                                listeners:
                                {
                                    change:function(Field, newValue, oldValue){
                                        var num = parseInt(newValue);
                                        if (isNaN(num) || !num || num<1)
                                        {
                                            num = $appName_alias.$classname.Config.PageSize;
                                            Field.setValue(num);
                                        }
                                        $appName_alias.$classname.Config.PageSize = num;
                                        this.ownerCt.ownerCt.doSelect{$belong_class}();
                                    },
                                    specialKey :function(field,e){
                                        if (e.getKey() == Ext.EventObject.ENTER){
                                            var num = parseInt(field.getValue());
                                            if (isNaN(num) || !num || num<1)
                                            {
                                                num = $appName_alias.$classname.Config.PageSize;
                                            }
                                            $appName_alias.$classname.Config.PageSize = num;
                                            this.ownerCt.ownerCt.doSelect{$belong_class}();
                                        }
                                    }
                                }
                            },
                            {xtype:'label', text: '个'}
                        ]
                    })
                }, config);
                //初始化显示{$comment_belong}列表
                //this.doSelect{$belong_class}();
                $appName_alias.$classname.Store.select{$belong_class}Store.proxy=new Ext.data.DirectProxy({
                    api: {read:ExtService{$classname}.queryPage{$classname}{$belong_class}}
                });
                $appName_alias.$classname.View.{$belong_class}View.{$belong_class}Grid.superclass.constructor.call(this, config);
            },
            /**
            * SelectionModel
            */
            sm : new Ext.grid.CheckboxSelectionModel({
                listeners : {
                    selectionchange:function(sm) {
                        $appName_alias.$classname.View.Running.select{$belong_class}Window.changeTitle();
                        $appName_alias.$classname.View.Running.select{$belong_class}Window.{$belong_instance_name}Grid.changeFilter();
                    },
                    rowselect: function(sm, rowIndex, record) {
                        var sel{$belong_class}Win  = $appName_alias.$classname.View.Running.select{$belong_class}Window;
                        var selData      = sel{$belong_class}Win.selData;
                        var oldData      = sel{$belong_class}Win.oldData;
                        var $belong_idcolumn     = record.data.$belong_idcolumn;
                        //添加该货品ID
                        selData[$belong_idcolumn] = true;
                        //判断是否是已关联的货品
                        if(oldData[$belong_idcolumn]){
                            oldData[$belong_idcolumn].active = true;
                        }
                    },
                    rowdeselect: function(sm, rowIndex, record) {
                        var sel{$belong_class}Win  = $appName_alias.$classname.View.Running.select{$belong_class}Window;
                        var selData      = sel{$belong_class}Win.selData;
                        var oldData      = sel{$belong_class}Win.oldData;
                        var $belong_idcolumn     = record.data.$belong_idcolumn;
                        //删除该货品ID
                        delete selData[$belong_idcolumn];
                        //判断是否是已关联的货品
                        if(oldData[$belong_idcolumn]){
                            oldData[$belong_idcolumn].active = false;
                        }
                    }
                }
            }),
            doSelect{$belong_class} : function() {
                var tmp_sel{$belong_class}=this.filter.sel{$belong_class};
                if (this.topToolbar){
                    var {$owner_idcolumn}=this.{$owner_idcolumn};
                    if (!this.filter.selectType)this.filter.selectType=0;
{$m2m_filterdoSelect}'{$owner_idcolumn}':{$owner_idcolumn},'selectType':this.filter.selectType};
                }
                this.filter.sel{$belong_class}=tmp_sel{$belong_class};
                var condition = {'start':0,'limit':$appName_alias.$classname.Config.PageSize};
                Ext.apply(condition,this.filter);
                ExtService{$classname}.queryPage{$classname}{$belong_class}(condition,function(provider, response) {
                    if (response.result&&response.result.data) {
                        var result           = new Array();
                        result['data']       =response.result.data;
                        result['totalCount'] =response.result.totalCount;
                        $appName_alias.$classname.Store.select{$belong_class}Store.loadData(result);
                    } else {
                        $appName_alias.$classname.Store.select{$belong_class}Store.removeAll();
                        Ext.Msg.alert('提示', '无符合条件的{$comment_belong}！');
                    }
                });
            },
            /**
            * 修改查询条件
            */
            changeFilter: function(){
                var selData =$appName_alias.$classname.View.Running.select{$belong_class}Window.selData;
                var selArr  = new Array();
                for(var x in selData){
                    selArr.push(x);
                }
                this.filter.sel{$belong_class} = selArr.join(",");
            }
        }),
    },
MANY2MANY;

    /**
     * 多对多选择菜单
     * @var mixed
     */
    $jsMany2ManyMenu=<<<MANY2MANYMENU
,'-',{
                                    text : '选择{$comment_belong}',ref:'../../t{$belong_instance_name}',iconCls : 'icon-edit',disabled : true,
                                    handler : function() {
                                        if($appName_alias.$classname.View.Running.select{$belong_class}Window==null || $appName_alias.$classname.View.Running.select{$belong_class}Window.hidden){
                                            this.show{$belong_class}();
                                        }else{
                                            this.hide{$belong_class}();
                                        }
                                    }
                                }
MANY2MANYMENU;

    /**
     * 多对多选择菜单显示隐藏
     * @var mixed
     */
    $jsMany2ManyMenuShowHide=<<<MANY2MANYMENUShowHide

                    this.grid.t{$belong_instance_name}.setDisabled(sm.getCount() != 1);
MANY2MANYMENUShowHide;

    $filterwordNames    =$m2m_filters["filterwordNames"];
    $m2m_filterSelectionDoSelect="";
    foreach ($filterwordNames as $filterwordName) {
        $m2m_filterSelectionDoSelect.="                $appName_alias.$classname.View.Running.select{$belong_class}Window.{$belong_instance_name}Grid.topToolbar.$filterwordName.setValue(\"\");\r\n";
    }

    /**
     * 多对多显示隐藏窗口
     * @var mixed
     */
    $jsMany2ManyShowHide=<<<MANY2MANYSHOWHIDE

        /**
         * 显示{$comment_belong}
         */
        show{$belong_class}:function(){
            if ($appName_alias.$classname.View.Running.select{$belong_class}Window==null){
                $appName_alias.$classname.View.Running.select{$belong_class}Window=new $appName_alias.$classname.View.{$belong_class}View.Select{$belong_class}Window();
            }
            var {$owner_idcolumn}=$appName_alias.$classname.View.Running.{$owner_instance_name}Grid.getSelectionModel().getSelected().data.{$owner_idcolumn};

            //关联{$comment_belong}ID组成的字符串
            var {$belong_instance_name}Str    = $appName_alias.$classname.View.Running.{$owner_instance_name}Grid.getSelectionModel().getSelected().data.{$belong_instance_name}Str;
            var selData    = {};
            var oldData    = {};
            if({$belong_instance_name}Str){
                var {$belong_instance_name}Arr = {$belong_instance_name}Str.split(",");
                for(var i=0;i<{$belong_instance_name}Arr.length;i++){
                    selData[{$belong_instance_name}Arr[i]] = true;//ture为已存在的关联货品
                    oldData[{$belong_instance_name}Arr[i]] = {};
                    oldData[{$belong_instance_name}Arr[i]].active = true;
                }
            }
            var sel{$belong_class}Win     = $appName_alias.$classname.View.Running.select{$belong_class}Window;
            var sel{$belong_class}Grid    = sel{$belong_class}Win.{$belong_instance_name}Grid;
            sel{$belong_class}Win.selData = selData;
            sel{$belong_class}Win.oldData = oldData;
            sel{$belong_class}Win.changeTitle();//根据选择的货品,修改标题

            sel{$belong_class}Grid.{$owner_idcolumn} = {$owner_idcolumn};
            if (sel{$belong_class}Win.hidden){
$m2m_filterSelectionDoSelect                sel{$belong_class}Grid.filter = {sel{$belong_class}:{$belong_instance_name}Str};
                sel{$belong_class}Grid.topToolbar.menus.all.setChecked(true);
                sel{$belong_class}Grid.isSelect.toggle(false);
                $appName_alias.$classname.Store.select{$belong_class}Store.removeAll();
            }
            sel{$belong_class}Grid.doSelect{$belong_class}();
            sel{$belong_class}Win.show();
        },
        /**
         * 隐藏{$comment_belong}
         */
        hide{$belong_class}:function(){
            if ($appName_alias.$classname.View.Running.select{$belong_class}Window!=null){
                $appName_alias.$classname.View.Running.select{$belong_class}Window.hide();
            }
        },
MANY2MANYSHOWHIDE;

    /**
     * 多对多创建运行窗口
     * @var mixed
     */
    $jsMany2ManyRunningWindow=<<<M2MRW

        /**
         * 推荐{$comment_belong}
         */
        select{$belong_class}Window:null,
M2MRW;

    /**
     * 行选择控制隐藏
     */
    $jsMany2ManyRowSelect=<<<M2MROWSELECT

                        this.grid.hide{$belong_class}();
M2MROWSELECT;

    /**
     * 行选择控制显示
     */
    $jsMany2ManyRowSelectElse=<<<M2MROWSELECTELSE

                            if($appName_alias.$classname.View.Running.select{$belong_class}Window && !$appName_alias.$classname.View.Running.select{$belong_class}Window.hidden){
                                this.grid.show{$belong_class}();
                            }
M2MROWSELECTELSE;
}
?>
