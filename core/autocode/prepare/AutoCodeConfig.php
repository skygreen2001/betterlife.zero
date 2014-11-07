<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成配置文件<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.autocode
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeConfig extends AutoCode
{
	/**
	 * 生成条件的个数
	 */
	public static $count_condition=4;
	/**
	 * 所有的表配置数组
	 */
	private static $config_classes;
	/**
	 * 记录表和$classes["class"]里的key的对应关系，通过它直接获取到指定的表配置进行修改
	 */
	private static $table_key_map;
	/**
	 * 代码生成配置xml文件名
	 */
	private static $filename_config_xml;
	/**
	 * 代码生成配置xml网络路径
	 */
	private static $url_config_xml;

//<editor-fold defaultstate="collapsed" desc="数据结构转换成配置xml文件">
	/**
	 * 初始化
	 */
	public static function init()
	{
		self::$filename_config_xml=Gc::$nav_root_path."tools".DS."tools".DS."autocode".DS."autocode.config.xml";
		self::$url_config_xml     =Gc::$url_base."tools/tools/autocode/autocode.config.xml";
		parent::init();
	}

	/**
	 * 自动生成配置
	 * @param array|string $table_names
	 * 示例如下：
	 *  1.array:array('bb_user_admin','bb_core_blog')
	 *  2.字符串:'bb_user_admin,bb_core_blog'
	 */
	public static function run($table_names="")
	{
		$filename=self::$filename_config_xml;
		if ((!Config_AutoCode::ALWAYS_AUTOCODE_XML_NEW)&&file_exists($filename))
		{
			$filename=dirname($filenamepath)."autocode_create.config.xml";
			self::$filename_config_xml=$filename;
			self::$url_config_xml=Gc::$url_base."tools/tools/autocode/autocode_create.config.xml";
		}

		self::$config_classes=array("class"=>array());
		self::$table_key_map=array();
		self::init();

		$fieldInfos=self::fieldInfosByTable_names($table_names);
		foreach ($fieldInfos as $tablename=>$fieldInfo){
			$classname=self::getClassname($tablename);
			$current_class_config=array(
				'@attributes' => array(
					"name"=>$classname
				),
				"conditions"=>array(
					"condition"=>array()
				),
				"relationShows"=>array(
					"show"=>array()
				)
			);

			$conditions=$current_class_config["conditions"]["condition"];
			$relationShows=$current_class_config["relationShows"]["show"];

			//添加查询条件配置
			$conditions=self::conditionsToConfig($classname,$tablename,$fieldInfo,$conditions);
			//表关系主键显示配置
			$relationShows=self::relationShowsToConfig($classname,$fieldInfo,$relationShows);

			$current_class_config["conditions"]["condition"]= $conditions;
			$current_class_config["relationShows"]["show"]  = $relationShows;
			if (count($relationShows)==0){
				unset($current_class_config["relationShows"]);
			}
			self::$config_classes["class"][]=$current_class_config;
			end(self::$config_classes["class"]);
			self::$table_key_map[$classname]=key(self::$config_classes["class"]);
		}

		$relation_keys=array("has_one","belong_has_one","has_many","many_many","belongs_many_many");
		foreach ($fieldInfos as $tablename=>$fieldInfo){
			$classname=self::getClassname($tablename);
			foreach ($relation_keys as  $relation_key) {
				self::$config_classes["class"][self::$table_key_map[$classname]][$relation_key]=array(
				);
			}
		}
		foreach ($fieldInfos as $tablename=>$fieldInfo){
			$classname=self::getClassname($tablename);
			$current_class_config= self::$config_classes["class"][self::$table_key_map[$classname]];
			foreach ($relation_keys as  $relation_key) {
				$relation_fives[$relation_key]=$current_class_config[$relation_key];
			}
			//数据对象之间关系配置
			$relation_fives=self::relationFives($classname,$tablename,$fieldInfo,$relation_fives);
			foreach ($relation_keys as $relation_key) {
				$current_class_config[$relation_key]  = $relation_fives[$relation_key];
			}
			self::$config_classes["class"][self::$table_key_map[$classname]]=$current_class_config;
		}
		foreach ($fieldInfos as $tablename=>$fieldInfo){
			foreach ($relation_keys as $relation_key) {
				$t_k_m=self::$table_key_map[$classname];
				$c_c=self::$config_classes["class"][$t_k_m];
				if (array_key_exists($relation_key, $c_c)){
					$c_c_r=$c_c[$relation_key];
					if (count($c_c_r)==0)unset($c_c_r);
				}
			}
		}
		$result =UtilArray::saveXML($filename,self::$config_classes,"classes");
		if(!file_exists($filename)){
			$dir_autocode=Gc::$nav_root_path."tools".DS."tools".DS."autocode".DS;
			die("<p style='font: 15px/1.5em Arial;margin:15px;line-height:2em;'>因为安全原因，需要手动在操作系统中创建目录:".$dir_autocode."<br/>".
				"Linux系统需要执行指令:<br/>".str_repeat("&nbsp;",8).
				"sudo chmod -R 0777 ".$dir_autocode."</p>");
		}else{
			self::$showPreviewReport.= "<div style='width: 1000px; margin-left: 80px;'>";
			self::$showPreviewReport.= "<a href='javascript:' style='cursor:pointer;' onclick=\"(document.getElementById('showCreateConfigXml').style.display=(document.getElementById('showCreateConfigXml').style.display=='none')?'':'none')\">显示生成代码配置文件报告</a>";
			self::$showPreviewReport.= "<div id='showCreateConfigXml' style='display: none;'>";
			self::$showPreviewReport.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;成功生成配置文件：<font color='#0000FF'><a target='_blank' href='".self::$url_config_xml."'>".$filename."</a></font><br /><br />";
			self::$showPreviewReport.= "</div>";
			self::$showPreviewReport.= "</div>";
		}
		return true;
	}

	/**
	 * 添加查询条件配置
	 * @param array $classname 数据对象类名
	 * @param string $tablename 表名称
	 * @param array $fieldInfo 表列信息列表
	 * @param array $conditions 查询条件
	 */
	private static function conditionsToConfig($classname,$tablename,$fieldInfo,$conditions)
	{
		if (!self::isMany2ManyByClassname($classname)){
			$showfieldname=self::getShowFieldNameByClassname($classname,true);
			if (!empty($showfieldname)){
				$exists_condition=array();
				if ((!contain($tablename,Config_Db::TABLENAME_RELATION))&&(!in_array($showfieldname,$exists_condition))) {
					$conditions[]=array("@value"=>$showfieldname);
					$exists_condition[]=$showfieldname;
				}
			}
		}
		foreach  ($fieldInfo as $fieldname=>$field)
		{
			if (!self::isNotColumnKeywork($fieldname))continue;
			if ($fieldname==self::keyIDColumn($classname))continue;
			if ((!in_array($fieldname,$exists_condition))&&contains($fieldname,array("name","title"))&&($fieldname!=$showfieldname)&&(!contain($fieldname,"_id"))){
				$conditions[]=array("@value"=>$fieldname);
				$exists_condition[]=$fieldname;
			}

			if (count($conditions)<self::$count_condition){
				if ((!in_array($fieldname,$exists_condition))&&contains($fieldname,array("code","_no","status","type"))&&(!contain($fieldname,"_id"))){
					$conditions[]=array("@value"=>$fieldname);
					$exists_condition[]=$fieldname;
				}
				if (contain($fieldname,"_id")&&(!contain($fieldname,"parent_id"))){
					$relation_classname=str_replace("_id", "", $fieldname);
					$relation_classname{0}=strtoupper($relation_classname{0});
					$relation_class=null;
					if (class_exists($relation_classname)) {
						$relation_class=new $relation_classname();
					}
					if ((!in_array($fieldname,$exists_condition))&&($relation_class instanceof DataObject)){
						$showfieldname_relation=self::getShowFieldNameByClassname($relation_classname);
						if (!in_array($showfieldname_relation,$exists_condition)){
							$conditions[]=array(
								'@attributes' => array(
									"relation_class"=>$relation_classname,
									"show_name"=>$showfieldname_relation
								),
								"@value"=>$fieldname);
							$exists_condition[]=$fieldname;
						}
					}
				}
			}else{
				break;
			}
		}
		return $conditions;
	}

	/**
	 * 表关系主键显示配置
	 * @param array $classname 数据对象类名
	 * @param array $fieldInfo 表列信息列表
	 * @param array $relationShows 表关系主键显示
	 */
	private static function relationShowsToConfig($classname,$fieldInfo,$relationShows)
	{
		foreach  ($fieldInfo as $fieldname=>$field)
		{
			if (!self::isNotColumnKeywork($fieldname))continue;
			if ($fieldname==self::keyIDColumn($classname))continue;
			if (contain($fieldname,"_id")||(contain(strtoupper($fieldname),"PARENT_ID"))){
				$relation_classname=str_replace("_id", "", $fieldname);
				$relation_classname{0}=strtoupper($relation_classname{0});
				if (strtoupper($relation_classname)=="PARENT"){
					$showfieldname=self::getShowFieldNameByClassname($classname);
					$relationShows[]=array(
						'@attributes' => array(
							"local_key"=>$fieldname,
							"relation_class"=>$classname
						),
						"@value"=>$showfieldname);
				}else{
					$relation_class=null;
					if (class_exists($relation_classname)) {
						$relation_class=new $relation_classname();
					}
					if ($relation_class instanceof DataObject){
						$showfieldname=self::getShowFieldNameByClassname($relation_classname);
						$relationShows[]=array(
							'@attributes' => array(
								"local_key"=>$fieldname,
								"relation_class"=>$relation_classname
							),
							"@value"=>$showfieldname);
					}
				}
			}
		}
		return $relationShows;
	}

	/**
	 * 表五种关系映射配置
	 * @param array $classname 数据对象类名
	 * @param string $tablename 表名称
	 * @param array $fieldInfo 表列信息列表
	 * @param array $relation_fives 表五种关系映射配置
	 */
	private static function relationFives($classname,$tablename,$fieldInfo,$relation_fives)
	{
		foreach  ($fieldInfo as $fieldname=>$field)
		{
			if (!self::isNotColumnKeywork($fieldname))continue;
			if ($fieldname==self::keyIDColumn($classname))continue;

			$realId=DataObjectSpec::getRealIDColumnName($classname);
			if (contain($fieldname,"_id")||(contain($fieldname,"parent_id"))){
				$relation_classname=str_replace("_id", "", $fieldname);
				$relation_classname{0}=strtoupper($relation_classname{0});
				if (strtoupper($relation_classname)=="PARENT"){
					$instance_name=$classname;
					$instance_name{0}=strtolower($instance_name);
					$relation_fives["belong_has_one"]["relationclass"][]=array(
						'@attributes' => array(
							"name"=>$classname
						),
						'@value' => $instance_name."_p"
					);
				}else{
					$relation_class=null;
					if (class_exists($relation_classname)) {
						$relation_class=new $relation_classname();
					}
					if ($relation_class instanceof DataObject){
						//belong_has_one:[当前表有归属表的标识，归属表没有当前表的标识]
						if (!array_key_exists($realId, $relation_class))
						{
							$instance_name=$relation_classname;
							$instance_name{0}=strtolower($instance_name);
							$relation_fives["belong_has_one"]["relationclass"][]=array(
								'@attributes' => array(
									"name"=>$relation_classname
								),
								'@value' => $instance_name
							);
						}

						//has_many[归属表没有当前表的标识，当前表有归属表的标识]
						//has_one:[归属表没有当前表的标识，当前表有归属表的标识，并且当前表里归属表的标识为Unique]
						if (!array_key_exists($realId, $relation_class))
						{
							if (array_key_exists($relation_classname, self::$table_key_map)){
								$relation_tablename_key=self::$table_key_map[$relation_classname];
								$instance_name=$classname;
								$instance_name{0}=strtolower($instance_name);
								$isunique=Manager_Db::newInstance()->dbinfo()->hasUnique($tablename,$fieldname);
								if ($isunique){
									self::$config_classes["class"][$relation_tablename_key]
														 ["has_one"]["relationclass"][]=array(
										'@attributes' => array(
											"name"=>$classname
										),
										'@value' => $instance_name
									);

								}else{
									$instance_name.="s";
									$is_create_hasmany=true;
									if ((!Config_AutoCode::AUTOCONFIG_CREATE_FULL)&&(self::isMany2ManyByClassname($classname))){
										$is_create_hasmany=false;
									}
									if ($is_create_hasmany){
										self::$config_classes["class"][$relation_tablename_key]
															 ["has_many"]["relationclass"][]=array(
											'@attributes' => array(
												"name"=>$classname
											),
											'@value' => $instance_name
										);
									}
								}
							}
						}
					}
				}
			}
		}

		if (contain($tablename,Config_Db::TABLENAME_RELATION)){
			if (self::isMany2ManyByClassname($classname))
			{
				$fieldInfo_m2m=self::$fieldInfos[self::getTablename($classname)];
				unset($fieldInfo_m2m['updateTime'],$fieldInfo_m2m['commitTime']);
				$realId=DataObjectSpec::getRealIDColumnName($classname);
				unset($fieldInfo_m2m[$realId]);
				if (count($fieldInfo_m2m)==2){
					//many_many[在关系表中有两个关系主键，并且表名的前半部分是其中一个主键]
					//belongs_many_many[在关系表中有两个关系主键，并且表名的后半部分是其中一个主键]
					$class_onetwo=array();
					foreach (array_keys($fieldInfo_m2m) as $fieldname_m2m)
					{
						$class_onetwo_element=str_replace("_id", "", $fieldname_m2m);
						$class_onetwo[]=$class_onetwo_element;
					}

					if ($class_onetwo[0].$class_onetwo[1]==strtolower($classname)){
						$ownerClassname=$class_onetwo[0];
						$belongClassname=$class_onetwo[1];
						$ownerInstancename=$class_onetwo[0]."s";
						$belongInstancename=$class_onetwo[1]."s";
					}else if ($class_onetwo[1].$class_onetwo[0]==strtolower($classname)){
						$ownerClassname=$class_onetwo[1];
						$belongClassname=$class_onetwo[0];
						$ownerInstancename=$class_onetwo[1]."s";
						$belongInstancename=$class_onetwo[0]."s";
					}
					$ownerClassname{0}=strtoupper($ownerClassname{0});
					$belongClassname{0}=strtoupper($belongClassname{0});

					$relation_tablename_key_m2m=self::$table_key_map[$ownerClassname];
					if (self::$config_classes["class"][$relation_tablename_key_m2m]){
						self::$config_classes["class"][$relation_tablename_key_m2m]
											 ["many_many"]["relationclass"][]=array(
							'@attributes' => array(
								"name"=>$belongClassname
							),
							'@value' => $belongInstancename
						);
					}
					$relation_tablename_key_m2m=self::$table_key_map[$belongClassname];
					if (self::$config_classes["class"][$relation_tablename_key_m2m]){
						self::$config_classes["class"][$relation_tablename_key_m2m]
											 ["belongs_many_many"]["relationclass"][]=array(
							'@attributes' => array(
								"name"=>$ownerClassname
							),
							'@value' => $ownerInstancename
						);
					}
				}
			}
		}
		return $relation_fives;
	}
//</editor-fold>


//<editor-fold defaultstate="collapsed" desc="配置xml文件转换成数据结构">
	/**
	 * 代码生成配置xml文件转换成数据结构
	 * @param array|string $table_names
	 * 示例如下：
	 *  1.array:array('bb_user_admin','bb_core_blog')
	 *  2.字符串:'bb_user_admin,bb_core_blog'
	 */
	public static function Decode($table_names="")
	{
		self::init();
		//读取配置文件里查询条件和关系列显示的配置
		if (file_exists(self::$filename_config_xml))
		{
			$classes=UtilXmlSimple::fileXmlToObject(self::$filename_config_xml);
			if(!empty($table_names)){
				if (is_string($table_names))$table_names=explode(",", $table_names);
				$class_names=array();
				foreach ($table_names as $table_name) {
					$class_names[]=AutoCodeConfig::getClassname($table_name);
				}
				if (is_string($class_names)){
					$class_names=explode(",",$class_names);
				}
				$xpath_arr=array();
				if ($class_names&&(count($class_names)>0)){
					for($i=0;$i<count($class_names);$i++){
						if (!empty($class_names[$i])){
							$class_name=$class_names[$i];
							$xpath_arr[]="@name='$class_name'";
						}
					}
				}
				if(!empty($xpath_arr)&&(count($xpath_arr)>0)){
					$xpath_str=implode(" or ", $xpath_arr);
					$dataobjects = $classes->xpath("//class[$xpath_str]");
				}
			}else{
				$dataobjects = $classes->xpath("//class");
			}
			if($dataobjects){
				foreach ($dataobjects as $dataobject) {
					$attributes=$dataobject->attributes();
					$classname=$attributes->name."";
					$conditions_obj=$dataobject->conditions->condition;
					$conditions=array("relation_show"=>array());
					foreach ($conditions_obj as $condition) {
						$attributes_condition=$condition->attributes();
						$condition_name=$condition."";
						if ($attributes_condition){
							$con_relation_class=$attributes_condition->relation_class."";
							$show_name=$attributes_condition->show_name."";
							if (!array_key_exists($condition_name,$conditions["relation_show"])){
								$conditions["relation_show"][$condition_name]["relation_class"]=$con_relation_class;
								$conditions["relation_show"][$condition_name]["show_name"]=$show_name;
							}
						}
						if (!in_array($condition_name,$conditions)){
							$conditions[]=$condition_name;
						}
					}
					AutoCodeViewExt::$filter_fieldnames[$classname]=$conditions;

					$relation_viewfield_obj=$dataobject->relationShows->show;
					if (!empty($relation_viewfield_obj)){
						$relation_viewfields=array();
						foreach ($relation_viewfield_obj as $relation_viewfield) {
							$attributes=$relation_viewfield->attributes();
							$local_key=$attributes->local_key."";
							if (!array_key_exists($local_key,$relation_viewfields)){
								$relation_class=$attributes->relation_class."";
								$show=array($relation_class=>$relation_viewfield."");
								$relation_viewfields[$local_key]=$show;
							}
						}
						AutoCodeViewExt::$relation_viewfield[$classname]=$relation_viewfields;
					}

					$redundancy_table_field_obj=$dataobject->redundancy->table;
					if (!empty($redundancy_table_field_obj)){
						$redundancy_table_fields=array();
						foreach ($redundancy_table_field_obj as $redundancy_table) {
							$attributes=$redundancy_table->attributes();
							$table_name=$attributes->name."";
							$redundancy_field_obj=$redundancy_table->field;
							foreach ($redundancy_field_obj as $redundancy_field) {
								$attributes=$redundancy_field->attributes();
								$field_name=$attributes->name."";
								$field_come=$attributes->come."";
								if (empty($field_come)) $field_come=$field_name;
								$redundancy_table_fields[$table_name][$field_name]=$field_come;
							}
						}
						AutoCodeViewExt::$redundancy_table_fields[$classname]=$redundancy_table_fields;
					}

					//**********************start:导出数据对象之间关系规范定义*************************
					self::relation_specification_create($classname,$dataobject);
					//**********************end  :导出数据对象之间关系规范定义*************************
				}
			}
		}
	}

	/**
	 * 导出数据对象之间关系规范定义
	 * @param string classname 导出的类名
	 * @param array dataobject 来自xml配置文件单个类的所有说明
	 */
	private static function relation_specification_create($classname,$dataobject)
	{
		//导出一对一关系规范定义(如果存在)
		$has_one_spec=$dataobject->has_one->relationclass;
		if (!empty($has_one_spec)){
			foreach ($has_one_spec as $has_one) {
				$attributes=$has_one->attributes();
				AutoCodeDomain::$relation_all[$classname]['has_one'][$attributes->name.""]=$has_one."";
			}
		}
		//导出从属一对一关系规范定义(如果存在)
		$belong_has_one_spec=$dataobject->belong_has_one->relationclass;
		if (!empty($belong_has_one_spec)){
			foreach ($belong_has_one_spec as $belong_has_one) {
				$attributes=$belong_has_one->attributes();
				AutoCodeDomain::$relation_all[$classname]['belong_has_one'][$attributes->name.""]=$belong_has_one."";
			}
		}
		//导出一对多关系规范定义(如果存在)
		$has_many_spec=$dataobject->has_many->relationclass;
		if (!empty($has_many_spec)){
			foreach ($has_many_spec as $has_many) {
				$attributes=$has_many->attributes();
				AutoCodeDomain::$relation_all[$classname]['has_many'][$attributes->name.""]=$has_many."";
			}
		}
		//导出多对多关系规范定义(如果存在)
		$many_many_spec=$dataobject->many_many->relationclass;
		if (!empty($many_many_spec)){
			foreach ($many_many_spec as $many_many) {
				$attributes=$many_many->attributes();
				AutoCodeDomain::$relation_all[$classname]['many_many'][$attributes->name.""]=$many_many."";
			}
		}
		//导出从属于多对多关系规范定义(如果存在)
		$belongs_many_many_spec=$dataobject->belongs_many_many->relationclass;
		if (!empty($belongs_many_many_spec)){
			foreach ($belongs_many_many_spec as $belongs_many_many) {
				$attributes=$belongs_many_many->attributes();
				AutoCodeDomain::$relation_all[$classname]['belongs_many_many'][$attributes->name.""]=$belongs_many_many."";
			}
		}
	}
//</editor-fold>
}
?>
