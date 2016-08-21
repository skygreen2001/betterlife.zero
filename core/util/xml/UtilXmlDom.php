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
class UtilXmlDom extends Util
{

    private static $xml = null;
    private static $encoding = 'UTF-8';
    /**
     * 转换数组保存符合规范的XML到指定的文件
     * @param array $filename 文件名
     * @param array $data 符合cml格式的数据
     * @example 
     * 示例：<br/>
     *     $data=array("id"=>"8","member_id"=>"5","app_name"=>"mall","username"=>"pass","relation"=>array("Role"=>"roleId","Function"=>"functionId"));<br/>
     *     $data=array("a","b","c","d","e"=>array("a","b","c"));<br/>
     *     echo UtilArray::array_to_xml($data, 'Member');<br/>
     * 完整的示例[包括@attributes,@value,@cdata]:<br/>
     *         $classes=array(
     *             "class"=>array(
     *                "conditions"=>array(
     *                    "condition"=>array(
     *                       array('@cdata'=>'Stranger in a Strange Land'),
     *                       array(
     *                            '@attributes' => array(
     *                                "relation_class"=>"Blog",
     *                                 "show_name"=>"title"
     *                            ),
     *                            '@value' => "blog_id"
     *                        ),
     *                        array(
     *                            "@value"=>"comment_name"    
     *                        )
     *                    )                    
     *                )
     *            )
     *        );
     * 生成xml如下：<br/>
     * <?xml version="1.0" encoding="utf-8"?>
     * <classes>
     *     <class>
     *         <conditions>
     *             <condition>
     *                 <comment><![CDATA[Stranger in a Strange Land]]></comment>
     *                 <condition relation_class="Blog" show_name="title">blog_id</condition>
     *                 <condition>comment_name</condition>
     *             </condition>
     *         </conditions>
     *     </class>
     * </classes>
     * @param string $rootNodeName - 根节点的名称 - 默认:data.
     */
    public static function saveXML($filename,$data,$rootNodeName='data')
    {
        $result =UtilXmlDom::array_to_xml($data,$rootNodeName);
        $result=str_replace("  ","    ",$result);
        file_put_contents($filename,$result); 
    }

    /**
     * Initialize the root XML node [optional]
     * @param $version
     * @param $encoding
     * @param $format_output
     */
    public static function init($version = '1.0', $encoding = 'UTF-8', $format_output = true)
    {
        self::$xml = new DomDocument($version, $encoding);
        self::$xml->formatOutput = $format_output;
        self::$encoding = $encoding;
    }
 
    /**
     * 将数组类型转换成xml<br/>
     * Convert an Array to XML
     * 原为Array2XML类<br/>
     * 参考:Array2XML:http://www.lalit.org/lab/convert-php-array-to-xml-with-attributes/<br/>
     * 在数组里添加@attributes,@value,@cdata;可以添加Xml中Node的属性，值和CDATA<br/>
     * The main function for converting to an XML document.<br/>
     * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.<br/>
     * @example 
     * 示例：<br/>
     *     $data=array("id"=>"8","member_id"=>"5","app_name"=>"mall","username"=>"pass","relation"=>array("Role"=>"roleId","Function"=>"functionId"));<br/>
     *     $data=array("a","b","c","d","e"=>array("a","b","c"));<br/>
     *     echo UtilArray::array_to_xml($data, 'Member');<br/>
     * 完整的示例[包括@attributes,@value,@cdata]:<br/>
     *         $classes=array(
     *             "class"=>array(
     *                "conditions"=>array(
     *                    "condition"=>array(
     *                       array('@cdata'=>'Stranger in a Strange Land'),
     *                       array(
     *                            '@attributes' => array(
     *                                "relation_class"=>"Blog",
     *                                 "show_name"=>"title"
     *                            ),
     *                            '@value' => "blog_id"
     *                        ),
     *                        array(
     *                            "@value"=>"comment_name"    
     *                        )
     *                    )                    
     *                )
     *            )
     *        );
     * 生成xml如下：<br/>
     * <?xml version="1.0" encoding="utf-8"?>
     * <classes>
     *     <class>
     *         <conditions>
     *             <condition>
     *                 <comment><![CDATA[Stranger in a Strange Land]]></comment>
     *                 <condition relation_class="Blog" show_name="title">blog_id</condition>
     *                 <condition>comment_name</condition>
     *             </condition>
     *         </conditions>
     *     </class>
     * </classes>
     * @param string $node_name - name of the root node to be converted
     * @param array $arr - aray to be converterd
     * @return DomDocument
     */
    public static function array_to_xml($arr=array(),$node_name='data') 
    {
        $xml = self::getXMLRoot();
        $xml->appendChild(self::convert($node_name, $arr));
        self::$xml = null;    // clear the xml node in the class for 2nd time use.
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        return $xml->saveXML(); 
    }
 
    /**
     * Convert an Array to XML
     * @param string $node_name - name of the root node to be converted
     * @param array $arr - aray to be converterd
     * @return DOMNode
     */
    private static function convert($node_name, $arr=array()) 
    {
        //print_arr($node_name);
        $xml = self::getXMLRoot();
        $node = $xml->createElement($node_name);
 
        if(is_array($arr)){
            // get the attributes first.;
            if(isset($arr['@attributes'])) {
                foreach($arr['@attributes'] as $key => $value) {
                    if(!self::isValidTagName($key)) {
                        throw new Exception('[Array2XML] Illegal character in attribute name. attribute: '.$key.' in node: '.$node_name);
                    }
                    $node->setAttribute($key, self::bool2str($value));
                }
                unset($arr['@attributes']); //remove the key from the array once done.
            }
 
            // check if it has a value stored in @value, if yes store the value and return
            // else check if its directly stored as string
            if(isset($arr['@value'])) {
                $node->appendChild($xml->createTextNode(self::bool2str($arr['@value'])));
                unset($arr['@value']);    //remove the key from the array once done.
                //return from recursion, as a note with value cannot have child nodes.
                return $node;
            } else if(isset($arr['@cdata'])) {
                $node->appendChild($xml->createCDATASection(self::bool2str($arr['@cdata'])));
                unset($arr['@cdata']);    //remove the key from the array once done.
                //return from recursion, as a note with cdata cannot have child nodes.
                return $node;
            }
        }
 
        //create subnodes using recursion
        if(is_array($arr)){
            // recurse to get the node for that key
            foreach($arr as $key=>$value){
                if(!self::isValidTagName($key)) {
                    throw new Exception('[Array2XML] Illegal character in tag name. tag: '.$key.' in node: '.$node_name);
                }
                if(is_array($value) && is_numeric(key($value))) {
                    // MORE THAN ONE NODE OF ITS KIND;
                    // if the new array is numeric index, means it is array of nodes of the same kind
                    // it should follow the parent key name
                    foreach($value as $k=>$v){
                        $node->appendChild(self::convert($key, $v));
                    }
                } else {
                    // ONLY ONE NODE OF ITS KIND
                    $node->appendChild(self::convert($key, $value));
                }
                unset($arr[$key]); //remove the key from the array once done.
            }
        }
 
        // after we are done with all the keys in the array (if it is one)
        // we check if it has any text value, if yes, append it.
        if(!is_array($arr)) {
            $node->appendChild($xml->createTextNode(self::bool2str($arr)));
        }
 
        return $node;
    }
 
    /*
     * Get the root XML node, if there isn't one, create it.
     */
    private static function getXMLRoot()
    {
        if(empty(self::$xml)) {
            self::init();
        }
        return self::$xml;
    }
 
    /*
     * Get string representation of boolean value
     */
    private static function bool2str($v)
    {
        //convert boolean to text value.
        $v = $v === true ? 'true' : $v;
        $v = $v === false ? 'false' : $v;
        return $v;
    }
 
    /*
     * Check if the tag name or attribute name contains illegal characters
     * Ref: http://www.w3.org/TR/xml/#sec-common-syn
     */
    private static function isValidTagName($tag)
    {
        $pattern = '/^[a-z_]+[a-z0-9\:\-\.\_]*[^:]*$/i';
        return preg_match($pattern, $tag, $matches) && $matches[0] == $tag;
    }    
    
    /**
     * 仅供测试
     */
    public static function main()
    {
        UtilXmlDom::sample_html_new();
        UtilXmlDom::sample_html_update();
    }
    
    /**
     * 示例：采用Dom方式创建html并显示
     */
    private static function sample_html_new() 
    {
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
    private static function sample_html_update() 
    {
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

?>
