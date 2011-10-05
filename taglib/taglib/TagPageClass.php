<?php             
/**
 * 自定义标签:分页
 *
 * @author zyp
 */
class TagPageClass extends TagClass {
    public static $tag_page_sessionname="bb_page";
    private $src;//链接地址  
    function setHtml() {
        $page=HttpSession::get(self::$tag_page_sessionname);
        if ($page) {
            $this->html="";
            $attributes=TagClass::getAttributesFormTag($this->getAttributeDesc());
            if (array_key_exists("src",$attributes)) {
                $this->src=$attributes["src"];
                $page->setLinkUrl($this->src);
            }
            $this->html=$page->createBaseNavi();
//            $this->html=$page->getNavig();
//            $this->html=$page->getNavigTo();

        }else {
            $error_info="The session name should be".$tag_page_sessionname;
            $this->html= $error_info;
            echo $error_info;
        }
    }
}
?>
