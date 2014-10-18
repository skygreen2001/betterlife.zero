<?php
require_once("../../init.php");
/**
 * 加载数据处理方案的所有目录进IncludePath
 */
$data_root_dir=Gc::$nav_root_path.Config_F::ROOT_DATA.DS;

/**
 * 加载模块里所有的文件
 */
load_module(Config_F::ROOT_DATA,$data_root_dir);


?>
