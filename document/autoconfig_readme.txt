һ���������д����ַ:http://localhost/betterlife/tools/tools/autoCode/db_onekey.php


*********************************************************************
*********************************************************************
***********************autoconfig����˵��****************************
*********************************************************************
*********************************************************************

*.���ݶ��������á�
  �����ļ���Ԫ�ڵ�Ϊclass����ÿһ�����ݶ�����в�ѯ���������ϵ������ʾ���á����ݶ���֮���ϵ����
    <classes>
	<class name="Order">
    </classes>

    - name :���ݶ�������

*.��ѯ��������[�������ù�ϵ������ѯ]
    <conditions>
        <condition>name</condition>
	<condition relation_class="Member" show_name="username">member_id</condition>
    </conditions>

    - relation_class :��ϵ������Ӧ�ı����ݶ���
    - show_name      :��Ӧ�ı����ݶ�����ʾ��
    - ֵ             :�ڵ�ǰ���ݶ�����д��ڵĹ�ϵ��������

*.���ϵ������ʾ����
    <relationShows>
       <show local_key="product_id" relation_class="Product">name</show>
    </relationShows>

    - relation_class :��ϵ������Ӧ�ı����ݶ���
    - local_key             :�ڵ�ǰ���ݶ�����д��ڵĹ�ϵ��������
    - ֵ      :��Ӧ�ı����ݶ�����ʾ��

*.���ݶ���֮���ϵ����
    - has_one��һ��һ��
    - belong_has_one������һ��һ��
    - has_many��һ�Զࡿ
    - many_many����Զࡿ
    - belongs_many_many��������Զࡿ

   ʾ����
       һ��һ���û����û�����
	<class name="User">       
		<has_one>  
			<relationclass name="Userdetail">userDetail</relationclass>
		</has_one> 
	</class>   
    
       ����һ��һ���û��Ͳ���
	<class name="User">   
		<belong_has_one>  
			<relationclass name="Department">department</relationclass> 
		</belong_has_one>  
	</class>  

       һ�Զࣺ�û�������
	<class name="User">   
		<has_many>  
			<relationclass name="Comment">comment</relationclass>
		</has_many> 
	</class>      

       ��Զࣺ�û��ͽ�ɫ �����м������һ���ǣ�userrole��  
	<class name="User">
		<many_many>
			<relationclass name="Role">roles</relationclass>
		</many_many>
	</class>      

       ������Զࣺ��ɫ���û� �����м������һ���ǣ�userrole��          
	<class name="Role">
		<belongs_many_many>
			<relationclass name="User">users</relationclass>
		</belongs_many_many>     
	</class>      

*.�����ֶ�����
      ��Ҫ�����м��Ϊ�˼��������ѯ����������ֶ����ݡ�
      ��Ҫ���ø�֪�ǹ����ĸ����ݶ���������ֶΡ�
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
