<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-校验器<br/>
 +---------------------------------<br/>  
 * @category betterlife
 * @package core.autoCode   
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeValidate extends AutoCode
{   
	/**
	 * 预先校验表定义是否有问题<br/>
	 * 校验包括以下问题:<br/>
	 * 0.检测标识列是否按规范定义<br/>
	 * 1.表无注释说明<br/>
	 * 2.列名无注释说明<br/>
	 * 3.列名不能包含:同名、同名_id<br/>
	 * 4.列名不能为Mysql特殊关键字如:desc,from,<br/>
	 */
	public static function run()
	{
		self::init();
		$table_error=array("nocomment"=>array(),"column_nocomment"=>array(),
							);
		$isValid=true;
		foreach (self::$fieldInfos as $tablename=>$fieldInfo){
			$tableCommentKey=self::tableCommentKey($tablename);
			if (empty($tableCommentKey)){
				$table_error["nocomment"][]=$tablename;    
			}
			foreach ($fieldInfo as $fieldname=>$field)
			{       
				$field_comment=$field["Comment"];  
				if (empty($field_comment)){
					$table_error["column_nocomment"][$tablename]=$fieldname;    
				}
			}
		}

		$print_error_info=array("nocomment"=>"以下表无注释,请添加以下表的注释",
								"column_nocomment"=>"以下表列举的列无注释,请添加以下表列举的列的注释");
		foreach ($print_error_info as $key => $value) {
			if (count($table_error[$key])>0){
				$isValid=false;
				echo "<font color='#00FF00'>&nbsp;&nbsp;/".str_repeat("*",40).$value.str_repeat("*",40)."</font></a><br/>";  
				foreach ($table_error[$key] as $first=>$second) {
					if (is_numeric($first)){
						echo $second."<br/>";
					}else{
						echo $first."->".$second."<br/>";
					}
					
				}
			}
		}
		return $isValid;
	}
}
?>
