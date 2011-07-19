<?php
/**
 +---------------------------------<br/>
 * 功能:处理文件目录相关的事宜方法。<br/>
 +---------------------------------
 * @category betterlife
 * @package util.common
 * @author skygreen
 */
class UtilFileSystem extends Util {
    /**
     * 移除数据中的BOM头，它一般是看不见的，但php或html文件有BOM头会影响显示，在头部总有删除不掉的空行。
     * @param string $data 数据
     * @return type 
     */
    public static function removeBom($data){
        if(is_array($data)){
            foreach($data as $k=>$v){
                $charset[1] = substr($v, 0, 1);
                $charset[2] = substr($v, 1, 1);
                $charset[3] = substr($v, 2, 1);
                if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
                    $data[$k] = substr($v, 3);
                }
            }
        }
        else{
            $charset[1] = substr($data, 0, 1);
            $charset[2] = substr($data, 1, 1);
            $charset[3] = substr($data, 2, 1);
            if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
                $data = substr($data, 3);
            }
        }
        return $data;
    }
    
    /**
    * 文件重命名
    * @param string $source 原文件名
    * @param string $dest 新文件名
    * @return bool 是否重命名成功。
    */
    public static function file_rename($source,$dest){
        if(PHP_OS=='WINNT'){
            @copy($source,$dest);
            @unlink($source);
            if(file_exists($dest)) return true;
            else return false;
        }else{
            return rename($source,$dest);
        }
    }
    
    /**
     * 创建文件夹
     * @param string $dir
     * @return bool 是否创建成功。 
     */
    public static function createDir($dir){
       return is_dir($dir) or (self::createDir(dirname($dir)) and mkdir($dir, 0777));
    }
    
        
    /**
     * 保存内容到指定文件。
     * 如果该文件不存在，则创建该文件。
     * @param string $filename 文件名
     * @param string $content  内容
     * @return bool 是否创建成功。 
     */
    public static function save_file_content($filename,$content){
        $cFile = fopen ($filename, 'w' ); 
        if ($cFile){
            file_put_contents($filename, $content);
        }else{
            LogMe::log("创建文件:".$filename."失败！");
        }
        fclose($cFile);     
    }
    
    
    /**
     * 移除文件夹。<br/>
     * 前提是该目录下没有子目录。
     * @param string $path 文件路径
     */
    public static function remove_folder($path){
        if(($handle = opendir($path))){
            while (false !==($file = readdir($handle))){
                if($file!='.' && $file!='..'){
                    if(is_dir($file)){
                        self::remove_floder($path.DIRECTORY_SEPARATOR.$file);
                    }else{
                        @unlink($path.DIRECTORY_SEPARATOR.$file);
                    }
                }
            }
            closedir($handle);
            @rmdir($path);
        }
        return true;
    }
    
    /**
     * 删除目录
     * @param string $dir 目录
     */
    public static function deleteDir($dir){
        $handle = @opendir($dir);
        if(!$handle){
            die("目录不存在");
        }
        while (false !== ($file = readdir($handle))) {
            if($file != "." && $file != ".."){
                $file = $dir . DIRECTORY_SEPARATOR .$file;
                if (is_dir($file)){
                    self::deleteDir($file);
                } else {
                    @unlink($file);
                }
            }
        }
        closedir( $handle );
        @rmdir($dir);
    }
    
    /**
     * 上传文件后，将目标文件的权限设置为0644，避免有些服务器丢失读取权限
     * @param string $filename 文件名
     * @param string $destination 目的地
     * @param string $mod 文件访问权限
     * @return bool 是否操作成功 
     */
    public static function move_chmod_uploaded_file($filename,$destination,$mod=0644){
        if(move_uploaded_file($filename,$destination)){
            chmod($destination,$mod);
            return true;
        }else{
            return false;
        }
    }    
    
    /**
     +----------------------------------------------------------<br/>
     * 查看指定目录下的子目录<br/>
     +----------------------------------------------------------
     * @static
     * @access public
     * @param string $dir 指定目录
     * @return array 指定目录下的子目录
     *   1:key-子目录名
     *   2:value-全路径名
     */
    public static function getSubDirsInDirectory($dir) {
        $dirdata=array();
        if (strcmp(substr($dir, strlen($dir)-1,strlen($dir)),DIRECTORY_SEPARATOR)==0) {
            $dir=substr($dir,0,strlen($dir)-1);//如果路径不以DIRECTORY_SEPARATOR结尾的话，应补上
        }

        $iterator = new DirectoryIterator($dir);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isDir()) {
                if($fileinfo->getFilename()!='.'&& $fileinfo->getFilename()!='..'&& $fileinfo->getFilename()!='.svn') {
                    $dirdata[$fileinfo->getFilename()]=$fileinfo->getPathname();
//                    echo $fileinfo->getFilename() ."=>".$fileinfo->getPathname()."\n";
                }
            }
        }
        return  $dirdata;
    }

        public static function getFilesInDirectory($dir,$agreesuffix=array("php")) {
            $result=array();
            if (is_dir($dir)) {
                $dh = opendir($dir);
                if ($dh) {
                    while (($file = readdir($dh)) !== false) {
                         if($file!='.'&& $file!='..'&& $file!='.svn' &&UtilString::contain($file,".")) {    
                             foreach ($agreesuffix as $suffix) {
                                if (strcasecmp(end(explode('.', $file)),$suffix)===0) {
                                    $result[]=$dir.$file;
                                    //echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";                                  
                                }
                             }
                         }
                    }
                    closedir($dh);
                }
            }
            return $result;
        }

    
    /**
     +----------------------------------------------------------<br/>
     * 查看指定目录下的所有文件<br/>
     +----------------------------------------------------------
     * @static
     * @access public
     * @param string $dir 指定目录
     * @param string|array $agreesuffix 是否要求文件后缀名为指定
     *      1.当$agreesuffix='*'为查找所有后缀名的文件
     *      2.当$agreesuffix='php'为查找所有php后缀名的文件
     *      3.当$agreesuffix=array('php','xml')为查找所有php和xml后缀名的文件
     * @return array
     */
    public static function getAllFilesInDirectory($dir,$agreesuffix=array("php")) {
        $data=array();
        if (strcmp(substr($dir, strlen($dir)-1,strlen($dir)),DIRECTORY_SEPARATOR)==0) {
            $dir=substr($dir,0,strlen($dir)-1);//如果路径不以DIRECTORY_SEPARATOR结尾的话，应补上
        }
        $dir=self::charsetConvert($dir);
        self::searchAllFilesInDirectory($dir,$data,$agreesuffix);
        ksort($data);
        $result=array_values($data);
//        print_r($data);
        return  $result;
    }

    /**
     +----------------------------------------------------------<br/>
     * 查看指定目录下所有的目录<br/>
     +----------------------------------------------------------
     * @static
     * @access public
     * @param string $dir 指定目录
     * @return array
     */
    public static function getAllDirsInDriectory($dir) {
        $dirdata=array();
        if (strcmp(substr($dir, strlen($dir)-1,strlen($dir)),DIRECTORY_SEPARATOR)==0) {
            $dir=substr($dir,0,strlen($dir)-1);//如果路径不以DIRECTORY_SEPARATOR结尾的话，应补上
        }
        $dir=self::charsetConvert($dir);
        self::searchAllDirsInDirectory($dir,$dirdata);
        return  $dirdata;
    }

    /**
     * 递归执行查看指定目录下的所有目录
     * @param string $dir 指定目录
     * @return array
     */
    private static function searchAllDirsInDirectory($path,&$dirdata) {
        if(is_dir($path)) {
            $dp=dir($path);
            $dirdata[]=$path;
            while($file=$dp->read()) {
                if($file!='.'&& $file!='..'&& $file!='.svn') {
                    self::searchAllDirsInDirectory($path.DIRECTORY_SEPARATOR.$file,$dirdata);
                }
            }
            $dp->close();
        }
    }

    /**
     * 递归执行查看指定目录下的所有文件
     * @param string $dir 指定目录
     * @param string|array $agreesuffix 是否要求文件后缀名为指定
     *      1.当$agreesuffix='*'为查找所有后缀名的文件
     *      2.当$agreesuffix='php'为查找所有php后缀名的文件
     *      3.当$agreesuffix=array('php','xml')为查找所有php和xml后缀名的文件
     * @return array
     */
    private static function searchAllFilesInDirectory($path,&$data,$agreesuffix=array("php")) {
        $handle = @opendir($path);
        if ($handle) {
            while (false !== ($file = @readdir($handle))) {
                if($file=='.' || $file=='..'|| $file=='.svn') {
                    continue;
                }
                $nextpath = $path.DIRECTORY_SEPARATOR.$file;

                if(is_dir($nextpath)) {
                    self::searchAllFilesInDirectory($nextpath,$data,$agreesuffix);
                }else {
                    if ($file!=="Thumbs.db") {
                        if ($agreesuffix=="*") {
                            $data[dirname($nextpath).DIRECTORY_SEPARATOR.'a'.basename($nextpath)]=$nextpath;
                        }else  if (is_string($agreesuffix)) {
                            if (strcasecmp(end(explode('.', $file)),$agreesuffix)===0) {
                                $data[dirname($nextpath).DIRECTORY_SEPARATOR.'a'.basename($nextpath)]=$nextpath;
                            }
                        }else  if (is_array($agreesuffix)) {
                            foreach ($agreesuffix as $suffix) {
                                if (strcasecmp(end(explode('.', $file)),$suffix)===0) {
                                    $data[dirname($nextpath).DIRECTORY_SEPARATOR.'a'.basename($nextpath)]=$nextpath;
                                }
                            }
                        }
                    }

                }
            }
            @closedir($handle);
        }
    }

    /**
     * 解决直接传入中文文件夹无法正常获取子目录的问题
     * @param string $path
     * @return string
     * @example print_r(UtilFileSystem::getAllFilesInDirectory("D:\\测试文件夹\\"));
     */
    private static function charsetConvert($path) {
        return iconv("UTF-8", "GBK", $path);
    }
}
//print_r(UtilFileSystem::getAllFilesInDirectory("D:\\wamp\\www"));

?>
