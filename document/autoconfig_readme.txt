一键生成所有代码地址:http://127.0.0.1/betterlife/tools/tools/autocode/db_onekey.php

*********************************************************************
*********************************************************************
***********************autoconfig配置说明****************************
*********************************************************************
*********************************************************************

*.数据对象类配置。
  配置文件的元节点为class，对每一个数据对象进行查询条件、表关系主键显示配置、数据对象之间关系配置
    <classes>
    <class name="Order">
    </classes>

    - name :数据对象名称

*.查询条件配置[允许配置关系主键查询]
    <conditions>
        <condition>name</condition>
        <condition relation_class="Member" show_name="username">member_id</condition>
    </conditions>

    - relation_class :关系主键对应的表数据对象
    - show_name      :对应的表数据对象显示列
    - 值             :在当前数据对象表中存在的关系主键名称

*.表关系主键显示配置
    <relationShows>
       <show local_key="product_id" relation_class="Product">name</show>
    </relationShows>

    - relation_class :关系主键对应的表数据对象
    - local_key             :在当前数据对象表中存在的关系主键名称
    - 值      :对应的表数据对象显示列

*.数据对象之间关系配置
    - has_one【一对一】
    - belong_has_one【从属一对一】
    - has_many【一对多】
    - many_many【多对多】
    - belongs_many_many【从属多对多】

   示例：
       一对一：用户和用户详情
    <class name="User">
        <has_one>
            <relationclass name="Userdetail">userDetail</relationclass>
        </has_one>
    </class>

       从属一对一：用户和部门
    <class name="User">
        <belong_has_one>
            <relationclass name="Department">department</relationclass>
        </belong_has_one>
    </class>

       一对多：用户和评论
    <class name="User">
        <has_many>
            <relationclass name="Comment">comment</relationclass>
        </has_many>
    </class>

       多对多：用户和角色 【表中间名最后一段是：userrole】
    <class name="User">
        <many_many>
            <relationclass name="Role">roles</relationclass>
        </many_many>
    </class>

       从属多对多：角色和用户 【表中间名最后一段是：userrole】
    <class name="Role">
        <belongs_many_many>
            <relationclass name="User">users</relationclass>
        </belongs_many_many>
    </class>

*.冗余字段配置
      主要用于中间表为了减少联表查询保存的冗余字段数据。
      需要配置告知是关联哪个数据对象的冗余字段。
    <class name="Themeshow">
        <redundancy>
            <table name="Theme">
                <field name="theme_name"/>
                <field name="introduce"/>
                <field name="image"/>
                <field name="parent_id"/>
                <field name="childcount"/>
                <field name="level"/>
            </table>
        </redundancy>
    </class>


*.支持一选多[全部|已选|未选择]
  一选多中配置的是中间关系表才会生成
    <class name="User">
        <has_many>
            <relationclass name="Userrole">userrole</relationclass>
        </has_many>
    </class>
  说明:中间表对象类必须配置查询条件配置conditions才能正常使用