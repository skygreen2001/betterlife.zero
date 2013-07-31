Ext.namespace("Betterlife.Admin.Admin");
Bb = Betterlife.Admin;
Bb.Admin={
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
			 * 显示系统管理人员的视图相对系统管理人员列表Grid的位置
			 * 1:上方,2:下方,3:左侧,4:右侧,
			 */
			Direction:2,
			/**
			 *是否显示。
			 */
			IsShow:0,
			/**
			 * 是否固定显示系统管理人员信息页(或者打开新窗口)
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
		if (Bb.Admin.Cookie.get('View.Direction')){
			Bb.Admin.Config.View.Direction=Bb.Admin.Cookie.get('View.Direction');
		}
		if (Bb.Admin.Cookie.get('View.IsFix')!=null){
			Bb.Admin.Config.View.IsFix=Bb.Admin.Cookie.get('View.IsFix');
		}
	}
}; 
/**
 * Model:数据模型   
 */
Bb.Admin.Store = { 
	/**
	 * 系统管理人员
	 */ 
	adminStore:new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			totalProperty: 'totalCount',
			successProperty: 'success',  
			root: 'data',remoteSort: true,                
			fields : [
				  {name: 'admin_id',type: 'int'},
				  {name: 'username',type: 'string'},
				  {name: 'realname',type: 'string'},
				  {name: 'password',type: 'string'},
				  {name: 'roletypeShow',type: 'string'},
				  {name: 'roletype',type: 'string'},
				  {name: 'roleid',type: 'int'},
				  {name: 'seescopeShow',type: 'string'},
				  {name: 'seescope',type: 'string'}
			]}         
		),
		writer: new Ext.data.JsonWriter({
			encode: false 
		}),
		listeners : {    
			beforeload : function(store, options) {   
				if (Ext.isReady) {  
					Ext.apply(options.params, Bb.Admin.View.Running.adminGrid.filter);//保证分页也将查询条件带上  
				}
			}
		}    
	})      
};
/**
 * View:系统管理人员显示组件   
 */
Bb.Admin.View={ 
	/**
	 * 编辑窗口：新建或者修改系统管理人员
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
							  {xtype: 'hidden',  name : 'admin_id',ref:'../admin_id'},
							  {fieldLabel : '用户名(<font color=red>*</font>)',name : 'username',allowBlank : false},
							  {fieldLabel : '真实姓名',name : 'realname'},
							  {fieldLabel : '密码(<font color=red>*</font>)',name : 'password',inputType: 'password',allowBlank : false},
							  {fieldLabel : '扮演角色',hiddenName : 'roletype',xtype : 'combo',mode : 'local',triggerAction : 'all',lazyRender : true,editable: false,allowBlank : false,
								store : new Ext.data.SimpleStore({
										fields : ['value', 'text'],
										data : [['0', '超级管理员'],['1', '管理人员'],['2', '运维人员'],['3', '合作伙伴']]
								  }),emptyText: '请选择系统管理员扮演角色',
								valueField : 'value',// 值
								displayField : 'text'// 显示文本
							}, {fieldLabel : '视野',hiddenName : 'seescope',xtype : 'combo',mode : 'local',triggerAction : 'all',lazyRender : true,editable: false,allowBlank : false,
								store : new Ext.data.SimpleStore({
										fields : ['value', 'text'],
										data : [['0', '只能查看自己的信息'],['1', '查看所有的信息']]
								  }),emptyText: '请选择视野',
								valueField : 'value',// 值
								displayField : 'text'// 显示文本
							}        
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
							this.editForm.api.submit=ExtServiceAdmin.save;                   
							this.editForm.getForm().submit({
								success : function(form, action) {
									Ext.Msg.alert("提示", "保存成功！");
									Bb.Admin.View.Running.adminGrid.doSelectAdmin();
									form.reset(); 
									editWindow.hide();
								},
								failure : function(form, action) {
									Ext.Msg.alert('提示', '失败');
								}
							});
						}else{
							this.editForm.api.submit=ExtServiceAdmin.update;
							this.editForm.getForm().submit({
								success : function(form, action) {                                                  
									Ext.Msg.show({title:'提示',msg: '修改成功！',buttons: {yes: '确定'},fn: function(){       
										Bb.Admin.View.Running.adminGrid.bottomToolbar.doRefresh(); 
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
						this.editForm.form.loadRecord(Bb.Admin.View.Running.adminGrid.getSelectionModel().getSelected());
 
					}                  
				}]    
			}, config);  
			Bb.Admin.View.EditWindow.superclass.constructor.call(this, config);     
		}
	}),
	/**
	 * 显示系统管理人员详情
	 */
	AdminView:{
		/**
		 * Tab页：容器包含显示与系统管理人员所有相关的信息
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
								if (Bb.Admin.View.Running.adminGrid.getSelectionModel().getSelected()==null){
									Ext.Msg.alert('提示', '请先选择系统管理人员');
									return false;
								} 
								Bb.Admin.Config.View.IsShow=1;
								Bb.Admin.View.Running.adminGrid.showAdmin();   
								Bb.Admin.View.Running.adminGrid.tvpView.menu.mBind.setChecked(false);
								return false;
							}
						}
					},
					items: [
						{title:'+',tabTip:'取消固定',ref:'tabFix',iconCls:'icon-fix'}
					]
				}, config);
				Bb.Admin.View.AdminView.Tabs.superclass.constructor.call(this, config);
 
				this.onAddItems();
			},
			/**
			 * 根据布局调整Tabs的宽度或者高度以及折叠
			 */
			enableCollapse:function(){
				if ((Bb.Admin.Config.View.Direction==1)||(Bb.Admin.Config.View.Direction==2)){
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
					{title: '基本信息',ref:'tabAdminDetail',iconCls:'tabs',
					 tpl: [
					  '<table class="viewdoblock">', 
						 '<tr class="entry"><td class="head">用户名</td><td class="content">{username}</td></tr>',
						 '<tr class="entry"><td class="head">真实姓名</td><td class="content">{realname}</td></tr>',
						 '<tr class="entry"><td class="head">密码</td><td class="content">{password}</td></tr>',
						 '<tr class="entry"><td class="head">扮演角色</td><td class="content">{roletypeShow}</td></tr>',
						 '<tr class="entry"><td class="head">视野</td><td class="content">{seescopeShow}</td></tr>',                      
					 '</table>' 
					 ]
					}
				);
				// this.add(
				// 	{title: '其他',iconCls:'tabs'}
				// );  
			}       
		}),
		/**
		 * 窗口:显示系统管理人员信息
		 */
		Window:Ext.extend(Ext.Window,{ 
			constructor : function(config) { 
				config = Ext.apply({
					title:"查看系统管理人员",constrainHeader:true,maximizable: true,minimizable : true, 
					width : 705,height : 500,minWidth : 450,minHeight : 400,
					layout : 'fit',resizable:true,plain : true,bodyStYle : 'padding:5px;',
					closeAction : "hide",
					items:[new Bb.Admin.View.AdminView.Tabs({ref:'winTabs',tabPosition:'top'})],
					listeners: { 
						minimize:function(w){
							w.hide();
							Bb.Admin.Config.View.IsShow=0;
							Bb.Admin.View.Running.adminGrid.tvpView.menu.mBind.setChecked(true);
						},
						hide:function(w){
							Bb.Admin.View.Running.adminGrid.tvpView.toggle(false);
						}   
					},
					buttons: [{
						text: '新增',scope:this,
						handler : function() {this.hide();Bb.Admin.View.Running.adminGrid.addAdmin();}
					},{
						text: '修改',scope:this,
						handler : function() {this.hide();Bb.Admin.View.Running.adminGrid.updateAdmin();}
					}]
				}, config);  
				Bb.Admin.View.AdminView.Window.superclass.constructor.call(this, config);   
			}        
		})
	},
	/**
	 * 视图：系统管理人员列表
	 */
	Grid:Ext.extend(Ext.grid.GridPanel, {
		constructor : function(config) {
			config = Ext.apply({
				/**
				 * 查询条件  
				 */
				filter:null,
				region : 'center',
				store : Bb.Admin.Store.adminStore,
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
						  {header : '标识',dataIndex : 'admin_id',hidden:true},
						  {header : '用户名',dataIndex : 'username'},
						  {header : '真实姓名',dataIndex : 'realname'},
						  {header : '扮演角色',dataIndex : 'roletypeShow'},
						  {header : '视野',dataIndex : 'seescopeShow'}                            
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
								'用户名 ','&nbsp;&nbsp;',{ref: '../ausername'},'&nbsp;&nbsp;', 
								'真实姓名 ','&nbsp;&nbsp;',{ref: '../arealname'},'&nbsp;&nbsp;',
								'扮演角色','&nbsp;&nbsp;',{ref: '../aroletype',xtype : 'combo',mode : 'local',
									triggerAction : 'all',lazyRender : true,editable: false,
									store : new Ext.data.SimpleStore({
										fields : ['value', 'text'],
										data : [['0', '超级管理员'],['1', '管理人员'],['2', '运维人员'],['3', '合作伙伴'],['4', '供应商'],['5', '渠道商']]
									  }),
									valueField : 'value',// 值
									displayField : 'text'// 显示文本
								},  
								{
									xtype : 'button',text : '查询',scope: this, 
									handler : function() {
										this.doSelectAdmin();
									}
								}, 
								{
									xtype : 'button',text : '重置',scope: this,
									handler : function() {
										this.topToolbar.ausername.setValue("");
										this.topToolbar.arealname.setValue("");
										this.topToolbar.aroletype.setValue("");  
										this.filter={};
										this.doSelectAdmin();
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
									text : '添加系统管理人员',iconCls : 'icon-add',
									handler : function() {
										this.addAdmin();
									}
								},'-',{
									text : '修改系统管理人员',ref: '../../btnUpdate',iconCls : 'icon-edit',disabled : true,  
									handler : function() {
										this.updateAdmin();
									}
								},'-',{
									text : '删除系统管理人员', ref: '../../btnRemove',iconCls : 'icon-delete',disabled : true,                                    
									handler : function() {
										this.deleteAdmin();
									}
								},'-',{ 
									xtype:'tbsplit',text: '查看系统管理人员', ref:'../../tvpView',iconCls : 'icon-updown',
									enableToggle: true, disabled : true,  
									handler:function(){this.showAdmin()},
									menu: {
										xtype:'menu',plain:true,
										items: [
											{text:'上方',group:'mlayout',checked:false,iconCls:'view-top',scope:this,handler:function(){this.onUpDown(1)}},
											{text:'下方',group:'mlayout',checked:true ,iconCls:'view-bottom',scope:this,handler:function(){this.onUpDown(2)}}, 
											{text:'左侧',group:'mlayout',checked:false,iconCls:'view-left',scope:this,handler:function(){this.onUpDown(3)}},
											{text:'右侧',group:'mlayout',checked:false,iconCls:'view-right',scope:this,handler:function(){this.onUpDown(4)}}, 
											{text:'隐藏',group:'mlayout',checked:false,iconCls:'view-hide',scope:this,handler:function(){this.hideAdmin();Bb.Admin.Config.View.IsShow=0;}},'-', 
											{text: '固定',ref:'mBind',checked: true,scope:this,checkHandler:function(item, checked){this.onBindGrid(item, checked);Bb.Admin.Cookie.set('View.IsFix',Bb.Admin.Config.View.IsFix);}} 
										]}
								},'-']}
					)]
				},                
				bbar: new Ext.PagingToolbar({          
					pageSize: Bb.Admin.Config.PageSize,
					store: Bb.Admin.Store.adminStore,
					scope:this,autoShow:true,displayInfo: true,
					displayMsg: '当前显示 {0} - {1}条记录/共 {2}条记录。',
					emptyMsg: "无显示数据",
					items: [
						{xtype:'label', text: '每页显示'},
						{xtype:'numberfield', value:Bb.Admin.Config.PageSize,minValue:1,width:35, 
							style:'text-align:center',allowBlank: false,
							listeners:
							{
								change:function(Field, newValue, oldValue){
									var num = parseInt(newValue);
									if (isNaN(num) || !num || num<1)
									{
										num = Bb.Admin.Config.PageSize;
										Field.setValue(num);
									}
									this.ownerCt.pageSize= num;
									Bb.Admin.Config.PageSize = num;
									this.ownerCt.ownerCt.doSelectAdmin();
								}, 
								specialKey :function(field,e){
									if (e.getKey() == Ext.EventObject.ENTER){
										var num = parseInt(field.getValue());
										if (isNaN(num) || !num || num<1)
										{
											num = Bb.Admin.Config.PageSize;
										}
										this.ownerCt.pageSize= num;
										Bb.Admin.Config.PageSize = num;
										this.ownerCt.ownerCt.doSelectAdmin();
									}
								}
							}
						},
						{xtype:'label', text: '个'}
					]
				})
			}, config);
			//初始化显示系统管理人员列表
			this.doSelectAdmin();
			Bb.Admin.View.Grid.superclass.constructor.call(this, config); 
			//创建在Grid里显示的系统管理人员信息Tab页
			Bb.Admin.View.Running.viewTabs=new Bb.Admin.View.AdminView.Tabs();
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
					this.grid.updateViewAdmin();                     
					if (sm.getCount() != 1){
						this.grid.hideAdmin();
						Bb.Admin.Config.View.IsShow=0;
					}else{
						if (Bb.Admin.View.IsSelectView==1){
							Bb.Admin.View.IsSelectView=0;  
							this.grid.showAdmin();   
						}     
					}    
				},
				rowdeselect: function(sm, rowIndex, record) {  
					if (sm.getCount() != 1){
						if (Bb.Admin.Config.View.IsShow==1){
							Bb.Admin.View.IsSelectView=1;    
						}             
						this.grid.hideAdmin();
						Bb.Admin.Config.View.IsShow=0;
					}    
				}
			}
		}),
		/**
		 * 双击选行
		 */
		onRowDoubleClick:function(grid, rowIndex, e){  
			if (!Bb.Admin.Config.View.IsShow){
				this.sm.selectRow(rowIndex);
				this.showAdmin();
				this.tvpView.toggle(true);
			}else{
				this.hideAdmin();
				Bb.Admin.Config.View.IsShow=0;
				this.sm.deselectRow(rowIndex);
				this.tvpView.toggle(false);
			}
		},
		/**
		 * 是否绑定在本窗口上
		 */
		onBindGrid:function(item, checked){ 
			if (checked){             
			   Bb.Admin.Config.View.IsFix=1; 
			}else{ 
			   Bb.Admin.Config.View.IsFix=0;   
			}
			if (this.getSelectionModel().getSelected()==null){
				Bb.Admin.Config.View.IsShow=0;
				return ;
			}
			if (Bb.Admin.Config.View.IsShow==1){
			   this.hideAdmin(); 
			   Bb.Admin.Config.View.IsShow=0;
			}
			this.showAdmin();   
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
		 * 查询符合条件的系统管理人员
		 */
		doSelectAdmin : function() {
			if (this.topToolbar){
				var ausername = this.topToolbar.ausername.getValue();
				var arealname = this.topToolbar.arealname.getValue();
				var aroletype = this.topToolbar.aroletype.getValue();
				this.filter       ={'username':ausername,'realname':arealname,'roletype':aroletype};
			}
			var condition = {'start':0,'limit':Bb.Admin.Config.PageSize};
			Ext.apply(condition,this.filter);
			ExtServiceAdmin.queryPageAdmin(condition,function(provider, response) {   
				if (response.result.data) {   
					var result           = new Array();
					result['data']       =response.result.data; 
					result['totalCount'] =response.result.totalCount;
					Bb.Admin.Store.adminStore.loadData(result); 
				} else {
					Bb.Admin.Store.adminStore.removeAll();                        
					Ext.Msg.alert('提示', '无符合条件的系统管理人员！');
				}
			});
		}, 
		/**
		 * 显示系统管理人员视图
		 * 显示系统管理人员的视图相对系统管理人员列表Grid的位置
		 * 1:上方,2:下方,0:隐藏。
		 */
		onUpDown:function(viewDirection){
			Bb.Admin.Config.View.Direction=viewDirection; 
			switch(viewDirection){
				case 1:
					this.ownerCt.north.add(Bb.Admin.View.Running.viewTabs);
					break;
				case 2:
					this.ownerCt.south.add(Bb.Admin.View.Running.viewTabs);
					break;
				case 3:
					this.ownerCt.west.add(Bb.Admin.View.Running.viewTabs);
					break;
				case 4:
					this.ownerCt.east.add(Bb.Admin.View.Running.viewTabs);
					break;    
			}  
			Bb.Admin.Cookie.set('View.Direction',Bb.Admin.Config.View.Direction);
			if (this.getSelectionModel().getSelected()!=null){
				if ((Bb.Admin.Config.View.IsFix==0)&&(Bb.Admin.Config.View.IsShow==1)){
					this.showAdmin();     
				}
				Bb.Admin.Config.View.IsFix=1;
				Bb.Admin.View.Running.adminGrid.tvpView.menu.mBind.setChecked(true,true);  
				Bb.Admin.Config.View.IsShow=0;
				this.showAdmin();     
			}
		}, 
		/**
		 * 显示系统管理人员
		 */
		showAdmin : function(){
			if (this.getSelectionModel().getSelected()==null){
				Ext.Msg.alert('提示', '请先选择系统管理人员！');
				Bb.Admin.Config.View.IsShow=0;
				this.tvpView.toggle(false);
				return ;
			} 
			if (Bb.Admin.Config.View.IsFix==0){
				if (Bb.Admin.View.Running.view_window==null){
					Bb.Admin.View.Running.view_window=new Bb.Admin.View.AdminView.Window();
				}
				if (Bb.Admin.View.Running.view_window.hidden){
					Bb.Admin.View.Running.view_window.show();
					Bb.Admin.View.Running.view_window.winTabs.hideTabStripItem(Bb.Admin.View.Running.view_window.winTabs.tabFix);   
					this.updateViewAdmin();
					this.tvpView.toggle(true);
					Bb.Admin.Config.View.IsShow=1;
				}else{
					this.hideAdmin();
					Bb.Admin.Config.View.IsShow=0;
				}
				return;
			}
			switch(Bb.Admin.Config.View.Direction){
				case 1:
					if (!this.ownerCt.north.items.contains(Bb.Admin.View.Running.viewTabs)){
						this.ownerCt.north.add(Bb.Admin.View.Running.viewTabs);
					}
					break;
				case 2:
					if (!this.ownerCt.south.items.contains(Bb.Admin.View.Running.viewTabs)){
						this.ownerCt.south.add(Bb.Admin.View.Running.viewTabs);
					}
					break;
				case 3:
					if (!this.ownerCt.west.items.contains(Bb.Admin.View.Running.viewTabs)){
						this.ownerCt.west.add(Bb.Admin.View.Running.viewTabs);
					}
					break;
				case 4:
					if (!this.ownerCt.east.items.contains(Bb.Admin.View.Running.viewTabs)){
						this.ownerCt.east.add(Bb.Admin.View.Running.viewTabs);
					}
					break;    
			}  
			this.hideAdmin();
			if (Bb.Admin.Config.View.IsShow==0){
				Bb.Admin.View.Running.viewTabs.enableCollapse();  
				switch(Bb.Admin.Config.View.Direction){
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
				this.updateViewAdmin();
				this.tvpView.toggle(true);
				Bb.Admin.Config.View.IsShow=1;
			}else{
				Bb.Admin.Config.View.IsShow=0;
			}

			this.ownerCt.doLayout();
		},
		/**
		 * 隐藏系统管理人员
		 */
		hideAdmin : function(){
			this.ownerCt.north.hide();
			this.ownerCt.south.hide();
			this.ownerCt.west.hide();   
			this.ownerCt.east.hide(); 
			if (Bb.Admin.View.Running.view_window!=null){
				Bb.Admin.View.Running.view_window.hide();
			}            
			this.tvpView.toggle(false);
			this.ownerCt.doLayout();
		},
		/**
		 * 更新当前系统管理人员显示信息
		 */
		updateViewAdmin : function() {
			if (Bb.Admin.View.Running.view_window!=null){
				Bb.Admin.View.Running.view_window.winTabs.tabAdminDetail.update(this.getSelectionModel().getSelected().data);
			}
			Bb.Admin.View.Running.viewTabs.tabAdminDetail.update(this.getSelectionModel().getSelected().data);
		},
		/**
		 * 新建系统管理人员
		 */
		addAdmin : function() {  
			if (Bb.Admin.View.Running.edit_window==null){   
				Bb.Admin.View.Running.edit_window=new Bb.Admin.View.EditWindow();   
			}     
			Bb.Admin.View.Running.edit_window.resetBtn.setVisible(false);
			Bb.Admin.View.Running.edit_window.saveBtn.setText('保 存');
			Bb.Admin.View.Running.edit_window.setTitle('添加系统管理人员');
			Bb.Admin.View.Running.edit_window.savetype=0;
			Bb.Admin.View.Running.edit_window.admin_id.setValue("");
			
			Bb.Admin.View.Running.edit_window.show();   
			Bb.Admin.View.Running.edit_window.maximize();               
		},   
		/**
		 * 编辑系统管理人员时先获得选中的系统管理人员信息
		 */
		updateAdmin : function() {
			if (Bb.Admin.View.Running.edit_window==null){   
				Bb.Admin.View.Running.edit_window=new Bb.Admin.View.EditWindow();   
			}            
			Bb.Admin.View.Running.edit_window.saveBtn.setText('修 改');
			Bb.Admin.View.Running.edit_window.resetBtn.setVisible(true);
			Bb.Admin.View.Running.edit_window.setTitle('修改系统管理人员');
			Bb.Admin.View.Running.edit_window.editForm.form.loadRecord(this.getSelectionModel().getSelected());
			Bb.Admin.View.Running.edit_window.savetype=1;
			
			Bb.Admin.View.Running.edit_window.show();    
			Bb.Admin.View.Running.edit_window.maximize();                  
		},        
		/**
		 * 删除系统管理人员
		 */
		deleteAdmin : function() {
			Ext.Msg.confirm('提示', '确实要删除所选的系统管理人员吗?', this.confirmDeleteAdmin,this);
		}, 
		/**
		 * 确认删除系统管理人员
		 */
		confirmDeleteAdmin : function(btn) {
			if (btn == 'yes') {  
				var del_admin_ids ="";
				var selectedRows    = this.getSelectionModel().getSelections();
				for ( var flag = 0; flag < selectedRows.length; flag++) {
					del_admin_ids=del_admin_ids+selectedRows[flag].data.admin_id+",";
				}
				ExtServiceAdmin.deleteByIds(del_admin_ids);
				this.doSelectAdmin();
				Ext.Msg.alert("提示", "删除成功！");        
			}
		}
	}),
	/**
	 * 核心内容区
	 */
	Panel:Ext.extend(Ext.form.FormPanel,{
		constructor : function(config) {
			Bb.Admin.View.Running.adminGrid=new Bb.Admin.View.Grid();           
			if (Bb.Admin.Config.View.IsFix==0){
				Bb.Admin.View.Running.adminGrid.tvpView.menu.mBind.setChecked(false,true);  
			}
			config = Ext.apply({ 
				region : 'center',layout : 'fit', frame:true,
				items: {
					layout:'border',
					items:[
						Bb.Admin.View.Running.adminGrid, 
						{region:'north',ref:'north',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true},
						{region:'south',ref:'south',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true,items:[Bb.Admin.View.Running.viewTabs]}, 
						{region:'west',ref:'west',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true}, 
						{region:'east',ref:'east',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true} 
					]
				}
			}, config);   
			Bb.Admin.View.Panel.superclass.constructor.call(this, config);  
		}        
	}),
	/**
	 * 当前运行的可视化对象
	 */ 
	Running:{         
		/**
		 * 当前系统管理人员Grid对象
		 */
		adminGrid:null,
  
		/**
		 * 显示系统管理人员信息及关联信息列表的Tab页
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
	Ext.state.Manager.setProvider(Bb.Admin.Cookie);
	Ext.Direct.addProvider(Ext.app.REMOTING_API);     
	Bb.Admin.Init();
	/**
	 * 系统管理人员数据模型获取数据Direct调用
	 */        
	Bb.Admin.Store.adminStore.proxy=new Ext.data.DirectProxy({ 
		api: {read:ExtServiceAdmin.queryPageAdmin}
	});   
	/**
	 * 系统管理人员页面布局
	 */
	Bb.Admin.Viewport = new Ext.Viewport({
		layout : 'border',
		items : [new Bb.Admin.View.Panel()]
	});
	Bb.Admin.Viewport.doLayout();                                  
	setTimeout(function(){
		Ext.get('loading').remove();
		Ext.get('loading-mask').fadeOut({
			remove:true
		});
	}, 250);
});     
