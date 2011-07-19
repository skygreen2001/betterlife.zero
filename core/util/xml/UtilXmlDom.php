<?php
/**
 +---------------------------------------<br/>
 * 采用Dom方式处理Xml<br/>
 * 可采用其处理Html格式的文档内容<br/>
 +---------------------------------------<br/>
 * @category betterlife
 * @package util.xml
 * @author skygreen
 */
class UtilXmlDom extends Util{
    /**
     * 示例：采用Dom方式创建html并显示
     */
    public static function sample_html_new() {
        $doc = new DOMDocument("1.0","UTF-8");
        $html = $doc->createElement("html");
        $body = $doc->createElement("body");
        $h1 = $doc->createElement("h1","OOP with PHP");
        $h1->setAttribute("id","firsth1");
        $p = $doc->createElement("p");
        $p->appendChild($doc->createTextNode("Hi - how about some text?"));
        $body->appendChild($h1);
        $body->appendChild($p);
        $html->appendChild($body);
        $doc->appendChild($html);
        file_put_contents("c:/xml_dom.xml", $doc->saveHTML());
        echo $doc->saveHTML();
    }
    /**
     *  示例：采用Dom方式修改html并显示
     * 前提条件：需要先运行self::sample_html_new()方法；
     */
    public static function sample_html_update() {
        $uri = 'c:/xml_dom.xml';
        $document = new DOMDocument();
        $document->loadHTMLFile($uri);// load the content of this URL as HTML
        $h1s = $document->getElementsByTagName("h1");//find all h1 elements
        $newText = $document->createElement("h1","New Heading");//created a new h1 element
        $h1s->item(0)->parentNode->insertBefore($newText,$h1s->item(0));//insert before the existing h1 element
        $h1s->item(0)->parentNode->removeChild($h1s->item(1));//remove the old h1 element
        echo $document->saveHTML();//display the content as HTML
    }
}
//UtilXmlDom::sample_html_new();
//UtilXmlDom::sample_html_update();

?>
