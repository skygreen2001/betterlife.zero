# 准备工作

路径    :core/autocode/prepare/

文件名称：AutoCodeConfig.php

文件名称：AutoCodeValidate.php

##概述
准备工作分为两个部分
1. 校验器
     * 预先校验表定义是否有问题
     校验包括以下问题:
     * 0.invalid_idname-检测标识列是否按规范定义
     * 1.nocomment-表无注释说明
     * 2.column_nocomment-列名无注释说明
     * 3.samefieldname_id-同张表列名不能包含:同名、同名_id
     * 4.invaid_keywords-列名不能为Mysql特殊关键字如:desc,from,
     * 5.表中列定义中的"_"是半角，不是全角。
     * 6.确认以下表的实体类放置在规范的domain目录下

2. 自动生成配置文件
   自动生成表五种关系的配置,条件查询的配置、关系主键显示属性的配置。



