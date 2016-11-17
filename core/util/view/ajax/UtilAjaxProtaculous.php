<?php

/**
 +---------------------------------<br/>
 * 工具类：Protaculous[Javascript Ajax 框架]<br/>
 +---------------------------------
 * @category betterlife
 * @package util.view.ajax
 * @author skygreen
 */
class UtilAjaxProtaculous extends UtilAjax implements IUtilAjax
{
    /**
     * 动态加载Protaculous:Ajax Javascript Framework库
     * @param string $version javascript框架的版本号
     * @param ViewObject $viewObject 表示层显示对象,只在Web框架中使用,一般结合loadJsReady使用
     */
    public static function load($version="",$viewObject=null)
    {
        self::loadAjaxJs(EnumJsFramework::JS_FW_PROTOTYPE,$version,$viewObject);
        self::loadAjaxJs(EnumJsFramework::JS_FW_SCRIPTACULOUS,$version,$viewObject);
    }

    /**
     * 发送Ajax请求的语句
     * @todo 暂未实现。
     * @param string $url 通信的Url地址。
     * @param array $dataArray 传送的数据。
     * @param array $method HTTP的标准协议动词【get|post|put|delete】。
     * @param enum $response_type 返回的数据类型
     * @param string $callback Javascript调用的回执方法名。
     * @return 发送Ajax请求的语句
     */
    public static function ajaxRequstStatement($url,$dataArray,$method,$response_type=EnumResponseType::XML,$callback=null)
    {
        $result="";
        $result.= "<script type='text/javascript'>";
        //<editor-fold defaultstate="collapsed" desc="Protaculous">
        $result.="";
        //</editor-fold>
        $result.= "</script>";
        return $result;
    }

    /**
     * 生成Javascript的回调函数
     * @todo
     * @param string $local_service_flag 对象名称
     * @param string $callback 回调函数
     * @param enum $response_type 返回的数据类型
     * @return string 回调函数
     */
    public static function callbackForJsFramework($local_service_flag,$response_type=EnumResponseType::XML)
    {
        $class_name=str_replace("RO","",$local_service_flag);

        if (!self::$IsHtmlBody){
            echo "<body><h1 id='object_name'></h1><ol id='properties'></ol></body>\r\n";
            self::$IsHtmlBody=true;
        }
        return $result;
    }
}

?>
