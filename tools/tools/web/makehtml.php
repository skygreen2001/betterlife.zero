<?php
/**
 * 仅供使用Smarty模板开发使用生成全网站静态
 */
require_once ("../../../init.php");

/**
 * 是否显示耗时信息
 */
Gc::$dev_profile_on=true;
$html_dir=Gc::$nav_root_path."html".DS;
UtilFileSystem::createDir($html_dir);
Gc::$url_base=UtilNet::urlbase();
/**
 * 是否输出返回静态页面信息
 */
Dispatcher::$isOutputStatic=true;
/**
 * 是否在线优化:是否html文本压缩
 */
Gc::$is_online_optimize=true;

if (Gc::$dev_profile_on){
	Profiler::init();
	Profiler::mark("生成首页");
	echo "/".str_repeat("*",40).UtilDateTime::now().":生成首页".str_repeat("*",40)."<br/>";
}
createOneStaticHtmlPage("model.index.index",$html_dir."index.html");
if (Gc::$dev_profile_on){
	Profiler::unmark("生成首页");
}

if (Gc::$dev_profile_on){
	Profiler::show();
}
echo "全部静态页面生成！";

/**
 * 生成单个静态的页面
 * @param mixed $go
 * @param mixed $htmlfilename
 */
function createOneStaticHtmlPage($go,$htmlfilename,$go_param=null)
{
	$htmlcontent=runphp($go,$go_param);
	$htmlcontent=replaceProductDetailLink($htmlcontent);
	file_put_contents($htmlfilename,$htmlcontent);
}

/**
 * 替换商品详情链接
 */
function replaceProductDetailLink($content)
{
	if (!empty($content)){
		$content=preg_replace("/index.php[?]go=model.blog.view&blog_id=(\d+)/i","html/blog_\\1.html",$content);
	}
	return $content;
}

/**
 * 运行动态php程序代码
 * @param mixed $go
 * @param mixed $pararm
 */
function runphp($go,$pararm=null)
{
	$_GET["go"]=$go;
	if (is_string($pararm)){
		$pararm=parse_str($pararm);
	}
	if (is_array($pararm)){
		foreach ($pararm as $key=>$value) {
			$_GET[$key]=$value;
		}
	}
	$result=Dispatcher::dispatch(new Router());
	if (!empty($result)){
		if (Gc::$is_online_optimize){
			if (contain($result,"<body")){
			   /************************start:整个Html页面去除注释，换行，空格********************/
				$result=preg_replace("/<\!--(.*?)-->/","",$result);//去掉html里的注释
				$result = preg_replace("~>\s+\n~",">",$result);
				$result = preg_replace("~>\s+\r~",">",$result);
				$result = preg_replace("~>\s+<~","><",$result);
				$result=str_replace("\r\n","",$result);
			   /************************end  :整个Html页面去除注释，换行，空格********************/
			}
		}
	}
	return $result;
}
?>

<?php
/**
 * 传统的生成全静态网站页面策略:访问网站得到静态html页面另存为指定策略的静态网页文件
 */
/**
require_once ("../../../init.php");
$html_dir=Gc::$nav_root_path."html".DS;
UtilFileSystem::createDir($html_dir);
$url_base=UtilNet::urlbase();
echo "/".str_repeat("*",40)."start:生成首页".str_repeat("*",40)."<br/>";
$htmlcontent=file_get_contents($url_base."index.php?go=model.index.index");
file_put_contents($html_dir."index.html",$htmlcontent);
echo "/".str_repeat("*",40)."end  :生成首页".str_repeat("*",40)."<br/>";
//header("location:".$url_base."html/index.html");
 */
?>