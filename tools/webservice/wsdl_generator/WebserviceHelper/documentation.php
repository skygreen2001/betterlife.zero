<?php
//need to manually include for the function 'get_declared_classes()'
include_once("classes/soap/commentParser.class.php");
include_once("classes/soap/phpdoc.class.php");
include_once("classes/soap/phpdocClass.class.php");
include_once("classes/soap/phpdocMethod.class.php");
include_once("classes/soap/phpdocProperty.class.php");
include_once("classes/soap/WSDLStruct.class.php");
include_once("classes/soap/WSHelper.class.php");
include_once("classes/soap/XMLSchema.class.php");
include_once("classes/soap/xtemplate.class.php");

$phpdoc=new phpdoc();
if(isset($_GET['class'])) $phpdoc->setClass($_GET['class']);
echo $phpdoc->getDocumentation();
?>