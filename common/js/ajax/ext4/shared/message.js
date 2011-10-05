/**
 * Ext.App
 * @extends Ext.util.Observable
 * @author Chris Scott
 */
Ext.App = function(config) {
    this.views = [];
    
    this.initStateProvider();
    
    Ext.apply(this, config);
    
    if (!this.api.actions) {
        this.api.actions = {};
    }
    
    Ext.onReady(this.onReady, this);
    
    Ext.App.superclass.constructor.apply(this, arguments);
};

Ext.extend(Ext.App, Ext.util.Observable, {

    /***
     * response status codes.
     */
    STATUS_EXCEPTION :          'exception',
    STATUS_VALIDATION_ERROR :   "validation",
    STATUS_ERROR:               "error",
    STATUS_NOTICE:              "notice",
    STATUS_OK:                  "ok",
    STATUS_HELP:                "help",
    /***
     * response status codes.
     */
    TITLE_STATUS_EXCEPTION :          '异常',
    TITLE_STATUS_VALIDATION_ERROR :   "校验",
    TITLE_STATUS_ERROR:               "错误",
    TITLE_STATUS_NOTICE:              "警告",
    TITLE_STATUS_OK:                  "成功",
    TITLE_STATUS_HELP:                "帮助",

    /**
     * @cfg {Object} api
     * remoting api.  should be defined in your own config js.
     */
    api: {
        url: null,
        type: null,
        actions: {}
    },

    // private, ref to message-box Element.
    msgCt : null,

    // @protected, onReady, executes when Ext.onReady fires.
    onReady : function() {
        // create the msgBox container.  used for App.setAlert
        this.msgCt = Ext.DomHelper.insertFirst(document.body, {id:'msg-div'}, true);
        this.msgCt.setStyle('position', 'absolute');
        this.msgCt.setStyle('z-index', 9999);
        this.msgCt.setWidth(300);
    },

    initStateProvider : function() {
        /*
         * set days to be however long you think cookies should last
         */
        var days = '';        // expires when browser closes
        if(days){
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var exptime = "; expires="+date.toGMTString();
        } else {
            var exptime = null;
        }

        // register provider with state manager.
        Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
            path: '/',
            expires: exptime,
            domain: null,
            secure: false
        }));
    },

    /**
     * registerView
     * register an application view component.
     * @param {Object} view
     */
    registerView : function(view) {
        this.views.push(view);
    },

    /**
     * getViews
     * return list of registered views
     */
    getViews : function() {
        return this.views;
    },

    /**
     * registerActions
     * registers new actions for API
     * @param {Object} actions
     */
    registerActions : function(actions) {
        Ext.apply(this.api.actions, actions);
    },

    /**
     * getAPI
     * return Ext Remoting api
     */
    getAPI : function() {
        return this.api;
    },

    /***
     * setAlert
     * show the message box.  Aliased to addMessage
     * @param {String} msg
     * @param {Bool} status
     */
    setAlert : function(status, msg) {
        this.addMessage(status, msg);
    },

    /***
     * adds a message to queue.
     * @param {String} msg
     * @param {Bool} status
     */
    addMessage : function(status, msg) {
        var delay = 3;    // <-- default delay of msg box is 1 second.
        if (status == false) {
            delay = 5;    // <-- when status is error, msg box delay is 3 seconds.
        }
        // add some smarts to msg's duration (div by 13.3 between 3 & 9 seconds)
        delay = msg.length / 13.3;
        if (delay < 3) {
            delay = 3;
        }
        else if (delay > 9) {
            delay = 9;
        }

        this.msgCt.alignTo(document, 't-t');
        Ext.DomHelper.append(this.msgCt, {html:this.buildMessageBox(status, String.format.apply(String, Array.prototype.slice.call(arguments, 1)))}, true).slideIn('t').pause(delay).ghost("t", {remove:true});
    },

    /***
     * buildMessageBox
     */
    buildMessageBox : function(title, msg) {
        switch (title) {
            case true:
                title_show = this.TITLE_STATUS_OK;
                break;
            case false:
                title_show = this.TITLE_STATUS_ERROR;
                break;
        }
        return [
            '<div class="app-msg">',
            '<div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>',
            '<div class="x-box-ml"><div class="x-box-mr"><div class="x-box-mc"><h3 class="x-icon-text icon-status-' + title + '">', title_show, '</h3>', msg, '</div></div></div>',
            '<div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>',
            '</div>'
        ].join('');
    },

    /***
     * setViewState, alias for Ext.state.Manager.set
     * @param {Object} key
     * @param {Object} value
     */
    setViewState : function(key, value) {
        Ext.state.Manager.set(key, value);
    },

    /***
     * getViewState, aliaz for Ext.state.Manager.get
     * @param {Object} cmd
     */
    getViewState : function(key) {
        return Ext.state.Manager.get(key);
    },

    handleResponse : function(res) {
        if (res.type == this.STATUS_EXCEPTION) {
            return this.handleException(res);
        }
        if (res.message.length > 0) {
            this.setAlert(res.status, res.message);
        }
    },

    handleException : function(res) {
        Ext.MessageBox.alert(res.type.toUpperCase(), res.message);
    }
});



/*!
 * Ext JS Library 3.3.0
 * Copyright(c) 2006-2010 Ext JS, Inc.
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
//Ext.BLANK_IMAGE_URL = 'ajax/ext/resources/images/default/s.gif';

Ext.msg = function(){
    var msgCt;

    function createBox(t, s){
        return ['<div class="msg">',
                '<div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>',
                '<div class="x-box-ml"><div class="x-box-mr"><div class="x-box-mc"><h3>', t, '</h3>', s, '</div></div></div>',
                '<div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>',
                '</div>'].join('');
    }
    return {
        msg : function(title, format){
            if(!msgCt){
                msgCt = Ext.DomHelper.insertFirst(document.body, {id:'msg-div'}, true);
            }
            msgCt.alignTo(document, 't-t');
            var s = String.format.apply(String, Array.prototype.slice.call(arguments, 1));
            var m = Ext.DomHelper.append(msgCt, {html:createBox(title, s)}, true);
            m.slideIn('t').pause(1).ghost("t", {remove:true});
        },

        init : function(){
            var lb = Ext.get('lib-bar');
            if(lb){
                lb.show();
            }
        }
    };
}();

Ext.onReady(Ext.msg.init, Ext.msg);

// old school cookie functions
var Cookies = {};
Cookies.set = function(name, value){
     var argv = arguments;
     var argc = arguments.length;
     var expires = (argc > 2) ? argv[2] : null;
     var path = (argc > 3) ? argv[3] : '/';
     var domain = (argc > 4) ? argv[4] : null;
     var secure = (argc > 5) ? argv[5] : false;
     document.cookie = name + "=" + escape (value) +
       ((expires == null) ? "" : ("; expires=" + expires.toGMTString())) +
       ((path == null) ? "" : ("; path=" + path)) +
       ((domain == null) ? "" : ("; domain=" + domain)) +
       ((secure == true) ? "; secure" : "");
};

Cookies.get = function(name){
    var arg = name + "=";
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    var j = 0;
    while(i < clen){
        j = i + alen;
        if (document.cookie.substring(i, j) == arg)
            return Cookies.getCookieVal(j);
        i = document.cookie.indexOf(" ", i) + 1;
        if(i == 0)
            break;
    }
    return null;
};

Cookies.clear = function(name) {
  if(Cookies.get(name)){
    document.cookie = name + "=" +
    "; expires=Thu, 01-Jan-70 00:00:01 GMT";
  }
};

Cookies.getCookieVal = function(offset){
   var endstr = document.cookie.indexOf(";", offset);
   if(endstr == -1){
       endstr = document.cookie.length;
   }
   return unescape(document.cookie.substring(offset, endstr));
};