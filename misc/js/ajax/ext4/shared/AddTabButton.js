


//Add new tab button in tab strip
//@link http://www.sencha.com/forum/archive/index.php/t-83213.html
Ext.ux.AddTabButton = (function() {
    function onTabPanelRender()
    {
        this.addTab = new Ext.tab.Tab({
            text: '&#160',
            icon: 'ajax/ext4/resources/images/new/add.gif',
            closable: false
        });

        this.addTab.on({
            click: this.onAddTabClick,
            scope: this
        });

        // I'm not sure about adding the tab to the tab bar with a massive index. Seems to work though.
        this.getTabBar().insert(999, this.addTab);
    }
    
    return {
        init: function(tp) {
            if (tp instanceof Ext.tab.Panel) {
                tp.onRender = Ext.Function.createSequence(tp.onRender, onTabPanelRender);
            }
        }
    };
})();