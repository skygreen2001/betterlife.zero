<?php
mysql_connect("localhost","root","");
$result = mysql_db_query("Spider","select Id,Url,Content from WebPageLocal where Id>$n1 and Id<$n2");//举例：$n1=2,$n2=10000
while($mt = mysql_fetch_array($result)) {
    $Title = "";
    $Body = "";
    $mt2 = strtoupper($mt[2]);
    $PosTitleL = strpos($mt2,"<TITLE>");
    $PosTitleR = strpos($mt2,"</TITLE>");
    $PosBody = strpos($mt2,"<BODY>");
    $PosHeadR = strpos($mt2,"</HEAD>");
    if($PosTitleL&&$PosTitleR) $Title = substr($mt[2],$PosTitleL+7,$PosTitleR-$PosTitleL-7);
    $Title = eregi_replace("'","’",$Title);
    if($PosBody) $Body = substr($mt[2],$PosBody);
    else if($PosHeadR) $Body = substr($mt[2],$PosHeadR+7);
    else if($PosTitleR) $Body = substr($mt[2],$PosTitleR+8);
    else if($PosTitleL) $Body = substr($mt[2],$PosTitleL);
    else $Body = $mt[2];
    $BodyText = strip_tags($Body);
    $BodyNoSpace = eregi_replace("[[:space:]]+","",$BodyText);
    $BodyNoSpace = eregi_replace("　","",$BodyNoSpace);
    $BodyNoQuote = eregi_replace("'","",$BodyNoSpace);
    $Body512 = substr($BodyNoQuote,0,511)." ";
    $Id = $mt[0];
    $Url = $mt[1];
    $sql="Insert Into WebPageFindFast(Id,Url,Title,Content) VALUES($Id,'$Url','$Title','$Body512')";
    mysql_db_query("Spider",$sql) or die($sql);
    echo $Id." ";
}
?>