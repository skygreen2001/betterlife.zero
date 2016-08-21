<?php
/**
 * ###################################################################################<br/>
 * # XML class: utility class to be used with PHP's XML handling functions <br/>
 * # XML和Array的转换<br/>
 * ###################################################################################<br/>
 * @category betterlife
 * @package util.xml
 * @author Keith Devens
 */
class UtilXMLLib extends Util
{
    ###################################################################################
    # XML_unserialize: takes raw XML as a parameter (a string)
    # and returns an equivalent PHP data structure
    ###################################################################################
    public static function XML_unserialize($xml)
    {
        return XML::parse($xml);
    }
    /**
     * 转换指定xml文件里的内容到数组。
     * @param string $xmlFile Xml内容的文件名
     */
    public static function xmltoArray($xmlFile)
    {
        $xml=file_get_contents($xmlFile);
        $result=self::XML_unserialize($xml);
        return $result;
    }
}

###################################################################################
# XML class: utility class to be used with PHP's XML handling functions
###################################################################################
class XML
{
    public static $tags;//所有解析的标签和对应的xml元的内容所在的标识
    public static $values;//所有解析的经过归类的xml内容

    public function parse($data)
    {
        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, false);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, true);
        xml_parse_into_struct($parser, $data, $values, $vtags);
        xml_parser_free($parser);

        self::$tags =$vtags;
        self::$values =$values;

        $keys=array_keys(self::$tags);
        $root = array_shift($keys);
        foreach ($keys as $key=>$keyword_meta) {
            $current_flags = self::$tags[$keyword_meta];
            $count=0;
            for ($i=0; $i < count($current_flags); $i+=2) {
                $start = $current_flags[$i] + 1;
                if (array_key_exists($i + 1, $current_flags))$end = $current_flags[$i + 1]-1;
                if (array_key_exists("attributes",self::$values[$current_flags[$i]])){
                    $key_attr= $count." attr";
                    $result[$root][$keyword_meta][$key_attr]=self::$values[$current_flags[$i]]["attributes"];
                }
                if (!empty($keys[$key+1]))$result[$root][$keyword_meta][$count] = self::parseElements($start,$end,$keys[$key+1]);
                $count++;
            }
        }
        return $result;
    }

    public static function parseElements($start, $end,$keyword_meta)
    {
        $result=array();
        if (array_key_exists($keyword_meta, self::$tags)){
            $current_flags =self::$tags[$keyword_meta];
            if (!empty($current_flags)){
                $start_flag=array_search($start,$current_flags);
                $end_flag=array_search($end,$current_flags);
                $keys=array_keys(self::$tags);
                $keyword_meta_key=array_search($keyword_meta,$keys);

                $count=0;
                if ($keyword_meta_key+1<count($keys)){
                    $next_keyword_meta=$keys[$keyword_meta_key+1];
                    for ($i=$start_flag; $i < $end_flag; $i+=2) {
                        $start = $current_flags[$i] + 1;
                        $end = $current_flags[$i + 1]-1;
                        if (array_key_exists("attributes",self::$values[$current_flags[$i]])){
                            $key= $count." attr";
                            $result[$keyword_meta][$key]=self::$values[$current_flags[$i]]["attributes"];
                            $result[$keyword_meta][$count] = self::parseElements($start, $end,$next_keyword_meta);
                        } else{
                            $result[$keyword_meta] = self::parseElements($start, $end,$next_keyword_meta);
                        }
                        $count++;
                    }
                }else{
                    for ($i=$start_flag; $i <= $end_flag; $i++) {
                        if (array_key_exists("attributes",self::$values[$current_flags[$i]])){
                            $key= $count." attr";
                            $result[$keyword_meta][$key]=self::$values[$current_flags[$i]]["attributes"];
                        }
                        $result[$keyword_meta][$count] =self::$values[$current_flags[$i]]["value"];
                        $count++;
                    }
                }
            }
        }
        return $result;
    }
}

?>