<?php
/**
 * 自定义标签:分页
 *
 * @author zyp
 */
class TagPageClass extends TagClass
{
    public static $tag_page_sessionname="bb_page";
    private $src;//链接地址
    private $style;//分页风格，默认是1

    public function setHtml()
    {
        $page=HttpSession::get(self::$tag_page_sessionname);
        if ($page) {
            $this->html="";
            $attributes=TagClass::getAttributesFormTag($this->getAttributeDesc());
            if (array_key_exists("src",$attributes)) {
                $this->src=$attributes["src"];
                $page->setLinkUrl($this->src);
            }
            if (array_key_exists("style",$attributes)) {
                $this->style=$attributes["style"];
            }
            if (empty($this->style))$this->style=1;
            switch ($this->style) {
                case 2:
                    $this->html=$page->getNavig();
                    break;
                case 3:
                    $this->html=$page->getNavigTo();
                    break;
                default:
                    $this->html=$page->createBaseNavi();
                    break;
            }
        }else {
            $error_info="分页Session名称应该是:".$tag_page_sessionname;
            $this->html= $error_info;
            echo $error_info;
        }
    }
}
?>
