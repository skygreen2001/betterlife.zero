<?php
/* 
 * Edition:    ET091001
 * Desc:    Core Engine 3 (Memcache/Compile/Replace)
 * File:    template.core.php
 * Author:    David Meng
 * Site:    http://www.systn.com
 * Email:    mdchinese@gmail.com
 * 
*/

define("ET3!",TRUE);
class ETCore {
    var $ThisFile    = '';                //当前文件
    var $IncFile    = '';                //引入文件
    var $ThisValue    = array();            //当前数值
    var $FileList    = array();            //载入文件列表
    var $IncList    = array();            //引入文件列表
    var $ImgDir        = array('images');    //图片地址目录
    var $HtmDir        = 'cache_htm/';        //静态存放的目录
    var $HtmID        = '';                //静态文件ID
    var $HtmTime    = '180';            //秒为单位，默认三分钟
    var $AutoImage    = 1;                //自动解析图片目录开关默认值
    var $Hacker        = "<?php defined('ET3!') OR die('You are Hacker!<br>Power by Ease Template!');";
    var $Compile    = array();
    var $Analysis    = array();
    var $Emc        = array();
    /**
     *    声明模板用法
     */
    function ETCoreStart(
            $set = array(
                    'ID'         =>'1',                    //缓存ID
                    'TplType'     =>'htm',                //模板格式
                    'CacheDir'     =>'cache',                //缓存目录
                    'TemplateDir'=>'template' ,            //模板存放目录
                    'AutoImage'     =>'on' ,                //自动解析图片目录开关 on表示开放 off表示关闭
                    'LangDir'     =>'language' ,            //语言文件存放的目录
                    'Language'     =>'default' ,            //语言的默认文件
                    'Copyright'     =>'off' ,                //版权保护
                    'MemCache'     =>'' ,                    //Memcache服务器地址例如:127.0.0.1:11211
                    'Compress'     =>'on' ,                //压缩代码
                    'WebURL'     =>'' ,                    //如果采用路由模式请设定真实网站地址
            )
    ) {

        $this->TplID        = (defined('TemplateID')?TemplateID:( ((int)$set['ID']<=1)?1:(int)$set['ID']) ).'_';

        $this->CacheDir       = (defined('NewCache')?NewCache:( (trim($set['CacheDir']) != '')?$set['CacheDir']:'cache') ).'/';

        $this->TemplateDir    = (defined('NewTemplate')?NewTemplate:( (trim($set['TemplateDir']) != '')?$set['TemplateDir']:'template') ).'/';

        $this->Ext            = (@$set['TplType'] != '')?$set['TplType']:'htm';

        $this->AutoImage    = (@$set['AutoImage']=='off')?0:1;

        $this->Copyright    = (@$set['Copyright']=='off')?0:1;

        $this->Compress        = (@$set['Compress']=='off')?0:1;

        $this->version        = (trim($_GET['EaseTemplateVer']))?die('Ease Templae E3!'):'';

        $this->WebURL        = (@$set['WebURL'] != '')?$set['WebURL']:'';

        if(isset($_SERVER["PATH_INFO"]) && $this->WebURL=='') {
            die(ET_E_routing);
        }

        //载入语言文件
        $this->Language    = (defined('Language')?Language:( (($set['Language']!='default' && $set['Language'])?$set['Language']:'default') ));
        $this->LangDir        = (defined('LangDir')?LangDir:( ((@$set['LangDir']!='language' && @$set['LangDir'])?$set['LangDir']:'language') )).'/';

        if(is_dir($this->LangDir)) {
            if(@is_file($this->LangDir.$this->Language.'.php')) {
                $lang = array();
                include_once $this->LangDir.$this->Language.'.php';
                $this->LangData = $lang;
            }
        }else {
            $this->Language = 'default';
        }


        //缓存目录检测以及运行模式
        if(@ereg(':',$set['MemCache'])) {
            $this->RunType        = 'MemCache';
            if(!FUNCTION_EXISTS('memcache_connect')) {
                die(ET_E_memcache);
            }
            $memset        = explode(":",$set['MemCache']);
            $this->Emc    = memcache_connect($memset[0], $memset[1]) OR die(ET_E_unconnect);
        }else {
            $this->RunType        = (@substr(@sprintf('%o', @fileperms($this->CacheDir)), -3)==777 && is_dir($this->CacheDir))?'Cache':'Replace';
        }

    }


    /**
     *    设置数值
     *    set_var(变量名或是数组,设置数值[数组不设置此值]);
     */
    function set_var(
            $name,
            $value = ''
    ) {
        if (is_array($name)) {
            $this->ThisValue = @array_merge($this->ThisValue,$name);
        }else {
            $this->ThisValue[$name] = $value;
        }
    }


    /**
     *    设置模板文件
     *    set_file(文件名,设置目录);
     */
    function set_file(
            $FileName,
            $NewDir = ''
    ) {
        //当前模板名
        $this->ThisFile  = $FileName.'.'.$this->Ext;

        //目录地址检测
        if(trim($NewDir) != '') {
            $search_tmp    = str_replace('./','',$this->TemplateDir);
            $search_tmp = PREG_REPLACE("/(^[a-z0-9A-Z]{1,100}).+/is","\\1",$search_tmp);
            $this->FileDir[$this->ThisFile] = eregi($search_tmp,$NewDir)?$NewDir.'/':$this->TemplateDir.$NewDir.'/';
        }else {
            $this->FileDir[$this->ThisFile] = $this->TemplateDir;
        }

        $this->IncFile[$FileName]         = $this->FileDir[$this->ThisFile].$this->ThisFile;

        if(!is_file($this->IncFile[$FileName]) && $this->Copyright==1) {
            die(ET_E_not_exist1.$this->IncFile[$FileName].ET_E_not_exist2);
        }

        //bug 系统
        $this->IncList[] = $this->ThisFile;
    }

    //解析替换程序
    function ParseCode($FileList = '', $CacheFile = '') {
        //模板数据
        $ShowTPL = '';
        //解析续载
        if (@is_array($FileList) && $FileList!='include_page') {
            foreach ($FileList AS $K=>$V) {
                $read_file= $this->reader($V.$K);
                $ShowTPL .= $this->ImgCheck($read_file, $V.$K);
            }
        }else {

            //如果指定文件地址则载入
            $SourceFile = ($FileList!='')?$FileList:$this->FileDir[$this->ThisFile].$this->ThisFile;

            if(!is_file($SourceFile) && $this->Copyright==1) {
                die(ET_E_not_exist1.$SourceFile.ET_E_not_exist2);
            }

            $ShowTPL = $this->ImgCheck($this->reader($SourceFile) ,$SourceFile);
        }

        //引用模板处理
        $ShowTPL = $this->inc_preg($ShowTPL);

        //检测run方法
        $run = 0;
        if (eregi("run:",$ShowTPL)) {
            $run     = 1;
            //Fix =
            $ShowTPL = preg_replace('/(\{|<!--\s*)run:(\}|\s*-->)\s*=/','{run:}echo ',$ShowTPL);
            $ShowTPL = preg_replace('/(\{|<!--\s*)run:\s*=/','{run:echo ',$ShowTPL);
            //Fix Run 1
            $ShowTPL = preg_replace('/(\{|<!--\s*)run:(\}|\s*-->)\s*(.+?)\s*(\{|<!--\s*)\/run(\}|\s*-->)/is', '(T_T)\\3;(T_T!)',$ShowTPL);
        }

        //Fix XML
        if (eregi("<?xml",$ShowTPL)) {
            $ShowTPL = @preg_replace('/<\?(xml.+?)\?>/is', '<ET>\\1</ET>', $ShowTPL);
        }

        //修复代码中\n换行错误
        $ShowTPL = str_replace('\\','\\\\',$ShowTPL);
        //修复双引号问题
        $ShowTPL = str_replace('"','\"',$ShowTPL);

        $CompileBasic = array(
                '/(\{\s*|<!--\s*)inc_php:([a-zA-Z0-9_\[\]\.\,\/\?\=\#\:\;\-\|\^]{5,200})(\s*\}|\s*-->)/eis',

                '/<!--\s*DEL\s*-->/is',
                '/<!--\s*IF\s*(\[|\()(.+?)(\]|\))\s*-->/is',
                '/<!--\s*ELSEIF\s*(\[|\()(.+?)(\]|\))\s*-->/is',
                '/<!--\s*ELSE\s*-->/is',
                '/<!--\s*END\s*-->/is',
                '/<!--\s*([a-zA-Z0-9_\$\[\]\'\"\(\)]{2,60})\s*(AS|as)\s*(.+?)\s*-->/',
                '/<!--\s*while\:\s*(.+?)\s*-->/is',

                '/\{ET_Inc\:(.+?),(.+?)\}/eis',
                '/(\{\s*|<!--\s*)lang\:(.+?)(\s*\}|\s*-->)/eis',
                '/(\{\s*|<!--\s*)row\:(.+?)(\s*\}|\s*-->)/eis',
                '/(\{\s*|<!--\s*)color\:\s*([\#0-9A-Za-z]+\,[\#0-9A-Za-z]+)(\s*\}|\s*-->)/eis',
                '/(\{\s*|<!--\s*)run\:(\}|\s*-->)\s*(.+?)\s*(\{|<!--\s*)\/run(\s*\}|\s*-->)/is',
                '/(\{\s*|<!--\s*)run\:(.+?)(\s*\}|\s*-->)/is',
                '/\{([a-zA-Z0-9_\'\"\[\]\$\->]{1,100})\}/',
        );
        $this->Compile = (is_array($this->Compile))?array_merge($this->Compile,$CompileBasic):$CompileBasic;

        $AnalysisBasic = array(
                '$this->inc_php("\\2")',

                '";if($ET_Del==true){echo"',
                '";if(\\2){echo"',
                '";}elseif(\\2){echo"',
                '";}else{echo"',
                '";}echo"',
                '";\$_i=0;foreach(\\1 AS \\3){\$_i++;echo"',
                '";\$_i=0;while(\\1){\$_i++;echo"',

                '$this->ET_Inc("\\1","\\2")',
                '$this->lang("\\2")',
                '$this->Row("\\2")',
                '$this->Color("\\2")',
                '";\\3;echo"',
                '";\\2;echo"',
                '";echo \$\\1;echo"',
        );
        $this->Analysis = (is_array($this->Analysis))?array_merge($this->Analysis,$AnalysisBasic):$AnalysisBasic;

        //编译运算
        $ShowTPL = @preg_replace($this->Compile, $this->Analysis, $ShowTPL);

        //分析图片地址
        //$ShowTPL = $this->ImgCheck($ShowTPL,$SourceFile);

        //Fix 模板中金钱符号
        $ShowTPL = str_replace('$','\$',$ShowTPL);

        //修复php运行错误
        $ShowTPL = @preg_replace("/\";(.+?)echo\"/e", '$this->FixPHP(\'\\1\')', $ShowTPL);

        //Fix Run 2
        if ($run==1) {
            $ShowTPL = preg_replace("/\(T_T\)(.+?)\(T_T!\)/ise", '$this->FixPHP(\'\\1\')', $ShowTPL);
        }

        //还原xml
        $ShowTPL = (ereg('<ET>',$ShowTPL))?@preg_replace('/ET>(.+?)<\/ET/is', '?\\1?', $ShowTPL):$ShowTPL;

        //修复"问题
        $ShowTPL = str_replace('echo ""','echo "\"',$ShowTPL);

        //从数组中将变量导入到当前的符号表
        @extract($this->Value());
        ob_start();
        ob_implicit_flush(0);
        @eval('echo "'.$ShowTPL.'";');
        $contents = ob_get_contents();
        ob_end_clean();

        //Cache htm
        if($this->HtmID) {
            $this->writer($this->HtmDir.$this->HtmID,$this->Hacker."? >".$contents);
        }


        //编译模板
        if ($this->RunType=='Cache') {
            $this->CompilePHP($ShowTPL,$CacheFile);
        }

        //错误检查
        if(strlen($contents)<=0) {
            die(ET_E_not_exist3.$SourceFile.ET_E_not_exist4);
        }
        return $contents;
    }


    /**
     *  多语言
     */
    function lang(
            $str = ''
    ) {
        if (is_dir($this->LangDir)) {
            //采用MD5效验
            $id      = md5($str);
            //不存在数据则写入
            if($this->LangData[$id]=='' && $this->Language=='default') {
                //语言包文件
                if (@is_file($this->LangDir.$this->Language.'.php')) {
                    unset($lang);
                    @include($this->LangDir.$this->Language.'.php');
                }


                //如果检测到有数据则输出
                if ($lang[$id]) {
                    return $lang[$id];
                }

                //修复'多\问题
                $w_str    = str_replace("\\\\","\\\\\\\\", $str);
                $w_str    = str_replace('"','\\\"', $w_str);
                $w_str    = str_replace("\'","'", $w_str);

                //语言文件过大时采取建立新文件
                $docs    = str_replace("\'","'", $str);
                $docs    = str_replace('\\\\','\\', $docs);
                $docs    = str_replace('\\"','"', $docs);
                if(strlen($docs)>400) {
                    $this->writer($this->LangDir.$this->Language.'.'.$id.'.php','<? $etl = "'.$w_str.'";?>');
                    $docs    = substr($docs,0,40);        //简要说明
                    $str    = 'o(O_O)o.ET Lang.o(*_*)o';    //语言新文件
                }

                //文件安全处理
                $data = (!is_file($this->LangDir.'default.php'))?"<?\n/**\n/* SYSTN ET Language For ".$this->Language."\n*/\n\n\n":'';


                if (trim($str)) {
                    //写入数据
                    $data.= "/**".date("Y.m.d",time())."\n";
                    $data.= $docs."\n";
                    $data.= "*/\n";
                    $data.= '$lang["'.$id.'"] = "'.$w_str.'";'."\n\n";
                    $this->writer($this->LangDir.'default.php',$data,'a+');
                }
            }
            //单独语言文件包
            if($this->LangData[$id]=='o(O_O)o.ET Lang.o(*_*)o') {
                unset($etl);
                include($this->LangDir.$this->Language.".".$id.".php");
                $this->LangData[$id] = $etl;
            }
            return ($this->LangData[$id]!='')?$this->LangData[$id]:$str;
        }else {
            //修复'多\问题
            return str_replace("\'","'", $str);
        }
    }

    /**
     *  inc引用函数
     */
    function inc_preg(
            $content
    ) {
        return preg_replace('/<\!--\s*\#include\s*file\s*=(\"|\')([a-zA-Z0-9_\.\|\/]{1,100})(\"|\')\s*-->/eis', '$this->inc("\\2")', preg_replace('/(\{\s*|<!--\s*)inc\:([^\{\} ]{1,100})(\s*\}|\s*-->)/eis', '$this->inc("\\2")', $content));
    }


    /**
     *  引用函数运算
     */
    function inc(
            $Files = ''
    ) {
        if($Files) {
            if (!strrpos($Files,$this->Ext)) {
                $Files    = $Files.".".$this->Ext;
            }
            $FileLs        = $this->TemplateDir.$Files;
            $contents    =$this->ParseCode($FileLs,$Files);

            if($this->RunType=='Cache') {
                $this->FileDir[$Files] = $this->TemplateDir;
                //引用模板
                $this->IncList[] = $Files;
                $cache_file = $this->FileName($Files,$this->TplID);
                return "{ET_Inc:".base64_encode($this->TemplateDir).",".base64_encode($Files)."}
<!-- IF(@is_file('".$cache_file."')) -->{inc_php:".$cache_file."}
<!-- IF(\$EaseTemplate3_Cache) -->{run:@eval('echo \"'.\$EaseTemplate3_Cache.'\";')}<!-- END -->
<!-- END -->";
            }elseif($this->RunType=='MemCache') {
                //cache date
                memcache_set($this->Emc,$Files.'_date', time()) OR die(ET_E_mc_save);
                memcache_set($this->Emc,$Files, $contents) OR die(ET_E_mc_save);
                return "<!-- ET_inc_cache[".$Files."] -->".$contents;
            }else {
                //引用模板
                $this->FileDir[$Files] = $this->TemplateDir;
                $this->IncList[] = $Files;
                return $contents;
            }
        }
    }


    /**
     *  编译解析处理
     */
    function CompilePHP(
            $content='',
            $cachename = ''
    ) {
        if ($content) {
            //如果没有安全文件则自动创建
            if($this->RunType=='Cache' && !is_file($this->CacheDir.'index.htm')) {
                $Ease_name   = 'Ease Template!';
                $Ease_base   = "<title>$Ease_name</title><a href='http://www.systn.com'>$Ease_name</a>";
                $this->writer($this->CacheDir.'index.htm',$Ease_base);
                $this->writer($this->CacheDir.'index.html',$Ease_base);
                $this->writer($this->CacheDir.'default.htm',$Ease_base);
            }


            //编译记录
            $content = str_replace("\\","\\\\",$content);
            $content = str_replace("'","\'",$content);
            $content = str_replace('echo"";',"",$content);        //替换多余数据
            $wfile = ($cachename)?$cachename:$this->ThisFile;

            $this->writer($this->FileName($wfile,$this->TplID) ,$this->Hacker.'$EaseTemplate3_Cache = \''.$content.'\';');
        }
    }


    //修复PHP执行时产生的错误
    function FixPHP(
            $content=''
    ) {
        $content = str_replace('\\\\','\\',$content);
        return '";'.str_replace('\\"','"',str_replace('\$','$',$content)).'echo"';
    }


    /**
     *  检测缓存是否要更新
     *    filename    缓存文件名
     *    settime        指定事件则提供更新，只用于memcache
     */
    function FileUpdate($filname,$settime=0) {

        //检测设置模板文件
        if (is_array($this->IncFile)) {
            unset($k,$v);
            $update        = 0;
            $settime    = ($settime>0)?$settime:@filemtime($filname);
            foreach ($this->IncFile AS $k=>$v) {
                if (@filemtime($v)>$settime) {
                    $update = 1;
                }
            }
            //更新缓存
            if($update==1) {
                return false;
            }else {
                return $filname;
            }

        }else {
            return $filname;
        }
    }


    /**
     *    输出运算
     *   Filename    连载编译输出文件名
     */
    function output($Filename = '') {
        switch($this->RunType) {

            //Mem编译模式
            case'MemCache':
                if ($Filename=='include_page') {
                    //直接输出文件
                    $contents    = $this->reader($this->FileDir[$this->ThisFile].$this->ThisFile);
                }else {

                    $FileNames    = ($Filename)?$Filename:$this->ThisFile;
                    $CacheFile    = $this->FileName($FileNames,$this->TplID);

                    //检测记录时间
                    $updateT    = memcache_get($this->Emc,$CacheFile.'_date');
                    $update        = $this->FileUpdate($CacheFile,$updateT);

                    $CacheData = memcache_get($this->Emc,$CacheFile);

                    if(trim($CacheData) && $update) {
                        //Close
                        memcache_close($this->Emc);
                        $contents    = $CacheData;
                    }else {
                        if ($Filename) {
                            $CacheData    = $this->ParseCode($this->FileList,$Filename);
                            //cache date
                            @memcache_set($this->Emc,$CacheFile.'_date', time()) OR die(ET_E_mc_save);
                            @memcache_set($this->Emc,$CacheFile, $CacheData) OR die(ET_E_mc_save);
                            //Close
                            memcache_close($this->Emc);
                            $contents    = $CacheData;
                        }else {
                            $CacheData    = $this->ParseCode();
                            //cache date
                            @memcache_set($this->Emc,$CacheFile.'_date', time()) OR die(ET_E_mc_save);
                            @memcache_set($this->Emc,$CacheFile, $CacheData) OR die(ET_E_mc_save);
                            //Close
                            memcache_close($this->Emc);
                            $contents    = $CacheData;
                        }
                    }
                }
                break;


            //编译模式
            case'Cache':
                if ($Filename=='include_page') {
                    //直接输出文件

                    $contents    = $this->reader($this->FileDir[$this->ThisFile].$this->ThisFile);
                }else {

                    $FileNames    = ($Filename)?$Filename:$this->ThisFile;
                    $CacheFile    = $this->FileName($FileNames,$this->TplID);

                    $CacheFile    = $this->FileUpdate($CacheFile);
                    if (@is_file($CacheFile)) {
                        @extract($this->Value());
                        ob_start();
                        ob_implicit_flush(0);
                        include $CacheFile;
                        //获得列表文件
                        if($EaseTemplate3_Cache!='') {
                            @eval('echo "'.$EaseTemplate3_Cache.'";');
                            $contents = ob_get_contents();
                            ob_end_clean();
                            $contents    = $contents;
                        }
                    }else {
                        if ($Filename) {
                            $contents    = $this->ParseCode($this->FileList,$Filename);
                        }else {
                            $contents    = $this->ParseCode();
                        }
                    }
                }

                break;


            //替换引擎
            default:
                if($Filename) {
                    if ($Filename=='include_page') {
                        //直接输出文件
                        $contents    = $this->reader($this->FileDir[$this->ThisFile].$this->ThisFile);
                    }else {
                        $contents    = $this->ParseCode($this->FileList);
                    }
                }else {
                    $contents    = $this->ParseCode();
                }
        }

        //代码压缩
        if($this->Compress==1) {
            if (!empty($content)) {
                $content    = preg_replace("~>\s+\r~", ">", preg_replace("~>\s+\n~", ">", $content));
                $content    = preg_replace("~>\s+<~", "><", $content);
            }
            $contents    = str_replace("\r\n\r\n\r\n", "\r\n", $contents);
            $contents    = str_replace("\r\n\r\n", "\r\n", $contents);
        }
        return $contents;

    }


    /**
     *  连载函数
     */
    function n() {
        //连载模板
        $this->FileList[$this->ThisFile] = $this->FileDir[$this->ThisFile];
    }


    /**
     *    输出模板内容
     *   Filename    连载编译输出文件名
     */
    function r(
            $Filename = ''
    ) {
        return $this->output($Filename);
    }


    /**
     *    打印模板内容
     *   Filename    连载编译输出文件名
     */
    function p($Filename = '') {
        echo $this->output($Filename);
    }


    /**
     *    分析图片地址
     *   content 分析内容
     *   fileadds 文件名
     */
    function ImgCheck($content,$fileadds='') {
        //Check Image Dir
        if($this->AutoImage==1) {
            $file_dir    = dirname($fileadds);

            if(isset($_SERVER["PATH_INFO"])) {
                $file_dir    = $this->WebURL.$file_dir;
            }

            //增加替换目录
            $content = PREG_REPLACE('/(\{\s*|<!--\s*)dir\:([^\{\}]{1,100})(\s*\}|\s*-->)/eis',
                    '$this->Dirs("\\2")', $content);

            if(is_array($this->ImgDir)) {
                foreach($this->ImgDir AS $rep) {
                    $rep = trim($rep);
                    //检测是否执行替换
                    if(strrpos($content,$rep."/")) {
                        if(substr($rep,-1)=='/') {
                            $rep = substr($rep,0,strlen($rep)-1);
                        }
                        $content = str_replace($rep.'/',$file_dir.'/'.$rep.'/',$content);
                    }
                }
            }
        }
        return $content;
    }


    /**
     *    获得所有设置与公共变量
     */
    function Value() {
        return (is_array($this->ThisValue))?array_merge($this->ThisValue,$GLOBALS):$GLOBALS;
    }


    /**
     *    清除设置
     */
    function clear() {
        $this->RunType = 'Replace';
    }

    /**
     *    清除设置
     */
    function ET_Inc($dirs='',$files='') {
        return '";$this->FileDir[\''.BASE64_DECODE($files).'\'] = \''.BASE64_DECODE($dirs).'\'; echo "';
    }


    /**
     *  静态文件写入
     */
    function htm_w(
            $w_dir = '',
            $w_filename = '',
            $w_content = ''
    ) {

        $dvs  = '';
        if($w_dir && $w_filename && $w_content) {
            //目录检测数量
            $w_dir_ex  = explode('/',$w_dir);
            $w_new_dir = '';    //处理后的写入目录
            unset($dvs,$fdk,$fdv,$w_dir_len);
            foreach((array)$w_dir_ex AS $dvs) {
                if(trim($dvs) && $dvs!='..') {
                    $w_dir_len .= '../';
                    $w_new_dir .= $dvs.'/';
                    if (!@is_dir($w_new_dir)) @mkdir($w_new_dir, 0777);
                }
            }


            //获得需要更改的目录数
            foreach((array)$this->FileDir AS $fdk=>$fdv) {
                $w_content = str_replace($fdv,$w_dir_len.str_replace('../','',$fdv),$w_content);
            }
            $this->writer($w_dir.$w_filename,$w_content);
        }
    }


    /**
     *  改变静态刷新时间
     */
    function htm_time($times=0) {
        if((int)$times>0) {
            $this->HtmTime = (int)$times;
        }
    }


    /**
     *  静态文件存放的绝对目录
     */
    function htm_dir($Name = '') {
        if(trim($Name)) {
            $this->HtmDir = trim($Name).'/';
        }
    }


    /**
     *  产生静态文件输出
     */
    function HtmCheck(
            $Name = ''
    ) {

        $Name            = trim($Name);
        $this->HtmID    = md5($Name ? $Name : $_SERVER['REQUEST_URI']) . '.php';
        $file_adds        = $this->HtmDir.$this->HtmID;

        //检测时间
        if(is_file($file_adds) && (time() - @filemtime($file_adds)<=$this->HtmTime)) {
            ob_start();
            ob_implicit_flush(0);
            include $file_adds;
            $HtmContent = ob_get_contents();
            ob_end_clean();
            return $HtmContent;
        }
    }


    /**
     *  打印静态内容
     */
    function htm_p(
            $Name = ''
    ) {
        $output = $this->HtmCheck($Name);
        if ($output) {
            die($output);
        }
    }


    /**
     *  输出静态内容
     */
    function htm_r(
            $Name = ''
    ) {
        return $this->HtmCheck($Name);
    }





    /**
     *    解析文件
     */
    function FileName(
            $name,
            $id = '1'
    ) {
        $extdir = explode("/",$name);
        $dirnum = @count($extdir) - 1;

        if($dirnum>0) {
            if(!is_dir($this->CacheDir.'/'.$dirnum)) {
                @mkdir($this->CacheDir.'/'.$dirnum,0777);    //建立多级缓存目录
            }
            return $this->CacheDir.$dirnum.'/'.$id.str_replace("/",',',$name).".".$this->Language.'.php';
        }
        return $this->CacheDir.$id.$name.".".$this->Language.'.php';
    }


    /**
     *  检测引入文件
     */
    function inc_php(
            $url = ''
    ) {
        $parse    = parse_url($url);
        unset($vals,$code_array);
        foreach((array)explode('&',$parse['query']) AS $vals) {
            $code_array .= preg_replace('/(.+)=(.+)/',"\$_GET['\\1']= \$\\1 ='\\2';",$vals);
        }
        return '";'.$code_array.' @include(\''.$parse['path'].'\');echo"';
    }


    /**
     *    换行函数
     *    Row(换行数,换行颜色);
     *    Row("5,#ffffff:#e1e1e1");
     */
    function Row(
            $Num = ''
    ) {
        $Num = trim($Num);
        if($Num != '') {
            $Nums  = explode(",",$Num);
            $Numr  = ((int)$Nums[0]>0)?(int)$Nums[0]:2;
            $input = (trim($Nums[1]) == '')?'</tr><tr>':$Nums[1];

            if(trim($Nums[1]) != '') {
                $Co         = explode(":",$Nums[1]);
                $OutStr = "if(\$_i%$Numr===0){\$row_count++;echo(\$row_count%2===0)?'</tr><tr bgcolor=\"$Co[0]\">':'</tr><tr bgcolor=\"$Co[1]\">';}";
            }else {
                $OutStr = "if(\$_i%$Numr===0){echo '$input';}";
            }
            return '";'.$OutStr.'echo "';
        }
    }


    /**
     *    间隔变色
     *    Color(两组颜色代码);
     *    Color('#FFFFFF,#DCDCDC');
     */
    function Color(
            $color = ''
    ) {
        if($color != '') {
            $OutStr = preg_replace("/(.+),(.+)/","_i%2===0)?'\\1':'\\2';",$color);
            if(strrpos($OutStr,"%2")) {
                return '";echo(\$'.$OutStr.'echo "';
            }
        }
    }


    /**
     *    映射图片地址
     */
    function Dirs(
            $adds = ''
    ) {
        $adds_ary = explode(",",$adds);
        if(is_array($adds_ary)) {
            $this->ImgDir = (is_array($this->ImgDir))?@array_merge($adds_ary, $this->ImgDir):$adds_ary;
        }
    }


    /**
     *    读取函数
     *    reader(文件名);
     */
    function reader(
            $filename
    ) {
        return file_get_contents($filename);
    }


    /**
     *    写入函数
     *    writer(文件名,写入数据, 写入数据方式);
     */
    function writer(
            $filename,
            $data = '',
            $mode='w'
    ) {
        if(trim($filename)) {
            $file = @fopen($filename, $mode);
            @fwrite($file, $data);
            @fclose($file);
        }
        if(!is_file($filename)) {
            die(ET_E_not_write1.$filename.ET_E_not_write2);
        }
    }


    /**
     *    建立目录
     *    create_dir(建立文件夹的路径，支持多级目录);
     */
    function create_dir($dir_adds='') {
        $falg = true;
        $dir_adds  = trim($dir_adds);
        if($dir_adds!='') {
            $dir_adds = str_replace(array('//','\\','\\\\'),'/',$dir_adds);
            if (!is_dir($dir_adds)) {
                $temp = explode('/',$dir_adds);
                $cur_dir = '';
                for($i=0;$i<count($temp);$i++) {
                    $cur_dir .= $temp[$i].'/';
                    if (!@is_dir($cur_dir)) {
                        if(!@mkdir($cur_dir,0777))
                            $falg = false;
                    }
                }
            }
            return $falg;
        }
    }


    /**
     *    删除目录及目录下所有文件
     *    del_dir(删除的路径,1表示删除目录下数据，0默认删除本目录);
     */
    function del_dir($dir_adds='',$del_def=0) {
        $result = false;
        if(! is_dir($dir_adds)) {
            return false;
        }
        $handle = opendir($dir_adds);
        while(($file = readdir($handle)) !== false) {
            if($file != '.' && $file != '..') {
                $dir = $dir_adds . DIRECTORY_SEPARATOR . $file;
                is_dir($dir) ? $this->del_dir($dir) : unlink($dir);
            }
        }
        closedir($handle);
        if($del_def==0) {
            $result = rmdir($dir_adds) ? true : false;
        }else {
            $result = true;
        }
        return $result;
    }


    /**
     *    引入模板系统
     *    察看当前使用的模板以及调试信息
     *    $mode 默认为直接显示，设置任意值将为return代码
     */
    function inc_list($mode='print') {
        //清除缓存 START
        if(strrpos($_SERVER['REQUEST_URI'],'Ease_Templatepage=Clear')) {
            if(file_exists($this->CacheDir)) {
                $this->del_dir($this->CacheDir,1);
            }
            $EXTS = explode("/",$_SERVER['REQUEST_URI']);
            $Last = count($EXTS) -1;
            header("Location: ".urldecode( urldecode( preg_replace("/.+?REFERER=(.+?)!!!/","\\1",$EXTS[$Last]) ) ));
            exit;
        }
        //清除缓存 END

        if(is_array($this->FileDir)) {
            $list_file    = array();
            $file_nums    = count($this->FileDir);

            foreach($this->FileDir AS $K=>$V) {
                $File_Size   = @round(@filesize($V.$K) / 1024 * 100) / 100 . 'KB';
                if(isset($_SERVER["PATH_INFO"])) {
                    if($this->WebURL!='') {
                        $links     = "<a href='".$this->WebURL.$V.$K."' target='_blank'>";
                    }
                }else {
                    $links     = "<a href='".$V.$K."' target='_blank'>";
                }

                $list_file[] = "<tr><td colspan=\"2\" align=\"left\" bgcolor=\"#F7F7F7\">".$links."<font color='#6F7D84' style='font-size:14px;'>".$V.$K."</font></a><font color='#B4B4B4' style='font-size:10px;'>&nbsp;&nbsp;".$File_Size."</font></td></tr>";
            }

            //路由连接地址
            if(isset($_SERVER["PATH_INFO"])) {
                $BackURL = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . $_SERVER['PATH_INFO'];
                ;
            }else {
                $BackURL = preg_replace("/.+\//","\\1",$_SERVER['REQUEST_URI']);
            }
            $NowPAGE = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];

            $clear_link = $NowPAGE."?Ease_Templatepage=Clear&REFERER=".urlencode($BackURL)."!!!";
            $sf13    = ' style="font-size:13px;color:#666666"';
            $result     = '<br><table border="1" width="960" align="center" cellpadding="3" style="border-collapse: collapse" bordercolor="#DCDCDC">
<tr bgcolor="#B5BDC1"><td align="left"><font color=#000000 style="font-size:16px;"><b>'.ET_E_inc_tpl.count($this-> FileDir).')</b></font></td>
<td align="right">';

            if($this->RunType=='Cache') {
                $result.= '[<a onclick="alert(\''.ET_E_cache_del.'\');location=\''.$clear_link.'\';return false;" href="'.$clear_link.'"><font'.$sf13.'>'.ET_E_clear_cache.'</font></a>]';
            }

            $result.= '</td></tr><tr><td colspan="2" bgcolor="#F7F7F7"><table border="0" width="100%" cellpadding="0" style="border-collapse: collapse">
<tr><td'.$sf13.'>'.ET_E_cache_id.': <b>'.substr($this->TplID,0,-1).'</b></td>
<td'.$sf13.'>'.ET_E_index.' <b>'.((count($this->FileList)==0)?'False':'True').'</b></td>
<td'.$sf13.'>'.ET_E_format.' <b>'.$this->Ext.'</b></td>
<td'.$sf13.'>'.ET_E_cache.' <b>'.($this->RunType=='MemCache'?'Memcache Engine':($this->RunType == 'Replace'?'Replace Engine':$this->CacheDir)).'</b></td>
<td'.$sf13.'>'.ET_E_template.' <b>'.$this->TemplateDir.'</b></td></tr>
</table></td></tr>'.implode("",$list_file)."</table><br>";
        }

        if($mode!='print') {
            return $result;
        }
        echo $result;
    }

}

?>