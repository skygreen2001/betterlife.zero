<?php
//服务器安装提示
require_once ("../init.php");
$os =strtolower(php_uname());
echo "系统信息:".$os."<br/>";
if (contain($os,"Windows")) {
	echo "您使用的是Windows系统<br/>";
	echo "[以下以wamp进行说明]安装提示如下:<br/>".str_repeat("&nbsp;",20);
	echo "需要安装PHP模块如下:<br/>".str_repeat("&nbsp;",20);
	echo "php_gd2|php_curl|php_mbstring|php_mysqli<br/>".str_repeat("&nbsp;",20);
	echo "需要修改php.ini文件，去掉以下行前的注释符号;<br/>".str_repeat("&nbsp;",40);
	echo "extension=php_curl.dll".str_repeat("&nbsp;",40);
	echo "extension=php_mbstring.dll".str_repeat("&nbsp;",40);
	echo "extension=php_mysqli.dll".str_repeat("&nbsp;",40);
	echo "extension=php_gd2.dll".str_repeat("&nbsp;",40);
	echo "重新运行apache:重新启动 wamp";
} else if (contain($os,"darwin")) {
	echo "您使用的是MAC系统<br/>";
	echo "安装提示如下:<br/>".str_repeat("&nbsp;",20);
	// echo "需要安装PHP模块如下:<br/>".str_repeat("&nbsp;",20);
	// echo "php_gd2|php_curl|php_mbstring|php_mysqli<br/>".str_repeat("&nbsp;",20);
	// echo "需要修改php.ini文件，去掉以下行前的注释符号;<br/>".str_repeat("&nbsp;",40);

	echo "因为安全原因，需要手动在服务器上创建以下目录能够读写<br/>".str_repeat("&nbsp;",20);
	echo "log|attachment|upload|templates_c<br/>".str_repeat("&nbsp;",20);
	echo "需要手动在服务器上执行脚本:<br/>".str_repeat("&nbsp;",40);
	foreach (Gc::$module_names as $template_tmp_dir) {
		$destination=Gc::$nav_root_path.$template_tmp_dir."/".Config_F::VIEW_CORE."/default/tmp/templates_c/";
		echo "sudo mkdir -p ".$destination."<br/>".str_repeat("&nbsp;",40);
		echo "sudo chmod 0777 ".$destination."<br/>".str_repeat("&nbsp;",40);
	}
	if (empty(Gc::$log_config["logpath"])){
		Gc::$log_config["logpath"] = Gc::$nav_root_path.Config_F::LOG_ROOT.DIRECTORY_SEPARATOR;
	}
	$destination=Gc::$log_config["logpath"];
	echo "sudo mkdir -p ".$destination."<br/>".str_repeat("&nbsp;",40);
	echo "sudo chmod 0777 ".$destination."<br/>".str_repeat("&nbsp;",40);
	$destination=Gc::$attachment_path;
	echo "sudo mkdir -p ".$destination."<br/>".str_repeat("&nbsp;",40);
	echo "sudo chmod 0777 ".$destination."<br/>".str_repeat("&nbsp;",40);
	$destination=Gc::$upload_path;
	echo "sudo mkdir -p ".$destination."<br/>".str_repeat("&nbsp;",40);
	echo "sudo chmod 0777 ".$destination."<br/>".str_repeat("&nbsp;",40);
	echo "重新运行apache:sudo apachectl restart";
} else if (contain($os,"ubuntu")) {
	echo "您使用的是Ubuntu系统<br/>";
	echo "安装提示如下:<br/>".str_repeat("&nbsp;",20);
	echo "需要安装PHP模块如下:<br/>".str_repeat("&nbsp;",20);
	echo "php_gd2|php_curl|php_mbstring|php_mysqli<br/>".str_repeat("&nbsp;",20);
	echo "服务器下执行:<br/>".str_repeat("&nbsp;",40);
	echo "sudo apt-get install php5-gd<br/>".str_repeat("&nbsp;",40);
	echo "sudo apt-get install php5-curl<br/>".str_repeat("&nbsp;",20);
	echo "因为安全原因，需要手动在服务器上创建以下目录能够读写<br/>".str_repeat("&nbsp;",20);
	echo "log|attachment|upload|templates_c<br/>".str_repeat("&nbsp;",20);
	echo "需要手动在服务器上执行脚本:<br/>".str_repeat("&nbsp;",40);
	foreach (Gc::$module_names as $template_tmp_dir) {
		$destination=Gc::$nav_root_path.$template_tmp_dir."/".Config_F::VIEW_CORE."/default/tmp/templates_c/";
		echo "sudo mkdir -p ".$destination."<br/>".str_repeat("&nbsp;",40);
		echo "sudo chmod 0777 ".$destination."<br/>".str_repeat("&nbsp;",40);
	}
	if (empty(Gc::$log_config["logpath"])){
		Gc::$log_config["logpath"] = Gc::$nav_root_path.Config_F::LOG_ROOT.DIRECTORY_SEPARATOR;
	}
	$destination=Gc::$log_config["logpath"];
	echo "sudo mkdir -p ".$destination."<br/>".str_repeat("&nbsp;",40);
	echo "sudo chmod 0777 ".$destination."<br/>".str_repeat("&nbsp;",40);
	$destination=Gc::$attachment_path;
	echo "sudo mkdir -p ".$destination."<br/>".str_repeat("&nbsp;",40);
	echo "sudo chmod 0777 ".$destination."<br/>".str_repeat("&nbsp;",40);
	$destination=Gc::$upload_path;
	echo "sudo mkdir -p ".$destination."<br/>".str_repeat("&nbsp;",40);
	echo "sudo chmod 0777 ".$destination."<br/>".str_repeat("&nbsp;",20);
	echo "重新运行apache:sudo service apache2 restart";
} else if (contain($os,"linux")) {
	echo "您使用的是linux系统<br/>";
	echo "安装提示如下:<br/>";
	echo "您使用的是Ubuntu系统<br/>";
	echo "安装提示如下:<br/>".str_repeat("&nbsp;",20);
	echo "需要安装PHP模块如下:<br/>".str_repeat("&nbsp;",20);
	echo "php_gd2|php_curl|php_mbstring|php_mysqli<br/>".str_repeat("&nbsp;",20);
	echo "如果是在Centos服务器下，执行:<br/>".str_repeat("&nbsp;",40);
	echo "yum install php-gd<br/>".str_repeat("&nbsp;",40);
	echo "因为安全原因，需要手动在服务器上创建以下目录能够读写<br/>".str_repeat("&nbsp;",20);
	echo "log|attachment|upload|templates_c<br/>".str_repeat("&nbsp;",20);
	echo "需要手动在服务器上执行脚本:<br/>".str_repeat("&nbsp;",40);
	foreach (Gc::$module_names as $template_tmp_dir) {
		$destination=Gc::$nav_root_path.$template_tmp_dir."/".Config_F::VIEW_CORE."/default/tmp/templates_c/";
		echo "mkdir -p ".$destination."<br/>".str_repeat("&nbsp;",40);
		echo "chmod 0777 ".$destination."<br/>".str_repeat("&nbsp;",40);
	}
	if (empty(Gc::$log_config["logpath"])){
		Gc::$log_config["logpath"] = Gc::$nav_root_path.Config_F::LOG_ROOT.DIRECTORY_SEPARATOR;
	}
	$destination=Gc::$log_config["logpath"];
	echo "mkdir -p ".$destination."<br/>".str_repeat("&nbsp;",40);
	echo "chmod 0777 ".$destination."<br/>".str_repeat("&nbsp;",40);
	$destination=Gc::$attachment_path;
	echo "mkdir -p ".$destination."<br/>".str_repeat("&nbsp;",40);
	echo "chmod 0777 ".$destination."<br/>".str_repeat("&nbsp;",40);
	$destination=Gc::$upload_path;
	echo "mkdir -p ".$destination."<br/>".str_repeat("&nbsp;",40);
	echo "chmod 0777 ".$destination."<br/>".str_repeat("&nbsp;",20);
	echo "重新运行apache:sudo service httpd restart";
	$mysql_config=<<<MYSQLCONFIG
			修改mysql的字符集为utf8
				 修改etc/my.cnf
				[mysqld]
				character_set_server=utf8
				init_connect='SET NAMES utf8'

				[client]
				default-character-set=utf8

				[mysql]
				default-character-set=utf8

				service mysqld restart

				查看字符集：
				mysql：
				show variables like 'character_set%';
MYSQLCONFIG;
	echo $mysql_config;
	echo "重新运行apache:sudo service mysqld restart";
}

?>