# Betterlife CMS Framework

下载地址：https://github.com/skygreen2001/betterlife

        支持IT开发者快速开发、易于维护、实用于项目和产品开发的框架，它的原则通用于Php、Java、C#；后端集成Extjs通用的增删改查导入导出模式、提供了一对一、一对多、多对多关系表示层的实现；这一切都可以用一键生成所有的代码，在初期能快速开发，后期易于修改维护不断完善成为产品；它不只是代码级框架的实践，也是项目经验的实践。它不只生成代码，还自动生成项目所需的文档

1. 数据库原型设计：MysqlWorkBench
2. 代码原型      ：Betterlife框架的代码生成工具
3. 页面原型设计
4. 设计图到静态页面
5. 中间件服务器：Apache
6. 部署工具：Wamp
7. 开发语言：Php
8. 数据库  ：Mysql

## 开发流程：
1. 数据层：MysqlWorkBench->Mysql->Betterlife框架的代码生成工具->生成前端和后端代码
2. 表示层：Axure-〉Dreamweaver-〉静态标准Html页面
3. 逻辑层：整合数据层<=〉表示层

## 框架目录定义
1. core:框架核心支持文件
2. data:数据初始化-抓取网上数据，仅供开发测试
3. db:框架数据库测试数据
4. library:通用功能模块
5. module:通用应用模块，如搜索引擎，百度地图等
6. taglib:自定义标签，您也可以在自己的应用中定义自定义标签
7. test:单元测试用例，使用PHPUnit
8. tools:开发中通常用到的小工具【需发布在应用中访问url路径使用】




