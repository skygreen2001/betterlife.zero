<?php
/**
 * The Parent Class of all tags
 *
 * @author skygreen <skygreen2001@gmail.com>
 */
abstract class TagClass {
    const PREFIX="my";// tag prefix name
    protected $tagname;
    protected $attributesDesc;
    protected $content;
    protected $html;

    function __construct($tagname,$attributesDesc,$content=null){
        $this->tagname=$tagname;
        $this->attributesDesc=$attributesDesc;
        if (!empty($content)){
          $this->content=$content;
        }
        $this->setHtml();
    }

    /**
     * Return the replace content by the assigned tag define.
     */
    abstract function setHtml();

    public function getHtml(){
        return $this->html;
    }

    public function getContent(){
        return $this->content;
    }

    public function setContent($content){
        $this->content=$content;
    }
    public function getTagName(){
        return $this->tagname;
    }

    public function setTagName($tagname){
        $this->tagname=$tagname;
    }

    public function getAttributeDesc(){
        return $this->attributesDesc;
    }

    public function setAttributeDesc($attributesDesc){
        $this->attributesDesc=$attributesDesc;
    }

    /**
     *
     * @param $tagStr
     * @return take the attribute from tag
     */
    final function getAttributesFormTag($tagStr){
        $attributes=array();
        preg_match_all('/\b(\w+)\=(\\\\"([^\\\"]+)\\\"|\\\\\'([^\\\\\']+)\\\\\')/is',$tagStr,$split);
        //            preg_match_all('/\b(\w+)\=(\"([^\"]+)\"|\'([^\']+)\')/is',$tagStr,$split);
        foreach($split[0] as $str){
            $tmpArr=preg_split('/\=(?=\\\"|\\\\\')|\=(?=\"|\')/',$str);
            //              $tmpArr=preg_split('/\=(?=\"|\')/',$str);
            $attributes[$tmpArr[0]]=preg_replace('/\\\\\'/i','',preg_replace('/\\\\\"/i','',$tmpArr[1]));
            //              $attributes[$tmpArr[0]]=$tmpArr[1];
        }

        preg_match_all('/\b(\w+)\=(\"([^\"]+)\"|\'([^\']+)\')/is',$tagStr,$split);
        foreach($split[0] as $str){
            $tmpArr=preg_split('/\=(?=\"|\')|\=(?=\"|\')/',$str);
            $attributes[$tmpArr[0]]=preg_replace("/\'/i",'',preg_replace("/\"/i",'',$tmpArr[1]));
        }
        return $attributes;
    }
}
?>
