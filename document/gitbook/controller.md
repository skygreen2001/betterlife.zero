# 控制器层

## 定位
路径    :core/model/

文件名称：ActionBasic.php

github路径:https://github.com/skygreen2001/betterlife/blob/master/core/model/ActionBasic.php

每个控制器都继承自它，它是所有控制器的父类；规范要求：所有控制器要求的前缀:Action_;

在Action所有的方法执行之前可以执行的方法:beforeAction

在Action所有的方法执行之后可以执行的方法:afterAction

可供选择的集成在线编辑器:

* CKEditor
* KindEditor
* xhEditor
* UEditor

默认集成在线编辑器:UEditor

## 前台控制器父类定位
路径:home/betterlife/action/

文件名称：Action.php

## 后台控制器父类定位
路径:home/admin/action/

文件名称：ActionExt.php

## 通用模版控制器父类定位
路径:home/model/action/

文件名称：ActionModel.php


## 内部跳转

    /**
	 * 内部转向到另一网页地址
	 *
	 * @param mixed $action
	 * @param mixed $method
	 * @param array|string $querystringparam
	 * 示例：
	 *     index.php?g=betterlife&m=blog&a=write&pageNo=8&userId=5
	 *     $action：blog
	 *     $method：write
	 *     $querystring：pageNo=8&userId=5
	 *                   array('pageNo'=>8,'userId'=>5)
	 */
	public function redirect($action,$method,$querystring="")

##路由跳转
在全局配置文件Gc.php里:

	/**
	 * URL访问模式,可选参数0、1、2、3,代表以下四种模式：<br/>
	 * 0 (普通模式);<br/>
	 * 1 (PATHINFO 模式); eg:<br/>
	 * 2 (REWRITE  模式); 需要打开.htaccess里的注释行: RewriteEngine On;
	 *					eg: http://localhost/betterlife/betterlife/auth/login<br/>
	 * 3 兼容模式(通过一个GET变量将PATHINFO传递给dispather，默认为s index.php?s=/module/action/id/1)<br/>
	 * 当URL_DISPATCH_ON开启后有效; 默认为PATHINFO 模式，提供最好的用户体验和SEO支持
	 * @var int
	 * @static
	 */
	public static $url_model=0;

在跳转控制文件core/main/Router.php里，集中控制了Action的前因后果的处理。



