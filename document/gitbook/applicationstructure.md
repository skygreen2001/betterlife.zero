# 框架介绍

##概述
框架设计之初主要关注于Web网站开发，因此采用标准的MVC模式定义了整个网站的结构；规划设计大量借鉴了Java阵营中Spring+Hibernate+Struts的优秀设计，并基于实用简化的原则对其进行了调整完善。

## 层级关系
* domain:实体类,数据对象层,所有层都可以使用它;
* service:服务类,业务逻辑层。
* action:控制器层,也有定义为controller的。
* view:显示页面,表示层;包括html,js,css,images等资源文件

常规的调用关系如下:

    domain<-service<-action<-view

在使用框架之初,因为业务逻辑比较简单,更常用的调用关系如下:

    domain<-action<-view

在框架的示例前台应用中,一般都采用这种方式实现;一般来讲，它已经足够解决比较复杂的问题了，service实在多余，在常规的Web应用开发中,service层的设计并未大量投入使用。
