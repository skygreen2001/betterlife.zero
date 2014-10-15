<?php
require_once ("../../init.php");
/**
 * 重用类型
 */
class EnumReusePjType extends Enum
{
	/**
	 * 完整版【同现有版本一样】
	 */
	const FULL	  = 1;
	/**
	 * 通用版【后台使用Jquery框架】
	 */
	const LIKE	  = 2;
	/**
	 * 高级版【后台使用Extjs框架】
	 */
	const HIGH	  = 3;
	/**
	 * 精简版【只包括框架核心】
	 */
	const MINI	  = 4;
}

/**
 * Web项目代码重用
 * 在项目开发中，往往商业模式是可以重用的
 * 只要在原有的代码基础上稍作修改即可，一般不需要高级开发者花费太多的时间
 * 在公司运作中，只需初级开发者找到文字修改或者换肤即可很快重用代码变身成新的项目
 * 本开发工具提供图像化界面方便开发者快速重用现有代码生成新的项目
 * 输入:
 *		项目路径|项目名称【中文-英文】|项目别名
 *      重用类型
 *          1.完整版【同现有版本一样】
 *          2.通用版【后台使用Jquery框架】
 *          3.高级版【后台使用Extjs框架】
 *          4.精简版【只包括框架核心】
 * 处理流程操作:
 *      1.复制整个项目到新的路径
 *      2.修改Gc.php相关配置
 *      3.修改Config_Db.php
 *      4.修改帮助地址
 *      5.修改应用文件夹名称
 *      6.清除在大部分项目中不需要的目录
 * @author skygreen2001@gmail.com
 */
class Project_Refactor
{
	/**
	 * 重用类型
	 */
	public static $reuse_type=EnumReusePjType::FULL;
	/**
	 * 保存新Web项目路径
	 */
	public static $save_dir="";
	/**
	 * 新Web项目名称【中文】
	 */
	public static $pj_name_cn="";
	/**
	 * 新Web项目名称【英文】
	 */
	public static $pj_name_en="";
	/**
	 * 新Web项目名称别名【最好两个字母,头字母大写】
	 */
	public static $pj_name_alias="";
	/**
	 * 数据库名称
	 */
	public static $db_name="";
	/**
	 * Git版本地址
	 */
	public static $git_name="";
	/**
	 * 需要忽略的目录【在大部分的项目中都不会用到】
	 */
	public static $ignore_dir=array(
		".settings",
		"_notes",
		"attachment",
		"api",
		"data",
		"db",
		"nbproject",
		"phpext",
		"test",
		"upload"
	);

	/**
	 * 清除无关的目录
	 */
	private static function IgnoreDir()
	{
		foreach (self::$ignore_dir as $ignore_dir) {
			$toDeleteDir=self::$save_dir.DS.$ignore_dir;
			UtilFileSystem::deleteDir($toDeleteDir);
		}
		if(is_dir(self::$save_dir.DS.Gc::$module_root.DS."business"))
			UtilFileSystem::deleteDir(self::$save_dir.DS.Gc::$module_root.DS."business");
	}


	/**
	 * 运行生成Web项目代码重用
	 */
	public static function Run()
	{
		if(isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))self::$save_dir=$_REQUEST["save_dir"];
		if(isset($_REQUEST["pj_name_cn"])&&!empty($_REQUEST["pj_name_cn"]))
		{
			self::$pj_name_cn=$_REQUEST["pj_name_cn"];
		}else{
			self::UserInput();
			die("不能为空:新Web项目名称【中文】");
		}
		if(isset($_REQUEST["pj_name_en"])&&!empty($_REQUEST["pj_name_en"]))
		{
			self::$pj_name_en=$_REQUEST["pj_name_en"];
		}else{
			self::UserInput();
			die("不能为空:新Web项目名称【英文】");
		}
		if(isset($_REQUEST["pj_name_alias"])&&!empty($_REQUEST["pj_name_alias"]))
		{
			self::$pj_name_alias=$_REQUEST["pj_name_alias"];
		}else{
			self::UserInput();
			die("不能为空:新Web项目名称别名");
		}
		if(isset($_REQUEST["dbname"])&&!empty($_REQUEST["dbname"]))
		{
			self::$db_name=$_REQUEST["dbname"];
		}else{
			self::UserInput();
			die("不能为空:数据库名称");
		}

		if(isset($_REQUEST["reuse_type"])&&!empty($_REQUEST["reuse_type"]))
			self::$reuse_type=$_REQUEST["reuse_type"];

		if(isset($_REQUEST["git_name"])&&!empty($_REQUEST["git_name"]))
			self::$git_name=$_REQUEST["git_name"];

		$default_dir=Gc::$nav_root_path;
		$domain_root=str_replace(Gc::$appName.DS, "", $default_dir);
		self::$save_dir=$domain_root.self::$save_dir;

		if(is_dir(self::$save_dir)){
			self::UserInput();
			die("该目录已存在!为防止覆盖您现有的代码,请更名!");
		}

		//生成新项目目录
		smartCopy(Gc::$nav_root_path,self::$save_dir);

		//修改Gc.php配置文件
		$gc_file=self::$save_dir.DS."Gc.php";
		$content=file_get_contents($gc_file);
		$content=str_replace(Gc::$site_name, self::$pj_name_cn, $content);
		$content=str_replace(Gc::$appName, self::$pj_name_en, $content);
		$content=str_replace(Gc::$appName_alias, self::$pj_name_alias, $content);
		file_put_contents($gc_file, $content);

		//修改Config_Db.php配置文件
		$conf_db_file=self::$save_dir.DS."config".DS."config".DS."Config_Db.php";
		$content=file_get_contents($conf_db_file);
		$content=str_replace("\$dbname=\"".Config_Db::$dbname."\"", "\$dbname=\"".self::$db_name."\"", $content);
		file_put_contents($conf_db_file, $content);

		//修改Welcome.php文件
		if(!empty(self::$git_name)){
			$welcome_file=self::$save_dir.DS."welcome.php";
			$content=file_get_contents($welcome_file);

			$ctrl=substr($content,0,strpos($content,"<?php \$help_url=\"")+17);
			$ctrr=substr($content,strpos($content,"<?php \$help_url=\"")+18);
			$ctrr=substr($ctrr,strpos($ctrr,"\""));
			$content=$ctrl.self::$git_name.$ctrr;
			file_put_contents($welcome_file, $content);
		}

		//修改应用文件夹名称
		$old_name=self::$save_dir.DS.Gc::$module_root.DS.Gc::$appName.DS;
		$new_name=self::$save_dir.DS.Gc::$module_root.DS.self::$pj_name_en.DS;
		if(is_dir($old_name))rename($old_name,$new_name);

		//清除在大部分项目中不需要的目录
		if(self::$reuse_type==EnumReusePjType::MINI)self::IgnoreDir();

		self::UserInput();
		die("生成新Web项目成功！");

	}

	/**
	 * 用户输入需求
	 */
	public static function UserInput()
	{
		$title="一键重用Web项目代码";
		if(empty($_REQUEST)){
			$pj_name_cn=Gc::$site_name;
			$pj_name_en=Gc::$appName;
			$pj_name_alias=Gc::$appName_alias;
			$default_dir=Gc::$nav_root_path;
			$domain_root=str_replace($pj_name_en.DS, "", $default_dir);
			$default_dir=$pj_name_en;
			$dbname=Config_Db::$dbname;
			$git_name="https://github.com/skygreen2001/betterlife";
		}else{
			$pj_name_cn=self::$pj_name_cn;
			$pj_name_en=self::$pj_name_en;
			$pj_name_alias=self::$pj_name_alias;
			$default_dir=Gc::$nav_root_path;
			$domain_root=str_replace(Gc::$appName.DS, "", $default_dir);
			$default_dir=self::$save_dir;
			$dbname=self::$db_name;
			$git_name=self::$git_name;
		}
		$inputArr=array(
			"1"=>"完整版",
			"2"=>"通用版",
			"3"=>"高级版",
			"4"=>"精简版"
		);

		echo  "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>\r\n
				<html lang='zh-CN' xml:lang='zh-CN' xmlns='http://www.w3.org/1999/xhtml'>\r\n";
		echo "<head>\r\n";
		echo UtilCss::form_css()."\r\n";
		$url_base=UtilNet::urlbase();
		echo "</head>";
		echo "<body>";
		echo "<br/><br/><br/><h1 align='center'>$title</h1>\r\n";
		echo "<div align='center' height='450'>\r\n";
		echo "<form>\r\n";
		echo "  <div style='line-height:1.5em;'>\r\n";
		echo "      <label>Web项目名称【中文】:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' name='pj_name_cn' value='$pj_name_cn' id='pj_name_cn' /><br/>\r\n";
		echo "      <label>Web项目名称【英文】:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' name='pj_name_en' value='$pj_name_en' id='pj_name_en' oninput=\"document.getElementById('dbname').value=this.value;document.getElementById('save_dir').value=this.value;\" /><br/>\r\n";
		echo "      <label title='最好两个字母,头字母大写'>Web项目别名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label><input title='最好两个字母,头字母大写' style='width:400px;text-align:left;padding-left:10px;' type='text' name='pj_name_alias' value='$pj_name_alias' id='pj_name_alias' /><br/>\r\n";
		echo "      <label>输出Web项目路径&nbsp;&nbsp;&nbsp;:</label>$domain_root<input style='width:306px;text-align:left;padding-left:10px;' type='text' name='save_dir' value='$default_dir' id='save_dir' /><br/>\r\n";
		echo "      <label>数据库名称&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' name='dbname' value='$dbname' id='dbname' /><br/>\r\n";
		echo "      <label>帮助地址&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label><input style='width:400px;text-align:left;padding-left:10px;' type='text' name='git_name' value='$git_name' id='git_name' /><br/>\r\n";

		if (!empty($inputArr)){
			echo "<label>&nbsp;&nbsp;&nbsp;重用类型&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label><select name='reuse_type'>\r\n";
			foreach ($inputArr as $key=>$value) {
				echo "        <option value='$key'>$value</option>\r\n";
			}
			echo "      </select>\r\n";
		}
		echo "  </div>\r\n";
		echo "  <input type='submit' value='生成' /><br/>\r\n";
		echo "</form>\r\n";
		echo "</div>\r\n";
		echo "</body>\r\n";
		echo "</html>";
	}
}

/**
 * Copy file or folder from source to destination, it can do
 * recursive copy as well and is very smart
 * It recursively creates the dest file or directory path if there weren't exists
 * Situtaions :
 * - Src:/home/test/file.txt ,Dst:/home/test/b ,Result:/home/test/b -> If source was file copy file.txt name with b as name to destination
 * - Src:/home/test/file.txt ,Dst:/home/test/b/ ,Result:/home/test/b/file.txt -> If source was file Creates b directory if does not exsits and copy file.txt into it
 * - Src:/home/test ,Dst:/home/ ,Result:/home/test/** -> If source was directory copy test directory and all of its content into dest
 * - Src:/home/test/ ,Dst:/home/ ,Result:/home/**-> if source was direcotry copy its content to dest
 * - Src:/home/test ,Dst:/home/test2 ,Result:/home/test2/** -> if source was directoy copy it and its content to dest with test2 as name
 * - Src:/home/test/ ,Dst:/home/test2 ,Result:->/home/test2/** if source was directoy copy it and its content to dest with test2 as name
 * @todo
 *     - Should have rollback technique so it can undo the copy when it wasn't successful
 *  - Auto destination technique should be possible to turn off
 *  - Supporting callback function
 *  - May prevent some issues on shared enviroments : http://us3.php.net/umask
 * @param $source //file or folder
 * @param $dest ///file or folder
 * @param $options //folderPermission,filePermission
 * @return boolean
 */
function smartCopy($source, $dest, $options=array('folderPermission'=>0755,'filePermission'=>0755))
{
    $result=false;

    if (is_file($source)) {
        if ($dest[strlen($dest)-1]=='/') {
            if (!file_exists($dest)) {
                cmfcDirectory::makeAll($dest,$options['folderPermission'],true);
            }
            $__dest=$dest."/".basename($source);
        } else {
            $__dest=$dest;
        }
        $result=copy($source, $__dest);
        chmod($__dest,$options['filePermission']);

    } elseif(is_dir($source)) {
        if ($dest[strlen($dest)-1]=='/') {
            if ($source[strlen($source)-1]=='/') {
                //Copy only contents
            } else {
                //Change parent itself and its contents
                $dest=$dest.basename($source);
                @mkdir($dest);
                chmod($dest,$options['filePermission']);
            }
        } else {
            if ($source[strlen($source)-1]=='/') {
                //Copy parent directory with new name and all its content
                @mkdir($dest,$options['folderPermission']);
                chmod($dest,$options['filePermission']);
            } else {
                //Copy parent directory with new name and all its content
                @mkdir($dest,$options['folderPermission']);
                chmod($dest,$options['filePermission']);
            }
        }

        $dirHandle=opendir($source);
        while($file=readdir($dirHandle))
        {
            if($file!="." && $file!=".."&& $file!=".git")
            {
                 if(!is_dir($source."/".$file)) {
                    $__dest=$dest."/".$file;
                } else {
                    $__dest=$dest."/".$file;
                }
                //echo "$source/$file ||| $__dest<br />";
                $result=smartCopy($source."/".$file, $__dest, $options);
            }
        }
        closedir($dirHandle);

    } else {
        $result=false;
    }
    return $result;
}


//控制器:运行Web项目代码重用
if(isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"])){
	Project_Refactor::Run();
}else{
	Project_Refactor::UserInput();
}

?>
