<?php
$jsOne2ManyContent=<<<ONE2MANY
	/**
	 * 视图：{$table_comment12n}列表
	 */
	{$current_classname}View:{
		/**
		 * 视图：{$table_comment12n}列表
		 */
		Grid:Ext.extend(Ext.grid.GridPanel, {
			constructor : function(config) {
				config = Ext.apply({
					store : $appName_alias.$classname.Store.{$current_instancename}Store,
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
$columns_relation                               
						]
					})
				}, config);
				$appName_alias.$classname.View.{$current_classname}View.Grid.superclass.constructor.call(this, config); 
			},             
			/**
			 * 查询符合条件的合同订单
			 */
			doSelect{$current_classname} : function() {
				if ($appName_alias.$classname.View.Running.{$instancename}Grid&&$appName_alias.$classname.View.Running.{$instancename}Grid.getSelectionModel().getSelected()){
					var $realId = $appName_alias.$classname.View.Running.{$instancename}Grid.getSelectionModel().getSelected().data.{$realId};
					var condition = {'$realId':$realId};
					ExtService{$current_classname}.queryPage{$current_classname}(condition,function(provider, response) { 
						if (response.result){  
							if (response.result.data) {   
								var result           = new Array();
								result['data']       =response.result.data; 
								$appName_alias.$classname.Store.{$current_instancename}Store.loadData(result); 
							} else {
								$appName_alias.$classname.Store.{$current_instancename}Store.removeAll();                        
								Ext.Msg.alert('提示', '无符合条件的合同订单！');
							}
						}
					});
				}
			}
		})
	},
ONE2MANY;
?>
