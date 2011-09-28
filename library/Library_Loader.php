<?php
/**
 +------------------------------------------------<br/>
 * 在这里实现第三方库的加载<br/>
 +------------------------------------------------
 * @category betterlife
 * @package library
 * @author zhouyuepu
 */
class Library_Loader 
{
    /**
     * @var 加载库的标识
     */
    const SPEC_ID="id";    
    /**
     * @var 加载库的名称
     */
    const SPEC_NAME="name";
    /**
     * @var yes:加载，no:不加载；如果不定义则代表该库由逻辑自定义开关规则。
     */
    const SPEC_OPEN="open";
    /**
     * @var 加载库的方法
     */
    const SPEC_INIT="init";
    /**
     * @var 是否必须加载的
     */
    const SPEC_REQUIRED="required";
    /**
     * @var 是否加载：是
     */
    const OPEN_YES="true";
    /**
     * @var 是否加载：否
     */
    const OPEN_NO="false";
    /**
     * 模板库的加载目录
     */
    const DIR_TEMPLATE="template";
    /**
     * 加载库的规格Xml文件名。
     */
    const FILE_SPEC_LOAD_LIBRARY="load.library.xml";    
    /**
     * 加载库遵循以下规则：<br/>
     * 1.加载的库文件应该都放在library目录下以加载库的名称为子目录的名称内<br/>
     * 2.是否加载库由load.library.xml文件相关规范说明决定。<br/>
     * 3.name:加载库的名称，要求必须是英文和数字。<br/>
     * 4.init:加载库的方法，一般库有一个头文件，该方法由库提供者定义在本文件内。<br/>
     * 5.open:是否加载库。true:加载，false:不加载；如果不定义则代表该库由逻辑自定义开关规则。<br/>
     * 6.required:是否必须加载的，如无定义，则根据open定义加载库。<br/>
     */
    public static function load_run() 
    {
        $spec_library=UtilXmlSimple::fileXmlToArray(dirname(__FILE__).DIRECTORY_SEPARATOR.self::FILE_SPEC_LOAD_LIBRARY);
        foreach ($spec_library["resourceLibrary"] as $block){
            $blockAttr=$block[Util::XML_ELEMENT_ATTRIBUTES];
            if (array_key_exists(self::SPEC_REQUIRED, $blockAttr)){
                if (strtolower($blockAttr[self::SPEC_REQUIRED])=='true'){
                    $method=$blockAttr[self::SPEC_INIT];
                    if (method_exists(__CLASS__, $method)){
                        self::$method();
                    }
                }
            }else{
                if (array_key_exists(self::SPEC_OPEN, $blockAttr)){
                    if (strtolower($blockAttr[self::SPEC_OPEN])==self::OPEN_YES){
                        $method=$blockAttr[self::SPEC_INIT];
                        if (method_exists(__CLASS__, $method)){
                            self::$method();
                        }                        
                    }
                }
            }
        }
    }

    /**
     * @return string 返回库所在目录路径
     */
    private static function dir_library() 
    {
        return Gc::$nav_root_path.Config_F::ROOT_LIBRARY.DIRECTORY_SEPARATOR;
    }

    /**
     * 加载MDB2库
     */
    private static function load_mdb2()
    {
        $dir_library_mdb2="mdb2".DIRECTORY_SEPARATOR;
        include(self::dir_library().$dir_library_mdb2.'MDB2.php');
        set_include_path(get_include_path().PATH_SEPARATOR.self::dir_library().$dir_library_mdb2);
    }
    
    /**
     * 加载ADODE库
     */
    private static function load_adode() 
    {
        $dir_library_adodb="adodb5".DIRECTORY_SEPARATOR;
        include(self::dir_library().$dir_library_adodb.'adodb.inc.php');
    }

    /**
     * 加载PHPUnit库
     */
    private static function load_phpunit() 
    {
        $dir_library_phpunit="PHPUnit".DIRECTORY_SEPARATOR;
        set_include_path(get_include_path().PATH_SEPARATOR.self::dir_library());
        set_include_path(get_include_path().PATH_SEPARATOR.self::dir_library().$dir_library_phpunit);
    }

    /**
     * 加载PHPExcel库<br/>
     * PHPExcel库：可解析Excel，PDF，CSV文件内容<br/>
     * PHPExcel解决内存占用过大问题-设置单元格对象缓存<br/>
     * @link http://luchuan.iteye.com/blog/985890
     */
    private static function load_phpexcel() 
    {
        $dir_library_phpexcel="phpexcel".DIRECTORY_SEPARATOR;
        $class_phpexcel="PHPExcel.php";
        require_once(self::dir_library().$dir_library_phpexcel.$class_phpexcel);
        require_once(self::dir_library().$dir_library_phpexcel.'PHPExcel'.DIRECTORY_SEPARATOR.'Writer'.DIRECTORY_SEPARATOR.'Excel2007.php');
    }

    /**
     * PHPExcel自动加载对象
     */
    public static function load_phpexcel_autoload($pObjectName) 
    {
        if ((class_exists($pObjectName)) || (strpos($pObjectName, 'PHPExcel') === False)) {
            return false;
        }
        $pObjectFilePath =PHPEXCEL_ROOT.str_replace('_',DIRECTORY_SEPARATOR,$pObjectName). '.php';
        if ((file_exists($pObjectFilePath) === false) || (is_readable($pObjectFilePath) === false)) {
            return false;
        }
        require($pObjectFilePath);
        return true;
    }

    /**
     * 加载特定的模板类库文件
     */
    private static function load_template() 
    {
        if (Gc::$template_mode==View::TEMPLATE_MODE_SMARTY) {
            self::load_template_smarty();
        }else if (Gc::$template_mode==View::TEMPLATE_MODE_SMARTTEMPLATE) {
            self::load_template_smartytemplate();
        }else if (Gc::$template_mode==View::TEMPLATE_MODE_EASETEMPLATE) {
            self::load_template_easetemplate();
        }else if (Gc::$template_mode==View::TEMPLATE_MODE_TEMPLATELITE) {
            self::load_template_templatelite();
        }else if (Gc::$template_mode==View::TEMPLATE_MODE_FLEXY){
            self::load_template_flexy();
        }
    }

    /**
    * 加载单点登录类库
    * @see http://code.google.com/p/oauth-php/downloads/list[下载oauth]
    * @see http://code.google.com/p/oauth-php/wiki/ConsumerHowTo#Two-legged_OAuth[文档oauth]    
    */
    private static function load_sso()
    {
        if (Gc::$sso_method=="oauth"){
            
        }else if (Gc::$sso_method=="openid"){
        
        }             
    }
    
    /**
     * 加载Smarty模板库
     * @see http://www.smarty.net/
     */
    private static function load_template_smarty() 
    {
        $dir_template_smarty="Smarty";
        $file_template_smarty="Smarty.class.php";
        require_once self::dir_library().self::DIR_TEMPLATE.DIRECTORY_SEPARATOR.$dir_template_smarty.DIRECTORY_SEPARATOR.$file_template_smarty;
    }

    /**
     * 加载SmartTemplate模板库
     * @see http://quickskin.worxware.com/
     */
    private static function load_template_smartytemplate() 
    {
        $dir_template_smarty="SmartTemplate";
        $file_template_smarty="class.quickskin.php";
        require_once self::dir_library().self::DIR_TEMPLATE.DIRECTORY_SEPARATOR.$dir_template_smarty.DIRECTORY_SEPARATOR.$file_template_smarty;
    }

    /**
     * 加载EaseTemplate模板库
     * @see http://www.systn.com/data/et/1.html
     */
    private static function load_template_easetemplate() 
    {
        $dir_template_smarty="EaseTemplate";
        $file_template_smarty="template.ease.php";
        require_once self::dir_library().self::DIR_TEMPLATE.DIRECTORY_SEPARATOR.$dir_template_smarty.DIRECTORY_SEPARATOR.$file_template_smarty;
    }

    /**
     * 加载TemplateLite模板库
     * @see http://templatelite.sourceforge.net/
     */
    private static function load_template_templatelite() 
    {
        $dir_template_smarty="TemplateLite";
        $file_template_smarty="class.template.php";
        require_once self::dir_library().self::DIR_TEMPLATE.DIRECTORY_SEPARATOR.$dir_template_smarty.DIRECTORY_SEPARATOR.$file_template_smarty;
    }
    /**
    *  加载Flexy模板库
    *  @see http://pear.php.net/package/HTML_Template_Flexy
    */
    private static function load_template_flexy() 
    {
        $dir_template_flexy="Flexy";
        $file_template_smarty="Flexy.php";
        set_include_path(get_include_path().PATH_SEPARATOR.self::dir_library().self::DIR_TEMPLATE.DIRECTORY_SEPARATOR.$dir_template_flexy); 
        $sub_dir_flexy="HTML/Template";
        require_once self::dir_library().self::DIR_TEMPLATE.DIRECTORY_SEPARATOR.$dir_template_flexy.DIRECTORY_SEPARATOR.$sub_dir_flexy.DIRECTORY_SEPARATOR.$file_template_smarty; 
        
    }
    /**
    * 加载YAML-Spyc库
    * Spyc is a YAML loader/dumper written in pure PHP. 
    * Given a YAML document, Spyc will return an array
    * @see http://code.google.com/p/spyc/
    * 
    */
    private static function load_yaml_spyc()
    {
        require_once self::dir_library()."yaml".DIRECTORY_SEPARATOR."spyc.php";
    }
    
    private static function load_PHPLinq()
    {
        set_include_path(get_include_path().PATH_SEPARATOR.self::dir_library()."linq".DIRECTORY_SEPARATOR. 'Classes'.DIRECTORY_SEPARATOR);
        require_once  self::dir_library()."linq".DIRECTORY_SEPARATOR. 'Classes'.DIRECTORY_SEPARATOR.'PHPLinq'.DIRECTORY_SEPARATOR."LinqToObjects.php";
    }
}
?>
