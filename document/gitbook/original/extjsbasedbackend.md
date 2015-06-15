# 基于extjs框架的后台
基于extjs框架搭建了统一的增删改查模版。并采用了代码生成大大减少了重复创建的工作量。

## 结构
包括统一的列表Grid、查询重置按钮行、新增修改window、批量导入window、删除确认窗口。以表为单位每个都可以通过代码生成。

框架结构制作包括后台布局UI
* 左侧菜单:home/admin/src/view/leftmenu.config.xml
* 顶部菜单:home/admin/src/view/menu.config.xml
* 顶部工具栏home/admin/src/view/toolbar.config.xml

###service配置文件

    采用Ext Direct方式进行前台JS调用后台服务的方式。
    路径:home/admin/src/service/service.config.xml
    定义单元样式如下:
    	<service name="ExtServiceBlog">
    		<methods>
    			<method name="save">
    				<param name="len">1</param>
    				<param name="formHandler">true</param>
    			</method>
    			<method name="update">
    				<param name="len">1</param>
    				<param name="formHandler">true</param>
    			</method>
    			<method name="deleteByIds">
    				<param name="len">1</param>
    			</method>
    			<method name="queryPageBlog">
    				<param name="len">1</param>
    			</method>
    			<method name="exportBlog">
    				<param name="len">1</param>
    			</method>
    		</methods>
    	</service>
    这是以表bb_core_blog，类Blog定义的可访问服务接口。

### 后台服务
    路径:home/admin/src/services/ext/
    以上定义的service方法所在的service文件一般都放在这里。

### 批量上传文件
    所有表批量上传的调用统一放在后台Action

    路径:home/admin/action/Action_Upload.php
    形式如下:
    /**
	 * 上传数据对象:博客数据文件
	 */
	public function uploadBlog()
	{
		return self::ExtResponse(Manager_ExtService::blogService()->import($_FILES));
	}

## 表示层定义页面
    路径:home/admin/view/default/core

## 表示层定义JS文件
    路径:home/admin/view/default/js/ext/
    后台extjs将近90%核心代码都定义在这里
