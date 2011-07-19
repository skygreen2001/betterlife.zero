// Application instance for showing user-feedback messages.
var App = new Ext.App({});

// Create a standard HttpProxy instance.
var proxy = new Ext.data.HttpProxy({
    url: 'home/admin/src/services/ajax/extjs/remote/service.php/ResourceLibrary'
});

// Typical JsonReader.  Notice additional meta-data params for defining the core attributes of your json-response
var reader = new Ext.data.JsonReader({
    totalProperty: 'total',
    successProperty: 'success',
    idProperty: 'id',
    root: 'data',
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

// load the store immeditately
store.load();

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

// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
var resourceLibraryColumns =  [
    new Ext.grid.RowNumberer(),
    {header: "库名称", width: 100, sortable: true, dataIndex: 'name', editor: new Ext.form.TextField({})},
    {header: "已加载", width: 150, sortable: true, dataIndex: 'open', xtype: 'checkcolumn',editor: {xtype:'checkbox'}},
    {header: "初始化方法", width: 150, sortable: true, dataIndex: 'init', editor: new Ext.form.TextField({})},
    {header: "必须加载", width: 150, sortable: true, dataIndex: 'required', xtype: 'checkcolumn',editor:{xtype:'checkbox'}}
];

    // use RowEditor for editing
    var editor = new Ext.ux.grid.RowEditor({
        saveText: '修改'
    });

    // Create a typical GridPanel with RowEditor plugin
    var resourceLibraryGrid = new Ext.grid.GridPanel({
        iconCls: 'icon-grid',
        frame: true,
        title: '资源库管理',
        width:800,
        height: 600,
        store: store,
        plugins: [editor],
        columns : resourceLibraryColumns,
        tbar: [{
            ref: '../addBtn',
            text: '新增',
            iconCls: 'silk-add',
            handler: onAdd
        }, '-', {
            ref: '../removeBtn',
            text: '删除',
            disabled: true,
            iconCls: 'silk-delete',
            handler: onDelete
        }, '-'],
        viewConfig: {
            forceFit: true
        }
    });

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
        
        var s = resourceLibraryGrid.getSelectionModel().getSelections();
        for(var i = 0, r; r = s[i]; i++){             
            resourceLibraryGrid.store.remove(r);
        }                             
    }
    
     
    
Ext.onReady(function() {
    Ext.QuickTips.init();

    resourceLibraryGrid.render('resourceLibrary-grid'),
    
    resourceLibraryGrid.getSelectionModel().on('selectionchange', function(sm){
        resourceLibraryGrid.removeBtn.setDisabled(sm.getCount() < 1);
    });
});
