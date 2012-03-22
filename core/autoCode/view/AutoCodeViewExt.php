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
        $tableList=Manager_Db::newInstance()->dbinfo()->tableList();
        $fieldInfos=array();
        $tableInfoList=Manager_Db::newInstance()->dbinfo()->tableInfoList(); 
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
        echo UtilCss::form_css()."\r\n";
        echo "<font color='#FF0000'>采用ExtJs框架生成后端Js文件导出:</font><br/>";   
        foreach ($fieldInfos as $tablename=>$fieldInfo){
            $defineJsFileContent=self::tableToViewJsDefine($tablename,$tableInfoList,$fieldInfo);
            if (isset(self::$save_dir)&&!empty(self::$save_dir)&&isset($defineJsFileContent)){
                $jsName=self::saveJsDefineToDir($tablename,$defineJsFileContent);
                echo "生成导出完成:$tablename=>$jsName!<br/>";   
            }else{
                echo $defineJsFileContent."<br/>";
            }       
        } 

        echo "<font color='#FF0000'>生成后端tpl模板显示文件导出:</font><br/>";  
        foreach ($fieldInfos as $tablename=>$fieldInfo){      
            $defineTplFileContent=self::tableToViewTplDefine($fieldInfo);
            if (isset(self::$save_dir)&&!empty(self::$save_dir)&&isset($defineTplFileContent)){
                $tplName=self::saveTplDefineToDir($tablename,$defineTplFileContent);
                echo "生成导出完成:$tablename=>$tplName!<br/>";   
            }else{
                echo $defineTplFileContent."<br/>";
            }   
        }   
        self::tableToAjaxPhpDefine();
                             
        /**
         * 需要在后端admin/src/view/menu目录下 菜单配置文件:menu.config.xml里添加的代码 
         */
        echo "<br/><font color='#FF0000'>[需要在后端admin/src/view/menu目录下菜单配置文件:menu.config.xml里添加没有的代码]</font><br/>";  
        $section_content="";
        $appName=Gc::$appName;
        foreach($tableList as $tablename){
            $table_comment=$tableInfoList[$tablename]["Comment"];
            $table_comment=str_replace("关系表","",$table_comment); 
            if (contain($table_comment,"\r")||contain($table_comment,"\n")){
                $table_comment=preg_split("/[\s,]+/", $table_comment);    
                $table_comment=$table_comment[0]; 
            }
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
        echo  "新生成的menu.config.xml文件路径:<font color='#0000FF'>".self::$menuconfig_dir_full.$filename."</font><br /><br /><br /><br /><br />";  
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
        echo "<br/><font color='#FF0000'>[生成关系列Ajax请求php文件]</font><br/>";           
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
    }
                     
    /**
     * 将表列定义转换成使用ExtJs生成的表示层Js文件定义的内容
     * @param string $tablename 表名
     * @param array $tableInfoList 表信息列表
     * @param array $fieldInfo 表列信息列表
     */
    public static function tableToViewJsDefine($tablename,$tableInfoList,$fieldInfo)
    {
        $appName_alias=Gc::$appName_alias;
        $appName=Gc::$appName;
        if ($tableInfoList!=null&&count($tableInfoList)>0&&  array_key_exists("$tablename", $tableInfoList))
        {
            $table_comment=$tableInfoList[$tablename]["Comment"];
            $table_comment=str_replace("关系表","",$table_comment); 
            if (contain($table_comment,"\r")||contain($table_comment,"\n")){
                $table_comment=preg_split("/[\s,]+/", $table_comment);    
                $table_comment=$table_comment[0]; 
            }
        }else{
            $table_comment=$tablename;
        }    
        $classname=self::getClassname($tablename);
        $instancename=self::getInstancename($tablename);    
        $fields="";//Ext "store" 中包含的fields
        $appName=ucfirst($appName);
        $appName_alias=ucfirst($appName_alias);
        $relationStore="";//Ext "$relationStore="中关系库Store的定义
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
                }
                $fields.="                  {name: '$fieldname',type: '".$datatype."'"; 
                if ($datatype=='date')
                {
                    $fields.=",dateFormat:'Y-m-d H:i:s'";
                }
                $fields.="},\r\n";     
                if (array_key_exists($classname,self::$relation_viewfield)){
                    $relationSpecs=self::$relation_viewfield[$classname];  
                    if (array_key_exists($fieldname,$relationSpecs)){
                        $relationShow=$relationSpecs[$fieldname];
                        foreach ($relationShow as $key=>$value) {                  
                            if (!array_key_exists($value,$fieldInfo)){              
                                $fields.="                  {name: '$value',type: 'string'},\r\n";  
                            }
                            $relation_classcomment=self::getClassComments($key);
                            $relation_classcomment=str_replace("关系表","",$relation_classcomment); 
                            if (contain($relation_classcomment,"\r")||contain($relation_classcomment,"\n")){
                                $relation_classcomment=preg_split("/[\s,]+/", $relation_classcomment);    
                                $relation_classcomment=$relation_classcomment[0]; 
                            }   
                            $key{0}=strtolower($key{0});
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
                                            "            id: '$fieldname'\r\n".
                                            "          }, [\r\n".
                                            "              {name: '$fieldname', mapping: '$fieldname'}, \r\n".
                                            "              {name: '$value', mapping: '$value'} \r\n".
                                            "        ])\r\n".
                                            "    })";                    
                        }  
                    }    
                }        
            }
        }        
        $fields=substr($fields,0,strlen($fields)-3);  

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


        $viewdoblock="";//Ext "Tabs" 中"onAddItems"包含的viewdoblock
        foreach ($fieldInfo as $fieldname=>$field)
        {       
            if (array_key_exists($classname,self::$relation_viewfield)){
                $relationSpecs=self::$relation_viewfield[$classname];  
                if (array_key_exists($fieldname,$relationSpecs)){
                    $relationShow=$relationSpecs[$fieldname];
                    foreach ($relationShow as $key=>$value) { 
                        if (!array_key_exists($value,$fieldInfo)){
                            $field_comment=$field["Comment"];   
                            if (contain($field_comment,"\r")||contain($field_comment,"\n"))
                            {
                                $field_comment=preg_split("/[\s,]+/", $field_comment);    
                                $field_comment=$field_comment[0]; 
                            }                    
                            if ($field_comment){
                                $field_comment=str_replace('标识',"",$field_comment);
                                $field_comment=str_replace('编号',"",$field_comment);      
                            }            
                            foreach ($relationShow as $key=>$value) {
                                $viewdoblock.="                         '<tr class=\"entry\"><td class=\"head\">$field_comment</td><td class=\"content\">{{$value}}</td></tr>',\r\n";
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
                if (contain($field_comment,"\r")||contain($field_comment,"\n"))
                {
                    $field_comment=preg_split("/[\s,]+/", $field_comment);    
                    $field_comment=$field_comment[0]; 
                }                  
                if ($field_comment){
                    $field_comment=str_replace('标识',"",$field_comment);
                    $field_comment=str_replace('编号',"",$field_comment);      
                }                     
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
                }else{
                    $viewdoblock.="                         '<tr class=\"entry\"><td class=\"head\">$field_comment</td><td class=\"content\">{{$fieldname}{$dateformat}}</td></tr>',\r\n";
                }
            }
        }
        $viewdoblock=substr($viewdoblock,0,strlen($viewdoblock)-2);
        $columns="";//Ext "Grid" 中包含的columns
        foreach ($fieldInfo as $fieldname=>$field)
        {
            if (array_key_exists($classname,self::$relation_viewfield)){
                $relationSpecs=self::$relation_viewfield[$classname];  
                if (array_key_exists($fieldname,$relationSpecs)){
                    $relationShow=$relationSpecs[$fieldname];
                    foreach ($relationShow as $key=>$value) {
                        if (!array_key_exists($value,$fieldInfo)){
                            $field_comment=$field["Comment"];  
                            if (contain($field_comment,"\r")||contain($field_comment,"\n"))
                            {
                                $field_comment=preg_split("/[\s,]+/", $field_comment);    
                                $field_comment=$field_comment[0]; 
                            }                          
                            if ($field_comment){
                                $field_comment=str_replace('标识',"",$field_comment);
                                $field_comment=str_replace('编号',"",$field_comment);      
                            }                                  
                            foreach ($relationShow as $key=>$value) {
                                $columns.="                          {header : '$field_comment',dataIndex : '{$value}'},\r\n";
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
                if (contain($field_comment,"\r")||contain($field_comment,"\n"))
                {
                    $field_comment=preg_split("/[\s,]+/", $field_comment);    
                    $field_comment=$field_comment[0]; 
                }                  
                if ($field_comment){
                    $field_comment=str_replace('标识',"",$field_comment);
                    $field_comment=str_replace('编号',"",$field_comment);      
                }    
                $datatype=self::comment_type($field["Type"]);
                $columns.="                          {header : '$field_comment',dataIndex : '{$fieldname}'";  
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
        $filterFields="";//Ext "Grid" 中"tbar"包含的items中的items
        $filterReset="";//重置语句
        $filterdoSelect="";//查询中的语句
        $filterfilter="";
        if (array_key_exists($classname, self::$filter_fieldnames))
        {
            $filterwords=self::$filter_fieldnames[$classname];
            $instancename_pre=$instancename{0}; 
            $filterfilter="                this.filter       ={";
            foreach ($fieldInfo as $fieldname=>$field)
            {
                $field_comment=$field["Comment"];  
                if (contain($field_comment,"\r")||contain($field_comment,"\n"))
                {
                    $field_comment=preg_split("/[\s,]+/", $field_comment);    
                    $field_comment=$field_comment[0]; 
                }         
                if (in_array($fieldname, $filterwords))
                {
                    $fname=$instancename_pre.$fieldname;
                    $datatype=self::comment_type($field["Type"]);
                    $field_comment=str_replace('标识',"",$field_comment);
                    $field_comment=str_replace('编号',"",$field_comment);
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
                                "                                      }),\r\n".
                                "                                    valueField : 'value',// 值\r\n".
                                "                                    displayField : 'text'// 显示文本\r\n".
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
                                "                                      }),\r\n".
                                "                                    valueField : 'value',// 值\r\n".
                                "                                    displayField : 'text'// 显示文本\r\n".
                                "                                ";
                    }
                    
                    if ($filterwords["relation_show"]){
                        if (array_key_exists($fieldname, $filterwords["relation_show"])){
                            $con_relation_class=$filterwords["relation_show"][$fieldname]["relation_class"];
                            $show_name         =$filterwords["relation_show"][$fieldname]["show_name"];
                            $con_pre=strtolower($classname{0}); 
                            $con_fname=$con_pre.$show_name;
                            $field_comment=str_replace('标识',"",$field_comment);
                            $field_comment=str_replace('编号',"",$field_comment);
                            $store_con_relation_class=$con_relation_class;
                            $store_con_relation_class[0]=strtolower($store_con_relation_class[0]);
                            $storeName="$appName_alias.$classname.Store.".$store_con_relation_class."Store";
                            $filterFields.=",xtype:'hidden',name : '{$fieldname}',id:'{$fname}'},\r\n".   
                                          "                                {\r\n". 
                                          "                                     xtype: 'combo',name : '{$show_name}',id : '{$con_fname}',\r\n".
                                          "                                     store:{$storeName},emptyText: '请选择{$field_comment}',itemSelector: 'div.search-item',\r\n".
                                          "                                     loadingText: '查询中...',width:280,pageSize:$appName_alias.$classname.Config.PageSize,\r\n". 
                                          "                                     displayField:'{$show_name}',// 显示文本\r\n".
                                          "                                     mode: 'remote',  editable:true,minChars: 1,autoSelect :true,typeAhead: false,\r\n".
                                          "                                     forceSelection: true,triggerAction: 'all',resizable:false,selectOnFocus:true,\r\n".
                                          "                                     tpl:new Ext.XTemplate(\r\n".
                                          "                                                '<tpl for=\".\"><div class=\"search-item\">',\r\n".
                                          "                                                    '<h3>{{$show_name}}</h3>',\r\n".
                                          "                                                '</div></tpl>'\r\n".
                                          "                                     ),\r\n".
                                          "                                     onSelect:function(record,index){\r\n".
                                          "                                         if(this.fireEvent('beforeselect', this, record, index) !== false){\r\n".
                                          "                                            Ext.getCmp(\"{$fname}\").setValue(record.data.{$fieldname});\r\n".
                                          "                                            Ext.getCmp(\"{$con_fname}\").setValue(record.data.{$show_name});\r\n".
                                          "                                            this.collapse();\r\n".
                                          "                                         }\r\n".
                                          "                                     }\r\n".
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
        $result="";                
        require("includemodeljs.php");
        $result.=$jsContent;
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
                            if (!array_key_exists($value,$fieldInfo)){
                                $field_comment=$field["Comment"];    
                            }else{                         
                                $field_comment=$fieldInfo[$value]["Comment"];  
                                $ignord_field=$value; 
                            } 
                            if (contain($field_comment,"\r")||contain($field_comment,"\n"))
                            {
                                $field_comment=preg_split("/[\s,]+/", $field_comment);    
                                $field_comment=$field_comment[0]; 
                            }                    
                            if ($field_comment){
                                $field_comment=str_replace('标识',"",$field_comment);
                                $field_comment=str_replace('编号',"",$field_comment);      
                            }
                            $key{0}=strtolower($key{0});
                            $fieldLabels.="                              {xtype: 'hidden',name : '$fieldname',id:'$fieldname'},\r\n".
                                          "                              {\r\n".
                                          "                                 fieldLabel : '{$field_comment}',xtype: 'combo',name : '$value',id : '$value',\r\n".
                                          "                                 store:$appName_alias.$classname.Store.{$key}Store,emptyText: '请选择{$field_comment}',itemSelector: 'div.search-item',\r\n".
                                          "                                 loadingText: '查询中...',width: 570, pageSize:$appName_alias.$classname.Config.PageSize,\r\n".
                                          "                                 displayField:'$value',// 显示文本\r\n".
                                          "                                 mode: 'remote',  editable:true,minChars: 1,autoSelect :true,typeAhead: false,\r\n".
                                          "                                 forceSelection: true,triggerAction: 'all',resizable:false,selectOnFocus:true,\r\n".
                                          "                                 tpl:new Ext.XTemplate(\r\n".
                                          "                                            '<tpl for=\".\"><div class=\"search-item\">',\r\n".
                                          "                                                '<h3>{{$value}}</h3>',\r\n".
                                          "                                            '</div></tpl>'\r\n".
                                          "                                 ),\r\n".
                                          "                                 onSelect:function(record,index){\r\n".
                                          "                                     if(this.fireEvent('beforeselect', this, record, index) !== false){\r\n".
                                          "                                        Ext.getCmp(\"$fieldname\").setValue(record.data.$fieldname);\r\n".
                                          "                                        Ext.getCmp(\"$value\").setValue(record.data.$value);\r\n".
                                          "                                        this.collapse();\r\n".
                                          "                                       }\r\n".
                                          "                                   }\r\n".
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
                    $result["isFileUpload"]="fileUpload: true,";  
                    $fieldLabels.="                              {xtype: 'hidden',  name : '$fieldname',ref:'../$fieldname'},\r\n"; 
                    $fieldLabels.="                              {fieldLabel : '{$table_comment}图片',name : 'imageUpload',ref:'../imageUpload',xtype:'fileuploadfield',\r\n".
                                "                             emptyText: '请上传{$table_comment}图片文件',buttonText: '',accept:'image/*',buttonCfg: {iconCls: 'upload-icon'}";
                }else{                  
                    $datatype=self::comment_type($field["Type"]);
                    $field_comment=$field["Comment"];  
                    if (contain($field_comment,"\r")||contain($field_comment,"\n"))
                    {
                        $field_comment=preg_split("/[\s,]+/", $field_comment);    
                        $field_comment=$field_comment[0]; 
                    }                    
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
                    if ($field_comment){
                        $field_comment=str_replace('标识',"",$field_comment);
                        $field_comment=str_replace('编号',"",$field_comment);      
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
                        $fieldLabels.=",xtype : 'combo',mode : 'local',triggerAction : 'all',lazyRender : true,editable: false,allowBlank : false,\r\n".
                                  "                                store : new Ext.data.SimpleStore({\r\n".
                                  "                                        fields : ['value', 'text'],\r\n".
                                  "                                        data : [['0', '否'], ['1', '是']]\r\n".
                                  "                                  }),emptyText: '请选择$field_comment',\r\n".
                                  "                                valueField : 'value',// 值\r\n".
                                  "                                displayField : 'text'// 显示文本\r\n                            ";
                    }
                    if ($column_type=='enum')
                    { 
                        $enum_columnDefine=self::enumDefines($field["Comment"]);  
                        $fieldLabels.=",xtype : 'combo',mode : 'local',triggerAction : 'all',lazyRender : true,editable: false,allowBlank : false,\r\n".
                                      "                                store : new Ext.data.SimpleStore({\r\n".
                                      "                                        fields : ['value', 'text'],\r\n".
                                      "                                        data : [";  
                        $enumArr=array();              
                        foreach ($enum_columnDefine as $enum_column) 
                        {
                            $enumArr[]="['".$enum_column["value"]."', '".$enum_column["comment"]."']";  
                        }                                         
                        $fieldLabels.=implode(",",$enumArr);              
                        $fieldLabels.="]\r\n".
                                      "                                  }),emptyText: '请选择$field_comment',\r\n".
                                      "                                valueField : 'value',// 值\r\n".
                                      "                                displayField : 'text'// 显示文本\r\n                            ";  
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
        return $fieldLabels;        
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

        $textareaOnlineditor_Replace_array=array("ckEditor"=>'',"kindEditor"=>'',"xhEditor"=>''); 
        $textareaOnlineditor_Add_array=array("ckEditor"=>'',"kindEditor"=>'',"xhEditor"=>''); 
        $textareaOnlineditor_Update_array=array("ckEditor"=>'',"kindEditor"=>'',"xhEditor"=>'');     
        $textareaOnlineditor_Save_array=array("ckEditor"=>'',"kindEditor"=>'',"xhEditor"=>''); 
        $textareaOnlineditor_Reset_array=array("ckEditor"=>'',"kindEditor"=>'',"xhEditor"=>''); 
        $reset_img="";
        $add_img="";
        $update_img="";        
        foreach ($fieldInfo as $fieldname=>$field)
        {       
            if (self::isNotColumnKeywork($fieldname))
            {      
                $column_type=self::column_type($field["Type"]);
                $isImage =self::columnIsImage($fieldname,$field["Comment"]);                  
                if ($fieldname==self::keyIDColumn($classname))
                {
                    $result["tableFieldIdName"]=$fieldname;       
                }if ($isImage){
                    $reset_img.="                        this.{$fieldname}Upload.setValue(this.{$fieldname}.getValue());\r\n";
                    $add_img.="            $appName_alias.$classname.View.Running.edit_window.{$fieldname}Upload.setValue(\"\");\r\n";   
                    $update_img.="            $appName_alias.$classname.View.Running.edit_window.{$fieldname}Upload.setValue($appName_alias.$classname.View.Running.edit_window.{$fieldname}.getValue());\r\n";           
                }else{                  
                    $datatype=self::comment_type($field["Type"]);
                    $field_comment=$field["Comment"];  
                    if (contain($field_comment,"\r")||contain($field_comment,"\n"))
                    {
                        $field_comment=preg_split("/[\s,]+/", $field_comment);    
                        $field_comment=$field_comment[0]; 
                    }      
                    if ($field_comment){
                        $field_comment=str_replace('标识',"",$field_comment);
                        $field_comment=str_replace('编号',"",$field_comment);      
                    }                    

                    if (self::columnIsTextArea($fieldname,$field["Type"]))
                    {         
                        $textareaOnlineditor_Replace_array["ckEditor"].="                                ckeditor_replace_$fieldname(); \r\n";  
                        $textareaOnlineditor_Replace_array["kindEditor"].="                                $appName_alias.$classname.View.EditWindow.KindEditor_$fieldname = KindEditor.create('textarea[name=\"$fieldname\"]',{width:'98%',minHeith:'350px', filterMode:true});\r\n";
                        $textareaOnlineditor_Replace_array["xhEditor"].="                                pageInit(\"$fieldname\");\r\n";

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

        $result["textareaOnlineditor_Replace"]=$textareaOnlineditor_Replace;
        $result["textareaOnlineditor_Add"]=$textareaOnlineditor_Add;
        $result["textareaOnlineditor_Update"]=$textareaOnlineditor_Update;    
        $result["textareaOnlineditor_Save"]=$textareaOnlineditor_Save; 
        $result["textareaOnlineditor_Reset"]=$textareaOnlineditor_Reset;
        return $result;        
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
