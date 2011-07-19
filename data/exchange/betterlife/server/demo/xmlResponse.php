<?php
//simulate a remote connection
sleep(2);

//include any libraries you want to use here.

//get the $mode var
$mode = stripslashes($_GET['mode']);
//Set the content-type header to xml
header("Content-type: text/xml");
//echo the XML declaration
echo chr(60).chr(63).'xml version="1.0" encoding="utf-8" '.chr(63).chr(62);
?>
<xmlresponse>
  <?php
  //make a decision based on $mode
  switch ($mode) {
    case 'getitems':
      //set items in a test array
      $items_array[] = array(
        'id'=>15,
        'name'=>'Finger Bun',
        'price'=>'$0.80 ea'
      );
      $items_array[] = array(
        'id'=>16,
        'name'=>'Donuts',
        'price'=>'$0.50 ea'
      );
      $items_array[] = array(
        'id'=>17,
        'name'=>'Apple Pie',
        'price'=>'$1.20 slice'
      );
      $items_array[] = array(
        'id'=>18,
        'name'=>'Double Choc Chip Cup Cakes',
        'price'=>'$1.00 ea'
      );

      //echo a count of items
      echo '<item_count>'.count($items_array).'</item_count>';
      //echo the array in XML style
      for ($x=0;$x<count($items_array);$x++) {
        echo '<item>';
        echo '<id>'.htmlspecialchars($items_array[$x]['id']).'</id>';
        echo '<name>'.htmlspecialchars($items_array[$x]['name']).'</name>';
        echo '<price>'.htmlspecialchars($items_array[$x]['price']).'</price>';
        echo '</item>';
      }

      //set a No Error response - function defined below
      SetErrorNode();
      break;

    default:
      //inccorrect mode, let the hacker know!
      SetErrorNode(404,'Stop hacking. Get a real job.');
      break;
  } //end switch
  ?> 
</xmlresponse><?php

//FUNCTIONS
//this sets the error code node - essential for every xml document to be returned.
function SetErrorNode($code=0,$text='') {
  echo "<error_code>" . $code . "</error_code>\n<error>" . htmlspecialchars($text) . "</error>";
}
?>
