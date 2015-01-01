<?php

/**
 +---------------------------------<br/>
 * 菜单分组<br/>
 +---------------------------------
 * @category betterlife
 * @package web.back
 * @subpackage menu
 * @author skygreen skygreen2001@gmail.com
 */
class MenuGroup extends Viewable
{
	/**
	 * 菜单配置文件
	 */
	const CONFIG_LEFTMENU_FILE="leftmenu.config.xml";
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
	* 是否需要显示
	* @var bool
	*/
	private $show;
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

	private function setShow($show)
	{
		$this->show=$show;
	}

	public function getShow()
	{
		return $this->show;
	}

	//删除menus方法
	public function delMenus($index)
	{
		unset($this->menus[$index]);
		$this->menus;
	}

	/**
	 * Xml格式存储的文件路径地址
	 */
	public static function address()
	{
		return dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.self::CONFIG_LEFTMENU_FILE;
	}


	private function getMenuConfigs()
	{
		if ($this->menuConfigs==null){
			$uri= Menu::address();
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
						$menu->setId($attributes->id."");
						$menu->setAddress($address."");
						$menu->setTitle($attributes->title."");
						$menu->setIconCls($attributes->iconCls."");
						$menu->setLang($attributes->lang."");
						$menu->setFor($attributes->for."");
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
		$uri=Menu::address();
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
	 * 获取所有的MenuGroups for json
	 */
	public static function allforjson()
	{
		$uri=Menu::address();
		$menuConfigs=UtilXmlSimple::fileXmlToObject($uri);
		$result=array();
		if ($menuConfigs!=null)
		{
			foreach ($menuConfigs as $menuGroup)
			{
				$attributes=$menuGroup->attributes();
				$id= $attributes->id."";
				$menuG="";
				$attributes=$menuGroup->attributes();
				$menuG->name=$attributes->name."";
				$address=$attributes->address;
				if (!startWith($address, "http")){
					$address=Gc::$url_base.$address;
				}
				$menuG->id=$attributes->id."";
				$menuG->address=$address."";
				$menuG->title=$attributes->title."";
				$menuG->iconCls=$attributes->iconCls."";
				$menuG->lang=$attributes->lang."";
				$menuG->for=$attributes->for."";
				foreach($menuGroup->menu as $menus){
					$menu="";
					$attributes=$menus->attributes();
					$menu->name=$attributes->name."";
					$address=$attributes->address;
					if (!startWith($address, "http")){
						$address=Gc::$url_base.$address;
					}
					$menu->id=$attributes->id."";
					$menu->address=$address."";
					$menu->title=$attributes->title."";
					$menu->iconCls=$attributes->iconCls."";
					$menu->lang=$attributes->lang."";
					$menu->for=$attributes->for."";
					$menuG->menus[]=$menu;
				}
				$result[]=$menuG;
			}
		}
		return $result;
	}

	//得到角色对应菜单的id
	public static function allroleMenuId($roletype)
	{
		$uri=self::address();
		$rolemenuConfigs=UtilXmlSimple::fileXmlToObject($uri);

		$roletype += 1;
		$menu_ids = $rolemenuConfigs->xpath("//roleplayer[$roletype]//menu//@id");

		$_menu_ids = array();
		foreach ($menu_ids as $v) {
		 $_menu_ids[] = (string)$v;
		}

		return $_menu_ids;
	}

	/**
	 * 获取所有角色的MenuGroups
	 */
	public static function allrole()
	{
		$uri=self::address();
		$rolemenuConfigs=UtilXmlSimple::fileXmlToObject($uri);
		$role=array();
		if ($rolemenuConfigs!=null)
		{
			foreach ($rolemenuConfigs as $rolemenuGroup)
			{
				$result=array();
				$roleattributes=$rolemenuGroup->attributes();
				$roleid= $roleattributes->id."";
				$menuConfigs=$rolemenuGroup->menuGroups;
				$menuGroups=$menuConfigs->menuGroup;
				foreach($menuGroups as $menuGroup){
					$attributes=$menuGroup->attributes();
					$id= $attributes->id."";
					$menuG=new MenuGroup($id);
					$menuG->name=$attributes->name."";
					$address=$attributes->address;
					if (!startWith($address, "http")){
						$address=Gc::$url_base.$address;
					}
					$menuG->id=$attributes->id."";
					$menuG->address=$address."";
					$menuG->title=$attributes->title."";
					$menuG->iconCls=$attributes->iconCls."";
					$menuG->lang=$attributes->lang."";
					$menuG->for=$attributes->for."";
					foreach($menuGroup->menu as $menus){
						$attributes=$menus->attributes();
						$menu=new Menu();
						$menu->setName($attributes->name."");
						$address=$attributes->address;
						if (!startWith($address, "http")){
							$address=Gc::$url_base.$address;
						}
						$menu->setId($attributes->id."");
						$menu->setAddress($address."");
						$menu->setTitle($attributes->title."");
						$menu->setIconCls($attributes->iconCls."");
						$menu->setLang($attributes->lang."");
						$menu->setFor($attributes->for."");
						$menuG->menus[]=$menu;
					}
					$result[]=$menuG;
				}
				$role[$roleid]=$result;
			}
		}
		return $role;
	}

	/**
	* 只获取菜单分组信息
	* @param int $returnType 返回类型;0:数据对象,1:数组
	*/
	public static function allMenuGroups($returnType=EnumReturnType::DATAOBJECT)
	{
		$uri=Menu::address();
		$menuConfigs=UtilXmlSimple::fileXmlToObject($uri);
		$result=array();
		if ($menuConfigs!=null)
		{
			foreach ($menuConfigs as $menuGroup)
			{
				$attributes=$menuGroup->attributes();
				$id= $attributes->id."";
				$lang=$attributes->lang."";
				if (empty($lang)){
					$lang="cn";
				}
				if ($returnType==EnumReturnType::DATAOBJECT){
					$menuG=new MenuGroup($id);
					$menuG->name=$attributes->name."";
					$menuG->lang=$lang;
					$menuG->iconCls=$attributes->iconCls."";
				}else{
					$menuG['id']=$id;
					$menuG['name']=$attributes->name."";
					$menuG['lang']=$lang;
					$menuG['iconCls']=$attributes->iconCls."";
				}
				$result[]=$menuG;
			}
		}
		return $result;
	}

	/**
	 * 菜单总计数
	 * @param object|string|array $filter<br/>
	 *		$filter 格式示例如下：<br/>
	 *			0.允许对象如new User(id="1",name="green");<br/>
	 *			1."id=1","name='sky'"<br/>
	 *			2.array("id=1","name='sky'")<br/>
	 *			3.array("id"=>"1","name"=>"sky")
	 * @return 菜单总计数
	 */
	public static function count($filter=null)
	{
		$uri=Menu::address();
		$menuConfigs=UtilXmlSimple::fileXmlToObject($uri);
		$result=array();
		if ($menuConfigs!=null)
		{
			foreach ($menuConfigs as $menuGroup)
			{
				$attributes=$menuGroup->attributes();
				$id= $attributes->id."";
				$menuG=new MenuGroup($id);
				$menus=$menuG->getMenus();
				foreach ($menus as $menu){
					$menu=UtilObject::object_to_array($menu);
					if (XmlObject::isValidData($menu,$filter)){
						$result[]=$menu;
					}
				}
			}
		}
		return count($result);
	}

	/**
	 * 菜单对象分页
	 * @param string $xmlObject_classname 具体的Xml对象类名
	 * @param int $startPoint	分页开始记录数
	 * @param int $endPoint	分页结束记录数
	 * @param string|array $filter 过滤条件
	 * 示例如下：<br/>
	 *		string[只有一个查询条件]
	 *		1. id="1"--精确查找
	 *		2. name contain 'sky'--模糊查找
	 *		array[多个查询条件]
	 *		1.array("id"=>"1","name"=>"sky")<br/>--精确查找
	 *		2.array("id"=>"1","name contain 'sky'")<br/>--模糊查找
	 * @return mixed 对象分页
	 */
	public static function queryPage($startPoint,$endPoint,$filter=null)
	{
		$uri=Menu::address();
		$menuConfigs=UtilXmlSimple::fileXmlToObject($uri);
		$result=array();
		if ($menuConfigs!=null)
		{
			foreach ($menuConfigs as $menuGroup)
			{
				$attributes=$menuGroup->attributes();
				$id= $attributes->id."";
				$menuG=new MenuGroup($id);
				$menus=$menuG->getMenus();
				foreach ($menus as $menu){
					$menu=UtilObject::object_to_array($menu);
					if (XmlObject::isValidData($menu,$filter)){
						$result[]=$menu;
					}
				}
			}
		}
		$result=array_slice($result, $startPoint, $endPoint);
		return $result;
	}

	/**
	 * ExtJs菜单显示
	 */
	public static function viewForExtJs()
	{
		//生成ExtJs左侧主菜单，若是没有进入主菜单下子菜单的权限，则不予显示
		$admin=HttpSession::get("admin");
		$roletype=$admin->roletype;
		if($roletype==EnumRoletype::SUPERADMIN){
			$uri=Menu::address();
			$menuConfigs=UtilXmlSimple::fileXmlToObject($uri);
		}else{
			$uri=self::address();
			$menuConfigs=UtilXmlSimple::fileXmlToObject($uri);
			$roletype=EnumRoletype::roletypeEnumKey($roletype);
			$menuConfigs = $menuConfigs->xpath("//roleplayer[@id='$roletype']");
			$menuConfigs = $menuConfigs[0]->menuGroups;
			$menuConfigs=$menuConfigs->menuGroup;
			if(!array_key_exists("@attributes", $menuConfigs)){
				$uri=Menu::address();
				$menuConfigs=UtilXmlSimple::fileXmlToObject($uri);
			}
		}

		$result=Gc::$appName_alias.".Layout.LeftMenuGroups= [\r\n";
		if ($menuConfigs!=null)
		{
			foreach ($menuConfigs as $menuGroup)
			{
				$attributes=$menuGroup->attributes();
				//判断是否拥有进入子菜单的权限
				foreach ($menuGroup->menu as $menu)
				{
					$result.="{
						contentEl:'$attributes->id',
						title:'$attributes->name',
						border: false,
						iconCls: '$attributes->iconCls'},";
					break;
				}
			}
		}
		if (contain($result,"contentEl")){
			$result=substr($result,0,strlen($result)-1);
		}
		$result.="];";
		if (Gc::$is_online_optimize){
			$result=UtilString::online_optimize($result);
		}
		return $result;
	}

}
?>
