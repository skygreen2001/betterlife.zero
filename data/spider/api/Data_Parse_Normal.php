<?php
class Data_Parse_Normal {
    /**
     * 要求内容必须按照Xml
     * 有开始标签就必须要有结束标签的规范
     * 否则其后的内容将不再解析
     *
     * @param mixed $content
     */
    public static function To_Xml($content) {
        $parser = xml_parser_create();
        xml_parse_into_struct($parser, $content, $values, $index);
        xml_parser_free($parser);
        echo "<pre>";
        print_r($values);
        echo "</pre>";
    }

    public static function To_SimpleXml($content) {
        $content = simplexml_load_string($content);
        print_r($content);
        return   $content;
    }
}
?>
