// Application instance for showing user-feedback messages.
var App = new Ext.App({});

// Create a standard HttpProxy instance.
var proxy = new Ext.data.HttpProxy({
    url: 'home/admin/src/services/ajax/extjs/remote/service.php/ResourceLibrary'
});

// Typical JsonReader.  Notice additional meta-data params for defining the core attributes of your json-response
var reader = new Ext.data.JsonReader({
    totalProperty: 'totalCount',
    successProperty: 'success',
    idProperty: 'id',
    root: 'data',
    remoteSort: true, 
    messageProperty: 'message'  // <-- New "messageProperty" meta-data
}, [
    {name: 'id'},
    {name: 'name', allowBlank: false},
    {name: 'open', type: 'bool'},
    {name: 'required',type: 'bool'},
    {name: 'init', allowBlank: false}
]);

// The new DataWriter component.
var writer = new Ext.data.JsonWriter({
    encode: false   // <-- don't return encoded JSON -- causes Ext.Ajax#request to send data using jsonData config rather than HTTP params
});

// Typical Store collecting the Proxy, Reader and Writer together.
var store = new Ext.data.Store({
    id: 'resourceLibrary',
    restful: true,     // <-- This Store is RESTful
    proxy: proxy,
    reader: reader,
    writer: writer    // <-- plug a DataWriter into the store just as you would a Reader
});         
store.setDefaultSort('id', 'desc');
////
// ***New*** centralized listening of DataProxy events "beforewrite", "write" and "writeexception"
// upon Ext.data.DataProxy class.  This is handy for centralizing user-feedback messaging into one place rather than
// attaching listenrs to EACH Store.
//
// Listen to all DataProxy beforewrite events
//
Ext.data.DataProxy.addListener('beforewrite', function(proxy, action) {
    //App.setAlert(App.STATUS_NOTICE, "Before " + action);
});

////
// all write events
//
Ext.data.DataProxy.addListener('write', function(proxy, action, result, res, rs) {
    resourceLibraryGrid.addBtn.setDisabled(false);
    App.setAlert(true, action + ':' + res.message);
});

////
// all exception events
//
Ext.data.DataProxy.addListener('exception', function(proxy, type, action, options, res) {
    App.setAlert(false, "发生异常，执行： " + action);
});

// pluggable renders  
function renderOperation(value, p, record){
    return String.format(
            '<a href="index.php?go=admin.system.library.view" target="_blank">浏览</a>|'+
            '<a href="index.php?go=admin.system.library.edit" target="_blank">编辑</a>|'+ 
            '<a href="index.php?go=admin.system.library.delete" target="_blank">删除</a>', 
             record.data.id);
}

var sm = new Ext.grid.CheckboxSelectionModel();  // add checkbox column  
 
/** 解决了CheckboxSelectionModel和RowEditor的冲突问题
 * @link http://www.sencha.com/forum/showthread.php?116823-OPEN-1419-RowEditor-CheckboxSelectionModel-causes-error.
 * @link http://www.sencha.com/forum/showthread.php?115154-RowEditor-and-CheckboxSelectionModel-together-Problem
 */
Ext.applyIf(sm, {
  getEditor: function() {
    return false;
  }
});  
// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
var resourceLibraryColumns =  new Ext.grid.ColumnModel([
    new Ext.grid.RowNumberer(),           
    sm, 
    {header: "库名称", width: 100, sortable: true, dataIndex: 'name', editor: new Ext.form.TextField({})},
    {header: "已加载", width: 150, sortable: true, dataIndex: 'open', xtype: 'checkcolumn',editor: {xtype:'checkbox'}},
    {header: "初始化方法", width: 150, sortable: true, dataIndex: 'init', editor: new Ext.form.TextField({})},
    {header: "必须加载", width: 150, sortable: true, dataIndex: 'required', xtype: 'checkcolumn',editor:{xtype:'checkbox'}},
    {header:"操作" , width:150, id:'id',dataIndex: 'id', align:'center',renderer:renderOperation,sortable: false}
]);

// use RowEditor for editing
var editor = new Ext.ux.grid.RowEditor({
    saveText: '修改',
    clicksToEdit: 2
});     

// Create a typical GridPanel with RowEditor plugin
var resourceLibraryGrid = new Ext.grid.GridPanel({
    iconCls: 'icon-grid',
    //frame: true,
    collapsible: true,        
    title: '资源库管理',
    width: 800,
    height: 600,
    store: store,
    plugins: [editor],          
    //columns : resourceLibraryColumns,
    sm:sm,
    cm:resourceLibraryColumns,          
    trackMouseOver:true,
    loadMask:true,//{msg:'正在加载数据，请稍侯……'}
    stripeRows:true,         
    tbar: [{
            ref: '../reverseSelectBtn',
            text: '反选',          
            handler: onReverseSelect
        }, '-',{
            ref: '../addBtn',
            text: '新增',
            iconCls: 'silk-add',
            handler: onAdd
        }, '-', {
            ref: '../removeBtn',
            text: '批量删除',
            disabled: true,
            iconCls: 'silk-delete',
            handler: onDelete
        }, '-'],
    bbar: new Ext.PagingToolbar({
        pageSize: 10,
        store: store,
        autoShow:true,
        displayInfo: true,
        displayMsg: '当前显示 {0} - {1}条记录 /共 {2}条记录',
        emptyMsg: "无显示数据"
    }),   
    viewConfig: {
        forceFit: true
    }
}); 


function onReverseSelect() {
    for (var i = resourceLibraryGrid.getView().getRows().length - 1; i >= 0; i--) {
        if (sm.isSelected(i)) {
            sm.deselectRow(i);
        }else {
            sm.selectRow(i, true);
        }
    }
}
/**
 * 新增
 */
function onAdd(btn, ev) {
    resourceLibraryGrid.addBtn.setDisabled(true);
    var u = new resourceLibraryGrid.store.recordType({
        name : '',
        init : '',
        open : false,
        required : false
    });
    editor.stopEditing();
    resourceLibraryGrid.store.insert(0, u);
    editor.startEditing(0);
}
/**
 * 删除
 */
function onDelete() {
//        删除单条记录        
//        var rec = resourceLibraryGrid.getSelectionModel().getSelected();
//        if (!rec) {
//            return false;
//        }
//        resourceLibraryGrid.store.remove(rec); 
    Ext.MessageBox.confirm('提示', '确实要删除所选的记录吗?',showResult);  
}

function showResult(btn) {  
      //确定要删除你选定项的信息  
     if(btn=='yes')  
     {  
        var selectedRows  = resourceLibraryGrid.getSelectionModel().getSelections();        
        for(var i = 0; i<selectedRows.length; i++){    
            resourceLibraryGrid.store.remove(selectedRows[i]);
        }   
     } 
     resourceLibraryGrid.getView().refresh();//刷新整个grid视图,重新排序.
};     
    
Ext.onReady(function() {
    Ext.QuickTips.init(); 
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
    Ext.Direct.addProvider(Ext.app.REMOTING_API);
     
    //resourceLibraryGrid.render('resourceLibrary-grid'),

    // load the store
    //store.load();
    store.load({params:{start:0, limit:10}});
    
    resourceLibraryGrid.getSelectionModel().on('selectionchange', function(sm){
        resourceLibraryGrid.removeBtn.setDisabled(sm.getCount() < 1);
    });
    

   searchForm = new Ext.form.FormPanel({
        labelAlign: 'left',
        frame:true,//True表示为面板的边框外框可自定义的，false表示为边框可1px的点线,这个属性很重要，不显式地设置为true时，FormPanel中的指定的items和buttons的背景色会不一致
        labelWidth: 55,
        headerAsText:false,//是否显示标题栏
        bodyStyle:'padding:5px 5px 0',
        height:85,
        api: {
            // The server-side must mark the submit handler a-s a 'formHandler'
            submit: SystemService.doLibrarySelect//表单数据提交，service.config.xml中要加'formHandler'=>true
        },
        items: [{
            layout:'column',
            items:[{
                columnWidth:.33,
                layout:'form',
                items:[{
                    fieldLabel: '用户名',
                    anchor:'90%',
                    xtype:'textfield',
                    name: 'username'

                }]
            }, {
                columnWidth:.33,
                layout:'form',
                items:[{
                    fieldLabel: '性别',
                    anchor:'90%',
                    xtype:'textfield',
                    name: 'realName'

                }]
            },{
                columnWidth:.33,
                layout:'form',
                items:[{
                    fieldLabel: '手机',
                    anchor:'90%',
                    xtype:'textfield',
                    name: 'phone'
                            
                }]
            }]
                    
        },{
            layout:'column',
            items:[{
                columnWidth:.33,
                layout:'form',
                items:[{
                    fieldLabel: '邮件',
                    xtype:'textfield',
                    anchor:'90%',
                    name: 'email'

                }]
            },{
                columnWidth:.33,
                layout:'form',
                items:[{
                    fieldLabel: '应用名',
                    xtype:'textfield',
                    anchor:'90%',
                    name: 'appName'

                }]
            },{
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
                                searchForm.getForm().submit({
                                    success:function(form, action) {//表单提交成功后,调用的函数.参数分为两个,一个是提交的表单对象,另一个是JSP返回的参数值对象
                                        store.loadData(action.result.data);
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
    
    
    
    centerPanel=[{                    
            region:'center',       
            id: 'centerPanel',
            contenEl:'resourceLibrary-grid',
            height:'100%', 
            headerAsText:false,
            defaults:{
                margins:'5 5 5 5'
            },//定义边距与间距
            layout:{
                type:'vbox',
                align:'stretch'
            },
            items:[searchForm,resourceLibraryGrid]
            }];   
    var viewport = new Ext.Viewport({
        layout: 'border',
        items: [      
          centerPanel
        ]
    });    
    viewport.doLayout();
    setTimeout(function(){
        Ext.get('loading').remove();
        Ext.get('loading-mask').fadeOut({
            remove:true
        });
    }, 250);       
    
});
