<?php
/**
 +---------------------------------------<br/>
 * 采用SimpleXML处理Xml<br/>
 +---------------------------------------<br/>
 * @category betterlife
 * @package util.xml
 * @author skygreen
 */
class UtilXmlSimple extends Util
{ 
    /**
     * Xml文件里的XML转换成Array
     * @param string filename 文件名
     * @example ../../../library/load.library.xml
     */
    public static function fileXmlToArray($filename)
    {
      $sxi = new SimpleXmlIterator($filename, null, true);
      $result=self::sxiToArray($sxi);
      return $result;
    }

    /**
     * SimpleXmlIterator转换成Array
     * @param SimpleXmlIterator $sxi
     * @return array 
     */
    private static function sxiToArray($sxi)
    {
        $a = array();
        for( $sxi->rewind(); $sxi->valid(); $sxi->next() ) {
            if(!array_key_exists($sxi->key(), $a)){
                $a[$sxi->key()] = array();
            }
            if($sxi->hasChildren()){
                $a[$sxi->key()][] = self::sxiToArray($sxi->current());
            }
            else{
                $sxiCurrent=$sxi->current();
                $tmp=array();
                $tmp[self::XML_ELEMENT_TEXT] = strval($sxiCurrent);
                $tmpAttr=@array_values((array)$sxiCurrent->attributes());
                if (isset($tmpAttr)&&count($tmpAttr)>0){
                    $tmp[self::XML_ELEMENT_ATTRIBUTES]=$tmpAttr[0];
                }
                $a[$sxi->key()][]=$tmp;
            }
        }
        return $a;
    }    

    /**
     * Xml文件里的XML转换成对象
     * @param string $xml_filename 文件名
     * @return SimpleXMLElement Object
     */
    public static function fileXmlToObject($xml_filename)
    {
        if (file_exists($xml_filename))
        {
            return simplexml_load_file($xml_filename);
        }
        return null;
    }
    
    /**
     * 将xml数组对象转换成数组
     * @param string $arrObjData
     * @param array $arrSkipIndices
     * @return array 
     */
    public static function objectsIntoArray($arrObjData, $arrSkipIndices = array())
    {
        $arrData = array();

        // if input is object, convert into array
        if (is_object($arrObjData)) {
            $arrObjData = get_object_vars($arrObjData);
        }

        if (is_array($arrObjData)) {
            foreach ($arrObjData as $index => $value) {
                if (is_object($value) || is_array($value)) {
                    $value = self::objectsIntoArray($value, $arrSkipIndices); // recursive call
                }
                if (in_array($index, $arrSkipIndices)) {
                    continue;
                }
                $arrData[$index] = $value;
            }
        }
        return $arrData;
    }
    
    /**
     * 示例：显示Flickr图片内容
     */
    public static function sample_flickr() 
    {
        $content =file_get_contents(
                "http://www.flickr.com/services/feeds/photos_public.gne");
        $sx=simplexml_load_string($content);
        foreach ($sx->entry as $entry) {
            echo "<a href='{$entry->link['href']}'>".$entry->title."</a><br/>";
            echo $entry->content."<br/>";
        }
    }

    /**
     * 示例：显示Email
     */
    public static function sample_email() 
    {
        $str = <<< EMAIL
        <emails>
            <email type="mime">
                <from>nowhere@notadomain.tld</from>
                <to>unknown@unknown.tld</to>
                <subject>there is no subject</subject>
                <body><![CDATA[is it a body? oh ya, with some texts & symbols & images]]></body>
            </email>
            <email>
                <from>nowhere@notadomain.tld</from>
                <to>unknown@unknown.tld</to>
                <subject>there is no subject</subject>
                <body>is it a body? oh ya</body>
            </email>
        </emails>
EMAIL;
        $sxml = simplexml_load_string($str);
//$s = simplexml_load_string($str,null,LIBXML_NOCDATA);//PHP5.1以前版本；当xml有特殊字符需要如此定义
        print_r($sxml);
//echo $sxml->email[0]->from;
//foreach ($sxml->email as $email) {
//    echo $email->from."：";
//    echo $email['type']."<br/>";
//    echo $email->body."<br/>";
//}
    }

    /**
     * 示例：XPath寻径显示ACL
     */
    public static function sample_xpath_acl() 
    {
        $str = <<< ROLES
    <roles>
        <task type="analysis">
            <state name="new">
                <assigned to="cto">
                    <action newstate="clarify" assignedto="pm">
                        <notify>pm</notify>
                        <notify>cto</notify>
                    </action>
                </assigned>
            </state>
            <state name="clarify">
                <assigned to="pm">
                    <action newstate="clarified" assignedto="pm">
                        <notify>cto</notify>
                    </action>
                </assigned>
            </state>
        </task>
    </roles>
ROLES;
        $s = simplexml_load_string($str);
        $node = $s->xpath("//task[@type='analysis']/state[@name='new']/assigned[@to='cto']");
        echo $node[0]->action[0]['newstate']."<br/>";
        echo $node[0]->action[0]->notify[0]."<br/>";
        echo count($s->xpath("//state"))."<br/>";
        echo count($s->xpath("//notify"))."<br/>";
        echo count($s->xpath("task//notify"))."<br/>";
    }
}
//UtilXmlSimple::sample_email();
//UtilXmlSimple::sample_flickr();
//UtilXmlSimple::sample_xpath_acl();

?>
