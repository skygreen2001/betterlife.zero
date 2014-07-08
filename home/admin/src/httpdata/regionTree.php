<?php 
require_once ("../../../../init.php");
$node=intval($_REQUEST["id"]);
if ($node){
    $condition=array("parent_id"=>"$node");
}else{
    $condition=array("parent_id"=>'0');
}
$regions=Region::get($condition,"region_id asc");
echo "[";
if (!empty($regions)){
    $trees="";
    $maxLevel=Region::maxlevel();
    foreach ($regions as $region){
        $trees.="{
            'text': '$region->region_name',
            'id': '$region->region_id',
            'level':'$region->level',";
        if ($region->level==$maxLevel){
            $trees.="'leaf':true,'cls': 'file'";
        }else{
            $trees.="'cls': 'folder'";
        }
        if (isset($region->countChild)){
            if ($region->countChild==0){
                $trees.=",'leaf':true";
            }
        }
        $trees.="},";
    }
    $trees=substr($trees, 0, strlen($trees)-1);
    echo $trees;
}
echo "]";
?>
