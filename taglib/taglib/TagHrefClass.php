<?php             
/**
 * 自定义标签:超链接
 * @link http://www.w3schools.com/tags/tag_a.asp
 * @author zyp
 */
class TagHrefClass extends TagClass 
{
    /**
     * 是否加密
     * @var bool
     */
    public static $isMcrypt=false;
    /**
     * 链接地址
     * @var string
     */
    private $href; 
    /**
     * 在哪里打开链接地址的页面
     * 同超链接a的target
     * @var string
     */
    private $target;
    /**
     * 样式
     * @var string
     */
    private $style;
    /**
     * CSS 类名
     * @var string
     */
    private $class;
    /**
     * 标题     
     * @var string
     */
    private $title;
    /**
    * 点击事件
    * @var string
    */
    private $onclick;    
    /**
     * 鼠标移上去的事件
     * @var string
     */
    private $onmouseover; 
    /**
     * 鼠标移开的事件
     * @var string
     */
    private $onmouseout; 
    
    public function setHtml() 
    {
        $attributes=TagClass::getAttributesFormTag($this->getAttributeDesc());
        if (array_key_exists("href",$attributes)){
            $this->href=$attributes["href"];  
        }        
        if (array_key_exists("target",$attributes)){
            $this->target=$attributes["target"];  
        }       
        if (array_key_exists("style",$attributes)){
            $this->style=$attributes["style"];  
        }        
        if (array_key_exists("class",$attributes)){
            $this->class=$attributes["class"];  
        }                  
        if (array_key_exists("title",$attributes)){
            $this->title=$attributes["title"];  
        }                 
        if (array_key_exists("onclick",$attributes)){
            $this->onclick=$attributes["onclick"];  
        }                  
        if (array_key_exists("onmouseover",$attributes)){
            $this->onmouseover=$attributes["onmouseover"];  
        }                        
        if (array_key_exists("onmouseout",$attributes)){
            $this->onmouseout=$attributes["onmouseout"];  
        }     
        $this->html="<a ";
        if ($this->href){
            $href=$this->href; 
            if (self::$isMcrypt){
                if (contain($this->href,Gc::$url_base."index.php?")){      
                    $params=str_replace(Gc::$url_base."index.php?","",$this->href);  
                    $crypttext = base64_encode($params);   
                    $href=Gc::$url_base."index.php?".$crypttext;
                }    
            }
            $this->html.="href=\"".$href."\" ";
        }
        if ($this->target){
            $this->html.="target=\"".$this->target."\" ";    
        }        
        
        if ($this->style){
            $this->html.="style=\"".$this->style."\" ";
        }
        if ($this->class){
            $this->html.="class=\"".$this->class."\" ";    
        }
        if ($this->title){
            $this->html.="title=\"".$this->title."\" ";
        }
        if ($this->onclick){
            $this->html.="onclick=\"".$this->onclick."\" ";    
        }
        if ($this->onmouseover){
            $this->html.="onmouseover=\"".$this->onmouseover."\" ";
        }
        if ($this->onmouseout){
            $this->html.="onmouseout=\"".$this->onmouseout."\" ";    
        }
        
        $this->html.=">".$this->getContent()."</a>";
    }
    
}
?>
