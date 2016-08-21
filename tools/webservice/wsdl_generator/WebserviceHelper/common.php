<?php

/**
 * The autload function checks if the given class is
 * available in the /classes directory
 */
function __autoload($classname) {
    if(file_exists("classes/data_objects/$classname.class.php"))
        include("classes/data_objects/$classname.class.php");
    elseif(file_exists("classes/soap/$classname.class.php"))
        include("classes/soap/$classname.class.php");
    elseif(file_exists("classes/$classname.class.php"))
        include("classes/$classname.class.php");
}

/**
 * Debug function. writes the given string to a file
 * @param string The text to write to the file
 * @param string The filename
 * @return void
 */
function debug($txt,$file="debug.txt"){
    $fp = fopen($file, "a");
    fwrite($fp, str_replace("\n","\r\n","---\r\n".$txt));
    fclose($fp);
}

/**
 * Copies all the properies, including sub-objects to the new class. This function is
 * only used when there is no PHP 5.0.3+ available
 * @param Object Original class
 * @param Object New class
 * @return void
 * @deprec Not needed anymore
 */
/*
function copyClass($old,$new){
    foreach($old as $p_name=>$p_value){
        if(is_object($new->$p_name)){
            copyClass($old->$p_name,$new->$p_name);
        }elseif(is_array($old->$p_name)){
            if($i=strpos($p_name,"array")){
                $name=substr($p_name,0,$i);
            }else $name=false;
            foreach($p_value as $item){
                if($name){
                    if(!is_array($new->$p_name)) $new->$p_name=Array();
                    $new->{$p_name}[]=new $name();
                    $new_item=$new->{$p_name}[(count($new->$p_name)-1)];
                    copyClass($item,$new_item);
                }else{
                    $new->{$p_name}[]=$item;
                }
            }
        }else{
            $new->$p_name=$old->$p_name;
        }
    }
}
*/
?>
