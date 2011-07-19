<?php

/**
 * Initialisation
 */

//error_reporting(E_ALL | E_STRICT);

define("WEBSERVICEUSER", "test");				//the required login name
define("WEBSERVICEPASSWORD", "test");			//the required password
define("WSURI", "http://schema.betterlife.com");	//schema URI
define("AUTHENTICATE", false); 					// enable or disable user authentication

//cache directory (with trailing slash)
$cache_dir = dirname(__FILE__)."/wsdl_cache/";
$compression = false;

/* All the allowed webservice classes */
$WSClasses = array(
	"contactManager"
);

/* The classmap associative array. When you want to allow objects as a parameter for
 * your webservice method. ie. saveObject($object). By default $object will now be
 * a stdClass, but when you add a classname defined in the type description in the @param 
 * documentation tag and add your class to the classmap below, the object will be of the
 * given type. Requires PHP 5.0.3+ 
 */
$WSStructures = array(
	"contact" => "contact",
	"address" => "address",
);

//start session
session_start();

?>