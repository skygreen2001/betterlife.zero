<?php
header("Content-Type:text/html; charset=UTF-8");
/**
 * 范围【本项目】
 * 查看指定文件的源代码[不显示行]
 */
class CustomFO extends SplFileObject {
    private $i=1;
    public function current() {
        return htmlspecialchars($this->getCurrentLine())."";
    }
}

/**
 * 范围【本项目】
 * 查看指定文件的源代码[显示行]
 */
class CustomLineFO extends SplFileObject {
    private $i=1;
    public function current() {
        return  $this->i++ . ": " .
            htmlspecialchars($this->getCurrentLine())."";
    }
}


//$print_filename="../../index.php"; //需要输出的代码文件
$print_filename=$_GET["f"];
if(array_key_exists("l",$_GET))$show_line=$_GET["l"];
if($print_filename){
    if(file_exists($print_filename)){
        $SFI= new SplFileInfo($print_filename);

        if(isset($show_line)&&($show_line=="false"))$SFI->setFileClass( "CustomFO" ); else $SFI->setFileClass( "CustomLineFO" );
        $file = $SFI->openFile( );
        echo "<pre>";
        echo '<a style="cursor:pointer;" onclick="window.history.back();">返回</a><br/>';
        foreach( $file as $line ) {
            echo $line;
        }
    }
}
echo '<br/><a style="cursor:pointer;" onclick="window.history.back();">返回</a>';

?>