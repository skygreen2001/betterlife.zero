<?php

$appName_alias=Gc::$appName_alias;
$appName_alias=ucfirst($appName_alias);
$appName=Gc::$appName;
$appName=ucfirst($appName);

$loginout =<<<LOGININOUT
    /**
     * 控制器:登录
     */
    public function login()
    {
        if(HttpSession::isHave(Gc::\$appName_alias.'admin_id')) {
            \$this->redirect("index","index");
        }
        \$this->loadCss("resources/css/login.css");
        UtilJavascript::loadJsReady(\$this->view->viewObject,Gc::\$url_base."common/js/ajax/jquery/jquery-1.7.1.js");
        \$this->loadJs("js/login.js");
        if (!empty(\$_POST)) {
            if (HttpSession::get("validate")!= md5(\$this->data["validate"])){
                \$this->view->set("message","图形验证码输入错误");
                return;
            }
            \$admin = \$this->model->Admin;
            \$admindata = Admin::get_one(\$admin);
            if (empty(\$admindata)) {
                \$this->view->set("message","用户名或者密码错误");
            }else {
                HttpSession::set('admin_id',\$admindata->admin_id);
                HttpSession::set(Gc::\$appName_alias.'admin_id',\$admindata->admin_id);
                HttpCookie::sets(array("admin_id"=>\$admindata->admin_id,"operator"=>\$admindata->username,'roletype'=>\$admindata->roletype,'roleid'=>\$admindata->roleid));
                \$this->redirect("index","index");
            }
        }
    }

    /**
     * 控制器:登出
     */
    public function logout()
    {
        HttpSession::remove("admin_id");
        HttpSession::remove(Gc::\$appName_alias."admin_id");
        \$this->redirect("index","login");
    }

LOGININOUT;

$jsIndexContent=<<<INDEXJS
Ext.namespace("$appName.Admin");
$appName_alias = $appName.Admin;
/**
 * 全局配置
 */
$appName_alias.Config={
    /**
     * 访问历史记录的组件标识分割符
     */
    TokenDelimiter:':',
    /**
     * 操作者
     */
    Operator:'',
    /**
     * 在线编辑器类型。
     * 1:CkEditor,2:KindEditor,3:xhEditor
     * 配合Action的变量配置\$online_editor
     */
    OnlineEditor:1,
    /**
     * 提供第三方公用使用的窗口，一个应用只有一个这样的窗口
     */
    ViewOnlyWindow:null,
    ViewOnlyWindows:{},
    /**
     * 初始化
     */
    Init:function(){
        if (Ext.util.Cookies.get('OnlineEditor')!=null){
            $appName_alias.Config.OnlineEditor=Ext.util.Cookies.get('OnlineEditor');
        }
        if (Ext.util.Cookies.get('operator')!=null){
            $appName_alias.Config.Operator=Ext.util.Cookies.get('operator');
        }
        if (Ext.util.Cookies.get('admin_id')!=null){
            $appName_alias.Config.Admin_id=Ext.util.Cookies.get('admin_id');
        }
    }
};

Ext.onReady(function(){
    Ext.QuickTips.init();
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
    $appName_alias.Viewport = new Ext.Viewport({
        layout: 'border',
        items: [
          $appName_alias.Layout.HeaderPanel,$appName_alias.Layout.LeftPanel,$appName_alias.Layout.CenterPanel
        ]
    });
    $appName_alias.Config.Init();
    $appName_alias.Layout.Init();
    $appName_alias.Viewport.doLayout();
    setTimeout(function(){
        Ext.get('loading').remove();
        Ext.get('loading-mask').fadeOut({remove:true});
    }, 250);

    //让浏览器的后退前进跟从Tab访问的历史记录
    Ext.History.init();

    // Handle this change event in order to restore the UI to the appropriate history state
    Ext.History.on('change', function(token){
        if(token){
            var parts = token.split($appName_alias.Config.TokenDelimiter);
            var tabPanel = Ext.getCmp(parts[0]);
            var tabId = parts[1];
            tabPanel.show();
            tabPanel.setActiveTab(tabId);
        }
    });
});
INDEXJS;

$jsLayoutContent=<<<LAYOUTJS
Ext.namespace("$appName.Admin");
$appName_alias = $appName.Admin;
/**
 * 页面布局格局
 */
$appName_alias.Layout = {
    /**
     * 布局：页面头部
     */
    HeaderPanel : [{
        region:'north',ref:'head',header:false,collapsible:true,collapseMode : 'mini',split : true,height:27,//contentEl:'header',
        tbar:{
            xtype : 'container',layout : 'anchor',
            height : 27,style:'font-size:14px',
            items : [
                new Ext.Toolbar({
                    height : 27,ref:'menus',
                    items:[
                        {text: '', ref:'../../file',iconCls : 'logo',
                            menu: {
                                xtype:'menu',items: [
                                  /*{text:'新建',handler:function(){}},
                                    {text:'导入',handler:function(){}},
                                    {text:'导出',handler:function(){}},*/
                                    {text:'关闭所有',handler:function(){
                                        $appName_alias.Viewport.center.tabCloseMenu.onCloseAll();
                                    }}, '-',
                                    {text: '退出',iconCls : 'icon-quit',ref:'exit',handler:function(){
                                        window.location.href="index.php?go=admin.index.logout";
                                    }}
                                ]
                            }
                        },{text: '显示', ref:'../../view',iconCls : 'page',handler:function(){
                                var headerPanel=this.ownerCt.ownerCt.ownerCt;
                                if(window.outerHeight==screen.height && window.outerWidth==screen.width){
                                    headerPanel.view.menu.full.setChecked(true);
                                }else{
                                    headerPanel.view.menu.full.setChecked(false);
                                }
                            },menu: {
                                xtype:'menu',items: [
                                    '-',
                                    {text:'工具栏',checked:true,ref:'toolbar',checkHandler:function(){
                                        if ($appName_alias.Viewport.head.view.menu.toolbar.checked){
                                            $appName_alias.Viewport.head.topToolbar.toolbar.show();
                                            $appName_alias.Viewport.head.topToolbar.setHeight(27*3);
                                            $appName_alias.Viewport.head.setHeight(27*3);
                                        }else{
                                            $appName_alias.Viewport.head.topToolbar.toolbar.hide();
                                            $appName_alias.Viewport.head.topToolbar.setHeight(27);
                                            $appName_alias.Viewport.head.setHeight(27);
                                        }
                                        $appName_alias.Viewport.head.syncHeight();
                                        $appName_alias.Viewport.doLayout();
                                    }},
                                    {text:'导航栏',checked:true,ref:'nav',checkHandler:function(){
                                        if ($appName_alias.Viewport.head.view.menu.nav.checked){
                                            $appName_alias.Viewport.west.expand();
                                        }else{
                                            $appName_alias.Viewport.west.collapse();
                                        }
                                    }},
                                    {text:'在线编辑器',ref:'onlineditor',menu:{
                                        items: [ {
                                            text: '默认【ckEditor】',
                                            checked: true,value:"1",
                                            group: 'onlineditor',
                                            checkHandler: function(item, checked){{$appName_alias}.Layout.Function.onOnlineditorCheck(item, checked);}
                                        },{
                                            text: 'KindEditor',
                                            checked: false,value:"2",
                                            group: 'onlineditor',
                                            checkHandler: function(item, checked){{$appName_alias}.Layout.Function.onOnlineditorCheck(item, checked);}
                                        }, {
                                            text: 'xHeditor',
                                            checked: false,value:"3",
                                            group: 'onlineditor',
                                            checkHandler: function(item, checked){{$appName_alias}.Layout.Function.onOnlineditorCheck(item, checked);}
                                        }]
                                    }},
                                    '-',
                                    {text: '全屏  [F11]',checked:false,ref:'full',checkHandler:function(){
                                        {$appName_alias}.Layout.Function.FullScreen();
                                    }}
                                ]
                            }
                        },'-',{text: '退出', iconCls : 'icon-quit',ref:'../../exit',handler:function(){
                            window.location.href="index.php?go=admin.index.logout";
                        }},new Ext.Toolbar.Fill(),'-',{text: "",ref:'../../operator',handler:function(){
                            {$appName_alias}.Layout.Function.OpenWindow("index.php?go=admin.view.admin&admin_id="+$appName_alias.Config.Admin_id);
                        }}]
                })
          ]}
    }],
    /**
     * 布局:页面左部
     */
    LeftPanel : [{
        region : 'west',ref:'west',
        title : '功能区',collapseMode : 'mini',collapsible : true,
        split : true,width : 200,minSize : 100,
        maxSize : 400,margins : '0 0 3 3',
        layout : {
            type : 'accordion',animate : true,
            activeOnTop: true
        }
    }],
    /**
     * 布局:页面内容区
     */
    CenterPanel : new Ext.TabPanel({
        region : 'center',ref:'center',
        deferredRender : false,enableTabScroll : true,margins : '0 3 3 0',
        activeTab : 0,resizeTabs : true,minTabWidth : 115,tabWidth : 135,
        defaults : {autoScroll : true},
        plugins : [
            // Ext.ux.AddTaBbutton,
            new Ext.ux.TabCloseMenu(),// Tab标题头右键菜单选项生成
            // Tab标题头右侧下拉选择指定页面
            new Ext.ux.TabScrollerMenu({maxText : 15,pageSize : 5})
        ],
        listeners:{
            render: function() {
                Ext.getBody().on("contextmenu", Ext.emptyFn, null, {preventDefault: true});
            },
            tabchange: function(tabPanel, tab){
                if (tab){
                    //tabs切换时修改浏览器hash
                    Ext.History.add(tabPanel.id + $appName_alias.Config.TokenDelimiter + tab.id);
                }
            }
        },
        items : [{
                contentEl : 'centerArea',
                title : '首页',
                bodyStyle : 'padding:150px 0 0 0',
                url   : 'index.php?go=admin.index.index',
                iconCls : 'tabs',
                autoScroll : true
            }]
    }),
    /**
     * Layout初始化
     */
    Init : function() {
        $appName_alias.Viewport.west.add($appName_alias.Layout.LeftMenuGroups);
        $appName_alias.Viewport.west.doLayout();
        if ($appName_alias.Viewport.layout.north){
            //顶部导航区不可拖动，否则顶部下方会有空白
            $appName_alias.Viewport.layout.north.split.el.dom.style.cursor="inherit";
            $appName_alias.Viewport.layout.north.split.dd.lock();
        }
        if (Ext.get('hideit')) {
            Ext.get('hideit').on('click', function() {
                if ($appName_alias.Viewport.west.collapsed) {
                    Ext.get('hideit').update('隐藏左侧');
                    $appName_alias.Viewport.west.expand();
                } else {
                    Ext.get('hideit').update('显示左侧');
                    $appName_alias.Viewport.west.collapse();
                }
            });
        }
        var navEs = $appName_alias.Viewport.west.el.select('a');
        navEs.on('click', $appName_alias.Navigation.HyperlinkClicked);
        navEs.on('contextmenu',$appName_alias.Navigation.OnContextMenu);
        if ($appName_alias.Viewport.head){
            if ($appName_alias.Viewport.head.operator)$appName_alias.Viewport.head.operator.setText($appName_alias.Config.Operator);
            //设置当前在线编辑器的菜单选项
            if ($appName_alias.Viewport.head.view){
                var onlineditorItems=$appName_alias.Viewport.head.view.menu.onlineditor.menu.items.items;
                Ext.each(onlineditorItems, function(item) {
                  //console.log(item.value);
                  if (item.value==$appName_alias.Config.OnlineEditor){
                      item.checked=true;
                  }else{
                      item.checked=false;
                  }
                });
            }
        }
    },
    Function:{
        onOnlineditorCheck:function(item, checked){
            if (checked){
                Ext.util.Cookies.set('OnlineEditor',item.value);
            }
        },
        /**
         * 在主界面打开窗口
         * 参数有两个，默认为可重复打开窗口，当第二个参数为true，始终只打开一个窗口
         */
        OpenWindow:function(url){
            var IsOnlyOneWindow=arguments[1]?arguments[1]:true;
            if (IsOnlyOneWindow){
                url=url+"&ow=true";
            }
            Ext.get("frmview").dom.src=url;
        },
        /**
         * 全屏模式支持:
         * 支持HTML5,Firefor和Chrome高级版本支持
         * http://css.dzone.com/articles/pragmatic-introduction-html5
         * https://developer.mozilla.org/en/DOM/Using_full-screen_mode*/
        FullScreen:function(){
            var isIEPrompt=true;
            if (arguments[0]==false)isIEPrompt=false;
            if ($appName_alias.Viewport.head.view.menu.full.checked){
                var docElm = document.documentElement;
                if (docElm.requestFullscreen) {
                   docElm.requestFullscreen();
                }
                else if (docElm.mozRequestFullScreen) {
                   docElm.mozRequestFullScreen();
                }
                else if (docElm.webkitRequestFullScreen) {
                   docElm.webkitRequestFullScreen();
                }
                else if (typeof window.ActiveXObject !== "undefined"){
                    try
                    {
                        var wscript = new ActiveXObject("WScript.Shell");
                        if (wscript !== null) {
                            wscript.SendKeys("{F11}");
                        }
                    }
                    catch(err)
                    {
                       if(!((window.outerHeight==screen.height && window.outerWidth==screen.width))){
                           if (isIEPrompt){
                                Ext.Msg.alert('提示', 'IE浏览器请使用快捷键:F11');
                           }
                           $appName_alias.Viewport.head.view.menu.full.setChecked(false);
                           return;
                       }
                    }
                }
            }else{
                if (document.exitFullscreen) {
                   document.exitFullscreen();
                }
                else if (document.mozCancelFullScreen) {
                   document.mozCancelFullScreen();
                }
                else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                }else{
                    try
                    {
                        var wscript = new ActiveXObject("WScript.Shell");
                        if (wscript !== null) {
                            wscript.SendKeys("{F11}");
                        }
                    }
                    catch(err)
                    {

                        if((window.outerHeight==screen.height && window.outerWidth==screen.width)){
                            if (isIEPrompt){
                                Ext.Msg.alert('提示', 'IE浏览器请使用快捷键:F11');
                            }
                        }
                        return;
                    }
                }
            }
        }
    }
};
LAYOUTJS;

$jsNavigationContent=<<<NAVIGATIONJS
Ext.namespace("$appName.Admin");
$appName_alias = $appName.Admin;
/**
 * 导航功能
 */
$appName_alias.Navigation = {
    /**
     * 在指定的TabPanel组件上添加Tab 【如果已经添加了则激活该Tab】
     *
     * @param contentPanel
     *            TabPanel component组件
     * @param title
     *            Tab头标题
     * @param html
     *            Tab页面内的内容
     * @param id
     *            添加的Tab的标识
     */
    AddTab : function(contentPanel, title, html, id) {
        var cpId = 'cp-' + id;
        var tab = Ext.getCmp(cpId);
        if (tab) {
            contentPanel.setActiveTab(tab);
        } else {
            contentPanel.add({
                title : title,
                html : html,
                closable : true
            }).show();
        }
    },
    /**
     * 页面导航在Tab内嵌一个Ifame组件
     */
    IFrameComponent : Ext.extend(Ext.BoxComponent, {
        onRender : function(ct, position) {
            this.el = ct.createChild({tag : 'iframe',id : this.id,frameBorder : 0,src : this.url});
        }
    }),
    /**
     * 根据指定的Url在指定的TabPanel组件上添加Tab 【如果已经添加了则激活该Tab】
     *
     * @param contentPanel
     *            TabPanel component组件
     * @param title
     *            Tab头标题
     * @param url
     *            指定的Url
     * @param id
     *            添加的Tab的标识
     */
    AddTabbyUrl : function(contentPanel, title, url, id) {
        var cpId = 'cp-' + id;
        var tab = Ext.getCmp(cpId);
        if (tab) {
            contentPanel.setActiveTab(tab);
        } else {
            contentPanel.add({
                id : cpId,
                title : title,
                tabTip:title,
                url:url,
                iconCls : 'tabs',
                html : "<iframe id='frm"+id+"' name='frm"+id+"' scrolling='auto' width='100%' height='100%'  frameborder='0' src='"
                        + url + "'></iframe>",
                closable : true
            }).show();
        }
    },
    /**
     * 改写超链接默认事件，使新打开的页面都显示在指定的TabPanel组件上。
     */
    HyperlinkClicked : function(e) {
        e.preventDefault();
        var linkTarget = e.target;
        var title = "";
        if (Ext.isIE) {
            title = linkTarget.innerText;
        } else {
            title = linkTarget.text;
        }
        if (linkTarget.id=="logout"){
            window.location.href="index.php?go=admin.index.logout";
        }else{
            $appName_alias.Navigation.AddTabbyUrl($appName_alias.Viewport.center, title, linkTarget.href,linkTarget.id);
        }
    },
    OnContextMenu:function(e, item){
        if (item.href){
            e.preventDefault();
            var m = new Ext.menu.Menu({
                items: [{
                    itemId: 'openInNewIETab',
                    text: '在新标签页中打开',
                    scope: this,
                    handler: function(){
                        window.open(item.href,"_blank");
                    }
                }]
            });
            m.setWidth(150);
            e.stopEvent();
            m.showAt(e.getPoint());
        }
    }
};
NAVIGATIONJS;

?>
