Ext.namespace("Betterlife.Admin.User");
Bb = Betterlife.Admin;
Bb.User={
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
			 * 显示用户的视图相对用户列表Grid的位置
			 * 1:上方,2:下方,3:左侧,4:右侧,
			 */
			Direction:2,
			/**
			 *是否显示。
			 */
			IsShow:0,
			/**
			 * 是否固定显示用户信息页(或者打开新窗口)
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
		if (Bb.User.Cookie.get('View.Direction')){
			Bb.User.Config.View.Direction=Bb.User.Cookie.get('View.Direction');
		}
		if (Bb.User.Cookie.get('View.IsFix')!=null){
			Bb.User.Config.View.IsFix=Bb.User.Cookie.get('View.IsFix');
		}
	}
}; 
/**
 * Model:数据模型   
 */
Bb.User.Store = { 
	/**
	 * 用户
	 */ 
	userStore:new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			totalProperty: 'totalCount',
			successProperty: 'success',  
			root: 'data',remoteSort: true,                
			fields : [
				  {name: 'user_id',type: 'int'},
				  {name: 'department_id',type: 'int'},
				  {name: 'department_name',type: 'string'},
				  {name: 'username',type: 'string'},
				  {name: 'password',type: 'string'},
				  {name: 'email',type: 'string'}
			]}         
		),
		writer: new Ext.data.JsonWriter({
			encode: false 
		}),
		listeners : {    
			beforeload : function(store, options) {   
				if (Ext.isReady) {  
					Ext.apply(options.params, Bb.User.View.Running.userGrid.filter);//保证分页也将查询条件带上  
				}
			}
		}    
	}),
	/**
	 * 用户所属部门
	 */
	departmentStore : new Ext.data.Store({
		proxy: new Ext.data.HttpProxy({
			url: 'home/admin/src/httpdata/department.php'
		  }),
		reader: new Ext.data.JsonReader({
			root: 'departments',
			autoLoad: true,
			totalProperty: 'totalCount',
			id: 'department_id'
		  }, [
			  {name: 'department_id', mapping: 'department_id'}, 
			  {name: 'department_name', mapping: 'department_name'} 
		])
	}),
	/**
	 * 博客
	 */
	blogStore:new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			totalProperty: 'totalCount',
			successProperty: 'success',
			root: 'data',remoteSort: true,
			fields : [
				  {name: 'blog_id',type: 'int'},
				  {name: 'user_id',type: 'int'},
				  {name: 'username',type: 'string'},
				  {name: 'blog_name',type: 'string'},
				  {name: 'content',type: 'string'}
			]}
		),
		writer: new Ext.data.JsonWriter({
			encode: false 
		})
	}),
	/**
	 * 评论
	 */
	commentStore:new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			totalProperty: 'totalCount',
			successProperty: 'success',
			root: 'data',remoteSort: true,
			fields : [
				  {name: 'comment_id',type: 'int'},
				  {name: 'user_id',type: 'int'},
				  {name: 'username',type: 'string'},
				  {name: 'comment',type: 'string'},
				  {name: 'blog_id',type: 'int'},
				  {name: 'blog_name',type: 'string'}
			]}
		),
		writer: new Ext.data.JsonWriter({
			encode: false 
		})
	})      
};
/**
 * View:用户显示组件   
 */
Bb.User.View={ 
	/**
	 * 编辑窗口：新建或者修改用户
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
							  {xtype: 'hidden',  name : 'user_id',ref:'../user_id'},
							  {xtype: 'hidden',name : 'department_id',id:'department_id'},
							  {
								 fieldLabel : '部门',xtype: 'combo',name : 'department_name',id : 'department_name',
								 store:Bb.User.Store.departmentStore,emptyText: '请选择部门',itemSelector: 'div.search-item',
								 loadingText: '查询中...',width: 570, pageSize:Bb.User.Config.PageSize,
								 displayField:'department_name',// 显示文本
								 mode: 'remote',  editable:true,minChars: 1,autoSelect :true,typeAhead: false,
								 forceSelection: true,triggerAction: 'all',resizable:false,selectOnFocus:true,
								 tpl:new Ext.XTemplate(
											'<tpl for="."><div class="search-item">',
												'<h3>{department_name}</h3>',
											'</div></tpl>'
								 ),
								 onSelect:function(record,index){
									 if(this.fireEvent('beforeselect', this, record, index) !== false){
										Ext.getCmp("department_id").setValue(record.data.department_id);
										Ext.getCmp("department_name").setValue(record.data.department_name);
										this.collapse();
									   }
								   }
							  },
							  {fieldLabel : '用户名(<font color=red>*</font>)',name : 'username',allowBlank : false},
							  {fieldLabel : '用户密码',name : 'password'},
							  {fieldLabel : '邮箱地址',name : 'email'}        
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
							this.editForm.api.submit=ExtServiceUser.save;                   
							this.editForm.getForm().submit({
								success : function(form, action) {
									Ext.Msg.alert("提示", "保存成功！");
									Bb.User.View.Running.userGrid.doSelectUser();
									form.reset(); 
									editWindow.hide();
								},
								failure : function(form, action) {
									Ext.Msg.alert('提示', '失败');
								}
							});
						}else{
							this.editForm.api.submit=ExtServiceUser.update;
							this.editForm.getForm().submit({
								success : function(form, action) {                                                  
									Ext.Msg.show({title:'提示',msg: '修改成功！',buttons: {yes: '确定'},fn: function(){       
										Bb.User.View.Running.userGrid.bottomToolbar.doRefresh(); 
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
						this.editForm.form.loadRecord(Bb.User.View.Running.userGrid.getSelectionModel().getSelected());
 
					}                  
				}]    
			}, config);  
			Bb.User.View.EditWindow.superclass.constructor.call(this, config);     
		}
	}),
	/**
	 * 显示用户详情
	 */
	UserView:{
		/**
		 * Tab页：容器包含显示与用户所有相关的信息
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
								if (Bb.User.View.Running.userGrid.getSelectionModel().getSelected()==null){
									Ext.Msg.alert('提示', '请先选择用户！');
									return false;
								} 
								Bb.User.Config.View.IsShow=1;
								Bb.User.View.Running.userGrid.showUser();   
								Bb.User.View.Running.userGrid.tvpView.menu.mBind.setChecked(false);
								return false;
							}
						}
					},
					items: [
						{title:'+',tabTip:'取消固定',ref:'tabFix',iconCls:'icon-fix'}
					]
				}, config);
				Bb.User.View.UserView.Tabs.superclass.constructor.call(this, config);
				Bb.User.View.Running.blogGrid=new Bb.User.View.BlogView.Grid();
				Bb.User.View.Running.commentGrid=new Bb.User.View.CommentView.Grid(); 
				this.onAddItems();
			},
			/**
			 * 根据布局调整Tabs的宽度或者高度以及折叠
			 */
			enableCollapse:function(){
				if ((Bb.User.Config.View.Direction==1)||(Bb.User.Config.View.Direction==2)){
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
					{title: '基本信息',ref:'tabUserDetail',iconCls:'tabs',
					 tpl: [
					  '<table class="viewdoblock">', 
						 '<tr class="entry"><td class="head">部门</td><td class="content">{department_name}</td></tr>',
						 '<tr class="entry"><td class="head">用户名</td><td class="content">{username}</td></tr>',
						 '<tr class="entry"><td class="head">邮箱地址</td><td class="content">{email}</td></tr>',    
					  '</table>' 
					 ]
					}
				);
				this.add(
					{title: '博客',iconCls:'tabs',tabWidth:150,
					 items:[Bb.User.View.Running.blogGrid]
					},
					{title: '评论',iconCls:'tabs',tabWidth:150,
					 items:[Bb.User.View.Running.commentGrid]
					}
				);         
			}       
		}),
		/**
		 * 窗口:显示用户信息
		 */
		Window:Ext.extend(Ext.Window,{ 
			constructor : function(config) { 
				config = Ext.apply({
					title:"查看用户",constrainHeader:true,maximizable: true,minimizable : true, 
					width : 705,height : 500,minWidth : 450,minHeight : 400,
					layout : 'fit',resizable:true,plain : true,bodyStYle : 'padding:5px;',
					closeAction : "hide",
					items:[new Bb.User.View.UserView.Tabs({ref:'winTabs',tabPosition:'top'})],
					listeners: { 
						minimize:function(w){
							w.hide();
							Bb.User.Config.View.IsShow=0;
							Bb.User.View.Running.userGrid.tvpView.menu.mBind.setChecked(true);
						},
						hide:function(w){
							Bb.User.View.Running.userGrid.tvpView.toggle(false);
						}   
					},
					buttons: [{
						text: '新增',scope:this,
						handler : function() {this.hide();Bb.User.View.Running.userGrid.addUser();}
					},{
						text: '修改',scope:this,
						handler : function() {this.hide();Bb.User.View.Running.userGrid.updateUser();}
					}]
				}, config);  
				Bb.User.View.UserView.Window.superclass.constructor.call(this, config);   
			}        
		})
	},
	/**
	 * 视图：博客列表
	 */
	BlogView:{
		/**
		 * 视图：博客列表
		 */
		Grid:Ext.extend(Ext.grid.GridPanel, {
			constructor : function(config) {
				config = Ext.apply({
					store : Bb.User.Store.blogStore,
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
						  {header : '用户',dataIndex : 'username'},
						  {header : '博客标题',dataIndex : 'blog_name'},
						  {header : '博客内容',dataIndex : 'content',width:450}                               
						]
					})
				}, config);
				Bb.User.View.BlogView.Grid.superclass.constructor.call(this, config); 
			},             
			/**
			 * 查询符合条件的合同订单
			 */
			doSelectBlog : function() {
				if (Bb.User.View.Running.userGrid&&Bb.User.View.Running.userGrid.getSelectionModel().getSelected()){
					var user_id = Bb.User.View.Running.userGrid.getSelectionModel().getSelected().data.user_id;
					var condition = {'user_id':user_id};
					ExtServiceBlog.queryPageBlog(condition,function(provider, response) { 
						if (response.result){  
							if (response.result.data) {   
								var result           = new Array();
								result['data']       =response.result.data; 
								Bb.User.Store.blogStore.loadData(result); 
							} else {
								Bb.User.Store.blogStore.removeAll();                        
								Ext.Msg.alert('提示', '无符合条件的合同订单！');
							}
						}
					});
				}
			}
		})
	},
	/**
	 * 视图：评论列表
	 */
	CommentView:{
		/**
		 * 视图：评论列表
		 */
		Grid:Ext.extend(Ext.grid.GridPanel, {
			constructor : function(config) {
				config = Ext.apply({
					store : Bb.User.Store.commentStore,
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
						  {header : '评论者',dataIndex : 'username'},
						  {header : '评论',dataIndex : 'comment',width:450},
						  {header : '博客',dataIndex : 'blog_name',width:450}                               
						]
					})
				}, config);
				Bb.User.View.CommentView.Grid.superclass.constructor.call(this, config); 
			},             
			/**
			 * 查询符合条件的合同订单
			 */
			doSelectComment : function() {
				if (Bb.User.View.Running.userGrid&&Bb.User.View.Running.userGrid.getSelectionModel().getSelected()){
					var user_id = Bb.User.View.Running.userGrid.getSelectionModel().getSelected().data.user_id;
					var condition = {'user_id':user_id};
					ExtServiceComment.queryPageComment(condition,function(provider, response) { 
						if (response.result){  
							if (response.result.data) {   
								var result           = new Array();
								result['data']       =response.result.data; 
								Bb.User.Store.commentStore.loadData(result); 
							} else {
								Bb.User.Store.commentStore.removeAll();                        
								Ext.Msg.alert('提示', '无符合条件的合同订单！');
							}
						}
					});
				}
			}
		})
	},
	/**
	 * 窗口：批量上传用户
	 */        
	UploadWindow:Ext.extend(Ext.Window,{ 
		constructor : function(config) { 
			config = Ext.apply({     
				title : '批量用户上传',
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
							emptyText: '请上传用户Excel文件',buttonText: '',
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
									url : 'index.php?go=admin.upload.uploadUser',
									success : function(form, action) {
										Ext.Msg.alert('成功', '上传成功');
										uploadWindow.hide();
										uploadWindow.uploadForm.upload_file.setValue('');
										Bb.User.View.Running.userGrid.doSelectUser();
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
			Bb.User.View.UploadWindow.superclass.constructor.call(this, config);     
		}        
	}),
	/**
	 * 视图：用户列表
	 */
	Grid:Ext.extend(Ext.grid.GridPanel, {
		constructor : function(config) {
			config = Ext.apply({
				/**
				 * 查询条件  
				 */
				filter:null,
				region : 'center',
				store : Bb.User.Store.userStore,
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
						  {header : '部门',dataIndex : 'department_name'},
						  {header : '用户名',dataIndex : 'username'},
						  {header : '邮箱地址',dataIndex : 'email'}                                 
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
							enableOverflow: true,width : 100,
							defaults : {
							   xtype : 'textfield'
							},
							items : [
								'用户名 ','&nbsp;&nbsp;',{ref: '../uusername'},'&nbsp;&nbsp;',                                
								{
									xtype : 'button',text : '查询',scope: this, 
									handler : function() {
										this.doSelectUser();
									}
								}, 
								{
									xtype : 'button',text : '重置',scope: this,
									handler : function() {
										this.topToolbar.uusername.setValue("");                                        
										this.filter={};
										this.doSelectUser();
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
									text : '添加用户',iconCls : 'icon-add',
									handler : function() {
										this.addUser();
									}
								},'-',{
									text : '修改用户',ref: '../../btnUpdate',iconCls : 'icon-edit',disabled : true,  
									handler : function() {
										this.updateUser();
									}
								},'-',{
									text : '删除用户', ref: '../../btnRemove',iconCls : 'icon-delete',disabled : true,                                    
									handler : function() {
										this.deleteUser();
									}
								},'-',{
									text : '导入',iconCls : 'icon-import', 
									handler : function() {
										this.importUser();
									}
								},'-',{
									text : '导出',iconCls : 'icon-export', 
									handler : function() { 
										this.exportUser();
									}
								},'-',{ 
									xtype:'tbsplit',text: '查看用户', ref:'../../tvpView',iconCls : 'icon-updown',
									enableToggle: true, disabled : true,  
									handler:function(){this.showUser()},
									menu: {
										xtype:'menu',plain:true,
										items: [
											{text:'上方',group:'mlayout',checked:false,iconCls:'view-top',scope:this,handler:function(){this.onUpDown(1)}},
											{text:'下方',group:'mlayout',checked:true ,iconCls:'view-bottom',scope:this,handler:function(){this.onUpDown(2)}}, 
											{text:'左侧',group:'mlayout',checked:false,iconCls:'view-left',scope:this,handler:function(){this.onUpDown(3)}},
											{text:'右侧',group:'mlayout',checked:false,iconCls:'view-right',scope:this,handler:function(){this.onUpDown(4)}}, 
											{text:'隐藏',group:'mlayout',checked:false,iconCls:'view-hide',scope:this,handler:function(){this.hideUser();Bb.User.Config.View.IsShow=0;}},'-', 
											{text: '固定',ref:'mBind',checked: true,scope:this,checkHandler:function(item, checked){this.onBindGrid(item, checked);Bb.User.Cookie.set('View.IsFix',Bb.User.Config.View.IsFix);}} 
										]}
								},'-']}
					)]
				},                
				bbar: new Ext.PagingToolbar({          
					pageSize: Bb.User.Config.PageSize,
					store: Bb.User.Store.userStore,
					scope:this,autoShow:true,displayInfo: true,
					displayMsg: '当前显示 {0} - {1}条记录/共 {2}条记录。',
					emptyMsg: "无显示数据",
					listeners:{
						change:function(thisbar,pagedata){
							if (Bb.User.Config.View.IsShow==1){
								Bb.User.View.IsSelectView=1;    
							}             
							this.ownerCt.hideUser();
							Bb.User.Config.View.IsShow=0;                            
						}
					},                    
					items: [
						{xtype:'label', text: '每页显示'},
						{xtype:'numberfield', value:Bb.User.Config.PageSize,minValue:1,width:35, 
							style:'text-align:center',allowBlank: false,
							listeners:
							{
								change:function(Field, newValue, oldValue){
									var num = parseInt(newValue);
									if (isNaN(num) || !num || num<1)
									{
										num = Bb.User.Config.PageSize;
										Field.setValue(num);
									}
									this.ownerCt.pageSize= num;
									Bb.User.Config.PageSize = num;
									this.ownerCt.ownerCt.doSelectUser();
								}, 
								specialKey :function(field,e){
									if (e.getKey() == Ext.EventObject.ENTER){
										var num = parseInt(field.getValue());
										if (isNaN(num) || !num || num<1)
										{
											num = Bb.User.Config.PageSize;
										}
										this.ownerCt.pageSize= num;
										Bb.User.Config.PageSize = num;
										this.ownerCt.ownerCt.doSelectUser();
									}
								}
							}
						},
						{xtype:'label', text: '个'}
					]
				})
			}, config);
			//初始化显示用户列表
			this.doSelectUser();
			Bb.User.View.Grid.superclass.constructor.call(this, config); 
			//创建在Grid里显示的用户信息Tab页
			Bb.User.View.Running.viewTabs=new Bb.User.View.UserView.Tabs();
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
					this.grid.updateViewUser();                     
					if (sm.getCount() != 1){
						this.grid.hideUser();
						Bb.User.Config.View.IsShow=0;
					}else{
						if (Bb.User.View.IsSelectView==1){
							Bb.User.View.IsSelectView=0;  
							this.grid.showUser();   
						}     
					}    
				},
				rowdeselect: function(sm, rowIndex, record) {  
					if (sm.getCount() != 1){
						if (Bb.User.Config.View.IsShow==1){
							Bb.User.View.IsSelectView=1;    
						}             
						this.grid.hideUser();
						Bb.User.Config.View.IsShow=0;
					}    
				}
			}
		}),
		/**
		 * 双击选行
		 */
		onRowDoubleClick:function(grid, rowIndex, e){  
			if (!Bb.User.Config.View.IsShow){
				this.sm.selectRow(rowIndex);
				this.showUser();
				this.tvpView.toggle(true);
			}else{
				this.hideUser();
				Bb.User.Config.View.IsShow=0;
				this.sm.deselectRow(rowIndex);
				this.tvpView.toggle(false);
			}
		},
		/**
		 * 是否绑定在本窗口上
		 */
		onBindGrid:function(item, checked){ 
			if (checked){             
			   Bb.User.Config.View.IsFix=1; 
			}else{ 
			   Bb.User.Config.View.IsFix=0;   
			}
			if (this.getSelectionModel().getSelected()==null){
				Bb.User.Config.View.IsShow=0;
				return ;
			}
			if (Bb.User.Config.View.IsShow==1){
			   this.hideUser(); 
			   Bb.User.Config.View.IsShow=0;
			}
			this.showUser();   
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
		 * 查询符合条件的用户
		 */
		doSelectUser : function() {
			if (this.topToolbar){
				var uusername = this.topToolbar.uusername.getValue();
				this.filter       ={'username':uusername};
			}
			var condition = {'start':0,'limit':Bb.User.Config.PageSize};
			Ext.apply(condition,this.filter);
			ExtServiceUser.queryPageUser(condition,function(provider, response) {   
				if (response.result.data) {   
					var result           = new Array();
					result['data']       =response.result.data; 
					result['totalCount'] =response.result.totalCount;
					Bb.User.Store.userStore.loadData(result); 
				} else {
					Bb.User.Store.userStore.removeAll();                        
					Ext.Msg.alert('提示', '无符合条件的用户！');
				}
			});
		}, 
		/**
		 * 显示用户视图
		 * 显示用户的视图相对用户列表Grid的位置
		 * 1:上方,2:下方,0:隐藏。
		 */
		onUpDown:function(viewDirection){
			Bb.User.Config.View.Direction=viewDirection; 
			switch(viewDirection){
				case 1:
					this.ownerCt.north.add(Bb.User.View.Running.viewTabs);
					break;
				case 2:
					this.ownerCt.south.add(Bb.User.View.Running.viewTabs);
					break;
				case 3:
					this.ownerCt.west.add(Bb.User.View.Running.viewTabs);
					break;
				case 4:
					this.ownerCt.east.add(Bb.User.View.Running.viewTabs);
					break;    
			}  
			Bb.User.Cookie.set('View.Direction',Bb.User.Config.View.Direction);
			if (this.getSelectionModel().getSelected()!=null){
				if ((Bb.User.Config.View.IsFix==0)&&(Bb.User.Config.View.IsShow==1)){
					this.showUser();     
				}
				Bb.User.Config.View.IsFix=1;
				Bb.User.View.Running.userGrid.tvpView.menu.mBind.setChecked(true,true);  
				Bb.User.Config.View.IsShow=0;
				this.showUser();     
			}
		}, 
		/**
		 * 显示用户
		 */
		showUser : function(){
			if (this.getSelectionModel().getSelected()==null){
				Ext.Msg.alert('提示', '请先选择用户！');
				Bb.User.Config.View.IsShow=0;
				this.tvpView.toggle(false);
				return ;
			} 
			if (Bb.User.Config.View.IsFix==0){
				if (Bb.User.View.Running.view_window==null){
					Bb.User.View.Running.view_window=new Bb.User.View.UserView.Window();
				}
				if (Bb.User.View.Running.view_window.hidden){
					Bb.User.View.Running.view_window.show();
					Bb.User.View.Running.view_window.winTabs.hideTabStripItem(Bb.User.View.Running.view_window.winTabs.tabFix);   
					this.updateViewUser();
					this.tvpView.toggle(true);
					Bb.User.Config.View.IsShow=1;
				}else{
					this.hideUser();
					Bb.User.Config.View.IsShow=0;
				}
				return;
			}
			switch(Bb.User.Config.View.Direction){
				case 1:
					if (!this.ownerCt.north.items.contains(Bb.User.View.Running.viewTabs)){
						this.ownerCt.north.add(Bb.User.View.Running.viewTabs);
					}
					break;
				case 2:
					if (!this.ownerCt.south.items.contains(Bb.User.View.Running.viewTabs)){
						this.ownerCt.south.add(Bb.User.View.Running.viewTabs);
					}
					break;
				case 3:
					if (!this.ownerCt.west.items.contains(Bb.User.View.Running.viewTabs)){
						this.ownerCt.west.add(Bb.User.View.Running.viewTabs);
					}
					break;
				case 4:
					if (!this.ownerCt.east.items.contains(Bb.User.View.Running.viewTabs)){
						this.ownerCt.east.add(Bb.User.View.Running.viewTabs);
					}
					break;    
			}  
			this.hideUser();
			if (Bb.User.Config.View.IsShow==0){
				Bb.User.View.Running.viewTabs.enableCollapse();  
				switch(Bb.User.Config.View.Direction){
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
				this.updateViewUser();
				this.tvpView.toggle(true);
				Bb.User.Config.View.IsShow=1;
			}else{
				Bb.User.Config.View.IsShow=0;
			}
			this.ownerCt.doLayout();
		},
		/**
		 * 隐藏用户
		 */
		hideUser : function(){
			this.ownerCt.north.hide();
			this.ownerCt.south.hide();
			this.ownerCt.west.hide();   
			this.ownerCt.east.hide(); 
			if (Bb.User.View.Running.view_window!=null){
				Bb.User.View.Running.view_window.hide();
			}            
			this.tvpView.toggle(false);
			this.ownerCt.doLayout();
		},
		/**
		 * 更新当前用户显示信息
		 */
		updateViewUser : function() {
			Bb.User.View.Running.blogGrid.doSelectBlog();
			Bb.User.View.Running.commentGrid.doSelectComment();
			if (Bb.User.View.Running.view_window!=null){
				Bb.User.View.Running.view_window.winTabs.tabUserDetail.update(this.getSelectionModel().getSelected().data);
			}
			Bb.User.View.Running.viewTabs.tabUserDetail.update(this.getSelectionModel().getSelected().data);
		},
		/**
		 * 新建用户
		 */
		addUser : function() {  
			if (Bb.User.View.Running.edit_window==null){   
				Bb.User.View.Running.edit_window=new Bb.User.View.EditWindow();   
			}     
			Bb.User.View.Running.edit_window.resetBtn.setVisible(false);
			Bb.User.View.Running.edit_window.saveBtn.setText('保 存');
			Bb.User.View.Running.edit_window.setTitle('添加用户');
			Bb.User.View.Running.edit_window.savetype=0;
			Bb.User.View.Running.edit_window.user_id.setValue("");
			
			Bb.User.View.Running.edit_window.show();   
			Bb.User.View.Running.edit_window.maximize();               
		},   
		/**
		 * 编辑用户时先获得选中的用户信息
		 */
		updateUser : function() {
			if (Bb.User.View.Running.edit_window==null){   
				Bb.User.View.Running.edit_window=new Bb.User.View.EditWindow();   
			}            
			Bb.User.View.Running.edit_window.saveBtn.setText('修 改');
			Bb.User.View.Running.edit_window.resetBtn.setVisible(true);
			Bb.User.View.Running.edit_window.setTitle('修改用户');
			Bb.User.View.Running.edit_window.editForm.form.loadRecord(this.getSelectionModel().getSelected());
			Bb.User.View.Running.edit_window.savetype=1;
			
			Bb.User.View.Running.edit_window.show();    
			Bb.User.View.Running.edit_window.maximize();                  
		},        
		/**
		 * 删除用户
		 */
		deleteUser : function() {
			Ext.Msg.confirm('提示', '确实要删除所选的用户吗?', this.confirmDeleteUser,this);
		}, 
		/**
		 * 确认删除用户
		 */
		confirmDeleteUser : function(btn) {
			if (btn == 'yes') {  
				var del_user_ids ="";
				var selectedRows    = this.getSelectionModel().getSelections();
				for ( var flag = 0; flag < selectedRows.length; flag++) {
					del_user_ids=del_user_ids+selectedRows[flag].data.user_id+",";
				}
				ExtServiceUser.deleteByIds(del_user_ids);
				this.doSelectUser();
				Ext.Msg.alert("提示", "删除成功！");        
			}
		},
		/**
		 * 导出用户
		 */
		exportUser : function() {            
			ExtServiceUser.exportUser(this.filter,function(provider, response) {  
				if (response.result.data) {
					window.open(response.result.data);
				}
			});                        
		},
		/**
		 * 导入用户
		 */
		importUser : function() { 
			if (Bb.User.View.current_uploadWindow==null){   
				Bb.User.View.current_uploadWindow=new Bb.User.View.UploadWindow();   
			}     
			Bb.User.View.current_uploadWindow.show();
		}                
	}),
	/**
	 * 核心内容区
	 */
	Panel:Ext.extend(Ext.form.FormPanel,{
		constructor : function(config) {
			Bb.User.View.Running.userGrid=new Bb.User.View.Grid();           
			if (Bb.User.Config.View.IsFix==0){
				Bb.User.View.Running.userGrid.tvpView.menu.mBind.setChecked(false,true);  
			}
			config = Ext.apply({ 
				region : 'center',layout : 'fit', frame:true,
				items: {
					layout:'border',
					items:[
						Bb.User.View.Running.userGrid, 
						{region:'north',ref:'north',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true},
						{region:'south',ref:'south',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true,items:[Bb.User.View.Running.viewTabs]}, 
						{region:'west',ref:'west',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true}, 
						{region:'east',ref:'east',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true} 
					]
				}
			}, config);   
			Bb.User.View.Panel.superclass.constructor.call(this, config);  
		}        
	}),
	/**
	 * 当前运行的可视化对象
	 */ 
	Running:{         
		/**
		 * 当前用户Grid对象
		 */
		userGrid:null,
		/**
		 * 当前博客Grid对象
		 */
		blogGrid:null,
		/**
		 * 当前评论Grid对象
		 */
		commentGrid:null,  
		/**
		 * 显示用户信息及关联信息列表的Tab页
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
	Ext.state.Manager.setProvider(Bb.User.Cookie);
	Ext.Direct.addProvider(Ext.app.REMOTING_API);     
	Bb.User.Init();
	/**
	 * 用户数据模型获取数据Direct调用
	 */        
	Bb.User.Store.userStore.proxy=new Ext.data.DirectProxy({ 
		api: {read:ExtServiceUser.queryPageUser}
	});   
	/**
	 * 用户页面布局
	 */
	Bb.User.Viewport = new Ext.Viewport({
		layout : 'border',
		items : [new Bb.User.View.Panel()]
	});
	Bb.User.Viewport.doLayout();                                  
	setTimeout(function(){
		Ext.get('loading').remove();
		Ext.get('loading-mask').fadeOut({
			remove:true
		});
	}, 250);
});     