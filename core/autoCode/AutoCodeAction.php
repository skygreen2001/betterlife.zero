<?php        
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-控制器<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.autoCode   
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeAction extends AutoCode
{
    /**
     * 控制器生成定义的方式<br/>
     * 1.前端Action，继承基本Action。<br/> 
     * 2.后端Action，继承ActionExt。<br/>
     */
    public static $type;
    /**
     *Action文件所在的路径 
     */
    public static $action_dir="action";      
    /**
     * Action完整的保存路径
     */
    public static $action_dir_full;        
    /**
     * 前端Action所在的namespace
     */
    public static $package_front="web.front.action"; 
    /**
     * 前端Action所在的namespace
     */
    public static $package_back="web.back.admin";    
    /**
     * 需打印输出的文本
     * @var string
     */
    public static $echo_result="";        
    /**
    * 需打印输出  
    * @var string
    */
    public static $echo_upload="";
    
    /**
     * 自动生成代码-控制器
     */
    public static function AutoCode()
    {         
        if (self::$type==2){
            self::$app_dir="admin";
        }else{
            self::$app_dir=Gc::$appName;
        }    
        self::$action_dir_full=self::$save_dir.DIRECTORY_SEPARATOR.self::$app_dir.DIRECTORY_SEPARATOR.self::$action_dir.DIRECTORY_SEPARATOR;
        if (!UtilString::is_utf8(self::$action_dir_full)){
            self::$action_dir_full=UtilString::gbk2utf8(self::$action_dir_full);    
        }     
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
        self::$echo_result="";
        self::$echo_upload="";
        foreach ($fieldInfos as $tablename=>$fieldInfo){  
           $definePhpFileContent=self::tableToActionDefine($tablename,$tableInfoList,$fieldInfo);
           if (!empty($definePhpFileContent)){
               if (isset(self::$save_dir)&&!empty(self::$save_dir)&&isset($definePhpFileContent)){
                   $classname=self::saveActionDefineToDir($tablename,$definePhpFileContent);
                   echo "生成导出完成:$tablename=>$classname!<br/>";   
               }else{
                   echo $definePhpFileContent."<br/>";
               }
           }
        }               
        $category_cap=Gc::$appName;
        $category_cap{0}=ucfirst($category_cap{0});
        if (self::$type==2){                                                       
            echo "<br/><font color='#FF0000'>[需要在【后台】Action_Index或者Action_".$category_cap."里添加没有的代码]</font><br />";
            $category  = Gc::$appName;              
            $package   = self::$package_back;              
            $author    = self::$author; 
            $e_index="     /**\r\n".
                     "      * 控制器:首页\r\n". 
                     "      */\r\n". 
                     "     public function index()\r\n". 
                     "     {\r\n". 
                     "         \$this->init();\r\n". 
                     "         \$this->loadIndexJs();\r\n". 
                     "         //加载菜单 \r\n". 
                     "         \$this->view->menuGroups=MenuGroup::all(); \r\n". 
                     "     }\r\n\r\n".
                     "     /**\r\n".                    
                     "      * 初始化，加载Css和Javascript库。\r\n".
                     "      */ \r\n".                    
                     "     private function init()\r\n".  
                     "     {\r\n".                    
                     "         //初始化加载Css和Javascript库 \r\n".
                     "         \$this->view->viewObject=new ViewObject();\r\n".                    
                     "         UtilCss::loadExt(\$this->view->viewObject,UtilAjaxExtjs::\$ext_version);\r\n".    
                     "         UtilAjaxExtjs::loadUI(\$this->view->viewObject,UtilAjaxExtjs::\$ext_version);\r\n".                    
                     "     }\r\n\r\n".
                     "     /**\r\n".                    
                     "      * 预加载首页JS定义库。\r\n".  
                     "      * @param ViewObject $viewobject 表示层显示对象 \r\n".                    
                     "      * @param string $templateurl\r\n".
                     "      */\r\n".                    
                     "     private function loadIndexJs()\r\n".                                                            
                     "     {\r\n".     
                     "        \$viewobject=\$this->view->viewObject;\r\n".                                                            
                     "        \$this->loadExtCss(\"index.css\",true);\r\n".  
                     "        \$this->loadExtJs(\"index.js\"); \r\n".                                                            
                     "        //核心功能:外观展示\r\n".     
                     "        \$this->loadExtJs(\"layout.js\",true);\r\n".                                                            
                     "        //左侧菜单组生成显示\r\n".                                                                             
                     "        UtilJavascript::loadJsContentReady(\$viewobject,MenuGroup::viewForExtJs()); \r\n".     
                     "        //核心功能:导航[Tab新建窗口]  \r\n".                                                            
                     "        \$this->loadExtJs(\"navigation.js\"); \r\n".  
                     "     }\r\n\r\n";         
            $action_names=array("Action_Index","Action_".$category_cap); 
            foreach($action_names as $action_name){
                $e_result="<?php\r\n".
                         "/**\r\n".
                         " +---------------------------------------<br/>\r\n".
                         " * 控制器:网站后台管理<br/>\r\n".
                         " +---------------------------------------\r\n".
                         " * @category $category\r\n".
                         " * @package $package\r\n".
                         " * @subpackage action\r\n".
                         " * @author $author\r\n".
                         " */\r\n".  
                         "class $action_name extends ActionExt\r\n".  
                         "{\r\n".$e_index.self::$echo_result."}";                        
                self::saveDefineToDir(self::$action_dir_full,"$action_name.php",$e_result); 
                echo  "新生成的$action_name文件路径:<font color='#0000FF'>".self::$action_dir_full."$action_name.php</font><br />";  
            }                                                              
            
/*            self::$echo_result=str_replace(" ","&nbsp;",self::$echo_result);      
            self::$echo_result=str_replace("\r\n","<br />",self::$echo_result);    
            echo self::$echo_result;     */
            
            echo "<br/><font color='#FF0000'>[需要在【后台】Action_Upload里添加没有的代码]</font><br/>";   
            $e_result="<?php\r\n".
                     "/**\r\n".
                     " +---------------------------------------<br/>\r\n".
                     " * 控制器:上传文件<br/>\r\n".
                     " +---------------------------------------\r\n".
                     " * @category $category\r\n".
                     " * @package $package\r\n".
                     " * @subpackage action\r\n".
                     " * @author $author\r\n".
                     " */\r\n".  
                     "class Action_Upload extends ActionExt\r\n".  
                     "{\r\n".self::$echo_upload."}";   
            self::saveDefineToDir(self::$action_dir_full,"Action_Upload.php",$e_result);   
            echo  "新生成的Action_Upload文件路径:<font color='#0000FF'>".self::$action_dir_full."Action_Upload.php</font><br />";
/*            self::$echo_upload=str_replace(" ","&nbsp;",self::$echo_upload);      
            self::$echo_upload=str_replace("\r\n","<br />",self::$echo_upload);    
            echo self::$echo_upload;  */  
        }      
    }

    /**
     * 用户输入需求                 
     */
    public static function UserInput()
    {
        $inputArr=array(
            "1"=>"前端Action，继承基本Action。",
            "2"=>"后端Action，继承ActionExt"
        );    
        parent::UserInput("需要定义生成控制器Action类的输出文件路径参数",$inputArr);  
    }

    /**
     * 将表列定义转换成数据对象Php文件定义的内容
     * @param string $tablename 表名
     * @param array $tableInfoList 表信息列表
     * @param array $fieldInfo 表列信息列表
     */
    private static function tableToActionDefine($tablename,$tableInfoList,$fieldInfo)
    {
        $result="<?php\r\n";
        if ($tableInfoList!=null&&count($tableInfoList)>0&&  array_key_exists("$tablename", $tableInfoList)){
            $table_comment=$tableInfoList[$tablename]["Comment"];
            $table_comment=str_replace("关系表","",$table_comment); 
            if (contain($table_comment,"\r")||contain($table_comment,"\n")){
                $table_comment=preg_split("/[\s,]+/", $table_comment);    
                $table_comment=$table_comment[0]; 
            }
        }else{
            $table_comment="$tablename";
        }   
        $category  = Gc::$appName;              
        $package   = self::$package_front;
        $classname = self::getClassname($tablename);
        $instancename=self::getInstancename($tablename);  
        $author    = self::$author;
        switch (self::$type) {
            case 2:  
                $result ="     /**\r\n";
                $result.="      * 控制器:$table_comment\r\n"; 
                $result.="      */\r\n";
                $result.="     public function $instancename()\r\n"; 
                $result.="     {\r\n";
                $result.="         \$this->init();\r\n"; 
                $result.="         \$this->ExtDirectMode();\r\n";
                $result.="         \$this->ExtUpload();\r\n"; 
                $result.="         \$this->loadExtJs('$instancename/$instancename.js');\r\n"; 
                foreach ($fieldInfo as $fieldname=>$field)
                {                    
                    if (self::columnIsTextArea($fieldname,$field["Type"]))
                    {
                        $result.="         \$this->view->editorHtml=UtilCKEeditor::loadReplace(\"$fieldname\");\r\n"; 
                    }   
                }
                
                $result.="     }\r\n\r\n";       
                self::$echo_result.=$result;  
                $result_upload ="    /**\r\n".                        
                         "     * 上传数据对象:{$table_comment}数据文件\r\n".  
                         "     */\r\n".  
                         "    public function upload{$classname}()\r\n".  
                         "    {\r\n".                         
                         "        return self::ExtResponse(Manager_ExtService::{$instancename}Service()->import(\$_FILES)); \r\n".  
                         "    }\r\n\r\n";  
                self::$echo_upload.=$result_upload;                
                return "";
            default:                
                $result.="/**\r\n".
                         " +---------------------------------------<br/>\r\n".
                         " * 控制器:$table_comment<br/>\r\n".
                         " +---------------------------------------\r\n".
                         " * @category $category\r\n".
                         " * @package $package\r\n".
                         " * @author $author\r\n".
                         " */\r\n".  
                         "class Action_$classname extends Action\r\n".  
                         "{\r\n".
                         "    /**\r\n".
                         "     * {$table_comment}列表页面\r\n".
                         "     */\r\n".
                         "    public function lists()\r\n".
                         "    {\r\n".
                         "    }\r\n".
                         "    /**\r\n".
                         "     * {$table_comment}详情页面\r\n".
                         "     */\r\n".
                         "    public function view()\r\n".
                         "    {\r\n".
                         "    }\r\n".
                         "}\r\n\r\n"; 
                $result.="?>";  
                break;
        }             
        return $result;
    }

    /**
     * 保存生成的代码到指定命名规范的文件中 
     * @param string $tablename 表名称
     * @param string $definePhpFileContent 生成的代码 
     */
    private static function saveActionDefineToDir($tablename,$definePhpFileContent)
    {
        $filename="Action_".self::getClassname($tablename).".php";    
        return self::saveDefineToDir(self::$action_dir_full,$filename,$definePhpFileContent);
    }    
}

?>
