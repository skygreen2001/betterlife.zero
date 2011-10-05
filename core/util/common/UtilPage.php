<?php
/**
 +---------------------------------<br/>
 * 自定义标签：分页工具类<br/>
 +---------------------------------
 * @category betterlife
 * @package util.common
 * @author skygreen
 */
class UtilPage {
    /**
     * 在分页导航条里链接地址里带有的当前页数标识符
     *
     */
    public static $linkUrl_pageFlag="pageNo";
    /**
     * 在分页标签里显示的分页页码数
     *
     * @var mixed
     */
    private static $tag_viewpagecount=10;
    private $count = 10; // 记录总数
    private $allPageCount;//总页数
    private $nowpage = 1; // 当前页数
    private $pageSize; // 每页显示记录数
    private $startPoint;//分页开始记录数
    private $endPoint;//分页结束记录数

    private  $linkUrl;// 导航地址

    private  $navig;// 导航条
    private  $navigTo;// 导航条
                                 
    public static function Init($nowpage,$count,$pageSize=3,$linkUrl=null){
        return new UtilPage($nowpage,$count,$pageSize,$linkUrl);
    }  
                                 
    /**
     *
     * @param pageSize
     *            :每页显示记录数
     * @param nowpage
     *            :当前页数
     * @param count
     *            :记录总数
     */
    function __construct($nowpage,$count,$pageSize=3,$linkUrl=null) 
    {
        if (empty($linkUrl)){         
          $linkUrl=@$_SERVER['HOST'].@$_SERVER['REQUEST_URI'];
        }
        if (empty($nowpage)) {
            $nowpage=1;
        }
        
        $this->count=$count;
        $this->pageSize = $pageSize;
        $this->nowpage = $nowpage;
        // 总页数
        $this->allPageCount = floor(($this->count + $this->pageSize - 1) / $this->pageSize);
        if ($this->nowpage>$this->allPageCount){
            $this->nowpage=$this->allPageCount;
        }        
        if ($this->count>0) {    
            $this->startPoint=($this->nowpage-1)*$this->pageSize+1;
            if ($this->startPoint>$this->count) {  
              $this->startPoint=0;      
            }
        }else{
            $this->startPoint= 0;
        }

        $this->endPoint=$this->nowpage*$this->pageSize;
        if ($this->endPoint>$this->count) {
            $this->endPoint=$this->count;
        }

        $this->linkUrl = $linkUrl;
        $_SESSION[TagPageClass::$tag_page_sessionname]=$this; 
    }

    /**
     * 查看URL链接里是否已经有参数带有?
     * 1.如果有，则加后缀&pageno=分页数
     * 2.如果没有，则加后缀?pageno=分页数
     * @param mixed $this->linkUrl
     */
    private function url_link_pageparam($pageNo) 
    {                                 
        if (UtilString::contain($this->linkUrl,"?")) {
            $result="<a href='$this->linkUrl&".self::$linkUrl_pageFlag."=$pageNo'>";
        }else {
            $result="<a href='$this->linkUrl?".self::$linkUrl_pageFlag."=$pageNo'>";
        }
        return $result;
    }

    /**
     * 获取当前页开始记录数
     *
     */
    public function getStartPoint() 
    {
        return $this->startPoint;
    }

    /**
     * 获取当前页结束记录数
     *
     */
    public function getEndPoint() 
    {
        return $this->endPoint;
    }

    /**
     * 生成导航条供页面使用
     *
     * @param somedo
     * @return
     */
    public function createBaseNavi() 
    {
        if ($this->count < $this->pageSize) {
            $this->navig = "";
            return "";
        }

        if (!UtilString::contain($this->linkUrl,"?")) {
            $this->linkUrl.= "?";
        }

        $stb = "";
        // 总页数
        $this->allPageCount = floor(($this->count + $this->pageSize - 1) / $this->pageSize);

        // 首页
        if ($this->nowpage > 1) {
            $stb.=$this->url_link_pageparam(1);
            $stb.="首页</a> ";
        } else {
            $stb.="首页";
        }

        // 上一页
        if ($this->nowpage > 1) {
            $stb.=$this->url_link_pageparam($this->nowpage - 1);
            $stb.="上一页</a> ";
        } else {
            $stb.="上一页";
        }

        // 显示页码
        $showNo = self::$tag_viewpagecount;
        if ($this->allPageCount > $showNo) {
            if ($this->nowpage > floor($showNo / 2)) {
                $stb.="...";
            }
        }
        if ($this->allPageCount > 1) {
            $startShowNo = $this->nowpage - floor($showNo / 2)+1;
            $endShowNo = $this->nowpage + floor($showNo / 2);
            if ($startShowNo < 1) {
                $endShowNo =  $showNo;//$this->nowpage +
            }
            if ($endShowNo > $this->allPageCount) {
                $startShowNo = $this->allPageCount - $showNo+1;
            }
            if ($endShowNo > $this->allPageCount) {
                $endShowNo = $this->allPageCount;
            }
            if ($startShowNo < 1) {
                $startShowNo = 1;
            }
            if ($this->allPageCount <= $showNo) {
                $startShowNo = 1;
                $endShowNo = $this->allPageCount;
            }

            for ($i= $startShowNo; $i<= $endShowNo; $i++) {
                if ($i == $this->nowpage) {
                    $stb.=$i."&nbsp;";
                } else {
                    $stb.=$this->url_link_pageparam($i);
                    $stb.=$i."</a>&nbsp;";
                }
            }
        }

        if ($this->allPageCount > $showNo)
            if ($this->nowpage<($this->allPageCount-floor($showNo / 2)))
                $stb.="...";

        // 下一页
        if ($this->nowpage < floor(($this->count + $this->pageSize - 1) / $this->pageSize)) {
            $stb.=$this->url_link_pageparam($this->nowpage + 1);
            $stb.="下一页</a>";
        } else {
            $stb.="下一页 ";
        }

        // 末页
        if ($this->nowpage < floor(($this->count + $this->pageSize - 1) / $this->pageSize)) {
            $stb.=$this->url_link_pageparam(floor(($this->count + $this->pageSize - 1) / $this->pageSize));
            $stb.="末页</a> "; // 共x页
        } else {
            $stb.="末页 ";
        }

        return $stb;
    }

    private function viewStatistic() 
    {
        $stb="<br />";
        $stb.="当前：第&nbsp;";
        $stb.=$this->getNowpage();
        $stb.="&nbsp;页";
        $stb.="&nbsp;&nbsp;&nbsp;&nbsp;共计：&nbsp;";
        $stb.=$this->allPageCount;
        $stb.="&nbsp;页";
        $stb.="&nbsp;&nbsp;&nbsp;&nbsp;总记录：";
        $stb.=$this->getCount();
        $stb.="&nbsp;条";
        return $stb;
    }

    private function createNavig() 
    {
        $this->navig = $this->createBaseNavi();
        return $this->navig.$this->viewStatistic();
    }

    private function createNavigTo() 
    {
        $this->navigTo=$this->createBaseNavi();
        $stb="";
        //当某个分页板块内容超过10页以上，那么允许显示跳转到指定页功能
        if ($this->allPageCount>11) {
            $stb.="<form id='bb_page' name='bb_page' action='".$this->getLinkUrl()."'>";
            $stb.="跳转至：";
            $stb.="<input type='text' name='pageNo' id='".self::$linkUrl_pageFlag."'";
            $stb.=" size='3'";
            $stb.=" lang='3'";
            $stb.=" maxlength='3' />";
            $stb.="<a href=\"javascript:";    
            $stb.="var topage=document.getElementById('".self::$linkUrl_pageFlag."').value;";
            $stb.="if(!(/^\d+$/.test(topage)))";
            $stb.="{";
            $stb.="alert('需要输入数字');";
            $stb.="}else{";                     
            $stb.="document.forms.bb_page.setAttribute('action','".$this->getLinkUrl()."&".self::$linkUrl_pageFlag."='+topage);";        
            $stb.="document.forms.bb_page.setAttribute('method','post');";
            $stb.="document.forms.bb_page.submit();";                
//            $stb.="document.forms.bb_page.".self::$linkUrl_pageFlag.".value='';";                     
            $stb.="}";
            $stb.="\">&nbsp;跳转&nbsp;</a>&nbsp;";
            $stb.="</form>";
        }
        return $this->navigTo.$stb.$this->viewStatistic();
    }

    public function getCount() 
    {
        return $this->count;
    }

    public function getNavig() 
    {
        $this->navig=$this->createNavig();
        return $this->navig;
    }

    public function getNowpage() 
    {
        return $this->nowpage;
    }

    public function getPageSize() 
    {
        return $this->pageSize;
    }

    public function setCount($count) 
    {
        $this->count = $count;
    }

    public function setNowpage($nowpage) 
    {
        $this->nowpage = $nowpage;
    }

    public function setPageSize($pageSize) 
    {
        $this->pageSize = $pageSize;
    }

    /**
     * wap使用替换空格
     */
    public function wapReplace() 
    {
        $this->navig = str_replace("&nbsp;", "  ",navig);
    }   

    public function getNavigTo() 
    {
        $this->navigTo=$this->createNavigTo();
        return $this->navigTo;
    }

    public function getLinkUrl() 
    {
        return $this->linkUrl;
    }

    public function setLinkUrl($linkUrl) 
    {
        $this->linkUrl = $linkUrl;
    }

    public function setNavig($navig) 
    {
        $this->navig = $navig;
    }

}
?>
