1.为了解决TabPanel的tabWidth='auto'在IE6里的bug，修改了ExtJS库的原文件：
  ext-all.js
************Ext-3.3***************************
  原为：o.style.width=(l-(g-d))+"px"
  现为：if(this.tabWidth!=='auto'){o.style.width=(l-(g-d))+"px";}else{o.style.width ='auto';}

************Ext-3.4***************************
  原为：n.style.width=(k-(g-d))+"px"
  现为：if(this.tabWidth!=='auto'){n.style.width=(k-(g-d))+"px";}else{n.style.width ='auto';}

  ext-all-debug.js
  ext-all-debug-w-comments.js
  原为：inner.style.width = (each - (tw-iw)) + 'px';
  现为：if(this.tabWidth!=='auto'){inner.style.width = (each - (tw-iw)) + 'px';}else{inner.style.width ='auto';}

2.在TabCloseMenu.js里添加以下代码使Grid 的单元格可复制文本:
Ext.override(Ext.grid.GridView, {
    templates: {
        cell: new Ext.Template(
                    '<td class="x-grid3-col x-grid3-cell x-grid3-td-{id} {css}" style="{style}" tabIndex="0" {cellAttr}>',
                    '<div class="x-grid3-cell-inner x-grid3-col-{id}" {attr}>{value}</div>',
                    "</td>"
            )
    }
});