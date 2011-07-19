<?php
  require_once("../../../../../init.php");  
?>
<html><head>
<script type="text/javascript" src="gr/abiss/js/sarissa/sarissa.js"></script>
<script type="text/javascript">
//  Will initate the http request for data.
function getUrl(url,fn) {
  if (url && fn) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", url, true);
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4) {
        fn(xmlhttp.responseXML);
      }
    };
    xmlhttp.send('');
  } else {
    alert('url or function not specified!');
  }
}

//  trim functions
//  will trim whitespace from strings
String.prototype.trim=function(){ 
  return this.replace(/^\s*|\s*$/g,'');
}
String.prototype.ltrim=function(){
  return this.replace(/^\s*/g,'');
}
String.prototype.rtrim=function(){
  return this.replace(/\s*$/g,'');
}

//  CheckResultIsOk
//  Will check the XML data you've return for <error_code>x</error_code>
//  and returns the error to the user if found.
function checkResultIsOk(xml) {
  if (!xml) {
    //xml data was empty. Result was not ok.
    return false;
  }
  if (xml.getElementsByTagName('error_code')[0].firstChild.data && 
    Math.abs(xml.getElementsByTagName('error_code')[0].firstChild.data.trim()) > 0) {
    //error has been supplied
    alert('Error ' + xml.getElementsByTagName('error_code')[0].firstChild.data + ': ' +
      xml.getElementsByTagName('error')[0].firstChild.data.trim());
    return false;
  } else {
    //no errors!
    return true;
  }
}

//  ajaxGetItems
//  Called from clicking a link or button on your page
function ajaxGetItems() {
  //send the request    
  var filetouse = '<?php echo Gc::$url_base ?>data/exchange/betterlife/server/demo/xmlResponse.php?mode=getitems';
  getUrl(filetouse,cbGetItems);
}

//  cbGetItems
//  Called when the data from ajaxGetItems has been retrieved.
function cbGetItems(xml) {
  //check result is ok
  if (checkResultIsOk(xml)) {
    //get the item count
    var item_count = Math.abs(xml.getElementsByTagName('item_count')[0].firstChild.data.trim());

    //get the items
    var items = xml.getElementsByTagName('item');
    for (i=0;i<items.length;i++) {          
    //send an alert of the data received
      alert (i + "  Data recieved:\n" +
        "id: " + items[i].getElementsByTagName('id')[0].firstChild.data.trim() + "\n" +
        "name: " + items[i].getElementsByTagName('name')[0].firstChild.data.trim() + "\n" +
        "price: " + items[i].getElementsByTagName('price')[0].firstChild.data.trim() + "\n");           
    } //end i for       
  } //end check result
} //end function

ajaxGetItems();

</script></head><body></body></html>