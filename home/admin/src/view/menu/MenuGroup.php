<?php

/**
 +---------------------------------<br/>
 * 菜单分组<br/>
 +---------------------------------
 * @category betterlife
 * @package web.back
 * @subpackage menu
 * @author skygreen
 */
class MenuGroup extends Viewable 
{
    /**
     * 菜单配置文件
     */
    const CONFIG_MENU_FILE="menu.config.xml";
    /**
     * 编号
     * @var string 
     */
    public $id;
    /**
     * 菜单分组名称
     * @var string 
     */
    private $name;
    /**
     * 语言文字种类
     * @var string 
     */
    private $lang;
    /**
     * 菜单分组的图标Css样式
     * @var string 
     */
    private $iconCls;
    /**
     * 分组拥有的菜单列表
     * $key:菜单分组ID,$value:所有的菜单项。
     * @var array 菜单列表
     */
    private $menus;
    /**
     * 所有的菜单配置信息
     */
    private $menuConfigs;

    /**
     * 构造器
     * @param string $id
     * @param string $name 
     */
    public function __construct($id) 
    {
        $this->setId($id);
    }
    
    //<editor-fold defaultstate="collapsed" desc="默认列Setter和Getter">
    private function setId($id)
    {
        $this->id=$id;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setName($name)
    {
        $this->name=$name;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function setIconCls($iconCls)
    {
        $this->iconCls=$iconCls;
    }
    
    public function getIconCls()
    {
        return $this->iconCls;
    }
    
    public function setLang($lang)
    {
        $this->lang=$lang;
    }

    public function getLang()
    {
        return $this->lang;
    }
    
    private function getMenuConfigs()
    {
        if ($this->menuConfigs==null){
            $uri=dirname(__FILE__).DIRECTORY_SEPARATOR.self::CONFIG_MENU_FILE;
            $this->menuConfigs=UtilXmlSimple::fileXmlToObject($uri);        
        }
        return $this->menuConfigs;
    }
    //</editor-fold>
    
    /**
     * 根据菜单分组获取下属所有菜单信息    
     * @param string $name 菜单分组ID
     */
    public function getMenus()
    {
        if (empty($this->menus)||(!is_array($this->menus)))
        {
            $this->getByID();
        }
        return $this->menus;
    }
    
    
    /**
     * 根据菜单分组获取菜单分组信息   
     */
    public function getByID()
    {
       $menuConfig= $this->getMenuConfigs();
       if ($menuConfig!=null)
       {
           $menuGroup = $menuConfig->xpath("//menuGroup[@id='$this->id']");
           if (is_array($menuGroup)&&count($menuGroup)>0)
           {
               $menuGroup=$menuGroup[0];               
               $attributes=$menuGroup->attributes();
               $this->id= $attributes->id."";
               if (empty($this->name))
               {
                    $this->name= $attributes->name."";
               }
               $this->lang= $attributes->lang."";
               $this->iconCls= $attributes->iconCls."";
               
               if (empty($this->menus)||(!is_array($this->menus))){
                   $this->menus=array();
                   foreach ($menuGroup->menu as $menuItem)
                   {
                       $attributes=$menuItem->attributes();
                       $menu=new Menu();
                       $menu->setName($attributes->name."");
                       $address=$attributes->address;
                       if (!startWith($address, "http")){
                           $address=Gc::$url_base.$address;
                       }
                       $menu->setAddress($address."");
                       $menu->setTitle($attributes->title."");
                       $menu->setIconCls($attributes->iconCls."");
                       $menu->setLang($attributes->lang."");
                       $this->menus[]=$menu;
                   }
               }
           }
        }
        unset($this->menuConfigs);
        return $this;
    }
    
    /**
     * 获取所有的MenuGroups             
     */
    public static function all()
    {
        $uri=dirname(__FILE__).DIRECTORY_SEPARATOR.self::CONFIG_MENU_FILE;
        $menuConfigs=UtilXmlSimple::fileXmlToObject($uri);   
        $result=array();
        if ($menuConfigs!=null)
        {
            foreach ($menuConfigs as $menuGroup) 
            {              
                $attributes=$menuGroup->attributes();
                $id= $attributes->id."";
                $menuG=new MenuGroup($id);
                $menuG->getMenus();
                $result[]=$menuG;
            }
        }
        return $result;
    }
    
    /**
    * 只获取菜单分组信息 
    * @param int $returnType 返回类型;0:数据对象,1:数组
    */
    public static function allMenuGroups($returnType=EnumReturnType::DATAOBJECT){
        $uri=dirname(__FILE__).DIRECTORY_SEPARATOR.self::CONFIG_MENU_FILE;
        $menuConfigs=UtilXmlSimple::fileXmlToObject($uri);   
        $result=array();
        if ($menuConfigs!=null)
        {
            foreach ($menuConfigs as $menuGroup) 
            {              
                $attributes=$menuGroup->attributes();
                $id= $attributes->id."";
                if ($returnType==EnumReturnType::DATAOBJECT){
                    $menuG=new MenuGroup($id);
                    $menuG->name=$attributes->name."";
                    $menuG->lang=$attributes->lang."";
                    $menuG->iconCls=$attributes->iconCls."";
                }else{
                    $menuG['id']=$id;  
                    $menuG['name']=$attributes->name."";
                    $menuG['lang']=$attributes->lang."";
                    $menuG['iconCls']=$attributes->iconCls."";
                }
                $result[]=$menuG;
            }
        }
        return $result; 
    }
    
    /**
     * ExtJs菜单显示
     */
    public static function viewForExtJs()
    {
        $uri=dirname(__FILE__).DIRECTORY_SEPARATOR.self::CONFIG_MENU_FILE;
        $menuConfigs=UtilXmlSimple::fileXmlToObject($uri);   
        $result="
                bb.Layout.LeftMenuGroups= [";
        if ($menuConfigs!=null)
        {
            foreach ($menuConfigs as $menuGroup) 
            {              
                $attributes=$menuGroup->attributes();
                $result.="{
                    contentEl:'$attributes->id',
                    title:'$attributes->name',
                    border: false,
                    iconCls: '$attributes->iconCls'},";
                    
            }
        }
        $result=substr($result,0,strlen($result)-1);
        $result.="];";
        return $result;
                
//       $result="
//                bb.Layout.LeftMenuGroups= [{
//                    contentEl: 'nav',
//                    title: '导航区',
//                    border: false,
//                    iconCls: 'nav' // see the HEAD section for style used
//                }, {
//                    title: '设计',
//                    contentEl: 'navdesign', 
//                    border: false,
//                    iconCls: 'navdesign'
//                }, {
//                    title: '系统设置',    
//                    html: '<p>暂无.</p>',
//                    border: false,
//                    iconCls: 'settings'
//                }];
//        ";
//        return $result;
    }
}
?>
