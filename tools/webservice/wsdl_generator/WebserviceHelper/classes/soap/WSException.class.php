<?php 
/**
 * Exception class which can be thrown by
 * the WSHelper class.
 */
class WSException extends Exception { 
 	/** 
 	 * @param string The error message
 	 * @return void 
 	 */
	public function __construct($msg) { 
		$this->msg = $msg; 
	} 
	
	public function toString(){
		$dom = new DOMDocument('1.0', 'UTF-8');
		$envelope = $dom->createElementNS('http://schemas.xmlsoap.org/soap/envelope/', 'SOAP-ENV:Envelope');
		
		$body = $dom->createElementNS('http://schemas.xmlsoap.org/soap/envelope/', 'SOAP-ENV:Body');
		$fault = $dom->createElementNS('http://schemas.xmlsoap.org/soap/envelope/', 'SOAP-ENV:Fault');
		$faultCode = $dom->createElement('faultcode','Server');
		$faultString = $dom->createElement('faultstring',$this->msg);
		$fault->appendChild($faultCode);
		$fault->appendChild($faultString);
		$body->appendChild($fault);
		$envelope->appendChild($body);
		$dom->appendChild($envelope);
		return $dom->saveXML();
	}
} 
?>