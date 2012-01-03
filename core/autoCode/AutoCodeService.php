<?php

/**
* 工具类:自动生成代码-服务类
*/
class AutoCodeService extends AutoCode
{
	/**
     * 服务类生成定义的方式<br/>
     * 1.继承具有标准方法的Service。
     * 2.生成标准方法的Service。
     */
	public static $type;
    /**
     * 自动生成代码-服务类
     */
	public static function AutoCode()
	{
	    $tableList=Manager_Db::newInstance()->dbinfo()->tableList(); 
	    $tableInfoList=Manager_Db::newInstance()->dbinfo()->tableInfoList(); 
	    echo UtilCss::form_css()."\r\n";           
	    foreach($tableList as $tablename){     
	        $definePhpFileContent=self::tableToServiceDefine($tablename,$tableInfoList); 
	        if (isset(self::$save_dir)&&!empty(self::$save_dir)&&isset($definePhpFileContent)){
	           $classname=self::saveServiceDefineToDir(self::$save_dir,$tablename,$definePhpFileContent);
	           echo "生成导出完成:$tablename->$classname!<br/>";   
	        }else{
	           echo $definePhpFileContent."<br/>";
	        }         
	    }
	    
	    /**
	    * 需要在管理类Manager_Service里添加的代码       
	    */
	    echo "<br/><br/>需要在管理类Manager_Service里添加的代码[如果没有]:<br/>";      
	    $section_define="";
	    $section_content="";
	    foreach($tableList as $tablename){  
	        $classname=self::getClassname($tablename);             
	        if ($tableInfoList!=null&&count($tableInfoList)>0&&array_key_exists("$tablename", $tableInfoList)){
	            $table_comment=$tableInfoList[$tablename]["Comment"];
	        }else{
	            $table_comment="$classname";
	        }    
	        $classname{0} = strtolower($classname{0});  
	        $service_classname=self::getServiceClassname($tablename);      

	        $section_define .="&nbsp;&nbsp;&nbsp;&nbsp;private static \$".$classname."Service;<br/>"; 
	        
	      
	        $section_content.="&nbsp;&nbsp;&nbsp;&nbsp;/**<br/>".
	                        "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;提供服务:".$table_comment.
	                        "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*/<br/>";
	                 
	        $section_content.="&nbsp;&nbsp;&nbsp;&nbsp;public static function ".$classname."Service() {<br/>".
	                         "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if (self::\$".$classname."Service==null) {<br/>".
	                         "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;self::\$".$classname."Service=new $service_classname();<br/>".
	                         "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br/>".
	                         "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return self::\$".$classname."Service;<br/>".
	                         "&nbsp;&nbsp;&nbsp;&nbsp;}<br/>";
	    }
	    echo  $section_define.'<br/>';
	    echo  $section_content;  
	}

	/**
	 * 用户输入需求
	 */
	public static function UserInput()
	{
	    /**
	     * javascript文件夹选择框的两种解决方案,这里选择了第一种
	     * @link http://www.blogjava.net/supercrsky/archive/2008/06/17/208641.html
	     */
	    echo  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	           <html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">';
	    echo "<head>\r\n";     
	    echo UtilCss::form_css()."\r\n";
	    $url_base=UtilNet::urlbase();
	    echo "<script type='text/javascript' src='".$url_base."common/js/util/file.js'></script>";
	    echo "</head>";     
	    echo "<body>";   
	    echo "<br/><br/><br/><br/><br/><h1 align='center'>需要定义生成服务类的输出文件路径参数</h1>";
	    echo "<div align='center' height='450'>";
	    echo "<form>";  
	    echo "  <div style='line-height:1.5em;'>";
	    echo "      <label>输出文件路径:</label><input type=\"text\" name=\"save_dir\" />
	                    <input type=\"button\" onclick=\"browseFolder('save_dir')\" value=\"浏览...\" /><br/><br/>
	                <label>生成模式:</label><select name=\"type\">
	                  <option value='1'>继承具有标准方法的Service。</option><option value='2'>生成标准方法的Service。</option>
	                </select>";  
	    echo "  </div>";
	    echo "  <input type=\"submit\" value='生成' /><br/>";
	    echo "  <p id='indexPage'>说明： <br/>
	                * 可手动输入文件路径，也可选择浏览指定文件夹。<br/>
	                * 如果您希望选择指定文件夹，特别注意的是,由于安全方面的问题,你还需要如下设置才能使本JS代码正确运行,否则会出现\"没有权限\"的问题。<br/>
	                1.设置可信任站点（例如本地的可以为：http://localhost）<br/>
	                2.其次：可信任站点安全级别自定义设置中：设置下面的选项<br/>
	                \"对没有标记为安全的ActiveX控件进行初始化和脚本运行\"----\"启用\"</p>"; 
	    echo "</form>";
	    echo "</div>";
	    echo "</body>";      
	    echo "</html>";
	    return;
	}

	/**
	 * 将表列定义转换成数据对象Php文件定义的内容
	 * @param string $tablename
	 * @param array $fieldInfo 
	 */
	private static function tableToServiceDefine($tablename,$tableInfoList)
	{
	    $result="<?php\r\n";
	    $classname=self::getClassname($tablename);
	    $service_classname=self::getServiceClassname($tablename);
	    $object_desc="";
	    if ($tableInfoList!=null&&count($tableInfoList)>0&&array_key_exists("$tablename", $tableInfoList)){
	        $object_desc=$tableInfoList[$tablename]["Comment"];
	        $table_comment="服务类:".$object_desc;
	        $table_comment=str_replace("\n","\r\n * ",$table_comment); 
	        if (endWith($table_comment,"\r\n * ")){
	           $table_comment=substr($table_comment,0,strlen($table_comment)-5);
	        }                                                         
	    }else{
	        $object_desc=$classname;
	        $table_comment="关于服务类$classname的描述";
	    }    
	    $category=  Gc::$appName;
	    $author= "skygreen skygreen2001@gmail.com"; 
	    $result.="//加载初始化设置\r\n";
	    $result.="class_exists(\"Service\")||require(\"../init.php\");\r\n";       
	    $result.="/**\r\n".
				 " +---------------------------------------<br/>\r\n".
				 " * $table_comment<br/>\r\n".
				 " +---------------------------------------\r\n".
				 " * @category $category\r\n".
				 " * @package services\r\n".
				 " * @author $author\r\n".
				 " */\r\n";	 
	    switch (self::$type) {
	        case 2:     
	            $result.="class $service_classname extends Service implements IServiceBasic \r\n{\r\n"; 
	            $instance_name=$classname;
	            $instance_name{0} = strtolower($instance_name{0});    
	            //save          
	            $result.="    /**\r\n".
	                     "     * 保存数据对象:$object_desc\r\n".
	                     "     * @param array|DataObject \$$instance_name\r\n".
	                     "     * @return int 保存对象记录的ID标识号\r\n".
	                     "     */\r\n";
	            $result.="    public public static function save(\$$instance_name)\r\n".
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
	                     "     * 更新数据对象 :$object_desc\r\n".  
	                     "     * @param array|DataObject \$$instance_name\r\n".
	                     "     * @return boolen 是否更新成功；true为操作正常\r\n".
	                     "     */\r\n";
	            $result.="    public public static function update(\$$instance_name)\r\n".
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
	                     "     * 由标识删除指定ID数据对象 :$object_desc\r\n".  
	                     "     * @param int \$id 数据对象:$object_desc标识\r\n".
	                     "     * @return boolen 是否删除成功；true为操作正常\r\n".
	                     "     */\r\n";
	            $result.="    public public static function deleteByID(\$id)\r\n".
	                     "    {\r\n". 
	                     "        return $classname::deleteByID(\$id);\r\n".
	                     "    }\r\n\r\n";   
	            //deleteByIds         
	            $result.="    /**\r\n".
	                     "     * 根据主键删除数据对象:$object_desc的多条数据记录\r\n".  
	                     "     * @param array|string \$ids 数据对象编号\r\n".  
	                     "     * 形式如下:\r\n".
	                     "     * 1.array:array(1,2,3,4,5)\r\n".
	                     "     * 2.字符串:1,2,3,4 \r\n".  
	                     "     * @return boolen 是否删除成功；true为操作正常\r\n".
	                     "     */\r\n";
	            $result.="    public public static function deleteByIds(\$ids)\r\n".
	                     "    {\r\n". 
	                     "        return $classname::deleteByIds(\$ids);\r\n".
	                     "    }\r\n\r\n";            
	            //increment         
	            $result.="    /**\r\n".
	                     "     * 对数据对象:$object_desc的属性进行递增\r\n".  
	                     "     * @param string \$filter 查询条件，在where后的条件<br/> \r\n".  
	                     "     * 示例如下：<br/>\r\n".
	                     "     * 0.\"id=1,name='sky'\"<br/>\r\n".
	                     "     * 1.array(\"id=1\",\"name='sky'\")<br/> \r\n".  
	                     "     * 2.array(\"id\"=>\"1\",\"name\"=>\"sky\")<br/>\r\n".
	                     "     * 3.允许对象如new User(id=\"1\",name=\"green\");<br/>\r\n".
	                     "     * @param string property_name 属性名称 \r\n".  
	                     "     * @param int incre_value 递增数 \r\n". 
	                     "     * @return boolen 是否操作成功；true为操作正常\r\n".
	                     "     */\r\n";
	            $result.="    public public static function increment(\$filter=null,\$property_name,\$incre_value)\r\n".
	                     "    {\r\n". 
	                     "        return $classname::increment(\$filter,\$property_name,\$incre_value);\r\n".
	                     "    }\r\n\r\n"; 
	            //decrement         
	            $result.="    /**\r\n".
	                     "     * 对数据对象:$object_desc的属性进行递减\r\n".  
	                     "     * @param string \$filter 查询条件，在where后的条件<br/> \r\n".  
	                     "     * 示例如下：<br/>\r\n".
	                     "     * 0.\"id=1,name='sky'\"<br/>\r\n".
	                     "     * 1.array(\"id=1\",\"name='sky'\")<br/> \r\n".  
	                     "     * 2.array(\"id\"=>\"1\",\"name\"=>\"sky\")<br/>\r\n".
	                     "     * 3.允许对象如new User(id=\"1\",name=\"green\");<br/>\r\n".
	                     "     * @param string property_name 属性名称 \r\n".  
	                     "     * @param int decre_value 递减数 \r\n". 
	                     "     * @return boolen 是否操作成功；true为操作正常\r\n".
	                     "     */\r\n";
	            $result.="    public public static function decrement(\$filter=null,\$property_name,\$decre_value)\r\n".
	                     "    {\r\n". 
	                     "        return $classname::decrement(\$filter,\$property_name,\$decre_value);\r\n".
	                     "    }\r\n\r\n"; 
	            //select         
	            $result.="    /**\r\n".
	                     "     * 查询数据对象:$object_desc需显示属性的列表\r\n".  
	                     "     * @param string 指定的显示属性，同SQL语句中的Select部分。 \r\n".  
	                     "     * 示例如下：<br/>\r\n".
	                     "     *    id,name,commitTime\r\n". 
	                     "     * @param string \$filter 查询条件，在where后的条件<br/> \r\n".  
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
	                     "     * @return 数据对象:$object_desc列表数组\r\n".
	                     "     */\r\n";
	            $result.="    public public static function select(\$columns,\$filter=null,\$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID,\$limit=null)\r\n".
	                     "    {\r\n". 
	                     "        return $classname::select(\$columns,\$filter,\$sort,\$limit);\r\n".
	                     "    }\r\n\r\n";   
	            //get         
	            $result.="    /**\r\n".
	                     "     * 查询数据对象:$object_desc的列表\r\n".       
	                     "     * @param string \$filter 查询条件，在where后的条件<br/> \r\n".  
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
	                     "     * @return 数据对象:$object_desc列表数组\r\n".
	                     "     */\r\n";
	            $result.="    public public static function get(\$filter=null,\$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID,\$limit=null)\r\n".
	                     "    {\r\n". 
	                     "        return $classname::get(\$filter,\$sort,\$limit);\r\n".
	                     "    }\r\n\r\n";   
	            //get_one         
	            $result.="    /**\r\n".
	                     "     * 查询得到单个数据对象:$object_desc实体\r\n".       
	                     "     * @param string \$filter 查询条件，在where后的条件<br/> \r\n".  
	                     "     * 示例如下：<br/>\r\n".
	                     "     * 0.\"id=1,name='sky'\"<br/>\r\n".
	                     "     * 1.array(\"id=1\",\"name='sky'\")<br/> \r\n".  
	                     "     * 2.array(\"id\"=>\"1\",\"name\"=>\"sky\")<br/>\r\n".
	                     "     * 3.允许对象如new User(id=\"1\",name=\"green\");<br/>\r\n".
	                     "     * 默认:SQL Where条件子语句。如：\"(id=1 and name='sky') or (name like 'sky')\"<br/> \r\n". 
	                     "     * @return 单个数据对象:$object_desc实体\r\n".
	                     "     */\r\n";
	            $result.="    public public static function get_one(\$filter=null)\r\n".
	                     "    {\r\n". 
	                     "        return $classname::get_one(\$filter);\r\n".
	                     "    }\r\n\r\n";   
	            //get_by_id         
	            $result.="    /**\r\n".
	                     "     * 根据表ID主键获取指定的对象[ID对应的表列] \r\n".       
	                     "     * @param string \$id  \r\n".  
	                     "     * @return 单个数据对象:$object_desc实体\r\n".
	                     "     */\r\n";
	            $result.="    public public static function get_by_id(\$id)\r\n".
	                     "    {\r\n". 
	                     "        return $classname::get_by_id(\$id);\r\n".
	                     "    }\r\n\r\n"; 
	            //count         
	            $result.="    /**\r\n".
	                     "     * 数据对象:$object_desc总计数\r\n".       
	                     "     * @param string \$filter 查询条件，在where后的条件<br/> \r\n".  
	                     "     * 示例如下：<br/>\r\n".
	                     "     * 0.\"id=1,name='sky'\"<br/>\r\n".
	                     "     * 1.array(\"id=1\",\"name='sky'\")<br/> \r\n".  
	                     "     * 2.array(\"id\"=>\"1\",\"name\"=>\"sky\")<br/>\r\n".
	                     "     * 3.允许对象如new User(id=\"1\",name=\"green\");<br/>\r\n".
	                     "     * 默认:SQL Where条件子语句。如：\"(id=1 and name='sky') or (name like 'sky')\"<br/> \r\n". 
	                     "     * @return 数据对象:$object_desc总计数\r\n".
	                     "     */\r\n";
	            $result.="    public public static function count(\$filter=null)\r\n".
	                     "    {\r\n". 
	                     "        return $classname::count(\$filter);\r\n".
	                     "    }\r\n\r\n";   
	            //queryPage         
	            $result.="    /**\r\n".
	                     "     * 数据对象:$object_desc分页查询\r\n". 
	                     "     * @param int $startPoint  分页开始记录数\r\n". 
	                     "     * @param int $endPoint    分页结束记录数r\n".                                            
	                     "     * @param string \$filter 查询条件，在where后的条件<br/> \r\n".  
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
	                     "     * @return 数据对象:$object_desc分页查询列表\r\n".
	                     "     */\r\n";
	            $result.="    public public static function queryPage(\$startPoint,\$endPoint,\$filter=null,\$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID)\r\n".
	                     "    {\r\n". 
	                     "        return $classname::queryPage(\$startPoint,\$endPoint,\$filter,\$sort);\r\n".
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
	 * 从表名称获取对象的类名。
	 * @param string $tablename
	 * @return string 返回对象的类名 
	 */
	private static function getClassname($tablename)
	{
	    $classnameSplit= explode("_", $tablename);
	    $classname=ucfirst($classnameSplit[count($classnameSplit)-1]);   
	    return $classname;
	} 
	 
	/**
	 * 从表名称获取服务的类名。
	 * @param string $tablename
	 * @return string 返回对象的类名 
	 */
	private static function getServiceClassname($tablename)
	{
	    $classnameSplit= explode("_", $tablename);
	    $classname='Service'.ucfirst($classnameSplit[count($classnameSplit)-1]);   
	    return $classname;
	}
	   
	/**
	 * 保存生成的代码到指定命名规范的文件中 
	 * @param string $dir
	 * @param string $definePhpFileContent 
	 */
	private static function saveServiceDefineToDir($dir,$tablename,$definePhpFileContent)
	{ 
	    $filename=self::getServiceClassname($tablename).".php";      
	    $dir=$dir.DIRECTORY_SEPARATOR.$package;
	    return self::saveDefineToDir($dir,$filename,$definePhpFileContent);
	}
}

?>