<?php
/**
* 检查清除文件头的Bom
* @link http://www.qinbin.me/removal-of-the-bom-header-php-file/
*/
class CheckBOMTask
{
	/**
	* 是否需要清除文件头的Bom 
	* @var mixed
	*/
	private static $isRemoveBom=false;
	/**
	* 检查文件头是否有Bom
	* @param mixed $isRemoveBom 是否需要清除文件头的Bom
	*/
	public static function run($isRemoveBom=false,$checkDir="../../../")
	{
		self::$isRemoveBom=$isRemoveBom;
		if (isset($_GET['dir'])){
		  $basedir=$_GET['dir'];
		  self::checkdir($basedir);
		}else{
		  self::checkdir($checkDir);             
		  //checkdir("../../../common/js/"); 
		  //checkdir("../../../core/");
		  //checkdir("../../../home/");  
		  //checkdir("../../../include/"); 
		  //checkdir("../../../library/"); 
		  //checkdir("../../../module/");  
		  //checkdir("../../../taglib/");    
		  //checkdir("../../../tools/");  
		}                   
	}  
	
	private static function checkdir($basedir)
	{
	  if ($dh = opendir($basedir)) {
		while (($file = readdir($dh)) !== false) {
		   if ($file != '.' && $file != '..'&& $file != '.git'){
			  if (!is_dir($basedir."/".$file)) {
				  if (self::$isRemoveBom){
					  echo "filename: $basedir/$file ".self::checkBOM("$basedir/$file")." <br>";                      
				  }else{
					  $cb=self::checkBOM("$basedir/$file");
					  if ($cb){
						echo "filename: $basedir/$file ".$cb." <br>";
					  }                                                                                                 
				  }                                                                            
			  }else{
				  $dirname = $basedir."/".$file;
				  self::checkdir($dirname);
			  }
		   }
		}
		closedir($dh);
	  }
	}
	
	private static function checkBOM ($filename) 
	{              
	  $contents = file_get_contents($filename);
	  $charset[1] = substr($contents, 0, 1);
	  $charset[2] = substr($contents, 1, 1);
	  $charset[3] = substr($contents, 2, 1);
	  if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
		if (self::$isRemoveBom) {
		   $rest = substr($contents, 3);
		   self::rewrite ($filename, $rest);
		   return ("<font color=red>BOM found, automatically removed.</font>");
		} else {
		   return ("<font color=red>BOM found.</font>");
		}
	  }else{
		//if (self::$isRemoveBom) return ("BOM Not Found."); else return ""; 
	  }                                                    
	}

	private static function rewrite ($filename, $data) 
	{
	  $filenum = fopen($filename, "w");
	  flock($filenum, LOCK_EX);
	  fwrite($filenum, $data);
	  fclose($filenum);
	}
}
CheckBOMTask::run(false);
?>
