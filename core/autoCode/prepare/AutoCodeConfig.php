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
        $filename=Gc::$nav_root_path."tools".DIRECTORY_SEPARATOR."tools".DIRECTORY_SEPARATOR."autoCode".DIRECTORY_SEPARATOR."autocode_create.config.xml";
        self::$config_classes=array("class"=>array());
        self::$table_key_map=array();
        self::init();     
        $relation_keys=array("has_one","belong_has_one","has_many","many_many","belongs_many_many");
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
            foreach ($relation_keys as  $relation_key) {
                $current_class_config[$relation_key]=array(
                    "relationclass"=>array()
                );
                $relation_fives[$relation_key]=$current_class_config[$relation_key];
            }

            $conditions=$current_class_config["conditions"]["condition"];
            $relationShows=$current_class_config["relationShows"]["show"];

            //添加查询条件配置
            $conditions=self::conditionsToConfig($classname,$tablename,$fieldInfo,$conditions);
            //表关系主键显示配置 
            $relationShows=self::relationShowsToConfig($classname,$fieldInfo,$relationShows);
            //数据对象之间关系配置
            $relation_fives=self::relationFives($fieldInfo,$relation_fives);

            $current_class_config["conditions"]["condition"]= $conditions;
            $current_class_config["relationShows"]["show"]  = $relationShows;
            if (count($relationShows)==0){
                unset($current_class_config["relationShows"]);
            }
            foreach ($relation_keys as $relation_key) {
                $current_class_config[$relation_key]  = $relation_fives[$relation_key];
                if (count($relation_fives[$relation_key])==0){
                    unset($current_class_config[$relation_key]);
                }
            }
            self::$config_classes["class"][]=$current_class_config;
        }
        $result =UtilArray::saveXML($filename,self::$config_classes,"classes");
        echo "成功生成配置文件：".$filename;
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
        $showfieldname=self::getShowFieldNameByClassname($classname);
        if (!contain($tablename,Config_Db::TABLENAME_RELATION)) $conditions[]=array("@value"=>$showfieldname);
        foreach  ($fieldInfo as $fieldname=>$field)
        {
            if (!self::isNotColumnKeywork($fieldname))continue;
            if ($fieldname==self::keyIDColumn($classname))continue;
            if (contains($fieldname,array("name","title"))&&($fieldname!=$showfieldname)&&(!contain($fieldname,"_id"))){
                $conditions[]=array("@value"=>$fieldname);
            }
            if (count($conditions)<self::$count_condition){
                if (contains($fieldname,array("code","_no","status","type"))){
                    $conditions[]=array("@value"=>$fieldname);
                }
                if (contain($fieldname,"_id")&&(!contain($fieldname,"parent_id"))){
                    $relation_classname=str_replace("_id", "", $fieldname);
                    $relation_classname{0}=strtoupper($relation_classname{0});
                    //$relation_class=null;
                    if (class_exists($relation_classname)) {
                        $relation_class=new $relation_classname();
                    }
                    if ($relation_class instanceof DataObject){
                        $showfieldname=self::getShowFieldNameByClassname($relation_classname);
                        $conditions[]=array(
                            '@attributes' => array(
                                "relation_class"=>$relation_classname,
                                "show_name"=>$showfieldname
                            ),
                            "@value"=>$fieldname);
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
                //$relation_class=null;
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
     * @param array $relationShows 表关系主键显示
     */
    private static function relationFives($classname,$fieldInfo,$relation_fives)
    {
        foreach  ($fieldInfo as $fieldname=>$field)
        {
            if (!self::isNotColumnKeywork($fieldname))continue;
            if ($fieldname==self::keyIDColumn($classname))continue;

            $realId=DataObjectSpec::getRealIDColumnName($classname);   
            if (contain($fieldname,"_id")&&(!contain($fieldname,"parent_id"))){
                $relation_classname=str_replace("_id", "", $fieldname);
                $relation_classname{0}=strtoupper($relation_classname{0});
                //$relation_class=null;
                if (class_exists($relation_classname)) {
                    $relation_class=new $relation_classname();
                }
                if ($relation_class instanceof DataObject){
                    $showfieldname=self::getShowFieldNameByClassname($relation_classname);
                    //belong_has_one:[当前表有归属表的标识，归属表没有当前表的标识]

                    //has_many[当前表没有归属表的标识，归属表有当前表的标识]
                    //has_one:[当前表没有归属表的标识，归属表有当前表的标识，并且归属表里当前表的标识为Unique]
                }
                if (!contain($tablename,Config_Db::TABLENAME_RELATION)) $conditions[]=array("@value"=>$showfieldname);
            }

            foreach (self::$fieldInfos as $tablename=>$fieldInfo){

            }
            if (!contain($tablename,Config_Db::TABLENAME_RELATION)) $conditions[]=array("@value"=>$showfieldname);
            //many_many
            //belongs_many_many


        }

    }
}
?>
