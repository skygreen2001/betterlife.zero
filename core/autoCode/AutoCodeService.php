<?php     
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-服务类<br/>
 +---------------------------------<br/>  
 * @category betterlife
 * @package core.autoCode   
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeService extends AutoCode
{
    /**
     * Service类所在的目录
     */
    public static $package="services";   
    /**
     * Service类所在的目录
     */
    public static $service_dir="services";    
    /**
     * Ext js Service文件所在的路径 
     */
    public static $ext_dir="ext";
    /**
    * Service完整的保存路径
    */
    public static $service_dir_full;     
    /**
     * 服务类生成定义的方式<br/>
     * 1.继承具有标准方法的Service。<br/>
     * 2.生成标准方法的Service。<br/>
     * 3.生成ExtJs框架使用的Service【后台】。
     */
    public static $type;

    /**
     * 自动生成代码-服务类
     */
    public static function AutoCode()
    {
        if (self::$type==3){
            self::$app_dir="admin";
        }else{
            self::$app_dir=Gc::$appName;
        }    
        if (!UtilString::is_utf8(self::$service_dir_full)){
            self::$service_dir_full=UtilString::gbk2utf8(self::$service_dir_full);    
        }   
        self::$service_dir_full=self::$save_dir.DIRECTORY_SEPARATOR.self::$app_dir.DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR.self::$service_dir.DIRECTORY_SEPARATOR;
        
        $tableList=Manager_Db::newInstance()->dbinfo()->tableList(); 
        
        $fieldInfos=array();
        foreach ($tableList as $tablename){
           $fieldInfoList=Manager_Db::newInstance()->dbinfo()->fieldInfoList($tablename); 
           foreach($fieldInfoList as $fieldname=>$field){
               $fieldInfos[$tablename][$fieldname]["Field"]=$field["Field"];
               $fieldInfos[$tablename][$fieldname]["Type"]=$field["Type"];
               $fieldInfos[$tablename][$fieldname]["Comment"]=$field["Comment"];
           }
        }
        
        $tableInfoList=Manager_Db::newInstance()->dbinfo()->tableInfoList(); 
        echo UtilCss::form_css()."\r\n";           
        foreach($tableList as $tablename){     
            $definePhpFileContent=self::tableToServiceDefine($tablename,$tableInfoList,$fieldInfos); 
            if (isset($definePhpFileContent)&&(!empty($definePhpFileContent))){
               $classname=self::saveServiceDefineToDir($tablename,$definePhpFileContent);
               echo "生成导出完成:$tablename=>$classname!<br/>";   
            }else{
               echo $definePhpFileContent."<br/>";
            }         
        }
        $category  = Gc::$appName;                 
        $author    = self::$author;
        $package   = self::$package;
        if ((self::$type==2)||(self::$type==1))
        {
            /**
             * 需要在管理类Manager_Service.php里添加的代码       
             */
            echo "<br/><font color='#FF0000'>[需要在管理类Manager_Service里添加没有的代码]</font><br />";      
            $section_define="";
            $section_content="";
            foreach($tableList as $tablename){  
                $classname=self::getClassname($tablename);             
                if ($tableInfoList!=null&&count($tableInfoList)>0&&array_key_exists("$tablename", $tableInfoList)){
                    $table_comment=$tableInfoList[$tablename]["Comment"];
                    $table_comment=str_replace("关系表","",$table_comment); 
                    if (contain($table_comment,"\r")||contain($table_comment,"\n")){
                        $table_comment=preg_split("/[\s,]+/", $table_comment);    
                        $table_comment=$table_comment[0]; 
                    }  
                }else{
                    $table_comment="$classname";
                }    
                $classname{0} = strtolower($classname{0});  
                $service_classname=self::getServiceClassname($tablename); 
                $section_define .="    private static \$".$classname."Service;\r\n";  
                $section_content.="    /**\r\n".
                                  "     * 提供服务:".$table_comment."\r\n".
                                  "     */\r\n";    
                $section_content.="    public static function ".$classname."Service()\r\n".
                                  "    {\r\n".
                                  "        if (self::\$".$classname."Service==null) {\r\n".
                                  "            self::\$".$classname."Service=new $service_classname();\r\n".
                                  "        }\r\n".
                                  "        return self::\$".$classname."Service;\r\n".
                                  "    }\r\n\r\n";
            }                 
            $e_result="<?php\r\n".
                     "/**\r\n".
                     " +---------------------------------------<br/>\r\n".
                     " * 服务类:所有Service的管理类<br/>\r\n".
                     " +---------------------------------------\r\n".
                     " * @category $category\r\n".
                     " * @package $package\r\n".   
                     " * @author $author\r\n".
                     " */\r\n".  
                     "class Manager_Service extends Manager\r\n".  
                     "{\r\n".$section_define."\r\n".$section_content."}\r\n"; 
            $e_result.="?>";                                                                                      
            self::saveDefineToDir(self::$service_dir_full,"Manager_Service.php",$e_result);  
              
            echo  "新生成的Manager_Service文件路径:<font color='#0000FF'>".self::$service_dir_full."Manager_Service.php</font><br /><br /><br />";
/*            $section_define=str_replace(" ","&nbsp;",$section_define); 
            $section_define=str_replace("\r\n","<br />",$section_define); 
            echo  $section_define.'<br/>';
            $section_content=str_replace(" ","&nbsp;",$section_content);  
            $section_content=str_replace("\r\n","<br />",$section_content); 
            echo  $section_content;*/      
        }
        else if (self::$type==3)
        {
            /**
             * 需要在管理类Manager_ExtService.php里添加的代码       
             */
            echo "<br/><font color='#FF0000'>[需要在管理类Manager_ExtService里添加没有的代码]</font><br/>";      
            $section_define="";
            $section_content="";
            foreach($tableList as $tablename){  
                $classname=self::getClassname($tablename);             
                if ($tableInfoList!=null&&count($tableInfoList)>0&&array_key_exists("$tablename", $tableInfoList)){
                    $table_comment=$tableInfoList[$tablename]["Comment"];
                    $table_comment=str_replace("关系表","",$table_comment); 
                    if (contain($table_comment,"\r")||contain($table_comment,"\n")){
                        $table_comment=preg_split("/[\s,]+/", $table_comment);    
                        $table_comment=$table_comment[0]; 
                    }          
                }else{
                    $table_comment="$classname";
                }    
                $classname{0} = strtolower($classname{0});  
                $service_classname=self::getServiceClassname($tablename); 
                $section_define .="    private static \$".$classname."Service;\r\n";  
                $section_content.="    /**\r\n".
                                  "     * 提供服务:".$table_comment."\r\n".
                                  "     */\r\n";    
                $section_content.="    public static function ".$classname."Service()\r\n".
                                  "    {\r\n".
                                  "        if (self::\$".$classname."Service==null) {\r\n".
                                  "            self::\$".$classname."Service=new Ext$service_classname();\r\n".
                                  "        }\r\n".
                                  "        return self::\$".$classname."Service;\r\n".
                                  "    }\r\n\r\n";
            } 
            $package   = self::$app_dir.".".$package;    
            $e_result="<?php\r\n".
                     "/**\r\n".
                     " +---------------------------------------<br/>\r\n".
                     " * 服务类:所有ExtService的管理类<br/>\r\n".
                     " +---------------------------------------\r\n".
                     " * @category $category\r\n".
                     " * @package $package\r\n". 
                     " * @subpackage ext\r\n".  
                     " * @author $author\r\n".
                     " */\r\n".  
                     "class Manager_ExtService extends Manager\r\n".  
                     "{\r\n".$section_define."\r\n".$section_content."}\r\n"; 
            $e_result.="?>";              
            self::saveDefineToDir(self::$service_dir_full.self::$ext_dir.DIRECTORY_SEPARATOR,"Manager_ExtService.php",$e_result);                                                                     
            echo  "新生成的Manager_ExtService文件路径:<font color='#0000FF'>".self::$service_dir_full.self::$ext_dir.DIRECTORY_SEPARATOR,"Manager_ExtService.php</font><br /><br/>";
/*            $section_define=str_replace(" ","&nbsp;",$section_define); 
            $section_define=str_replace("\r\n","<br />",$section_define);  
            echo  $section_define.'<br/>';
            $section_content=str_replace(" ","&nbsp;",$section_content); 
            $section_content=str_replace("\r\n","<br />",$section_content);   
            echo  $section_content; */                                          
            /**
             * 需要在Ext Direct 服务配置文件:service.config.xml里添加的代码 
             */
            echo "<font color='#FF0000'>[需要在Ext Direct 服务配置文件:service.config.xml里添加没有的代码]</font><br/>";  
            $section_content="";
            foreach($tableList as $tablename){ 
                $classname=self::getClassname($tablename);  
                $section_content.="    <service name=\"ExtService{$classname}\">\r\n". 
                                  "        <methods>\r\n".       
                                  "            <method name=\"save\">\r\n". 
                                  "                <param name=\"len\">1</param>\r\n".       
                                  "                <param name=\"formHandler\">true</param>\r\n". 
                                  "            </method>\r\n".       
                                  "            <method name=\"update\">\r\n".       
                                  "                <param name=\"len\">1</param>\r\n". 
                                  "                <param name=\"formHandler\">true</param>\r\n".       
                                  "            </method>\r\n". 
                                  "            <method name=\"deleteByIds\">\r\n". 
                                  "                <param name=\"len\">1</param>\r\n". 
                                  "            </method>\r\n". 
                                  "            <method name=\"queryPage{$classname}\">\r\n". 
                                  "                <param name=\"len\">1</param>\r\n". 
                                  "            </method>\r\n". 
                                  "            <method name=\"export{$classname}\">\r\n". 
                                  "                <param name=\"len\">1</param>\r\n". 
                                  "            </method>\r\n". 
                                  "        </methods> \r\n". 
                                  "    </service>\r\n\r\n";   
            }           
            $e_result="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n".  
                     "<services>\r\n".  
                     "    <service name=\"ServiceMenuGroup\"> \r\n".           
                     "        <methods>\r\n".             
                     "            <method name=\"save\">\r\n".           
                     "                <param name=\"len\">1</param>\r\n".           
                     "                <param name=\"formHandler\">true</param> \r\n".           
                     "            </method>\r\n".             
                     "            <method name=\"update\">\r\n".           
                     "                <param name=\"len\">1</param>\r\n".         
                     "                <param name=\"formHandler\">true</param>\r\n".           
                     "            </method>\r\n".             
                     "            <method name=\"delete\">\r\n".           
                     "                <param name=\"len\">1</param>\r\n".           
                     "            </method>\r\n".           
                     "            <method name=\"allMenuGroup\">\r\n".             
                     "                <param name=\"len\">0</param> \r\n".           
                     "            </method>\r\n".      
                     "        </methods>\r\n".           
                     "    </service>\r\n\r\n".             
                     "    <service name=\"ServiceMenu\">\r\n".           
                     "        <methods>\r\n".           
                     "            <method name=\"save\">\r\n".           
                     "                <param name=\"len\">1</param>\r\n".             
                     "                <param name=\"formHandler\">true</param>\r\n".           
                     "            </method>\r\n".       
                     "            <method name=\"update\">\r\n".             
                     "                <param name=\"len\">1</param>\r\n".           
                     "                <param name=\"formHandler\">true</param>\r\n".   
                     "            </method>\r\n".             
                     "            <method name=\"delete\">\r\n".           
                     "                <param name=\"len\">1</param>\r\n".       
                     "            </method> \r\n".             
                     "            <method name=\"getMenusByGroupId\"> \r\n".           
                     "                <param name=\"len\">1</param>\r\n".   
                     "            </method>\r\n".           
                     "            <method name=\"queryPageMenu\">\r\n".       
                     "                <param name=\"len\">1</param>\r\n".             
                     "            </method>\r\n".           
                     "            <method name=\"queryPageMenuForm\">\r\n".   
                     "                <param name=\"len\">1</param> \r\n".             
                     "                <param name=\"formHandler\">true</param>\r\n".           
                     "            </method>\r\n".       
                     "        </methods>\r\n".             
                     "    </service>\r\n\r\n".    
                     $section_content.  
                     "</services>\r\n";                                                                                                                       
            self::saveDefineToDir(self::$service_dir_full,"service.config.xml",$e_result);   
            echo  "新生成的service.config.xml文件路径:<font color='#0000FF'>".self::$service_dir_full,"service.config.xml</font><br /><br /><br /><br />";
/*            $section_content=str_replace(" ","&nbsp;",$section_content);    
            $section_content=str_replace("<","&lt;",$section_content); 
            $section_content=str_replace(">","&gt;",$section_content); 
            $section_content=str_replace("\r\n","<br />",$section_content); 
            echo  $section_content;     */
        }      
    }

    /**
     * 用户输入需求
     */
    public static function UserInput()
    {
        $inputArr=array(
            "1"=>"继承具有标准方法的Service。",
            "2"=>"生成标准方法的Service。",
            "3"=>"生成ExtJs框架使用的Service【后台】。"
        );    
        parent::UserInput("需要定义生成服务类的输出文件路径参数",$inputArr);  
    }

    /**
     * 将表列定义转换成数据对象Php文件定义的内容
     * @param string $tablename 表名
     * @param array $tableInfoList 表信息列表
     * @param array $fieldInfos 表列信息列表
     */
    private static function tableToServiceDefine($tablename,$tableInfoList,$fieldInfos)
    {
        if (array_key_exists($tablename,$fieldInfos)){
            $fieldInfo=$fieldInfos[$tablename];
        }else{
            $fieldInfo=$fieldInfos;
        }
        $result            ="<?php\r\n";
        $classname         =self::getClassname($tablename);
        $instance_name     =self::getInstancename($tablename); 
        $service_classname =self::getServiceClassname($tablename);
        $object_desc       ="";
        if ($tableInfoList!=null&&count($tableInfoList)>0&&array_key_exists("$tablename", $tableInfoList))
        {
            $object_desc   =$tableInfoList[$tablename]["Comment"];
            $object_desc=str_replace("关系表","",$object_desc); 
            if (contain($object_desc,"\r")||contain($object_desc,"\n")){
                $object_desc=preg_split("/[\s,]+/", $object_desc);    
                $object_desc=$object_desc[0]; 
            }           
            $table_comment ="服务类:".$object_desc;                     
            //$table_comment =str_replace("\n","\r\n * ",$table_comment); 
            //if (endWith($table_comment,"\r\n * ")){
               //$table_comment=substr($table_comment,0,strlen($table_comment)-5);
            //}                                                         
        }else
        {
            $object_desc   =$classname;
            $table_comment ="关于服务类$classname的描述";
        }    
        $category = Gc::$appName;
        $author   = self::$author;
        $result  .= "//加载初始化设置\r\n";
        $package =self::$package;
        if (self::$type==3){   
            $package   = self::$app_dir.".".$package;  
        }
        $result.="class_exists(\"Service\")||require(\"../init.php\");\r\n";       
        $result.="/**\r\n".
                 " +---------------------------------------<br/>\r\n".
                 " * $table_comment<br/>\r\n".
                 " +---------------------------------------\r\n".
                 " * @category $category\r\n".
                 " * @package $package\r\n";
        if (self::$type==3){         
            $result.=" * @subpackage ext\r\n";
        }  
        $result.=" * @author $author\r\n".
                 " */\r\n";     
        switch (self::$type) {
            case 3:
                $result.="class Ext$service_classname extends ServiceBasic\r\n{\r\n"; 
                //save          
                $result.="    /**\r\n".
                         "     * 保存数据对象:{$object_desc}\r\n".
                         "     * @param array|DataObject \$$instance_name\r\n".
                         "     * @return int 保存对象记录的ID标识号\r\n".
                         "     */\r\n";
                $result.="    public function save(\$$instance_name)\r\n".
                         "    {\r\n".
                         self::bit2BoolInExtService($instance_name,$fieldInfo).
                         self::imageUploadInExtService($instance_name,$fieldInfo).
                         "        \$data=parent::save(\$$instance_name);\r\n".     
                         "        return array(\r\n".
                         "            'success' => true,\r\n".   
                         "            'data'    => \$data\r\n". 
                         "        ); \r\n".           
                         "    }\r\n\r\n"; 
                //update         
                $result.="    /**\r\n".
                         "     * 更新数据对象 :{$object_desc}\r\n".  
                         "     * @param array|DataObject \$$instance_name\r\n".
                         "     * @return boolen 是否更新成功；true为操作正常\r\n".
                         "     */\r\n";
                $result.="    public function update(\$$instance_name)\r\n".
                         "    {\r\n".
                         self::bit2BoolInExtService($instance_name,$fieldInfo).
                         self::enumComment2KeyInExtService($instance_name,$fieldInfo,$tablename).  
                         self::imageUploadInExtService($instance_name,$fieldInfo).   
                         "        \$data=parent::update(\$$instance_name);\r\n". 
                         "        return array(\r\n".
                         "            'success' => true,\r\n".   
                         "            'data'    => \$data\r\n". 
                         "        ); \r\n".    
                         "    }\r\n\r\n";     
                //uploadImg:有图片的上传图片功能         
                $result.=self::imageUploadFunctionInExtService($instance_name,$fieldInfo,$object_desc);                  
                //deleteByIds         
                $result.="    /**\r\n".
                         "     * 根据主键删除数据对象:{$object_desc}的多条数据记录\r\n".  
                         "     * @param array|string \$ids 数据对象编号\r\n".  
                         "     * 形式如下:\r\n".
                         "     * 1.array:array(1,2,3,4,5)\r\n".
                         "     * 2.字符串:1,2,3,4 \r\n".  
                         "     * @return boolen 是否删除成功；true为操作正常\r\n".
                         "     */\r\n";
                $result.="    public function deleteByIds(\$ids)\r\n".
                         "    {\r\n". 
                         "        \$data=parent::deleteByIds(\$ids);\r\n".     
                         "        return array(\r\n".
                         "            'success' => true,\r\n".   
                         "            'data'    => \$data\r\n". 
                         "        ); \r\n". 
                         "    }\r\n\r\n";   
                //queryPage         
                $result.="    /**\r\n".
                         "     * 数据对象:{$object_desc}分页查询\r\n". 
                         "     * @param stdclass \$formPacket  查询条件对象\r\n". 
                         "     * 必须传递分页参数：start:分页开始数，默认从0开始\r\n".
                         "     *                   limit:分页查询数，默认10个。\r\n".
                         "     * @return 数据对象:{$object_desc}分页查询列表\r\n".
                         "     */\r\n";
                $result.="    public function queryPage{$classname}(\$formPacket=null)\r\n".
                         "    {\r\n".                          
                         "        \$start=1;\r\n". 
                         "        \$limit=10;\r\n".                          
                         "        \$condition=UtilObject::object_to_array(\$formPacket);\r\n". 
                         "        if (isset(\$condition['start'])){\r\n".                          
                         "            \$start=\$condition['start']+1;\r\n". 
                         "          }\r\n".
                         "        if (isset(\$condition['limit'])){\r\n".                          
                         "            \$limit=\$condition['limit']; \r\n". 
                         "            \$limit=\$start+\$limit-1; \r\n".
                         "        }\r\n".        
                         "        unset(\$condition['start'],\$condition['limit']);\r\n".     
                         "        if (!empty(\$condition)&&(count(\$condition)>0)){\r\n". 
                         "            \$conditionArr=array();\r\n".                          
                         "            foreach (\$condition as \$key=>\$value) {\r\n". 
                         "                if (!UtilString::is_utf8(\$value)){\r\n".         
                         "                    \$value=UtilString::gbk2utf8(\$value);\r\n". 
                         "                }\r\n".                          
                         "                if (is_numeric(\$value)){ \r\n". 
                         "                    \$conditionArr[]=\$key.\"=\".\$value;\r\n".   
                         "                }else{\r\n". 
                         "                    \$conditionArr[]=\$key.\" like '%\".\$value.\"%'\"; \r\n".                          
                         "                }\r\n". 
                         "            }\r\n".         
                         "            \$condition=implode(\" and \",\$conditionArr);\r\n". 
                         "        }\r\n".  
                         "        \$count=parent::count(\$condition);\r\n".       
                         "        if (\$count>0){\r\n".          
                         "            if (\$limit>\$count)\$limit=\$count;\r\n".          
                         "            \$data =parent::queryPage(\$start,\$limit,\$condition);\r\n".   
                         self::enumKey2CommentInExtService($classname,$fieldInfo,"    "). 
                         "            if (\$data==null)\$data=array();\r\n".          
                         "        }else{\r\n".        
                         "            \$data=array();\r\n".        
                         "        }\r\n".        
                         "        return array(\r\n".
                         "            'success' => true,\r\n". 
                         "            'totalCount'=>\$count,\r\n".   
                         "            'data'    => \$data\r\n". 
                         "        ); \r\n".             
                         "    }\r\n\r\n";   
                //import
                $result.="    /**\r\n".
                         "     * 批量上传{$object_desc}\r\n".
                         "     * @param mixed \$upload_file <input name=\"upload_file\" type=\"file\">\r\n".
                         "     */\r\n".
                         "    public function import(\$_FILES)\r\n".
                         "    {\r\n".
                         "        \$diffpart=date(\"YmdHis\");\r\n".
                         "        if (!empty(\$_FILES[\"upload_file\"])){\r\n".
                         "            \$tmptail = end(explode('.', \$_FILES[\"upload_file\"][\"name\"]));\r\n".
                         "            \$uploadPath =GC::\$attachment_path.\"{$instance_name}\\\\import\\\\{$instance_name}\$diffpart.\$tmptail\";\r\n".
                         "            \$result     =UtilFileSystem::uploadFile(\$_FILES,\$uploadPath);\r\n".
                         "            if (\$result&&(\$result['success']==true)){\r\n".
                         "                if (array_key_exists('file_name',\$result)){\r\n".
                         "                    \$arr_import_header = self::fieldsMean({$classname}::tablename());\r\n".
                         "                    \$data              = UtilExcel::exceltoArray(\$uploadPath,\$arr_import_header);\r\n".
                         "                    \$result=false;\r\n".
                         "                    foreach (\$data as \${$instance_name}) {\r\n".
                         "                        \${$instance_name}=new {$classname}(\${$instance_name});\r\n".
                         self::enumComment2KeyInExtService($instance_name,$fieldInfo,$tablename,"                ").  
                         "                        \${$instance_name}_id=\${$instance_name}->getId();\r\n".
                         "                        if (!empty(\${$instance_name}_id)){\r\n".
                         "                            \$had{$classname}={$classname}::get_by_id(\${$instance_name}->getId());\r\n".
                         "                            if (\$had{$classname}!=null){\r\n".
                         "                                \$result=\${$instance_name}->update();\r\n".
                         "                            }else{\r\n".
                         "                                \$result=\${$instance_name}->save();\r\n".
                         "                            }\r\n".
                         "                        }else{\r\n".
                         "                            \$result=\${$instance_name}->save();\r\n".
                         "                        }\r\n".
                         "                    }\r\n".
                         "                }else{\r\n".
                         "                    \$result=false;\r\n".
                         "                }\r\n".
                         "            }else{\r\n".
                         "                return \$result;\r\n".
                         "            }\r\n".
                         "        }\r\n".
                         "        return array(\r\n".
                         "            'success' => true,\r\n".   
                         "            'data'    => \$result\r\n". 
                         "        );\r\n".
                         "    }\r\n\r\n";
                //export
                $result.="    /**\r\n".
                         "     * 导出{$object_desc}\r\n".
                         "     * @param mixed \$filter\r\n".
                         "     */\r\n".
                         "    public function export{$classname}(\$filter=null)\r\n".
                         "    {\r\n".
                         "        \$data=parent::get(\$filter);\r\n".
                         self::enumKey2CommentInExtService($classname,$fieldInfo).   
                         "        \$arr_output_header= self::fieldsMean({$classname}::tablename()); \r\n".
                         "        unset(\$arr_output_header['updateTime']);\r\n".
                         "        unset(\$arr_output_header['commitTime']);\r\n".
                         "        \$diffpart=date(\"YmdHis\");\r\n".
                         "        \$outputFileName=Gc::\$attachment_path.\"{$instance_name}\\\\export\\\\{$instance_name}\$diffpart.xls\"; \r\n".
                         "        UtilFileSystem::createDir(dirname(\$outputFileName)); \r\n".
                         "        UtilExcel::arraytoExcel(\$arr_output_header,\$data,\$outputFileName,false); \r\n".
                         "        \$downloadPath  =Gc::\$attachment_url.\"{$instance_name}/export/{$instance_name}\$diffpart.xls\"; \r\n".
                         "        return array(\r\n".
                         "            'success' => true,\r\n".   
                         "            'data'    => \$downloadPath\r\n". 
                         "        ); \r\n". 
                         "    }\r\n";
                break;
            case 2:     
                $result.="class $service_classname extends Service implements IServiceBasic \r\n{\r\n";    
                //save          
                $result.="    /**\r\n".
                         "     * 保存数据对象:{$object_desc}\r\n".
                         "     * @param array|DataObject \$$instance_name\r\n".
                         "     * @return int 保存对象记录的ID标识号\r\n".
                         "     */\r\n";
                $result.="    public function save(\$$instance_name)\r\n".
                         "    {\r\n".
                         "        if (is_array(\$$instance_name)){\r\n".                           
                         "            \$$instance_name=new $classname(\$$instance_name);\r\n".
                         "        }\r\n". 
                         "        if (\$$instance_name instanceof $classname){\r\n". 
                         "            return \$".$instance_name."->save();\r\n".
                         "        }else{\r\n". 
                         "            return false;\r\n".                        
                         "        }\r\n".                     
                         "    }\r\n\r\n"; 
                //update         
                $result.="    /**\r\n".
                         "     * 更新数据对象 :{$object_desc}\r\n".  
                         "     * @param array|DataObject \$$instance_name\r\n".
                         "     * @return boolen 是否更新成功；true为操作正常\r\n".
                         "     */\r\n";
                $result.="    public function update(\$$instance_name)\r\n".
                         "    {\r\n".
                         "        if (is_array(\$$instance_name)){\r\n".                           
                         "            \$$instance_name=new $classname(\$$instance_name);\r\n".
                         "        }\r\n". 
                         "        if (\$$instance_name instanceof $classname){\r\n". 
                         "            return \$".$instance_name."->update();\r\n".
                         "        }else{\r\n". 
                         "            return false;\r\n".                        
                         "        }\r\n".                     
                         "    }\r\n\r\n";                     
                //deleteByID         
                $result.="    /**\r\n".
                         "     * 由标识删除指定ID数据对象 :{$object_desc}\r\n".  
                         "     * @param int \$id 数据对象:{$object_desc}标识\r\n".
                         "     * @return boolen 是否删除成功；true为操作正常\r\n".
                         "     */\r\n";
                $result.="    public function deleteByID(\$id)\r\n".
                         "    {\r\n". 
                         "        return $classname::deleteByID(\$id);\r\n".
                         "    }\r\n\r\n";   
                //deleteByIds         
                $result.="    /**\r\n".
                         "     * 根据主键删除数据对象:{$object_desc}的多条数据记录\r\n".  
                         "     * @param array|string \$ids 数据对象编号\r\n".  
                         "     * 形式如下:\r\n".
                         "     * 1.array:array(1,2,3,4,5)\r\n".
                         "     * 2.字符串:1,2,3,4 \r\n".  
                         "     * @return boolen 是否删除成功；true为操作正常\r\n".
                         "     */\r\n";
                $result.="    public function deleteByIds(\$ids)\r\n".
                         "    {\r\n". 
                         "        return $classname::deleteByIds(\$ids);\r\n".
                         "    }\r\n\r\n";            
                //increment         
                $result.="    /**\r\n".
                         "     * 对数据对象:{$object_desc}的属性进行递增\r\n".  
                         "     * @param object|string|array \$filter 查询条件，在where后的条件<br/> \r\n".  
                         "     * 示例如下：<br/>\r\n".
                         "     * 0.\"id=1,name='sky'\"<br/>\r\n".
                         "     * 1.array(\"id=1\",\"name='sky'\")<br/> \r\n".  
                         "     * 2.array(\"id\"=>\"1\",\"name\"=>\"sky\")<br/>\r\n".
                         "     * 3.允许对象如new User(id=\"1\",name=\"green\");<br/>\r\n".
                         "     * 默认:SQL Where条件子语句。如：\"(id=1 and name='sky') or (name like 'sky')\"<br/>\r\n".
                         "     * @param string \$property_name 属性名称 \r\n".  
                         "     * @param int \$incre_value 递增数 \r\n". 
                         "     * @return boolen 是否操作成功；true为操作正常\r\n".
                         "     */\r\n";
                $result.="    public function increment(\$filter=null,\$property_name,\$incre_value)\r\n".
                         "    {\r\n". 
                         "        return $classname::increment(\$filter,\$property_name,\$incre_value);\r\n".
                         "    }\r\n\r\n"; 
                //decrement         
                $result.="    /**\r\n".
                         "     * 对数据对象:{$object_desc}的属性进行递减\r\n".  
                         "     * @param object|string|array \$filter 查询条件，在where后的条件<br/> \r\n".  
                         "     * 示例如下：<br/>\r\n".
                         "     * 0.\"id=1,name='sky'\"<br/>\r\n".
                         "     * 1.array(\"id=1\",\"name='sky'\")<br/> \r\n".  
                         "     * 2.array(\"id\"=>\"1\",\"name\"=>\"sky\")<br/>\r\n".
                         "     * 3.允许对象如new User(id=\"1\",name=\"green\");<br/>\r\n".
                         "     * 默认:SQL Where条件子语句。如：\"(id=1 and name='sky') or (name like 'sky')\"<br/>\r\n".
                         "     * @param string \$property_name 属性名称 \r\n".  
                         "     * @param int \$decre_value 递减数 \r\n". 
                         "     * @return boolen 是否操作成功；true为操作正常\r\n".
                         "     */\r\n";
                $result.="    public function decrement(\$filter=null,\$property_name,\$decre_value)\r\n".
                         "    {\r\n". 
                         "        return $classname::decrement(\$filter,\$property_name,\$decre_value);\r\n".
                         "    }\r\n\r\n"; 
                //select         
                $result.="    /**\r\n".
                         "     * 查询数据对象:{$object_desc}需显示属性的列表\r\n".  
                         "     * @param string \$columns 指定的显示属性，同SQL语句中的Select部分。 \r\n".  
                         "     * 示例如下：<br/>\r\n".
                         "     *    id,name,commitTime\r\n". 
                         "     * @param object|string|array \$filter 查询条件，在where后的条件<br/> \r\n".  
                         "     * 示例如下：<br/>\r\n".
                         "     * 0.\"id=1,name='sky'\"<br/>\r\n".
                         "     * 1.array(\"id=1\",\"name='sky'\")<br/> \r\n".  
                         "     * 2.array(\"id\"=>\"1\",\"name\"=>\"sky\")<br/>\r\n".
                         "     * 3.允许对象如new User(id=\"1\",name=\"green\");<br/>\r\n".
                         "     * 默认:SQL Where条件子语句。如：\"(id=1 and name='sky') or (name like 'sky')\"<br/> \r\n".  
                         "     * @param string \$sort 排序条件<br/>\r\n". 
                         "     * 示例如下：<br/>\r\n".    
                         "     *      1.id asc;<br/>\r\n". 
                         "     *      2.name desc;<br/>\r\n". 
                         "     * @param string \$limit 分页数目:同Mysql limit语法\r\n". 
                         "     * 示例如下：<br/>\r\n".    
                         "     *      0,10<br/>\r\n".     
                         "     * @return 数据对象:{$object_desc}列表数组\r\n".
                         "     */\r\n";
                $result.="    public function select(\$columns,\$filter=null,\$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID,\$limit=null)\r\n".
                         "    {\r\n". 
                         "        return $classname::select(\$columns,\$filter,\$sort,\$limit);\r\n".
                         "    }\r\n\r\n";   
                //get         
                $result.="    /**\r\n".
                         "     * 查询数据对象:{$object_desc}的列表\r\n".       
                         "     * @param object|string|array \$filter 查询条件，在where后的条件<br/> \r\n".  
                         "     * 示例如下：<br/>\r\n".
                         "     * 0.\"id=1,name='sky'\"<br/>\r\n".
                         "     * 1.array(\"id=1\",\"name='sky'\")<br/> \r\n".  
                         "     * 2.array(\"id\"=>\"1\",\"name\"=>\"sky\")<br/>\r\n".
                         "     * 3.允许对象如new User(id=\"1\",name=\"green\");<br/>\r\n".
                         "     * 默认:SQL Where条件子语句。如：\"(id=1 and name='sky') or (name like 'sky')\"<br/> \r\n".  
                         "     * @param string \$sort 排序条件<br/>\r\n". 
                         "     * 示例如下：<br/>\r\n".    
                         "     *      1.id asc;<br/>\r\n". 
                         "     *      2.name desc;<br/>\r\n". 
                         "     * @param string \$limit 分页数目:同Mysql limit语法\r\n". 
                         "     * 示例如下：<br/>\r\n".    
                         "     *      0,10<br/>\r\n".     
                         "     * @return 数据对象:{object_desc}列表数组\r\n".
                         "     */\r\n";
                $result.="    public function get(\$filter=null,\$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID,\$limit=null)\r\n".
                         "    {\r\n". 
                         "        return $classname::get(\$filter,\$sort,\$limit);\r\n".
                         "    }\r\n\r\n";   
                //get_one         
                $result.="    /**\r\n".
                         "     * 查询得到单个数据对象:{$object_desc}实体\r\n".       
                         "     * @param object|string|array \$filter 查询条件，在where后的条件<br/> \r\n".  
                         "     * 示例如下：<br/>\r\n".
                         "     * 0.\"id=1,name='sky'\"<br/>\r\n".
                         "     * 1.array(\"id=1\",\"name='sky'\")<br/> \r\n".  
                         "     * 2.array(\"id\"=>\"1\",\"name\"=>\"sky\")<br/>\r\n".
                         "     * 3.允许对象如new User(id=\"1\",name=\"green\");<br/>\r\n".
                         "     * 默认:SQL Where条件子语句。如：\"(id=1 and name='sky') or (name like 'sky')\"<br/> \r\n". 
                         "     * @param string \$sort 排序条件<br/>\r\n". 
                         "     * 示例如下：<br/>\r\n".    
                         "     *      1.id asc;<br/>\r\n". 
                         "     *      2.name desc;<br/>\r\n". 
                         "     * @return 单个数据对象:{$object_desc}实体\r\n".
                         "     */\r\n";
                $result.="    public function get_one(\$filter=null, \$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID)\r\n".
                         "    {\r\n". 
                         "        return $classname::get_one(\$filter,\$sort);\r\n".
                         "    }\r\n\r\n";   
                //get_by_id         
                $result.="    /**\r\n".
                         "     * 根据表ID主键获取指定的对象[ID对应的表列] \r\n".       
                         "     * @param string \$id  \r\n".  
                         "     * @return 单个数据对象:{$object_desc}实体\r\n".
                         "     */\r\n";
                $result.="    public function get_by_id(\$id)\r\n".
                         "    {\r\n". 
                         "        return $classname::get_by_id(\$id);\r\n".
                         "    }\r\n\r\n"; 
                //count         
                $result.="    /**\r\n".
                         "     * 数据对象:{$object_desc}总计数\r\n".       
                         "     * @param object|string|array \$filter 查询条件，在where后的条件<br/> \r\n".  
                         "     * 示例如下：<br/>\r\n".
                         "     * 0.\"id=1,name='sky'\"<br/>\r\n".
                         "     * 1.array(\"id=1\",\"name='sky'\")<br/> \r\n".  
                         "     * 2.array(\"id\"=>\"1\",\"name\"=>\"sky\")<br/>\r\n".
                         "     * 3.允许对象如new User(id=\"1\",name=\"green\");<br/>\r\n".
                         "     * 默认:SQL Where条件子语句。如：\"(id=1 and name='sky') or (name like 'sky')\"<br/> \r\n". 
                         "     * @return 数据对象:{$object_desc}总计数\r\n".
                         "     */\r\n";
                $result.="    public function count(\$filter=null)\r\n".
                         "    {\r\n". 
                         "        return $classname::count(\$filter);\r\n".
                         "    }\r\n\r\n";   
                //queryPage         
                $result.="    /**\r\n".
                         "     * 数据对象:{$object_desc}分页查询\r\n". 
                         "     * @param int \$startPoint  分页开始记录数\r\n". 
                         "     * @param int \$endPoint    分页结束记录数\r\n".
                         "     * @param object|string|array \$filter 查询条件，在where后的条件<br/> \r\n".  
                         "     * 示例如下：<br/>\r\n".
                         "     * 0.\"id=1,name='sky'\"<br/>\r\n".
                         "     * 1.array(\"id=1\",\"name='sky'\")<br/> \r\n".  
                         "     * 2.array(\"id\"=>\"1\",\"name\"=>\"sky\")<br/>\r\n".
                         "     * 3.允许对象如new User(id=\"1\",name=\"green\");<br/>\r\n".
                         "     * 默认:SQL Where条件子语句。如：\"(id=1 and name='sky') or (name like 'sky')\"<br/> \r\n".  
                         "     * @param string \$sort 排序条件<br/>\r\n". 
                         "     * 默认为 id desc<br/>\r\n".    
                         "     * 示例如下：<br/>\r\n".    
                         "     *      1.id asc;<br/>\r\n". 
                         "     *      2.name desc;<br/>\r\n".   
                         "     * @return 数据对象:{$object_desc}分页查询列表\r\n".
                         "     */\r\n";
                $result.="    public function queryPage(\$startPoint,\$endPoint,\$filter=null,\$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID)\r\n".
                         "    {\r\n". 
                         "        return $classname::queryPage(\$startPoint,\$endPoint,\$filter,\$sort);\r\n".
                         "    }\r\n";    
                //sqlExecute    
                $result.="    /**\r\n".   
                         "     * 直接执行SQL语句\r\n".                                             
                         "     * @return array\r\n".
                         "     *  1.执行查询语句返回对象数组\r\n".
                         "     *  2.执行更新和删除SQL语句返回执行成功与否的true|null\r\n".
                         "     */\r\n".
                         "    public function sqlExecute()\r\n".
                         "    {\r\n".
                         "        return self::dao()->sqlExecute(\"select * from \".$classname::tablename(),$classname::classname_static());\r\n".
                         "    }\r\n";                     
                break;
            default:          
                $result.="class $service_classname extends ServiceBasic\r\n{\r\n";                         
                $result.= "\r\n";            
                break;
        }
        $result.="}\r\n";    
        $result.="?>";
        return $result;
    }    
    
    /**
     * 将表列为上传图片路径类型的列提供上传图片的功能 
     * @param string $instance_name 实体变量    
     * @param array $fieldInfo 表列信息列表    
     */
    private static function imageUploadInExtService($instance_name,$fieldInfo)
    {
        $result="";   
        foreach ($fieldInfo as $fieldname=>$field){
            $isImage =self::columnIsImage($fieldname,$field["Comment"]);
            if ($isImage){                                           
                $result.="        if (!empty(\$_FILES)&&!empty(\$_FILES[\"imageUpload\"][\"name\"])){\r\n".
                         "            \$result=\$this->uploadImg(\$_FILES);\r\n".
                         "            if (\$result&&(\$result['success']==true)){\r\n".   
                         "                if (array_key_exists('file_name',\$result)){ \r\n".
                         "                    \${$instance_name}[\"{$fieldname}\"]= \$result['file_name'];\r\n".            
                         "                }\r\n".
                         "            }else{\r\n".            
                         "                return \$result;\r\n".
                         "            }\r\n".      
                         "        }\r\n";
            }
        }   
        return $result;
    }
    
    /**
     * 将表列为上传图片路径类型的列提供上传图片的函数,为图片上传功能提供支持 
     * @param string $instance_name 实体变量    
     * @param array $fieldInfo 表列信息列表
     * @param string $object_desc     
     */
    private static function imageUploadFunctionInExtService($instance_name,$fieldInfo,$object_desc)
    {
        $result="";   
        foreach ($fieldInfo as $fieldname=>$field){
            $isImage =self::columnIsImage($fieldname,$field["Comment"]);
            if ($isImage){                                            
                $result.="\r\n".
                         "    /**\r\n".
                         "     * 上传{$object_desc}图片文件\r\n".
                         "     */\r\n".     
                         "    public function uploadImg(\$_FILES)\r\n".
                         "    {\r\n". 
                         "        \$diffpart=date(\"YmdHis\");\r\n".
                         "        \$result=\"\";\r\n". 
                         "        if (!empty(\$_FILES[\"imageUpload\"])&&!empty(\$_FILES[\"imageUpload\"][\"name\"])){\r\n".
                         "            \$tmptail = end(explode('.', \$_FILES[\"imageUpload\"][\"name\"]));\r\n". 
                         "            \$uploadPath =GC::\$upload_path.\"images\\\\{$instance_name}\\\\\$diffpart.\$tmptail\";\r\n".
                         "            \$result     =UtilFileSystem::uploadFile(\$_FILES,\$uploadPath,\"imageUpload\");\r\n". 
                         "            if (\$result&&(\$result['success']==true)){\r\n".
                         "                \$result['file_name']=\"product/\$diffpart.\$tmptail\";\r\n".    
                         "            }else{\r\n".                     
                         "                return array(\r\n".                     
                         "                    'success' => true,\r\n".                     
                         "                    'data'    => false\r\n".                     
                         "                );\r\n".                     
                         "            }\r\n".                         
                         "        }\r\n".                                
                         "        return \$result;\r\n".                                             
                         "    }\r\n";
            }
        }   
        return $result; 
    }     
    
    /**
     * 将表列为bit类型的列转换成需要存储在数据库里的bool值 
     * @param string $instance_name 实体变量    
     * @param array $fieldInfo 表列信息列表    
     */
    private static function bit2BoolInExtService($instance_name,$fieldInfo)
    {
        $result="";   
        foreach ($fieldInfo as $fieldname=>$field){
            $datatype =self::column_type($field["Type"]);
            if ($datatype=='bit'){                                           
                $result.="        if (isset(\${$instance_name}[\"$fieldname\"])&&(\${$instance_name}[\"$fieldname\"]=='1'))\${$instance_name}[\"$fieldname\"]=true; else \${$instance_name}[\"$fieldname\"]=false;\r\n";
            }
        }   
        return $result;  
    }
    
    /**
     * 将表列为枚举类型的列用户能阅读的注释文字内容转换成需要存储在数据库里的值 
     * @param string $instance_name 实体变量    
     * @param array $fieldInfos 表列信息列表
     * @param string $blankPre 空白字符
     */    
    private static function enumComment2KeyInExtService($instance_name,$fieldInfo,$tablename,$blankPre="")
    { 
        $result="";   
        foreach ($fieldInfo as $fieldname=>$field){
            $datatype =self::comment_type($field["Type"]);
            if ($datatype=='enum'){
                $enumclassname=self::enumClassName($fieldname,$tablename);    
                $enum_columnDefine=self::enumDefines($field["Comment"]);
                $result.=$blankPre."        if (!{$enumclassname}::isEnumValue(\$".$instance_name."[\"".$fieldname."\"])){\r\n".                                          
                         $blankPre."            \$".$instance_name."["."\"".$fieldname."\""."]=".$enumclassname."::".$fieldname."ByShow(\$".$instance_name."[\"".$fieldname."\"]);\r\n".
                         $blankPre."        }\r\n";   
            }
        }   
        return $result;  
    }
    
    /**
     * 将表列为枚举类型的列转换成用户能阅读的注释文字内容 
     * @param string $enumclassname 枚举类名称
     * @param array $fieldInfos 表列信息列表
     * @param string $blankPre 空白字符
     */
    private static function enumKey2CommentInExtService($classname,$fieldInfo,$blankPre="")
    {   
        $result="";
        $enumColumns=array();         
        foreach ($fieldInfo as $fieldname=>$field){
            $datatype =self::comment_type($field["Type"]);
            if ($datatype=='enum'){                                        
                $enumColumns[]="'".$fieldname."'";
            }
        }        
        if (count($enumColumns)>0){
            $enumColumns=implode(",",$enumColumns);
            $result.=$blankPre."        if ((!empty(\$data))&&(count(\$data)>0))\r\n".  
                     $blankPre."        {\r\n".                                                                
                     $blankPre."            $classname::propertyShow(\$data,array($enumColumns));\r\n".
                     $blankPre."        }\r\n";
        }
        return $result;   
    }   
     
    /**
     * 从表名称获取服务的类名。
     * @param string $tablename
     * @return string 返回对象的类名 
     */
    private static function getServiceClassname($tablename)
    {
        $classnameSplit = explode("_", $tablename);
        $classname      ='Service'.ucfirst($classnameSplit[count($classnameSplit)-1]);   
        return $classname;
    }
       
    /**
     * 保存生成的代码到指定命名规范的文件中 
     * @param string $tablename 表名称
     * @param string $definePhpFileContent 生成的代码 
     */
    private static function saveServiceDefineToDir($tablename,$definePhpFileContent)
    { 
        $filename =self::getServiceClassname($tablename).".php";  
        if (self::$type==3){
            $filename = "Ext".$filename;
        }       
        $service_dir_full=self::$service_dir_full;             
        if (self::$type==3){
            $service_dir_full=$service_dir_full.DIRECTORY_SEPARATOR.self::$ext_dir;
        }                                                                                                        
        return self::saveDefineToDir($service_dir_full,$filename,$definePhpFileContent);
    }
}

?>
