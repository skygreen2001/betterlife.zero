<?php
/**
* 通用的方法服务类。
* 继承本基本服务方法类要求遵循命名规范
*     服务类名=Service+[DataObject名称]
*     如用户服务ServiceUser=Service+User
* 通用的方法包括如下：
*     数据对象的增删改
*     根据标识ID获取数据对象
*     根据过滤条件获取数据对象
*     根据数据对象的计数
*     分页获取数据对象
*     对属性进行递增递减            
* @category betterlife  
* @package core.model
* @subpackage service
* @author skygreen 
*/              
class ServiceBasic extends Service implements IServiceBasic
{  
    /**
     +--------------------------------------------------<br/>
     * 服务类和数据对象类的STD映射<br/>      
     * 默认命名规则:
     *     服务类=Service+数据对象类命名                              
     * 示例如下：<br/>     
     *     数据对象类  :Task<br/>    
     *     服务类      :ServiceTask<br/>
     * 如果未按命名规范定义定义服务类，则在此处定义Service To DataObject的映射(简称STD)
     * 服务类需放置在services目录下<br/>      
     * 数据库实体POJO对象需放置在domain目录下<br/>  
     +--------------------------------------------------<br/>
     * @var array
     * @static
     */    
    private static $std=array(
      "ServiceStorage"=>'Order'  
    );
    
    /**
     * 保存数据对象                    
     * @param array|DataObject $dataobject
     * @return int 保存对象记录的ID标识号
     */
    public function save($dataobject) 
    {           
        $dataobject_class=self::std($this->classname());  
        if (class_exists($dataobject_class)){   
            if (is_array($dataobject)){        
                $dataobject=new $dataobject_class($dataobject); 
            }
            if ($dataobject instanceof DataObject){
                return $dataobject->save();
            }else{
                return false;
            }
        }else{
             LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
             return false;
        }
    }

    /**
     * 更新数据对象                                  
     * @param array|DataObject $dataobject
     * @return boolen 是否更新成功；true为操作正常
     */
    public function update($dataobject)
    {
        $dataobject_class=self::std($this->classname());  
        if (class_exists($dataobject_class)){         
            if (is_array($dataobject)){ 
                $dataobject=new $dataobject_class($dataobject); 
            }                                               
            if ($dataobject instanceof DataObject){ 
                return $dataobject->update();
            }else{
                return false;
            }
        }else{
             LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
             return false;
        } 
    }
    
    /**
    * 由标识删除指定ID数据对象   
    * @param int $id 数据对象标识 
    * @return boolen 是否删除成功；true为操作正常\r\n".
    */
    public function deleteByID($id)
    {
        $dataobject_class=self::std($this->classname()); 
        if (class_exists($dataobject_class)){      
            return call_user_func($dataobject_class."::deleteByID",$id);     
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return false;
        }
    } 

   /**
    * 根据主键删除多条记录                  
    * @param array|string $ids 数据对象编号
    *  形式如下:
    *  1.array:array(1,2,3,4,5)
    *  2.字符串:1,2,3,4
    * @return boolen 是否删除成功；true为操作正常
    */
    public function deleteByIds($ids)
    {
        $dataobject_class=self::std($this->classname()); 
        if (class_exists($dataobject_class)){      
            return call_user_func($dataobject_class."::deleteByIds",$ids);     
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return false;
        }                                                             
    } 
    
    /**
    * 对属性进行递增
    * @param string $filter 查询条件，在where后的条件<br/>
    * 示例如下：<br/>
    *      0."id=1,name='sky'"<br/>
    *      1.array("id=1","name='sky'")<br/>
    *      2.array("id"=>"1","name"=>"sky")<br/>
    *      3.允许对象如new User(id="1",name="green");<br/>      
    * @param string $property_name 属性名称
    * @param int $incre_value 递增数  
    * @return boolen 是否操作成功；true为操作正常    
    */
    public function increment($filter=null,$property_name,$incre_value)
    {            
        $dataobject_class=self::std($this->classname()); 
        if (class_exists($dataobject_class)){      
            return call_user_func_array($dataobject_class."::increment",array($filter,$property_name,$incre_value));     
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return false;
        }   
    }
    
    /**
    * 对属性进行递减    
    * @param string $filter 查询条件，在where后的条件<br/>
    * 示例如下：<br/>
    *      0."id=1,name='sky'"<br/>
    *      1.array("id=1","name='sky'")<br/>
    *      2.array("id"=>"1","name"=>"sky")<br/>
    *      3.允许对象如new User(id="1",name="green");<br/>   
    * @param string $property_name 属性名称
    * @param int $decre_value 递减数
    * @return boolen 是否操作成功；true为操作正常
    */
    public function decrement($filter=null,$property_name,$decre_value)
    {
        $dataobject_class=self::std($this->classname()); 
        if (class_exists($dataobject_class)){      
            return call_user_func_array($dataobject_class."::decrement",array($filter,$property_name,$decre_value));     
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return false;
        }                                                                                                   
    }    
    
    /**
    * 查询当前对象需显示属性的列表  
    * @param string $columns 指定的显示属性，同SQL语句中的Select部分。 
    * 示例如下：<br/>
    *     id,name,commitTime                                                               
    * @param mixed $filter 查询条件，在where后的条件<br/>
    * 示例如下：<br/>
    *      0."id=1,name='sky'"<br/>
    *      1.array("id=1","name='sky'")<br/>
    *      2.array("id"=>"1","name"=>"sky")<br/>
    *      3.允许对象如new User(id="1",name="green");<br/>
    * 默认:SQL Where条件子语句。如："(id=1 and name='sky') or (name like 'sky')"<br/>
    * @param string $sort 排序条件<br/>
    * 示例如下：<br/>
    *      1.id asc;<br/>
    *      2.name desc;<br/>
    * @param string $limit 分页数目:同Mysql limit语法
    * 示例如下：<br/>
    *    0,10<br/>
    * @return 对象列表数组
    */
    public function select($columns,$filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID, $limit=null)
    {        
        $dataobject_class=self::std($this->classname()); 
        if (class_exists($dataobject_class)){      
            return call_user_func_array($dataobject_class."::select",array($columns,$filter,$sort,$limit));     
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return null;
        } 
        
    }
    
    /**
     * 查询当前对象列表
     * @param string $filter 查询条件，在where后的条件<br/>
     * 示例如下：<br/>
     *      0."id=1,name='sky'"<br/>
     *      1.array("id=1","name='sky'")<br/>
     *      2.array("id"=>"1","name"=>"sky")<br/>
     *      3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如："(id=1 and name='sky') or (name like 'sky')"<br/>
     * @param string $sort 排序条件<br/>
     * 示例如下：<br/>
     *      1.id asc;<br/>
     *      2.name desc;<br/>
     * @param string $limit 分页数目:同Mysql limit语法
     * 示例如下：<br/>
     *    0,10<br/>
     * @return 对象列表数组
     */
    public function get($filter=null,$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID, $limit=null)
    {
        $dataobject_class=self::std($this->classname()); 
        if (class_exists($dataobject_class)){      
            return call_user_func_array($dataobject_class."::get",array($filter,$sort,$limit));     
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return null;
        }                                                 
    }
    
    /**
     * 查询得到单个对象实体
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下：<br/>
     *      0."id=1,name='sky'"<br/>
     *      1.array("id=1","name='sky'")<br/>
     *      2.array("id"=>"1","name"=>"sky")<br/>
     *      3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
     * @return 单个对象实体
     */
    public function get_one($filter=null)
    {
        $dataobject_class=self::std($this->classname()); 
        if (class_exists($dataobject_class)){      
            return call_user_func($dataobject_class."::get_one",$filter);     
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return null;
        }                                                         
    }

    /**
     * 根据表ID主键获取指定的对象[ID对应的表列]
     * @param string $id
     * @return 对象
     */
    public function get_by_id($id) 
    {                                                           
        $dataobject_class=self::std($this->classname()); 
        if (class_exists($dataobject_class)){      
            return call_user_func($dataobject_class."::get_by_id",$id);     
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return null;
        }          
    }

    /**
     * 对象总计数
     * @param object|string|array $filter<br/>
     *      $filter 格式示例如下：<br/>
     *          0.允许对象如new User(id="1",name="green");<br/>
     *          1."id=1","name='sky'"<br/>
     *          2.array("id=1","name='sky'")<br/>
     *          3.array("id"=>"1","name"=>"sky")
     * @return 数据对象总计数
     */
    public function count($filter=null) 
    {
        $dataobject_class=self::std($this->classname()); 
        if (class_exists($dataobject_class)){      
            return call_user_func($dataobject_class."::count",$filter);     
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return 0;
        }                                                       
    }

    /**
     * 数据对象分页
     * @param int $startPoint  分页开始记录数
     * @param int $endPoint    分页结束记录数 
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下：<br/>
     *      0."id=1,name='sky'"<br/>
     *      1.array("id=1","name='sky'")<br/>
     *      2.array("id"=>"1","name"=>"sky")<br/>
     *      3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
     * @param string $sort 排序条件<br/>
     * 默认为 id desc<br/>
     * 示例如下：<br/>
     *      1.id asc;<br/>
     *      2.name desc;
     * @return mixed 数据对象分页查询列表
     */
    public function queryPage($startPoint,$endPoint,$filter=null,$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID) 
    {
        $dataobject_class=self::std($this->classname()); 
        if (class_exists($dataobject_class)){      
            return call_user_func_array($dataobject_class."::queryPage",array($startPoint,$endPoint,$filter,$sort));     
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return null;
        }                                                      
    }      
    
    /**
    * 根据数据对象服务名称获取当前数据对象
    */
    private static function std($current_servicename)
    {          
        if (array_key_exists($current_servicename,self::$std)){
            $current_dataobjectname=self::$std[$current_servicename];
        }else{
            $current_servicename=str_replace("ExtService","",$current_servicename);
            $current_servicename=str_replace("Service_","",$current_servicename);
            $current_servicename=str_replace("Service","",$current_servicename);
            $current_dataobjectname=ucfirst($current_servicename);                     
        }                                                                      
        return $current_dataobjectname;  
    }
}
?>