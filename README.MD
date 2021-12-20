# betterlife

最新代码已经移植到 [betterlife 2](https://github.com/skygreen2001/betterlife)

符合中国开发者思维方式的快速开发的框架，设计初衷快捷、简单、实用。

它包括一套实用的通用模版、后台管理模版、手机模版，并根据项目的需要，按照数据库的设计一键生成定制化的代码框架。

## 安装说明
    * **安装NodeJs**
        下载地址: https://nodejs.org/en/download/
    * **通过Github官网下载**
        官网地址: https://github.com/skygreen2001/betterlife.zero
        * git clone https://github.com/skygreen2001/betterlife.zero.git
        * git clone git@github.com:skygreen2001/betterlife.zero.git
    * **安装Gulp**
      npm install --global gulp
    * **安装目录下运行**
      npm install
      [说明]:安装目录是根目录下的install目录, 即本说明文件目录下
    * **安装目录下运行**
      gulp

## 框架目录定义
    * core    : 框架核心支持文件
    * install : 安装目录
    * library : 通用功能模块
    * module  : 通用应用模块，如通信，条形码等
    * taglib  : 自定义标签，您也可以在自己的应用中定义自定义标签
    * tools   : 开发中通常用到的小工具【需发布在应用中访问url路径使用】
    * home    : 基于MVC模式网站开发实际工作目录
    * document: 框架帮助说明文档
    * misc    : 引用第三方Js、Css、Image、Fonts资源目录

## 参考资料
### 入门级网站：
    * 网站建设教程:http://www.w3school.com.cn/
    * 新手开发手册:http://www.tizag.com/

### 参考框架：
* **English**
    * Drupal：http://drupal.org/handbook
    * SilverStripe:http://doc.silverstripe.org/
    * Lavaral: http://laravel.com/
    * Symfony: http://symphony-cms.com/
    * Yii    : https://www.yiiframework.com/
    * Joomla : http://docs.joomla.org/
    * Phalcon: https://phalcon.io/
    * CodeIgniter: http://codeigniter.com/user_guide/index.html
    * Yaf    : https://www.php.net/manual/zh/book.yaf.php

* **国内**
    * ThinkPHP: http://www.thinkphp.cn/Manual
    * Swoole  : https://www.swoole.com/ 
    * Discuz!:https://discuz.com/
    * MyOA:http://www.tongda2000.com/download/document.php#oa

* **MVC框架**
    * Magento:使用Zend Framwork框架,一套专业开源的电子商务系统
    * CakePHP:重构Mambo
    * Zend Framework:IBM用于开发开源ecommerce解决方案
    * Mambo:a full-featured, award-winning content management system

* **CMS**
    * 织梦CMS: http://www.dedecms.com/
    * 帝国CMS: http://www.phome.net/

* **数据库DAO-DAL框架**
    * Metabase
    * PEAR:DB
    * ADODB:http://phplens.com/lens/adodb/docs-adodb.htm
    * PDO
    * ActiveRecord:
        - Doctrine:http://www.doctrine-project.org/
        - Php ActiveRecord:http://www.phpactiverecord.org/
        - Propel:http://www.propelorm.org

* **包管理工具**
    * composer:http://www.phpcomposer.com/
    * packagist:https://packagist.org/
    * PEAR:http://pear.php.net/
    * PECL:http://pecl.php.net/

### Book
    * Object-oriented programming with php5 [author:Hasin Hayder]

### 代码规范[Code Guide]
    * PHP-fig:http://www.php-fig.org/
    * PHP The right way:http://www.phptherightway.com/#code_style_guide
    * 代码风格指南: https://laravel-china.github.io/php-the-right-way/#code_style_guide
    * Drupal Code Standard:https://www.drupal.org/docs/develop/standards
    * PHP Coding Guidelines & Best Practices:http://flowframework.readthedocs.io/en/stable/TheDefinitiveGuide/PartV/CodingGuideLines/PHP.html
    * Tencent AlloyTeam:http://alloyteam.github.io/CodeGuide/

### 推荐工具
    * 部署：XAMMP(Wamp/Lamp/Mamp) | XAMPP
    * IDE：Atom | Sublime | Brackets | VS Code

### 在线资源
    * Github Tools: https://github.com/integrations
    * codemirror  : http://codemirror.net/

## FAQ
* 一般来讲：中文字符集都采用UTF-8，但在Ajax发送Json中文数据发现Firefox正常，IE为乱码;
*  需要标明：contentType: "application/x-www-form-urlencoded; charset=utf-8",
*  解决方法参考：http://91jquery.com/jQuery/1769.html

   ```
      jQuery(form).ajaxSubmit({
        url: "ajax.aspx?a=memberlogin",
        type: "post",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded; charset=utf-8",
        success: showLoginResponse
      });
   ```
   
* 网站框架访问样式不正常，发现无法正常加载Css或者Gzip；
    - 提示：
      - 内容编码错误
      - 无法显示您尝试查看的页面，因为它使用了无效或者不支持的压缩格式。
    - 回答：
      - 1.清除所有文件带有的BOM.
      - 2.反置PHP.ini里的zlib output compression设置。
      - 3.mysql数据库列字段名不能为desc关键字。
