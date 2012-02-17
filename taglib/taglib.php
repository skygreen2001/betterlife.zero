<?php
/**
 * Refer:http://bbs.blueidea.com/archiver/tid-2965257.html
 */
ob_start("render_tag");                      
/**
 * need handle the tags
 * $buffer = preg_replace_callback('/<fb:(\w+-\w+)((\s+\w+=\\\"\w+\\\"|\s+\w+=\"\w+\"|\s+\w+=\\\\\'\w+\\\\\'|\s+\w+=\'\w+\')*)>(.+?)<\/fb:\1>/is', 'parseTag', $buffer);
 * [note:]
 *       Because the coder can write the code with the following matched string by chance;so I must write the complicated regExp;
 *       I explain it by the following notes;
 *          Expression    :<regex>/<fb:(\w+-\w+)((\s+\w+=\\\\\'\w+\\\\\')*)>(.+?)<\/fb:\1>/is</regex>
 *          Matched String:<fb:js-string var1=\'ttt\' var2=\'666\'></fb:js-string>
 *          Expression    :<regex>/<fb:(\w+-\w+)((\s+\w+=\'\w+\')*)>(.+?)<\/fb:\1>/is</regex>
 *          Matched String:<fb:js-string var1='ttt' var2='666'></fb:js-string>
 *          Expression    :<regex>/<fb:(\w+-\w+)((\s+\w+=\\\"\w+\\\")*)>(.+?)<\/fb:\1>/is</regex>
 *          Matched String:<fb:js-string var1=\"ttt\" var2=\"666\"></fb:js-string>
 *          Expression    :<regex>/<fb:(\w+-\w+)((\s+\w+=\"\w+\")*)>(.+?)<\/fb:\1>/is</regex>
 *          Matched String:<fb:js-string var1="ttt" var2="666"></fb:js-string>
 *      $buffer = preg_replace_callback('/<fb:(\w+-\w+)>(.+?)<\/fb:\1>/is', 'parseTag', $buffer);
 * @param $buffer
 * @return need handle the tags,html written the html page later.
 */
function render_tag($buffer) {
	$result =$buffer;
	if (!empty($buffer)){
		if(stristr($buffer,"<".TagClass::PREFIX.":")) {
	//        $repReg='/<'.TagClass::PREFIX.':(\w+[-]?\w+)((\s+\w+=\\\"\w+\\\"|\s+\w+=\"\w+\"|\s+\w+=\\\\\'\w+\\\\\'|\s+\w+=\'\w+\')*)\s*>(.*)<\/'.TagClass::PREFIX.':\1>/isU';

	//        $repReg='/<'.TagClass::PREFIX.':(\w+[-]?\w+)((\s+\w+=\\\"[^"]*\\\"|\s+\w+=\"[^"]*\"|\s+\w+=\\\\\'[^"]*\\\\\'|\s+\w+=\'[^"]*\')*)\s*>(.*)<\/'.TagClass::PREFIX.':\1>/isU';
			/**
			 * 处理自定义标签，如下形式：
			 * <my:page src="index.php?g=betterlife&m=blog&a=display">this is page 5</my:page>
			 * @var mixed
			 */
			$repReg='/<'.TagClass::PREFIX.':(\w+[-]?\w*)((\s+\w+=\\\"[^"\']*\\\"|\s+\w+=\"[^"\']*\"|\s+\w+=\\\\\'[^"\']*\\\\\'|\s+\w+=\'[^"\']*\')*)\s*>(.*)<\/'.TagClass::PREFIX.':\1>/isU';
			$result = preg_replace_callback($repReg, 'parseTag', $buffer);
			/**
			 * 处理自定义标签，如下形式：
			 * <my:page src="index.php?g=betterlife&m=blog&a=display" />
			 * @var mixed
			 */
			$repReg='/<'.TagClass::PREFIX.':(\w+[-]?\w*)((\s+\w+=\\\"[^"]*\\\"|\s+\w+=\"[^"]*\"|\s+\w+=\\\\\'[^"]*\\\\\'|\s+\w+=\'[^"]*\')*)\s*[\/]{1}>/isU';
			$result = preg_replace_callback($repReg, 'parseTag', $result);
		}
	}
	if (Gc::$is_online_optimize){
		if (contain($result,"<body")){
		   /************************start:整个Html页面去除注释，换行，空格********************/ 
//            $result=preg_replace("/<\!--(.*?)-->/","",$result);//去掉html里的注释
//            $result = preg_replace("~>\s+\n~",">",$result);   
//            $result = preg_replace("~>\s+\r~",">",$result);
//            $result = preg_replace("~>\s+<~","><",$result);              
//            $result=str_replace("\r\n","",$result); 
		   /************************end  :整个Html页面去除注释，换行，空格********************/
		   /************************start:Html页面body部分去除注释，换行，空格********************/
			$r_arr=explode("<body",$result);
			$r_arr[1]=str_replace("\r\n","",$r_arr[1]);      
			$r_arr[1]=preg_replace("/<\!--(.*?)-->/","",$r_arr[1]);//去掉html里的注释
			$r_arr[1] = preg_replace("~>\s+\n~",">",$r_arr[1]);   
			$r_arr[1] = preg_replace("~>\s+\r~",">",$r_arr[1]);
			$r_arr[1] = preg_replace("~>\s+<~","><",$r_arr[1]);                    
			$result=implode("<body",$r_arr);  
			if (contain($result,"</html>")){
				$result=str_replace("</html>","",$result);    
				$result.="\r\n</html>";
			}
		   /************************end  :Html页面body部分去除注释，换行，空格********************/  
		}                                    
	}    
	return $result;
}

/**
 *
 * @param $matches
 *        $matches[1]:the tag name except the prefix name;
 *        $matches[2]:the tag attribute content;
 *        $matches[3]:the content in the tag
 * @return html written the html page.
 */
function parseTag($matches) {
	$tagname=$matches[1];
	if (count($matches)>=4){
		switch ($tagname) {
			case 'demo':
				$content=isset($matches[4])?$matches[4]:"";
				$invokeTag=new TagDemoClass($matches[1],$matches[2],$content);
				break;
			case 'page':   
				$content=isset($matches[4])?$matches[4]:"";
				$invokeTag=new TagPageClass($matches[1],$matches[2],$content);
				break;
			case 'a':   
				$content=isset($matches[4])?$matches[4]:"";
				$invokeTag=new TagHrefClass($matches[1],$matches[2],$content);
				break;
			default:
				return "undefined tag：$invokeTag->getTagName()";
		}
		return $invokeTag->getHtml();
	}else{
		return "";
	}
}
?>