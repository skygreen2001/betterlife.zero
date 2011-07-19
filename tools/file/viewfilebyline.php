<?php
header("Content-Type:text/html; charset=UTF-8");
/**
 * 范围【本项目】
 * 查看指定文件的源代码[显示行]
 *
 */
class CustomFO extends SplFileObject {
    private $i=1;
    public function current() {
        return  $this->i++ . ": " .
                htmlspecialchars($this->getCurrentLine())."";
    }
}

//$print_filename="../../index.php"; //需要输出的代码文件
$print_filename=$_GET["f"];

$SFI= new SplFileInfo($print_filename);
$SFI->setFileClass( "CustomFO" );
$file = $SFI->openFile( );
echo "<pre>";
echo '<a href="viewfiles.php">返回</a><br/>';
foreach( $file as $line ) {
    echo $line;
}
echo '<br/><a href="viewfiles.php">返回</a>';

?>