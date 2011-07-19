<?php
$wsdl_server_ip=gethostbyname($_SERVER['HTTP_HOST']);
$wsdl = "http://$wsdl_server_ip/betterlife/tools/webservice/wsdl_generator/WebserviceHelper/service.php?class=contactManager&wsdl";
//test:test@
echo $wsdl."<br>\n";

$options = Array('actor' =>'http://schema.betterlife.com',
				 'login' => 'test',
				 'password' => 'test',
                                 'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP);
				 //'trace' => true
$client = new SoapClient($wsdl,$options);
$res = $client->getContacts();
print_r($res);
echo $client->__getLastResponse();
//$client->getContact(1);
?>