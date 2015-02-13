# 工具类

工具类

路径    :core/util/


### 工具集
工具集列表如下
* common:常用工具类目录
        -.UtilArray.php:数组
        -.UtilString.php:字符串
        -.UtilExcel.php:Excel
        -.UtilFileSystem.php:文件目录操作
        -.UtilImage.php:图像处理
        -.UtilNumber.php:数字处理
        -.UtilPage.php:分页［与自定义标签配合使用］
        -.UtilPinyin.php:中文转换成拼音工具类
        -.UtilObject.php:对象类管理
        -.UtilReflection.php:类反射注入
        -.UtilWatermark.php:图片水印工具类
        -.UtilZipfile.php:将多个文件压缩成zip文件的工具类
              解决在Windows服务器、Linux服务器、Windows桌面、、Linux桌面上的中文文件名称的问题。

* datetime:日期时间类目录
        -.UtilDateTime.php:处理通用的日期时间工具类
        -.UtilDateFestival.php:处理节假日的工具类
        -.UtilDateLunar.php:处理阴阳历的工具类
* config:配置类目录
        -.ini:读取ini文件配置
        -.php:读取php变量配置
        -.xml:读取xml文件配置
        -.yaml:读取yaml配置
        -.json:读取json变量配置
* email:电子邮件类目录
        -.UtilEmailer.php:邮件发送
            推荐使用这种方式
        -.UtilEmailSmtp.php:使用Smtp发送邮件
            可以发送附件,比较复杂
        -.UtilEmailPhp.php:这个功能用在Linux上
            需要配合Sendmail使用
* view:显示工具类目录
        -.ajax:支持动态加载Ajax库
            包括Jquery、extjs、dojo、prototype、mootools、yui、scriptaculous、protaculous等
        -.onlineditor:支持在线编辑器。
            包括UEditor、CKEditor、KindEditor、Xheditor
        -.UtilCss.php:服务器端控制加载Css库
        -.UtilJavascript.php:加载Javascript库和发送请求
* xml:XML处理工具类目录
        -.UtilXmlDom:采用Dom方式处理Xml
        -.UtilXMLLib:XML和Array的转换
        -.UtilXmlObject:基于对象的增删改查XML节点数据项
        -.UtilXmlSimple:采用SimpleXML处理Xml
* mobile:手机工具类目录
* net:网络工具类目录
* ucenter:与Discuz的Ucenter整合工具类目录
