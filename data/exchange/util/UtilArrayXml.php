<?php
/**
  +---------------------------------<br/>
 * 工具类：将数组转换成Xml<br/>
  +---------------------------------
 * @category betterlife
 * @package data.exchange.util
 * @subpackage array2xml
 * @author skygreen
 */
class UtilArrayXml {    
    /**
     * @link http://code.google.com/p/array-to-domdocument/
     */
    public function dom(){
        $source['book'][0][DOM::ATTRIBUTES]['isbn'] = '978-3-16-148410-0';
        $source['book'][0][DOM::ATTRIBUTES]['publish-date'] = '2002-03-25';
        $source['book'][0]['author'] = 'Author0';
        $source['book'][0]['title'] = 'Title0';
        $source['book'][0]['publisher'] = 'Publisher0';

        $source['book'][1]['author'][0] = 'Author1';
        $source['book'][1]['author'][1] = 'Author2';
        $source['book'][1]['title'] = 'Title1';
        $source['book'][1]['publisher'] = 'Publisher1';

        $source['book'][2][DOM::ATTRIBUTES]['isbn'] = '978-3-16-148410-0';
        $source['book'][2][DOM::ATTRIBUTES]['publish-date'] = '2002-03-25';
        $source['book'][2][DOM::CONTENT] = 'Title2';

        $xml = DOM::arrayToXMLString($source);
        print_r(DOM::xmlStringToArray($xml));        
    }
}

?>
