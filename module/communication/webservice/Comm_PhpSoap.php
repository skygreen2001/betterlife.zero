<?php
 /**
 +---------------------------------<br/>
 * 通过WebService NuSoap通信<br/>
 * 要运行以下程序，需要先打开wamp里 php.ini文件<br/>
 * 将;extension=php_soap.dll前的注释取消；使之有效<br/>
 * 即修改后为：extension=php_soap.dll<br/>
 * 修改后重启wamp；运行phpinfo查看是否soap client =enabled<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package module.communication
 * @subpackage phpsoap
 * @author skygreen
 */
class Comm_PhpSoap{
    public static function sample(){
        $client = new SoapClient("http://122.224.205.206:99/Webtest/GetFeeByHouse.asmx?WSDL",array(    
          "style" => SOAP_RPC,
          "use" => SOAP_ENCODED,
          "soap_version" => SOAP_1_1,  
          "uri"=>"http://122.224.205.206:99/Webtest/GetFeeByHouse.asmx"
        )); 

        // var_dump($client->__getFunctions());
        // echo "<br/><br/><br/><br/><br/>";

        // print_r($client->GetPropertyFee(iconv("UTF-8","GBK","白鹭郡东->Block-13->43幢->1单元->201")));
         $source=$client->GetPropertyFee(array('houseName'=>"白鹭郡东->Block-13->43幢->1单元->201"));

          //第一种输出：输出数据格式【stdclass类型对象】                               
        // print_r($datas);


          //第二种输出：输出XML格式的数据页面显示
         $datas=(array) $source;  
         $datas=$datas['GetPropertyFeeResult'];
         $xmlStr=(array) $datas;

         header('Content-Type: text/xml');
         echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
         echo "<datafrom>";
         echo $xmlStr['any'];
         echo "</datafrom>";
    }
}
?>
