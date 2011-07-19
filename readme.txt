=============================================第一部分:参考资料==========================================================================
入门级网站：
    网站建设教程:http://www.w3school.com.cn/
    新手开发手册:http://www.tizag.com/

参考框架：
    1.Drupal：http://drupal.org/handbook
    2.Joomla：http://docs.joomla.org/
    3.SilverStripe:http://doc.silverstripe.org/
    4.CodeIgniter：http://codeigniter.com/user_guide/index.html

国内：
    1.ThinkPHP:http://www.thinkphp.cn/Manual
    2.QEE:http://qeephp.com/，原为FleaPHP
    3.康盛创想产品:http://faq.comsenz.com/development【comsenz】
    4.MyOA:http://www.tongda2000.com/download/document.php#oa

MVC框架：
    Symfony:开发了Yahoo bookmarks
    PHPFUse:http://www.phpfuse.net/
    CakePHP:重构Mambo
    CodeIgniter:开发大型应用
    Zend Framework:IBM用于开发开源ecommerce解决方案：Magento
    Mambo:a full-featured, award-winning content management system that can be used for everything from simple websites to complex corporate applications

Cms:
    2.织梦CMS: http://help.dedecms.com/
    3.PHPWind: http://faq.phpwind.net/
    4.帝国CMS:http://bbs.phome.net/ShowThread/?threadid=18902&forumid=13
    5.symphony:http://symphony-cms.com/    XSLT-powered open source content management system

数据库DAO-DAL框架：
Metabase
PEAR:DB
ADODB:http://phplens.com/lens/adodb/docs-adodb.htm
PDO:
ActiveRecord:
    Doctrine:http://www.doctrine-project.org/
    Php ActiveRecord:http://www.phpactiverecord.org/
    Propel:http://www.propelorm.org

RIA:
    Cross platform For Mobile:http://www.phonegap.com/[http://www.nitobi.com/]

扩展组件:
    PEAR:a framework and distribution system for reusable PHP components.
    PECL:a repository for PHP Extensions, providing a directory of all known extensions and hosting facilities for downloading and development of PHP extensions
    ezComponents:an enterprise ready, general purpose PHP components library

第三方库：
地图导航：
   百度地图:http://openapi.baidu.com/map/index.html
   City8:http://sh.city8.com/api.html

View:
   YAML:http://www.yaml.de/en/home.html(Yet Another Multicolumn Layout)
   CSS　LAYOUT:http://layouts.ironmyers.com/

其他：
mod_rewrite：控制网站导航转向。
    http://httpd.apache.org/docs/2.0/mod/mod_rewrite.html

HtmlEditor:
22个Web在线编辑器:http://paranimage.com/22-online-web-editor/
TinyMce:http://tinymce.moxiecode.com/
KindEditor:http://www.kindsoft.net/index.php
YuiEditor:http://developer.yahoo.com/yui/editor/

校验 Html和Css是否符合规范
    http://validator.w3.org/

学习书：
    Object-oriented programming with php5 [author:Hasin Hayder]

    
推荐开发工具:
    部署：wamp(windows+apache+mysql+php)
    开发：NetBeans + xDebug
          PhpEd + Dbg Debugger
    模板：Flexy|Smarty
=============================================第二部分:框架目录定义=======================================================================
core:框架核心支持文件
data:数据初始化-抓取网上数据，仅供开发测试
db:框架数据库测试数据
library:通用功能模块
module:通用应用模块，如搜索引擎，百度地图等
taglib:自定义标签，您也可以在自己的应用中定义自定义标签     
test:单元测试用例，使用PHPUnit  
tools:开发中通常用到的小工具【需发布在应用中访问url路径使用】    
=============================================第三部分:FAQ========================================================================================    
FAQ:
1.一般来讲：中文字符集都采用UTF-8，但在Ajax发送Json中文数据发现Firefox正常，IE为乱码;
  需要标明：contentType: "application/x-www-form-urlencoded; charset=utf-8",  
  解决方法参考：http://91jquery.com/jQuery/1769.html
  jQuery(form).ajaxSubmit({ 
    url: "ajax.aspx?a=memberlogin", 
    type: "post", 
    dataType: "json", 
    contentType: "application/x-www-form-urlencoded; charset=utf-8", 
    success: showLoginResponse 
    }); 
 