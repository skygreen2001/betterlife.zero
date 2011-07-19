<?php
/**
 +---------------------------------------<br/>
 * 基于对象的增删改查XML节点数据项<br/>
 * 规则说明如下<br/>
 * 0.所有元素名为小写<br/>
 * 1.节点根名称为 类名+s：如Role为roles<br/>
 * 2.每一个节点为:类名(如role)<br/>
 * 3.节点下名为类的属性<br/>
 * 4.可有全局配置项，目录名为global(可选<br/>
 * 示例：<br/>
 *  <roles><br/>
 *    <global><br/>
 *      <dir_path>F:\testshare\</dir_path><br/>
 *    </global><br/>
 *    <role><br/>
 *      <id>1</id><br/>
 *      <name>管理者</name><br/>
 *      <sub_dir></sub_dir><br/>
 *    </role><br/>
 *  <roles><br/>
 +---------------------------------------<br/>
 * @category betterlife
 * @package util.xml
 * @author skygreen
 */
class UtilXmlObject extends Util
{
    private $global;
    private $xml;
    private $filename;
    /**
     * 初始化指定的Xml文件对象进行操作
     * @var string $filename :针对文件根目录
     * @global array 初始化全局配置项
     */
    public function __construct($filename,$global=null) 
    {
        $this->global=$global;
        $this->filename=$filename;
        if(is_file($filename))
            $this->xml=$this->load($filename);
    }
    
    /**
     * 当该Xml文件不存在的时候创建XML对象
     */
    public function createXmlObject($object) 
    {
        $node=strtolower(get_class($object));
        $head="<?xml version=\"1.0\" encoding=\"".Gc::$encoding."?>";
        $this->xml=new SimpleXMLElement($this->head."<".$node."s><global/></".$node."s>");
        if($this->global!=null) {
            $globalNodes=UtilObject::object_to_array($this->global);
            foreach($globalNodes as $key=>$value) {
                $this->xml->global->addChild($key,$value);
            }
        }
        $child=$this->xml->addChild($node);
        $data=UtilObject::object_to_array($object);
        foreach($data as $key=>$value) {
            $child->addChild($key,$value);
        }
        $this->xml->asXML($this->filename);
    }

    /**
     * 创建一个对象节点
     */
    public function save($object) 
    {
        if(is_file($this->filename)) {
            $data=UtilObject::object_to_array($object);
            $child=$this->xml->addChild(strtolower(get_class($object)));//取该对象的类名作为节点名，并转化为小写
            foreach($data as $key=>$value) {
                $child->addChild($key, $value);
            }
        }else {
            $this->createXmlObject($object);
        }
        $this->xml->asXML($this->filename);
    }

    /**
     * 更新一个对象节点
     */
    public function update($object) 
    {
        $data=UtilObject::object_to_array($object);
        $node=strtolower(get_class($object));
        $xml_child=null;
        foreach($this->xml->xpath("//".$node) as $child)//取该对象的类名作为节点名，查询所有该节点
        {
            if($object->getId()==$child->id) {
                $xml_child=$child;
            }
        }
        foreach($data as $key=>$value) {
            $xml_child->$key=$value;
        }
        $this->xml->asXML($this->filename);
    }

    /**
     * 删除一个对象节点
     */
    public function delete($object) 
    {
    }

    /**
     * 根据条件获取一个对象节点
     * $where array 查询条件值
     * array("id"=>"1","name"=>"sky")
     */
    public function get($where) 
    {
        $datalist=new DataObjectList();
        $datalist=$this->getAll();
        for($i=$datalist->count()-1;$i>=0;$i--) {
            $isFilter=false;
            foreach ($where as $akey => $aval) {
                if (method_exists($datalist[$i], 'set'.ucfirst($akey))) {
                    if($datalist[$i]->{'get'.ucfirst($akey)}()==$aval) {
                        continue;
                    }
                    else {
                        $isFilter=true;
                        break;
                    }
                }
            }
            if($isFilter) {
                $datalist->remove($i);
            }
        }
        return $datalist;
    }

    /**
     * 获取所有的对象节点
     */
    public function getAll() 
    {
        $datalist=new DataObjectList();
        $nodeName=$this->xml->getName();
        $children=$this->xml->xpath("//".substr($nodeName,0,strlen($nodeName)-1));
        foreach($children as $child) {
            $class=new ReflectionClass(ucfirst($child->getName()));
            $data=$class->newInstance();
            foreach ($child as $akey => $aval) {
                if (method_exists($data, 'set'.ucfirst($akey))&&(array)$aval!=null) {
                    $dataArr=(array)$aval;//将SimpleXMLElement数组转换成普通array,否则$aval[0]取出的是SimpleXMLElement对象
                    $data->{'set'.ucfirst($akey)}($dataArr[0]);
                }

            }
            $datalist->add($data);
        }
        return $datalist;
    }
    /*
     * 根据节点名取出global节点的值
    */
    public function getGlobal($nodeName) 
    {
        return $this->xml->global->$nodeName;
    }

    /*
     * 新增一个Global节点
    */
    public function saveGlobal($nodeName,$value) 
    {
        $this->xml->global->addChild($nodeName,$value);
    }

    /*
     * 更新一个Global节点
    */
    public function updateGlobal($nodeName,$value) 
    {
        $this->xml->global->$nodeName=$value;
        $this->xml->asXML($this->filename);
    }

    /**
     * 加载xml文件
     */
    private function load($filename) 
    {
        return simplexml_load_file($filename);
    }
    
}
?>
