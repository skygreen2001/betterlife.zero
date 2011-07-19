<?php
/**
 +---------------------------------<br/>
 * 通过WebService NuSoap通信
 +---------------------------------<br/>
 * @category betterlife
 * @package module.communication
 * @subpackage nusoap
 * @author skygreen
 */
class Comm_Nusoap
{
    /**
    * @link http://hi.baidu.com/realfishes/blog/item/e0049bf151103ac87931aae4.html  
    * @link http://lhx1026.javaeye.com/blog/506092 
    */    
    public static function sample(){
        // Create the client instance
        $client = new soapclient("http://122.224.205.206:99/Webtest/GetFeeByHouse.asmx?WSDL",true);

        $err = $client->getError();
        if ($err) {
            echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        }

        $client->soap_defencoding = 'UTF-8';
        $client->decode_utf8 = false;
        $client->xml_encoding = 'utf-8'; 

        $condition="白鹭郡东->Block-13->43幢->1单元->201";
        //$condition=iconv("UTF-8","GBK","白鹭郡东->Block-13->43幢->1单元->201"); 

        // Call the SOAP method
        $result = $client->call('GetPropertyFee', array('houseName' =>$condition));
        if ($client->fault) {
            echo '<h2>Fault</h2><pre>'; print_r($result); echo '</pre>';
        } else {
            // Check for a fault 
            $err = $client->getError();    
            if ($err) {       
                echo '<h2>Error</h2><pre>' . $err . '</pre>';
            } else {
                print_r($result);
            }    
        }
    }
}
?>