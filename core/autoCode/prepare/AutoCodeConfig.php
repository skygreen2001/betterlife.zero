<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成配置文件<br/>
 +---------------------------------<br/>  
 * @category betterlife
 * @package core.autoCode   
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
     * 自动生成配置
     */
    public static function run()
    {
        $dest_directory=Gc::$nav_root_path."tools".DIRECTORY_SEPARATOR."tools".DIRECTORY_SEPARATOR."autoCode".DIRECTORY_SEPARATOR;
        $filename=$dest_directory."autocode.config.xml";
        if (file_exists($filename)){
            $filename=$dest_directory."autocode_create.config.xml";

        }
        self::$config_classes=array("class"=>array());
        self::$table_key_map=array();
        self::init();     
        foreach (self::$fieldInfos as $tablename=>$fieldInfo){
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
        foreach (self::$fieldInfos as $tablename=>$fieldInfo){
            $classname=self::getClassname($tablename);
            foreach ($relation_keys as  $relation_key) {
                self::$config_classes["class"][self::$table_key_map[$classname]][$relation_key]=array(
                );
            }
        }
        foreach (self::$fieldInfos as $tablename=>$fieldInfo){
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
        foreach (self::$fieldInfos as $tablename=>$fieldInfo){                      
            foreach ($relation_keys as $relation_key) {   
                if (count(self::$config_classes["class"][self::$table_key_map[$classname]][$relation_key])==0){
                    unset(self::$config_classes["class"][self::$table_key_map[$classname]][$relation_key]);
                } 
            }                                                                                          
        }
        $result =UtilArray::saveXML($filename,self::$config_classes,"classes");
        echo "&nbsp;&nbsp;"."成功生成配置文件：".$filename."<br /><br />";
        return true;
    }

    /**
     * 添加查询条件配置
     * @param array $classname 数据对象类名
     * @param string $tablename 表名称  
     * @param array $fieldInfo 表列信息列表  
     * @param array $conditions 查询条件
     */
    private static function conditionsToConfig($classname,$tablename,$fieldInfo,$conditions,$showfieldname)
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
            if (contain($fieldname,"_id")&&(!contain($fieldname,"parent_id"))){
                $relation_classname=str_replace("_id", "", $fieldname);
                $relation_classname{0}=strtoupper($relation_classname{0});
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
        return $relationShows;
    }

    /**
     * 表关系主键显示配置 
     * @param array $classname 数据对象类名
     * @param array $fieldInfo 表列信息列表  
     * @param array $relation_fives 表关系主键显示
     */
    private static function relationFives($classname,$tablename,$fieldInfo,$relation_fives)
    {
        foreach  ($fieldInfo as $fieldname=>$field)
        {
            if (!self::isNotColumnKeywork($fieldname))continue;
            if ($fieldname==self::keyIDColumn($classname))continue;

            $realId=DataObjectSpec::getRealIDColumnName($classname);   
            if (contain($fieldname,"_id")&&(!contain($fieldname,"parent_id"))){
                $relation_classname=str_replace("_id", "", $fieldname);
                $relation_classname{0}=strtoupper($relation_classname{0});
                $relation_class=null;
                if (class_exists($relation_classname)) {
                    $relation_class=new $relation_classname();
                }
                if ($relation_class instanceof DataObject){
                    //belong_has_one:[当前表有归属表的标识，归属表没有当前表的标识]
                    if (!array_key_exists($realId, $relation_classname))
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

                    //has_many[当前表没有归属表的标识，归属表有当前表的标识]
                    //has_one:[当前表没有归属表的标识，归属表有当前表的标识，并且归属表里当前表的标识为Unique]
                    if (!array_key_exists($realId, $relation_classname))
                    {
                        if (array_key_exists($relation_classname, self::$table_key_map)){
                            $relation_tablename_key=self::$table_key_map[$relation_classname];
                            $instance_name=$classname;
                            $instance_name{0}=strtolower($instance_name)."s";
                            $fieldInfo_key_isunique=$fieldInfo[$fieldname];
                            $isunique=$fieldInfo_key_isunique["Key"];
                            if ($isunique=="UNI"){
                                self::$config_classes["class"][$relation_tablename_key]
                                                     ["has_one"]["relationclass"][]=array(
                                    '@attributes' => array(
                                        "name"=>$classname
                                    ),
                                    '@value' => $instance_name
                                );

                            }else{
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
}
?>
