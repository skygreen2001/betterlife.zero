<?php
/**
 +---------------------------------<br/>
 * 本系统第三方资源库
 +---------------------------------<br/>
 * @category betterlife
 * @package web.back.admin.doman
 * @subpackage system
 * @author skygreen
*/
class Module extends XmlObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
    * 库的名称
    * @var string
    */
    private $name;
    /**
    * 是否加载
    * @var string
    */
    private $open;
    /**
    * 初始化方法
    * @var string
    */
    private $init;
    /**
    * 是否必须加载
    * @var bool
    */
    private $required;
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="默认列Setter和Getter">
    public function setName($value)
    {
      $this->name=$value;
    }
    public function getName()
    {
      return $this->name;
    }
    public  function setOpen($value)
    {
      $this->open=$value;
    }
    public function getOpen()
    {
      return $this->open;
    }
    public  function setInit($value)
    {
      $this->init=$value;
    }
    public function getInit()
    {
        return $this->init;
    }
    public  function setRequired($required)
    {
      $this->required=$required;
    }
    public function getRequired(){
        return $this->required;
    }
    //</editor-fold>

    /**
     * Xml格式存储的文件路径地址
     */
    public static function address()
    {
        return Gc::$nav_root_path.Config_F::ROOT_MODULE.DS.Module_Loader::FILE_SPEC_LOAD_MODULE;
    }

    /**
    * 获取所有资料库的信息
    *
    */
    public static function get($filter=null)
    {
        $result= parent::get(__CLASS__);
        return $result;
    }

    /**
     * 资料库总计数
     * @param object|string|array $filter<br/>
     *      $filter 格式示例如下：<br/>
     *          0.允许对象如new User(id="1",name="green");<br/>
     *          1."id=1","name='sky'"<br/>
     *          2.array("id=1","name='sky'")<br/>
     *          3.array("id"=>"1","name"=>"sky")
     * @return 资料库总计数
     */
    public static function count($filter=null)
    {
        foreach ($filter as $key=>$value){
          if (empty($value)){
              unset($filter[$key]);
          }else{
              if ($key=='name') {
                    $condition[]="$key contain '$value'";
               } else if ($key=='init') {
                    $condition[]="$key contain '$value'";
               } else {
                    $condition[$key]=$value;
               }
          }
        }
        $result=0;
        $classname=get_called_class();
        $filename=call_user_func("$classname::address");
        $spec_library=UtilXmlSimple::fileXmlToArray($filename);
        if (($spec_library!=null)&&(count($spec_library))>0){
            $result=count($dataobjets);
        }
        return $result;
    }

    /**
    * 分页:获取资料库的信息列表
    * @param int $startPoint  分页开始记录数
    * @param int $endPoint    分页结束记录数
    * @param array $filter 过滤条件
    * 示例如下：<br/>
    *      string[只有一个查询条件]
    *      1. id="1"--精确查找
    *      2. name contain 'sky'--模糊查找
    *      array[多个查询条件]
    *      1.array("id"=>"1","name"=>"sky")<br/>--精确查找
    *      2.array("id"=>"1","name contain 'sky'")<br/>--模糊查找
    */
    public static function queryPage($startPoint,$endPoint,$filter=null)
    {
        $condition=array();
        foreach ($filter as $key=>$value){
          if (empty($value)){
              unset($filter[$key]);
          }else{
              if ($key=='name') {
                    $condition[]="$key contain '$value'";
               } else if ($key=='init') {
                    $condition[]="$key contain '$value'";
               } else if ($key==Module_Loader::SPEC_OPEN) {
                   $filter_open=($value=="true"?true:false);
//                   if ($value=="true")
//                   $filter_open=true;
//                   else $filter_open=false;
               } else {
                    $condition[$key]=$value;
               }
          }
        }
        $resourceLibs= parent::queryPage($startPoint,$endPoint,$condition,__CLASS__);
        $result=array();
        //必须加载的一定是已加载，如果必须加载的参数没有设置，则不是必须加载。
        foreach ($resourceLibs as $key=>$value) {
            if(!empty($resourceLibs[$key][Module_Loader::SPEC_OPEN])){
                 if ($resourceLibs[$key][Module_Loader::SPEC_OPEN]=="true"){
                    $resourceLibs[$key][Module_Loader::SPEC_OPEN]=true;
                 }else{
                    $resourceLibs[$key][Module_Loader::SPEC_OPEN]=false;
                 }
            }
            //过滤条件是否需要判断open字段
            if (isset($filter_open)&&($filter_open!=$resourceLibs[$key][Module_Loader::SPEC_OPEN])){
            }else{
                $result[]=$resourceLibs[$key];
            }
        }
        return $result;
    }

    /**
     * 保存资料库的信息
     */
    public function save()
    {
        if ($this->required){
            $this->open=Module_Loader::OPEN_YES;
        }
        return parent::save();
    }

    /**
     * 更新资料库的信息
     */
    public function update()
    {
        if (isset($this->open)){
            if ($this->open==true){
                $this->open=Module_Loader::OPEN_YES;
            }else{
                $this->open=Module_Loader::OPEN_NO;
            }
        }
        $result=parent::update();
        if ($result->required==null){
            $result->required=false;
        }
        return $result;
    }

    /**
     * 删除资料库的信息
     */
    public function delete()
    {
        return parent::delete();
    }
}
?>
