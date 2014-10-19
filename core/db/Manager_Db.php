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
	private $dao_static;
	/**
	 * @var mixed 实时指定的Dao或者Dal对象，实时注入配置
	 */
	private $dao_dynamic;
	/**
	 * @var mixed 当前使用的Dao或者Dal对象
	 */
	private $currentdao;
	/**
	 * @var IDbInfo 获取数据库表信息对象
	 */
	private $dbinfo_static;
	/**
	 * @var Manager_Db 当前唯一实例化的Db管理类。
	 */
	private static $instance;

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
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance=new $c();
		}
		return self::$instance;
	}

	/**
	 * 返回当前使用的Dao
	 * @return mixed 当前使用的Dao
	 */
	public function currentdao(){
		if ($this->currentdao==null){
			$this->dao();
		}
		return $this->currentdao;
	}

	/**
	 * 全局设定一个Dao对象；
	 * 由开发者配置设定对象决定
	 */
	public function dao() {
		if (Config_Db::$engine==EnumDbEngine::ENGINE_DAL_MDB2) {
			if ($this->dao_static==null)  $this->dao_static=new Dal_Mdb2();
		}if (Config_Db::$engine==EnumDbEngine::ENGINE_DAL_PDO) {
			if ($this->dao_static==null)  $this->dao_static=new Dal_Pdo();
		}else if ((Config_Db::$engine==EnumDbEngine::ENGINE_DAL_ADODB)||(Config_Db::$engine==EnumDbEngine::ENGINE_DAL_ADODB_PDO)) {
			if ($this->dao_static==null)  $this->dao_static=new Dal_Adodb();
		}else {
			switch (Config_Db::$db) {
				case EnumDbSource::DB_MYSQL:
					switch (Config_Db::$engine) {
						case EnumDbEngine::ENGINE_OBJECT_MYSQL_MYSQLI:
							if ($this->dao_static==null)  $this->dao_static=new Dao_MysqlI5();
							break;
						case EnumDbEngine::ENGINE_OBJECT_MYSQL_PHP:
							if ($this->dao_static==null) $this->dao_static=new Dao_Php5();
							break;
						default:
						//默认：Config_Mysql::ENGINE_MYSQL_PHP
							if ($this->dao_static==null) $this->dao_static=new Dao_Php5();
							break;
					}
					break;
				case EnumDbSource::DB_MICROSOFT_ACCESS:
				case EnumDbSource::DB_MICROSOFT_EXCEL:
				case EnumDbSource::DB_SQLSERVER:
					switch (Config_Db::$engine) {
						case EnumDbEngine::ENGINE_OBJECT_ODBC:
							if ($this->dao_static==null) $this->dao_static=new Dao_Odbc();
							break;
						case EnumDbEngine::ENGINE_OBJECT_MSSQLSERVER:
							if ($this->dao_static==null) $this->dao_static=new Dao_Mssql();
							break;
					}
					break;
				case EnumDbSource::DB_PGSQL:
					if ($this->dao_static==null) $this->dao_static=new Dao_Postgres();
					break;
				case EnumDbSource::DB_SQLITE2:
					if ($this->dao_static==null) $this->dao_static=new Dao_Sqlite2();
					break;
				case EnumDbSource::DB_SQLITE3:
					if ($this->dao_static==null) $this->dao_static=new Dao_Sqlite3();
					break;
				default:
				//默认：Config_Mysql::ENGINE_MYSQL_PHP
					if ($this->dao_static==null) $this->dao_static=new Dao_Php5();
					break;
			}
		}
		$this->currentdao=$this->dao_static;
		return $this->dao_static;
	}

	/**
	 * 使用PHP 自带的 PDO访问数据库方法函数
	 * @param string $host
	 * @param string $port
	 * @param string $username
	 * @param string $password
	 * @param string $dbname
	 * @param mixed $dbtype 指定数据库类型。{该字段的值参考：EnumDbSource}
	 * @param mixed $engine 指定操作数据库引擎。{该字段的值参考：EnumDbEngine}
	 * @param bool $forced 是否强制重新连接数据库获取新的数据库连接对象实例
	 * @return mixed 实时指定的Dal对象
	 */
	public function dal_pdo($host=null,$port=null,$username=null,$password=null,$dbname=null,$dbtype=null,$engine=null,$forced=false) {
		if (Config_Db::$engine==EnumDbEngine::ENGINE_DAL_PDO) {
			if (($this->dao_dynamic==null)||$forced) {
				$this->dao_dynamic=new Dal_Pdo($host,$port,$username,$password,$dbname,$dbtype,$engine);
			}else if (!($this->dao_dynamic instanceof Dal_Pdo)) {
				$this->dao_dynamic=new Dal_Pdo($host,$port,$username,$password,$dbname,$dbtype,$engine);
			}
		}
		$this->currentdao=$this->dao_dynamic;
		return $this->dao_dynamic;
	}

	/**
	 * 使用第三方支持的MDB2访问数据库方法函数
	 * @param string $host
	 * @param string $port
	 * @param string $username
	 * @param string $password
	 * @param string $dbname
	 * @param mixed $dbtype 指定数据库类型。{该字段的值参考：EnumDbSource}
	 * @param mixed $engine 指定操作数据库引擎。{该字段的值参考：EnumDbEngine}
	 * @param bool $forced 是否强制重新连接数据库获取新的数据库连接对象实例
	 * @return mixed 实时指定的Dal对象
	 */
	public function dal_mdb2($host=null,$port=null,$username=null,$password=null,$dbname=null,$dbtype=null,$engine=null,$forced=false) {
		if ((Config_Db::$engine==EnumDbEngine::ENGINE_DAL_ADODB)||(Config_Db::$engine==EnumDbEngine::ENGINE_DAL_ADODB_PDO)) {
			if (($this->dao_dynamic==null)||$forced) {
				$this->dao_dynamic=new Dal_Mdb2($host,$port,$username,$password,$dbname,$dbtype,$engine);
			}else if (!($this->dao_dynamic instanceof Dal_AdoDb)) {
				$this->dao_dynamic=new Dal_Mdb2($host,$port,$username,$password,$dbname,$dbtype,$engine);
			}
		}
		$this->currentdao=$this->dao_dynamic;
		return $this->dao_dynamic;
	}

	/**
	 * 使用第三方支持的Adodb访问数据库方法函数
	 * @param string $host
	 * @param string $port
	 * @param string $username
	 * @param string $password
	 * @param string $dbname
	 * @param mixed $dbtype 指定数据库类型。{该字段的值参考：EnumDbSource}
	 * @param mixed $engine 指定操作数据库引擎。{该字段的值参考：EnumDbEngine}
	 * @param bool $forced 是否强制重新连接数据库获取新的数据库连接对象实例
	 * @return mixed 实时指定的Dal对象
	 */
	public function dal_adodb($host=null,$port=null,$username=null,$password=null,$dbname=null,$dbtype=null,$engine=null,$forced=false) {
		if ((Config_Db::$engine==EnumDbEngine::ENGINE_DAL_ADODB)||(Config_Db::$engine==EnumDbEngine::ENGINE_DAL_ADODB_PDO)) {
			if (($this->dao_dynamic==null)||$forced) {
				$this->dao_dynamic=new Dal_Adodb($host,$port,$username,$password,$dbname,$dbtype,$engine);
			}else if (!($this->dao_dynamic instanceof Dal_AdoDb)) {
				$this->dao_dynamic=new Dal_Adodb($host,$port,$username,$password,$dbname,$dbtype,$engine);
			}
		}
		$this->currentdao=$this->dao_dynamic;
		return $this->dao_dynamic;
	}

	/**
	 * 获取数据库信息对对象
	 * @param bool $isUseDbInfoDatabase 是否使用获取数据库信息的数据库
	 * @param bool $forced 是否强制重新连接数据库获取新的数据库连接对象实例
	 * @param string $host
	 * @param string $port
	 * @param string $username
	 * @param string $password
	 * @param string $dbname
	 * @return mixed 实时指定的Dbinfo对象
	 */
	public function dbinfo($isUseDbInfoDatabase=false,$forced=false,$host=null,$port=null,$username=null,$password=null,$dbname=null,$engine=null) {
		if (($this->dbinfo_static==null)||$forced) {
			switch (Config_Db::$db) {
				case EnumDbSource::DB_MYSQL:
					DbInfo_Mysql::$isUseDbInfoDatabase=$isUseDbInfoDatabase;
					$this->dbinfo_static=new DbInfo_Mysql($host,$port,$username,$password,$dbname,$engine);
					DbInfo_Mysql::$isUseDbInfoDatabase=false;
					break;
			}
		}
		return $this->dbinfo_static;
	}

	/**
	 * 使用PHP自带的Ms SQL Server数据库访问方法函数
	 * @param string $host
	 * @param string $port
	 * @param string $username
	 * @param string $password
	 * @param string $dbname
	 * @param bool $forced 是否强制重新连接数据库获取新的数据库连接对象实例
	 * @return mixed 实时指定的Dao对象
	 */
	public function object_ms_sqlserver($host=null,$port=null,$username=null,$password=null,$dbname=null,$forced=false) {
		if (($this->dao_dynamic==null)||$forced) {
			$this->dao_dynamic=new Dao_Mssql($host,$port,$username,$password,$dbname);
		}
		$this->currentdao=$this->dao_dynamic;
		return $this->dao_dynamic;
	}

	/**
	 * 使用PHP自带的MYSQL数据库访问方法函数
	 * @param string $host
	 * @param string $port
	 * @param string $username
	 * @param string $password
	 * @param string $dbname
	 * @param bool $forced 是否强制重新连接数据库获取新的数据库连接对象实例
	 * @return mixed 实时指定的Dao对象
	 */
	public function object_mysql_php5($host=null,$port=null,$username=null,$password=null,$dbname=null,$forced=false) {
		if (($this->dao_dynamic==null)||$forced) {
			$this->dao_dynamic=new Dao_Php5($host,$port,$username,$password,$dbname);
		}else if (!($this->dao_dynamic instanceof Dao_Php5)) {
			$this->dao_dynamic=new Dao_Php5($host,$port,$username,$password,$dbname);
		}
		$this->currentdao=$this->dao_dynamic;
		return $this->dao_dynamic;
	}

	/**
	 * 使用经典的MYSQLI访问数据库方法函数
	 * @param string $host
	 * @param string $port
	 * @param string $username
	 * @param string $password
	 * @param string $dbname
	 * @param bool $forced 是否强制重新连接数据库获取新的数据库连接对象实例
	 * @return mixed 实时指定的Dao对象
	 */
	public function object_mysql_mysqli($host=null,$port=null,$username=null,$password=null,$dbname=null,$forced=false) {
		if (($this->dao_dynamic==null)||$forced) {
			$this->dao_dynamic=new Dao_MysqlI5($host,$port,$username,$password,$dbname);
		}else if (!($this->dao_dynamic instanceof Dao_MysqlI5)) {
			$this->dao_dynamic=new Dao_MysqlI5($host,$port,$username,$password,$dbname);
		}
		$this->currentdao=$this->dao_dynamic;
		return $this->dao_dynamic;
	}

	/**
	 * 使用经典的Sqlite 2数据库方法函数
	 * @param string $host
	 * @param string $port
	 * @param string $username
	 * @param string $password
	 * @param string $dbname
	 * @param bool $forced 是否强制重新连接数据库获取新的数据库连接对象实例
	 * @return mixed 实时指定的Dao对象
	 */
	public function object_sqlite2($host=null,$port=null,$username=null,$password=null,$dbname=null,$forced=false) {
		if (($this->dao_dynamic==null)||$forced) {
			$this->dao_dynamic=new Dao_Sqlite2($host,$port,$username,$password,$dbname);
		}else if (!($this->dao_dynamic instanceof Dao_Sqlite2)) {
			$this->dao_dynamic=new Dao_Sqlite2($host,$port,$username,$password,$dbname);
		}
		$this->currentdao=$this->dao_dynamic;
		return $this->dao_dynamic;
	}

	/**
	 * 使用经典的Sqlite 3数据库方法函数
	 * @param string $host
	 * @param string $port
	 * @param string $username
	 * @param string $password
	 * @param string $dbname
	 * @param bool $forced 是否强制重新连接数据库获取新的数据库连接对象实例
	 * @return mixed 实时指定的Dao对象
	 */
	public function object_sqlite3($host=null,$port=null,$username=null,$password=null,$dbname=null,$forced=false) {
		if (($this->dao_dynamic==null)||$forced) {
			$this->dao_dynamic=new Dao_Sqlite3($host,$port,$username,$password,$dbname);
		}else if (!($this->dao_dynamic instanceof Dao_Sqlite3)) {
			$this->dao_dynamic=new Dao_Sqlite3($host,$port,$username,$password,$dbname);
		}
		$this->currentdao=$this->dao_dynamic;
		return $this->dao_dynamic;
	}

	/**
	 * 使用经典的Postgres 数据库方法函数
	 * @param string $host
	 * @param string $port
	 * @param string $username
	 * @param string $password
	 * @param string $dbname
	 * @param bool $forced 是否强制重新连接数据库获取新的数据库连接对象实例
	 * @return mixed 实时指定的Dao对象
	 */
	public function object_postgres($host=null,$port=null,$username=null,$password=null,$dbname=null,$forced=false) {
		if (($this->dao_dynamic==null)||$forced) {
			$this->dao_dynamic=new Dao_Postgres($host,$port,$username,$password,$dbname);
		}else if (!($this->dao_dynamic instanceof Dao_Postgres)) {
			$this->dao_dynamic=new Dao_Postgres($host,$port,$username,$password,$dbname);
		}
		$this->currentdao=$this->dao_dynamic;
		return $this->dao_dynamic;
	}

	/**
	 * 使用默认的PHP访问ODBC数据库方法函数
	 * @param string $host
	 * @param string $port
	 * @param string $username
	 * @param string $password
	 * @param string $dbname
	 * @param enum $dbtype 指定数据库类型。{使用Dao_ODBC引擎，需要定义该字段,该字段的值参考：EnumDbSource}
	 * @param bool $forced 是否强制重新连接数据库获取新的数据库连接对象实例
	 * @return mixed 实时指定的Dao对象
	 */
	public function object_odbc($host=null,$port=null,$username=null,$password=null,$dbname=null,$dbtype=null,$forced=false) {
		if (($this->dao_dynamic==null)||$forced) {
			$this->dao_dynamic=new Dao_Odbc($host,$port,$username,$password,$dbname,$dbtype);
		}else if (!($this->dao_dynamic instanceof Dao_Odbc)) {
			$this->dao_dynamic=new Dao_Odbc($host,$port,$username,$password,$dbname,$dbtype);
		}
		$this->currentdao=$this->dao_dynamic;
		return $this->dao_dynamic;
	}
}
?>
