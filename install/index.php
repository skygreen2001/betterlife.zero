<?php
//服务器安装提示
require_once ("../init.php");
$os  = strtolower(php_uname());
echo UtilCss::form_css() . "\r\n";
echo "
<style type = 'text/css'>
body {
    font-family: Arial;
    line-height:2em;
    margin:15px;
    padding:0;
    border:0 none;
}
p {
    margin:5px;
}
</style>
";
echo "系统信息:" . $os . "<br/>";
if (contain($os,"Windows")) {
    echo "您使用的是Windows系统<br/>";
    echo "[以下以wamp进行说明]<br/>安装提示如下:<br/>" . str_repeat("&nbsp;",12);
    echo "* . 需要安装PHP模块如下:<br/>" . str_repeat("&nbsp;",30);
    echo "php_gd2|php_curl|php_mbstring|php_mysqli<br/>" . str_repeat("&nbsp;",12);
    echo "* . 需要修改php.ini文件，去掉以下行前的注释符号;<br/>" . str_repeat("&nbsp;",30);
    echo "extension = php_curl.dll<br/>" . str_repeat("&nbsp;",30);
    echo "extension = php_mbstring.dll<br/>" . str_repeat("&nbsp;",30);
    echo "extension = php_mysqli.dll<br/>" . str_repeat("&nbsp;",30);
    echo "extension = php_gd2.dll<br/>" . str_repeat("&nbsp;",12);
    echo "* . 重启 wamp<br/>";
} else if (contain($os,"darwin")) {
    echo "您使用的是MacOS系统<br/>";
    echo "安装提示如下:<br/>" . str_repeat("&nbsp;",12);
    // echo "需要安装PHP模块如下:<br/>" . str_repeat("&nbsp;",12);
    // echo "php_gd2|php_curl|php_mbstring|php_mysqli<br/>" . str_repeat("&nbsp;",12);
    // echo "需要修改php.ini文件，去掉以下行前的注释符号;<br/>" . str_repeat("&nbsp;",30);
    echo "* . 因为安全原因，需要手动在服务器上创建以下目录能够读写<br/>" . str_repeat("&nbsp;",30);
    echo "log|upload|templates_c<br/>" . str_repeat("&nbsp;",12);
    echo "* . 需要手动在服务器上执行脚本:<br/>" . str_repeat("&nbsp;",24);
    echo "第一种解决方案:<br/>";
    $apache_config = <<<APACHECONFIG
                              1 . 命令行工具输入 : id 得到:uid=pupu(您的名字)
                              2 . 编辑配置文件 : sudo vi /etc/apache2/httpd.conf
                                  修改配置如下 :
                                  &lt;IfModule unixd_module>
                                      #       👇  您的名字[原为_www]
                                      User pupu
                                      #         👇  [原为_www]
                                      Group staff
                                  &lt;/IfModule>
APACHECONFIG;
    $apache_config = str_replace("\n", "<br/>", $apache_config);
    $apache_config = str_replace(" ", "&nbsp;", $apache_config);
    echo $apache_config . "<br/><br/>" . str_repeat("&nbsp;",24);
    echo "第二种解决方案:<br/>" . str_repeat("&nbsp;",30);
    foreach (Gc::$module_names as $template_tmp_dir) {
        $destination = Gc::$nav_root_path . Gc::$module_root . "/" . $template_tmp_dir . "/" . View::VIEW_DIR_VIEW . "/" . Gc::$self_theme_dir . "/tmp/templates_c/";
        echo "sudo mkdir -p " . $destination . "<br/>" . str_repeat("&nbsp;",30);
        echo "sudo chmod -R 0777 " . $destination . "<br/>" . str_repeat("&nbsp;",30);
    }
    if (empty(Gc::$log_config["logpath"])){
        Gc::$log_config["logpath"] = Gc::$nav_root_path . Config_F::LOG_ROOT . DS;
    }
    $destination = Gc::$log_config["logpath"];
    echo "sudo mkdir -p " . $destination . "<br/>" . str_repeat("&nbsp;",30);
    echo "sudo chmod -R 0755 " . $destination . "<br/>" . str_repeat("&nbsp;",30);
    $destination = Gc::$upload_path;
    echo "sudo mkdir -p " . $destination . "<br/>" . str_repeat("&nbsp;",30);
    echo "sudo chmod -R 0755 " . $destination . "<br/>" . str_repeat("&nbsp;",12);
    echo "* . 重启apache : sudo apachectl restart";
} else if (contain($os,"ubuntu")) {
    echo "您使用的是Ubuntu系统<br/>";
    echo "安装提示如下:<br/>" . str_repeat("&nbsp;",12);
    echo "* . 需要安装PHP模块如下:<br/>" . str_repeat("&nbsp;",30);
    echo "php_gd2|php_curl|php_mbstring|php_mysqli<br/>" . str_repeat("&nbsp;",12);
    echo "* . 服务器下执行:<br/>" . str_repeat("&nbsp;",30);
    echo "sudo apt-get install php5-gd<br/>" . str_repeat("&nbsp;",30);
    echo "sudo apt-get install php5-curl<br/>" . str_repeat("&nbsp;",12);
    echo "* . 因为安全原因，需要手动在服务器上创建以下目录能够读写<br/>" . str_repeat("&nbsp;",30);
    echo "log|upload|templates_c<br/>" . str_repeat("&nbsp;",12);
    echo "* . 需要手动在服务器上执行脚本:<br/>" . str_repeat("&nbsp;",30);
    foreach (Gc::$module_names as $template_tmp_dir) {
        $destination = Gc::$nav_root_path . Gc::$module_root . "/" . $template_tmp_dir . "/" . View::VIEW_DIR_VIEW . "/" . Gc::$self_theme_dir . "/tmp/templates_c/";
        echo "sudo mkdir -p " . $destination . "<br/>" . str_repeat("&nbsp;",30);
        echo "sudo chown -R www-data:www-data " . $destination . "<br/>" . str_repeat("&nbsp;",30);
        echo "sudo chmod -R 0777 " . $destination . "<br/>" . str_repeat("&nbsp;",30);
    }
    if (empty(Gc::$log_config["logpath"])){
        Gc::$log_config["logpath"] = Gc::$nav_root_path . Config_F::LOG_ROOT . DS;
    }
    $destination = Gc::$log_config["logpath"];
    echo "sudo mkdir -p " . $destination . "<br/>" . str_repeat("&nbsp;",30);
    echo "sudo chown -R www-data:www-data " . $destination . "<br/>" . str_repeat("&nbsp;",30);
    echo "sudo chmod -R 0777 " . $destination . "<br/>" . str_repeat("&nbsp;",30);
    $destination = Gc::$upload_path;
    echo "sudo mkdir -p " . $destination . "<br/>" . str_repeat("&nbsp;",30);
    echo "sudo chown -R www-data:www-data " . $destination . "<br/>" . str_repeat("&nbsp;",30);
    echo "sudo chmod -R 0755 " . $destination . "<br/>" . str_repeat("&nbsp;",12);
    echo "* . 重启apache : sudo service apache2 restart";
} else if (contain($os,"linux")) {
    echo "您使用的是linux系统<br/>";
    echo "安装提示如下:<br/>" . str_repeat("&nbsp;",12);
    echo "* . 需要安装PHP模块如下:<br/>" . str_repeat("&nbsp;",30);
    echo "php_gd2|php_curl|php_mbstring|php_mysqli<br/>" . str_repeat("&nbsp;",12);
    echo "* . 如果是在Centos服务器下，执行:<br/>" . str_repeat("&nbsp;",30);
    echo "yum install php-gd<br/>" . str_repeat("&nbsp;",30);
    echo "* . 因为安全原因，需要手动在服务器上创建以下目录能够读写<br/>" . str_repeat("&nbsp;",30);
    echo "log|upload|templates_c<br/>" . str_repeat("&nbsp;",12);
    echo "* . 需要手动在服务器上执行脚本 : <br/>" . str_repeat("&nbsp;",30);
    foreach (Gc::$module_names as $template_tmp_dir) {
        $destination = Gc::$nav_root_path . Gc::$module_root . "/" . $template_tmp_dir . "/" . View::VIEW_DIR_VIEW . "/" . Gc::$self_theme_dir . "/tmp/templates_c/";
        echo "sudo mkdir -p " . $destination . "<br/>" . str_repeat("&nbsp;",30);
        echo "sudo chown -R www-data:www-data " . $destination . "<br/>" . str_repeat("&nbsp;",30);
        echo "sudo chmod -R 0777 " . $destination . "<br/>" . str_repeat("&nbsp;",30);
    }
    if (empty(Gc::$log_config["logpath"])){
        Gc::$log_config["logpath"] = Gc::$nav_root_path . Config_F::LOG_ROOT . DS;
    }
    $destination = Gc::$log_config["logpath"];
    echo "sudo mkdir -p " . $destination . "<br/>" . str_repeat("&nbsp;",30);
    echo "sudo chown -R www-data:www-data " . $destination . "<br/>" . str_repeat("&nbsp;",30);
    echo "sudo chmod -R 0777 " . $destination . "<br/>" . str_repeat("&nbsp;",30);
    $destination = Gc::$upload_path;
    echo "sudo mkdir -p " . $destination . "<br/>" . str_repeat("&nbsp;",30);
    echo "sudo chown -R www-data:www-data " . $destination . "<br/>" . str_repeat("&nbsp;",30);
    echo "sudo chmod -R 0755 " . $destination . "<br/>" . str_repeat("&nbsp;",12);
    echo "* . 重启apache : sudo service httpd restart";
    $mysql_config = <<<MYSQLCONFIG
                                + . 修改mysql的字符集为utf8
                                        修改etc/my . cnf
                                        [mysqld]
                                            character_set_server = utf8
                                            init_connect = 'SET NAMES utf8'
                                        [client]
                                            default-character-set = utf8
                                        [mysql]
                                            default-character-set = utf8
                                + . 运行命令:service mysqld restart
                                + . 查看字符集：
                                        mysql：
                                            show variables like 'character_set%';
MYSQLCONFIG;
    $mysql_config = str_replace("\r\n", "<br/>", $mysql_config);
    $mysql_config = str_replace("    ", "&nbsp;&nbsp;&nbsp;&nbsp;", $mysql_config);
    echo $mysql_config . str_repeat("&nbsp;", 12);
    echo "* . 重启apache : sudo service mysqld restart";
}

?>
