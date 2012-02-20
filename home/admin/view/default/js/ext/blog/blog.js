Ext.namespace("Betterlife.Admin.Blog");
Betterlife = Betterlife.Admin.Blog;
Betterlife.Blog={
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
			 * 显示博客的视图相对博客列表Grid的位置
			 * 1:上方,2:下方,3:左侧,4:右侧,
			 */
			Direction:2,
			/**
			 *是否显示。
			 */
			IsShow:0,
			/**
			 * 是否固定显示博客信息页(或者打开新窗口)
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
		if (Betterlife.Blog.Cookie.get('View.Direction')){
			Betterlife.Blog.Config.View.Direction=Betterlife.Blog.Cookie.get('View.Direction');
		}
		if (Betterlife.Blog.Cookie.get('View.IsFix')!=null){
			Betterlife.Blog.Config.View.IsFix=Betterlife.Blog.Cookie.get('View.IsFix');
		}
	}
}; 
/**
 * Model:数据模型   
 */
Betterlife.Blog.Store = { 
	/**
	 * 博客
	 */ 
	blogStore:new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			totalProperty: 'totalCount',
			successProperty: 'success',  
			root: 'data',remoteSort: true,                
			fields : [
				{name: 'id',type: 'int'},  
				{name: 'userId',type: 'int'},
				{name: 'username',type: 'string'},
				{name: 'name',type: 'string'},
				{name: 'content',type: 'string'}
			]}         
		),
		writer: new Ext.data.JsonWriter({
			encode: false 
		}),
		listeners : {    
			beforeload : function(store, options) {   
				if (Ext.isReady) {  
					Ext.apply(options.params, Betterlife.Blog.View.Running.blogGrid.filter);//保证分页也将查询条件带上  
				}
			}
		}    
	}),
	/**
	 * 用户
	 */
	userStore : new Ext.data.Store({
		proxy: new Ext.data.HttpProxy({
			url: 'home/admin/src/httpdata/user.php'
		}),
		reader: new Ext.data.JsonReader({
			root: 'users',
			autoLoad: true,
			totalProperty: 'totalCount',
			id: 'id'
		}, [
			{name: 'userId', mapping: 'id'}, 
			{name: 'username', mapping: 'name'} 
		])
	})      
}
/**
 * View:博客显示组件   
 */
Betterlife.Blog.View={ 
	/**
	 * 编辑窗口：新建或者修改博客
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
						this.editForm.form.getEl().dom.reset();                    
					},
					afterrender:function(){
						ckeditor_replace(); 
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
							{xtype: 'hidden',name : 'id',ref:"../id"}, 
							{xtype: 'hidden',name : 'userId',id:'userId'},
							{
								 fieldLabel : '用户名',xtype: 'combo',name : 'username',id : 'username',
								 store:Betterlife.Blog.Store.userStore,emptyText: '请选择用户',itemSelector: 'div.search-item',
								 loadingText: '查询中...',width: 570, pageSize:Betterlife.Blog.Config.PageSize,
								 displayField:'name',// 显示文本
								 mode: 'remote',  editable:true,minChars: 1,autoSelect :true,typeAhead: false,
								 forceSelection: true,triggerAction: 'all',resizable:false,selectOnFocus:true,
								 tpl:new Ext.XTemplate(
											'<tpl for="."><div class="search-item">',
												'<h3>{username}</h3>',
											'</div></tpl>'
								 ),
								 onSelect:function(record,index){
									 if(this.fireEvent('beforeselect', this, record, index) !== false){
										Ext.getCmp("userId").setValue(record.data.userId);
										Ext.getCmp("username").setValue(record.data.username);
										this.collapse();
									 }
								 }
							},
							{fieldLabel : '博客名称',name : 'name',id:'name'},    
							{fieldLabel : '博客内容',name : 'content',xtype : 'textarea',id:'content',ref:'content'}        
						]
					})                
				],
				buttons : [ {         
					text: "",ref : "../saveBtn",scope:this,
					handler : function() {
						if (CKEDITOR.instances.content){
							this.editForm.content.setValue(CKEDITOR.instances.content.getData());
						}                        
						if (!this.editForm.getForm().isValid()) {
							return;
						}
						editWindow=this;  
						if (this.savetype==0){    
							this.editForm.api.submit=ExtServiceBlog.save;                   
							this.editForm.getForm().submit({
								success : function(form, action) {
									Ext.Msg.alert("提示", "保存成功！");
									Betterlife.Blog.View.Running.blogGrid.doSelectBlog();
									form.reset(); 
									editWindow.hide();
								},
								failure : function(form, action) {
									Ext.Msg.alert('提示', '失败');
								}
							});
						}else{
							this.editForm.api.submit=ExtServiceBlog.update;
							this.editForm.getForm().submit({
								success : function(form, action) {
									Ext.Msg.alert("提示", "修改成功！");
									Betterlife.Blog.View.Running.blogGrid.doSelectBlog();
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
						this.editForm.form.loadRecord(Betterlife.Blog.View.Running.blogGrid.getSelectionModel().getSelected());
						if (CKEDITOR.instances.content){
							CKEDITOR.instances.content.setData(Betterlife.Blog.View.Running.blogGrid.getSelectionModel().getSelected().data.content);
						} 
					}                  
				}]    
			}, config);  
			Betterlife.Blog.View.EditWindow.superclass.constructor.call(this, config);     
		}
	}),
	/**
	 * 显示博客详情
	 */
	BlogView:{
		/**
		 * Tab页：容器包含显示与博客所有相关的信息
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
								if (Betterlife.Blog.View.Running.blogGrid.getSelectionModel().getSelected()==null){
									Ext.Msg.alert('提示', '请先选择博客！');
									return false;
								} 
								Betterlife.Blog.Config.View.IsShow=1;
								Betterlife.Blog.View.Running.blogGrid.showBlog();   
								Betterlife.Blog.View.Running.blogGrid.tvpView.menu.mBind.setChecked(false);
								return false;
							}
						}
					},
					items: [
						{title: '取消固定',ref:'tabFix',iconCls:'icon-fix'}
					]
				}, config);
				Betterlife.Blog.View.BlogView.Tabs.superclass.constructor.call(this, config); 
				this.onAddItems();
			},
			/**
			 * 根据布局调整Tabs的宽度或者高度以及折叠
			 */
			enableCollapse:function(){
				if ((Betterlife.Blog.Config.View.Direction==1)||(Betterlife.Blog.Config.View.Direction==2)){
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
					{title: '基本信息',ref:'tabBlogDetail',iconCls:'tabs',
					 tpl: [
					  '<table class="viewdoblock">', 
						 '<tr class="entry"><td class="head">会员名</td><td class="content">{username}</td></tr>',
						 '<tr class="entry"><td class="head">博客名称</td><td class="content">{name}</td></tr>',
						 '<tr class="entry"><td class="head">博客内容</td><td class="content">{content}</td></tr>',                      
					 '</table>' 
					 ]
					}
				);
				this.add(
					{title: '其他',iconCls:'tabs'}
				);
			}       
		}),
		/**
		 * 窗口:显示博客信息
		 */
		Window:Ext.extend(Ext.Window,{ 
			constructor : function(config) { 
				config = Ext.apply({
					title:"查看博客",constrainHeader:true,maximizable: true,minimizable : true, 
					width : 600,height : 500,minWidth : 450,minHeight : 400,
					layout : 'fit',resizable:true,plain : true,bodyStYle : 'padding:5px;',
					closeAction : "hide",
					items:[new Betterlife.Blog.View.BlogView.Tabs({ref:'winTabs',tabPosition:'top'})],
					listeners: { 
						minimize:function(w){
							w.hide();
							Betterlife.Blog.Config.View.IsShow=0;
							Betterlife.Blog.View.Running.blogGrid.tvpView.menu.mBind.setChecked(true);
						},
						hide:function(w){
							Betterlife.Blog.View.Running.blogGrid.tvpView.toggle(false);
						}   
					},
					buttons: [{
						text: '新增',scope:this,
						handler : function() {this.hide();Betterlife.Blog.View.Running.blogGrid.addBlog();}
					},{
						text: '修改',scope:this,
						handler : function() {this.hide();Betterlife.Blog.View.Running.blogGrid.updateBlog();}
					}]
				}, config);  
				Betterlife.Blog.View.BlogView.Window.superclass.constructor.call(this, config);   
			}        
		})
	},
	/**
	 * 窗口：批量上传博客
	 */        
	UploadWindow:Ext.extend(Ext.Window,{ 
		constructor : function(config) { 
			config = Ext.apply({     
				title : '批量博客上传',
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
							emptyText: '请上传博客Excel文件',buttonText: '',
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
									url : 'index.php?go=admin.upload.uploadBlog',
									success : function(form, action) {
										Ext.Msg.alert('成功', '上传成功');
										uploadWindow.hide();
										uploadWindow.uploadForm.upload_file.setValue('');
										Betterlife.Blog.View.Running.blogGrid.doSelectBlog();
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
			Betterlife.Blog.View.UploadWindow.superclass.constructor.call(this, config);     
		}        
	}),
	/**
	 * 视图：博客列表
	 */
	Grid:Ext.extend(Ext.grid.GridPanel, {
		constructor : function(config) {
			config = Ext.apply({
				/**
				 * 查询条件  
				 */
				filter:null,
				region : 'center',
				store : Betterlife.Blog.Store.blogStore,
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
						{header : '会员名',dataIndex : 'username'},
						{header : '博客名称',dataIndex : 'name'},
						{header : '博客内容',dataIndex : 'content'}                                 
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
								'博客名称　',{ref: '../bname'},'&nbsp;&nbsp;',
								'博客内容　',{ref: '../bcontent'},'&nbsp;&nbsp;',                                
								{
									xtype : 'button',text : '查询',scope: this, 
									handler : function() {
										this.doSelectBlog();
									}
								}, 
								{
									xtype : 'button',text : '重置',scope: this,
									handler : function() {
										this.topToolbar.bname.setValue("");
										this.topToolbar.bcontent.setValue("");                                        
										this.filter={};
										this.doSelectBlog();
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
									text : '添加博客',iconCls : 'icon-add',
									handler : function() {
										this.addBlog();
									}
								},'-',{
									text : '修改博客',ref: '../../btnUpdate',iconCls : 'icon-edit',disabled : true,  
									handler : function() {
										this.updateBlog();
									}
								},'-',{
									text : '删除博客', ref: '../../btnRemove',iconCls : 'icon-delete',disabled : true,                                    
									handler : function() {
										this.deleteBlog();
									}
								},'-',{
									text : '导入',iconCls : 'icon-import', 
									handler : function() {
										this.importBlog();
									}
								},'-',{
									text : '导出',iconCls : 'icon-export', 
									handler : function() { 
										this.exportBlog();
									}
								},'-',{ 
									xtype:'tbsplit',text: '查看博客', ref:'../../tvpView',iconCls : 'icon-updown',
									enableToggle: true, disabled : true,  
									handler:function(){this.showBlog()},
									menu: {
										xtype:'menu',plain:true,
										items: [
											{text:'上方',group:'mlayout',checked:false,iconCls:'view-top',scope:this,handler:function(){this.onUpDown(1)}},
											{text:'下方',group:'mlayout',checked:true ,iconCls:'view-bottom',scope:this,handler:function(){this.onUpDown(2)}}, 
											{text:'左侧',group:'mlayout',checked:false,iconCls:'view-left',scope:this,handler:function(){this.onUpDown(3)}},
											{text:'右侧',group:'mlayout',checked:false,iconCls:'view-right',scope:this,handler:function(){this.onUpDown(4)}}, 
											{text:'隐藏',group:'mlayout',checked:false,iconCls:'view-hide',scope:this,handler:function(){this.hideBlog();Betterlife.Blog.Config.View.IsShow=0;}},'-', 
											{text: '固定',ref:'mBind',checked: true,scope:this,checkHandler:function(item, checked){this.onBindGrid(item, checked);Betterlife.Blog.Cookie.set('View.IsFix',Betterlife.Blog.Config.View.IsFix);}} 
										]}
								},'-']}
					)]
				},                
				bbar: new Ext.PagingToolbar({          
					pageSize: Betterlife.Blog.Config.PageSize,
					store: Betterlife.Blog.Store.blogStore,
					scope:this,autoShow:true,displayInfo: true,
					displayMsg: '当前显示 {0} - {1}条记录/共 {2}条记录。',
					emptyMsg: "无显示数据",
					items: [
						{xtype:'label', text: '每页显示'},
						{xtype:'numberfield', value:Betterlife.Blog.Config.PageSize,minValue:1,width:35, 
							style:'text-align:center',allowBlank: false,
							listeners:
							{
								change:function(Field, newValue, oldValue){
									var num = parseInt(newValue);
									if (isNaN(num) || !num || num<1)
									{
										num = Betterlife.Blog.Config.PageSize;
										Field.setValue(num);
									}
									this.ownerCt.pageSize= num;
									Betterlife.Blog.Config.PageSize = num;
									this.ownerCt.ownerCt.doSelectBlog();
								}, 
								specialKey :function(field,e){
									if (e.getKey() == Ext.EventObject.ENTER){
										var num = parseInt(field.getValue());
										if (isNaN(num) || !num || num<1)
										{
											num = Betterlife.Blog.Config.PageSize;
										}
										this.ownerCt.pageSize= num;
										Betterlife.Blog.Config.PageSize = num;
										this.ownerCt.ownerCt.doSelectBlog();
									}
								}
							}
						},
						{xtype:'label', text: '个'}
					]
				})
			}, config);
			//初始化显示博客列表
			this.doSelectBlog();
			Betterlife.Blog.View.Grid.superclass.constructor.call(this, config); 
			//创建在Grid里显示的博客信息Tab页
			Betterlife.Blog.View.Running.viewTabs=new Betterlife.Blog.View.BlogView.Tabs();
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
					this.grid.updateViewBlog();                     
					if (sm.getCount() != 1){
						this.grid.hideBlog();
						Betterlife.Blog.Config.View.IsShow=0;
					}else{
						if (Betterlife.Blog.View.IsSelectView==1){
							Betterlife.Blog.View.IsSelectView=0;  
							this.grid.showBlog();   
						}     
					}    
				},
				rowdeselect: function(sm, rowIndex, record) {  
					if (sm.getCount() != 1){
						if (Betterlife.Blog.Config.View.IsShow==1){
							Betterlife.Blog.View.IsSelectView=1;    
						}             
						this.grid.hideBlog();
						Betterlife.Blog.Config.View.IsShow=0;
					}    
				}
			}
		}),
		/**
		 * 双击选行
		 */
		onRowDoubleClick:function(grid, rowIndex, e){  
			if (!Betterlife.Blog.Config.View.IsShow){
				this.sm.selectRow(rowIndex);
				this.showBlog();
				this.tvpView.toggle(true);
			}else{
				this.hideBlog();
				Betterlife.Blog.Config.View.IsShow=0;
				this.sm.deselectRow(rowIndex);
				this.tvpView.toggle(false);
			}
		},
		/**
		 * 是否绑定在本窗口上
		 */
		onBindGrid:function(item, checked){ 
			if (checked){             
			   Betterlife.Blog.Config.View.IsFix=1; 
			}else{ 
			   Betterlife.Blog.Config.View.IsFix=0;   
			}
			if (this.getSelectionModel().getSelected()==null){
				Betterlife.Blog.Config.View.IsShow=0;
				return ;
			}
			if (Betterlife.Blog.Config.View.IsShow==1){
			   this.hideBlog(); 
			   Betterlife.Blog.Config.View.IsShow=0;
			}
			this.showBlog();   
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
		 * 查询符合条件的博客
		 */
		doSelectBlog : function() {
			if (this.topToolbar){
				var bname = this.topToolbar.bname.getValue();
				var bcontent = this.topToolbar.bcontent.getValue();
				this.filter       ={'name':bname,'content':bcontent};
			}
			var condition = {'start':0,'limit':Betterlife.Blog.Config.PageSize};
			Ext.apply(condition,this.filter);
			ExtServiceBlog.queryPageBlog(condition,function(provider, response) {   
				if (response.result.data) {   
					var result           = new Array();
					result['data']       =response.result.data; 
					result['totalCount'] =response.result.totalCount;
					Betterlife.Blog.Store.blogStore.loadData(result); 
				} else {
					Betterlife.Blog.Store.blogStore.removeAll();                        
					Ext.Msg.alert('提示', '无符合条件的博客！');
				}
			});
		}, 
		/**
		 * 显示博客视图
		 * 显示博客的视图相对博客列表Grid的位置
		 * 1:上方,2:下方,0:隐藏。
		 */
		onUpDown:function(viewDirection){
			Betterlife.Blog.Config.View.Direction=viewDirection; 
			switch(viewDirection){
				case 1:
					this.ownerCt.north.add(Betterlife.Blog.View.Running.viewTabs);
					break;
				case 2:
					this.ownerCt.south.add(Betterlife.Blog.View.Running.viewTabs);
					break;
				case 3:
					this.ownerCt.west.add(Betterlife.Blog.View.Running.viewTabs);
					break;
				case 4:
					this.ownerCt.east.add(Betterlife.Blog.View.Running.viewTabs);
					break;    
			}  
			Betterlife.Blog.Cookie.set('View.Direction',Betterlife.Blog.Config.View.Direction);
			if (this.getSelectionModel().getSelected()!=null){
				if ((Betterlife.Blog.Config.View.IsFix==0)&&(Betterlife.Blog.Config.View.IsShow==1)){
					this.showBlog();     
				}
				Betterlife.Blog.Config.View.IsFix=1;
				Betterlife.Blog.View.Running.blogGrid.tvpView.menu.mBind.setChecked(true,true);  
				Betterlife.Blog.Config.View.IsShow=0;
				this.showBlog();     
			}
		}, 
		/**
		 * 显示博客
		 */
		showBlog : function(){
			if (this.getSelectionModel().getSelected()==null){
				Ext.Msg.alert('提示', '请先选择博客！');
				Betterlife.Blog.Config.View.IsShow=0;
				this.tvpView.toggle(false);
				return ;
			} 
			if (Betterlife.Blog.Config.View.IsFix==0){
				if (Betterlife.Blog.View.Running.view_window==null){
					Betterlife.Blog.View.Running.view_window=new Betterlife.Blog.View.BlogView.Window();
				}
				if (Betterlife.Blog.View.Running.view_window.hidden){
					Betterlife.Blog.View.Running.view_window.show();
					Betterlife.Blog.View.Running.view_window.winTabs.hideTabStripItem(Betterlife.Blog.View.Running.view_window.winTabs.tabFix);   
					this.updateViewBlog();
					this.tvpView.toggle(true);
					Betterlife.Blog.Config.View.IsShow=1;
				}else{
					this.hideBlog();
					Betterlife.Blog.Config.View.IsShow=0;
				}
				return;
			}
			switch(Betterlife.Blog.Config.View.Direction){
				case 1:
					if (!this.ownerCt.north.items.contains(Betterlife.Blog.View.Running.viewTabs)){
						this.ownerCt.north.add(Betterlife.Blog.View.Running.viewTabs);
					}
					break;
				case 2:
					if (!this.ownerCt.south.items.contains(Betterlife.Blog.View.Running.viewTabs)){
						this.ownerCt.south.add(Betterlife.Blog.View.Running.viewTabs);
					}
					break;
				case 3:
					if (!this.ownerCt.west.items.contains(Betterlife.Blog.View.Running.viewTabs)){
						this.ownerCt.west.add(Betterlife.Blog.View.Running.viewTabs);
					}
					break;
				case 4:
					if (!this.ownerCt.east.items.contains(Betterlife.Blog.View.Running.viewTabs)){
						this.ownerCt.east.add(Betterlife.Blog.View.Running.viewTabs);
					}
					break;    
			}  
			this.hideBlog();
			if (Betterlife.Blog.Config.View.IsShow==0){
				Betterlife.Blog.View.Running.viewTabs.enableCollapse();  
				switch(Betterlife.Blog.Config.View.Direction){
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
				this.updateViewBlog();
				this.tvpView.toggle(true);
				Betterlife.Blog.Config.View.IsShow=1;
			}else{
				Betterlife.Blog.Config.View.IsShow=0;
			}
			this.ownerCt.doLayout();
		},
		/**
		 * 隐藏博客
		 */
		hideBlog : function(){
			this.ownerCt.north.hide();
			this.ownerCt.south.hide();
			this.ownerCt.west.hide();   
			this.ownerCt.east.hide(); 
			if (Betterlife.Blog.View.Running.view_window!=null){
				Betterlife.Blog.View.Running.view_window.hide();
			}            
			this.tvpView.toggle(false);
			this.ownerCt.doLayout();
		},
		/**
		 * 更新当前博客显示信息
		 */
		updateViewBlog : function() {
			if (Betterlife.Blog.View.Running.view_window!=null){
				Betterlife.Blog.View.Running.view_window.winTabs.tabBlogDetail.update(this.getSelectionModel().getSelected().data);
			}
			Betterlife.Blog.View.Running.viewTabs.tabBlogDetail.update(this.getSelectionModel().getSelected().data);
		},
		/**
		 * 新建博客
		 */
		addBlog : function() {  
			if (Betterlife.Blog.View.Running.edit_window==null){   
				Betterlife.Blog.View.Running.edit_window=new Betterlife.Blog.View.EditWindow();   
			}     
			Betterlife.Blog.View.Running.edit_window.resetBtn.setVisible(false);
			Betterlife.Blog.View.Running.edit_window.saveBtn.setText('保 存');
			Betterlife.Blog.View.Running.edit_window.setTitle('添加博客');
			Betterlife.Blog.View.Running.edit_window.savetype=0;
			Betterlife.Blog.View.Running.edit_window.id.setValue("");
			if (CKEDITOR.instances.content){
				CKEDITOR.instances.content.setData("");
			}            
			Betterlife.Blog.View.Running.edit_window.show();   
			Betterlife.Blog.View.Running.edit_window.maximize();               
		},   
		/**
		 * 编辑博客时先获得选中的博客信息
		 */
		updateBlog : function() {
			if (Betterlife.Blog.View.Running.edit_window==null){   
				Betterlife.Blog.View.Running.edit_window=new Betterlife.Blog.View.EditWindow();   
			}            
			Betterlife.Blog.View.Running.edit_window.saveBtn.setText('修 改');
			Betterlife.Blog.View.Running.edit_window.resetBtn.setVisible(true);
			Betterlife.Blog.View.Running.edit_window.setTitle('修改博客');
			Betterlife.Blog.View.Running.edit_window.editForm.form.loadRecord(this.getSelectionModel().getSelected());
			Betterlife.Blog.View.Running.edit_window.savetype=1;
			if (CKEDITOR.instances.content){
				CKEDITOR.instances.content.setData(this.getSelectionModel().getSelected().data.content); 
			}            
			Betterlife.Blog.View.Running.edit_window.show();    
			Betterlife.Blog.View.Running.edit_window.maximize();                  
		},        
		/**
		 * 删除博客
		 */
		deleteBlog : function() {
			Ext.Msg.confirm('提示', '确实要删除所选的博客吗?', this.confirmDeleteBlog,this);
		}, 
		/**
		 * 确认删除博客
		 */
		confirmDeleteBlog : function(btn) {
			if (btn == 'yes') {  
				var del_blog_ids ="";
				var selectedRows    = this.getSelectionModel().getSelections();
				for ( var flag = 0; flag < selectedRows.length; flag++) {
					del_blog_ids=del_blog_ids+selectedRows[flag].data.id+",";
				}
				ExtServiceBlog.deleteByIds(del_blog_ids);
				this.doSelectBlog();
				Ext.Msg.alert("提示", "删除成功！");        
			}
		},
		/**
		 * 导出博客
		 */
		exportBlog : function() {            
			ExtServiceBlog.exportBlog(this.filter,function(provider, response) {  
				if (response.result.data) {
					window.open(response.result.data);
				}
			});                        
		},
		/**
		 * 导入博客
		 */
		importBlog : function() { 
			if (Betterlife.Blog.View.current_uploadWindow==null){   
				Betterlife.Blog.View.current_uploadWindow=new Betterlife.Blog.View.UploadWindow();   
			}     
			Betterlife.Blog.View.current_uploadWindow.show();
		}                
	}),
	/**
	 * 核心内容区
	 */
	Panel:Ext.extend(Ext.form.FormPanel,{
		constructor : function(config) {
			Betterlife.Blog.View.Running.blogGrid=new Betterlife.Blog.View.Grid();           
			if (Betterlife.Blog.Config.View.IsFix==0){
				Betterlife.Blog.View.Running.blogGrid.tvpView.menu.mBind.setChecked(false,true);  
			}
			config = Ext.apply({ 
				region : 'center',layout : 'fit', frame:true,
				items: {
					layout:'border',
					items:[
						Betterlife.Blog.View.Running.blogGrid, 
						{region:'north',ref:'north',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true},
						{region:'south',ref:'south',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true,items:[Betterlife.Blog.View.Running.viewTabs]}, 
						{region:'west',ref:'west',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true}, 
						{region:'east',ref:'east',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true} 
					]
				}
			}, config);   
			Betterlife.Blog.View.Panel.superclass.constructor.call(this, config);  
		}        
	}),
	/**
	 * 当前运行的可视化对象
	 */ 
	Running:{         
		/**
		 * 当前博客Grid对象
		 */
		blogGrid:null,  
		/**
		 * 显示博客信息及关联信息列表的Tab页
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
	Ext.state.Manager.setProvider(Betterlife.Blog.Cookie);
	Ext.Direct.addProvider(Ext.app.REMOTING_API);     
	Betterlife.Blog.Init();
	/**
	 * 博客数据模型获取数据Direct调用
	 */        
	Betterlife.Blog.Store.blogStore.proxy=new Ext.data.DirectProxy({ 
		api: {read:ExtServiceBlog.queryPageBlog}
	});   
	/**
	 * 博客页面布局
	 */
	Betterlife.Blog.Viewport = new Ext.Viewport({
		layout : 'border',
		items : [new Betterlife.Blog.View.Panel()]
	});
	Betterlife.Blog.Viewport.doLayout();    
	setTimeout(function(){
		Ext.get('loading').remove();
		Ext.get('loading-mask').fadeOut({
			remove:true
		});
	}, 250);
});  