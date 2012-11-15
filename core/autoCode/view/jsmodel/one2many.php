<?php

if (Config_AutoCode::RELATION_VIEW_FULL)
{
    if (!self::isMany2ManyShowHasMany($current_classname))
    {
        $jsOne2ManyContent="";
        return;
    }
    $realId_relation=DataObjectSpec::getRealIDColumnName($key); 
    self::$relationStore=$relationStore;
    $editWindow_relationVars = self::model_fieldLables($appName_alias,$current_classname,$fieldInfo,$realId,"    ");
    $relationStore=self::$relationStore;
    $fieldLabels_relation    = $editWindow_relationVars["fieldLabels"];
    $treeLevelVisible_Add    = $editWindow_relationVars["treeLevelVisible_Add"];     
    $treeLevelVisible_Add   =str_replace("{$appName_alias}.$current_classname.", "{$appName_alias}.$classname.", $treeLevelVisible_Add);     
  
    $treeLevelVisible_Update = $editWindow_relationVars["treeLevelVisible_Update"];
    $treeLevelVisible_Update   =str_replace("{$appName_alias}.$current_classname.", "{$appName_alias}.$classname.", $treeLevelVisible_Update);  

    $textarea_Vars=self::model_textareaOnlineEditor($appName_alias,$current_classname,$current_instancename,$fieldInfo,"    ");
    $textareaOnlineditor_Add   =$textarea_Vars["textareaOnlineditor_Add"];    
    $textareaOnlineditor_Add   =str_replace("{$appName_alias}.$current_classname.", "{$appName_alias}.$classname.", $textareaOnlineditor_Add);     
    $textareaOnlineditor_Add   =str_replace("View.EditWindow.", "View.{$current_classname}View.EditWindow.",$textareaOnlineditor_Add);    
 
 
    $textareaOnlineditor_Update=$textarea_Vars["textareaOnlineditor_Update"]; 
    $textareaOnlineditor_Update   =str_replace("{$appName_alias}.$current_classname.", "{$appName_alias}.$classname.", $textareaOnlineditor_Update);    
    $textareaOnlineditor_Update   =str_replace("View.EditWindow.", "View.{$current_classname}View.EditWindow.", $textareaOnlineditor_Update);    
 
    $textareaOnlineditor_Replace=$textarea_Vars["textareaOnlineditor_Replace"]; 
    $textareaOnlineditor_Replace   =str_replace("{$appName_alias}.$current_classname.", "{$appName_alias}.$classname.", $textareaOnlineditor_Replace);    
    $textareaOnlineditor_Replace   =str_replace("View.EditWindow.", "View.{$current_classname}View.EditWindow.", $textareaOnlineditor_Replace);    
 
    $textareaOnlineditor_Save=$textarea_Vars["textareaOnlineditor_Save"]; 
    $textareaOnlineditor_Save   =str_replace("{$appName_alias}.$current_classname.", "{$appName_alias}.$classname.", $textareaOnlineditor_Save);    
    $textareaOnlineditor_Save   =str_replace("View.EditWindow.", "View.{$current_classname}View.EditWindow.", $textareaOnlineditor_Save);    
 
    $textareaOnlineditor_Reset=$textarea_Vars["textareaOnlineditor_Reset"]; 
    $textareaOnlineditor_Reset   =str_replace("{$appName_alias}.$current_classname.", "{$appName_alias}.$classname.", $textareaOnlineditor_Reset);    
    $textareaOnlineditor_Reset   =str_replace("View.EditWindow.", "View.{$current_classname}View.EditWindow.", $textareaOnlineditor_Reset);    
 
    $tbar=<<<TBAR
        
                    tbar : {
                        xtype : 'container',layout : 'anchor',
                        height : 27,style:'font-size:14px',
                        defaults : {
                            height : 27,anchor : '100%'
                        },
                        items : [
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
                                        text : '添加{$table_comment12n}',iconCls : 'icon-add',
                                        handler : function() {
                                            this.add{$current_classname}();
                                        }
                                    },'-',{
                                        text : '修改{$table_comment12n}',ref: '../../btnUpdate',iconCls : 'icon-edit',disabled : true,
                                        handler : function() {
                                            this.update{$current_classname}();
                                        }
                                    },'-',{
                                        text : '删除{$table_comment12n}', ref: '../../btnRemove',iconCls : 'icon-delete',disabled : true,
                                        handler : function() {
                                            this.delete{$current_classname}();
                                        }
                                    },'-']}
                        )]
                    },
TBAR;
    $sm="sm : this.sm,";
    $sm_incm="\r\n                            this.sm,";
    $sm_impl=<<<SM

            /**
             * 行选择器
             */
            sm : new Ext.grid.CheckboxSelectionModel({
                listeners : {
                    selectionchange:function(sm) {
                        // 判断删除和更新按钮是否可以激活
                        this.grid.btnRemove.setDisabled(sm.getCount() < 1);
                        this.grid.btnUpdate.setDisabled(sm.getCount() != 1);
                    }
                }
            }),
SM;

    $relation_editwindow=<<<EDITWINDOW

        /**
         *  当前创建的{$table_comment12n}编辑窗口
         */
        edit_window:null,
        /**
         * 编辑窗口：新建或者修改{$table_comment12n}
         */
        EditWindow:Ext.extend(Ext.Window,{
            constructor : function(config) {
                config = Ext.apply({
                    /**
                     * 自定义类型:保存类型
                     * 0:保存窗口,1:修改窗口
                     */
                    savetype:0,closeAction : "hide",constrainHeader:true,maximizable: true,collapsible: true,
                    width : 450,height : 550,minWidth : 400,minHeight : 450,
                    layout : 'fit',plain : true,buttonAlign : 'center',
                    defaults : {autoScroll : true},
                    listeners:{
                        beforehide:function(){
                            this.editForm.form.getEl().dom.reset();
                        }{$textareaOnlineditor_Replace}
                    },
                    items : [
                        new Ext.form.FormPanel({   
                            ref:'editForm',layout:'form',
                            labelWidth : 100,autoWidth : true,labelAlign : "center",
                            bodyStyle : 'padding:5px 5px 0',align : "center",
                            api : {},
                            defaults : {
                                xtype : 'textfield',anchor:'98%'
                            },
                            items : [
$fieldLabels_relation
                            ]
                        })
                    ],
                    buttons : [{
                        text: "",ref : "../saveBtn",scope:this,
                        handler : function() { 
{$textareaOnlineditor_Save}
                            if (!this.editForm.getForm().isValid()) {
                                return;
                            }
                            editWindow=this;
                            if (this.savetype==0){
                                this.editForm.api.submit=ExtService{$current_classname}.save;
                                this.editForm.getForm().submit({
                                    success : function(form, action) {
                                        Ext.Msg.alert("提示", "保存成功！");
                                        $appName_alias.$classname.View.Running.{$current_instancename}Grid.doSelect{$current_classname}();
                                        form.reset();
                                        editWindow.hide();
                                    },
                                    failure : function(form, action) {
                                        Ext.Msg.alert('提示', '失败');
                                    }
                                });
                            }else{
                                this.editForm.api.submit=ExtService{$current_classname}.update;
                                this.editForm.getForm().submit({
                                    success : function(form, action) {
                                        Ext.Msg.show({title:'提示',msg: '修改成功！',buttons: {yes: '确定'},fn: function(){
                                            $appName_alias.$classname.View.Running.{$current_instancename}Grid.bottomToolbar.doRefresh();
                                        }});
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
                            this.editForm.form.loadRecord($appName_alias.$classname.View.Running.{$current_instancename}Grid.getSelectionModel().getSelected());
{$textareaOnlineditor_Reset}                             
                        }
                    }]
                }, config);
                $appName_alias.$classname.View.{$current_classname}View.EditWindow.superclass.constructor.call(this, config);
            }
        }),
EDITWINDOW;

    $relation_editwindow=str_replace($appName_alias.".".$current_classname, $appName_alias.".".$classname, $relation_editwindow);
    $addupdateDeleteEditWindow=<<<ADDUPADTEDITWINDOW
,
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
             * 新建{$table_comment12n}
             */
            add{$current_classname} : function(){
                if ($appName_alias.$classname.View.{$current_classname}View.edit_window==null){
                    $appName_alias.$classname.View.{$current_classname}View.edit_window=new $appName_alias.$classname.View.{$current_classname}View.EditWindow();
                }
                $appName_alias.$classname.View.{$current_classname}View.edit_window.resetBtn.setVisible(false);
                $appName_alias.$classname.View.{$current_classname}View.edit_window.saveBtn.setText('保 存');
                $appName_alias.$classname.View.{$current_classname}View.edit_window.setTitle('添加{$table_comment12n}');
                $appName_alias.$classname.View.{$current_classname}View.edit_window.savetype=0;
                $appName_alias.$classname.View.{$current_classname}View.edit_window.{$realId_relation}.setValue("");
                var company_id = $appName_alias.$classname.View.Running.{$instancename}Grid.getSelectionModel().getSelected().data.{$realId};
                $appName_alias.$classname.View.{$current_classname}View.edit_window.{$realId}.setValue(company_id);
{$textareaOnlineditor_Add}{$treeLevelVisible_Add}
                $appName_alias.$classname.View.{$current_classname}View.edit_window.show();
                $appName_alias.$classname.View.{$current_classname}View.edit_window.maximize();
            },
            /**
             * 编辑{$table_comment12n}时先获得选中的{$table_comment12n}信息
             */
            update{$current_classname} : function() {
                if ($appName_alias.$classname.View.{$current_classname}View.edit_window==null){
                    $appName_alias.$classname.View.{$current_classname}View.edit_window=new $appName_alias.$classname.View.{$current_classname}View.EditWindow();
                }
                $appName_alias.$classname.View.{$current_classname}View.edit_window.saveBtn.setText('修 改');
                $appName_alias.$classname.View.{$current_classname}View.edit_window.resetBtn.setVisible(true);
                $appName_alias.$classname.View.{$current_classname}View.edit_window.setTitle('修改{$table_comment12n}');
                $appName_alias.$classname.View.{$current_classname}View.edit_window.editForm.form.loadRecord(this.getSelectionModel().getSelected());
                $appName_alias.$classname.View.{$current_classname}View.edit_window.savetype=1;
{$textareaOnlineditor_Update}{$treeLevelVisible_Update}
                $appName_alias.$classname.View.{$current_classname}View.edit_window.show();
                $appName_alias.$classname.View.{$current_classname}View.edit_window.maximize();
            },
            /**
             * 删除{$table_comment12n}
             */
            delete{$current_classname} : function() {
                Ext.Msg.confirm('提示', '确实要删除所选的{$table_comment12n}吗?', this.confirmDelete{$current_classname},this);
            },
            /**
             * 确认删除{$table_comment12n}
             */
            confirmDelete{$current_classname} : function(btn) {
                if (btn == 'yes') {  
                    var del_{$realId_relation}s ="";
                    var selectedRows    = this.getSelectionModel().getSelections();
                    for ( var flag = 0; flag < selectedRows.length; flag++) {
                        del_{$realId_relation}s=del_{$realId_relation}s+selectedRows[flag].data.{$realId_relation}+",";
                    }
                    ExtService{$current_classname}.deleteByIds(del_{$realId_relation}s);
                    this.doSelect{$current_classname}();
                    Ext.Msg.alert("提示", "删除成功！");
                }
            }
ADDUPADTEDITWINDOW;

}else{
    $tbar   ="";
    $sm     ="";
    $sm_impl="";
    $addupdateDeleteEditWindow="";    
}

$jsOne2ManyContent=<<<ONE2MANY

    /**
     * 视图：{$table_comment12n}列表
     */
    {$current_classname}View:{{$relation_editwindow}
        /**
         * 查询条件
         */
        filter:null,
        /**
         * 视图：{$table_comment12n}列表
         */
        Grid:Ext.extend(Ext.grid.GridPanel, {
            constructor : function(config) {
                config = Ext.apply({
                    store : $appName_alias.$classname.Store.{$current_instancename}Store,$sm
                    frame : true,trackMouseOver : true,enableColumnMove : true,columnLines : true,
                    loadMask : true,stripeRows : true,headerAsText : false,
                    defaults : {autoScroll : true},
                    cm : new Ext.grid.ColumnModel({
                        defaults:{
                            width:120,sortable : true
                        },
                        columns : [$sm_incm
$columns_relation
                        ]
                    }),$tbar
                    bbar: new Ext.PagingToolbar({
                        pageSize: $appName_alias.$classname.Config.PageSize,
                        store: $appName_alias.$classname.Store.{$current_instancename}Store,scope:this,autoShow:true,displayInfo: true,
                        displayMsg: '当前显示 {0} - {1}条记录/共 {2}条记录。',emptyMsg: "无显示数据",
                        items: [
                            {xtype:'label', text: '每页显示'},
                            {xtype:'numberfield', value:$appName_alias.$classname.Config.PageSize,minValue:1,width:35,style:'text-align:center',allowBlank: false,
                                listeners:
                                {
                                    change:function(Field, newValue, oldValue){
                                        var num = parseInt(newValue);
                                        if (isNaN(num) || !num || num<1)
                                        {
                                            num = $appName_alias.$classname.Config.PageSize;
                                            Field.setValue(num);
                                        }
                                        this.ownerCt.pageSize= num;
                                        $appName_alias.$classname.Config.PageSize = num;
                                        this.ownerCt.ownerCt.doSelect{$current_classname}();
                                    },
                                    specialKey :function(field,e){
                                        if (e.getKey() == Ext.EventObject.ENTER){
                                            var num = parseInt(field.getValue());
                                            if (isNaN(num) || !num || num<1)num = $appName_alias.$classname.Config.PageSize;
                                            this.ownerCt.pageSize= num;
                                            $appName_alias.$classname.Config.PageSize = num;
                                            this.ownerCt.ownerCt.doSelect{$current_classname}();
                                        }
                                    }
                                }
                            },{xtype:'label', text: '个'}
                        ]
                    })
                }, config);
                /**
                 * {$table_comment12n}数据模型获取数据Direct调用
                 */
                $appName_alias.$classname.Store.{$current_instancename}Store.proxy=new Ext.data.DirectProxy({
                    api: {read:ExtService{$current_classname}.queryPage{$current_classname}}
                });
                $appName_alias.$classname.View.{$current_classname}View.Grid.superclass.constructor.call(this, config);
            },$sm_impl
            /**
             * 查询符合条件的{$table_comment12n}
             */
            doSelect{$current_classname} : function() {
                if ($appName_alias.$classname.View.Running.{$instancename}Grid&&$appName_alias.$classname.View.Running.{$instancename}Grid.getSelectionModel().getSelected()){
                    var $realId = $appName_alias.$classname.View.Running.{$instancename}Grid.getSelectionModel().getSelected().data.{$realId};
                    var condition = {'$realId':$realId,'start':0,'limit':$appName_alias.$classname.Config.PageSize};
                    this.filter       ={'{$realId}':{$realId}};
                    ExtService{$current_classname}.queryPage{$current_classname}(condition,function(provider, response) {
                        if (response.result){
                            if (response.result.data) {
                                var result           = new Array();
                                result['data']       =response.result.data;
                                result['totalCount'] =response.result.totalCount;
                                $appName_alias.$classname.Store.{$current_instancename}Store.loadData(result);
                            } else {
                                $appName_alias.$classname.Store.{$current_instancename}Store.removeAll();
                                Ext.Msg.alert('提示', '无符合条件的{$table_comment12n}！');
                            }
                            if ($appName_alias.$classname.Store.{$current_instancename}Store.getTotalCount()>$appName_alias.$classname.Config.PageSize){
                                 $appName_alias.$classname.View.Running.{$current_instancename}Grid.bottomToolbar.show();
                            }else{
                                 $appName_alias.$classname.View.Running.{$current_instancename}Grid.bottomToolbar.hide();
                            }
                            $appName_alias.$classname.View.Running.{$instancename}Grid.ownerCt.doLayout();
                        }
                    });
                }
            }$addupdateDeleteEditWindow
        })
    },
ONE2MANY;

?>
