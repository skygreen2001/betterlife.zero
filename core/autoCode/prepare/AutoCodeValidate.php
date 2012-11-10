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
     * 0.invalid_idname-检测标识列是否按规范定义<br/>
     * 1.nocomment-表无注释说明<br/>
     * 2.column_nocomment-列名无注释说明<br/>
     * 3.samefieldname_id-同张表列名不能包含:同名、同名_id<br/>
     * 4.invaid_keywords-列名不能为Mysql特殊关键字如:desc,from,<br/>
     * 5.表中列定义中的"_"是半角，不是全角。
     * 6.确认以下表的实体类放置在规范的domain目录下
     */
    public static function run()
    {
        self::init();
        $table_error=array("invalid_idname"=>array(),"unlocation_domain"=>array(),
                           "nocomment"=>array(),"column_nocomment"=>array(),
                           "samefieldname_id"=>array(),"invaid_keywords"=>array(),
                           "specialkey_half"=>array());
        $isValid=true;
        $invaid_keywords=array("desc","from","describe");
        foreach (self::$fieldInfos as $tablename=>$fieldInfo){
            $tableCommentKey=self::tableCommentKey($tablename);
            if (empty($tableCommentKey)){
                $table_error["nocomment"][]=$tablename;    
            }

            $realId=DataObjectSpec::getRealIDColumnName(self::getClassname($tablename));
            if ($realId){
                if (!array_key_exists($realId, $fieldInfo)){
                    $table_error["invalid_idname"][$tablename]=$realId; 
                }                
            }else{
                $table_error["unlocation_domain"][]=$tablename; 
            }
            foreach ($fieldInfo as $fieldname=>$field)
            {       
                $field_comment=$field["Comment"];  
                if (empty($field_comment)){
                    $table_error["column_nocomment"][$tablename]=$fieldname;    
                }
                if (array_key_exists($fieldname."_id", $fieldInfo)){
                    $table_error["samefieldname_id"][$tablename]=$fieldname;  
                }
                if (in_array($fieldname, $invaid_keywords)){
                    $table_error["invaid_keywords"][$tablename]=$fieldname; 
                }
                if  (contain($fieldname,"＿")){
                    $table_error["specialkey_half"][$tablename]=$fieldname; 
                }
            }
        }

        $print_error_info=array(
                                "unlocation_domain"=>"确认以下表的实体类放置在规范的domain目录下",
                                "invalid_idname"=>"标识列未按规范定义,请检查ID列的名称应定义如下",
                                "nocomment"=>"以下表无注释,请添加以下表的注释",
                                "column_nocomment"=>"以下表列举的列无注释,请添加以下表列举的列的注释",
                                "samefieldname_id"=>"列名不能包含:同名、同名_id",
                                "invaid_keywords"=>"列名不能为Mysql特殊关键字如:desc,from",
                                "specialkey_half"=>"表中列定义中的\"_\"是半角，不是全角"
                                );
        foreach ($print_error_info as $key => $value) {
            if (count($table_error[$key])>0){
                $isValid=false;
                echo "<font color='#00FF00'>&nbsp;&nbsp;/".str_repeat("*",40).$value.str_repeat("*",40)."</font></a><br/>";  
                foreach ($table_error[$key] as $first=>$second) {
                    if (is_numeric($first)){
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;".$second."<br/>";
                    }else{
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;".$first."->".$second."<br/>";
                    }
                    
                }
            }
        }
        echo "<br/>";
        return $isValid;
    }
}
?>
