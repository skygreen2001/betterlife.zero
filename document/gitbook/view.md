# 显示
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


