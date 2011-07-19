<?php
  /**
  +---------------------------------<br/>
  * 工具类：数组<br/>
  +---------------------------------
  * @category betterlife
  * @package util.common
  * @author skygreen
  */
class UtilArray extends Util 
{       
    //<editor-fold defaultstate="collapsed" desc="array and xml">
    /**
     * 将数组类型转换成xml<br/>
     * The main function for converting to an XML document.<br/>
     * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.<br/>
     * 示例：<br/>
     *     $data=array("id"=>"8","member_id"=>"5","app_name"=>"mall","username"=>"pass","relation"=>array("Role"=>"roleId","Function"=>"functionId"));<br/>
     *     $data=array("a","b","c","d","e"=>array("a","b","c"));<br/>
     *     echo UtilArray::array_to_xml($data, 'Member');<br/>
     * @link http://snipplr.com/view/3491/convert-php-array-to-xml-or-simple-xml-object-if-you-wish/
     * @param array $data
     * @param string $rootNodeName - what you want the root node to be - defaultsto data.
     * @param SimpleXMLElement $xml - should only be used recursively
     * @return string XML xml内容
     */
    public static function array_to_xml($data, $rootNodeName='data',&$xml=null)
    {
        // turn off compatibility mode as simple xml throws a wobbly if you don't.
        if ( ini_get('zend.ze1_compatibility_mode') == 1 ) ini_set ( 'zend.ze1_compatibility_mode', 0 );
        if ( is_null( $xml ) ) $xml = new SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");

        // loop through the data passed in.
        foreach( $data as $key => $value ) {

            // no numeric keys in our xml please!
            if ( is_numeric( $key ) ) {
                $numeric = 1;
                 if (endWith($rootNodeName, "s")){
                    $key=substr($rootNodeName,0,  strlen($rootNodeName)-1); 
                 }else{
                    $key = $rootNodeName."_". (string) $key;
                 }
                //$key = $rootNodeName;
            }

            // delete any char not allowed in XML element names
            $key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);

            if( is_object( $value ) ) {
                $value = get_object_vars( $value );         
            }

            // if there is another array found recrusively call this function
            if ( is_array( $value ) ) {
                $node = self::is_assoc( $value ) || $numeric ? $xml->addChild( $key ) : $xml;

                // recrusive call.
                if ( isset($numeric) ) {                    
                    if (endWith($rootNodeName, "s")){
                        $key=substr($rootNodeName,0,  strlen($rootNodeName)-1);
                    }else{
                        $key = $rootNodeName."_". (string) $key;
                    }
                    //$key = 'anon';
                }
                self::array_to_xml( $value, $key, $node);
            } else {
                // add single node.
                //$value = htmlentities( $value );
                $xml->addChild( $key, $value );
            }
        }

        // pass back as XML
        //return $xml->asXML();

        // if you want the XML to be formatted, use the below instead to return the XML
        $doc = new DOMDocument('1.0');
        $doc->preserveWhiteSpace = false;
        $doc->loadXML( $xml->asXML() );
        $doc->formatOutput = true;
        return $doc->saveXML();        
    }

    /**
     * Convert an XML document to a multi dimensional array<br/>
     * Pass in an XML document (or SimpleXMLElement object) and this recrusively loops through and builds a representative array<br/>
     * 示例：<br/>
     *     $data=array("id"=>"8","member_id"=>"5","app_name"=>"mall","username"=>"pass","relation"=>array("Role"=>"roleId","Function"=>"functionId"));<br/>
     *     $data=array("a","b","c","d","e"=>array("a","b","c"));<br/>
     *     $xml=UtilArray::array_to_xml($data, 'Member');<br/>
     *     print_r(UtilArray::xml_to_array($xml,'Member'))<br/>
     * @link http://snipplr.com/view/3491/convert-php-array-to-xml-or-simple-xml-object-if-you-wish/
     * @param string $xml - XML document - can optionally be a SimpleXMLElement object
     * @return array ARRAY
     */
    public static function xml_to_array( $xml,$rootNodeName = 'data') {
        if ( is_string( $xml ) ){
            $xmlSxe = new SimpleXMLElement( $xml );
        }else{
            $xmlSxe=$xml;
        }
        $children = $xmlSxe->children();        
        if ( !$children ) {
            return (string) $xml;
        }
        $arr = array();
        //$attr_key="@".self::XML_ELEMENT_ATTRIBUTES;
        //unset($children[$attr_key]);
        foreach ( $children as $key => $node ) {
            $node = self::xml_to_array( $node,$rootNodeName);

            // support for 'anon' non-associative arrays
            if (UtilString::contain($key, $rootNodeName."_")) {
               $key = count( $arr ); 
            }
            //if ( $key == 'anon' ) $key = count( $arr );

            // if the node is already set, put it into an array
            if ( isset( $arr[$key] ) ) {
                if ( !is_array( $arr[$key] ) || $arr[$key][0] == null ){
                    $arr[$key] = array( $arr[$key] );
                }
                $arr[$key][] = $node;
            } else {
                $arr[$key] = $node;
            }
        }
        return $arr;
    }

    // determine if a variable is an associative array
    public static function is_assoc( $array ) {
        return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));
    }
    //</editor-fold>
    
    /**
    * 获取多重数组指定key的值<br/>
    * 当数据为多重时，可以通过点隔开的key获取指定key的值  <br/>
    * @param $array_key 中间以小数点隔开
    * @return unknown
    * @example:
    * 如$row=array("db"=>array("table"=>array("row"=>15)))
    *   可通过$array_multi_key="db.table.row"获得
    */
    public static function array_multi_direct_get($array_multi,$array_multi_key) {
         $var = explode('.', $array_multi_key);
         $result = $array_multi;
         foreach ($var as $key) {
            if (!isset($result[$key])) { return false; }
            $result = $result[$key];
         }
         return $result;
    }
    
    /**
     * 获取数组中报刊键数组的数组
     * @param array $array 数组,如array("key1"=>1,"key2"=>2,"key3"=>3,"key4"=>4);
     * @param string $keys 键字符串，如"key1,key3"
     * @return array  数组中包含键数组的数组,如array("key1"=>1,"key3"=>3);
     */
    public static function array_key_filter($array,$keys){
        $return = array();
        foreach(explode(',',$keys) as $k){
            if(isset($array[$k])){
                $return[$k] = $array[$k];
            }
        }
        return $return;
    }
    
     /**
     * 返回数组中指定值的键名称。
     * @param array $arr 数组
     * @param string $propertyValue
     * @param string $prefix 指定前缀或者后缀的名称
     * @param bool $isprefix 是否前缀，true:前缀,false:后缀
     * @return string 数组中指定值的键名称
     */
    public static function array_search($arr,$propertyValue,$pre1sufix="",$isprefix=true){           
        $result=null;        
        if (isset($propertyValue)&&isset($arr)&&in_array($propertyValue,$arr)){
            if (!empty($pre1sufix)){
                if ($isprefix){
                    foreach ($arr as $key => $value) {
                        if ($propertyValue==$value){                        
                            if (startWith($key, $pre1sufix)){
                                return $key;
                            }
                        }                        
                    }
                }else{
                    foreach ($arr as $key => $value) { 
                        if ($propertyValue==$value){    
                            if (endWith($key, $pre1sufix)){ 
                                return $key;
                            }
                        } 
                    }
                }
            }else{
                $result=array_search($propertyValue, $arr);   
            }
        }
        return $result;
    }    
  }
?>
