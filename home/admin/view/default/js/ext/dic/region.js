Ext.namespace("Betterlife.Admin.Region");
Bb = Betterlife.Admin;
Bb.Region={
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
			 * 显示地区的视图相对地区列表Grid的位置
			 * 1:上方,2:下方,3:左侧,4:右侧,
			 */
			Direction:2,
			/**
			 *是否显示。
			 */
			IsShow:0,
			/**
			 * 是否固定显示地区信息页(或者打开新窗口)
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
		if (Bb.Region.Cookie.get('View.Direction')){
			Bb.Region.Config.View.Direction=Bb.Region.Cookie.get('View.Direction');
		}
		if (Bb.Region.Cookie.get('View.IsFix')!=null){
			Bb.Region.Config.View.IsFix=Bb.Region.Cookie.get('View.IsFix');
		}
	}
};
/**
 * Model:数据模型
 */
Bb.Region.Store = {
	/**
	 * 地区
	 */
	regionStore:new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			totalProperty: 'totalCount',
			successProperty: 'success',
			root: 'data',remoteSort: true,
			fields : [
                {name: 'region_id',type: 'string'},
                {name: 'parent_id',type: 'string'},
                {name: 'region_name_parent',type: 'string'},
                {name: 'regionShowAll',type: 'string'},
                {name: 'region_name',type: 'string'},
                {name: 'region_typeShow',type: 'string'},
                {name: 'region_type',type: 'string'},
                {name: 'level',type: 'string'}
			]
		}),
		writer: new Ext.data.JsonWriter({
			encode: false
		}),
		listeners : {
			beforeload : function(store, options) {
				if (Ext.isReady) {
					if (!options.params.limit)options.params.limit=Bb.Region.Config.PageSize;
					Ext.apply(options.params, Bb.Region.View.Running.regionGrid.filter);//保证分页也将查询条件带上
				}
			}
		}
	})
};
/**
 * View:地区显示组件
 */
Bb.Region.View={
	/**
	 * 编辑窗口：新建或者修改地区
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
				defaults : {
					autoScroll : true
				},
				listeners:{
					beforehide:function(){
						this.editForm.getForm().reset();
					}
				},
				items : [
					new Ext.form.FormPanel({
						ref:'editForm',layout:'form',
						labelWidth : 100,labelAlign : "center",
						bodyStyle : 'padding:5px 5px 0',align : "center",
						api : {},
						defaults : {
							xtype : 'textfield',anchor:'98%'
						},
						items : [
                            {xtype: 'hidden',name : 'region_id',ref:'../region_id'},
                            {xtype: 'hidden',name : 'parent_id',ref:'../parent_id'},
                            {
                                  xtype: 'compositefield',ref: '../regioncomp',
                                  items: [
                                      {
                                          xtype:'combotree', fieldLabel:'父地区',ref:'region_name',name: 'region_name',grid:this,
                                          emptyText: '请选择父地区',canFolderSelect:false,flex:1,editable:false,
                                          tree: new Ext.tree.TreePanel({
                                              dataUrl: 'home/admin/src/httpdata/regionTree.php',
                                              root: {nodeType: 'async'},border: false,rootVisible: false,
                                              listeners: {
                                                  beforeload: function(n) {if (n) {this.getLoader().baseParams.id = n.attributes.id;}}
                                              }
                                          }),
                                          onSelect: function(cmb, node) {
                                              this.grid.parent_id.setValue(node.attributes.id);
                                              this.setValue(node.attributes.text);
                                          }
                                      },
                                      {xtype:'button',text : '修改父地区',ref: 'btnModify',iconCls : 'icon-edit',
                                       handler:function(){
                                           this.setVisible(false);
                                           this.ownerCt.ownerCt.region_name.setVisible(true);
                                           this.ownerCt.ownerCt.regionShowLabel.setVisible(true);
                                           this.ownerCt.ownerCt.regionShowValue.setVisible(true);
                                           this.ownerCt.ownerCt.doLayout();
                                      }},
                                      {xtype:'displayfield',value:'所选父地区:',ref: 'regionShowLabel'},{xtype:'displayfield',name:'regionShowAll',flex:1,ref: 'regionShowValue'}]
                            },
                            {fieldLabel : '地区名称(<font color=red>*</font>)',name : 'region_name',allowBlank : false},
                            {fieldLabel : '地区类型(<font color=red>*</font>)',hiddenName : 'region_type',allowBlank : false,xtype:'combo',ref:'../region_type',
                                mode : 'local',triggerAction : 'all',lazyRender : true,editable: false,allowBlank : false,
                                store : new Ext.data.SimpleStore({
                                    fields : ['value', 'text'],
                                    data : [['0', '国家'],['1', '省'],['2', '市'],['3', '区']]
                                }),emptyText: '请选择地区类型',
                                valueField : 'value',displayField : 'text'
                            },
                            {fieldLabel : '目录层级',name : 'level'}
						]
					})
				],
				buttons : [{
					text: "",ref : "../saveBtn",scope:this,
					handler : function() {

						if (!this.editForm.getForm().isValid()) {
							return;
						}
						editWindow=this;
						if (this.savetype==0){
							this.editForm.api.submit=ExtServiceRegion.save;
							this.editForm.getForm().submit({
								success : function(form, action) {
									Ext.Msg.alert("提示", "保存成功！");
									Bb.Region.View.Running.regionGrid.doSelectRegion();
									form.reset();
									editWindow.hide();
								},
								failure : function(form, response) {
									Ext.Msg.show({title:'提示',width:350,buttons: {yes: '确定'},msg:response.result.msg});
								}
							});
						}else{
							this.editForm.api.submit=ExtServiceRegion.update;
							this.editForm.getForm().submit({
								success : function(form, action) {
									Bb.Region.View.Running.regionGrid.store.reload();
									Ext.Msg.show({title:'提示',msg: '修改成功！',buttons: {yes: '确定'},fn: function(){
										Bb.Region.View.Running.regionGrid.bottomToolbar.doRefresh();
									}});
									form.reset();
									editWindow.hide();
								},
								failure : function(form, response) {
									Ext.Msg.show({title:'提示',width:350,buttons: {yes: '确定'},msg:response.result.msg});
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
						this.editForm.form.loadRecord(Bb.Region.View.Running.regionGrid.getSelectionModel().getSelected());

					}
				}]
			}, config);
			Bb.Region.View.EditWindow.superclass.constructor.call(this, config);
		}
	}),
	/**
	 * 显示地区详情
	 */
	RegionView:{
		/**
		 * Tab页：容器包含显示与地区所有相关的信息
		 */
		Tabs:Ext.extend(Ext.TabPanel,{
			constructor : function(config) {
				config = Ext.apply({
					region : 'south',collapseMode : 'mini',split : true,
					activeTab: 1, tabPosition:"bottom",resizeTabs : true,
					header:false,enableTabScroll : true,tabWidth:'auto', margins : '0 3 3 0',
					defaults : {
						autoScroll : true,
						layout:'fit'
					},
					listeners:{
						beforetabchange:function(tabs,newtab,currentTab){
							if (tabs.tabFix==newtab){
								if (Bb.Region.View.Running.regionGrid.getSelectionModel().getSelected()==null){
									Ext.Msg.alert('提示', '请先选择地区！');
									return false;
								}
								Bb.Region.Config.View.IsShow=1;
								Bb.Region.View.Running.regionGrid.showRegion();
								Bb.Region.View.Running.regionGrid.tvpView.menu.mBind.setChecked(false);
								return false;
							}
						}
					},
					items: [
						{title:'+',tabTip:'取消固定',ref:'tabFix',iconCls:'icon-fix'}
					]
				}, config);
				Bb.Region.View.RegionView.Tabs.superclass.constructor.call(this, config);

				this.onAddItems();
			},
			/**
			 * 根据布局调整Tabs的宽度或者高度以及折叠
			 */
			enableCollapse:function(){
				if ((Bb.Region.Config.View.Direction==1)||(Bb.Region.Config.View.Direction==2)){
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
					{title: '基本信息',ref:'tabRegionDetail',iconCls:'tabs',
					 tpl: [
						 '<table class="viewdoblock">',
                         '    <tr class="entry"><td class="head">父地区</td><td class="content">{region_name_parent}<tpl if="region_name_parent">({regionShowAll})</tpl></td></tr>',
                         '    <tr class="entry"><td class="head">地区名称</td><td class="content">{region_name}</td></tr>',
                         '    <tr class="entry"><td class="head">地区类型</td><td class="content">{region_typeShow}</td></tr>',
                         '    <tr class="entry"><td class="head">目录层级</td><td class="content">{level}</td></tr>',
						 '</table>'
					 ]}
				);
                this.add(
                    {title: '其他',iconCls:'tabs'}
                );
			}
		}),
		/**
		 * 窗口:显示地区信息
		 */
		Window:Ext.extend(Ext.Window,{
			constructor : function(config) {
				config = Ext.apply({
					title:"查看地区",constrainHeader:true,maximizable: true,minimizable : true,
					width : 705,height : 500,minWidth : 450,minHeight : 400,
					layout : 'fit',resizable:true,plain : true,bodyStyle : 'padding:5px;',
					closeAction : "hide",
					items:[new Bb.Region.View.RegionView.Tabs({ref:'winTabs',tabPosition:'top'})],
					listeners: {
						minimize:function(w){
							w.hide();
							Bb.Region.Config.View.IsShow=0;
							Bb.Region.View.Running.regionGrid.tvpView.menu.mBind.setChecked(true);
						},
						hide:function(w){
							Bb.Region.Config.View.IsShow=0;
							Bb.Region.View.Running.regionGrid.tvpView.toggle(false);
						}
					},
					buttons: [{
						text: '新增地区',scope:this,
						handler : function() {this.hide();Bb.Region.View.Running.regionGrid.addRegion();}
					},{
						text: '修改地区',scope:this,
						handler : function() {this.hide();Bb.Region.View.Running.regionGrid.updateRegion();}
					}]
				}, config);
				Bb.Region.View.RegionView.Window.superclass.constructor.call(this, config);
			}
		})
	},
	/**
	 * 窗口：批量上传地区
	 */
	UploadWindow:Ext.extend(Ext.Window,{
		constructor : function(config) {
			config = Ext.apply({
				title : '批量上传地区数据',width : 400,height : 110,minWidth : 300,minHeight : 100,
				layout : 'fit',plain : true,bodyStyle : 'padding:5px;',buttonAlign : 'center',closeAction : "hide",
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
							emptyText: '请上传地区Excel文件',buttonText: '',
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
							validationExpression   =/([\u4E00-\u9FA5]|\w)+(.xlsx|.XLSX|.xls|.XLS)$/;/**允许中文名*/
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
									url : 'index.php?go=admin.upload.uploadRegion',
									success : function(form, response) {
										Ext.Msg.alert('成功', '上传成功');
										uploadWindow.hide();
										uploadWindow.uploadForm.upload_file.setValue('');
										Bb.Region.View.Running.regionGrid.doSelectRegion();
									},
									failure : function(form, response) {
										Ext.Msg.alert('错误', response.result.data);
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
			Bb.Region.View.UploadWindow.superclass.constructor.call(this, config);
		}
	}),

	/**
	 * 视图：地区列表
	 */
	Grid:Ext.extend(Ext.grid.GridPanel, {
		constructor : function(config) {
			config = Ext.apply({
				/**
				 * 查询条件
				 */
				filter:null,
				region : 'center',
				store : Bb.Region.Store.regionStore,
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
						new Ext.grid.RowNumberer({width:40,header:'行号'}),
                        {header : '标识',dataIndex : 'region_id',hidden:true},
                        {header : '父地区',dataIndex : 'region_name_parent'},
                        {header : '地区名称',dataIndex : 'region_name'},
                        {header : '地区类型',dataIndex : 'region_typeShow'},
                        {header : '目录层级',dataIndex : 'level'}
					]
				}),
				tbar : {
					xtype : 'container',layout : 'anchor',autoScroll : true,height : 27 * 2,style:'font-size:14px',
					defaults : {
						height : 27,anchor : '100%',autoScroll : true,autoHeight : true
					},
					items : [
						new Ext.Toolbar({
							enableOverflow: true,width : 100,
							defaults : {
								xtype : 'textfield',
								listeners : {
								   specialkey : function(field, e) {
										if (e.getKey() == Ext.EventObject.ENTER)this.ownerCt.ownerCt.ownerCt.doSelectRegion();
									}
								}
							},
							items : [
                                '地区类型','&nbsp;&nbsp;',{ref: '../rregion_type',xtype:'combo',mode : 'local',
                                    triggerAction : 'all',lazyRender : true,editable: false,
                                    store : new Ext.data.SimpleStore({
                                        fields : ['value', 'text'],
                                        data : [['0', '国家'],['1', '省'],['2', '市'],['3', '区']]
                                    }),
                                    valueField : 'value',displayField : 'text'
                                },'&nbsp;&nbsp;',
								{
									xtype : 'button',text : '查询',scope: this,
									handler : function() {
										this.doSelectRegion();
									}
								},
								{
									xtype : 'button',text : '重置',scope: this,
									handler : function() {
                                        this.topToolbar.rregion_type.setValue("");
										this.filter={};
										this.doSelectRegion();
									}
								}]
						}),
						new Ext.Toolbar({
							defaults:{scope: this},
							items : [
								{
									text: '反选',iconCls : 'icon-reverse',
									handler: function(){
										this.onReverseSelect();
									}
								},'-',{
									text : '添加地区',iconCls : 'icon-add',
									handler : function() {
										this.addRegion();
									}
								},'-',{
									text : '修改地区',ref: '../../btnUpdate',iconCls : 'icon-edit',disabled : true,
									handler : function() {
										this.updateRegion();
									}
								},'-',{
									text : '删除地区', ref: '../../btnRemove',iconCls : 'icon-delete',disabled : true,
									handler : function() {
										this.deleteRegion();
									}
								},'-',{
									xtype:'tbsplit',text: '导入', iconCls : 'icon-import',
									handler : function() {
										this.importRegion();
									},
									menu: {
										xtype:'menu',plain:true,
										items: [
											{text:'批量导入地区',iconCls : 'icon-import',scope:this,handler:function(){this.importRegion()}}
										]}
								},'-',{
									text : '导出',iconCls : 'icon-export',
									handler : function() {
										this.exportRegion();
									}
								},'-',{
									xtype:'tbsplit',text: '查看地区', ref:'../../tvpView',iconCls : 'icon-updown',
									enableToggle: true, disabled : true,
									handler:function(){this.showRegion()},
									menu: {
										xtype:'menu',plain:true,
										items: [
											{text:'上方',group:'mlayout',checked:false,iconCls:'view-top',scope:this,handler:function(){this.onUpDown(1)}},
											{text:'下方',group:'mlayout',checked:true ,iconCls:'view-bottom',scope:this,handler:function(){this.onUpDown(2)}},
											{text:'左侧',group:'mlayout',checked:false,iconCls:'view-left',scope:this,handler:function(){this.onUpDown(3)}},
											{text:'右侧',group:'mlayout',checked:false,iconCls:'view-right',scope:this,handler:function(){this.onUpDown(4)}},
											{text:'隐藏',group:'mlayout',checked:false,iconCls:'view-hide',scope:this,handler:function(){this.hideRegion();Bb.Region.Config.View.IsShow=0;}},'-',
											{text: '固定',ref:'mBind',checked: true,scope:this,checkHandler:function(item, checked){this.onBindGrid(item, checked);Bb.Region.Cookie.set('View.IsFix',Bb.Region.Config.View.IsFix);}}
										]}
								},'-']}
					)]
				},
				bbar: new Ext.PagingToolbar({
					pageSize: Bb.Region.Config.PageSize,
					store: Bb.Region.Store.regionStore,
					scope:this,autoShow:true,displayInfo: true,
					displayMsg: '当前显示 {0} - {1}条记录/共 {2}条记录。',
					emptyMsg: "无显示数据",
					listeners:{
						change:function(thisbar,pagedata){
							if (Bb.Region.Viewport){
								if (Bb.Region.Config.View.IsShow==1){
									Bb.Region.View.IsSelectView=1;
								}
								this.ownerCt.hideRegion();
								Bb.Region.Config.View.IsShow=0;
							}
						}
					},
					items: [
						{xtype:'label', text: '每页显示'},
						{xtype:'numberfield', value:Bb.Region.Config.PageSize,minValue:1,width:35,
							style:'text-align:center',allowBlank: false,
							listeners:
							{
								change:function(Field, newValue, oldValue){
									var num = parseInt(newValue);
									if (isNaN(num) || !num || num<1)
									{
										num = Bb.Region.Config.PageSize;
										Field.setValue(num);
									}
									this.ownerCt.pageSize= num;
									Bb.Region.Config.PageSize = num;
									this.ownerCt.ownerCt.doSelectRegion();
								},
								specialKey :function(field,e){
									if (e.getKey() == Ext.EventObject.ENTER){
										var num = parseInt(field.getValue());
										if (isNaN(num) || !num || num<1)
										{
											num = Bb.Region.Config.PageSize;
										}
										this.ownerCt.pageSize= num;
										Bb.Region.Config.PageSize = num;
										this.ownerCt.ownerCt.doSelectRegion();
									}
								}
							}
						},
						{xtype:'label', text: '个'}
					]
				})
			}, config);
			//初始化显示地区列表
			this.doSelectRegion();
			Bb.Region.View.Grid.superclass.constructor.call(this, config);
			//创建在Grid里显示的地区信息Tab页
			Bb.Region.View.Running.viewTabs=new Bb.Region.View.RegionView.Tabs();
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
					if (sm.getCount() != 1){
						this.grid.hideRegion();
						Bb.Region.Config.View.IsShow=0;
					}else{
						if (Bb.Region.View.IsSelectView==1){
							Bb.Region.View.IsSelectView=0;
							this.grid.showRegion();
						}
					}
				},
				rowdeselect: function(sm, rowIndex, record) {
					if (sm.getCount() != 1){
						if (Bb.Region.Config.View.IsShow==1){
							Bb.Region.View.IsSelectView=1;
						}
						this.grid.hideRegion();
						Bb.Region.Config.View.IsShow=0;
					}
				}
			}
		}),
		/**
		 * 双击选行
		 */
		onRowDoubleClick:function(grid, rowIndex, e){
			if (!Bb.Region.Config.View.IsShow){
				this.sm.selectRow(rowIndex);
				this.showRegion();
				this.tvpView.toggle(true);
			}else{
				this.hideRegion();
				Bb.Region.Config.View.IsShow=0;
				this.sm.deselectRow(rowIndex);
				this.tvpView.toggle(false);
			}
		},
		/**
		 * 是否绑定在本窗口上
		 */
		onBindGrid:function(item, checked){
			if (checked){
			   Bb.Region.Config.View.IsFix=1;
			}else{
			   Bb.Region.Config.View.IsFix=0;
			}
			if (this.getSelectionModel().getSelected()==null){
				Bb.Region.Config.View.IsShow=0;
				return ;
			}
			if (Bb.Region.Config.View.IsShow==1){
			   this.hideRegion();
			   Bb.Region.Config.View.IsShow=0;
			}
			this.showRegion();
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
		 * 查询符合条件的地区
		 */
		doSelectRegion : function() {
			if (this.topToolbar){
                var rregion_type = this.topToolbar.rregion_type.getValue();
                this.filter       ={'region_type':rregion_type};
			}
			var condition = {'start':0,'limit':Bb.Region.Config.PageSize};
			Ext.apply(condition,this.filter);
			ExtServiceRegion.queryPageRegion(condition,function(provider, response) {
				if (response.result&&response.result.data) {
					var result           = new Array();
					result['data']       =response.result.data;
					result['totalCount'] =response.result.totalCount;
					Bb.Region.Store.regionStore.loadData(result);
				} else {
					Bb.Region.Store.regionStore.removeAll();
					Ext.Msg.alert('提示', '无符合条件的地区！');
				}
			});
		},
		/**
		 * 显示地区视图
		 * 显示地区的视图相对地区列表Grid的位置
		 * 1:上方,2:下方,0:隐藏。
		 */
		onUpDown:function(viewDirection){
			Bb.Region.Config.View.Direction=viewDirection;
			switch(viewDirection){
				case 1:
					this.ownerCt.north.add(Bb.Region.View.Running.viewTabs);
					break;
				case 2:
					this.ownerCt.south.add(Bb.Region.View.Running.viewTabs);
					break;
				case 3:
					this.ownerCt.west.add(Bb.Region.View.Running.viewTabs);
					break;
				case 4:
					this.ownerCt.east.add(Bb.Region.View.Running.viewTabs);
					break;
			}
			Bb.Region.Cookie.set('View.Direction',Bb.Region.Config.View.Direction);
			if (this.getSelectionModel().getSelected()!=null){
				if ((Bb.Region.Config.View.IsFix==0)&&(Bb.Region.Config.View.IsShow==1)){
					this.showRegion();
				}
				Bb.Region.Config.View.IsFix=1;
				Bb.Region.View.Running.regionGrid.tvpView.menu.mBind.setChecked(true,true);
				Bb.Region.Config.View.IsShow=0;
				this.showRegion();
			}
		},
		/**
		 * 显示地区
		 */
		showRegion : function(){
			if (this.getSelectionModel().getSelected()==null){
				Ext.Msg.alert('提示', '请先选择地区！');
				Bb.Region.Config.View.IsShow=0;
				this.tvpView.toggle(false);
				return ;
			}
			if (Bb.Region.Config.View.IsFix==0){
				if (Bb.Region.View.Running.view_window==null){
					Bb.Region.View.Running.view_window=new Bb.Region.View.RegionView.Window();
				}
				if (Bb.Region.View.Running.view_window.hidden){
					Bb.Region.View.Running.view_window.show();
					Bb.Region.View.Running.view_window.winTabs.hideTabStripItem(Bb.Region.View.Running.view_window.winTabs.tabFix);
					this.updateViewRegion();
					this.tvpView.toggle(true);
					Bb.Region.Config.View.IsShow=1;
				}else{
					this.hideRegion();
					Bb.Region.Config.View.IsShow=0;
				}
				return;
			}
			switch(Bb.Region.Config.View.Direction){
				case 1:
					if (!this.ownerCt.north.items.contains(Bb.Region.View.Running.viewTabs)){
						this.ownerCt.north.add(Bb.Region.View.Running.viewTabs);
					}
					break;
				case 2:
					if (!this.ownerCt.south.items.contains(Bb.Region.View.Running.viewTabs)){
						this.ownerCt.south.add(Bb.Region.View.Running.viewTabs);
					}
					break;
				case 3:
					if (!this.ownerCt.west.items.contains(Bb.Region.View.Running.viewTabs)){
						this.ownerCt.west.add(Bb.Region.View.Running.viewTabs);
					}
					break;
				case 4:
					if (!this.ownerCt.east.items.contains(Bb.Region.View.Running.viewTabs)){
						this.ownerCt.east.add(Bb.Region.View.Running.viewTabs);
					}
					break;
			}
			this.hideRegion();
			if (Bb.Region.Config.View.IsShow==0){
				Bb.Region.View.Running.viewTabs.enableCollapse();
				switch(Bb.Region.Config.View.Direction){
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
				this.updateViewRegion();
				this.tvpView.toggle(true);
				Bb.Region.Config.View.IsShow=1;
			}else{
				Bb.Region.Config.View.IsShow=0;
			}
			this.ownerCt.doLayout();
		},
		/**
		 * 隐藏地区
		 */
		hideRegion : function(){
			this.ownerCt.north.hide();
			this.ownerCt.south.hide();
			this.ownerCt.west.hide();
			this.ownerCt.east.hide();
			if (Bb.Region.View.Running.view_window!=null){
				Bb.Region.View.Running.view_window.hide();
			}
			this.tvpView.toggle(false);
			this.ownerCt.doLayout();
		},
		/**
		 * 更新当前地区显示信息
		 */
		updateViewRegion : function() {

			if (Bb.Region.View.Running.view_window!=null){
				Bb.Region.View.Running.view_window.winTabs.tabRegionDetail.update(this.getSelectionModel().getSelected().data);
			}
			Bb.Region.View.Running.viewTabs.tabRegionDetail.update(this.getSelectionModel().getSelected().data);
		},
		/**
		 * 新建地区
		 */
		addRegion : function() {
			if (Bb.Region.View.Running.edit_window==null){
				Bb.Region.View.Running.edit_window=new Bb.Region.View.EditWindow();
			}
			Bb.Region.View.Running.edit_window.resetBtn.setVisible(false);
			Bb.Region.View.Running.edit_window.saveBtn.setText('保 存');
			Bb.Region.View.Running.edit_window.setTitle('添加地区');
			Bb.Region.View.Running.edit_window.savetype=0;
			Bb.Region.View.Running.edit_window.region_id.setValue("");

            Bb.Region.View.Running.edit_window.regioncomp.btnModify.setVisible(false);
            Bb.Region.View.Running.edit_window.regioncomp.region_name.setVisible(true);
            Bb.Region.View.Running.edit_window.regioncomp.regionShowLabel.setVisible(false);
            Bb.Region.View.Running.edit_window.regioncomp.regionShowValue.setVisible(false);

			Bb.Region.View.Running.edit_window.show();
			Bb.Region.View.Running.edit_window.maximize();
		},
		/**
		 * 编辑地区时先获得选中的地区信息
		 */
		updateRegion : function() {
			if (Bb.Region.View.Running.edit_window==null){
				Bb.Region.View.Running.edit_window=new Bb.Region.View.EditWindow();
			}
			Bb.Region.View.Running.edit_window.saveBtn.setText('修 改');
			Bb.Region.View.Running.edit_window.resetBtn.setVisible(true);
			Bb.Region.View.Running.edit_window.setTitle('修改地区');
			Bb.Region.View.Running.edit_window.savetype=1;

            if (this.getSelectionModel().getSelected().data.regionShowAll){
                Bb.Region.View.Running.edit_window.regioncomp.btnModify.setVisible(true);
                Bb.Region.View.Running.edit_window.regioncomp.region_name.setVisible(false);
                Bb.Region.View.Running.edit_window.regioncomp.regionShowLabel.setVisible(true);
                Bb.Region.View.Running.edit_window.regioncomp.regionShowValue.setVisible(true);
            }else{
                Bb.Region.View.Running.edit_window.regioncomp.btnModify.setVisible(false);
                Bb.Region.View.Running.edit_window.regioncomp.region_name.setVisible(true);
                Bb.Region.View.Running.edit_window.regioncomp.regionShowLabel.setVisible(false);
                Bb.Region.View.Running.edit_window.regioncomp.regionShowValue.setVisible(false);
            }

			Bb.Region.View.Running.edit_window.show();
			Bb.Region.View.Running.edit_window.maximize();

			Bb.Region.View.Running.edit_window.editForm.form.loadRecord(this.getSelectionModel().getSelected());

		},
		/**
		 * 删除地区
		 */
		deleteRegion : function() {
			Ext.Msg.confirm('提示', '确认要删除所选的地区吗?', this.confirmDeleteRegion,this);
		},
		/**
		 * 确认删除地区
		 */
		confirmDeleteRegion : function(btn) {
			if (btn == 'yes') {
				var del_region_ids ="";
				var selectedRows    = this.getSelectionModel().getSelections();
				for ( var flag = 0; flag < selectedRows.length; flag++) {
					del_region_ids=del_region_ids+selectedRows[flag].data.region_id+",";
				}
				ExtServiceRegion.deleteByIds(del_region_ids);
				this.doSelectRegion();
				Ext.Msg.alert("提示", "删除成功！");
			}
		},
		/**
		 * 导出地区
		 */
		exportRegion : function() {
			ExtServiceRegion.exportRegion(this.filter,function(provider, response) {
				if (response.result.data) {
					window.open(response.result.data);
				}
			});
		},
		/**
		 * 导入地区
		 */
		importRegion : function() {
			if (Bb.Region.View.current_uploadWindow==null){
				Bb.Region.View.current_uploadWindow=new Bb.Region.View.UploadWindow();
			}
			Bb.Region.View.current_uploadWindow.show();
		}
	}),
	/**
	 * 核心内容区
	 */
	Panel:Ext.extend(Ext.form.FormPanel,{
		constructor : function(config) {
			Bb.Region.View.Running.regionGrid=new Bb.Region.View.Grid();
			if (Bb.Region.Config.View.IsFix==0){
				Bb.Region.View.Running.regionGrid.tvpView.menu.mBind.setChecked(false,true);
			}
			config = Ext.apply({
				region : 'center',layout : 'fit', frame:true,
				items: {
					layout:'border',
					items:[
						Bb.Region.View.Running.regionGrid,
						{region:'north',ref:'north',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true},
						{region:'south',ref:'south',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true,items:[Bb.Region.View.Running.viewTabs]},
						{region:'west',ref:'west',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true},
						{region:'east',ref:'east',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true}
					]
				}
			}, config);
			Bb.Region.View.Panel.superclass.constructor.call(this, config);
		}
	}),
	/**
	 * 当前运行的可视化对象
	 */
	Running:{
		/**
		 * 当前地区Grid对象
		 */
		regionGrid:null,

		/**
		 * 显示地区信息及关联信息列表的Tab页
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
	Ext.state.Manager.setProvider(Bb.Region.Cookie);
	Ext.Direct.addProvider(Ext.app.REMOTING_API);
	Bb.Region.Init();
	/**
	 * 地区数据模型获取数据Direct调用
	 */
	Bb.Region.Store.regionStore.proxy=new Ext.data.DirectProxy({
		api: {read:ExtServiceRegion.queryPageRegion}
	});
	/**
	 * 地区页面布局
	 */
	Bb.Region.Viewport = new Ext.Viewport({
		layout : 'border',
		items : [new Bb.Region.View.Panel()]
	});
	Bb.Region.Viewport.doLayout();
	setTimeout(function(){
		Ext.get('loading').remove();
		Ext.get('loading-mask').fadeOut({
			remove:true
		});
	}, 250);
});