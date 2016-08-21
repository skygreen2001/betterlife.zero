<?php
/**
 +--------------------------------<br/>
 * 定义 xhEditor 在线编辑器<br/>
 +--------------------------------
 * @category betterlife
 * @package util.view
 * @subpackage onlinediotr
 * @author skygreen
 */
class UtilXheditor extends Util 
{                       
    /**
     * 预加载xhEditor的样式和JS 库
     * @param string $textarea_id Input为Textarea的名称name
     * @param string $textarea_id 在线编辑器所在的内容编辑区域TextArea的ID
     * @param ViewObject $viewobject 表示层显示对象,只在Web框架中使用    
     * @param string form_id  在线编辑器所在的Form的ID                 
     */
    public static function loadReady($textarea_id,$viewObject=null,$form_id=null) 
    {  
        self::loadcss($viewObject);
        self::loadJs($textarea_id,$viewObject,$form_id);
    }
    
    /**
     * 加载xhEditor的样式 
     * @param ViewObject $viewobject 表示层显示对象,只在Web框架中使用    
     */
    public static function loadcss($viewObject=null)
    {
        UtilCss::loadCssContentReady($viewObject,"
            .btnMap {
                width:50px !important;
                background:transparent url(".Gc::$url_base."common/js/onlineditor/xheditor/demos/googlemap/map.gif) no-repeat center center;
            }
            .xheModal div{
                margin:0px;
            }    
            .contentBox{
                margin:15px;                          
            }    
            .xheModal{            
                font: 12px tahoma,arial,sans-serif;         
            }
           .xheDialog{
                line-height:1em;           
           }
           .xheDialog div{
                padding:2px 0;           
           }                
           .xheDialog input[type=text]{
                border-color: #ABADB3; 
                line-height:1em;   
                text-align:left;
                padding: 1px;
                width: 160px;   
                height:15px;                                
                border-style: solid;
                border-width: 1px;  
                font-size: 12px;
                margin: 0;
                color:#000;
                background:#FFF;                
            }            
        ");  
    }
    
    /**
     * 预加载xhEditor的JS 库
     * @param string $textarea_id 在线编辑器所在的内容编辑区域TextArea的ID
     * @param ViewObject $viewobject 表示层显示对象,只在Web框架中使用    
     * @param string form_id  在线编辑器所在的Form的ID                 
     */    
    private static function loadJs($textarea_id,$viewObject=null,$form_id=null)
    {
        if (UtilAjax::$IsDebug){
            UtilJavascript::loadJsReady($viewObject, "common/js/onlineditor/xheditor/xheditor-1.1.13-zh-cn.js"); 
        }else{
            UtilJavascript::loadJsReady($viewObject, "common/js/onlineditor/xheditor/xheditor-1.1.13-zh-cn.min.js"); 
        }
        self::loadJsPlugin($viewObject);
        self::loadJsFunction($textarea_id,$viewObject,$form_id);
        UtilJavascript::loadJsContentReady($viewObject,"$(function(){pageInit_{$textarea_id}();});");
    }  
    
    /**
     * 插入google地图插件
     * @param ViewObject $viewobject 表示层显示对象,只在Web框架中使用   
     */
    public static function loadJsPlugin($viewObject)
    {
        UtilJavascript::loadJsContentReady($viewObject,"
                var plugins={  
                    map:{c:'btnMap',t:'插入Google地图',e:function(){
                        var _this=this;
                        _this.showIframeModal('Google 地图','".Gc::$url_base."common/js/onlineditor/xheditor/demos/googlemap/googlemap.html',function(v){_this.pasteHTML('<img src=\"'+v+'\" />');},538,404);
                    }}
                }; 
        ");
        
    }
    

    /**
     * 设置标准toolbar
     * 参考:http://xheditor.com/manual/2#chapter2 
     *      2.2. 初始化参数列表
     *           tools：自定义工具按钮
     *     参数值：full(完全),mfull(多行完全),simple(简单),mini(迷你)
     *             或者自定义字符串，例如：'Cut,Copy,Paste,Pastetext,|,Source,Fullscreen,About'
     *    skin皮肤风格选择]：default(默认风格),o2007blue(Office 2007 蓝色),o2007silver(Office 2007 银色),vista(Vista),nostyle(NoStyle)
     */
    public static function toolbar_normal()
    {
        $skin="skin:'nostyle',";
        //return $skin."tools:'full'";
        // return $skin."tools:'mfull'";
        // return $skin."tools:'simple'";
        // return $skin."tools:'mini'";

        return $skin."tools:'Cut,Copy,Paste,Pastetext,|,Blocktag,Fontface,FontSize,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,|,SelectAll,Removeformat,Align,List,Outdent,Indent,|,Link,Unlink,Anchor,Img,Flash,Media,Hr,Emot,Table,|,Source,Preview,Print,Fullscreen,'";
    }


    /**
     * 预加载xhEditor的JS函数
     * @param string $textarea_id 在线编辑器所在的内容编辑区域TextArea的ID
     * @param ViewObject $viewobject 表示层显示对象,只在Web框架中使用    
     * @param string form_id  在线编辑器所在的Form的ID    
     * @param string $configString 配置字符串            
     */    
    public static function loadJsFunction($textarea_id,$viewObject=null,$form_id=null,$configString="")
    {
        $shortcut="";
        $formsubmit="";
        if (!empty($form_id)){
            $shortcut=",shortcuts:{'ctrl+enter':submitForm}";
            $formsubmit="function submitForm(){\$('#".$form_id."').submit();}";
        }
        $tools=self::toolbar_normal();

        UtilJavascript::loadJsContentReady($viewObject,"
            var xhEditor_{$textarea_id};
            function pageInit_{$textarea_id}()
            {
                xhEditor_{$textarea_id}=$('#{$textarea_id}').xheditor({{$tools},inlineStyle:false,{$configString}showBlocktag:true,upLinkUrl:'".Gc::$url_base."common/js/onlineditor/xheditor/demos/upload.php?immediate=1',upImgUrl:'".Gc::$url_base."common/js/onlineditor/xheditor/demos/upload.php?immediate=1',upFlashUrl:'".Gc::$url_base."common/js/onlineditor/xheditor/common/js/onlineditor/xheditor/demos/upload.php?immediate=1',upMediaUrl:'".Gc::$url_base."common/js/onlineditor/xheditor/demos/upload.php?immediate=1',localUrlTest:/^https?:\/\/[^\/]*?(xheditor\.com)\//i,remoteImgSaveUrl:'".Gc::$url_base."common/js/onlineditor/xheditor/demos/saveremoteimg.php',emots:{
                        msn:{name:'MSN',count:40,width:22,height:22,line:8},
                        pidgin:{name:'Pidgin',width:22,height:25,line:8,list:{smile:'微笑',cute:'可爱',wink:'眨眼',laugh:'大笑',victory:'胜利',sad:'伤心',cry:'哭泣',angry:'生气',shout:'大骂',curse:'诅咒',devil:'魔鬼',blush:'害羞',tongue:'吐舌头',envy:'羡慕',cool:'耍酷',kiss:'吻',shocked:'惊讶',sweat:'汗',sick:'生病',bye:'再见',tired:'累',sleepy:'睡了',question:'疑问',rose:'玫瑰',gift:'礼物',coffee:'咖啡',music:'音乐',soccer:'足球',good:'赞同',bad:'反对',love:'心',brokenheart:'伤心'}},
                        ipb:{name:'IPB',width:20,height:25,line:8,list:{smile:'微笑',joyful:'开心',laugh:'笑',biglaugh:'大笑',w00t:'欢呼',wub:'欢喜',depres:'沮丧',sad:'悲伤',cry:'哭泣',angry:'生气',devil:'魔鬼',blush:'脸红',kiss:'吻',surprised:'惊讶',wondering:'疑惑',unsure:'不确定',tongue:'吐舌头',cool:'耍酷',blink:'眨眼',whistling:'吹口哨',glare:'轻视',pinch:'捏',sideways:'侧身',sleep:'睡了',sick:'生病',ninja:'忍者',bandit:'强盗',police:'警察',angel:'天使',magician:'魔法师',alien:'外星人',heart:'心动'}}
                    },plugins:plugins,loadCSS:'<style>pre{margin-left:2em;border-left:3px solid #CCC;padding:0 1em;}</style>'$shortcut});                    
            }
            $formsubmit"
        );
    }
}   
?>
