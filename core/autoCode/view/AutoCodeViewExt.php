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
   * 查询过滤条件字段
   */
  public static $filter_fieldnames;

  /**
   * 设置必需的路径
   */
  public static function pathset()
  {                            
    self::$app_dir="admin";  
    self::$view_dir_full=self::$save_dir.DIRECTORY_SEPARATOR.self::$app_dir.DIRECTORY_SEPARATOR.Config_F::VIEW_VIEW.DIRECTORY_SEPARATOR.Gc::$self_theme_dir.DIRECTORY_SEPARATOR;
    self::$menuconfig_dir_full=self::$save_dir.DIRECTORY_SEPARATOR.self::$app_dir.DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR."view".DIRECTORY_SEPARATOR."menu".DIRECTORY_SEPARATOR;
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
    }
    $tableInfoList=Manager_Db::newInstance()->dbinfo()->tableInfoList(); 
    echo UtilCss::form_css()."\r\n";
    foreach ($fieldInfos as $tablename=>$fieldInfo){
      $defineJsFileContent=self::tableToViewJsDefine($tablename,$tableInfoList,$fieldInfo);
      if (isset(self::$save_dir)&&!empty(self::$save_dir)&&isset($defineJsFileContent)){
        $jsName=self::saveJsDefineToDir($tablename,$defineJsFileContent);
        echo "生成导出完成:$tablename->$jsName!<br/>";   
      }else{
        echo $defineJsFileContent."<br/>";
      }       
    } 

    foreach ($fieldInfos as $tablename=>$fieldInfo){      
      $defineTplFileContent=self::tableToViewTplDefine();
      if (isset(self::$save_dir)&&!empty(self::$save_dir)&&isset($defineTplFileContent)){
        $tplName=self::saveTplDefineToDir($tablename,$defineTplFileContent);
        echo "生成导出完成:$tablename->$tplName!<br/>";   
      }else{
        echo $defineTplFileContent."<br/>";
      }   
    }        
                              
    /**
     * 需要在后端admin/src/view/menu目录下 菜单配置文件:menu.config.xml里添加的代码 
     */
    echo "<br/><font color='#FF0000'>[需要在后端admin/src/view/menu目录下 菜单配置文件:menu.config.xml里添加没有的代码]</font><br/>";  
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
    echo  $section_content;    */
  }  

  /**
   * 用户输入需求
   */
  public static function UserInput()
  {
    parent::UserInput("使用ExtJs框架生成表示层【用于后台】的输出文件路径参数");  
  }
                        
  /**
   * 将表列定义转换成使用ExtJs生成的表示层Js文件定义的内容
   * @param string $tablename 表名
   * @param array $tableInfoList 表信息列表
   * @param array $fieldInfo 表列信息列表
   */
  public static function tableToViewJsDefine($tablename,$tableInfoList,$fieldInfo)
  {
    $appName=Gc::$appName;
    if ($tableInfoList!=null&&count($tableInfoList)>0&&  array_key_exists("$tablename", $tableInfoList))
    {
      $table_comment=$tableInfoList[$tablename]["Comment"];
      $table_comment=str_replace("关系表","",$table_comment); 
      if (contain($table_comment,"\r")||contain($table_comment,"\n")){
        $table_comment=preg_split("/[\s,]+/", $table_comment);    
        $table_comment=$table_comment[0]; 
      }
    }else
    {
      $table_comment=$tablename;
    }    
    $classname=self::getClassname($tablename);
    $instancename=self::getInstancename($tablename);    
    $fields="";//Ext "store" 中包含的fields
    foreach ($fieldInfo as $fieldname=>$field)
    {
      if (self::isNotColumnKeywork($fieldname))
      { 
        $datatype=self::comment_type($field["Type"]);
        $fields.="                {name: '$fieldname',type: '".$datatype."'";
        if ($datatype=='date')
        {
          $fields.=",dateFormat:'Y-m-d H:i:s'";
        }
        $fields.="},\r\n";
      }
    }
    $fields=substr($fields,0,strlen($fields)-3);  
    $fieldLabels="";//Ext "EditWindow"里items的fieldLabels
    $tableFieldIdName;
    foreach ($fieldInfo as $fieldname=>$field)
    {             
      if (self::isNotColumnKeywork($fieldname))
      { 
        $column_type=self::column_type($field["Type"]);      
        if ($fieldname==self::keyIDColumn($classname))
        {
          $tableFieldIdName=$fieldname;  
          $fieldLabels.="                            {xtype: 'hidden',  name : '$fieldname',ref:'../$fieldname'"; 
        }else
        {
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
          }else
          {  
            $fr1="";
            $fr2=""; 
          } 
          //当使用form.getForm().submit()方式提交时，服务器得到的请求字段中的值总是combobox实际显示的值，也就是displayField:'text'的值;
          //将name属性修改为hiddenName，便会将value值提交给服务器 
          if ($column_type=='enum'){
              $flName="hiddenName";
          }else{
              $flName="name";  
          }          
          $fieldLabels.="                            {fieldLabel : '$field_comment$fr1',$flName : '$fieldname'$fr2"; 
          if ($datatype=='date')
          {
            $fieldLabels.=",xtype : 'datefield',format : \"Y-m-d\"";
          }          
          if ($column_type=='bit')
          {
            $fieldLabels.=",xtype : 'combo',mode : 'local',triggerAction : 'all',lazyRender : true,editable: false,\r\n".
                          "                                store : new Ext.data.SimpleStore({\r\n".
                          "                                        fields : ['value', 'text'],\r\n".
                          "                                        data : [['0', '否'], ['1', '是']]\r\n".
                          "                                }),emptyText: '请选择$field_comment',\r\n".
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
                          "                                }),emptyText: '请选择$field_comment',\r\n".
                          "                                valueField : 'value',// 值\r\n".
                          "                                displayField : 'text'// 显示文本\r\n                            ";  
          } 
          if (self::columnIsTextArea($fieldname,$field["Type"]))
          {
            $fieldLabels.=",xtype : 'textarea'"; 
          }
        }
        if ($column_type=='bit')
        {
          $fieldLabels.="                            },\r\n";   
        }else
        {
          $fieldLabels.="},\r\n";   
        }
      }      
    }
    $fieldLabels=substr($fieldLabels,0,strlen($fieldLabels)-3);  
    $viewdoblock="";//Ext "Tabs" 中"onAddItems"包含的viewdoblock
    foreach ($fieldInfo as $fieldname=>$field)
    {
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
        $viewdoblock.="                         '<div class=\"entry\"><span class=\"head\">$field_comment :</span><span class=\"content\">{{$fieldname}}</span></div>',\r\n";
      }
    }
    $viewdoblock=substr($viewdoblock,0,strlen($viewdoblock)-2);
    $columns="";//Ext "Grid" 中包含的columns
    foreach ($fieldInfo as $fieldname=>$field)
    {
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
        $datatype=self::comment_type($field["Type"]);
        $columns.="                        {header : '$field_comment',dataIndex : '{$fieldname}'";
        if ($datatype=='date')
        {
          $columns.=",renderer:Ext.util.Format.dateRenderer('Y-m-d')";
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
          $filterFields.="                                '{$field_comment}　',";
          if ($datatype=='date'){
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
                    "                                    }),\r\n".
                    "                                    valueField : 'value',// 值\r\n".
                    "                                    displayField : 'text'// 显示文本\r\n".
                    "                                ";
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
   * 列是否大量文本输入应该TextArea输入  
   * @param string $column_name 列名称
   * @param string $column_type 列类型
   */
  private static function columnIsTextArea($column_name,$column_type)
  {        
    if ((self::column_length($column_type)>=500)||(contain($column_name,"intro"))||(self::column_type($column_type)=='text')){
       return true;
    }else{
       return false;
    } 
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
   * 是否默认的列关键字：id,committime,updateTime   
   * @param string $fieldname 列名称
   */
  private static function isNotColumnKeywork($fieldname)
  {                                         
    if ($fieldname=="id"||$fieldname=="commitTime"||$fieldname=="updateTime"){
      return false; 
    }else{    
      return true;
    }
  }
             

  /**
   * 将表列定义转换成使用ExtJs生成的表示层tpl文件定义的内容
   * @param string $tablename 表名
   * @param array $tableInfoList 表信息列表
   * @param array $fieldInfo 表列信息列表
   */
  private static function tableToViewTplDefine()
  {
    $result ="{extends file=\"\$templateDir/layout/normal/layout.tpl\"}\r\n".
             "{block name=body}\r\n".
             "    <div id=\"loading-mask\"></div>\r\n".
             "    <div id=\"loading\">\r\n".    
             "        <div class=\"loading-indicator\"><img src=\"{$url_base}common/js/ajax/ext/resources/images/extanim32.gif\" width=\"32\" height=\"32\" style=\"margin-right:8px;\" align=\"absmiddle\"/>正在加载中...</div>\r\n".  
             "    </div>\r\n". 
             "   <div id=\"win1\" class=\"x-hide-display\"></div>\r\n".
             "{/block}\r\n";  
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