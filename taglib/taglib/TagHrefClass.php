<?php
/**
 * 自定义标签:超链接
 * @link http://www.w3schools.com/tags/tag_a.asp
 * @author skygreen <skygreen2001@gmail.com>
 */
class TagHrefClass extends TagClass
{
    /**
     * 是否加密
     * @var bool
     */
    public static $isMcrypt=false;

    public function setHtml()
    {
        $attributes=TagClass::getAttributesFormTag($this->getAttributeDesc());

        $this->html="<a ";
        if ($attributes && (count($attributes)>0)){

            if ($attributes["href"]){
                $href=$attributes["href"];
                if (self::$isMcrypt){
                    if (contain($href,Gc::$url_base."index.php?")){
                        $params=str_replace(Gc::$url_base."index.php?","",$href);
                        $crypttext = base64_encode($params);
                        $href=Gc::$url_base."index.php?".$crypttext;
                    }
                }
                $this->html.="href='".$href."' ";
            }

            foreach ($attributes as $key => $value) {
                $this->{$key}=$value;
                $this->html.="{$key}='".$value."' ";
            }
        }

        $this->html.=">".$this->getContent()."</a>";
    }

}
?>
