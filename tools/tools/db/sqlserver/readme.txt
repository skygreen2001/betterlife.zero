betterlife从mysql到sql server的移植路线图
**********************在PHP:betterlife里****************************
1.新建BetterlifeNet数据库
2.复制betterlife所有的表到BetterlifeNet
3.运行脚本文件:http://127.0.0.1/betterlife/tools/tools/db/sqlserver/db_sqlserver_convert_prepare.php
4.在BetterlifeNet里运行生成的sql脚本

**********************在Net:Betterlife.Net里*************************
Betterlife.Net地址:https://github.com/skygreen2001/betterlife.net
1.先确保整个解决方案正常编译完成.
2.运行Betterlife.Net\Common\Tools\工程,在弹出窗口点击选择按钮:显示数据库信息.
3.选择数据库类型:Mysql,数据库名称选择:BetterlifeNet,点击选择按钮:
  移植数据库脚本[从Mysql->Sqlserver],生成创建Sqlserver数据库脚本
4.利用SSMS创建数据库BetterlifeNet,运行上一步生成的sql脚本