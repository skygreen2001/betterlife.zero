# 显示层
显示层默认使用PHP阵营最成熟的Smary框架

可以在Smarty模板引擎中显示页面tpl文件中直接使用以下通用的变量，它们定义在框架核心文件里：core/main/View.php

* url_base:网站根路径
* site_name:网站应用的名称,展示给网站用户,一般为中文
* appName:网站应用的名称,网站导航使用,一般为英文
* template_url:显示层所在路径，一般前台在home/web应用名/view/default/,后台在home/admin/view/default/
* templateDir:显示层所在物理路径，一般用在smarty模板继承layout地方定义需要用到
* upload_url   :上传文件网络路径
* uploadImg_url:上传图片定义网络路径
* encoding:网站字符编码,一般为UTF-8或者GBK

## 扩展特性
框架在开发初期参考Thinkphp实际整合了五套表示层框架，如下所示：

        * Smarty
        * EaseTemplate
        * Flexy
        * SmartTemplate
        * TemplateLite

在实践中完整验证了Smarty的通用性，其它并未实践使用过，有待考验。


## 显示层位置
### 后台管理:home/admin/view

    默认在default目录下

### 前台显示:home/betterlife/view/

    默认在bootstrap目录下
    /**
	 * 每个模块可以定义自己显示的模板名
	 * 如果没有定义，则使用$self_theme_dir默认定义的名称，一般都是default
	 * @var mixed
	 */
	public static $self_theme_dir_every=array(
		'betterlife'=>'bootstrap'
	);
    可通过设置Gc.php里的$self_theme_dir_every的bootstrap为default即可设置回默认default样式。
    public static $self_theme_dir_every=array(
		'betterlife'=>'default'
	);

### 通用模版显示:home/model/view/

    默认在default目录下


## 打开调试窗口
如果在mvc框架中表示层使用了smarty框架,可在Gc.php文件里调整以下配置进行调试。

	/**
	 * 是否打开Smarty Debug Console窗口
	 * @var bool
	 * @static
	 */
	public static $dev_smarty_on=false;

