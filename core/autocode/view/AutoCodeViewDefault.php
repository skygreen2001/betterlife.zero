<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-前端默认的表示层
 +---------------------------------<br/>
 * @category betterlife
 * @package core.autocode.view
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeViewDefault extends AutoCode
{
    /**
     * 表示层生成定义的方式<br/>
     * 0.生成前台所需的表示层页面。<br/>
     * 1.生成标准的增删改查模板所需的表示层页面。<br/>
     */
    public static $type;
    /**
     * 表示层所在的目录
     */
    public static $view_core;
    /**
     * 表示层完整的保存路径
     */
    public static $view_dir_full;
    /**
     * View生成tpl所在的应用名称，默认同网站应用的名称
     */
    public static $appName;
    /**
     * 设置必需的路径
     */
    public static function pathset()
    {
        switch (self::$type) {
           case 0:
             self::$app_dir=Gc::$appName;
             if (empty(self::$appName)){
                self::$appName=Gc::$appName;
             }
             break;
           case 1:
             self::$app_dir="model";
             self::$appName="model";
             break;
        }
        self::$view_dir_full=self::$save_dir.self::$app_dir.DS.Config_F::VIEW_VIEW.DS.Gc::$self_theme_dir.DS.Config_F::VIEW_CORE.DS;
    }

    /**
     * 自动生成代码-前端默认的表示层
     * @param array|string $table_names
     * 示例如下：
     *  1.array:array('bb_user_admin','bb_core_blog')
     *  2.字符串:'bb_user_admin,bb_core_blog'
     */
    public static function AutoCode($table_names="")
    {
        self::pathset();
        self::init();
        if (self::$isOutputCss)self::$showReport.= UtilCss::form_css()."\r\n";
        switch (self::$type) {
           case 0:
                self::$showReport.=AutoCodeFoldHelper::foldEffectCommon("Content_41");
                self::$showReport.= "<font color='#FF0000'>生成前台所需的表示层页面:</font></a>";
                self::$showReport.= '<div id="Content_41" style="display:none;">';

                $link_view_default_dir_href="file:///".str_replace("\\", "/", self::$view_dir_full);
                self::$showReport.= "<font color='#AAA'>存储路径:<a target='_blank' href='".$link_view_default_dir_href."'>".self::$view_dir_full."</a></font><br/><br/>";

                self::createModelIndexFile($table_names);
                self::createFrontModelPages($table_names);
                self::$showReport.= "</div><br>";
             break;
           case 1:
                self::$showReport.=AutoCodeFoldHelper::foldEffectCommon("Content_42");
                self::$showReport.= "<font color='#FF0000'>生成标准的增删改查模板表示层页面:</font></a>";
                self::$showReport.= '<div id="Content_42" style="display:none;">';

                $link_view_default_dir_href="file:///".str_replace("\\", "/", self::$view_dir_full);
                self::$showReport.= "<font color='#AAA'>存储路径:<a target='_blank' href='".$link_view_default_dir_href."'>".self::$view_dir_full."</a></font><br/><br/>";

                self::createModelIndexFile($table_names);

                $fieldInfos=self::fieldInfosByTable_names($table_names);
                foreach ($fieldInfos as $tablename=>$fieldInfo){
                    $tpl_listsContent=self::tpl_lists($tablename,$fieldInfo);
                    $filename="lists".Config_F::SUFFIX_FILE_TPL;
                    $tplName=self::saveTplDefineToDir($tablename,$tpl_listsContent,$filename);
                    self::$showReport.= "生成导出完成:$tablename=>$tplName!<br/>";
                    $tpl_viewContent=self::tpl_view($tablename,$fieldInfo);
                    $filename="view".Config_F::SUFFIX_FILE_TPL;
                    $tplName=self::saveTplDefineToDir($tablename,$tpl_viewContent,$filename);
                    self::$showReport.= "生成导出完成:$tablename=>$tplName!<br/>";
                    $tpl_editContent=self::tpl_edit($tablename,$fieldInfo);
                    $filename="edit".Config_F::SUFFIX_FILE_TPL;
                    $tplName=self::saveTplDefineToDir($tablename,$tpl_editContent,$filename);
                    self::$showReport.= "生成导出完成:$tablename=>$tplName!<br/>";
                }
                self::$showReport.= "</div><br>";
             break;
        }
    }

    /**
     * 用户输入需求
     * @param $default_value 默认值
     */
    public static function UserInput($title = "", $inputArr = null, $default_value = "", $more_content = "")
    {
        $inputArr=array(
            "0"=>"生成前台所需的表示层页面",
            "1"=>"生成标准的增删改查模板所需的表示层页面"
        );
        return parent::UserInput("一键生成表示层页面",$inputArr,$default_value);
    }

    /**
     * 将表列定义转换成表示层列表页面tpl文件定义的内容
     * @param string $tablename 表名
     * @param array $fieldInfo 表列信息列表
     */
    private static function tpl_lists($tablename,$fieldInfo)
    {
        $table_comment=self::tableCommentKey($tablename);
        $appname=self::$appName;
        $classname=self::getClassname($tablename);
        $instancename=self::getInstancename($tablename);
        $fieldNameAndComments=array();
        $enumColumns=array();
        foreach ($fieldInfo as $fieldname=>$field)
        {
            $field_comment=$field["Comment"];
            if (contain($field_comment,"\r")||contain($field_comment,"\n"))
            {
                $field_comment=preg_split("/[\s,]+/", $field_comment);
                $field_comment=$field_comment[0];
            }
            $fieldNameAndComments[$fieldname]=$field_comment;
            $datatype=self::comment_type($field["Type"]);
            if ($datatype=='enum'){
                $enumColumns[]=$fieldname;
            }
        }
        $headers="";
        $contents="";

        foreach ($fieldNameAndComments as $key=>$value) {
            if (self::isNotColumnKeywork($key)){
                $isImage =self::columnIsImage($key,$value);
                if ($isImage)continue;

                $is_no_relation=true;
                //关系列的显示
                if (is_array(self::$relation_viewfield)&&(count(self::$relation_viewfield)>0))
                {
                    if (array_key_exists($classname,self::$relation_viewfield))
                    {
                        $relationSpecs=self::$relation_viewfield[$classname];
                        if (array_key_exists($key,$relationSpecs))
                        {
                            $relationShow=$relationSpecs[$key];
                            foreach ($relationShow as $key_r=>$value_r) {
                                $realId=DataObjectSpec::getRealIDColumnName($key_r);
                                if (empty($realId))$realId=$key;
                                if ((!array_key_exists($value_r,$fieldInfo))||($classname==$key_r)){
                                    $show_fieldname=$value_r;
                                    if ($realId!=$key){
                                        if (contain($key,"_id")){
                                            $key=str_replace("_id","",$key);
                                        }
                                        $show_fieldname.="_".$key;
                                    }
                                    if ($show_fieldname=="name"){
                                        $show_fieldname= strtolower($key_r)."_".$value_r;
                                    }
                                    if (!array_key_exists("$show_fieldname",$fieldInfo)){
                                        $field_comment=$value;
                                        $field_comment=self::columnCommentKey($field_comment,$key);
                                        $headers.="            <th class=\"header\">$field_comment</th>\r\n";
                                        $contents.="            <td class=\"content\">{\${$instancename}.$show_fieldname}</td>\r\n";
                                        $is_no_relation=false;
                                    }
                                }else{
                                    if ($value_r=="name"){
                                        $show_fieldname= strtolower($key_r)."_".$value_r;
                                        if (!array_key_exists("$show_fieldname",$fieldInfo)){
                                            $field_comment=$value;
                                            $field_comment=self::columnCommentKey($field_comment,$key);
                                            $headers.="            <th class=\"header\">$field_comment</th>\r\n";
                                            $contents.="            <td class=\"content\">{\${$instancename}.$show_fieldname}</td>\r\n";
                                            $is_no_relation=false;
                                        }
                                    }
                                }
                                $relation_classcomment=self::relation_classcomment(self::$class_comments[$key_r]);

                                $fieldInfo_relationshow=self::$fieldInfos[self::getTablename($key_r)];
                                $key_r{0}=strtolower($key_r{0});
                                if (array_key_exists("parent_id",$fieldInfo_relationshow)){
                                    $headers.="            <th class=\"header\">{$field_comment}[全]</th>\r\n";
                                    $contents.="            <td class=\"content\">{\${$instancename}.{$key_r}ShowAll}</td>\r\n";
                                }
                            }
                        }
                    }
                }
                if($is_no_relation){
                    $headers.="            <th class=\"header\">$value</th>\r\n";
                    if((count($enumColumns)>0)&&(in_array($key, $enumColumns))){
                        $contents.="            <td class=\"content\">{\${$instancename}.{$key}Show}</td>\r\n";
                    }else{
                        $contents.="            <td class=\"content\">{\${$instancename}.$key}</td>\r\n";
                    }
                }
            }
        }

        if (!empty($headers)&&(strlen($headers)>2)){
            $headers=substr($headers,0,strlen($headers)-2);
            $contents=substr($contents,0,strlen($contents)-2);
        }
        $realId=DataObjectSpec::getRealIDColumnName($classname);
        $result = <<<LISTS
<div class="block">
    <div><h1>{$table_comment}列表(共计{\$count{$classname}s}个)</h1></div>
    <table class="viewdoblock">
        <tr class="entry">
$headers
            <th class="header">操作</th>
        </tr>
        {foreach item={$instancename} from=\${$instancename}s}
        <tr class="entry">
$contents
            <td class="btnCol"><my:a href="{\$url_base}index.php?go={$appname}.{$instancename}.view&amp;id={\${$instancename}.$realId}&amp;pageNo={\$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{\$url_base}index.php?go={$appname}.{$instancename}.edit&amp;id={\${$instancename}.$realId}&amp;pageNo={\$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{\$url_base}index.php?go={$appname}.{$instancename}.delete&amp;id={\${$instancename}.$realId}&amp;pageNo={\$smarty.get.pageNo|default:"1"}">删除</my:a></td>
        </tr>
        {/foreach}
    </table>

    <div class="footer" align="center">
        <div><my:page src='{\$url_base}index.php?go={$appname}.{$instancename}.lists' /></div>
        <my:a href='{\$url_base}index.php?go={$appname}.{$instancename}.edit&amp;pageNo={\$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{\$url_base}index.php?go={$appname}.index.index'>返回首页</my:a>
    </div>
</div>
LISTS;
        $result=self::tableToViewTplDefine($result);
        return $result;
    }

    /**
     * 将表列定义转换成表示层列表页面tpl文件定义的内容
     * @param string $tablename 表名
     * @param array $fieldInfo 表列信息列表
     */
    private static function tpl_edit($tablename,$fieldInfo)
    {
        $table_comment=self::tableCommentKey($tablename);
        $appname=self::$appName;
        $classname=self::getClassname($tablename);
        $instancename=self::getInstancename($tablename);
        $fieldNameAndComments=array();
        $text_area_fieldname=array();
        $enumColumns=array();
        foreach ($fieldInfo as $fieldname=>$field)
        {
            $field_comment=$field["Comment"];
            if (contain($field_comment,"\r")||contain($field_comment,"\n"))
            {
                $field_comment=preg_split("/[\s,]+/", $field_comment);
                $field_comment=$field_comment[0];
            }
            if (self::columnIsTextArea($fieldname,$field["Type"]))
            {
                $text_area_fieldname[$fieldname]=$field_comment;
            }else{
                $fieldNameAndComments[$fieldname]=$field_comment;
            }
            $datatype=self::comment_type($field["Type"]);
            if ($datatype=='enum'){
                $enumColumns[]=$fieldname;
            }
        }
        $headerscontents="";
        $idColumnName="id";
        $hasImgFormFlag="";
        foreach ($fieldNameAndComments as $key=>$value) {
            $idColumnName=DataObjectSpec::getRealIDColumnName($classname);
            if (self::isNotColumnKeywork($key)){
                $isImage =self::columnIsImage($key,$value);
                if ($idColumnName == $key) {
                    $headerscontents .= "        {if \${$instancename}}\r\n".
                                        "        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\">{\${$instancename}.$key}</td></tr>\r\n".
                                        "        {/if}\r\n";
                } else if ($isImage) {
                    $hasImgFormFlag = " enctype=\"multipart/form-data\"";
                    $headerscontents .= "        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\"><input type=\"file\" class=\"edit\" name=\"{$key}Upload\" accept=\"image/png,image/gif,image/jpg,image/jpeg\" value=\"{\${$instancename}.$key}\"/></td></tr>\r\n";
                } else {
                    $headerscontents .= "        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\"><input type=\"text\" class=\"edit\" name=\"$key\" value=\"{\${$instancename}.$key}\"/></td></tr>\r\n";
                }
            }
        }
        $ueTextareacontents = "";
        if (count($text_area_fieldname)>=1){
            $kindEditor_prepare = "    ";
            $ckeditor_prepare = "    ";
            $xhEditor_prepare = "    ";
            $ueEditor_prepare = "";
            foreach ($text_area_fieldname as $key => $value) {
                $headerscontents .= "        <tr class=\"entry\"><th class=\"head\">$value</th>\r\n".
                                    "            <td class=\"content\">\r\n".
                                    "                <textarea id=\"$key\" name=\"$key\" style=\"width:90%;height:300px;\">{\${$instancename}.$key}</textarea>\r\n".
                                    "            </td>\r\n".
                                    "        </tr>\r\n";
                $kindEditor_prepare .= "showHtmlEditor(\"$key\");";
                $ckeditor_prepare .= "ckeditor_replace_$key();";
                $xhEditor_prepare .= "pageInit_$key();";
                $ueEditor_prepare .= "pageInit_ue_$key();";
            }

            $textareapreparesentence = <<<EDIT
    {if (\$online_editor=='KindEditor')}
    <script>
    $kindEditor_prepare
    </script>
    {/if}
    {if (\$online_editor=='CKEditor')}
        {\$editorHtml}
        <script>
        $(function(){
        $ckeditor_prepare
        });
        </script>
    {/if}
    {if (\$online_editor=='xhEditor')}
    <script>
    \$(function(){
    $xhEditor_prepare
    });
    </script>
    {/if}
EDIT;
            $ueTextareacontents=<<<UETC
    {if (\$online_editor=='UEditor')}
        <script>$ueEditor_prepare</script>
    {/if}
UETC;
        }
        if (!empty($headerscontents)&&(strlen($headerscontents)>2)){
            $headerscontents=substr($headerscontents,0,strlen($headerscontents)-2);
        }
        $result = <<<EDIT
     <div class="block">
        <div><h1>{if \${$instancename}}编辑{else}新增{/if}{$table_comment}</h1><p><font color="red">{\$message|default:''}</font></p></div>
        <form name="{$instancename}Form" method="post"$hasImgFormFlag><input type="hidden" name="$idColumnName" value="{\${$instancename}.$idColumnName}"/>
        <table class="viewdoblock">
$headerscontents
            <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{\$url_base}index.php?go=$appname.{$instancename}.lists&amp;pageNo={\$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if \${$instancename}}
            |<my:a href='{\$url_base}index.php?go=$appname.{$instancename}.view&amp;id={\${$instancename}.id}&amp;pageNo={\$smarty.get.pageNo|default:"1"}'>查看{$table_comment}</my:a>
            {/if}
        </div>
    </div>
$ueTextareacontents
EDIT;
        if (count($text_area_fieldname)>=1){
            $result=$textareapreparesentence."\r\n".$result;
        }
        $result=self::tableToViewTplDefine($result);
        return $result;
    }

    /**
     * 将表列定义转换成表示层列表页面tpl文件定义的内容
     * @param string $tablename 表名
     * @param array $fieldInfo 表列信息列表
     */
    private static function tpl_view($tablename,$fieldInfo)
    {
        $table_comment=self::tableCommentKey($tablename);
        $appname=self::$appName;
        $classname=self::getClassname($tablename);
        $instancename=self::getInstancename($tablename);
        $fieldNameAndComments=array();
        $enumColumns=array();
        foreach ($fieldInfo as $fieldname=>$field)
        {
            $field_comment=$field["Comment"];
            if (contain($field_comment,"\r")||contain($field_comment,"\n"))
            {
                $field_comment=preg_split("/[\s,]+/", $field_comment);
                $field_comment=$field_comment[0];
            }
            $fieldNameAndComments[$fieldname]=$field_comment;
            $datatype=self::comment_type($field["Type"]);
            if ($datatype=='enum'){
                $enumColumns[]=$fieldname;
            }
        }
        $headerscontents="";
        foreach ($fieldNameAndComments as $key=>$value) {
            if (self::isNotColumnKeywork($key)){
                $isImage =self::columnIsImage($key,$value);
                if ($isImage){
                    $headerscontents.="        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\">\r\n".
                    "            <div class=\"wrap_2_inner\"><img src=\"{\$uploadImg_url|cat:\$$instancename.$key}\" alt=\"$value\"></div>\r\n".
                    "            <br/>存储相对路径:{\$$instancename.$key}</td></tr>\r\n";
                    continue;
                }

                //关系列的显示
                // $is_no_relation=true;
                if (is_array(self::$relation_viewfield)&&(count(self::$relation_viewfield)>0))
                {
                    if (array_key_exists($classname,self::$relation_viewfield))
                    {
                        $relationSpecs=self::$relation_viewfield[$classname];
                        if (array_key_exists($key,$relationSpecs))
                        {
                            $relationShow=$relationSpecs[$key];
                            foreach ($relationShow as $key_r=>$value_r) {
                                $realId=DataObjectSpec::getRealIDColumnName($key_r);
                                if (empty($realId))$realId=$key;
                                if ((!array_key_exists($value_r,$fieldInfo))||($classname==$key_r)){
                                    $show_fieldname=$value_r;
                                    if ($realId!=$key){
                                        $key_m=$key;
                                        if (contain($key,"_id")){
                                            $key_m=str_replace("_id","",$key_m);
                                        }
                                        $show_fieldname.="_".$key_m;
                                    }
                                    if ($show_fieldname=="name"){
                                        $show_fieldname= strtolower($key_r)."_".$value_r;
                                    }
                                    if (!array_key_exists("$show_fieldname",$fieldInfo)){
                                        $field_comment=$value;
                                        $field_comment=self::columnCommentKey($field_comment,$key);
                                        $headerscontents.="        <tr class=\"entry\"><th class=\"head\">$field_comment</th><td class=\"content\">{\${$instancename}.$show_fieldname}</td></tr>\r\n";
                                        // $is_no_relation=false;
                                    }
                                }else{
                                    if ($value_r=="name"){
                                        $show_fieldname= strtolower($key_r)."_".$value_r;
                                        if (!array_key_exists("$show_fieldname",$fieldInfo)){
                                            $field_comment=$value;
                                            $field_comment=self::columnCommentKey($field_comment,$key);
                                            $headerscontents.="        <tr class=\"entry\"><th class=\"head\">$field_comment</th><td class=\"content\">{\${$instancename}.$show_fieldname}</td></tr>\r\n";
                                            // $is_no_relation=false;
                                        }
                                    }
                                }

                                $relation_classcomment=self::relation_classcomment(self::$class_comments[$key_r]);

                                $fieldInfo_relationshow=self::$fieldInfos[self::getTablename($key_r)];
                                $key_r{0}=strtolower($key_r{0});
                                if (array_key_exists("parent_id",$fieldInfo_relationshow)){
                                    $headerscontents.="        <tr class=\"entry\"><th class=\"head\">{$field_comment}[全]</th><td class=\"content\">{\${$instancename}.{$key_r}ShowAll}</td></tr>\r\n";
                                }
                            }
                        }
                    }
                }

                if((count($enumColumns)>0)&&(in_array($key, $enumColumns))){
                    $headerscontents.="        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\">{\$$instancename.{$key}Show}</td></tr>\r\n";
                }else{
                    $headerscontents.="        <tr class=\"entry\"><th class=\"head\">$value</th><td class=\"content\">{\$$instancename.$key}</td></tr>\r\n";
                }
            }
        }
        if (!empty($headerscontents)&&(strlen($headerscontents)>2)){
            $headerscontents=substr($headerscontents,0,strlen($headerscontents)-2);
        }
        $realId=DataObjectSpec::getRealIDColumnName($classname);
        $result = <<<VIEW
<div class="block">
    <div><h1>查看{$table_comment}</h1></div>
    <table class="viewdoblock">
$headerscontents
    </table>
    <div class="footer" align="center"><my:a href='{\$url_base}index.php?go=$appname.{$instancename}.lists&amp;pageNo={\$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{\$url_base}index.php?go=$appname.{$instancename}.edit&amp;id={\${$instancename}.$realId}&amp;pageNo={\$smarty.get.pageNo|default:"1"}'>修改{$table_comment}</my:a></div>
</div>
VIEW;
        $result=self::tableToViewTplDefine($result);
        return $result;
    }

    /**
     * 表注释只获取第一行内容
     * @param array $classcomment 表注释
     */
    private static function relation_classcomment($classcomment)
    {
        $classcomment=str_replace("关系表","",$classcomment);
        if (contain($classcomment,"\r")||contain($classcomment,"\n")){
            $classcomment=preg_split("/[\s,]+/", $classcomment);
            $classcomment=$classcomment[0];
        }
        return $classcomment;
    }

    /**
     * 将表列定义转换成表示层tpl文件定义的内容
     * @param string $contents 页面内容
     */
    private static function tableToViewTplDefine($contents)
    {
        $result="{extends file=\"\$templateDir/layout/normal/layout.tpl\"}\r\n".
                "{block name=body}\r\n".
                "$contents\r\n".
                "{/block}";
        return $result;
    }

    /**
     * 生成标准的增删改查模板Action文件需生成首页访问所有生成的链接
     * @param array|string $table_names
     * 示例如下：
     *  1.array:array('bb_user_admin','bb_core_blog')
     *  2.字符串:'bb_user_admin,bb_core_blog'
     */
    private static function createModelIndexFile($table_names="")
    {
        $tableInfos=self::tableInfosByTable_names($table_names);
        $tpl_content="    <div><h1>这是首页列表(共计数据对象".count($tableInfos)."个)</h1></div>\r\n";
        $result="";
        $appname=self::$appName;
        if ($tableInfos!=null&&count($tableInfos)>0){
            foreach ($tableInfos as $tablename=>$tableInfo){
                $table_comment=$tableInfos[$tablename]["Comment"];
                if (contain($table_comment,"\r")||contain($table_comment,"\n")){
                    $table_comment=preg_split("/[\s,]+/", $table_comment);
                    $table_comment=$table_comment[0];
                }
                $instancename=self::getInstancename($tablename);
                $result.="        <tr class=\"entry\"><td class=\"content\"><a href=\"{\$url_base}index.php?go={$appname}.{$instancename}.lists\">{$table_comment}</a></td></tr>\r\n";
            }
        }
        $tpl_content.="    <table class=\"viewdoblock\" style=\"width: 500px;\">\r\n".
                     $result.
                     "    </table>\r\n".
                     "        \r\n";
        $tpl_content=self::tableToViewTplDefine($tpl_content);
        $filename="index".Config_F::SUFFIX_FILE_TPL;
        $dir=self::$view_dir_full."index".DS;
        return self::saveDefineToDir($dir,$filename,$tpl_content);
    }

    /**
     * 生成前台所需的表示层页面
     * @param array|string $table_names
     * 示例如下：
     *  1.array:array('bb_user_admin','bb_core_blog')
     *  2.字符串:'bb_user_admin,bb_core_blog'
     */
    private static function createFrontModelPages($table_names="")
    {

        $fieldInfos=self::fieldInfosByTable_names($table_names);
        foreach ($fieldInfos as $tablename=>$fieldInfo){
            if(self::$type==0) {
                $classname=self::getClassname($tablename);
                if ($classname=="Admin")continue;
            }
            $table_comment=self::tableCommentKey($tablename);
            $appname=self::$appName;
            $instancename=self::getInstancename($tablename);
            $link="    <div align=\"center\"><my:a href=\"{\$url_base}index.php?go={$appname}.{$instancename}.view\">查看</my:a>|<my:a href=\"{\$url_base}index.php?go={$appname}.{$instancename}.edit\">修改</my:a>";
            $back_index="    <my:a href='{\$url_base}index.php?go={$appname}.index.index'>返回首页</my:a></div>";
            $tpl_content=self::tableToViewTplDefine("    <div><h1>".$table_comment."列表</h1></div><br/>\r\n{$link}<br/>\r\n{$back_index}");
            $filename="lists".Config_F::SUFFIX_FILE_TPL;
            $tplName=self::saveTplDefineToDir($tablename,$tpl_content,$filename);
            self::$showReport.= "生成导出完成:$tablename=>$tplName!<br/>";
            $link="     <div align=\"center\"><my:a href=\"{\$url_base}index.php?go={$appname}.{$instancename}.lists\">返回列表</my:a>";
            $tpl_content=self::tableToViewTplDefine("    <div><h1>查看".$table_comment."</h1></div><br/>\r\n{$link}<br/>\r\n{$back_index}");
            $filename="view".Config_F::SUFFIX_FILE_TPL;
            $tplName=self::saveTplDefineToDir($tablename,$tpl_content,$filename);
            self::$showReport.= "生成导出完成:$tablename=>$tplName!<br/>";
            $tpl_content=self::tableToViewTplDefine("    <div><h1>编辑".$table_comment."</h1></div><br/>\r\n{$link}<br/>\r\n{$back_index}");
            $filename="edit".Config_F::SUFFIX_FILE_TPL;
            $tplName=self::saveTplDefineToDir($tablename,$tpl_content,$filename);
            self::$showReport.= "生成导出完成:$tablename=>$tplName!<br/>";
        }
    }

    /**
     * 保存生成的tpl代码到指定命名规范的文件中
     * @param string $tablename 表名称
     * @param string $defineTplFileContent 生成的代码
     * @param string $filename 文件名称
     */
    private static function saveTplDefineToDir($tablename,$defineTplFileContent,$filename)
    {
        $package =self::getInstancename($tablename);
        $dir=self::$view_dir_full.$package.DS;

        $classname=self::getClassname($tablename);
        $relative_path=str_replace(self::$save_dir, "", $dir.$filename);
        switch (self::$type) {
            case 0:
                AutoCodePreviewReport::$view_front_files[$classname.$filename]=$relative_path;
                break;
            case 1:
                AutoCodePreviewReport::$view_model_files[$classname.$filename]=$relative_path;
                break;
        }
        return self::saveDefineToDir($dir,$filename,$defineTplFileContent);
    }
}

?>
