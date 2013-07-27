*.安装wamp
*.将项目复制到www目录下
*.安装数据库

*.ftp上去文件后，需要设置以下目录权限为全公开：
- upload
- attachment
- log
- home/admin/view/default/tmp/templates_c
- home/应用名称/view/default/tmp/templates_c

*.修改以下配置：
http.conf
  所有的Deny from all修改成  Allow from all
  需加载模块
	LoadModule rewrite_module modules/mod_rewrite.so

php.ini
  display_errors = Off

  需加载功能模块:
	extension=php_curl.dll
	extension=php_mbstring.dll
	extension=php_mysqli.dll
	extension=php_gd2.dll
	extension=php_zip.dll
	extension=php_rar.dll

