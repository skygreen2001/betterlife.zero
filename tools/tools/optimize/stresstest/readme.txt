1.复制web目录一份在同级目录下
  cp -R betterlife betterlifedev
  移动betterlifedev到同级目录下
  mv betterlifedev betterlife

2.复制数据库备份一份
  betterlife  betterlifedev
  *.在phpmyadmin里导出sql脚本如betterlife.sql
  *.从betterlife.sql生成压缩文件betterlife.sql.zip
  *.新建数据库betterlifedev
  *.在betterlifedev里导入betterlife.sql.zip

3.模拟账户已登录

4.收集所有的网站链接

5.采用Apache HTTP 服务器性能基准工具:AB[Apache 自带，无需安装，直接可以使用]
   ab -c 100 -n 10000 http://[域名]/index.php
   *.模拟10000个请求数，100个并发

6.采用http_load测试web服务器的吞吐量与负载
  http_load -p 30 -s 60  urllist.txt
  *.urllist.txt里文件格式是每行一个URL，URL最好超过50－100个测试效果比较好
  下载地址:http://acme.com/software/http_load/

7.采用webbench进行压力测试:
  webbench -c 200 -t 30 http://[域名]/index.php
  下载地址:http://home.tiscali.cz/~cz210552/distfiles/webbench-1.5.tar.gz

8.查看负载:uptime
  查看load average最后一个值(查看15分钟内的平均负载)















参考:查看机器设备性能
    http://www.cnblogs.com/xd502djj/archive/2011/02/28/1967350.html

linux 下查看机器是cpu是几核的