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
class ResourceLibrary extends XmlObject
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
        return Gc::$nav_root_path.Config_F::ROOT_LIBRARY.DIRECTORY_SEPARATOR.Library_Loader::FILE_SPEC_LOAD_LIBRARY;  
    }
    
    /**
    * 获取所有资料库的信息
    * 
    */
    public static function get()
    {
        $result= parent::get(__CLASS__);
        for ($i=0;$i<count($result);$i++) {
            if (!array_key_exists(Library_Loader::SPEC_REQUIRED,$result[$i]))
            {
                $result[$i][Library_Loader::SPEC_REQUIRED]=false;
            }else{
                if ($result[$i][Library_Loader::SPEC_REQUIRED]=='true'){
                    $result[$i][Library_Loader::SPEC_OPEN]=Library_Loader::OPEN_YES;
                    $result[$i][Library_Loader::SPEC_REQUIRED]=true;
                }else{
                    $result[$i][Library_Loader::SPEC_REQUIRED]=false;
                }
            }      
        }
        return $result;
    }

    /**
    * 分页:获取资料库的信息列表
    * @param int $startPoint  分页开始记录数
    * @param int $endPoint    分页结束记录数 
    */
    public static function queryPage($startPoint,$endPoint,$filter=null) 
    {
        $result= parent::queryPage(__CLASS__,$startPoint,$endPoint);
        foreach ($result as $key=>$value) {
            if (!array_key_exists(Library_Loader::SPEC_REQUIRED,$result[$key]))
            {
                $result[$key][Library_Loader::SPEC_REQUIRED]=false;
            }else{
                if ($result[$key][Library_Loader::SPEC_REQUIRED]=='true'){
                    $result[$key][Library_Loader::SPEC_OPEN]=Library_Loader::OPEN_YES;
                    $result[$key][Library_Loader::SPEC_REQUIRED]=true;
                }else{
                    $result[$key][Library_Loader::SPEC_REQUIRED]=false;
                }
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
            $this->open=Library_Loader::OPEN_YES;
        }      
        return parent::save();
    }

    /**
     * 更新资料库的信息
     */
    public function update()
    {
        if (isset($this->required)){
            if ($this->required==true){
                $this->required='true';
                $this->open=Library_Loader::OPEN_YES;
            }else{
                $this->required='false';
            }       
        } 
        if (isset($this->open)){        
            if ($this->open==true){
                $this->open=Library_Loader::OPEN_YES;
            }else{
                $this->open=Library_Loader::OPEN_NO;
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
