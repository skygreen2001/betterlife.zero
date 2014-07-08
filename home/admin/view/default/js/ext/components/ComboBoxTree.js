/**
 * @description 下拉框目录树选择框
 * @link http://www.cnblogs.com/shenjk/archive/2010/05/25/1743579.html
 * @author Administrator
 */
Ext.form.ComboBoxTree = Ext.extend(Ext.form.ComboBox, {
	/**
	 * 是否目录也能被选择
	 * @type Boolean
	 */
	canFolderSelect:true,
	store: new Ext.data.SimpleStore({ fields: [], data: [[]] }),
	editable: true,
	shadow: false,
	mode: 'local',
	triggerAction: 'all',
	maxHeight: 200,
	selectedClass: '',
	onSelect: null,
	canCollapse: true,
	triggerAction: 'all',
	forceSelection: true,
	constructor: function(_cfg) {
		if (_cfg == null) {
			_cfg = {};
		}
		Ext.apply(this, _cfg);
		this.treerenderid = Ext.id();
		this.tpl = String.format('<tpl for="."><div style="height:200px"><div id="ext-combobox-tree{0}"></div></div></tpl>', this.treerenderid);
		Ext.form.ComboBoxTree.superclass.constructor.apply(this, arguments);
		if (this.tree) {
			var cmb = this;
			this.tree.on('click', function(node) {
				cmb.canCollapse = false;
				if (cmb.canFolderSelect){
						cmb.canCollapse = true;
						//modify by skygreen
						//原为:cmb.setValue(node.text);
						if (Ext.isFunction(cmb.onSelect)) {
							cmb.onSelect(cmb, node);
						} else {
							cmb.setValue(node.text);
						}
						cmb.collapse();
				}else{
					if (node.isLeaf()) {
						cmb.canCollapse = true;
						if (Ext.isFunction(cmb.onSelect)) {
							cmb.onSelect(cmb, node);
						} else {
							cmb.setValue(node.text);
						}
						cmb.collapse();
					}
				}
			});
			//以下事件，让combobox能正常关闭
			this.tree.on('expandnode', function() { cmb.canCollapse = true; });
			this.tree.on('beforeload', function() { cmb.canCollapse = false; });
			this.tree.on('beforeexpandnode', function() { cmb.canCollapse = false; });
			this.tree.on('beforecollapsenode', function() { cmb.canCollapse = false; });
		}
		this.on('expand', this.expandHandler, this);
		this.on('collapse', this.collapseHandler, this);
	},
	expandHandler: function expand() {
		this.canCollapse = true;
		if (this.tree) {
			this.tree.render('ext-combobox-tree' + this.treerenderid);
			this.canCollapse = true;
			this.tree.getRootNode().expand();

		}
	},
	collapseHandler: function collapse() {
		if (!this.canCollapse) {
			this.expand();
		}
	}
});
Ext.reg('combotree', Ext.form.ComboBoxTree);