Ext.namespace("Betterlife.Admin.Blog");
Bb = Betterlife.Admin;
Bb.Blog={
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
		},
        /**
         * 在线编辑器类型。
         * 1:CkEditor,2:KindEditor,3:xhEditor,4:UEditor
         * 配合Action的变量配置$online_editor
         */
        OnlineEditor:4
	},
	/**
	 * Cookie设置
	 */
	Cookie:new Ext.state.CookieProvider(),
	/**
	 * 初始化
	 */
	Init:function(){
		if (Bb.Blog.Cookie.get('View.Direction')){
			Bb.Blog.Config.View.Direction=Bb.Blog.Cookie.get('View.Direction');
		}
		if (Bb.Blog.Cookie.get('View.IsFix')!=null){
			Bb.Blog.Config.View.IsFix=Bb.Blog.Cookie.get('View.IsFix');
		}
        if (Ext.util.Cookies.get('OnlineEditor')!=null){
            Bb.Blog.Config.OnlineEditor=parseInt(Ext.util.Cookies.get('OnlineEditor'));
        }

	}
};
/**
 * Model:数据模型
 */
Bb.Blog.Store = {
	/**
	 * 博客
	 */
	blogStore:new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			totalProperty: 'totalCount',
			successProperty: 'success',
			root: 'data',remoteSort: true,
			fields : [
                {name: 'ID',type: 'int'},
                {name: 'User_ID',type: 'int'},
                {name: 'Username',type: 'string'},
                {name: 'Blog_Name',type: 'string'},
                {name: 'Blog_Content',type: 'string'},
                {name: 'Blog_ContentShow',type:'string'},
				{name: 'CommitTime',type: 'date',dateFormat:'Y-m-d H:i:s'},
				{name: 'UpdateTime',type: 'date',dateFormat:'Y-m-d H:i:s'},
			]}
		),
		writer: new Ext.data.JsonWriter({
			encode: false
		}),
		listeners : {
			beforeload : function(store, options) {
				if (Ext.isReady) {
					if (!options.params.limit)options.params.limit=Bb.Blog.Config.PageSize;
					Ext.apply(options.params, Bb.Blog.View.Running.blogGrid.filter);//保证分页也将查询条件带上
				}
			}
		}
	}),
    /**
     * 用户
     */
    userStoreForCombo:new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: 'home/admin/src/httpdata/user.php'
        }),
        reader: new Ext.data.JsonReader({
            root: 'users',
            autoLoad: true,
            totalProperty: 'totalCount',
            idProperty: 'User_ID'
        }, [
            {name: 'User_ID', mapping: 'User_ID'},
            {name: 'Username', mapping: 'Username'}
        ])
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
                {name: 'ID',type: 'int'},
                {name: 'Username',type: 'string'},
                {name: 'Comment',type: 'string'},
                {name: 'Blog_Name',type: 'string'},
				{name: 'CommitTime',type: 'date',dateFormat:'Y-m-d H:i:s'}
            ]}
        ),
        writer: new Ext.data.JsonWriter({
            encode: false
        }),
        listeners : {
            beforeload : function(store, options) {
                if (Ext.isReady) {
                    if (!options.params.limit)options.params.limit=Bb.Blog.Config.PageSize;
                    Ext.apply(options.params, Bb.Blog.View.Running.commentGrid.filter);//保证分页也将查询条件带上
                }
            }
        }
    }),
    /**
     * 博客
     */
    blogStoreForCombo:new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: 'home/admin/src/httpdata/blog.php'
        }),
        reader: new Ext.data.JsonReader({
            root: 'blogs',
            autoLoad: true,
            totalProperty: 'totalCount',
            idProperty: 'ID'
        }, [
            {name: 'ID', mapping: 'ID'},
            {name: 'Blog_Name', mapping: 'Blog_Name'}
        ])
    })
};
/**
 * View:博客显示组件
 */
Bb.Blog.View={
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
                        switch (Bb.Blog.Config.OnlineEditor)
                        {
                            case 1:
                                ckeditor_replace_Blog_Content();
                                break
                            case 2:
                                Bb.Blog.View.EditWindow.KindEditor_Blog_Content = KindEditor.create('textarea[name="Blog_Content"]',{width:'98%',minHeith:'350px', filterMode:true});
                                break
                            case 3:
                                pageInit_Blog_Content();
                                break
                            default:
                                this.editForm.Blog_Content.setWidth("98%");
                                pageInit_ue_Blog_Content();
                        }
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
                            {xtype: 'hidden',name : 'ID',ref:'../ID'},
                            {xtype: 'hidden',name : 'User_ID',ref:'../User_ID'},
                            {
                                 fieldLabel : '用户',xtype: 'combo',name : 'Username',ref : '../Username',
                                 store:Bb.Blog.Store.userStoreForCombo,emptyText: '请选择用户',itemSelector: 'div.search-item',
                                 loadingText: '查询中...',width: 570, pageSize:Bb.Blog.Config.PageSize,
                                 displayField:'Username',grid:this,
                                 mode: 'remote',  editable:true,minChars: 1,autoSelect :true,typeAhead: false,
                                 forceSelection: true,triggerAction: 'all',resizable:false,selectOnFocus:true,
                                 tpl:new Ext.XTemplate(
                                     '<tpl for="."><div class="search-item">',
                                         '<h3>{Username}</h3>',
                                     '</div></tpl>'
                                 ),
                                 listeners:{
                                     'beforequery': function(event){delete event.combo.lastQuery;}
                                 },
                                 onSelect:function(record,index){
                                     if(this.fireEvent('beforeselect', this, record, index) !== false){
                                        this.grid.User_ID.setValue(record.data.User_ID);
                                        this.grid.Username.setValue(record.data.Username);
                                        this.collapse();
                                     }
                                 }
                            },
                            {fieldLabel : '博客标题',name : 'Blog_Name'},
                            {fieldLabel : '博客内容',name : 'Blog_Content',xtype : 'textarea',id:'Blog_Content',ref:'Blog_Content'}
						]
					})
				],
				buttons : [{
					text: "",ref : "../saveBtn",scope:this,
					handler : function() {
                        switch (Bb.Blog.Config.OnlineEditor)
                        {
                            case 1:
                                if (CKEDITOR.instances.Blog_Content) this.editForm.Blog_Content.setValue(CKEDITOR.instances.Blog_Content.getData());
                                break
                            case 2:
                                if (Bb.Blog.View.EditWindow.KindEditor_Blog_Content)this.editForm.Blog_Content.setValue(Bb.Blog.View.EditWindow.KindEditor_Blog_Content.html());
                                break
                            case 3:
                                if (xhEditor_Blog_Content)this.editForm.Blog_Content.setValue(xhEditor_Blog_Content.getSource());
                                break
                            default:
                                if (ue_Blog_Content)this.editForm.Blog_Content.setValue(ue_Blog_Content.getContent());
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
									Bb.Blog.View.Running.blogGrid.doSelectBlog();
									form.reset();
									editWindow.hide();
								},
								failure : function(form, response) {
									Ext.Msg.show({title:'提示',width:350,buttons: {yes: '确定'},msg:response.result.msg});
								}
							});
						}else{
							this.editForm.api.submit=ExtServiceBlog.update;
							this.editForm.getForm().submit({
								success : function(form, action) {
									Bb.Blog.View.Running.blogGrid.store.reload();
									Ext.Msg.show({title:'提示',msg: '修改成功！',buttons: {yes: '确定'},fn: function(){
										Bb.Blog.View.Running.blogGrid.bottomToolbar.doRefresh();
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
						this.editForm.form.loadRecord(Bb.Blog.View.Running.blogGrid.getSelectionModel().getSelected());
                        switch (Bb.Blog.Config.OnlineEditor)
                        {
                            case 1:
                                if (CKEDITOR.instances.Blog_Content) CKEDITOR.instances.Blog_Content.setData(Bb.Blog.View.Running.blogGrid.getSelectionModel().getSelected().data.Blog_Content);
                                break
                            case 2:
                                if (Bb.Blog.View.EditWindow.KindEditor_Blog_Content) Bb.Blog.View.EditWindow.KindEditor_Blog_Content.html(Bb.Blog.View.Running.blogGrid.getSelectionModel().getSelected().data.Blog_Content);
                                break
                            case 3:
                                if (xhEditor_Blog_Content) xhEditor_Blog_Content.setSource(Bb.Blog.View.Running.blogGrid.getSelectionModel().getSelected().data.Blog_Content);
                                break
                            default:
                                if (ue_Blog_Content) ue_Blog_Content.setContent(Bb.Blog.View.Running.blogGrid.getSelectionModel().getSelected().data.Blog_Content);
                        }
					}
				}]
			}, config);
			Bb.Blog.View.EditWindow.superclass.constructor.call(this, config);
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
								if (Bb.Blog.View.Running.blogGrid.getSelectionModel().getSelected()==null){
									Ext.Msg.alert('提示', '请先选择博客！');
									return false;
								}
								Bb.Blog.Config.View.IsShow=1;
								Bb.Blog.View.Running.blogGrid.showBlog();
								Bb.Blog.View.Running.blogGrid.tvpView.menu.mBind.setChecked(false);
								return false;
							}
						}
					},
					items: [
						{title:'+',tabTip:'取消固定',ref:'tabFix',iconCls:'icon-fix'}
					]
				}, config);
				Bb.Blog.View.BlogView.Tabs.superclass.constructor.call(this, config);
                Bb.Blog.View.Running.commentGrid=new Bb.Blog.View.CommentView.Grid();
				this.onAddItems();
			},
			/**
			 * 根据布局调整Tabs的宽度或者高度以及折叠
			 */
			enableCollapse:function(){
				if ((Bb.Blog.Config.View.Direction==1)||(Bb.Blog.Config.View.Direction==2)){
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
                         '    <tr class="entry"><td class="head">用户</td><td class="content">{Username}</td></tr>',
                         '    <tr class="entry"><td class="head">博客标题</td><td class="content">{Blog_Name}</td></tr>',
                         '    <tr class="entry"><td class="head">博客内容</td><td class="content">{Blog_ContentShow}</td></tr>',
						 '	  <tr class="entry"><td class="head">创建时间</td><td class="content">{CommitTime:date("Y-m-d H:i")}</td></tr>',
						 '	  <tr class="entry"><td class="head">更新时间</td><td class="content">{UpdateTime:date("Y-m-d H:i")}</td></tr>',
						 '</table>'
					 ]}
				);
                this.add(
                    {title: '评论',iconCls:'tabs',tabWidth:150,
                     items:[Bb.Blog.View.Running.commentGrid]
                    }
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
					width : 705,height : 500,minWidth : 450,minHeight : 400,
					layout : 'fit',resizable:true,plain : true,bodyStyle : 'padding:5px;',
					closeAction : "hide",
					items:[new Bb.Blog.View.BlogView.Tabs({ref:'winTabs',tabPosition:'top'})],
					listeners: {
						minimize:function(w){
							w.hide();
							Bb.Blog.Config.View.IsShow=0;
							Bb.Blog.View.Running.blogGrid.tvpView.menu.mBind.setChecked(true);
						},
						hide:function(w){
							Bb.Blog.View.Running.blogGrid.tvpView.toggle(false);
						}
					},
					buttons: [{
						text: '新增博客',scope:this,
						handler : function() {this.hide();Bb.Blog.View.Running.blogGrid.addBlog();}
					},{
						text: '修改博客',scope:this,
						handler : function() {this.hide();Bb.Blog.View.Running.blogGrid.updateBlog();}
					}]
				}, config);
				Bb.Blog.View.BlogView.Window.superclass.constructor.call(this, config);
			}
		})
	},
	/**
	 * 视图：评论列表
	 */
	CommentView:{
		/**
		 *  当前创建的评论编辑窗口
		 */
		edit_window:null,
		/**
		 * 编辑窗口：新建或者修改评论
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
						},
                        afterrender:function(){
                            switch (Bb.Blog.Config.OnlineEditor)
                            {
                                case 1:
                                    ckeditor_replace_Comment();
                                    break
                                case 2:
                                    Bb.Blog.View.CommentView.EditWindow.KindEditor_Comment = KindEditor.create('textarea[name="Comment"]',{width:'98%',minHeith:'350px', filterMode:true});
                                    break
                                case 3:
                                    pageInit_Comment();
                                    break
                                default:
                                    this.editForm.Comment.setWidth("98%");
                                    pageInit_ue_Comment();
                            }
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
                                {xtype: 'hidden',name : 'ID',ref:'../ID'},
                                {xtype: 'hidden',name : 'User_ID',ref:'../User_ID'},
                                {
                                     fieldLabel : '评论者',xtype: 'combo',name : 'Username',ref : '../Username',
                                     store:Bb.Blog.Store.userStoreForCombo,emptyText: '请选择评论者',itemSelector: 'div.search-item',
                                     loadingText: '查询中...',width: 570, pageSize:Bb.Blog.Config.PageSize,
                                     displayField:'Username',grid:this,
                                     mode: 'remote',  editable:true,minChars: 1,autoSelect :true,typeAhead: false,
                                     forceSelection: true,triggerAction: 'all',resizable:false,selectOnFocus:true,
                                     tpl:new Ext.XTemplate(
                                         '<tpl for="."><div class="search-item">',
                                             '<h3>{Username}</h3>',
                                         '</div></tpl>'
                                     ),
                                     listeners:{
                                         'beforequery': function(event){delete event.combo.lastQuery;}
                                     },
                                     onSelect:function(record,index){
                                         if(this.fireEvent('beforeselect', this, record, index) !== false){
                                            this.grid.User_ID.setValue(record.data.User_ID);
                                            this.grid.Username.setValue(record.data.Username);
                                            this.collapse();
                                         }
                                     }
                                },
                                {fieldLabel : '评论',name : 'Comment',xtype : 'textarea',id:'Comment',ref:'Comment'},
                                {xtype: 'hidden',name : 'Blog_ID',ref:'../Blog_ID'},
                                {
                                     fieldLabel : '博客',xtype: 'combo',name : 'Blog_Name',ref : '../Blog_Name',
                                     store:Bb.Blog.Store.blogStoreForCombo,emptyText: '请选择博客',itemSelector: 'div.search-item',
                                     loadingText: '查询中...',width: 570, pageSize:Bb.Blog.Config.PageSize,
                                     displayField:'Blog_Name',grid:this,
                                     mode: 'remote',  editable:true,minChars: 1,autoSelect :true,typeAhead: false,
                                     forceSelection: true,triggerAction: 'all',resizable:false,selectOnFocus:true,
                                     tpl:new Ext.XTemplate(
                                         '<tpl for="."><div class="search-item">',
                                             '<h3>{Blog_Name}</h3>',
                                         '</div></tpl>'
                                     ),
                                     listeners:{
                                         'beforequery': function(event){delete event.combo.lastQuery;}
                                     },
                                     onSelect:function(record,index){
                                         if(this.fireEvent('beforeselect', this, record, index) !== false){
                                            this.grid.Blog_ID.setValue(record.data.Blog_ID);
                                            this.grid.Blog_Name.setValue(record.data.Blog_Name);
                                            this.collapse();
                                         }
                                     }
                                }
							]
						})
					],
					buttons : [{
						text: "",ref : "../saveBtn",scope:this,
						handler : function() {
                            switch (Bb.Blog.Config.OnlineEditor)
                            {
                                case 1:
                                    if (CKEDITOR.instances.Comment) this.editForm.Comment.setValue(CKEDITOR.instances.Comment.getData());
                                    break
                                case 2:
                                    if (Bb.Blog.View.CommentView.EditWindow.KindEditor_Comment)this.editForm.Comment.setValue(Bb.Blog.View.CommentView.EditWindow.KindEditor_Comment.html());
                                    break
                                case 3:
                                    if (xhEditor_Comment)this.editForm.Comment.setValue(xhEditor_Comment.getSource());
                                    break
                                default:
                                    if (ue_Comment)this.editForm.Comment.setValue(ue_Comment.getContent());
                            }

							if (!this.editForm.getForm().isValid()) {
								return;
							}
							editWindow=this;
							if (this.savetype==0){
								this.editForm.api.submit=ExtServiceComment.save;
								this.editForm.getForm().submit({
									success : function(form, action) {
										Ext.Msg.alert("提示", "保存成功！");
										Bb.Blog.View.Running.commentGrid.doSelectComment();
										form.reset();
										editWindow.hide();
									},
									failure : function(form, response) {
										Ext.Msg.show({title:'提示',width:350,buttons: {yes: '确定'},msg:response.result.msg});
									}
								});
							}else{
								this.editForm.api.submit=ExtServiceComment.update;
								this.editForm.getForm().submit({
									success : function(form, action) {
										Ext.Msg.show({title:'提示',msg: '修改成功！',buttons: {yes: '确定'},fn: function(){
											Bb.Blog.View.Running.commentGrid.bottomToolbar.doRefresh();
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
							this.editForm.form.loadRecord(Bb.Blog.View.Running.commentGrid.getSelectionModel().getSelected());
                            switch (Bb.Blog.Config.OnlineEditor)
                            {
                                case 1:
                                    if (CKEDITOR.instances.Comment) CKEDITOR.instances.Comment.setData(Bb.Blog.View.Running.commentGrid.getSelectionModel().getSelected().data.Comment);
                                    break
                                case 2:
                                    if (Bb.Blog.View.CommentView.EditWindow.KindEditor_Comment) Bb.Blog.View.CommentView.EditWindow.KindEditor_Comment.html(Bb.Blog.View.Running.commentGrid.getSelectionModel().getSelected().data.Comment);
                                    break
                                case 3:
                                    if (xhEditor_Comment) xhEditor_Comment.setSource(Bb.Blog.View.Running.commentGrid.getSelectionModel().getSelected().data.Comment);
                                    break
                                default:
                                    if (ue_Comment) ue_Comment.setContent(Bb.Blog.View.Running.commentGrid.getSelectionModel().getSelected().data.Comment);
                            }

						}
					}]
				}, config);
				Bb.Blog.View.CommentView.EditWindow.superclass.constructor.call(this, config);
			}
		}),
		/**
		 * 查询条件
		 */
		filter:null,
		/**
		 * 视图：评论列表
		 */
		Grid:Ext.extend(Ext.grid.GridPanel, {
			constructor : function(config) {
				config = Ext.apply({
					store : Bb.Blog.Store.commentStore,sm : this.sm,
					frame : true,trackMouseOver : true,enableColumnMove : true,columnLines : true,
					loadMask : true,stripeRows : true,headerAsText : false,
					defaults : {autoScroll : true},
					cm : new Ext.grid.ColumnModel({
						defaults:{
							width:120,sortable : true
						},
						columns : [
                            this.sm,
                            {header : '标识',dataIndex : 'ID',hidden:true},
                            {header : '评论者',dataIndex : 'Username'},
                            {header : '评论',dataIndex : 'Comment'},
							{header : '评论时间',dataIndex : 'CommitTime',renderer:Ext.util.Format.dateRenderer('Y-m-d H:i')}
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
										text : '添加评论',iconCls : 'icon-add',
										handler : function() {
											this.addComment();
										}
									},'-',{
										text : '修改评论',ref: '../../btnUpdate',iconCls : 'icon-edit',disabled : true,
										handler : function() {
											this.updateComment();
										}
									},'-',{
										text : '删除评论', ref: '../../btnRemove',iconCls : 'icon-delete',disabled : true,
										handler : function() {
											this.deleteComment();
										}
									},'-']}
						)]
					},
					bbar: new Ext.PagingToolbar({
						pageSize: Bb.Blog.Config.PageSize,
						store: Bb.Blog.Store.commentStore,scope:this,autoShow:true,displayInfo: true,
						displayMsg: '当前显示 {0} - {1}条记录/共 {2}条记录。',emptyMsg: "无显示数据",
						items: [
							{xtype:'label', text: '每页显示'},
							{xtype:'numberfield', value:Bb.Blog.Config.PageSize,minValue:1,width:35,style:'text-align:center',allowBlank: false,
								listeners:
								{
									change:function(Field, newValue, oldValue){
										var num = parseInt(newValue);
										if (isNaN(num) || !num || num<1)
										{
											num = Bb.Blog.Config.PageSize;
											Field.setValue(num);
										}
										this.ownerCt.pageSize= num;
										Bb.Blog.Config.PageSize = num;
										this.ownerCt.ownerCt.doSelectComment();
									},
									specialKey :function(field,e){
										if (e.getKey() == Ext.EventObject.ENTER){
											var num = parseInt(field.getValue());
											if (isNaN(num) || !num || num<1)num = Bb.Blog.Config.PageSize;
											this.ownerCt.pageSize= num;
											Bb.Blog.Config.PageSize = num;
											this.ownerCt.ownerCt.doSelectComment();
										}
									}
								}
							},{xtype:'label', text: '个'}
						]
					})
				}, config);
				/**
				 * 评论数据模型获取数据Direct调用
				 */
				Bb.Blog.Store.commentStore.proxy=new Ext.data.DirectProxy({
					api: {read:ExtServiceComment.queryPageComment}
				});
				Bb.Blog.View.CommentView.Grid.superclass.constructor.call(this, config);
			},
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
			/**
			 * 查询符合条件的评论
			 */
			doSelectComment : function() {
				if (Bb.Blog.View.Running.blogGrid&&Bb.Blog.View.Running.blogGrid.getSelectionModel().getSelected()){
					var Blog_ID = Bb.Blog.View.Running.blogGrid.getSelectionModel().getSelected().data.ID;
					var condition = {'Blog_ID':Blog_ID,'start':0,'limit':Bb.Blog.Config.PageSize};
					this.filter   = {'Blog_ID':Blog_ID};
					ExtServiceComment.queryPageComment(condition,function(provider, response) {
						if (response.result){
							if (response.result.data) {
								var result           = new Array();
								result['data']       =response.result.data;
								result['totalCount'] =response.result.totalCount;
								Bb.Blog.Store.commentStore.loadData(result);
							} else {
								Bb.Blog.Store.commentStore.removeAll();
								Ext.Msg.alert('提示', '无符合条件的评论！');
							}

							if (Bb.Blog.Store.commentStore.getTotalCount()>Bb.Blog.Config.PageSize){
								 Bb.Blog.View.Running.commentGrid.bottomToolbar.show();
							}else{
								 Bb.Blog.View.Running.commentGrid.bottomToolbar.hide();
							}
							Bb.Blog.View.Running.blogGrid.ownerCt.doLayout();
						}
					});
				}
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
			 * 新建评论
			 */
			addComment : function(){
				if (Bb.Blog.View.CommentView.edit_window==null){
					Bb.Blog.View.CommentView.edit_window=new Bb.Blog.View.CommentView.EditWindow();
				}
				Bb.Blog.View.CommentView.edit_window.resetBtn.setVisible(false);
				Bb.Blog.View.CommentView.edit_window.saveBtn.setText('保 存');
				Bb.Blog.View.CommentView.edit_window.setTitle('添加评论');
				Bb.Blog.View.CommentView.edit_window.savetype=0;
				Bb.Blog.View.CommentView.edit_window.ID.setValue("");
				var blog_id = Bb.Blog.View.Running.blogGrid.getSelectionModel().getSelected().data.ID;
				Bb.Blog.View.CommentView.edit_window.Blog_ID.setValue(blog_id);
                switch (Bb.Blog.Config.OnlineEditor)
                {
                    case 1:
                        if (CKEDITOR.instances.Comment) CKEDITOR.instances.Comment.setData("");
                        break
                    case 2:
                        if (Bb.Blog.View.CommentView.EditWindow.KindEditor_Comment) Bb.Blog.View.CommentView.EditWindow.KindEditor_Comment.html("");
                        break
                    case 3:
                        break
                    default:
                        if (ue_Comment)ue_Comment.setContent("");
                }

				Bb.Blog.View.CommentView.edit_window.show();
				Bb.Blog.View.CommentView.edit_window.maximize();
			},
			/**
			 * 编辑评论时先获得选中的评论信息
			 */
			updateComment : function() {
				if (Bb.Blog.View.CommentView.edit_window==null){
					Bb.Blog.View.CommentView.edit_window=new Bb.Blog.View.CommentView.EditWindow();
				}
				Bb.Blog.View.CommentView.edit_window.saveBtn.setText('修 改');
				Bb.Blog.View.CommentView.edit_window.resetBtn.setVisible(true);
				Bb.Blog.View.CommentView.edit_window.setTitle('修改评论');
				Bb.Blog.View.CommentView.edit_window.editForm.form.loadRecord(this.getSelectionModel().getSelected());
				Bb.Blog.View.CommentView.edit_window.savetype=1;
                switch (Bb.Blog.Config.OnlineEditor)
                {
                    case 1:
                        if (CKEDITOR.instances.Comment) CKEDITOR.instances.Comment.setData(this.getSelectionModel().getSelected().data.Comment);
                        break
                    case 2:
                        if (Bb.Blog.View.CommentView.EditWindow.KindEditor_Comment) Bb.Blog.View.CommentView.EditWindow.KindEditor_Comment.html(this.getSelectionModel().getSelected().data.Comment);
                        break
                    case 3:
                        if (xhEditor_Comment)xhEditor_Comment.setSource(this.getSelectionModel().getSelected().data.Comment);
                        break
                    default:
                        if (ue_Comment)ue_Comment.setContent(this.getSelectionModel().getSelected().data.Comment);
                }

				Bb.Blog.View.CommentView.edit_window.show();
				Bb.Blog.View.CommentView.edit_window.maximize();
			},
			/**
			 * 删除评论
			 */
			deleteComment : function() {
				Ext.Msg.confirm('提示', '确实要删除所选的评论吗?', this.confirmDeleteComment,this);
			},
			/**
			 * 确认删除评论
			 */
			confirmDeleteComment : function(btn) {
				if (btn == 'yes') {
					var del_IDs ="";
					var selectedRows    = this.getSelectionModel().getSelections();
					for ( var flag = 0; flag < selectedRows.length; flag++) {
						del_IDs=del_IDs+selectedRows[flag].data.ID+",";
					}
					ExtServiceComment.deleteByIds(del_IDs);
					this.doSelectComment();
					Ext.Msg.alert("提示", "删除成功！");
				}
			}
		})
	},
	/**
	 * 窗口：批量上传博客
	 */
	UploadWindow:Ext.extend(Ext.Window,{
		constructor : function(config) {
			config = Ext.apply({
				title : '批量上传博客数据',width : 400,height : 110,minWidth : 300,minHeight : 100,
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
									url : 'index.php?go=admin.upload.uploadBlog',
									success : function(form, response) {
										Ext.Msg.alert('成功', '上传成功');
										uploadWindow.hide();
										uploadWindow.uploadForm.upload_file.setValue('');
										Bb.Blog.View.Running.blogGrid.doSelectBlog();
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
			Bb.Blog.View.UploadWindow.superclass.constructor.call(this, config);
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
				store : Bb.Blog.Store.blogStore,
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
                        {header : '标识',dataIndex : 'ID',hidden:true},
                        {header : '用户',dataIndex : 'Username'},
                        {header : '博客标题',dataIndex : 'Blog_Name'},
						{header : '创建时间',dataIndex : 'CommitTime',renderer:Ext.util.Format.dateRenderer('Y-m-d H:i')},
						{header : '更新时间',dataIndex : 'UpdateTime',renderer:Ext.util.Format.dateRenderer('Y-m-d H:i')}
					]
				}),
				tbar : {
					xtype : 'container',layout : 'anchor',height : 27 * 2,style:'font-size:14px',
					defaults : {
						height : 27,anchor : '100%'
					},
					items : [
						new Ext.Toolbar({
							enableOverflow: true,width : 100,
							defaults : {
								xtype : 'textfield',
								listeners : {
								   specialkey : function(field, e) {
										if (e.getKey() == Ext.EventObject.ENTER)this.ownerCt.ownerCt.ownerCt.doSelectBlog();
									}
								}
							},
							items : [
                                '用户','&nbsp;&nbsp;',{ref: '../bUser_ID',xtype: 'combo',
                                     store:Bb.Blog.Store.userStoreForCombo,hiddenName : 'User_ID',
                                     emptyText: '请选择用户',itemSelector: 'div.search-item',
                                     loadingText: '查询中...',width:280,pageSize:Bb.Blog.Config.PageSize,
                                     displayField:'Username',valueField:'User_ID',
                                     mode: 'remote',editable:true,minChars: 1,autoSelect :true,typeAhead: false,
                                     forceSelection: true,triggerAction: 'all',resizable:true,selectOnFocus:true,
                                     tpl:new Ext.XTemplate(
                                         '<tpl for="."><div class="search-item">',
                                         '<h3>{Username}</h3>',
                                         '</div></tpl>'
                                     )
                                },'&nbsp;&nbsp;',
                                '博客标题','&nbsp;&nbsp;',{ref: '../bBlog_Name'},'&nbsp;&nbsp;',
								{
									xtype : 'button',text : '查询',scope: this,
									handler : function() {
										this.doSelectBlog();
									}
								},
								{
									xtype : 'button',text : '重置',scope: this,
									handler : function() {
                                        this.topToolbar.bUser_ID.setValue("");
                                        this.topToolbar.bBlog_Name.setValue("");
										this.filter={};
										this.doSelectBlog();
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
									xtype:'tbsplit',text: '导入', iconCls : 'icon-import',
									handler : function() {
										this.importBlog();
									},
									menu: {
										xtype:'menu',plain:true,
										items: [
											{text:'批量导入博客',iconCls : 'icon-import',scope:this,handler:function(){this.importBlog()}}
										]}
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
											{text:'隐藏',group:'mlayout',checked:false,iconCls:'view-hide',scope:this,handler:function(){this.hideBlog();Bb.Blog.Config.View.IsShow=0;}},'-',
											{text: '固定',ref:'mBind',checked: true,scope:this,checkHandler:function(item, checked){this.onBindGrid(item, checked);Bb.Blog.Cookie.set('View.IsFix',Bb.Blog.Config.View.IsFix);}}
										]}
								},'-']}
					)]
				},
				bbar: new Ext.PagingToolbar({
					pageSize: Bb.Blog.Config.PageSize,
					store: Bb.Blog.Store.blogStore,
					scope:this,autoShow:true,displayInfo: true,
					displayMsg: '当前显示 {0} - {1}条记录/共 {2}条记录。',
					emptyMsg: "无显示数据",
					listeners:{
						change:function(thisbar,pagedata){
							if (Bb.Blog.Viewport){
								if (Bb.Blog.Config.View.IsShow==1){
									Bb.Blog.View.IsSelectView=1;
								}
								this.ownerCt.hideBlog();
								Bb.Blog.Config.View.IsShow=0;
							}
						}
					},
					items: [
						{xtype:'label', text: '每页显示'},
						{xtype:'numberfield', value:Bb.Blog.Config.PageSize,minValue:1,width:35,
							style:'text-align:center',allowBlank: false,
							listeners:
							{
								change:function(Field, newValue, oldValue){
									var num = parseInt(newValue);
									if (isNaN(num) || !num || num<1)
									{
										num = Bb.Blog.Config.PageSize;
										Field.setValue(num);
									}
									this.ownerCt.pageSize= num;
									Bb.Blog.Config.PageSize = num;
									this.ownerCt.ownerCt.doSelectBlog();
								},
								specialKey :function(field,e){
									if (e.getKey() == Ext.EventObject.ENTER){
										var num = parseInt(field.getValue());
										if (isNaN(num) || !num || num<1)
										{
											num = Bb.Blog.Config.PageSize;
										}
										this.ownerCt.pageSize= num;
										Bb.Blog.Config.PageSize = num;
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
			Bb.Blog.View.Grid.superclass.constructor.call(this, config);
			//创建在Grid里显示的博客信息Tab页
			Bb.Blog.View.Running.viewTabs=new Bb.Blog.View.BlogView.Tabs();
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
						this.grid.hideBlog();
						Bb.Blog.Config.View.IsShow=0;
					}else{
						if (Bb.Blog.View.IsSelectView==1){
							Bb.Blog.View.IsSelectView=0;
							this.grid.showBlog();
						}
					}
				},
				rowdeselect: function(sm, rowIndex, record) {
					if (sm.getCount() != 1){
						if (Bb.Blog.Config.View.IsShow==1){
							Bb.Blog.View.IsSelectView=1;
						}
						this.grid.hideBlog();
						Bb.Blog.Config.View.IsShow=0;
					}
				}
			}
		}),
		/**
		 * 双击选行
		 */
		onRowDoubleClick:function(grid, rowIndex, e){
			if (!Bb.Blog.Config.View.IsShow){
				this.sm.selectRow(rowIndex);
				this.showBlog();
				this.tvpView.toggle(true);
			}else{
				this.hideBlog();
				Bb.Blog.Config.View.IsShow=0;
				this.sm.deselectRow(rowIndex);
				this.tvpView.toggle(false);
			}
		},
		/**
		 * 是否绑定在本窗口上
		 */
		onBindGrid:function(item, checked){
			if (checked){
			   Bb.Blog.Config.View.IsFix=1;
			}else{
			   Bb.Blog.Config.View.IsFix=0;
			}
			if (this.getSelectionModel().getSelected()==null){
				Bb.Blog.Config.View.IsShow=0;
				return ;
			}
			if (Bb.Blog.Config.View.IsShow==1){
			   this.hideBlog();
			   Bb.Blog.Config.View.IsShow=0;
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
                var bUser_ID = this.topToolbar.bUser_ID.getValue();
                var bBlog_Name = this.topToolbar.bBlog_Name.getValue();
                this.filter       ={'User_ID':bUser_ID,'Blog_Name':bBlog_Name};
			}
			var condition = {'start':0,'limit':Bb.Blog.Config.PageSize};
			Ext.apply(condition,this.filter);
			ExtServiceBlog.queryPageBlog(condition,function(provider, response) {
				if (response.result&&response.result.data) {
					var result           = new Array();
					result['data']       =response.result.data;
					result['totalCount'] =response.result.totalCount;
					Bb.Blog.Store.blogStore.loadData(result);
				} else {
					Bb.Blog.Store.blogStore.removeAll();
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
			Bb.Blog.Config.View.Direction=viewDirection;
			switch(viewDirection){
				case 1:
					this.ownerCt.north.add(Bb.Blog.View.Running.viewTabs);
					break;
				case 2:
					this.ownerCt.south.add(Bb.Blog.View.Running.viewTabs);
					break;
				case 3:
					this.ownerCt.west.add(Bb.Blog.View.Running.viewTabs);
					break;
				case 4:
					this.ownerCt.east.add(Bb.Blog.View.Running.viewTabs);
					break;
			}
			Bb.Blog.Cookie.set('View.Direction',Bb.Blog.Config.View.Direction);
			if (this.getSelectionModel().getSelected()!=null){
				if ((Bb.Blog.Config.View.IsFix==0)&&(Bb.Blog.Config.View.IsShow==1)){
					this.showBlog();
				}
				Bb.Blog.Config.View.IsFix=1;
				Bb.Blog.View.Running.blogGrid.tvpView.menu.mBind.setChecked(true,true);
				Bb.Blog.Config.View.IsShow=0;
				this.showBlog();
			}
		},
		/**
		 * 显示博客
		 */
		showBlog : function(){
			if (this.getSelectionModel().getSelected()==null){
				Ext.Msg.alert('提示', '请先选择博客！');
				Bb.Blog.Config.View.IsShow=0;
				this.tvpView.toggle(false);
				return ;
			}
			if (Bb.Blog.Config.View.IsFix==0){
				if (Bb.Blog.View.Running.view_window==null){
					Bb.Blog.View.Running.view_window=new Bb.Blog.View.BlogView.Window();
				}
				if (Bb.Blog.View.Running.view_window.hidden){
					Bb.Blog.View.Running.view_window.show();
					Bb.Blog.View.Running.view_window.winTabs.hideTabStripItem(Bb.Blog.View.Running.view_window.winTabs.tabFix);
					this.updateViewBlog();
					this.tvpView.toggle(true);
					Bb.Blog.Config.View.IsShow=1;
				}else{
					this.hideBlog();
					Bb.Blog.Config.View.IsShow=0;
				}
				return;
			}
			switch(Bb.Blog.Config.View.Direction){
				case 1:
					if (!this.ownerCt.north.items.contains(Bb.Blog.View.Running.viewTabs)){
						this.ownerCt.north.add(Bb.Blog.View.Running.viewTabs);
					}
					break;
				case 2:
					if (!this.ownerCt.south.items.contains(Bb.Blog.View.Running.viewTabs)){
						this.ownerCt.south.add(Bb.Blog.View.Running.viewTabs);
					}
					break;
				case 3:
					if (!this.ownerCt.west.items.contains(Bb.Blog.View.Running.viewTabs)){
						this.ownerCt.west.add(Bb.Blog.View.Running.viewTabs);
					}
					break;
				case 4:
					if (!this.ownerCt.east.items.contains(Bb.Blog.View.Running.viewTabs)){
						this.ownerCt.east.add(Bb.Blog.View.Running.viewTabs);
					}
					break;
			}
			this.hideBlog();
			if (Bb.Blog.Config.View.IsShow==0){
				Bb.Blog.View.Running.viewTabs.enableCollapse();
				switch(Bb.Blog.Config.View.Direction){
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
				Bb.Blog.Config.View.IsShow=1;
			}else{
				Bb.Blog.Config.View.IsShow=0;
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
			if (Bb.Blog.View.Running.view_window!=null){
				Bb.Blog.View.Running.view_window.hide();
			}
			this.tvpView.toggle(false);
			this.ownerCt.doLayout();
		},
		/**
		 * 更新当前博客显示信息
		 */
		updateViewBlog : function() {
            Bb.Blog.View.Running.commentGrid.doSelectComment();
			if (Bb.Blog.View.Running.view_window!=null){
				Bb.Blog.View.Running.view_window.winTabs.tabBlogDetail.update(this.getSelectionModel().getSelected().data);
			}
			Bb.Blog.View.Running.viewTabs.tabBlogDetail.update(this.getSelectionModel().getSelected().data);
		},
		/**
		 * 新建博客
		 */
		addBlog : function() {
			if (Bb.Blog.View.Running.edit_window==null){
				Bb.Blog.View.Running.edit_window=new Bb.Blog.View.EditWindow();
			}
			Bb.Blog.View.Running.edit_window.resetBtn.setVisible(false);
			Bb.Blog.View.Running.edit_window.saveBtn.setText('保 存');
			Bb.Blog.View.Running.edit_window.setTitle('添加博客');
			Bb.Blog.View.Running.edit_window.savetype=0;
			Bb.Blog.View.Running.edit_window.ID.setValue("");
            switch (Bb.Blog.Config.OnlineEditor)
            {
                case 1:
                    if (CKEDITOR.instances.Blog_Content) CKEDITOR.instances.Blog_Content.setData("");
                    break
                case 2:
                    if (Bb.Blog.View.EditWindow.KindEditor_Blog_Content) Bb.Blog.View.EditWindow.KindEditor_Blog_Content.html("");
                    break
                case 3:
                    break
                default:
                    if (ue_Blog_Content)ue_Blog_Content.setContent("");
            }

			Bb.Blog.View.Running.edit_window.show();
			Bb.Blog.View.Running.edit_window.maximize();
		},
		/**
		 * 编辑博客时先获得选中的博客信息
		 */
		updateBlog : function() {
			if (Bb.Blog.View.Running.edit_window==null){
				Bb.Blog.View.Running.edit_window=new Bb.Blog.View.EditWindow();
			}
			Bb.Blog.View.Running.edit_window.saveBtn.setText('修 改');
			Bb.Blog.View.Running.edit_window.resetBtn.setVisible(true);
			Bb.Blog.View.Running.edit_window.setTitle('修改博客');
			Bb.Blog.View.Running.edit_window.editForm.form.loadRecord(this.getSelectionModel().getSelected());
			Bb.Blog.View.Running.edit_window.savetype=1;
            switch (Bb.Blog.Config.OnlineEditor)
            {
                case 1:
                    if (CKEDITOR.instances.Blog_Content) CKEDITOR.instances.Blog_Content.setData(this.getSelectionModel().getSelected().data.Blog_Content);
                    break
                case 2:
                    if (Bb.Blog.View.EditWindow.KindEditor_Blog_Content) Bb.Blog.View.EditWindow.KindEditor_Blog_Content.html(this.getSelectionModel().getSelected().data.Blog_Content);
                    break
                case 3:
                    if (xhEditor_Blog_Content)xhEditor_Blog_Content.setSource(this.getSelectionModel().getSelected().data.Blog_Content);
                    break
                default:
                    if (ue_Blog_Content)ue_Blog_Content.setContent(this.getSelectionModel().getSelected().data.Blog_Content);
            }

			Bb.Blog.View.Running.edit_window.show();
			Bb.Blog.View.Running.edit_window.maximize();
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
					del_blog_ids=del_blog_ids+selectedRows[flag].data.ID+",";
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
			if (Bb.Blog.View.current_uploadWindow==null){
				Bb.Blog.View.current_uploadWindow=new Bb.Blog.View.UploadWindow();
			}
			Bb.Blog.View.current_uploadWindow.show();
		}
	}),
	/**
	 * 核心内容区
	 */
	Panel:Ext.extend(Ext.form.FormPanel,{
		constructor : function(config) {
			Bb.Blog.View.Running.blogGrid=new Bb.Blog.View.Grid();
			if (Bb.Blog.Config.View.IsFix==0){
				Bb.Blog.View.Running.blogGrid.tvpView.menu.mBind.setChecked(false,true);
			}
			config = Ext.apply({
				region : 'center',layout : 'fit', frame:true,
				items: {
					layout:'border',
					items:[
						Bb.Blog.View.Running.blogGrid,
						{region:'north',ref:'north',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true},
						{region:'south',ref:'south',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true,items:[Bb.Blog.View.Running.viewTabs]},
						{region:'west',ref:'west',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true},
						{region:'east',ref:'east',layout:'fit',collapseMode : 'mini',border:false,split: true,hidden:true}
					]
				}
			}, config);
			Bb.Blog.View.Panel.superclass.constructor.call(this, config);
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
         * 当前评论Grid对象
         */
        commentGrid:null,
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
	Ext.state.Manager.setProvider(Bb.Blog.Cookie);
	Ext.Direct.addProvider(Ext.app.REMOTING_API);
	Bb.Blog.Init();
	/**
	 * 博客数据模型获取数据Direct调用
	 */
	Bb.Blog.Store.blogStore.proxy=new Ext.data.DirectProxy({
		api: {read:ExtServiceBlog.queryPageBlog}
	});
	/**
	 * 博客页面布局
	 */
	Bb.Blog.Viewport = new Ext.Viewport({
		layout : 'border',
		items : [new Bb.Blog.View.Panel()]
	});
	Bb.Blog.Viewport.doLayout();
	setTimeout(function(){
		Ext.get('loading').remove();
		Ext.get('loading-mask').fadeOut({
			remove:true
		});
	}, 250);
});