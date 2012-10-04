<?php   
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-使用ExtJs生成的表示层<br/>
 +---------------------------------<br/>  
 * @category betterlife
 * @package core.autoCode.view   
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeViewExt extends AutoCode
{                                 
    /**
     * 表示层所在的目录
     */
    public static $view_core;
    /**
     * 表示层Js文件所在的目录
     */
    public static $view_js_package;
    /**
     * 表示层完整的保存路径
     */
    public static $view_dir_full;
    /**
     * 菜单配置完整的保存路径
     */
    public static $menuconfig_dir_full;  
    /**
     * 关系列显示发送ajax请求的配置完整的保存路径
     */
    public static $ajax_dir_full;  
    /**
     * 查询过滤条件字段
     */
    public static $filter_fieldnames=array();    
    /**
     * 所有表信息
     * @var mixed
     */
    private static $tableInfoList;
    /**
     * 所有表列信息
     * @var mixed
     */
    private static $fieldInfos;
    /**
     * 设置必需的路径
     */
    public static function pathset()
    {                            
        self::$app_dir="admin";  
        self::$view_dir_full=self::$save_dir.DIRECTORY_SEPARATOR.self::$app_dir.DIRECTORY_SEPARATOR.Config_F::VIEW_VIEW.DIRECTORY_SEPARATOR.Gc::$self_theme_dir.DIRECTORY_SEPARATOR;
        self::$menuconfig_dir_full=self::$save_dir.DIRECTORY_SEPARATOR.self::$app_dir.DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR."view".DIRECTORY_SEPARATOR."menu".DIRECTORY_SEPARATOR;
        self::$ajax_dir_full=self::$save_dir.DIRECTORY_SEPARATOR.self::$app_dir.DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR."httpdata".DIRECTORY_SEPARATOR;
        self::$view_core=self::$view_dir_full.Config_F::VIEW_CORE.DIRECTORY_SEPARATOR;
        self::$view_js_package=self::$view_dir_full."js".DIRECTORY_SEPARATOR."ext".DIRECTORY_SEPARATOR;  
    }   
                  
    /**
     * 自动生成代码-使用ExtJs生成的表示层
     */
    public static function AutoCode()
    {
        self::pathset();
        //加载生成实体数据对象的目录路径，以便生成代码中数据对象的ID名称
        $dirs_root=UtilFileSystem::getAllDirsInDriectory(self::$save_dir.DIRECTORY_SEPARATOR.Gc::$appName.DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR.self::$domain_dir.DIRECTORY_SEPARATOR);
        set_include_path(get_include_path().PATH_SEPARATOR.join(PATH_SEPARATOR, $dirs_root));
        $files = new AppendIterator();
        foreach ($dirs_root as $dir) {
            $tmp=new ArrayObject(UtilFileSystem::getAllFilesInDirectory($dir));
            if (isset($tmp)) $files->append($tmp->getIterator());
        }

        foreach ($files as $file) {
            Initializer::$coreFiles[Config_F::ROOT_CORE][basename($file,Initializer::SUFFIX_FILE_PHP)]=$file;
        }        
        
        $tableList=Manager_Db::newInstance()->dbinfo()->tableList();
        $fieldInfos=array();
        $tableInfoList=Manager_Db::newInstance()->dbinfo()->tableInfoList();
        self::$tableInfoList=$tableInfoList;  
        foreach ($tableList as $tablename){
            $fieldInfoList=Manager_Db::newInstance()->dbinfo()->fieldInfoList($tablename); 
            foreach($fieldInfoList as $fieldname=>$field){
                $fieldInfos[$tablename][$fieldname]["Field"]=$field["Field"];
                $fieldInfos[$tablename][$fieldname]["Type"]=$field["Type"];
                $fieldInfos[$tablename][$fieldname]["Comment"]=$field["Comment"];          
                if ($field["Null"]=='NO'){
                    $fieldInfos[$tablename][$fieldname]["IsPermitNull"]=false; 
                }else{
                    $fieldInfos[$tablename][$fieldname]["IsPermitNull"]=true;   
                }
            }   
            $classname=self::getClassname($tablename); 
            self::$class_comments[$classname]=$tableInfoList[$tablename]["Comment"];      
        }           
        self::$fieldInfos=$fieldInfos;                                                           
        if (self::$isNoOutputCss) echo UtilCss::form_css()."\r\n";        
        AutoCodeFoldHelper::foldEffectCommon("Content_51");  
        echo "<font color='#FF0000'>采用ExtJs框架生成后端Js文件导出:</font></a>";   
        echo '<div id="Content_51" style="display:none;">';
        foreach ($fieldInfos as $tablename=>$fieldInfo){
            $defineJsFileContent=self::tableToViewJsDefine($tablename,$fieldInfo);
            if (isset(self::$save_dir)&&!empty(self::$save_dir)&&isset($defineJsFileContent)){
                $jsName=self::saveJsDefineToDir($tablename,$defineJsFileContent);
                echo "生成导出完成:$tablename=>$jsName!<br/>";   
            }else{
                echo $defineJsFileContent."<br/>";
            }       
        } 
        echo "</div><br>";
        AutoCodeFoldHelper::foldEffectCommon("Content_52");              
        echo "<font color='#FF0000'>生成后端tpl模板显示文件导出:</font></a>";  
        echo '<div id="Content_52" style="display:none;">';
        foreach ($fieldInfos as $tablename=>$fieldInfo){      
            $defineTplFileContent=self::tableToViewTplDefine($fieldInfo);
            if (isset(self::$save_dir)&&!empty(self::$save_dir)&&isset($defineTplFileContent)){
                $tplName=self::saveTplDefineToDir($tablename,$defineTplFileContent);
                echo "生成导出完成:$tablename=>$tplName!<br/>";   
            }else{
                echo $defineTplFileContent."<br/>";
            }   
        }   
        echo '</div>';
        self::tableToAjaxPhpDefine();
                             
        /**
         * 需要在后端admin/src/view/menu目录下 菜单配置文件:menu.config.xml里添加的代码 
         */
        echo "<br/><font color='#FF0000'>[需要在后端admin/src/view/menu目录下菜单配置文件:menu.config.xml里添加没有的代码]</font><br/>";  
        $section_content="";
        $appName=Gc::$appName;
        foreach($tableList as $tablename){
            $table_comment=self::tableCommentKey($tablename);
            $instancename=self::getInstancename($tablename);                         
            $section_content.="        <menu name=\"$table_comment\" address=\"index.php?go=admin.$appName.{$instancename}\" />\r\n";
        }        
        $filename="menu.config.xml";
        $output_section_content="<?xml version=\"1.0\" encoding=\"UTF-8\"?> \r\n".
                         "<menuGroups>\r\n". 
                         "    <menuGroup id=\"navWebDev\" name=\"功能区\" iconCls=\"navdesign\" show=\"true\">\r\n".  
                         $section_content.
                         "    </menuGroup> \r\n".  
                         "</menuGroups>\r\n";                                                                                            
        self::saveDefineToDir(self::$menuconfig_dir_full,$filename,$output_section_content);  
        echo  "新生成的menu.config.xml文件路径:<font color='#0000FF'>".self::$menuconfig_dir_full.$filename."</font><br/>";  
        /*    $section_content=str_replace(" ","&nbsp;",$section_content);    
            $section_content=str_replace("<","&lt;",$section_content); 
            $section_content=str_replace(">","&gt;",$section_content); 
            $section_content=str_replace("\r\n","<br />",$section_content); 
            echo  $section_content;      */   
    }  

    /**
     * 用户输入需求
     */
    public static function UserInput()
    {
        parent::UserInput("使用ExtJs框架生成表示层【用于后台】的输出文件路径参数");  
    }
  
    /**
     * 生成关系列Ajax请求php文件。
     */
    public static function tableToAjaxPhpDefine()
    {          
        $isNeedCreate=false;
        foreach (self::$relation_viewfield as $relation_viewfield) {
            foreach ($relation_viewfield as $key=>$showClasses) {
                foreach ($showClasses as $key=>$value) {
                    if (!file_exists(self::$ajax_dir_full.$filename)){  
                        $isNeedCreate=true;
                        break 3;
                    }
                }
            }
        }
        if ($isNeedCreate){
            AutoCodeFoldHelper::foldEffectCommon("Content_53");      
            echo "<font color='#FF0000'>[生成关系列Ajax请求php文件]</font></a>"; 
            echo '<div id="Content_53" style="display:none;">';
            foreach (self::$relation_viewfield as $relation_viewfield) {
                foreach ($relation_viewfield as $key=>$showClasses) {
                    foreach ($showClasses as $key=>$value) {
                        $key_i=$key;
                        $key_i{0}=strtolower($key_i{0});
                        $classname=$key;
                        $classname{0}=strtolower($classname{0});  
                        $filename =$classname.Config_F::SUFFIX_FILE_PHP;  
                        if (!file_exists(self::$ajax_dir_full.$filename)){                  
                            $result="<?php \r\n".                                  
                                     "require_once (\"../../../../init.php\"); \r\n".        
                                     "\$pageSize=15;\r\n".      
                                     "\${$value}   = !empty(\$_REQUEST['query'])&&(\$_REQUEST['query']!=\"?\")&&(\$_REQUEST['query']!=\"？\") ? trim(\$_REQUEST['query']) : \"\";\r\n".
                                     "\$condition=array();\r\n".             
                                     "if (!empty(\${$value})){\r\n".
                                     "    \$condition[\"{$value}\"]=\" like '%\${$value}%'\"; \r\n".
                                     "}\r\n".                                     
                                     "\$start=0;\r\n".
                                     "if (isset(\$_REQUEST['start'])){\r\n".
                                     "    \$start=\$_REQUEST['start']+1;\r\n".
                                     "}\r\n".
                                     "\$limit=\$pageSize;\r\n".
                                     "if (isset(\$_REQUEST['limit'])){\r\n".
                                     "    \$limit=\$_REQUEST['limit']; \r\n".
                                     "    \$limit= \$start+\$limit-1;\r\n".
                                     "}\r\n".                             
                                     "\$arr['totalCount']= {$key}::count(\$condition);\r\n".
                                     "\$arr['{$key_i}s']    = {$key}::queryPage(\$start,\$limit,\$condition);\r\n".
                                     "echo json_encode(\$arr);\r\n".
                                     "?>\r\n";   
                            $ajaxName=self::saveoAjaxPhpDefineToDir($key,$result);
                            echo "生成导出Ajax服务类PHP文件完成:$tablename=>$ajaxName".Config_F::SUFFIX_FILE_PHP."!<br/>";  
                        }           
                    }                  
                }              
            }   
            echo '</div>';
        }
    }
                     
    /**
     * 将表列定义转换成使用ExtJs生成的表示层Js文件定义的内容
     * @param string $tablename 表名
     * @param array $fieldInfo 表列信息列表
     */
    public static function tableToViewJsDefine($tablename,$fieldInfo)
    {
        $appName_alias=Gc::$appName_alias;
        $appName=Gc::$appName;        
        $table_comment=self::tableCommentKey($tablename);   
        $classname=self::getClassname($tablename);
        $instancename=self::getInstancename($tablename);   
        $appName=ucfirst($appName); 
        $appName_alias=ucfirst($appName_alias);
        //Ext "store" 中包含的fields
        $storeInfo=self::model_fields($classname,$instancename,$fieldInfo);
        $fields=$storeInfo['fields'];
        //Ext "$relationStore="中关系库Store的定义
        $relationStore=$storeInfo['relationStore'];
        $relationClassesView=$storeInfo['relationClassesView'];
        $relationViewAdds=$storeInfo['relationViewAdds'];
        $relationViewGrids=$storeInfo['relationViewGrids'];
        $viewRelationDoSelect=$storeInfo['viewRelationDoSelect'];
        $relationViewGridInit=$storeInfo['relationViewGridInit'];

        //获取Ext "EditWindow"里items的fieldLabels
        $editWindowVars=self::model_fieldLables($appName_alias,$classname,$fieldInfo);
        $fieldLabels=$editWindowVars["fieldLabels"];
        $isFileUpload=$editWindowVars["isFileUpload"];

        $textarea_Vars=self::model_textareaOnlineEditor($appName_alias,$classname,$instancename,$fieldInfo);
        $tableFieldIdName=$textarea_Vars["tableFieldIdName"];
        $textareaOnlineditor_Replace=$textarea_Vars["textareaOnlineditor_Replace"];
        $textareaOnlineditor_Add=$textarea_Vars["textareaOnlineditor_Add"];
        $textareaOnlineditor_Update=$textarea_Vars["textareaOnlineditor_Update"];
        $textareaOnlineditor_Save=$textarea_Vars["textareaOnlineditor_Save"];
        $textareaOnlineditor_Reset=$textarea_Vars["textareaOnlineditor_Reset"];
        $textareaOnlineditor_Init=$textarea_Vars["textareaOnlineditor_Init"];

        //Ext "Tabs" 中"onAddItems"包含的viewdoblock
        $viewdoblock=self::model_viewblock($classname,$fieldInfo);
        //Ext "Grid" 中包含的columns
        $columns=self::model_columns($classname,$fieldInfo);

        $filters=self::model_filters($appName_alias,$classname,$instancename,$fieldInfo);        
        //Ext "Grid" 中"tbar"包含的items中的items
        $filterFields   =$filters["filterFields"];
        //重置语句
        $filterReset    =$filters["filterReset"];
        //查询中的语句
        $filterdoSelect =$filters["filterdoSelect"];
        
        $upload_mixed   =self::model_upload($appName_alias,$classname,$instancename,$fieldInfo);
        //批量上传图片文件菜单
        $menu_uploadImg =$upload_mixed["menu_uploadImg"];
        //打开批量上传图片窗口
        $openBatchUploadImagesWindow   =$upload_mixed["openBatchUploadImagesWindow"];    
        $batchUploadImagesWinow        =$upload_mixed["batchUploadImagesWinow"]; 
        $result="";                
        require("includemodeljs.php");
        $result.=$jsContent;
        return $result;
    }   
    
    /**
     * 获取Ext "Store"里的fields
     */
    private static function model_fields($classname,$instancename,$fieldInfo)
    {        
        $fields="";//Ext "store" 中包含的fields
        $relationStore="";//Ext "$relationStore="中关系库Store的定义
        $relationClassesView="";//Ext 关系表的显示定义
        foreach ($fieldInfo as $fieldname=>$field)
        { 
            if (self::isNotColumnKeywork($fieldname))
            { 
                $datatype=self::comment_type($field["Type"]);                             
                $field_comment=$field["Comment"]; 
                if (contains($field_comment,array("日期","时间")))
                {
                    $datatype='date';        
                }            
                if ($datatype=='enum'){
                    $datatype='string';
                    $fields.="                  {name: '{$fieldname}Show',type: '".$datatype."'},\r\n";
                }
                $fields.="                  {name: '$fieldname',type: '".$datatype."'"; 
                if ($datatype=='date')
                {
                    $fields.=",dateFormat:'Y-m-d H:i:s'";
                }
                $fields.="},\r\n";     
                if (array_key_exists($classname,self::$relation_viewfield))
                {
                    $relationSpecs=self::$relation_viewfield[$classname];  
                    if (array_key_exists($fieldname,$relationSpecs))
                    {
                        $relationShow=$relationSpecs[$fieldname];
                        foreach ($relationShow as $key=>$value) {  
                            $realId=DataObjectSpec::getRealIDColumnName($key);
                            if (empty($realId))$realId=$fieldname;
                            if ((!array_key_exists($value,$fieldInfo))||($classname==$key)){  
                                $show_fieldname=$value;
                                if ($realId!=$fieldname){
                                    if (contain($fieldname,"_id")){
                                        $fieldname=str_replace("_id","",$fieldname);
                                    }
                                    $show_fieldname.="_".$fieldname;               
                                }
                                if ($value=="name"){
                                    $show_fieldname= strtolower($key)."_".$value;
                                }
                                $fields.="                  {name: '$show_fieldname',type: 'string'},\r\n";  
                            }else{
                                if ($value=="name"){
                                    $show_fieldname= strtolower($key)."_".$value;
                                    $fields.="                  {name: '$show_fieldname',type: 'string'},\r\n";  
                                }
                            }
                            $relation_classcomment=self::getClassComments($key);
                            $relation_classcomment=str_replace("关系表","",$relation_classcomment); 
                            if (contain($relation_classcomment,"\r")||contain($relation_classcomment,"\n")){
                                $relation_classcomment=preg_split("/[\s,]+/", $relation_classcomment);    
                                $relation_classcomment=$relation_classcomment[0]; 
                            }   
                            if ($classname!=$key){
                                $key{0}=strtolower($key{0});
                                if (!contain($relationStore,"{$key}Store")){
                                    $showValue=$value;
                                    if ($value=="name") $showValue=strtolower($key)."_".$value;
                                    $relationStore.=",\r\n".
                                                    "    /**\r\n".  
                                                    "     * {$relation_classcomment}\r\n".
                                                    "     */\r\n".
                                                    "    {$key}Store : new Ext.data.Store({\r\n".
                                                    "        proxy: new Ext.data.HttpProxy({\r\n".
                                                    "            url: 'home/admin/src/httpdata/{$key}.php'\r\n".
                                                    "          }),\r\n".
                                                    "        reader: new Ext.data.JsonReader({\r\n".
                                                    "            root: '{$key}s',\r\n".
                                                    "            autoLoad: true,\r\n".
                                                    "            totalProperty: 'totalCount',\r\n".
                                                    "            idProperty: '$realId'\r\n".
                                                    "          }, [\r\n".
                                                    "              {name: '$realId', mapping: '$realId'}, \r\n".
                                                    "              {name: '$showValue', mapping: '$value'} \r\n".
                                                    "        ])\r\n".
                                                    "    })"; 
                                }                  
                            }
                        }  
                    }    
                }     
            }
        }        
        $fields=substr($fields,0,strlen($fields)-3);  
        $result['fields']=$fields;
        
        $relationViewDefine=self::relationViewDefine($classname,$instancename,$relationStore);
        $relationStore=$relationViewDefine['relationStore'];
        $relationClassesView=$relationViewDefine['one2many'];
        $relationViewAdds=$relationViewDefine['relationViewAdds'];
        $relationViewGrids=$relationViewDefine['relationViewGrids'];
        $viewRelationDoSelect=$relationViewDefine['viewRelationDoSelect'];
        $relationViewGridInit=$relationViewDefine['relationViewGridInit'];
        
        $result['relationStore']=$relationStore;
        $result['relationClassesView']=$relationClassesView;
        $result['relationViewAdds']=$relationViewAdds;
        $result['relationViewGrids']=$relationViewGrids;
        $result['viewRelationDoSelect']="\r\n".$viewRelationDoSelect;
        
        $result['relationViewGridInit']="\r\n".$relationViewGridInit;
        return $result;
    }
    
    /**
     * 关系显示定义 
     */
    private static function relationViewDefine($classname,$instancename,$relationStore)
    {
        $relationSpec=AutoCodeDomain::$relation_all[$classname];
        $relationClassesView="";
        $appName_alias=Gc::$appName_alias;
        $relationViewAdds="";  
        $relationViewGrids="";
        $viewRelationDoSelect="";  
        $relationViewGridInit="";                                             
        //导出一对多关系规范定义(如果存在)
        if (array_key_exists("has_many",$relationSpec))
        {
            $has_many=$relationSpec["has_many"];
            foreach ($has_many as $key=>$value) 
            {
                $current_classname=$key;
                $key{0}=strtolower($key{0});
                $tablename=self::getTablename($current_classname);
                $current_instancename=self::getInstancename($tablename); 
                
                $relation_classcomment=self::getClassComments($current_classname);
                $relation_classcomment=str_replace("关系表","",$relation_classcomment); 
                if (contain($relation_classcomment,"\r")||contain($relation_classcomment,"\n")){
                    $relation_classcomment=preg_split("/[\s,]+/", $relation_classcomment);    
                    $relation_classcomment=$relation_classcomment[0]; 
                }   
                $relationViewAdds.="                    {title: '$relation_classcomment',iconCls:'tabs',tabWidth:150,\r\n".
                                  "                     items:[$appName_alias.$classname.View.Running.{$current_instancename}Grid]\r\n".
                                  "                    },\r\n";  
                $relationViewGrids.="        /**\r\n".
                                    "         * 当前{$relation_classcomment}Grid对象\r\n".
                                    "         */\r\n".
                                    "        {$current_instancename}Grid:null,\r\n";  
                $viewRelationDoSelect.="            $appName_alias.$classname.View.Running.{$current_instancename}Grid.doSelect{$current_classname}();\r\n";     
                $relationViewGridInit.="                $appName_alias.$classname.View.Running.{$current_instancename}Grid=new $appName_alias.$classname.View.{$current_classname}View.Grid();\r\n";            
                if (!contain($relationStore,"{$key}Store"))
                {
                    $fieldInfo=self::$fieldInfos[$tablename];
                    $fields_relation="";
                    foreach ($fieldInfo as $fieldname=>$field)
                    {
                        if (!self::isNotColumnKeywork($fieldname))continue;
                        $datatype=self::comment_type($field["Type"]);                             
                        $field_comment=$field["Comment"]; 
                        if (contains($field_comment,array("日期","时间")))
                        {
                            $datatype='date';        
                        }            
                        if ($datatype=='enum'){
                            $datatype='string';
                        }
                        $fields_relation.="                  {name: '$fieldname',type: '".$datatype."'"; 
                        if ($datatype=='date')
                        {
                            $fields_relation.=",dateFormat:'Y-m-d H:i:s'";
                        }
                        $fields_relation.="},\r\n";   
                    }
                    $fields_relation=substr($fields_relation,0,strlen($fields_relation)-3); 
                    
                    $relation_classcomment=self::getClassComments($current_classname);
                    $relation_classcomment=str_replace("关系表","",$relation_classcomment); 
                    if (contain($relation_classcomment,"\r")||contain($relation_classcomment,"\n")){
                        $relation_classcomment=preg_split("/[\s,]+/", $relation_classcomment);    
                        $relation_classcomment=$relation_classcomment[0]; 
                    }                       
                    $relationStore.=",\r\n".
                                    "    /**\r\n".  
                                    "     * {$relation_classcomment}\r\n".
                                    "     */\r\n".
                                    "    {$key}Store:new Ext.data.Store({\r\n".  
                                    "        reader: new Ext.data.JsonReader({\r\n".
                                    "            totalProperty: 'totalCount',\r\n".
                                    "            successProperty: 'success',\r\n".
                                    "            root: 'data',remoteSort: true,\r\n".
                                    "            fields : [\r\n".
                                    "$fields_relation\r\n".
                                    "            ]}\r\n".
                                    "        ),\r\n".
                                    "        writer: new Ext.data.JsonWriter({\r\n".
                                    "            encode: false \r\n".
                                    "        })\r\n".
                                    "    })"; 
                }       
                
                if (!contain($relationClassesView,"{$current_classname}View"))
                {                    
                    $table_comment12n=self::tableCommentKey($tablename);
                    $realId=DataObjectSpec::getRealIDColumnName($classname);                    
                    $columns_relation="";                    
                    $fieldInfo=self::$fieldInfos[$tablename];
                    foreach ($fieldInfo as $fieldname=>$field)
                    {
                        if (!self::isNotColumnKeywork($fieldname))
                        {
                           continue; 
                        }
                        if ($fieldname==self::keyIDColumn($current_classname))
                        { 
                            continue;
                        }
                        $field_comment=$field["Comment"];  
                        $field_comment=self::columnCommentKey($field_comment,$fieldname);
                        $datatype=self::comment_type($field["Type"]);
                        $columns_relation.="                          {header : '$field_comment',dataIndex : '{$fieldname}'";  
                        if (($datatype=='date')||contains($field_comment,array("日期","时间"))) 
                        {
                            $columns_relation.=",renderer:Ext.util.Format.dateRenderer('Y-m-d')";
                        }
                    
                        $column_type=self::column_type($field["Type"]); 
                        if ($column_type=='bit'){
                            $columns_relation.=",renderer:function(value){if (value == true) {return \"是\";}else{return \"否\";}}";  
                        }
                        $columns_relation.="},\r\n";
                    }
                    $columns_relation=substr($columns_relation,0,strlen($columns_relation)-3); 
                    include("one2many.php");
                    $relationClassesView.="\r\n".$jsOne2ManyContent;
                }
                           
            }
        }
        $result['relationStore']=$relationStore;   
        if (empty($relationClassesView)){
            $relationViewAdds.="                    {title: '其他',iconCls:'tabs'}";                              
        }else{
            $relationViewAdds=substr($relationViewAdds,0,strlen($relationViewAdds)-3);
        }
        $relationViewAdds="                this.add(\r\n".
                          $relationViewAdds."\r\n".
                          "                );";
        $result['one2many']=$relationClassesView;
        $result['relationViewAdds']="\r\n".$relationViewAdds;
        $relationViewGrids=substr($relationViewGrids,0,strlen($relationViewGrids)-2);
        $result['relationViewGrids']="\r\n".$relationViewGrids;
        $viewRelationDoSelect=substr($viewRelationDoSelect,0,strlen($viewRelationDoSelect)-2);
        $result['viewRelationDoSelect']=$viewRelationDoSelect;
        $relationViewGridInit=substr($relationViewGridInit,0,strlen($relationViewGridInit)-2);
        $result['relationViewGridInit']=$relationViewGridInit;
        return $result;
    }

    /**
     * 获取Ext "EditWindow"里items的fieldLabels
     */
    private static function model_fieldLables($appName_alias,$classname,$fieldInfo)
    {
        $result=array();
        $fieldLabels="";//Ext "EditWindow"里items的fieldLabels
     
        foreach ($fieldInfo as $fieldname=>$field)
        {        
            if (isset($ignord_field)&&($ignord_field==$fieldname)){
                continue;
            }     
            if (self::isNotColumnKeywork($fieldname))
            {      
                if (array_key_exists($classname,self::$relation_viewfield)){
                    $relationSpecs=self::$relation_viewfield[$classname]; 
                    if (array_key_exists($fieldname,$relationSpecs)){
                        $relationShow=$relationSpecs[$fieldname];
                        foreach ($relationShow as $key=>$value) {  
                            if ((!array_key_exists($value,$fieldInfo))||($classname==$key)){
                                $field_comment=$field["Comment"];   
                                $field_comment=self::columnCommentKey($field_comment,$fieldname);
                            }else{   
                                $field_comment=self::$fieldInfos[self::getTablename($key)][$value]["Comment"]; 
                                $field_comment=self::columnCommentKey($field_comment,$value);
                                if ($field_comment=="名称"){
                                    $field_comment=$field["Comment"];
                                    $field_comment=self::columnCommentKey($field_comment,$fieldname);
                                } 
                                $ignord_field=$value; 
                            } 
                            $realId=DataObjectSpec::getRealIDColumnName($key);
                            if (empty($realId))$realId=$fieldname;
                            //避免name和本表的name冲突可能
                            if ($value=="name") $value=strtolower($key)."_".$value;
                            $show_name_diff=$value;

                            if ($realId!=$fieldname){
                                if (contain($fieldname,"_id")){
                                    $fieldname=str_replace("_id","",$fieldname);
                                }
                                $show_name_diff.="_".$fieldname;
                            }
                            $key{0}=strtolower($key{0});                            
                            $fieldLabels.="                              {xtype: 'hidden',name : '$fieldname',ref:'../$fieldname'},\r\n".
                                          "                              {\r\n".
                                          "                                 fieldLabel : '{$field_comment}',xtype: 'combo',name : '$show_name_diff',ref : '../$show_name_diff',\r\n".
                                          "                                 store:$appName_alias.$classname.Store.{$key}Store,emptyText: '请选择{$field_comment}',itemSelector: 'div.search-item',\r\n".
                                          "                                 loadingText: '查询中...',width: 570, pageSize:$appName_alias.$classname.Config.PageSize,\r\n".
                                          "                                 displayField:'$value',grid:this,\r\n".
                                          "                                 mode: 'remote',  editable:true,minChars: 1,autoSelect :true,typeAhead: false,\r\n".
                                          "                                 forceSelection: true,triggerAction: 'all',resizable:false,selectOnFocus:true,\r\n".
                                          "                                 tpl:new Ext.XTemplate(\r\n".
                                          "                                     '<tpl for=\".\"><div class=\"search-item\">',\r\n".
                                          "                                         '<h3>{{$value}}</h3>',\r\n".
                                          "                                     '</div></tpl>'\r\n".
                                          "                                 ),\r\n".
                                          "                                 onSelect:function(record,index){\r\n".
                                          "                                     if(this.fireEvent('beforeselect', this, record, index) !== false){\r\n".
                                          "                                        this.grid.$fieldname.setValue(record.data.$realId);\r\n".
                                          "                                        this.grid.$show_name_diff.setValue(record.data.$value);\r\n".
                                          "                                        this.collapse();\r\n".
                                          "                                     }\r\n".
                                          "                                 }\r\n".
                                          "                              },\r\n".
                                          ""; 
                        }
                        continue;
                    }      
                }  
                  
                $column_type=self::column_type($field["Type"]);
                $isImage =self::columnIsImage($fieldname,$field["Comment"]);        
                if ($fieldname==self::keyIDColumn($classname))
                { 
                    $fieldLabels.="                              {xtype: 'hidden',  name : '$fieldname',ref:'../$fieldname'"; 
                }else if ($isImage){
                    $field_comment=$field["Comment"]; 
                    $field_comment=self::columnCommentKey($field_comment,$fieldname);
                    $result["isFileUpload"]="fileUpload: true,";  
                    $fieldLabels.="                              {xtype: 'hidden',  name : '$fieldname',ref:'../$fieldname'},\r\n"; 
                    $fieldLabels.="                              {fieldLabel : '{$field_comment}',name : '{$fieldname}Upload',ref:'../{$fieldname}Upload',xtype:'fileuploadfield',\r\n".
                                "                             emptyText: '请上传{$field_comment}文件',buttonText: '',accept:'image/*',buttonCfg: {iconCls: 'upload-icon'}";
                }else{                  
                    $datatype=self::comment_type($field["Type"]);
                    $field_comment=$field["Comment"];  
                    $field_comment=self::columnCommentKey($field_comment,$fieldname);     
                    if (!$field["IsPermitNull"])
                    {
                        $fr1="(<font color=red>*</font>)";
                        $fr2=",allowBlank : false";
                    }else{  
                        $fr1="";
                        $fr2=""; 
                    } 
                    //当使用form.getForm().submit()方式提交时，服务器得到的请求字段中的值总是combobox实际显示的值，也就是displayField:'text'的值;
                    //将name属性修改为hiddenName，便会将value值提交给服务器 
                    if (($column_type=='enum')||($column_type=='bit')){
                        $flName="hiddenName";
                    }else{
                        $flName="name";  
                    }                        
                    $fieldLabels.="                              {fieldLabel : '$field_comment$fr1',$flName : '$fieldname'$fr2"; 
                    if (($datatype=='date')||contains($field_comment,array("日期","时间")))  
                    {
                        $fieldLabels.=",xtype : 'datefield',format : \"Y-m-d\"";
                    }elseif (($column_type=='int')||($datatype=='float')){  
                        $fieldLabels.=",xtype : 'numberfield'";  
                    }   
                    if ($column_type=='bit')
                    {
                        $fieldLabels.="\r\n                                 ,xtype : 'combo',mode : 'local',triggerAction : 'all',\r\n".
                                  "                                 lazyRender : true,editable: false,allowBlank : false,\r\n".
                                  "                                 store : new Ext.data.SimpleStore({\r\n".
                                  "                                     fields : ['value', 'text'],\r\n".
                                  "                                     data : [['0', '否'], ['1', '是']]\r\n".
                                  "                                 }),emptyText: '请选择$field_comment',\r\n".
                                  "                                 valueField : 'value',displayField : 'text'\r\n                              ";
                    }
                    if ($column_type=='enum')
                    { 
                        $enum_columnDefine=self::enumDefines($field["Comment"]);  
                        $fieldLabels.=",xtype : 'combo',\r\n".
                                      "                                mode : 'local',triggerAction : 'all',lazyRender : true,editable: false,allowBlank : false,\r\n".
                                      "                                store : new Ext.data.SimpleStore({\r\n".
                                      "                                    fields : ['value', 'text'],\r\n".
                                      "                                    data : ["; 
                        $enumArr=array();              
                        foreach ($enum_columnDefine as $enum_column) 
                        {
                            $enumArr[]="['".$enum_column["value"]."', '".$enum_column["comment"]."']";  
                        }                                         
                        $fieldLabels.=implode(",",$enumArr);              
                        $fieldLabels.="]\r\n".
                                      "                                }),emptyText: '请选择$field_comment',\r\n".
                                      "                                valueField : 'value',displayField : 'text'\r\n                              "; 
                    } 
                    if (self::columnIsTextArea($fieldname,$field["Type"]))
                    {        
                        $fieldLabels.=",xtype : 'textarea',id:'$fieldname',ref:'$fieldname'";
                    }
                }
                $fieldLabels.="},\r\n"; 
            }      
        }
        $fieldLabels=substr($fieldLabels,0,strlen($fieldLabels)-3);  
        $result["fieldLabels"]=$fieldLabels;
        return $result;        
    }

    /**
     * 获取Ext "Textarea" 转换成在线编辑器
     */
    private static function model_textareaOnlineEditor($appName_alias,$classname,$instancename,$fieldInfo)
    {
        $result=array();      
        $textareaOnlineditor_Replace="";
        $textareaOnlineditor_Add="";
        $textareaOnlineditor_Update="";    
        $textareaOnlineditor_Save=""; 
        $textareaOnlineditor_Reset="";
        $textareaOnlineditor_Init="";

        $textareaOnlineditor_Replace_array=array("ckEditor"=>'',"kindEditor"=>'',"xhEditor"=>''); 
        $textareaOnlineditor_Add_array=array("ckEditor"=>'',"kindEditor"=>'',"xhEditor"=>''); 
        $textareaOnlineditor_Update_array=array("ckEditor"=>'',"kindEditor"=>'',"xhEditor"=>'');     
        $textareaOnlineditor_Save_array=array("ckEditor"=>'',"kindEditor"=>'',"xhEditor"=>''); 
        $textareaOnlineditor_Reset_array=array("ckEditor"=>'',"kindEditor"=>'',"xhEditor"=>''); 
        $reset_img="";
        $add_img="";
        $update_img="";     
        $has_textarea=false;   
        foreach ($fieldInfo as $fieldname=>$field)
        {       
            if (self::isNotColumnKeywork($fieldname))
            {      
                $column_type=self::column_type($field["Type"]);
                $isImage =self::columnIsImage($fieldname,$field["Comment"]);                  
                if ($fieldname==self::keyIDColumn($classname))
                {
                    $result["tableFieldIdName"]=$fieldname;       
                }else if ($isImage){
                    $reset_img.="                        this.{$fieldname}Upload.setValue(this.{$fieldname}.getValue());\r\n";
                    $add_img.="            $appName_alias.$classname.View.Running.edit_window.{$fieldname}Upload.setValue(\"\");\r\n";   
                    $update_img.="            $appName_alias.$classname.View.Running.edit_window.{$fieldname}Upload.setValue($appName_alias.$classname.View.Running.edit_window.{$fieldname}.getValue());\r\n";           
                }else{                  
                    $datatype=self::comment_type($field["Type"]);
                    $field_comment=$field["Comment"];  
                    $field_comment=self::columnCommentKey($field_comment,$fieldname);

                    if (self::columnIsTextArea($fieldname,$field["Type"]))
                    {         
                        $has_textarea=true;
                        $textareaOnlineditor_Replace_array["ckEditor"].="                                ckeditor_replace_$fieldname(); \r\n";  
                        $textareaOnlineditor_Replace_array["kindEditor"].="                                $appName_alias.$classname.View.EditWindow.KindEditor_$fieldname = KindEditor.create('textarea[name=\"$fieldname\"]',{width:'98%',minHeith:'350px', filterMode:true});\r\n";
                        $textareaOnlineditor_Replace_array["xhEditor"].="                                pageInit_$fieldname();\r\n";

                        $textareaOnlineditor_Add_array["ckEditor"].="                    if (CKEDITOR.instances.$fieldname) CKEDITOR.instances.$fieldname.setData(\"\");\r\n"; 
                        $textareaOnlineditor_Add_array["kindEditor"].="                    if ($appName_alias.$classname.View.EditWindow.KindEditor_$fieldname) $appName_alias.$classname.View.EditWindow.KindEditor_{$fieldname}.html(\"\");\r\n";

                        $textareaOnlineditor_Update_array["ckEditor"].="                    if (CKEDITOR.instances.$fieldname) CKEDITOR.instances.$fieldname.setData(this.getSelectionModel().getSelected().data.$fieldname); \r\n"; 
                        $textareaOnlineditor_Update_array["kindEditor"].="                    if ($appName_alias.$classname.View.EditWindow.KindEditor_$fieldname) $appName_alias.$classname.View.EditWindow.KindEditor_$fieldname.html(this.getSelectionModel().getSelected().data.$fieldname);\r\n";
                        $textareaOnlineditor_Update_array["xhEditor"].="                    if (xhEditor_$fieldname)xhEditor_$fieldname.setSource(this.getSelectionModel().getSelected().data.$fieldname);\r\n";

                        $textareaOnlineditor_Save_array["ckEditor"].="                                if (CKEDITOR.instances.$fieldname) this.editForm.$fieldname.setValue(CKEDITOR.instances.$fieldname.getData());\r\n";
                        $textareaOnlineditor_Save_array["kindEditor"].="                                if ($appName_alias.$classname.View.EditWindow.KindEditor_$fieldname)this.editForm.$fieldname.setValue($appName_alias.$classname.View.EditWindow.KindEditor_$fieldname.html());\r\n";
                        $textareaOnlineditor_Save_array["xhEditor"].="                                if (xhEditor_$fieldname)this.editForm.$fieldname.setValue(xhEditor_$fieldname.getSource());\r\n";

                        $textareaOnlineditor_Reset_array["ckEditor"].="                                if (CKEDITOR.instances.$fieldname) CKEDITOR.instances.$fieldname.setData($appName_alias.$classname.View.Running.{$instancename}Grid.getSelectionModel().getSelected().data.$fieldname);\r\n";   
                        $textareaOnlineditor_Reset_array["kindEditor"].="                                if ($appName_alias.$classname.View.EditWindow.KindEditor_$fieldname) $appName_alias.$classname.View.EditWindow.KindEditor_$fieldname.html($appName_alias.$classname.View.Running.{$instancename}Grid.getSelectionModel().getSelected().data.$fieldname);\r\n";
                    }
                }
            }      
        }
        if ($has_textarea){
            $textareaOnlineditor_Init=",\r\n".
                                      "        /**\r\n".
                                      "         * 在线编辑器类型。\r\n".
                                      "         * 1:CkEditor,2:KindEditor,3:xhEditor\r\n".
                                      "         * 配合Action的变量配置\$online_editor\r\n".
                                      "         */\r\n".
                                      "        OnlineEditor:1";
            $textareaOnlineditor_Replace=",\r\n".
                                      "                    afterrender:function(){\r\n". 
                                      "                        switch ($appName_alias.$classname.Config.OnlineEditor)\r\n".
                                      "                        {\r\n".
                                      "                            case 2:\r\n".                                  
                                      $textareaOnlineditor_Replace_array["kindEditor"].
                                      "                                break\r\n".
                                      "                            case 3:\r\n".
                                      $textareaOnlineditor_Replace_array["xhEditor"].
                                      "                                break\r\n".                                  
                                      "                            default:\r\n".
                                      $textareaOnlineditor_Replace_array["ckEditor"].  
                                      "                        }\r\n".
                                      "                    }";    
            $textareaOnlineditor_Add=$add_img.
                                      "            switch ($appName_alias.$classname.Config.OnlineEditor)\r\n".
                                      "            {\r\n".
                                      "                case 2:\r\n".
                                      $textareaOnlineditor_Add_array["kindEditor"].
                                      "                    break\r\n".
                                      "                case 3:\r\n".
                                      "                    break\r\n".
                                      "                default:\r\n".
                                      $textareaOnlineditor_Add_array["ckEditor"].
                                      "            }\r\n";
            $textareaOnlineditor_Update=$update_img.
                                      "            switch ($appName_alias.$classname.Config.OnlineEditor)\r\n".
                                      "            {\r\n".
                                      "                case 2:\r\n".
                                      $textareaOnlineditor_Update_array["kindEditor"].
                                      "                    break\r\n".
                                      "                case 3:\r\n".
                                      $textareaOnlineditor_Update_array["xhEditor"].
                                      "                    break\r\n".
                                      "                default:\r\n".
                                      $textareaOnlineditor_Update_array["ckEditor"].
                                      "            }\r\n";
            $textareaOnlineditor_Save="                        switch ($appName_alias.$classname.Config.OnlineEditor)\r\n".
                                      "                        {\r\n".
                                      "                            case 2:\r\n".
                                      $textareaOnlineditor_Save_array["kindEditor"].
                                      "                                break\r\n".
                                      "                            case 3:\r\n".
                                      $textareaOnlineditor_Save_array["xhEditor"].
                                      "                                break\r\n".
                                      "                            default:\r\n".
                                      $textareaOnlineditor_Save_array["ckEditor"].
                                      "                        }\r\n";
            $textareaOnlineditor_Reset=$reset_img.
                                      "                        switch ($appName_alias.$classname.Config.OnlineEditor)\r\n".
                                      "                        {\r\n".
                                      "                            case 2:\r\n".
                                      $textareaOnlineditor_Reset_array["kindEditor"].
                                      "                                break\r\n".
                                      "                            case 3:\r\n".
                                      "                                break\r\n".
                                      "                            default:\r\n".
                                      $textareaOnlineditor_Reset_array["ckEditor"].
                                      "                        }\r\n";
        }else{
            $textareaOnlineditor_Add=$add_img;
            $textareaOnlineditor_Update=$update_img;
            $textareaOnlineditor_Reset=$reset_img;
        }
        $result["textareaOnlineditor_Replace"]=$textareaOnlineditor_Replace;
        $result["textareaOnlineditor_Add"]=$textareaOnlineditor_Add;
        $result["textareaOnlineditor_Update"]=$textareaOnlineditor_Update;    
        $result["textareaOnlineditor_Save"]=$textareaOnlineditor_Save; 
        $result["textareaOnlineditor_Reset"]=$textareaOnlineditor_Reset;
        $result["textareaOnlineditor_Init"]=$textareaOnlineditor_Init;
        return $result;        
    }

    /**
     * Ext "Tabs" 中"onAddItems"包含的viewdoblock
     */
    private static function model_viewblock($classname,$fieldInfo)
    {
        $viewdoblock="";//Ext "Tabs" 中"onAddItems"包含的viewdoblock
        foreach ($fieldInfo as $fieldname=>$field)
        {       
            if (array_key_exists($classname,self::$relation_viewfield)){
                $relationSpecs=self::$relation_viewfield[$classname];  
                if (array_key_exists($fieldname,$relationSpecs)){
                    $relationShow=$relationSpecs[$fieldname];
                    foreach ($relationShow as $key=>$value) { 
                        if ((!array_key_exists($value,$fieldInfo))||($classname==$key)){
                            $field_comment=$field["Comment"];  
                            $field_comment=self::columnCommentKey($field_comment,$fieldname); 
                            foreach ($relationShow as $key=>$value) {
                                $realId=DataObjectSpec::getRealIDColumnName($key);
                                $show_fieldname=$value;
                                if ($realId!=$fieldname){
                                    if (contain($fieldname,"_id")){
                                        $fieldname=str_replace("_id","",$fieldname);
                                    }
                                    $show_fieldname.="_".$fieldname; 
                                }
                                $viewdoblock.="                         '<tr class=\"entry\"><td class=\"head\">$field_comment</td><td class=\"content\">{{$show_fieldname}}</td></tr>',\r\n";
                            }          
                        }       
                    }
                    continue; 
                }
            }           
            if (self::isNotColumnKeywork($fieldname))
            { 
                if ($fieldname==self::keyIDColumn($classname))
                { 
                    continue;
                }                
                $field_comment=$field["Comment"];  
                $field_comment=self::columnCommentKey($field_comment,$fieldname);
                $datatype =self::comment_type($field["Type"]);    
                $dateformat=""; 
                if (($datatype=='date')||contains($field_comment,array("日期","时间")))
                {
                    $dateformat=":date(\"Y-m-d\")";    
                }
                $isImage =self::columnIsImage($fieldname,$field["Comment"]);   
                $column_type=self::column_type($field["Type"]); 
                if ($isImage){        
                    $viewdoblock.="                         '<tr class=\"entry\"><td class=\"head\">{$field_comment}路径</td><td class=\"content\">{{$fieldname}}</td></tr>',\r\n";
                    $viewdoblock.="                         '<tr class=\"entry\"><td class=\"head\">$field_comment</td><td class=\"content\"><img src=\"upload/images/{{$fieldname}}\" /></td></tr>',\r\n";
                }else if ($column_type=='bit'){      
                    $viewdoblock.="                         '<tr class=\"entry\"><td class=\"head\">$field_comment</td><td class=\"content\"><tpl if=\"{$fieldname} == true\">是</tpl><tpl if=\"{$fieldname} == false\">否</tpl></td></tr>',\r\n";
                }else if ($datatype=='enum'){
                    $viewdoblock.="                         '<tr class=\"entry\"><td class=\"head\">$field_comment</td><td class=\"content\">{{$fieldname}Show}</td></tr>',\r\n";
                }else{
                    $viewdoblock.="                         '<tr class=\"entry\"><td class=\"head\">$field_comment</td><td class=\"content\">{{$fieldname}{$dateformat}}</td></tr>',\r\n";
                }
            }
        }
        $viewdoblock=substr($viewdoblock,0,strlen($viewdoblock)-2);
        return $viewdoblock;        
    }

    /**
     * 获取Ext "Grid" 中包含的columns
     */
    private static function model_columns($classname,$fieldInfo)
    {
        $columns="";//Ext "Grid" 中包含的columns
        foreach ($fieldInfo as $fieldname=>$field)
        {
            if (array_key_exists($classname,self::$relation_viewfield)){
                $relationSpecs=self::$relation_viewfield[$classname];  
                if (array_key_exists($fieldname,$relationSpecs)){
                    $relationShow=$relationSpecs[$fieldname];
                    foreach ($relationShow as $key=>$value) {
                        if ((!array_key_exists($value,$fieldInfo))||($classname==$key)){
                            $field_comment=$field["Comment"];  
                            $field_comment=self::columnCommentKey($field_comment,$fieldname);                    
                            foreach ($relationShow as $key=>$value) {
                                $realId=DataObjectSpec::getRealIDColumnName($key);
                                $show_fieldname=$value;
                                if ($realId!=$fieldname){
                                    if (contain($fieldname,"_id")){
                                        $fieldname=str_replace("_id","",$fieldname);
                                    }
                                    $show_fieldname.="_".$fieldname; 
                                }
                                if ($show_fieldname=="name"){
                                    $show_fieldname=strtolower($key)."_".$show_fieldname;
                                }
                                $columns.="                          {header : '$field_comment',dataIndex : '{$show_fieldname}'},\r\n";
                            }
                        }else{
                            if ($value=="name"){
                                $field_comment=$field["Comment"];  
                                $field_comment=self::columnCommentKey($field_comment,$fieldname);   
                                $show_fieldname= strtolower($key)."_".$value;
                                $columns.="                          {header : '$field_comment',dataIndex : '{$show_fieldname}'},\r\n";
                            }
                            
                        }
                    }
                    continue; 
                }
            } 
            if (self::isNotColumnKeywork($fieldname))
            {
                if ($fieldname==self::keyIDColumn($classname))
                { 
                    continue;
                }
                $field_comment=$field["Comment"];  
                $field_comment=self::columnCommentKey($field_comment,$fieldname);
                $datatype=self::comment_type($field["Type"]);
                if ($datatype=='enum'){
                    $columns.="                          {header : '{$field_comment}',dataIndex : '{$fieldname}Show'"; 
                }else{
                    $columns.="                          {header : '$field_comment',dataIndex : '{$fieldname}'";  
                }
                if (($datatype=='date')||contains($field_comment,array("日期","时间"))) 
                {
                    $columns.=",renderer:Ext.util.Format.dateRenderer('Y-m-d')";
                }
            
                $column_type=self::column_type($field["Type"]); 
                if ($column_type=='bit'){
                    $columns.=",renderer:function(value){if (value == true) {return \"是\";}else{return \"否\";}}";  
                }
                $columns.="},\r\n";
            }
        }
        $columns=substr($columns,0,strlen($columns)-3); 
        return $columns;
    }

    /**
     * 获取Ext "Grid" 中"tbar"包含的items中的items<br/>
     * 获取重置语句<br/>
     * 获取查询中的语句<br/>
     */
    private static function model_filters($appName_alias,$classname,$instancename,$fieldInfo)
    {
        $filterFields             ="";//Ext "Grid" 中"tbar"包含的items中的items
        $filterReset              ="";//重置语句
        $filterdoSelect           ="";//查询中的语句
        if (array_key_exists($classname, self::$filter_fieldnames))
        {
            $filterwords=self::$filter_fieldnames[$classname];
            $instancename_pre=$instancename{0}; 
            $filterfilter="                this.filter       ={";
            foreach ($fieldInfo as $fieldname=>$field)
            {
                $field_comment=$field["Comment"];  
                $field_comment=self::columnCommentKey($field_comment,$fieldname);
                if (in_array($fieldname, $filterwords))
                {
                    $fname=$instancename_pre.$fieldname;
                    $datatype=self::comment_type($field["Type"]);
                    $filterFields.="                                '{$field_comment} ','&nbsp;&nbsp;',";
                    if (($datatype=='date')||contains($field_comment,array("日期","时间")))
                    {
                        $filterFields.="{xtype : 'datefield',ref: '../$fname',format : \"Y-m-d\"";
                    }else{
                        $filterFields.="{ref: '../$fname'";
                    }
                    $column_type=self::column_type($field["Type"]); 
                    if ($column_type=='bit')
                    {
                        $filterFields.=",xtype : 'combo',mode : 'local',\r\n".
                                "                                    triggerAction : 'all',lazyRender : true,editable: false,\r\n".
                                "                                    store : new Ext.data.SimpleStore({\r\n".
                                "                                        fields : ['value', 'text'],\r\n".
                                "                                        data : [['0', '否'], ['1', '是']]\r\n".
                                "                                    }),\r\n".
                                "                                    valueField : 'value',displayField : 'text'\r\n".
                                "                                ";
                    }                
                    if ($column_type=='enum')
                    {
                        $enum_columnDefine=self::enumDefines($field["Comment"]); 
                        $filterFields.=",xtype : 'combo',mode : 'local',\r\n".
                                "                                    triggerAction : 'all',lazyRender : true,editable: false,\r\n".
                                "                                    store : new Ext.data.SimpleStore({\r\n".
                                "                                        fields : ['value', 'text'],\r\n".
                                "                                        data : [";
                        $enumArr=array();              
                        foreach ($enum_columnDefine as $enum_column) 
                        {
                            $enumArr[]="['".$enum_column["value"]."', '".$enum_column["comment"]."']";  
                        }                                         
                        $filterFields.=implode(",",$enumArr);   
                        $filterFields.="]\r\n".
                                "                                    }),\r\n".
                                "                                    valueField : 'value',displayField : 'text'\r\n".
                                "                                ";
                    }
                    
                    if ($filterwords["relation_show"]){
                        if (array_key_exists($fieldname, $filterwords["relation_show"])){                            
                            $con_relation_class=$filterwords["relation_show"][$fieldname]["relation_class"];
                            $show_name         =$filterwords["relation_show"][$fieldname]["show_name"]; 
                            $store_con_relation_class=$con_relation_class;
                            $store_con_relation_class[0]=strtolower($store_con_relation_class[0]);
                            $storeName="$appName_alias.$classname.Store.".$store_con_relation_class."Store";
                            $filterFields.=",xtype: 'combo',\r\n".
                                          "                                     store:{$storeName},hiddenName : '{$fieldname}',\r\n".
                                          "                                     emptyText: '请选择{$field_comment}',itemSelector: 'div.search-item',\r\n".
                                          "                                     loadingText: '查询中...',width:280,pageSize:$appName_alias.$classname.Config.PageSize,\r\n". 
                                          "                                     displayField:'{$show_name}',valueField:'{$fieldname}',\r\n".
                                          "                                     mode: 'remote',editable:true,minChars: 1,autoSelect :true,typeAhead: false,\r\n".
                                          "                                     forceSelection: true,triggerAction: 'all',resizable:true,selectOnFocus:true,\r\n".
                                          "                                     tpl:new Ext.XTemplate(\r\n".
                                          "                                                '<tpl for=\".\"><div class=\"search-item\">',\r\n".
                                          "                                                    '<h3>{{$show_name}}</h3>',\r\n".
                                          "                                                '</div></tpl>'\r\n".
                                          "                                     )\r\n".
                                          "                                ";                       
                        }
                    }
                          
                    $filterFields.="},'&nbsp;&nbsp;',\r\n";
                    $filterReset.="                                        this.topToolbar.$fname.setValue(\"\");\r\n"; 
                    $filterdoSelect.="                var $fname = this.topToolbar.$fname.getValue();\r\n";
                    $filterfilter.="'$fieldname':$fname,";                                                          
                }   
            }  
            if (strlen($filterFields)>0)
            {
                $filterFields=substr($filterFields,0,strlen($filterFields)-2);    
                $filterReset=substr($filterReset,0,strlen($filterReset)-2);   
                $filterdoSelect=substr($filterdoSelect,0,strlen($filterdoSelect)-2);     
                $filterfilter=substr($filterfilter,0,strlen($filterfilter)-1);
                $filterfilter=$filterfilter."};";    
            }
        }
        $result["filterFields"]   =$filterFields;
        $result["filterReset"]    =$filterReset;
        $result["filterdoSelect"] =$filterdoSelect."\r\n".$filterfilter;
        return $result;
    }


    private static function model_upload($appName_alias,$classname,$instancename,$fieldInfo)
    {
        $menu_uploadImg=",\r\n";
        $batchUploadImagesWinow=",\r\n";
        $openBatchUploadImagesWindow=",\r\n";
        $isImage_once=false;
        $uploadServiceUrl=",\r\n";
        $moreImageUploads="if ($appName_alias.$classname.View.Running.batchUploadImagesWindow==null){\r\n".
                          "                $appName_alias.$classname.View.Running.batchUploadImagesWindow=new $appName_alias.$classname.View.BatchUploadImagesWindow();\r\n".
                          "            }\r\n";
        foreach ($fieldInfo as $fieldname=>$field)
        {
            $isImage =self::columnIsImage($fieldname,$field["Comment"]);  
            $field_comment=$field["Comment"];
            $field_comment=self::columnCommentKey($field_comment,$fieldname);
            $field_comment=str_replace('路径',"",$field_comment);   
            if ($isImage){
                $menu_uploadImg.="                                            {text:'批量导入{$field_comment}',iconCls : 'icon-import',scope:this,handler:function(){this.batchUploadImages(\"upload_{$fieldname}_files\",\"$field_comment\")}},\r\n";
                $fieldname_funcname=$fieldname;
                $fieldname_funcname{0}=strtoupper($fieldname_funcname);                
                if ($isImage_once){
                    $uploadServiceUrl.="                            if (this.uploadForm.upload_file.name==\"upload_{$fieldname}_files\"){\r\n".
                                       "                                uploadImageUrl='index.php?go=admin.upload.upload{$classname}{$fieldname_funcname}s';\r\n".
                                       "                            }\r\n";
                    $moreImageUploads="if ($appName_alias.$classname.View.Running.batchUploadImagesWindow!=null){\r\n".
                                      "                $appName_alias.$classname.View.Running.batchUploadImagesWindow.destroy();\r\n".                                       
                                      "                $appName_alias.$classname.View.Running.batchUploadImagesWindow=null;\r\n".
                                      "            }\r\n".                                      
                                      "            $appName_alias.$classname.View.Running.batchUploadImagesWindow=new $appName_alias.$classname.View.BatchUploadImagesWindow();\r\n";                                                                            
                }else{
                    $uploadServiceUrl="var uploadImageUrl='index.php?go=admin.upload.upload{$classname}{$fieldname_funcname}s';\r\n";
                }
                $isImage_once=true;
            }
        }
        if ($isImage_once){
            $batchUploadImagesWinow.=<<<BATCHUPLOADIMAGESWINDOW
    /**
     * 窗口：批量上传商品图片
     */ 
    BatchUploadImagesWindow:Ext.extend(Ext.Window,{
        constructor : function(config) { 
            config = Ext.apply({    
                width : 400,height : 180,minWidth : 300,minHeight : 100,closeAction : "hide",
                layout : 'fit',plain : true,bodyStYle : 'padding:5px;',buttonAlign : 'center',
                items : [
                    new Ext.form.FormPanel({
                        ref:'uploadForm',fileUpload: true,
                        width: 500,labelWidth: 50,autoHeight: true,baseCls: 'x-plain',
                        frame:true,bodyStyle: 'padding: 10px 10px 10px 10px;',
                        defaults: {
                            anchor: '95%',allowBlank: false,msgTarget: 'side'
                        },              
                        items : [{
                            xtype : 'fileuploadfield',fieldLabel : '文 件',ref:'upload_file',
                            emptyText: '请批量上传{$field_comment}文件(zip)',buttonText: '',
                            accept:'application/zip,application/x-zip-compressed',
                            buttonCfg: {iconCls: 'upload-icon'}
                        },
                        {xtype : 'displayfield',value:'*.图片名不能重名<br/>*.压缩文件最大不要超过50M'}]
                    })
                ],
                buttons : [{
                        text : '上 传',
                        scope:this,   
                        handler : function() {
                            uploadImagesWindow     =this;
                            validationExpression   =/([\u4E00-\u9FA5]|\w)+(.zip|.ZIP)$/;
                            var isValidExcelFormat = new RegExp(validationExpression);
                            var result             = isValidExcelFormat.test(this.uploadForm.upload_file.getValue());
                            if (!result){
                                Ext.Msg.alert('提示', '请上传ZIP文件，后缀名为zip！');
                                return;
                            }
                            $uploadServiceUrl
                            if (this.uploadForm.getForm().isValid()) {
                                Ext.Msg.show({
                                    title : '请等待',msg : '文件正在上传中，请稍后...',
                                    animEl : 'loading',icon : Ext.Msg.WARNING,
                                    closable : true,progress : true,progressText : '',width : 300
                                });
                                this.uploadForm.getForm().submit({
                                    url : uploadImageUrl,                        
                                    success : function(form, response) {
                                        Ext.Msg.alert('成功', '上传成功');
                                        uploadImagesWindow.hide();
                                        uploadImagesWindow.uploadForm.upload_file.setValue('');
                                        $appName_alias.$classname.View.Running.{$instancename}Grid.doSelect{$classname}();
                                    },
                                    failure : function(form, response) {
                                        Ext.Msg.alert('错误', response.result.data);
                                    }
                                });
                            }
                        }
                    },{
                        text : '取 消',
                        scope:this,
                        handler : function() {
                            this.uploadForm.upload_file.setValue('');
                            this.hide();
                        }
                    }]
                }, config);  
            $appName_alias.$classname.View.BatchUploadImagesWindow.superclass.constructor.call(this, config);     
        }  
    }),

BATCHUPLOADIMAGESWINDOW;

            $openBatchUploadImagesWindow.=<<<BATCHUPLOADIMAGES
        /**
         * 批量上传商品图片
         */
        batchUploadImages:function(inputname,title){
            $moreImageUploads 
            $appName_alias.$classname.View.Running.batchUploadImagesWindow.setTitle("批量上传"+title);
            $appName_alias.$classname.View.Running.batchUploadImagesWindow.uploadForm.upload_file.name=inputname;
            $appName_alias.$classname.View.Running.batchUploadImagesWindow.show();
        },

BATCHUPLOADIMAGES;
        }
        $menu_uploadImg=substr($menu_uploadImg,0,strlen($menu_uploadImg)-3); 
        $openBatchUploadImagesWindow=substr($openBatchUploadImagesWindow,0,strlen($openBatchUploadImagesWindow)-3); 
        $batchUploadImagesWinow=substr($batchUploadImagesWinow,0,strlen($batchUploadImagesWinow)-2); 
        $result["menu_uploadImg"]   =$menu_uploadImg;      
        $result["openBatchUploadImagesWindow"]   =$openBatchUploadImagesWindow;   
        $result["batchUploadImagesWinow"]   =$batchUploadImagesWinow;   
        return $result;
    }

    /**
     * 获取表注释第一行关键词说明
     */
    private static function tableCommentKey($tablename)
    {
        if (self::$tableInfoList!=null&&count(self::$tableInfoList)>0&&  array_key_exists("$tablename", self::$tableInfoList))
        {
            $table_comment=self::$tableInfoList[$tablename]["Comment"];
            $table_comment=str_replace("关系表","",$table_comment); 
            if (contain($table_comment,"\r")||contain($table_comment,"\n")){
                $table_comment=preg_split("/[\s,]+/", $table_comment);    
                $table_comment=$table_comment[0]; 
            }
        }else{
            $table_comment=$tablename;
        }    
        return $table_comment;
    }

    /**
     * 获取列注释第一行关键词说明
     * @param mixed $field_comment 列注释
     * @param mixed $default 默认返回值
     * @return mixed
     */
    private static function columnCommentKey($field_comment,$default="")
    {
        if (empty($field_comment)){
            return $default;
        }
        if (contain($field_comment,"\r")||contain($field_comment,"\n"))
        {
            $field_comment=preg_split("/[\s,]+/", $field_comment);    
            $field_comment=$field_comment[0]; 
        }      
        if ($field_comment){
            $field_comment=str_replace('标识',"",$field_comment);
            $field_comment=str_replace('编号',"",$field_comment);  
            $field_comment=str_replace('主键',"",$field_comment);      
        }                    
        return $field_comment;
    }

    /**
     * 获取数据对象的ID列名称
     * @param mixed $dataobject 数据对象实体|对象名称
     */
    private static function keyIDColumn($dataobject)
    {
        return DataObjectSpec::getRealIDColumnNameStatic($dataobject);  
    }    

    /**
     * 将表列定义转换成使用ExtJs生成的表示层tpl文件定义的内容    
     * @param array $fieldInfo 表列信息列表
     */
    private static function tableToViewTplDefine($fieldInfo)
    {
        $result ="{extends file=\"\$templateDir/layout/normal/layout.tpl\"}\r\n".
                 "{block name=body}\r\n".
                 "    <div id=\"loading-mask\"></div>\r\n".
                 "    <div id=\"loading\">\r\n".    
                 "        <div class=\"loading-indicator\"><img src=\"{$url_base}common/js/ajax/ext/resources/images/extanim32.gif\" width=\"32\" height=\"32\" style=\"margin-right:8px;\" align=\"absmiddle\"/>正在加载中...</div>\r\n".  
                 "    </div>\r\n". 
                 "    <div id=\"win1\" class=\"x-hide-display\"></div>\r\n";
        foreach ($fieldInfo as $fieldname=>$field)
        {                    
            if (self::columnIsTextArea($fieldname,$field["Type"]))
            {
                $result.="     {\$editorHtml}\r\n"; 
                break;
            }   
        }    
        $result .="{/block}\r\n";  
        return $result;
    }
       
    /**
     * 保存生成的Js代码到指定命名规范的文件中  
     * @param string $tablename 表名称  
     * @param string $defineJsFileContent 生成的代码 
     */
    private static function saveJsDefineToDir($tablename,$defineJsFileContent)
    { 
        $filename =self::getInstancename($tablename).Config_F::SUFFIX_FILE_JS;  
        $dir      =self::$view_js_package.self::getInstancename($tablename).DIRECTORY_SEPARATOR;
        return self::saveDefineToDir($dir,$filename,$defineJsFileContent);
    }
       
    /**
     * 保存生成的Ajax服务代码到指定命名规范的文件中  
     * @param string $classname 类名称  
     * @param string $defineAjaxPhpFileContent 生成的代码 
     */
    private static function saveoAjaxPhpDefineToDir($classname,$defineAjaxPhpFileContent)
    { 
        $classname{0}=strtolower($classname{0});  
        $filename =$classname.Config_F::SUFFIX_FILE_PHP;  
        $dir      =self::$ajax_dir_full;
        return self::saveDefineToDir($dir,$filename,$defineAjaxPhpFileContent);
    }  
  
    /**
     * 保存生成的tpl代码到指定命名规范的文件中    
     * @param string $tablename 表名称  
     * @param string $defineTplFileContent 生成的代码 
     */
    private static function saveTplDefineToDir($tablename,$defineTplFileContent)
    { 
        $filename =self::getInstancename($tablename).Config_F::SUFFIX_FILE_TPL;  
        $dir      =self::$view_core.Gc::$appName.DIRECTORY_SEPARATOR;
        return self::saveDefineToDir($dir,$filename,$defineTplFileContent);
    }
}

?>
