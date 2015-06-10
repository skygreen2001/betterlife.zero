# 与ucenter 整合
## 全局配置
在Gc.php里设置
	/**
	 * 是否与Ucenter的用户中心进行整合
	 * @var mixed
	 */
	public static $is_ucenter_integration=false;
默认是不整合ucenter,需要和ucenter整合时设置其为true前台会按照整合ucenter的规则处理工作。

## 工具类
与ucenter整合的代码集中在工具类里：

    core/util/ucenter/UtilUcenter.php

## 使用
使用Ucenter整合单点登录可在前台Action控制器文件中看到:
    home/betterlife/action/Action_Auth.php







