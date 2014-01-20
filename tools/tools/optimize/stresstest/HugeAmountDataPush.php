<?php
require_once ("../../../../init.php");

/**
 * 辅助压力测试的工具:注入海量数据
 */
class HugeAmountDataPush
{
	/**
	 * 是否显示测试报表
	 * @return boolean
	 */
	public static $isShowReport=true;

	 /**
	 * 是否显示sql语句
	 * @return boolean
	 */
	public static $isShowSql=true;
	/**
	* 测试数量级
	* @return int
	*/
	public static $num=10000;
	/**
	* 测试数量级
	* @return int
	*/
	public static $init_data_num=100;
	/**
	* 计数:中间关系表
	*/
	public static $num_relation_table=0;
	/**
	* 计数:所有表
	*/
	public static $num_table=0;
	/**
	* 脚本生成保存路径
	*/
	private static $script_sql_path;
	/**
	* 单张表生成Sql脚本
	*/
	private static $cache_result;
	/**
	* 所有生成Sql脚本
	*/
	private static $result;
	/**
	 * Sql文件创建时间
	 * @return timestamp
	 */
	private static $nowtime;
	/**
	* 不符合关系列定义的列名
	*/
	public static $relation_fields=array(
		"ns_product_re_productspec"=>array("attribute_id")
	);
	/**
	 * 所有表列信息
	 * @return array
	 */
	public static $fieldInfos=array();

	public static function init()
	{
		self::$nowtime=UtilDateTime::now(EnumDateTimeFormat::TIMESTAMP);
		self::$script_sql_path=Gc::$nav_root_path."stressdata".DIRECTORY_SEPARATOR;
		UtilFileSystem::createDir(self::$script_sql_path);
		self::$script_sql_path.="StresstTest".self::$nowtime.".txt";
		self::$result="";
	    file_put_contents(self::$script_sql_path,"");
	}

	/**
	 +----------------------------------------------------------<br/>
	 * 压力测试，单个或多个指定的数据表<br/>
	 +----------------------------------------------------------<br/>
	 * @param string|array $tablenames 数据表名称|多个数据表名称
	 * @param int $num 需要测试的数据数量级
	 */
	public static function createTablesData($tablenames)
	{
		if (is_string($tablenames)) {
			$tablenames=array($tablenames);
		}

		foreach ($tablenames as $tablename) {
			$count=self::getOneTableData($tablename);
			if(self::$isShowReport){
				echo "所测试的数据表：$tablename<br>";
				echo "预计生成数据数量级：".self::$num."<br>实际生成数据数目：$count<br/>";
				echo "SQL脚本文件地址：".self::$script_sql_path."<br/><br/>";
			}
			if(self::$isShowSql){
				$table_change = array("\r\n"=>"<br/>");
				$show_result= strtr(self::$cache_result,$table_change);
				echo $show_result."<br/><br/>";
			}
		}
		file_put_contents(self::$script_sql_path,self::$result);//,FILE_APPEND
	}

	/**
	 +----------------------------------------------------------<br/>
	 * 获取单个数据表数据<br/>
	 +----------------------------------------------------------<br/>
	 * @param string $tablename 数据表名称
	 * @param int $num 需要测试的数据数量级
	 * @return int 添加数据的数量
	 */
	private static function getOneTableData($tablename)
	{
		self::$cache_result="";
		$classname=UtilHugeAmount::getClassname($tablename);
		if (contains($classname,array("Copy","Copy1","Copy2","Copy3","Copy4"))){
			return -1;
		}
		$fieldarr=Manager_Db::newInstance()->dbinfo()->fieldInfoList($tablename);//获取数据表字段
		/*将数据表字段名存入数组*/
		foreach($fieldarr as $fieldname=>$field){
			self::$fieldInfos[$tablename][$fieldname]["Field"]=$field["Field"];
			self::$fieldInfos[$tablename][$fieldname]["Type"]=$field["Type"];
			self::$fieldInfos[$tablename][$fieldname]["Comment"]=$field["Comment"];
			self::$fieldInfos[$tablename][$fieldname]["Key"]=$field["Key"];
			if ($field["Null"]=='NO'){
				self::$fieldInfos[$tablename][$fieldname]["IsPermitNull"]=false;
			}else{
				self::$fieldInfos[$tablename][$fieldname]["IsPermitNull"]=true;
			}
		}

		$countStr="select count(*) from $tablename";
		$count=sqlExecute($countStr);//获取数据表当前数据数目
		$fields=self::fieldnames($tablename);

		$isRelationTable=UtilHugeAmount::isRelationTable($tablename);
		if ($isRelationTable) {
			if ($count>=self::$num){
				$count=0;
			}else{
				self::$cache_result.="delete from $tablename;\r\n";
				self::$cache_result.=self::relationTableData($tablename,$classname,$fields);
				$count=self::$num;
			}
			self::$num_relation_table+=1;
		}else{
			//需要注入初始化数据,默认注入20条
			if ($count<=0){
				self::$cache_result.=self::tableInitData($tablename,$classname,$fields);
				$count=self::$init_data_num;
			}
			if ($count>=self::$num){
				$count=0;
			}else{
				$fieldnames=implode(",",$fields);
				/*循环出需要执行的SQL语句*/
				while ($count< self::$num) {
					self::$cache_result.="insert into $tablename($fieldnames) select $fieldnames from $tablename;\r\n";
					$count=$count*2;
				}

				if ($count>self::$num){
			   		$classname=UtilHugeAmount::getClassname($tablename);
			   		$key_id=UtilHugeAmount::keyIDColumn($classname);
					self::$cache_result.="delete from $tablename where $key_id>=(select a.id from (select $key_id as id from $tablename limit ".self::$num.",1) as a);\r\n";
					$count=self::$num;
				}
			}
		}
		self::$result.=self::$cache_result;
		return $count;
	}

	/**
	 +----------------------------------------------------------<br/>
	 * 压力测试，数据库测试<br/>
	 +----------------------------------------------------------<br/>
	 */
	public static function createDatabaseData()
	{
	    $tablelists= Manager_Db::newInstance()->dbinfo()->tableList();//获取数据库表列表
		if(self::$isShowReport){
			echo "预计生成数据数量级：".self::$num."<br/>";
			echo "SQL脚本文件地址：".self::$script_sql_path."<br/>";
			self::$num_table=count($tablelists);
			echo "共计总计表数:".self::$num_table."<br/>";
		}
		$count_undo=0;
		$count_dic=0;
		$count_log=0;
		$echo_showresult="";
	    foreach ($tablelists as $tablename) {
	    	$tablename_up=strtoupper($tablename);
	    	if (contain($tablename_up,"_DIC_")) {
	    		$count_dic+=1;
	    		continue;
	    	}
	    	if (contain($tablename_up,"_LOG_")) {
	    		$count_log+=1;
	    		continue;
	    	}
			$count=self::getOneTableData($tablename);
			if ($count<0) {
				$count_undo+=1;
				continue;
			}

			if(self::$isShowReport){
				$echo_showresult.= "所测试的数据表：$tablename<br/>实际生成数据数目：$count<br/>";
			}
			if(self::$isShowSql){
				$table_change = array("\r\n"=>"<br>");
				$show_result= strtr(self::$cache_result,$table_change);
				$echo_showresult.= $show_result."<br/>";
			}
	    }
		if(self::$isShowReport){
			$num_relation_table=self::$num_relation_table;
			$num_main_table=self::$num_table-$num_relation_table-$count_undo;
	    	echo "主要实体表数:".$num_main_table.",中间关系表数:".$num_relation_table.",数据字典表:$count_dic,日志表:$count_log,无关的表:".$count_undo."<br/><br/>";
		}
		echo $echo_showresult;
	    file_put_contents(self::$script_sql_path,self::$result);//,FILE_APPEND
	}

	/**
	 +----------------------------------------------------------<br/>
	 * 初始化数据,自动生成随机数据<br/>
	 +----------------------------------------------------------<br/>
	 */
	public static function fieldnames($tablename)
	{
		$field_arr=self::$fieldInfos[$tablename];
		$field_names=array_keys($field_arr);
	   	$classname=UtilHugeAmount::getClassname($tablename);
	   	$key_id=strtoupper(UtilHugeAmount::keyIDColumn($classname));
	   	foreach($field_names as $fieldname){
	   		$fieldname_u=strtoupper($fieldname);
	   		if ($key_id==$fieldname_u){
	   			unset($field_arr[$fieldname]);
	   			break;
	   		}
	   	}
		return array_keys($field_arr);
	}

	/**
	 +----------------------------------------------------------<br/>
	 * 初始化数据,自动生成随机数据<br/>
	 * @param string $tablename 表名称
	 * @param string $classname 列名称
	 * @param string $fields 需要插入的列规格
	 +----------------------------------------------------------<br/>
	 */
	public static function relationTableData($tablename,$classname,$fields)
	{
		$i=0;
		$fieldnames=implode(",",$fields);
	   	$result.="insert into $tablename ($fieldnames) values\r\n";
		$field_arr=self::$fieldInfos[$tablename];
		$counter=array();
		$first_field="";
		$second_field="";
		$limit_count=self::$num/5;

		for ($i=0; $i <$limit_count ; $i++) {
			$values=array();
			foreach($fields as $fieldname){
				$is_relation_key_id=false;
				$r_fields=self::$relation_fields[$tablename];
				if (in_array($fieldname, $r_fields))$is_relation_key_id=true;
				$fieldname_flag=str_replace("_id", "", strtolower($fieldname));
				if (contain($tablename,$fieldname_flag))$is_relation_key_id=true;
				if ($is_relation_key_id) {
					if (empty($first_field))$first_field=$fieldname;
					if(empty($counter[$fieldname]))$counter[$fieldname]=1;
					$values[$fieldname]=$counter[$fieldname];
					if ($first_field==$fieldname) {
						if (!empty($second_field)) {
							if($counter[$second_field]>100){
								$counter[$fieldname]+=1;
								$values[$fieldname]=$counter[$fieldname];
								$counter[$second_field]=1;
							}
						}
					}else{
						$second_field=$fieldname;
						$counter[$fieldname]+=1;
					}
				}else{
					$type=$field_arr[$fieldname]["Type"];
					$typep=UtilHugeAmount::column_type($type);
					switch ($typep) {
						case "int":
						case "bigint":
						case "decimal":
							$tmp=strtoupper($fieldname);
							if ($tmp=="COMMITTIME"){
								$values[$fieldname] = "'".UtilDateTime::now(EnumDateTimeFORMAT::TIMESTAMP)."'";
							}else{
								$fieldname_f=strtoupper($fieldname);
								if(contain($fieldname_f,"ID")){
									$values[$fieldname]=rand(1,self::$num-1);
								}else {
									$num=UtilString::build_count_rand(1);
									$values[$fieldname] = $num[0];
								}
							}
							break;
	                    case "bit":
							$value=rand(0,1);
							$values[$fieldname]=($value==0)?'true':'false';
	                        break;
						case "enum":
							$enum_arr=UtilHugeAmount::getEnmu($type);
							$value=rand(0,count($enum_arr)-1);
							$values[$fieldname] = $enum_arr[$value];
							break;
	                    case "timestamp":
							$values[$fieldname] = "'".UtilDateTime::now()."'";
	                        break;
						case "datetime":
							$values[$fieldname] = "'".UtilDateTime::now()."'";
							break;
						default:
							$comment=$field_arr[$fieldname]["Comment"];
							$isUsername=UtilHugeAmount::columnIsUsername($tablename,$fieldname);
							if ($isUsername){
								$num=rand(1,2);
								$text=UtilString::rand_string(1,5).UtilString::rand_string($num,4);
							}else{
								$isName=UtilHugeAmount::columnIsName($tablename,$fieldname);
								if ($isName){
									$num=rand(2,8);
									$text=UtilString::rand_string($num,4);
								}else{
									$isTextArea=UtilHugeAmount::columnIsTextArea($fieldname,$typep);
									if ($isTextArea){
										$text=implode(UtilString::build_format_rand(".",50));
									}else{
										$isImage   =UtilHugeAmount::columnIsImage($fieldname,$comment);
										if ($isImage){
											$suffix=array("jpg","png","gif");
											$order=rand(0,count($suffix)-1);
											$text=Gc::$upload_url."images/".implode(UtilString::build_format_rand(".",8)).".".$suffix[$order];
										}else{
											$isEmail   =UtilHugeAmount::columnIsEmail($fieldname,$comment);
											if ($isEmail){
												$num=rand(6,30);
												$domain=array("gmail.com","hotmail.com","qq.com","yahoo.com","sina.com","sohu.com","163.com","263.net");
												$order=rand(0,count($domain)-1);
												$text=implode(UtilString::build_format_rand(".",$num))."@".$domain[$order];
											}else{
												$isPasswd  =UtilHugeAmount::columnIsPassword($tablename,$fieldname);
												if ($isPasswd){
													$text=implode(UtilString::build_format_rand("#",6));
												}else{
													$isMobile=UtilHugeAmount::columnIsMobile($fieldname,$comment);
													if ($isMobile) {
														$text=UtilMobile::randMobile(1);
														$text=$text[0];
													} else {
														$isNum=UtilHugeAmount::columnIsNum($fieldname,$comment);
														if ($isNum) {
															$num=rand(1,5);
															$num_value=UtilString::build_count_rand(1,$num);
															$text = $num_value[0];
															if(startWith($text,"0")){
																$text="1".$text;
															}
														} else {
															$text=implode(UtilString::build_format_rand(".",6));
														}
													}
												}
											}
										}
									}
								}
							}
							$values[$fieldname] = "'".$text."'";
							break;
					}
				}
			}
			$keys=implode(",",array_keys($values));
			$values=implode(",",array_values($values));
			$result.="($values),\r\n";
		}
		$result=substr($result, 0,strlen($result)-3).";";
		return $result;
	}

	/**
	 +----------------------------------------------------------<br/>
	 * 初始化数据,自动生成随机数据<br/>
	 * @param string $tablename 表名称
	 * @param string $classname 列名称
	 * @param string $fields 需要插入的列规格
	 +----------------------------------------------------------<br/>
	 */
	public static function tableInitData($tablename,$classname,$fields)
	{
		$i=0;
	   	$result="";
		$field_arr=self::$fieldInfos[$tablename];
		for ($i=0; $i <self::$init_data_num ; $i++) {
			$values=array();
			foreach($fields as $fieldname){
				$type=$field_arr[$fieldname]["Type"];
				$typep=UtilHugeAmount::column_type($type);
				switch ($typep) {
					case "int":
					case "bigint":
					case "decimal":
						$tmp=strtoupper($fieldname);
						if ($tmp=="COMMITTIME"){
							$values[$fieldname] = "'".UtilDateTime::now(EnumDateTimeFORMAT::TIMESTAMP)."'";
						}else{
							$fieldname_f=strtoupper($fieldname);
							if(contain($fieldname_f,"ID")){
								$values[$fieldname]=rand(1,self::$num-1);
							}else {
								$num=UtilString::build_count_rand(1);
								$values[$fieldname] = $num[0];
							}
						}
						break;
                    case "bit":
						$value=rand(0,1);
						$values[$fieldname]=($value==0)?'true':'false';
                        break;
					case "enum":
						$enum_arr=UtilHugeAmount::getEnmu($type);
						$value=rand(0,count($enum_arr)-1);
						$values[$fieldname] = $enum_arr[$value];
						break;
                    case "timestamp":
						$values[$fieldname] = "'".UtilDateTime::now()."'";
                        break;
					case "datetime":
						$values[$fieldname] = "'".UtilDateTime::now()."'";
						break;
					default:
						$comment=$field_arr[$fieldname]["Comment"];
						$isUsername=UtilHugeAmount::columnIsUsername($tablename,$fieldname);
						if ($isUsername){
							$num=rand(1,2);
							$text=UtilString::rand_string(1,5).UtilString::rand_string($num,4);
						}else{
							$isName=UtilHugeAmount::columnIsName($tablename,$fieldname);
							if ($isName){
								$num=rand(2,8);
								$text=UtilString::rand_string($num,4);
							}else{
								$isTextArea=UtilHugeAmount::columnIsTextArea($fieldname,$typep);
								if ($isTextArea){
									$text=implode(UtilString::build_format_rand(".",50));
								}else{
									$isImage   =UtilHugeAmount::columnIsImage($fieldname,$comment);
									if ($isImage){
										$suffix=array("jpg","png","gif");
										$order=rand(0,count($suffix)-1);
										$text=Gc::$upload_url."images/".implode(UtilString::build_format_rand(".",8)).".".$suffix[$order];
									}else{
										$isEmail   =UtilHugeAmount::columnIsEmail($fieldname,$comment);
										if ($isEmail){
											$num=rand(6,30);
											$domain=array("gmail.com","hotmail.com","qq.com","yahoo.com","sina.com","sohu.com","163.com","263.net");
											$order=rand(0,count($domain)-1);
											$text=implode(UtilString::build_format_rand(".",$num))."@".$domain[$order];
										}else{
											$isPasswd  =UtilHugeAmount::columnIsPassword($tablename,$fieldname);
											if ($isPasswd){
												$text=implode(UtilString::build_format_rand("#",6));
											}else{
												$isMobile=UtilHugeAmount::columnIsMobile($fieldname,$comment);
												if ($isMobile) {
													$text=UtilMobile::randMobile(1);
													$text=$text[0];
												} else {
													$isNum=UtilHugeAmount::columnIsNum($fieldname,$comment);
													if ($isNum) {
														$num=rand(1,5);
														$num_value=UtilString::build_count_rand(1,$num);
														$text = $num_value[0];
														if(startWith($text,"0")){
															$text="1".$text;
														}
													} else {
														$text=implode(UtilString::build_format_rand(".",6));
													}
												}
											}
										}
									}
								}
							}
						}
						$values[$fieldname] = "'".$text."'";
						break;
				}
			}
			if(empty($keys))$keys=implode(",",array_keys($values));
			$values=implode(",",array_values($values));
			$result.="($values),\r\n";
		}
		$result="insert into $tablename ($keys) values \r\n".substr($result, 0,strlen($result)-3).";\r\n";
		return $result;
	}

	/**
	 +----------------------------------------------------------<br/>
	 * 特定表和列的处理<br/>
	 +----------------------------------------------------------<br/>
	 */
	public static function aiForBusiness()
	{
		$filter=array(
			 "Member"=>array(
				"username",
				"realname"
			 ),
			 "Product"=>array(
				"product_name",
				"message"
			 ),
			 "Order"=>array(
				"order_no"
			 ),
			 "Consult" =>array(
				"title",
				"comments",
				"reply"
			 )
		);
	}
}

/**
* 工具类:提供给海量数据存储的辅助支持
*/
class UtilHugeAmount
{

	/**
	 * 是否中间关系表
	 * @param string $tablename 表名称
	 */
	public static function isRelationTable($tablename)
	{
		$tablename=strtoupper($tablename);
		if (contain($tablename,"_RE_")) {
			return true;
		}
		return false;

	}

	/**
	 * 列是否是email
	 * @param string $column_name 列名称
	 * @param mixed $column_comment 列注释
	 */
	public static function columnIsNum($column_name,$column_comment)
	{
		$column_name=strtoupper($column_name);
		if (contains($column_name,array("COUNT","WIDTH","HEIGHT"))||contains($column_comment,array("计数","宽度","高度"))){
			return true;
		}
		return false;
	}
	/**
	 * 列是否是email
	 * @param string $column_name 列名称
	 * @param mixed $column_comment 列注释
	 */
	public static function columnIsMobile($column_name,$column_comment)
	{
		$column_name=strtoupper($column_name);
		if (contains($column_name,array("MOBILE","PHONE"))||contains($column_comment,array("手机","电话"))){
			return true;
		}
		return false;
	}
	/**
	 * 列是否大量文本输入应该TextArea输入
	 * @param string $column_name 列名称
	 * @param string $column_type 列类型
	 */
	public static function columnIsTextArea($column_name,$column_type)
	{
		$column_name=strtoupper($column_name);
		if (contain($column_name,"ID")){
			return false;
		}
		if (((self::column_length($column_type)>=500)&&(!contain($column_name,"IMAGES"))&&(!contain($column_name,"LINK"))&&(!contain($column_name,"ICO")))
			 ||(contains($column_name,array("INTRO","MEMO","CONTENT")))||(self::column_type($column_type)=='text')||(self::column_type($column_type)=='longtext')){  //&&(!contain($column_name,"addr"))
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 列是否是图片路径
	 * @param string $column_name 列名称
	 * @param mixed $column_comment 列注释
	 */
	public static function columnIsImage($column_name,$column_comment)
	{
		$column_name=strtoupper($column_name);
		if (contain($column_name,"ID")){
			return false;
		}
		if (contains($column_name,array("IMAGE","IMG","ICO","LOGO","PIC"))){
			return true;
		}
		return false;
	}

	/**
	 * 列是否是email
	 * @param string $column_name 列名称
	 * @param mixed $column_comment 列注释
	 */
	public static function columnIsEmail($column_name,$column_comment)
	{
		$column_name=strtoupper($column_name);
		if ((contain($column_name,"EMAIL")||contains($column_comment,array("邮件","邮箱")))&&(!contain($column_name,"IS"))){
			return true;
		}
		return false;
	}

	/**
	 * 列是否是密码
	 * @param string $tablename 表名称
	 * @param string $column_name 列名称
	 */
	public static function columnIsPassword($table_name,$column_name)
	{

		$table_name=strtoupper($table_name);
		if (contains($table_name,array("MEMBER","ADMIN","USER"))){
			$column_name=strtoupper($column_name);
			if (contain($column_name,"PASSWORD")){
				return true;
			}
		}
		return false;
	}

	/**
	 * 是否名称
	 * @param string $tablename 表名称
	 * @param string $column_name 列名称
	 */
	public static function columnIsName($table_name,$column_name)
	{
		if (contains($table_name,array("MEMBER","ADMIN","USER"))){
			$column_name=strtoupper($column_name);
			if (contain($column_name,"NAME")){
				return false;
			}
		}
		$column_name=strtoupper($column_name);
		if (contain($column_name,"NAME")){
			return true;
		}
		return false;
	}

	/**
	 * 是否用户名
	 * @param string $tablename 表名称
	 */
	public static function columnIsUsername($table_name,$column_name)
	{
		$table_name=strtoupper($table_name);
		if (contains($table_name,array("MEMBER","ADMIN","USER"))){
			$column_name=strtoupper($column_name);
			if (contain($column_name,"REALNAME")){
				return true;
			}
		}
		return false;
	}

	/**
	 * 表中列的长度定义
	 * @param string $type
	 */
	public static function column_length($type)
	{
		if (UtilString::contain($type,"(")){
			list($typep,$length)=split('[()]', $type);
		}else{
			$length=1;
		}
		return $length;
	}

	/**
	 * 表中列的类型定义
	 * @param string $type
	 */
	public static function column_type($type)
	{
		if (UtilString::contain($type,"(")){
			list($typep,$length)=split('[()]', $type);
		}else{
			$typep=$type;
		}
		return $typep;
	}

	/**
	 * 抓取枚举类型的值并存入数组
	 */
	public static function getEnmu($enmu)
	{
		list($typep,$value)=split('[()]',$enmu);
		$decs= explode(",",$value);
		return $decs;
	}

	/**
	 * 获取数据对象的ID列名称
	 * @param mixed $dataobject 数据对象实体|对象名称
	 */
	public static function keyIDColumn($dataobject)
	{
		return DataObjectSpec::getRealIDColumnNameStatic($dataobject);
	}

	/**
	 * 从表名称获取对象的类名【头字母大写】。
	 * @param string $tablename 表名称
	 * @return string 返回对象的类名
	 */
	public static function getClassname($tablename)
	{
		if (in_array($tablename, Config_Db::$orm)) {
			$classname=array_search($tablename, Config_Db::$orm);
		}else {
			$classnameSplit= explode("_", $tablename);
			$classnameSplit=array_reverse($classnameSplit);
			$classname=ucfirst($classnameSplit[0]);
		}
		return $classname;
	}
}

HugeAmountDataPush::init();
HugeAmountDataPush::$relation_fields=array(
	"ns_product_re_productspec"=>array("attribute_id")
);
HugeAmountDataPush::createDatabaseData();
// HugeAmountDataPush::createTablesData(array("ns_ads","ns_member_admin"));
// HugeAmountDataPush::createTablesData("ns_product_re_productspec");
?>
