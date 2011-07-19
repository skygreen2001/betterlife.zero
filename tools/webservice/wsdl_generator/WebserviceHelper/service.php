<?php

require_once "config.php";
require_once "common.php";

if (AUTHENTICATE && !(isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] == WEBSERVICEUSER && $_SERVER['PHP_AUTH_PW'] == WEBSERVICEPASSWORD)) {
 	header("WWW-Authenticate: Basic realm=\"Webservice\"");
	header("HTTP/1.0 401 Unauthorized");
	die("No access!");
}

require_once "classes/compress.class.php";

if(isset($_GET['class']) && (in_array($_GET['class'], $WSClasses) || in_array($_GET['class'], $WSStructures))) {
	compress_start();
	$WSHelper = new WSHelper(WSURI, $cache_dir, $_GET['class']);
	$WSHelper->handle();
	compress_out();
} else{
	die("No correct class selected");
}

?>