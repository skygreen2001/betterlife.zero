Ext.ns("Betterlife.Admin.Library");

Bb = Betterlife.Admin;
/**
 * 资源库
 */
Bb.Library = {
	/**
	 * 全局配置
	 */
	Config : {
		/**
		 * 分页:每页显示记录数
		 */
		PageSize : 5
	},
	/**
	 * 显示用户操作回馈的应用实例
	 */
	App : new Ext.App({})
};

/**
 * 数据模型设置
 */
Bb.Library.Store = {
	// Typical Store collecting the Proxy, Reader and Writer together.
	libraryStore : new Ext.data.Store({
		id : 'resourceLibrary',
		restful : true, // <-- This Store is RESTful
		proxy : new Ext.data.HttpProxy({
			url : 'home/admin/src/services/ajax/extjs/remote/service.php/ResourceLibrary',
			// centralized listening of DataProxy events "beforewrite", "write"
			// and "writeexception" upon Ext.data.DataProxy class.
			listeners : {
				// /Listen to all DataProxy beforewrite events
				'beforewrite' : function(proxy, action) {
					// Bb.Library.App.setAlert(Bb.Library.App.STATUS_NOTICE, "在
					// " + action + "之前");
				},
				// / all write events
				'write' : function(proxy, action, result, res, rs) {
					Bb.Library.App.setAlert(true, action + ':' + res.message);
				},
				// / all exception events
				"exception" : function(proxy, type, action, options, res) {
					// this.rejectChanges();
					Bb.Library.App.setAlert(false, "发生异常，执行： " + action);
				}
			}
		}),
		autoLoad : {
			params : {
				start : 0,
				limit : Bb.Library.Config.PageSize
			}
		},
		autoSave : false,
		autoDestroy : true,
		reader : new Ext.data.JsonReader({
			totalProperty : 'totalCount',
			successProperty : 'success',
			idProperty : 'id',
			root : 'data',
			remoteSort : true,
			messageProperty : 'message',
			fields : [{
				name : 'id',
				type : 'string'
			}, {
				name : 'name',
				type : 'string'
			},
			{
				name : 'open',
				type : 'bool'
			}, {
				name : 'required',
				type : 'bool'
			}, {
				name : 'init',
				type : 'string'
			}]
		}),
		// The new DataWriter component.
		writer : new Ext.data.JsonWriter({
			/**
			 * Don't return encoded JSON -- causes Ext.Ajax#request to send data
			 * using jsonData config rather than HTTP params
			 * @type Boolean
			 */
			encode : false
				// ,writeAllFields: false
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
	})
}

/**
 * 创建一个典型的具备RowEditor Plugin的 GridPanel 类
 */
Bb.Library.Grid = Ext.extend(Ext.grid.GridPanel, {
	constructor : function(config) {
		Bb.Library.Store.libraryStore.setDefaultSort('id', 'desc');
		/**
		 * 解决了CheckboxSelectionModel和RowEditor的冲突问题
		 * 
		 * @link http://www.sencha.com/forum/showthread.php?116823-OPEN-1419-RowEditor-CheckboxSelectionModel-causes-error.
		 * @link http://www.sencha.com/forum/showthread.php?115154-RowEditor-and-CheckboxSelectionModel-together-Problem
		 */
		Ext.applyIf(this.sm, {
			getEditor : function() {
				return false;
			}
		});    
		config = Ext.apply({
			region : 'center',
			iconCls : 'icon-grid',
			frame : true,
			title : '资源库管理',
			width : 800,
			height : 400,
			store : Bb.Library.Store.libraryStore,
			plugins : [this.editor],
			defaults : {
				autoScroll : true
			},
			cm : new Ext.grid.ColumnModel({
			    columns : [
                    this.rm, 
                    this.sm, 
                    {
						header : "库名称",
						width : 100,
						sortable : true,
						dataIndex : 'name',
						editor : new Ext.form.TextField({})
					}, {
						header : "已加载",
						width : 150,
						sortable : true,
						dataIndex : 'open',
						xtype : 'checkcolumn',
						editor : {
							xtype : 'checkbox'
						}
					}, {
						header : "初始化方法",
						width : 150,
						sortable : true,
						dataIndex : 'init',
						editor : new Ext.form.TextField({})
					}, {
						header : "必须加载",
						width : 150,
						sortable : true,
						dataIndex : 'required',
						xtype : 'checkcolumn',
						editor : {
							xtype : 'checkbox'
						}
					}
                    /**, {
						header : "操作",
						width : 150,
						id : 'id',
						align : 'center',
						renderer : this.renderOperation,
						sortable : false
					}*/
                    ]
				}),
			sm : this.sm,
			trackMouseOver : true,
			enableColumnMove : true,
			columnLines : true,
			loadMask : true,// {msg:'正在加载数据，请稍侯……'}
			stripeRows : true,
			tbar : [{
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
					iconCls : 'silk-add',
					handler : this.onAdd
				}, {
					xtype : 'tbseparator'
				}, {
					id : 'removeBtn',
					scope: this,  
					text : '删除',
					disabled : true,
					iconCls : 'silk-delete',
					handler : this.onDelete
				}, {
					xtype : 'tbseparator'
				}, {
					ref : '../saveBtn',
					scope: this,  
					iconCls : 'icon-user-save',
					text : '提交',
					handler : function() {
						this.store.save();
					}
				}, {
					xtype : 'tbseparator'
				}],
			bbar : new Ext.PagingToolbar({
				pageSize : Bb.Library.Config.PageSize,
				store : Bb.Library.Store.libraryStore,
				autoShow : true,
				prependButtons : true,
				displayInfo : true,
				displayMsg : '当前显示 {0} - {1}条记录 /共 {2}条记录',
				emptyMsg : "无显示数据"
			}),
			viewConfig : {
				forceFit : true
			}
		}, config);
		Bb.Library.Grid.superclass.constructor.call(this, config);
	},
	/**
	 * 自动行号
	 */
	rm : new Ext.grid.RowNumberer({
		header : '序号',
		width : 40,
		renderer : function(value, metadata, record, rowIndex) {
			if (this.rowspan) {
				p.cellAttr = 'rowspan="' + this.rowspan + '"';
			}
			var start = record.store.lastOptions.params.start;
			return start + rowIndex + 1;
		}
	}),
	/**
	 * 行选择器
	 */
	sm : new Ext.grid.CheckboxSelectionModel({
		handleMouseDown : Ext.emptyFn,
		listeners : {
			'rowselect' : function(sm, rowIndex, record) {
				// console.log('rowselect',rowIndex)
			},
			'rowdeselect' : function(sm, rowIndex, record) {
				// console.log('rowdeselect',rowIndex)
			},
			'selectionchange' : function(sm) {
				// 判断删除按钮是否可以激活
				Ext.getCmp("removeBtn").setDisabled(sm.getCount() < 1);
				// console.log('selectionchange',sm.getSelections().length);
			}
		}
	}),
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
	/**
	 * 反选选择
	 */
	onReverseSelect : function() {
		for (var i = this.getView().getRows().length - 1; i >= 0; i--) {
			if (this.sm.isSelected(i)) {
				this.sm.deselectRow(i);
			} else {
				this.sm.selectRow(i, true);
			}
		}
	},
	/**
	 * 新增
	 */
	onAdd : function(btn, ev) {		
		var u = new this.store.recordType({
			name : '',
			init : '',
			open : false,
			required : false
		});
		this.editor.stopEditing();
		this.store.insert(0, u);
		this.getView().refresh();
		this.editor.startEditing(0, 0);
		this.getBottomToolbar().updateInfo();
	},
	/**
	 * 删除
	 */
	onDelete : function() {		
		Ext.MessageBox.confirm('提示', '确实要删除所选的记录吗?', this.showResult,this);
	},
	/**
	 * 批量删除
	 */
	showResult : function showResult(btn) {
		// 删除单条记录
		// var rec = this.getSelectionModel().getSelected();
		// if (!rec) {
		// return false;
		// }
		// this.store.remove(rec);
		// 确定要删除你选定项的信息
		if (btn == 'yes') {
			this.editor.stopEditing();
			var selectedRows = this.getSelectionModel().getSelections();
			for (var i = 0, r; r = selectedRows[i]; i++) {
				if (!selectedRows[i]) {
					continue;
				}
				this.store.remove(r);
			}
		}
		this.store.save();
		this.getBottomToolbar().updateInfo();
		// this.getView().refresh();//刷新整个grid视图,重新排序.
	},
	/**
	 * pluggable renders 
	renderOperation : function(value, p, record) {
		return String.format('<a href="index.php?go=admin.system.library.view" target="_blank">浏览</a>|'
					+ '<a href="index.php?go=admin.system.library.edit" target="_blank">编辑</a>|'
					+ '<a href="index.php?go=admin.system.library.delete" target="_blank">删除</a>',
                    record.data.id);
	}*/ 
});

/**
 * 查询Form表单:根据条件查询资源库
 */
Bb.Library.Form = new Ext.form.FormPanel({
	region : 'north',
	labelAlign : 'right',
	labelWidth : 75,
	title : '高级查询',
	frame : true,// True表示为面板的边框外框可自定义的，false表示为边框可1px的点线,这个属性很重要，不显式地设置为true时，FormPanel中的指定的items和buttons的背景色会不一致
	collapsible : true,
	collapseMode : 'mini',
	// collapsed:true,
	bodyStyle : 'padding:5px 5px 0',
	height : 70,
	items : [{
		layout : 'column',
		items : [{
			columnWidth : .25,
			layout : 'form',
			items : [{
				fieldLabel : '库名称',
				anchor : '90%',
				xtype : 'textfield',
				name : 'name',
				id : 'name'
			}]
		}, {
			columnWidth : .25,
			layout : 'form',
			items : [{
				fieldLabel : '初始化方法',
				anchor : '90%',
				xtype : 'textfield',
				name : 'init'
			}]
		}, {
			columnWidth : .25,
			layout : 'form',
			items : [{
				fieldLabel : '是否已加载',
				anchor : '90%',
				xtype : 'combo',
				name : 'open',
				emptyText : '',
				mode : 'local',// 数据模式，local代表本地数据
				store : new Ext.data.SimpleStore({
					fields : ['value', 'text'],
					data : [['true', '是'],
							['false', '否']]
				}),// 库加载状态
				hiddenName : 'open',
				// readOnly : true,//是否只读
				// allowBlank : false,//不允许为空
				triggerAction : 'all',// 显示所有下列数据，一定要设置属性triggerAction为all
				valueField : 'value',// 值
				displayField : 'text',// 显示文本
				editable : false// 是否允许输入
			}]
		}, {
			columnWidth : .08,
			layout : 'form',
			items : [{
				xtype : 'button',
				id : 'btn',
				text : '查询',
				width : 80,
				handler : function() {
					Bb.Library.Form.onSubmitQuery();
				}
			}]
		}, {
			columnWidth : .08,
			layout : 'form',
			items : [{
				xtype : 'button',
				id : 'reset',
				text : '重置',
				width : 80,
				handler : function() {
					Bb.Library.Form.getForm().reset();
				}
			}]
		}, {
			columnWidth : .09
		}]
	}],
	/**
	 * 处理键盘回车事件
	 */
	keys : [{
		key : Ext.EventObject.ENTER,
		fn : function() {
			Bb.Library.Form.onSubmitQuery();
		},
		scope : this
	}],
	/**
	 * 根据条件查询资料库
	 */
	onSubmitQuery : function() {
		Bb.Library.Config.params = Bb.Library.Form.getForm().getValues();
		Bb.Library.Config.params.start = 0;
		Bb.Library.Config.params.limit = Bb.Library.Config.PageSize;
		Bb.Library.Store.libraryStore.load({
		    params : Bb.Library.Config.params
		})
	}
});

/**
 * 主程序
 */
Ext.onReady(function() {
    Ext.QuickTips.init();
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

    Bb.Library.Viewport = new Ext.Viewport({
		layout : 'border',
		items : [Bb.Library.Form,
		    new Bb.Library.Grid()
		]
	});

    Bb.Library.Viewport.doLayout();

    setTimeout(function() {
		Ext.get('loading').remove();
		Ext.get('loading-mask').fadeOut({
			remove : true
		});
	}, 250);
});
