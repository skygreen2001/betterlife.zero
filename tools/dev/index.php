<?php
require_once ("../../init.php");
/**
 * 重用类型
 */
class EnumReusePjType extends Enum
{
	/**
	 * 完整版【同现有版本一样】
	 */
	const FULL	= 1;
	/**
	 * 通用版【后台使用Jquery框架】
	 */
	const LIKE	= 2;
	/**
	 * 高级版【后台使用Extjs框架】
	 */
	const HIGH	= 3;
	/**
	 * 精简版【只包括框架核心】
	 */
	const MINI	= 4;
}

/**
 * Web项目代码重用
 * 在项目开发中，往往商业模式是可以重用的
 * 只要在原有的代码基础上稍作修改即可，一般不需要高级开发者花费太多的时间
 * 在公司运作中，只需初级开发者找到文字修改或者换肤即可很快重用代码变身成新的项目
 * 本开发工具提供图像化界面方便开发者快速重用现有代码生成新的项目
 * 输入:
 *		项目路径|项目名称【中文-英文】|项目别名
 *		重用类型
 *			1.完整版【同现有版本一样】
 *			2.通用版【后台使用Jquery框架】
 *			3.高级版【后台使用Extjs框架】
 *			4.精简版【只包括框架核心】
 * 处理流程操作:
 *		1.复制整个项目到新的路径
 *		2.修改Gc.php相关配置
 *		3.修改Config_Db.php[数据库名称|数据库表名前缀]
 *		4.修改帮助地址
 *		5.修改应用文件夹名称
 *      6.重命名后台Action_Betterlife为新应用类
 *      7.替换Extjs的js文件里的命名空间
 *      精简版还执行了以下操作
 *			1.清除在大部分项目中不需要的目录
 *			2.清除在大部分项目中不需要的文件
 *			3.清除library下的不常用的库:
 *				adodb5|linq|mdb2|PHPUnit|yaml|template[EaseTemplate|SmartTemplate|TemplateLite]
 *			4.清除缓存相关的文件
 *      	5.清除mysql|sqlite|postgres以外的其他数据库引擎
 *			6.清除module大部分工程无需的文件
 *			7.清除tools大部分工程无需的文件
 *			8.清除common大部分工程无需的文件
 *          9.清除util大部分工程无需的文件
 * @author skygreen2001@gmail.com
 */
class Project_Refactor
{
	/**
	 * 重用类型
	 */
	public static $reuse_type=EnumReusePjType::FULL;
	/**
	 * 保存新Web项目路径
	 */
	public static $save_dir="";
	/**
	 * 新Web项目名称【中文】
	 */
	public static $pj_name_cn="";
	/**
	 * 新Web项目名称【英文】
	 */
	public static $pj_name_en="";
	/**
	 * 新Web项目名称别名【最好两个字母,头字母大写】
	 */
	public static $pj_name_alias="";
	/**
	 * 数据库名称
	 */
	public static $db_name="";
	/**
	 * 数据库表名前缀
	 */
	public static $table_prefix="";
	/**
	 * Git版本地址
	 */
	public static $git_name="";
	/**
	 * 需要忽略的目录【在大部分的项目中都不会用到】
	 */
	public static $ignore_dir=array(
		"api",
		"attachment",
		"data",
		"document",
		"nbproject",
		"phpext",
		"test",
		"log",
		"model",
		"upload",
		".settings",
		"_notes"
	);
	/**
	 * 需要忽略的文件【在大部分的项目中都不会用到】
	 */
	public static $ignore_files=array(
		"faq.txt",
		".gitignore",
		".project",
		"dw_php_codehinting.config",
		"unlock.cron"
	);

	/**
	 * 清除无关的目录
	 */
	private static function IgnoreDir()
	{
		foreach (self::$ignore_dir as $ignore_dir) {
			$toDeleteDir=self::$save_dir.$ignore_dir;
			if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
		}

		if(is_dir(self::$save_dir.Gc::$module_root.DS."business"))
			UtilFileSystem::deleteDir(self::$save_dir.Gc::$module_root.DS."business");

		if(is_dir(self::$save_dir.Gc::$module_root.DS."admin")){
			$toDeleteDir=self::$save_dir.Gc::$module_root.DS."admin".DS."src".DS."remoteobject".DS;
			UtilFileSystem::deleteDir($toDeleteDir);
			$toDeleteDir=self::$save_dir.Gc::$module_root.DS."admin".DS."view".DS."default".DS."tmp".DS."templates_c".DS;
			UtilFileSystem::deleteDir($toDeleteDir);
			UtilFileSystem::createDir($toDeleteDir);
		}

		if(is_dir(self::$save_dir."data")){
			UtilFileSystem::deleteDir(self::$save_dir."data".DS."spider");
			UtilFileSystem::deleteDir(self::$save_dir."data".DS."uc_client");
		}
	}

	/**
	 * 清除无关的文件
	 */
	private static function IgnoreFiles()
	{
		foreach (self::$ignore_files as $ignore_file) {
			$toDeleteFile=self::$save_dir.$ignore_file;
			if(file_exists($toDeleteFile))unlink($toDeleteFile);
		}
	}

	/**
	 * 清除清除library下的不常用的库
	 */
	private static function IgnoreLibraryUnused()
	{
		$ignore_library_dirs=array(
			"adodb5",
			"linq",
			"yaml",
			"mdb2",
			"PHPUnit"
		);
		$ignore_library_template_dirs=array(
			"EaseTemplate",
			"Flexy",
			"SmartTemplate",
			"TemplateLite"
		);
		foreach ($ignore_library_dirs as $ignore_library_dir) {
			$toDeleteDir=self::$save_dir.Config_F::ROOT_LIBRARY.DS.$ignore_library_dir;
			if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
		}
		foreach ($ignore_library_template_dirs as $ignore_library_template_dir) {
			$toDeleteDir=self::$save_dir.Config_F::ROOT_LIBRARY.DS.Library_Loader::DIR_TEMPLATE.DS.$ignore_library_template_dir;
			if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
		}

		//去除Library:PhpExcel的占空间的文件
		$phpExcelDir=self::$save_dir.Config_F::ROOT_LIBRARY.DS."phpexcel".DS."PHPExcel".DS;
		$toDeleteDir=$phpExcelDir."Shared".DS."PCLZip";
		if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
		$toDeleteDir=$phpExcelDir."Shared".DS."PDF";
		if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);

		$library_loader_content=<<<LIBRARY_LOAD
<?php
/**
 +------------------------------------------------<br/>
 * 在这里实现第三方库的加载<br/>
 +------------------------------------------------
 * @category betterlife
 * @package library
 * @author skygreen2001@gmail.com
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
		\$spec_library=UtilXmlSimple::fileXmlToArray(dirname(__FILE__).DS.self::FILE_SPEC_LOAD_LIBRARY);
		foreach (\$spec_library["resourceLibrary"] as \$block){
			\$blockAttr=\$block[Util::XML_ELEMENT_ATTRIBUTES];
			if (array_key_exists(self::SPEC_REQUIRED, \$blockAttr)){
				if (strtolower(\$blockAttr[self::SPEC_REQUIRED])=='true'){
					\$method=\$blockAttr[self::SPEC_INIT];
					if (method_exists(__CLASS__, \$method)){
						self::\$method();
					}
				}
			}else{
				if (array_key_exists(self::SPEC_OPEN, \$blockAttr)){
					if (strtolower(\$blockAttr[self::SPEC_OPEN])==self::OPEN_YES){
						\$method=\$blockAttr[self::SPEC_INIT];
						if (method_exists(__CLASS__, \$method)){
							self::\$method();
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
		return Gc::\$nav_root_path.Config_F::ROOT_LIBRARY.DS;
	}

	/**
	 * 加载PHPExcel库<br/>
	 * PHPExcel库：可解析Excel，PDF，CSV文件内容<br/>
	 * PHPExcel解决内存占用过大问题-设置单元格对象缓存<br/>
	 * @link http://luchuan.iteye.com/blog/985890
	 */
	private static function load_phpexcel()
	{
		\$dir_library_phpexcel="phpexcel".DS;
		\$class_phpexcel="PHPExcel.php";
		require_once(self::dir_library().\$dir_library_phpexcel.\$class_phpexcel);
		require_once(self::dir_library().\$dir_library_phpexcel.'PHPExcel'.DS.'Writer'.DS.'Excel2007.php');
	}

	/**
	 * PHPExcel自动加载对象
	 */
	public static function load_phpexcel_autoload(\$pObjectName)
	{
		if ((class_exists(\$pObjectName)) || (strpos(\$pObjectName, 'PHPExcel') === False)) {
			return false;
		}
		\$pObjectFilePath =PHPEXCEL_ROOT.str_replace('_',DIRECTORY_SEPARATOR,\$pObjectName). '.php';
		if ((file_exists(\$pObjectFilePath) === false) || (is_readable(\$pObjectFilePath) === false)) {
			return false;
		}
		require(\$pObjectFilePath);
		return true;
	}

	/**
	 * 加载特定的模板类库文件
	 */
	private static function load_template()
	{
		switch (Gc::\$template_mode) {
			case View::TEMPLATE_MODE_SMARTY:
				self::load_template_smarty();
			break;
		}
		if (isset(Gc::\$template_mode_every)){
			foreach (Gc::\$template_mode_every as \$value) {
				switch (\$value) {
					case View::TEMPLATE_MODE_SMARTY:
						self::load_template_smarty();
					break;
				}
			}
		}
	}

	/**
	 * 加载Smarty模板库
	 * @see http://www.smarty.net/
	 */
	private static function load_template_smarty()
	{
		\$dir_template_smarty="Smarty";
		\$file_template_smarty="Smarty.class.php";
		require_once self::dir_library().self::DIR_TEMPLATE.DS.\$dir_template_smarty.DS.\$file_template_smarty;
	}
}
?>

LIBRARY_LOAD;
		$library_loader_file=self::$save_dir.Config_F::ROOT_LIBRARY.DS."Library_Loader.php";
		file_put_contents($library_loader_file, $library_loader_content);

		$library_xml_config_content=<<<LIBRARY_XML_CONTENT
<?xml version="1.0" encoding="UTF-8"?>
<!--
	/**
	 * 加载库遵循以下规则：
	 * 1.加载的库文件应该都放在library目录下以加载库的名称为子目录的名称内
	 * 2.是否加载库由load.library.xml文件相关规范说明决定。
	 * 3.name:加载库的名称，要求必须是英文和数字。
	 * 4.init:加载库的方法，一般库有一个头文件，该方法由库提供者定义在本文件内。
	 * 5.open:是否加载库。true:加载，false:不加载，如果不定义则代表该库由逻辑自定义开关规则。
	 * 6.required:是否必须加载的，如无定义，则根据open定义加载库。
	 */
 -->
<resourceLibrarys>
	<resourceLibrary id="1" name="template" required="true" init="load_template" />
	<resourceLibrary id="2" name="phpexcel" open="true" init="load_phpexcel" />
</resourceLibrarys>
LIBRARY_XML_CONTENT;
		$library_xml_config_file=self::$save_dir.Config_F::ROOT_LIBRARY.DS."load.library.xml";
		file_put_contents($library_xml_config_file, $library_xml_config_content);
	}

	/**
	 * 清除缓存相关的文件
	 * 1.清除配置文件:config/cache
	 * 2.清除缓存引擎文件:core/cache
	 * 3.清除tools里缓存相关的工具类
	 */
	private static function IgnoreCache()
	{
		$root_config="config";
		$root_core="core";
		$root_tools="tools";
		//1.清除配置文件:config/cache
		$ignore_config_cache_dir=self::$save_dir.$root_config.DS."config".DS."cache".DS;
		if(is_dir($ignore_config_cache_dir))UtilFileSystem::deleteDir($ignore_config_cache_dir);

		//2.清除缓存引擎文件:core/cache
		$ignore_core_cache_dir=self::$save_dir.$root_core.DS."cache".DS;
		if(is_dir($ignore_core_cache_dir))UtilFileSystem::deleteDir($ignore_core_cache_dir);

		//3.清除tools里缓存相关的工具类
		$ignore_tools_cache_dir=self::$save_dir."tools".DS."cache".DS;
		if(is_dir($ignore_tools_cache_dir))UtilFileSystem::deleteDir($ignore_tools_cache_dir);
	}

	/**
	 * 清除除Mysql以外的其他数据库引擎文件
	 * 1.配置文件:config/db
	 * 2.数据库引擎文件:core/db/
	 * 3.数据库备份:db/
	 * 4.修改Manager_Db.php文件
	 */
	private static function IgnoreAllDbEngineExceptMysql()
	{
		$root_config="config";
		//1.清除配置文件:config/db
		$ignore_config_db_dir=self::$save_dir.$root_config.DS."config".DS."db".DS;
		UtilFileSystem::deleteDir($ignore_config_db_dir."dal".DS);
		unlink($ignore_config_db_dir."object".DS."Config_Mssql.php");
		unlink($ignore_config_db_dir."object".DS."Config_Odbc.php");

		//2.数据库引擎文件:core/db/
		$root_core="core";
		$ignore_core_db_dir=self::$save_dir.$root_core.DS."db".DS;
		UtilFileSystem::deleteDir($ignore_core_db_dir."dal".DS);
		UtilFileSystem::deleteDir($ignore_core_db_dir."object".DS."odbc".DS);
		UtilFileSystem::deleteDir($ignore_core_db_dir."object".DS."sqlserver".DS);

		//3.数据库备份:db/
		$ignore_db_dirs=array(
			"microsoft access",
			"postgres",
			"sqlite",
			"sqlserver"
		);
		foreach ($ignore_db_dirs as $ignore_db_dir) {
			$toDeleteDir=self::$save_dir."db".DS.$ignore_db_dir;
			if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
		}

		//4.修改Manager_Db.php文件
		$manager_db_content=<<<MANAGEDB
<?php
/**
 +---------------------------------<br/>
 * 数据库操作管理<br/>
 * 所有的数据库都通过这里进行访问<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.db
 * @author skygreen <skygreen2001@gmail.com>
 */
class Manager_Db extends Manager {
	/**
	 * @var IDao 默认Dao对象，采用默认配置
	 */
	private \$dao_static;
	/**
	 * @var mixed 实时指定的Dao或者Dal对象，实时注入配置
	 */
	private \$dao_dynamic;
	/**
	 * @var mixed 当前使用的Dao或者Dal对象
	 */
	private \$currentdao;
	/**
	 * @var IDbInfo 获取数据库表信息对象
	 */
	private \$dbinfo_static;
	/**
	 * @var Manager_Db 当前唯一实例化的Db管理类。
	 */
	private static \$instance;

	/**
	 * 构造器
	 */
	private function __construct() {
	}

	public static function singleton() {
		return newInstance();
	}

	/**
	 * 单例化
	 * @return Manager_Db
	 */
	public static function newInstance() {
		if (!isset(self::\$instance)) {
			\$c = __CLASS__;
			self::\$instance=new \$c();
		}
		return self::\$instance;
	}

	/**
	 * 返回当前使用的Dao
	 * @return mixed 当前使用的Dao
	 */
	public function currentdao(){
		if (\$this->currentdao==null){
			\$this->dao();
		}
		return \$this->currentdao;
	}

	/**
	 * 全局设定一个Dao对象；
	 * 由开发者配置设定对象决定
	 */
	public function dao() {
		switch (Config_Db::\$db) {
			case EnumDbSource::DB_MYSQL:
				switch (Config_Db::\$engine) {
					case EnumDbEngine::ENGINE_OBJECT_MYSQL_MYSQLI:
						if (\$this->dao_static==null)  \$this->dao_static=new Dao_MysqlI5();
						break;
					case EnumDbEngine::ENGINE_OBJECT_MYSQL_PHP:
						if (\$this->dao_static==null) \$this->dao_static=new Dao_Php5();
						break;
					default:
					//默认：Config_Mysql::ENGINE_MYSQL_PHP
						if (\$this->dao_static==null) \$this->dao_static=new Dao_Php5();
						break;
				}
				break;
			case EnumDbSource::DB_PGSQL:
				if (\$this->dao_static==null) \$this->dao_static=new Dao_Postgres();
				break;
			case EnumDbSource::DB_SQLITE2:
				if (\$this->dao_static==null) \$this->dao_static=new Dao_Sqlite2();
				break;
			case EnumDbSource::DB_SQLITE3:
				if (\$this->dao_static==null) \$this->dao_static=new Dao_Sqlite3();
				break;
			default:
			//默认：Config_Mysql::ENGINE_MYSQL_PHP
				if (\$this->dao_static==null) \$this->dao_static=new Dao_Php5();
				break;
		}
		\$this->currentdao=\$this->dao_static;
		return \$this->dao_static;
	}

	/**
	 * 获取数据库信息对对象
	 * @param bool \$isUseDbInfoDatabase 是否使用获取数据库信息的数据库
	 * @param bool \$forced 是否强制重新连接数据库获取新的数据库连接对象实例
	 * @param string \$host
	 * @param string \$port
	 * @param string \$username
	 * @param string \$password
	 * @param string \$dbname
	 * @return mixed 实时指定的Dbinfo对象
	 */
	public function dbinfo(\$isUseDbInfoDatabase=false,\$forced=false,\$host=null,\$port=null,\$username=null,\$password=null,\$dbname=null,\$engine=null) {
		if ((\$this->dbinfo_static==null)||\$forced) {
			switch (Config_Db::\$db) {
				case EnumDbSource::DB_MYSQL:
					DbInfo_Mysql::\$isUseDbInfoDatabase=\$isUseDbInfoDatabase;
					\$this->dbinfo_static=new DbInfo_Mysql(\$host,\$port,\$username,\$password,\$dbname,\$engine);
					DbInfo_Mysql::\$isUseDbInfoDatabase=false;
					break;
			}
		}
		return \$this->dbinfo_static;
	}

	/**
	 * 使用PHP自带的MYSQL数据库访问方法函数
	 * @param string \$host
	 * @param string \$port
	 * @param string \$username
	 * @param string \$password
	 * @param string \$dbname
	 * @param bool \$forced 是否强制重新连接数据库获取新的数据库连接对象实例
	 * @return mixed 实时指定的Dao对象
	 */
	public function object_mysql_php5(\$host=null,\$port=null,\$username=null,\$password=null,\$dbname=null,\$forced=false) {
		if ((\$this->dao_dynamic==null)||\$forced) {
			\$this->dao_dynamic=new Dao_Php5(\$host,\$port,\$username,\$password,\$dbname);
		}else if (!(\$this->dao_dynamic instanceof Dao_Php5)) {
			\$this->dao_dynamic=new Dao_Php5(\$host,\$port,\$username,\$password,\$dbname);
		}
		\$this->currentdao=\$this->dao_dynamic;
		return \$this->dao_dynamic;
	}

	/**
	 * 使用经典的MYSQLI访问数据库方法函数
	 * @param string \$host
	 * @param string \$port
	 * @param string \$username
	 * @param string \$password
	 * @param string \$dbname
	 * @param bool \$forced 是否强制重新连接数据库获取新的数据库连接对象实例
	 * @return mixed 实时指定的Dao对象
	 */
	public function object_mysql_mysqli(\$host=null,\$port=null,\$username=null,\$password=null,\$dbname=null,\$forced=false) {
		if ((\$this->dao_dynamic==null)||\$forced) {
			\$this->dao_dynamic=new Dao_MysqlI5(\$host,\$port,\$username,\$password,\$dbname);
		}else if (!(\$this->dao_dynamic instanceof Dao_MysqlI5)) {
			\$this->dao_dynamic=new Dao_MysqlI5(\$host,\$port,\$username,\$password,\$dbname);
		}
		\$this->currentdao=\$this->dao_dynamic;
		return \$this->dao_dynamic;
	}

	/**
	 * 使用经典的Sqlite 2数据库方法函数
	 * @param string \$host
	 * @param string \$port
	 * @param string \$username
	 * @param string \$password
	 * @param string \$dbname
	 * @param bool \$forced 是否强制重新连接数据库获取新的数据库连接对象实例
	 * @return mixed 实时指定的Dao对象
	 */
	public function object_sqlite2(\$host=null,\$port=null,\$username=null,\$password=null,\$dbname=null,\$forced=false) {
		if ((\$this->dao_dynamic==null)||\$forced) {
			\$this->dao_dynamic=new Dao_Sqlite2(\$host,\$port,\$username,\$password,\$dbname);
		}else if (!(\$this->dao_dynamic instanceof Dao_Sqlite2)) {
			\$this->dao_dynamic=new Dao_Sqlite2(\$host,\$port,\$username,\$password,\$dbname);
		}
		\$this->currentdao=\$this->dao_dynamic;
		return \$this->dao_dynamic;
	}

	/**
	 * 使用经典的Sqlite 3数据库方法函数
	 * @param string \$host
	 * @param string \$port
	 * @param string \$username
	 * @param string \$password
	 * @param string \$dbname
	 * @param bool \$forced 是否强制重新连接数据库获取新的数据库连接对象实例
	 * @return mixed 实时指定的Dao对象
	 */
	public function object_sqlite3(\$host=null,\$port=null,\$username=null,\$password=null,\$dbname=null,\$forced=false) {
		if ((\$this->dao_dynamic==null)||\$forced) {
			\$this->dao_dynamic=new Dao_Sqlite3(\$host,\$port,\$username,\$password,\$dbname);
		}else if (!(\$this->dao_dynamic instanceof Dao_Sqlite3)) {
			\$this->dao_dynamic=new Dao_Sqlite3(\$host,\$port,\$username,\$password,\$dbname);
		}
		\$this->currentdao=\$this->dao_dynamic;
		return \$this->dao_dynamic;
	}

	/**
	 * 使用经典的Postgres 数据库方法函数
	 * @param string \$host
	 * @param string \$port
	 * @param string \$username
	 * @param string \$password
	 * @param string \$dbname
	 * @param bool \$forced 是否强制重新连接数据库获取新的数据库连接对象实例
	 * @return mixed 实时指定的Dao对象
	 */
	public function object_postgres(\$host=null,\$port=null,\$username=null,\$password=null,\$dbname=null,\$forced=false) {
		if ((\$this->dao_dynamic==null)||\$forced) {
			\$this->dao_dynamic=new Dao_Postgres(\$host,\$port,\$username,\$password,\$dbname);
		}else if (!(\$this->dao_dynamic instanceof Dao_Postgres)) {
			\$this->dao_dynamic=new Dao_Postgres(\$host,\$port,\$username,\$password,\$dbname);
		}
		\$this->currentdao=\$this->dao_dynamic;
		return \$this->dao_dynamic;
	}
}
?>
MANAGEDB;
		$manager_db_file=$ignore_core_db_dir.DS."Manager_Db.php";
		file_put_contents($manager_db_file, $manager_db_content);
	}

	/**
	 * 清除module大部分工程无需的文件
	 */
	private static function IgnoreModules()
	{
		//去除module:barcode
		$dir_module=self::$save_dir.Config_F::ROOT_MODULE.DS;
		$ignore_module_dir=$dir_module."barcode".DS;
		UtilFileSystem::deleteDir($ignore_module_dir);

		$library_loader_content=<<<LIBRARY_LOAD
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
	public static function load_run()
	{
		\$spec_module=UtilXmlSimple::fileXmlToArray(dirname(__FILE__).DS.self::FILE_SPEC_LOAD_MODULE);
		foreach (\$spec_module["block"] as \$block){
			\$blockAttr=\$block[Util::XML_ELEMENT_ATTRIBUTES];
			if (array_key_exists(self::SPEC_OPEN, \$blockAttr)){
				if (strtolower(\$blockAttr[self::SPEC_OPEN])==self::OPEN_YES){
					self::\$blockAttr[self::SPEC_INIT]();
				}
			}else{
				self::\$blockAttr[self::SPEC_INIT]();
			}
		}
	}

	/**加载通信模块*/
	public static function load_communication()
	{
		//加载数据处理方案的所有目录进IncludePath
		\$communication_root_dir=Gc::\$nav_root_path.Config_F::ROOT_MODULE.DS."communication".DS;
		//加载模块里所有的文件
		load_module(Config_F::ROOT_MODULE,\$communication_root_dir,"webservice");
		load_module(Config_F::ROOT_MODULE,\$communication_root_dir."webservice".DS,"nusoap");
	}

	/**加载nusoap模块*/
	public static function load_nusoap()
	{
		\$communication_root_dir=Gc::\$nav_root_path.Config_F::ROOT_MODULE.DS."communication".DS;
		\$nusoap_dir=\$communication_root_dir."webservice/nusoap/lib/";
		require_once(\$nusoap_dir."nusoap.php");
		require_once(\$nusoap_dir."class.wsdlcache.php");
	}
}

?>

LIBRARY_LOAD;

		$library_loader_file=$dir_module.DS."Module_Loader.php";
		file_put_contents($library_loader_file, $library_loader_content);

		$library_xml_config_content=<<<LIBRARY_XML_CONTENT
<?xml version="1.0" encoding="UTF-8"?>
<!--
	/**
	 * 加载Module遵循以下规则：
	 * 1.加载的库文件应该都放在module目录下以加载Module的名称为子目录的名称内
	 * 2.是否加载Module由load.module.xml文件相关规范说明决定。
	 * 3.name:加载Module的名称，要求必须是英文和数字。
	 * 4.init:加载Module的方法，一般库有一个头文件，该方法由库提供者定义在本文件内。
	 * 5.open:是否加载Module。yes:加载，no:不加载，如果不定义则代表该Module由逻辑自定义开关规则。
	 * 6.group:若干Module属于同一个解决方案。
	 */
 -->
<module>
	<block name="communication" open="yes" init="load_communication" />
</module>

LIBRARY_XML_CONTENT;
		$library_xml_config_file=$dir_module.DS."load.module.xml";
		file_put_contents($library_xml_config_file, $library_xml_config_content);
	}

	/**
	 * 清除tools大部分工程无需的文件
	 */
	private static function IgnoreTools()
	{
		$root_tools="tools";
		//3.数据库备份:db/
		$ignore_db_dirs=array(
			"probe",
			"timertask",
			"webservice"
		);
		foreach ($ignore_db_dirs as $ignore_db_dir) {
			$toDeleteDir=self::$save_dir.$root_tools.DS.$ignore_db_dir;
			if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
		}
		$toDeleteDir=self::$save_dir.$root_tools.DS."dev".DS."install";
		if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
		$toDeleteDir=self::$save_dir.$root_tools.DS."dev".DS."phpsetget";
		if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
	}

	/**
	 * 清除common大部分工程无需的文件
	 * 1.去除js框架
	 * 2.去除在线编辑器
	 * 3.去除ext-all-debug.js文件
	 * 4.去除jquery下除1.7.1版本之外其他的文件
	 */
	private static function IgnoreCommons()
	{
		$root_commons="common";
		//1.去除js框架
		$ignore_js_dirs=array(
			"dojo",
			"ext4",
			"mootools",
			"prototype",
			"scriptaculous",
			"yui"
		);
		foreach ($ignore_js_dirs as $ignore_js_dir) {
			$toDeleteDir=self::$save_dir.$root_commons.DS."js".DS."ajax".DS.$ignore_js_dir;
			if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
		}
		//2.去除在线编辑器
		$ignore_oe_dirs=array(
			"ckeditor",
			"ckfinder",
			"kindeditor",
			"xheditor"
		);
		foreach ($ignore_oe_dirs as $ignore_oe_dir) {
			$toDeleteDir=self::$save_dir.$root_commons.DS."js".DS."onlineditor".DS.$ignore_oe_dir;
			if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);
		}
		//3.去除ext-all-debug.js文件
		unlink(self::$save_dir.$root_commons.DS."js".DS."ajax".DS."ext".DS."ext-all-debug.js");
		//4.去除jquery下除1.7.1版本之外其他的文件
		$ignore_files=array(
			"jquery-1.11.0.js",
			"jquery-1.11.0.min.js",
			"jquery-1.4.4.js",
			"jquery-1.4.4.min.js",
			"jquery-1.6.1.js",
			"jquery-1.6.1.min.js",
			"jquery.js",
			"jquery.min.js",
			"microsoft-jquery-1.4.4.min.js",
		);
		foreach ($ignore_files as $ignore_file) {
			$toDeleteFile=self::$save_dir.$root_commons.DS."js".DS."ajax".DS."jquery".DS.$ignore_file;
			if(file_exists($toDeleteFile))unlink($toDeleteFile);
		}
	}

	/**
	 * 清除util大部分工程无需的文件
	 * 1.去除ucenter工具集
	 * - 修改Action_Auth.php文件
	 * 2.去除在线编辑器工具集
	 * 3.去除js框架工具集
	 */
	private static function IgnoreUtils()
	{
		$root_core="core";
		//1.去除ucenter工具集
		$toDeleteDir=self::$save_dir.$root_core.DS."util".DS."ucenter";
		if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);

	 	// - 修改Action_Auth.php文件
		$action_auth_content=<<<AUTHCONTENT
<?php
/**
 +---------------------------------<br/>
 * 控制器:用户身份验证<br/>
 +---------------------------------
 * @category betterlife
 * @package  web.front
 * @subpackage auth
 * @author skygreen <skygreen2001@gmail.com>
 */
class Action_Auth extends Action
{
	/**
	 * 退出
	 */
	public function logout()
	{
		HttpSession::remove("user_id");
		\$this->redirect("auth","login");
	}

	/**
	 * 登录
	 */
	public function login()
	{
		\$this->view->set("message","");
		if(HttpSession::isHave('user_id')) {
			\$this->redirect("blog","display");
		}else if (!empty(\$_POST)) {
			\$user = \$this->model->User;
			\$userdata = User::get_one(array("username"=>\$user->username,
					"password"=>md5(\$user->getPassword())));
			if (empty(\$userdata)) {
				\$this->view->set("message","用户名或者密码错误");
			}else {
				HttpSession::set('user_id',\$userdata->user_id);
				\$this->redirect("blog","display");
			}
		}
	}

	/**
	 * 注册
	 */
	public function register()
	{
		if(!empty(\$_POST)) {
			\$user = \$this->model->User;
			\$userdata=User::get(array("username"=>\$user->username));
			if (empty(\$userdata)) {
				\$pass=\$user->getPassword();
				\$user->setPassword(md5(\$user->getPassword()));
				\$user->loginTimes=0;
				\$user->save();
				HttpSession::set('user_id',\$user->id);
				\$this->redirect("blog","display");
			}else{
				\$this->view->color="red";
				\$this->view->set("message","该用户名已有用户注册！");
			}
		}
	}
}
?>
AUTHCONTENT;

		$action_auth_file=self::$save_dir.Gc::$module_root.DS.self::$pj_name_en.DS."action".DS."Action_Auth.php";
		if(file_exists($action_auth_file))file_put_contents($action_auth_file, $action_auth_content);

		$util_view_dir=self::$save_dir.$root_core.DS."util".DS."view".DS;
		//2.去除在线编辑器工具集
		$ignore_files=array(
			"UtilKindEditor.php",
			"UtilXheditor.php",
		);
		foreach ($ignore_files as $ignore_file) {
			$toDeleteFile=$util_view_dir.DS."onlineditor".DS.$ignore_file;
			if(file_exists($toDeleteFile))unlink($toDeleteFile);
		}
		$toDeleteDir=$util_view_dir.DS."onlineditor".DS."ckEditor";
		if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);

		//3.去除js框架工具集
		$ignore_files=array(
			"UtilAjaxDojo.php",
			"UtilAjaxMootools.php",
			"UtilAjaxProtaculous.php",
			"UtilAjaxPrototype.php",
			"UtilAjaxScriptaculous.php",
			"UtilAjaxYui.php"
		);
		foreach ($ignore_files as $ignore_file) {
			$toDeleteFile=$util_view_dir.DS."ajax".DS.$ignore_file;
			if(file_exists($toDeleteFile))unlink($toDeleteFile);
		}
	}

	/**
	 * 运行生成Web项目代码重用
	 */
	public static function Run()
	{
		if(isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))self::$save_dir=$_REQUEST["save_dir"];
		if(isset($_REQUEST["pj_name_cn"])&&!empty($_REQUEST["pj_name_cn"]))
		{
			self::$pj_name_cn=$_REQUEST["pj_name_cn"];
		}else{
			self::UserInput();
			die("<div align='center'><font color='red'>不能为空:新Web项目名称【中文】</font></div>");
		}
		if(isset($_REQUEST["pj_name_en"])&&!empty($_REQUEST["pj_name_en"]))
		{
			self::$pj_name_en=$_REQUEST["pj_name_en"];
		}else{
			self::UserInput();
			die("<div align='center'><font color='red'>不能为空:新Web项目名称【英文】</font></div>");
		}
		if(isset($_REQUEST["pj_name_alias"])&&!empty($_REQUEST["pj_name_alias"]))
		{
			self::$pj_name_alias=$_REQUEST["pj_name_alias"];
		}else{
			self::UserInput();
			die("<div align='center'><font color='red'>不能为空:新Web项目名称别名</font></div>");
		}
		if(isset($_REQUEST["dbname"])&&!empty($_REQUEST["dbname"]))
		{
			self::$db_name=$_REQUEST["dbname"];
		}else{
			self::UserInput();
			die("<div align='center'><font color='red'>不能为空:数据库名称</font></div>");
		}

		if(isset($_REQUEST["table_prefix"])&&!empty($_REQUEST["table_prefix"]))
		{
			self::$table_prefix=$_REQUEST["table_prefix"];
		}

		if(isset($_REQUEST["reuse_type"])&&!empty($_REQUEST["reuse_type"]))
			self::$reuse_type=$_REQUEST["reuse_type"];

		if(isset($_REQUEST["git_name"])&&!empty($_REQUEST["git_name"]))
			self::$git_name=$_REQUEST["git_name"];


		$default_dir=Gc::$nav_root_path;
		$domain_root=str_replace(Gc::$appName.DS, "", $default_dir);
		$save_dir=self::$save_dir;
		self::$save_dir=$domain_root.self::$save_dir.DS;

		if(is_dir(self::$save_dir)){
			self::$save_dir=$save_dir;
			self::UserInput();
			die("<div align='center'><font color='red'>该目录已存在!为防止覆盖您现有的代码,请更名!</font></div>");
		}

		//生成新项目目录
		smartCopy(Gc::$nav_root_path,self::$save_dir);

		//修改Gc.php配置文件
		$gc_file=self::$save_dir."Gc.php";
		$content=file_get_contents($gc_file);
		$content=str_replace(Gc::$site_name, self::$pj_name_cn, $content);
		$content=str_replace(Gc::$appName, self::$pj_name_en, $content);
		$content=str_replace(Gc::$appName_alias, self::$pj_name_alias, $content);
		if((self::$reuse_type==EnumReusePjType::MINI)||(self::$reuse_type==EnumReusePjType::LIKE)){
			$content=str_replace("\"model\",\r\n", "", $content);
		}
		file_put_contents($gc_file, $content);

		//修改Config_Db.php配置文件
		$conf_db_file=self::$save_dir."config".DS."config".DS."Config_Db.php";
		$content=file_get_contents($conf_db_file);
		$content=str_replace("\$dbname=\"".Config_Db::$dbname."\"", "\$dbname=\"".self::$db_name."\"", $content);
		$content=str_replace("\$table_prefix=\"".Config_Db::$table_prefix."\"", "\$table_prefix=\"".self::$table_prefix."\"", $content);
		file_put_contents($conf_db_file, $content);

		//修改Welcome.php文件
		if(!empty(self::$git_name)){
			$welcome_file=self::$save_dir."welcome.php";
			$content=file_get_contents($welcome_file);

			$ctrl=substr($content,0,strpos($content,"<?php \$help_url=\"")+17);
			$ctrr=substr($content,strpos($content,"<?php \$help_url=\"")+18);
			$ctrr=substr($ctrr,strpos($ctrr,"\""));
			$content=$ctrl.self::$git_name.$ctrr;
			if(self::$reuse_type==EnumReusePjType::MINI)$content=str_replace("通用模板", "", $content);
			file_put_contents($welcome_file, $content);
		}

		//修改应用文件夹名称
		$old_name=self::$save_dir.Gc::$module_root.DS.Gc::$appName.DS;
		$new_name=self::$save_dir.Gc::$module_root.DS.self::$pj_name_en.DS;
		if(is_dir($old_name)){
			$toDeleteDir=$old_name."view".DS."default".DS."tmp".DS."templates_c".DS;
			UtilFileSystem::deleteDir($toDeleteDir);
			UtilFileSystem::createDir($toDeleteDir);
			rename($old_name,$new_name);
		}
		//重命名后台Action_Betterlife为新应用类
		$old_name=self::$save_dir.Gc::$module_root.DS."admin".DS."action".DS."Action_".ucfirst(Gc::$appName).".php";
		$new_name=self::$save_dir.Gc::$module_root.DS."admin".DS."action".DS."Action_".ucfirst(self::$pj_name_en).".php";
		if(is_dir($old_name))rename($old_name,$new_name);

		//替换Extjs的js文件里的命名空间
		$extjsDir=self::$save_dir.Gc::$module_root.DS."admin".DS."view".DS."default".DS."js".DS."ext".DS;
		$jsFiles=UtilFileSystem::getAllFilesInDirectory($extjsDir,array("js"));
		$o_appName=ucfirst(Gc::$appName);
		$n_appName=ucfirst(self::$pj_name_en);

		foreach ($jsFiles as $jsFile) {
			$content=file_get_contents($jsFile);
			$origin_content=$content;
            if(contain($jsFile,DS."components".DS))continue;
            $fileName=basename($jsFile,".js");
			//*.替换命名空间
			$fileName=str_replace(".js", "", $fileName);
			$fileName=ucfirst($fileName);
			$content=str_replace("Ext.namespace(\"$o_appName.Admin", "Ext.namespace(\"$n_appName.Admin", $content);
            if(contain($jsFile,DS."ext".DS."view".DS)){
			    //*.替换命名空间缩写定义
			    $content=str_replace(Gc::$appName_alias."View = $o_appName.Admin.View;", self::$pj_name_alias."View = $n_appName.Admin.View;", $content);
                //*.替换命名空间定义前缀
                $content=str_replace(Gc::$appName_alias."View.", self::$pj_name_alias."View.", $content);
                //*.替换命名空间定义前缀
                $content=str_replace("parent.".Gc::$appName_alias, "parent.".self::$pj_name_alias, $content);
			}else{
                //*.替换命名空间缩写定义
                $content=str_replace(Gc::$appName_alias." = $o_appName.Admin;", self::$pj_name_alias." = $n_appName.Admin;", $content);
                //*.替换命名空间定义前缀
                $content=str_replace(Gc::$appName_alias.".", self::$pj_name_alias.".", $content);
            }
			if($origin_content!=$content)file_put_contents($jsFile, $content);
		}

		$reuse_type=intval(self::$reuse_type);
		//清除在大部分项目中不需要的目录
		switch ($reuse_type) {
			case EnumReusePjType::MINI:
				self::IgnoreInCommon();
				$toDeleteDir=self::$save_dir.Gc::$module_root.DS."model";
				if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);

				$toDeleteDir=self::$save_dir.Gc::$module_root.DS."admin".DS."src".DS."timertask".DS;
				if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);

				self::IgnoreAllDbEngineExceptMysql();
				self::IgnoreCommons();
				self::IgnoreUtils();
				break;
			case EnumReusePjType::LIKE:
				self::IgnoreInCommon();
				self::IgnoreCommons();

				//删除exjs库
				$toDeleteDir=self::$save_dir."common".DS."js".DS."ajax".DS."ext".DS;
				if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);

				self::IgnoreUtils();

				$toDeleteDir=self::$save_dir.Gc::$module_root.DS."admin".DS."src".DS."timertask".DS;
				if(is_dir($toDeleteDir))UtilFileSystem::deleteDir($toDeleteDir);

				//修改model文件夹名称为后台文件夹admin
				$old_admin_name=self::$save_dir.Gc::$module_root.DS."admin".DS;
				UtilFileSystem::deleteDir($old_admin_name);
				$old_model_name=self::$save_dir.Gc::$module_root.DS."model".DS;
				$new_model_name=self::$save_dir.Gc::$module_root.DS."admin".DS;
				if(is_dir($old_model_name)){
					rename($old_model_name,$new_model_name);
					//替换model的tpl文件里的链接地址
					$modelTplDir=self::$save_dir.Gc::$module_root.DS."admin".DS."view".DS."default".DS."core".DS;
					$tplFiles=UtilFileSystem::getAllFilesInDirectory($modelTplDir,array("tpl"));

					foreach ($tplFiles as $tplFile) {
						$content=file_get_contents($tplFile);
						$content=str_replace("go=model.", "go=admin.", $content);
						file_put_contents($tplFile, $content);
					}
					//修改Action控制器类的注释:* @category 应用名称
					$modelActionDir=self::$save_dir.Gc::$module_root.DS."admin".DS."action".DS;
					$actionFiles=UtilFileSystem::getAllFilesInDirectory($modelActionDir,array("php"));

					foreach ($actionFiles as $actionFile) {
						$content=file_get_contents($actionFile);
						$content=str_replace("* @category ".Gc::$appName, "* @category ".self::$pj_name_en, $content);
						file_put_contents($actionFile, $content);
					}
				}


				//修改Config_AutoCode.php配置文件
				$config_autocode_file=self::$save_dir."config".DS."config".DS."Config_AutoCode.php";
				$content=file_get_contents($config_autocode_file);
				$content=str_replace("const AFTER_MODEL_CONVERT_ADMIN=false;", "const AFTER_MODEL_CONVERT_ADMIN=true;", $content);
				file_put_contents($config_autocode_file, $content);
				break;
			case EnumReusePjType::HIGH:
				self::IgnoreInCommon();
				$old_model_name=self::$save_dir.Gc::$module_root.DS."model".DS;
				if(is_dir($old_model_name))UtilFileSystem::deleteDir($old_model_name);
				break;
			default:
				break;
		}
		self::$save_dir=$save_dir;
		self::UserInput();
		$default_dir=Gc::$url_base;
		$domain_url=str_replace(Gc::$appName."/", "", $default_dir);
		die("<div align='center'><font color='green'><a href='".$domain_url.self::$pj_name_en."/' target='_blank'>生成新Web项目成功！</a></font><br/><a href='".$domain_url.self::$pj_name_en."/' target='_blank'>新地址</a></div>");
	}

	/**
	 * 多数情况下都会清除的内容
	 */
	private static function IgnoreInCommon()
	{
		self::IgnoreDir();
		self::IgnoreFiles();
		self::IgnoreLibraryUnused();
		self::IgnoreCache();
		self::IgnoreModules();
		self::IgnoreTools();
	}

	/**
	 * 用户输入需求
	 */
	public static function UserInput()
	{
		$title="一键重用Web项目代码";
		if(empty($_REQUEST["save_dir"])){
			$pj_name_cn=Gc::$site_name;
			$pj_name_en=Gc::$appName;
			$pj_name_alias=Gc::$appName_alias;
			$default_dir=Gc::$nav_root_path;
			$domain_root=str_replace($pj_name_en.DS, "", $default_dir);
			$default_dir=$pj_name_en;
			$dbname=Config_Db::$dbname;
			$table_prefix=Config_Db::$table_prefix;
			$git_name="http://skygreen2001.gitbooks.io/betterlife-cms-framework/content/index.html";
		}else{
			$reuse_type=self::$reuse_type;
			$pj_name_cn=self::$pj_name_cn;
			$pj_name_en=self::$pj_name_en;
			$pj_name_alias=self::$pj_name_alias;
			$default_dir=Gc::$nav_root_path;
			$domain_root=str_replace(Gc::$appName.DS, "", $default_dir);
			$default_dir=self::$save_dir;
			$dbname=self::$db_name;
			$table_prefix=self::$table_prefix;
			$git_name=self::$git_name;
		}
		$inputArr=array(
			"4"=>"精简版",
			"2"=>"通用版",
			"3"=>"高级版",
			"1"=>"完整版"
		);

		echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>\r\n
				<html lang='zh-CN' xml:lang='zh-CN' xmlns='http://www.w3.org/1999/xhtml'>\r\n";
		echo "<head>\r\n";
		echo UtilCss::form_css()."\r\n";
		$url_base=UtilNet::urlbase();
		echo "</head>";
		echo "<body>";
		echo "<br/><br/><br/><h1 align='center'>$title</h1>\r\n";
		echo "<div align='center' height='450'>\r\n";
		echo "<form>\r\n";
		echo "	<div style='line-height:1.5em;'>\r\n";
		echo "		<label>Web项目名称【中文】:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' name='pj_name_cn' value='$pj_name_cn' id='pj_name_cn' /><br/>\r\n";
		echo "		<label>Web项目名称【英文】:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' name='pj_name_en' value='$pj_name_en' id='pj_name_en' oninput=\"document.getElementById('dbname').value=this.value;document.getElementById('save_dir').value=this.value;\" /><br/>\r\n";
		echo "		<label title='最好两个字母,头字母大写'>Web项目别名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label><input title='最好两个字母,头字母大写' style='width:400px;text-align:left;padding-left:10px;' type='text' name='pj_name_alias' value='$pj_name_alias' id='pj_name_alias' /><br/>\r\n";
		echo "		<label>输出Web项目路径&nbsp;&nbsp;&nbsp;:</label>$domain_root<input style='width:306px;text-align:left;padding-left:10px;' type='text' name='save_dir' value='$default_dir' id='save_dir' /><br/>\r\n";
		echo "		<label>数据库名称&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' name='dbname' value='$dbname' id='dbname' /><br/>\r\n";
		echo "		<label>数据库表名前缀&nbsp;&nbsp;&nbsp;&nbsp;:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' name='table_prefix' value='$table_prefix' id='table_prefix' /><br/>\r\n";
		echo "		<label>帮助地址&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' name='git_name' value='$git_name' id='git_name' /><br/>\r\n";
		$selectd_str="";
		if (!empty($inputArr)){
			echo "<label>&nbsp;&nbsp;&nbsp;重用类型&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label><select name='reuse_type'>\r\n";
			foreach ($inputArr as $key=>$value) {
				if(isset($reuse_type)){
					if($key==$reuse_type)$selectd_str=" selected";else $selectd_str="";
				}
				echo "		<option value='$key'$selectd_str>$value</option>\r\n";
			}
			echo "		</select>\r\n";
		}
		echo "	</div>\r\n";
		echo "	<input type='submit' value='生成' /><br/>\r\n";
		echo "</form>\r\n";
		echo "</div>\r\n";
		echo "</body>\r\n";
		echo "</html>";
	}
}

/**
 * Copy file or folder from source to destination, it can do
 * recursive copy as well and is very smart
 * It recursively creates the dest file or directory path if there weren't exists
 * Situtaions :
 * - Src:/home/test/file.txt ,Dst:/home/test/b ,Result:/home/test/b -> If source was file copy file.txt name with b as name to destination
 * - Src:/home/test/file.txt ,Dst:/home/test/b/ ,Result:/home/test/b/file.txt -> If source was file Creates b directory if does not exsits and copy file.txt into it
 * - Src:/home/test ,Dst:/home/ ,Result:/home/test/** -> If source was directory copy test directory and all of its content into dest
 * - Src:/home/test/ ,Dst:/home/ ,Result:/home/**-> if source was direcotry copy its content to dest
 * - Src:/home/test ,Dst:/home/test2 ,Result:/home/test2/** -> if source was directoy copy it and its content to dest with test2 as name
 * - Src:/home/test/ ,Dst:/home/test2 ,Result:->/home/test2/** if source was directoy copy it and its content to dest with test2 as name
 * @todo
 *	 - Should have rollback technique so it can undo the copy when it wasn't successful
 *  - Auto destination technique should be possible to turn off
 *  - Supporting callback function
 *  - May prevent some issues on shared enviroments : http://us3.php.net/umask
 * @param $source //file or folder
 * @param $dest ///file or folder
 * @param $options //folderPermission,filePermission
 * @return boolean
 */
function smartCopy($source, $dest, $options=array('folderPermission'=>0755,'filePermission'=>0755))
{
	$result=false;

	if (is_file($source)) {
		if ($dest[strlen($dest)-1]=='/') {
			if (!file_exists($dest)) {
				cmfcDirectory::makeAll($dest,$options['folderPermission'],true);
			}
			$__dest=$dest."/".basename($source);
		} else {
			$__dest=$dest;
		}
		$result=copy($source, $__dest);
		chmod($__dest,$options['filePermission']);

	} elseif(is_dir($source)) {
		if ($dest[strlen($dest)-1]=='/') {
			if ($source[strlen($source)-1]=='/') {
				//Copy only contents
			} else {
				//Change parent itself and its contents
				$dest=$dest.basename($source);
				@mkdir($dest);
				chmod($dest,$options['filePermission']);
			}
		} else {
			if ($source[strlen($source)-1]=='/') {
				//Copy parent directory with new name and all its content
				@mkdir($dest,$options['folderPermission']);
				chmod($dest,$options['filePermission']);
			} else {
				//Copy parent directory with new name and all its content
				@mkdir($dest,$options['folderPermission']);
				chmod($dest,$options['filePermission']);
			}
		}

		$dirHandle=opendir($source);
		while($file=readdir($dirHandle))
		{
			if($file!="." && $file!=".."&& $file!=".git"&& $file!=".svn")
			{
				 if(!is_dir($source."/".$file)) {
					$__dest=$dest."/".$file;
				} else {
					$__dest=$dest."/".$file;
				}
				//echo "$source/$file ||| $__dest<br />";
				$result=smartCopy($source."/".$file, $__dest, $options);
			}
		}
		closedir($dirHandle);

	} else {
		$result=false;
	}
	return $result;
}


//控制器:运行Web项目代码重用
if(isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"])){
	Project_Refactor::Run();
}else{
	Project_Refactor::UserInput();
}

?>
