    Ext.namespace("betterlife.admin");
    bb = betterlife.admin;  
    
    /**
    * 导航功能        
    */    
    bb.Navigaion=Ext.emptyFn;

    /**
    * 根据指定的Url在指定的TabPanel组件上添加Tab
    * 【如果已经添加了则激活该Tab】
    * @param contentPanel TabPanel component组件
    * @param title Tab头标题
    * @param url 指定的Url
    * @param id 添加的Tab的标识
    */
    bb.Navigaion.addTabByUrl=function(contentPanel,title,url,id)
    {
        var cpId = 'cp-' + id;
        var isLocalUrl=false;
        if (!bb.Config.IsTabHeaderShow){   
            var centerArea=Ext.get("centerArea");
            centerArea.setHeight(contentPanel.getHeight());
            centerArea.setWidth(contentPanel.getWidth());
            centerArea.update("<iframe scrolling='auto' width='100%' height='100%'  frameborder='0' src='"+url+"'></iframe>");
            contentPanel.setTitle(title, "tabs");
            return ;
        }
        var tab = contentPanel.getComponent(cpId);
        if (tab){
           contentPanel.setActiveTab(tab);
        }else{
            if (isLocalUrl){
               contentPanel.add({   
                        id: cpId,
                        title: title,
                        iconCls:'tabs',  
                        loader: {
                            url: url,
                            contentType: 'html',
                            loadMask: true
                        },
                        closable:true,
                        listeners: {
                            activate: function(tab) {
                                tab.loader.load();
                            }
                        }      
                }).show();
            }else{
               contentPanel.add({   
                    id:cpId,
                    title: title,    
                    iconCls: 'tabs',  
                    html:"<iframe scrolling='auto' width='100%' height='100%'  frameborder='0' src='"+url+"'> </iframe>",//id='main' name='main' 
                    closable:true
                }).show();
            }            
         }
    }    

    
    /**
    * 改写超链接默认事件，使新打开的页面都显示在指定的TabPanel组件上。
    */
    bb.Navigaion.HyperlinkClicked=function(e)
    {
        e.preventDefault();
        var linkTarget=e.target;
        var title="";
        if (Ext.isIE){
          title=linkTarget.innerText;
        }else{
          title=linkTarget.text;  
        }
        bb.Navigaion.addTabByUrl(Ext.getCmp('centerPanel'),title,linkTarget.href,linkTarget.id);
    }
    