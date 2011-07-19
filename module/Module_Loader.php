<?php

/**
  +---------------------------------<br/>
 * 在这里实现Module模块的加载<br/>
  +---------------------------------
 * @category betterlife
 * @package module
 * @author zhouyuepu
 */
class Module_Loader {
    /**
     * @var 加载Module模块的名称
     */
    const SPEC_NAME="name";
    /**
     * @var yes:加载，no:不加载；如果不定义则代表该Module模块由逻辑自定义开关规则。
     */
    const SPEC_OPEN="open";
    /**
     * @var 加载Module模块的方法
     */
    const SPEC_INIT="init";
    /**
     * @var 是否加载：是
     */
    const OPEN_YES="yes";
    /**
     * @var 是否加载：否
     */
    const OPEN_NO="no";
    /**
     * 加载Module模块的规格Xml文件名。
     */
    const FILE_SPEC_LOAD_MODULE="load.module.xml";    
    /**
     * 加载Module遵循以下规则：
     * 1.加载的库文件应该都放在module目录下以加载Module的名称为子目录的名称内
     * 2.是否加载Module由load.module.xml文件相关规范说明决定。
     * 3.name:加载Module的名称，要求必须是英文和数字。
     * 4.init:加载Module的方法，一般库有一个头文件，该方法由库提供者定义在本文件内。
     * 5.open:是否加载Module。yes:加载，no:不加载，如果不定义则代表该Module由逻辑自定义开关规则。
     * 6.group:若干Module属于同一个解决方案。
     */
    public static function load_run() {
        $spec_module=UtilXmlSimple::fileXmlToArray(dirname(__FILE__).DIRECTORY_SEPARATOR.self::FILE_SPEC_LOAD_MODULE);
        foreach ($spec_module["block"] as $block){
            $blockAttr=$block[Util::XML_ELEMENT_ATTRIBUTES];
            if (array_key_exists(self::SPEC_OPEN, $blockAttr)){
                if (strtolower($blockAttr[self::SPEC_OPEN])==self::OPEN_YES){
                    self::$blockAttr[self::SPEC_INIT]();
                }
            }else{
                self::$blockAttr[self::SPEC_INIT]();
            }
        }
    }
    
    public static function load_communication(){
        //加载数据处理方案的所有目录进IncludePath
        $communication_root_dir=Gc::$nav_root_path.Config_F::ROOT_MODULE.DIRECTORY_SEPARATOR."communication".DIRECTORY_SEPARATOR;
        //加载模块里所有的文件
        load_module(Config_F::ROOT_MODULE,$communication_root_dir);
    }
}

?>
