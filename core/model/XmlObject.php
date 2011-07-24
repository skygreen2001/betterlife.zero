<?php
/**
 +-----------------------------------------<br/>
 * 所有Xml格式数据实体类的父类<br/>
 +-----------------------------------------<br/>
 */
class XmlObject extends Object implements ArrayAccess
{        
    /**
    * 库的唯一标识:库的名称
    * @var string
    */
    protected $id; 
    /**
     * @var int 记录创建的时间timestamp 
     */
    protected $commitTime;
    /**
     * @var int 记录最后更新的时间，当表中无该字段时，一般用commitTime记录最后更新的时间。
     */
    protected $updateTime; 
    
    private static $name_id_property="id"; 

    //<editor-fold defaultstate="collapsed" desc="默认列Setter和Getter">     
    /**
     * 设置唯一标识
     * @param mixed $id 
     */
    public function setId($id) 
    { 
        $this->id=$id;
    }
    
    /**
     * 获取唯一标识
     * @return mixed
     */
    public function getId() 
    {        
        return $this->id;
    }
    
    /**
     * 设置数据创建的时间
     * @param mixed $commitTime 
     */
    public function setCommitTime($commitTime) 
    {
        $this->commitTime=$commitTime;   
    }

    /**
     * 获取数据创建的时间
     * @return mixed 
     */
    public function getCommitTime() 
    {
        return $this->commitTime;
    }
    
    /**
     * 设置数据最后更新的时间
     * @param mixed $updateTime 
     */
    public function setUpdateTime($updateTime) 
    {
        $this->updateTime=$updateTime;   
    }

    /**
     * 获取数据最后更新的时间
     * @return mixed 
     */
    public function getUpdateTime() 
    {
        return $this->updateTime;        
    }  
    //</editor-fold>
   

    //<editor-fold defaultstate="collapsed" desc="魔术方法">
    /**
     * 可设定对象未定义的成员变量[但不建议这样做]<br/>
     * 类定义变量访问权限设定需要是pulbic
     * @param mixed $property 属性名
     * @return mixed 属性值
     */
    public function __get($property) 
    {
        if (method_exists($this, "get".ucfirst($property))) {
            $methodname="get".ucfirst($property);
            return $this->{$methodname}();
        }else {
            if (!property_exists($dataobject,$property)) {
                return @$this->{$property};
            }
        }
    }

    /**
     * 可设定对象未定义的成员变量[但不建议这样做]<br/>
     * 类定义变量访问权限设定需要是pulbic
     * @param mixed $property 属性名
     * @param mixed $value 属性值
     */
    public function __set($property, $value) 
    {
        if (method_exists($this, "set".ucfirst($property))) {
            $methodname="set".ucfirst($property);
            $this->{$methodname}($value);
        }else {
            if (!property_exists($this,$property)) {
                $this->{$property}=$value;
            }
        }
    } 
    //</editor-fold>
    
    //<editor-fold defaultstate="collapsed" desc="定义数组进入对象方式">
    public function offsetExists($key) 
    {
        $method="get".ucfirst($key);
        return method_exists($this,$method);
    }
    public function offsetGet($key) 
    {
        $method="get".ucfirst($key);
        return $this->$method();
    }
    public function offsetSet($key, $value) 
    {
        $method="set".ucfirst($key);
        $this->$method($value);
//        $this->$key = $value;
    }
    public function offsetUnset($key) 
    {
        unset($this->$key);
    }
    //</editor-fold>
    
    /**
     * Xml格式存储的文件路径地址
     */
    public static function address()
    {
        return Gc::$nav_root_path.basename(__FILE__, Config_F::SUFFIX_FILE_PHP).Config_F::SUFFIX_FILE_XML;  
    }
    
    /**
     * 获取所有Xml对象的信息
     * @param string $xmlObject_classname 具体的Xml对象类名
     * @param string $filter 查询条件，在where后的条件<br/>
     * 示例如下：<br/>
     *      0."id=1,name='sky'"<br/>
     *      1.array("id=1","name='sky'")<br/>
     *      2.array("id"=>"1","name"=>"sky")<br/>
     *      3.允许对象如new User(id="1",name="green");<br/>
     */
    public static function get($xmlObject_classname,$filter=null)
    {
        if ($xmlObject_classname==null){
            $classname=get_called_class();
        }else{
            $classname=$xmlObject_classname;
        }
        $filename=call_user_func("$classname::address");
        $spec_library=UtilXmlSimple::fileXmlToArray($filename);
        $result=array();             
        $classname{0} = strtolower($classname{0});
        foreach ($spec_library[$classname] as $block)
        {
            $blockAttr=$block[Util::XML_ELEMENT_ATTRIBUTES];        
            $result[]=$blockAttr;
        }
        return $result;
    }    

    /**
     * Xml数据对象总计数
     * @param object|string|array $filter<br/>
     *      $filter 格式示例如下：<br/>
     *          0.允许对象如new User(id="1",name="green");<br/>
     *          1."id=1","name='sky'"<br/>
     *          2.array("id=1","name='sky'")<br/>
     *          3.array("id"=>"1","name"=>"sky")
     * @return 对象总计数
     */
    public static function count($filter=null) 
    {
        $result=0;
        if ($xmlObject_classname==null){
            $classname=get_called_class();
        }else{
            $classname=$xmlObject_classname;
        }
        $filename=call_user_func("$classname::address");
        $spec_library=UtilXmlSimple::fileXmlToArray($filename);
        if (($spec_library!=null)&&(count($spec_library))>0){
            foreach ($spec_library as $dataobjets){
                $result=count($dataobjets);
            }
        }
        return $result;
    }
    
    /**
     * Xml对象分页
     * @param string $xmlObject_classname 具体的Xml对象类名
     * @param int $startPoint  分页开始记录数
     * @param int $endPoint    分页结束记录数 
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下：<br/>
     *      0."id=1,name='sky'"<br/>
     *      1.array("id=1","name='sky'")<br/>
     *      2.array("id"=>"1","name"=>"sky")<br/>
     *      3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
     * @return mixed 对象分页
     */
    public static function queryPage($xmlObject_classname,$startPoint,$endPoint,$filter=null) 
    {
        if ($xmlObject_classname==null){
            $classname=get_called_class();
        }else{
            $classname=$xmlObject_classname;
        }
        $filename=call_user_func("$classname::address");
        $spec_library=UtilXmlSimple::fileXmlToArray($filename);
        $result=array();             
        $classname{0} = strtolower($classname{0});
        foreach ($spec_library[$classname] as $block)
        {
            $blockAttr=$block[Util::XML_ELEMENT_ATTRIBUTES];        
            $result[]=$blockAttr;
        }
        $result=array_slice($result, $startPoint, $endPoint); 
        return $result;
    }    
    
    /**
     * 保存Xml对象的信息
     */
    public function save()
    {
        $this->commitTime=UtilDateTime::now();   
        $data=UtilObject::object_to_array($this);   
        $classname=$this->classname();       
        $filename=call_user_func("$classname::address");
        $xml=UtilXmlSimple::fileXmlToObject($filename);        
        $classname{0} = strtolower($classname{0});
        $child=$xml->addChild($classname);//取该对象的类名作为节点名，头字母转化为小写
        $this->id=UtilDateTime::dateToTimestamp();
        $child->addAttribute(self::$name_id_property, $this->id);     
        foreach($data as $key=>$value) {
            if ($value!=null&&!endWith($key,"Show")){
                $child->addAttribute($key, $value);
            }
        }
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        $dom->save($filename);        
        //$xml->asXML($filename);
        return $this;
    }
    
    /**
     * 更新Xml对象的信息
     */
    public function update()
    {
        $this->updateTime=UtilDateTime::now(); 
        $data=UtilObject::object_to_array($this);
        unset ($data[self::$name_id_property]);
        $node=$this->getId();
        $classname=$this->classname(); 
        $filename=call_user_func("$classname::address");
        $xml=UtilXmlSimple::fileXmlToObject($filename);      
        $classname{0} = strtolower($classname{0});
        $xml_child=$xml->xpath("//$classname"."[@".self::$name_id_property."=$node]");
        if ($xml_child)
        {
            $xml_attributes=$xml_child[0];
            if ($xml_child){            
                $attributes=$xml_attributes->attributes();                   if ($attributes){
                    $arrObjData = get_object_vars($attributes);
                    $arrObjData=end($arrObjData);
                    foreach ($arrObjData as $key => $value) {
                        $methodName="set".  ucfirst($key);
                        if (method_exists($this, $methodName)){                        
                            $this->$methodName($value);
                        }
                    }  
                }
            }  
        }   
        foreach($data as $key=>$value) {
            if (property_exists($attributes,$key)){
                $attributes->$key=$value;                
            }else{
                if ($value!=null&&!endWith($key,"Show")){
                    if ($attributes){
                        $attributes->addAttribute($key, $value);
                    }
                }
            }
            $this->$key=$value;
        }
        $xml->asXML($filename);   
        return $this;
    }
        
    /**
     * 删除Xml对象的信息
     */
    public function  delete()
    {
        $node=$this->getId();
        $classname=$this->classname();      
        $filename=call_user_func("$classname::address");
        $xml=UtilXmlSimple::fileXmlToObject($filename);         
        $classname{0} = strtolower($classname{0});
        $xml_child=$xml->xpath("//$classname"."[@".self::$name_id_property."=$node]");
        foreach( $xml_child  as $el){
            if($el[self::$name_id_property]==$node)
            {
                $domRef = dom_import_simplexml($el); 
                $domRef->parentNode->removeChild($domRef);
            }
        }                
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        $dom->save($filename);
        return true;
    }
    
    /**
     * 将XML对象转换成Array数组
     * @param $isAll 是否对象所有的field都要生成，包括没有内容或者内容为空的field
     * @return 数组
     */
    public function toArray($isAll=true)
    {
       return UtilObject::object_to_array($this,$isAll);        
    }
}
?>
