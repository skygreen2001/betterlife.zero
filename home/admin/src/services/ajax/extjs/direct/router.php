<?php
require_once("../../../../../../../init.php");
/**
 * Ext Direct Action
 */
class DirectAction {
    public $action;
    public $method;
    public $data;
    public $tid;
}
/**
 * Ext Direct Remote Service调用类
 */
class RemoteServiceCall
{
    /**
     * 是否数据对象转换成数组
     */
    const ISOBJECTTOARRAY=true;
    /**
     * 是否提交Form
     * @var bool
     */
    private $isForm = false;
    /**
     * 是否上传文件
     * @var bool
     */
    private $isUpload = false;
    /**
     * 需要传送的数据
     * @var mixed
     */
    private $data;
    /**
     * Ext Direct Remote通信配置API
     * @var array
     */
    private static $configApi;

    /**
     * 获取需要传送的数据
     * @param $post_data 源Post的数据
     */
    public function initData($post_data)
    {
        //$post_data = file_get_contents("php://input");
        //$post_data='{"url":"http:\/\/127.0.0.1\/betterlife\/core\/util\/view\/ajax\/extjs\/direct\/router.php","type":"remoting","actions":{"MemberService":[{"name":"doSelect","len":2,"formHandler":true},{"name":"getInfo","len":1},{"name":"getApp","len":1}]}}';
        if (isset($post_data)) {
            header('Content-Type:text/javascript;charset=UTF-8');
            $data = json_decode($post_data);
        } else if (isset($_POST['extAction'])) { // form post
            $this->isForm = true;
            $this->isUpload = $_POST['extUpload'] == 'true';
            $data = new DirectAction();
            $data->action = $_POST['extAction'];
            $data->method = $_POST['extMethod'];
            $data->tid = isset($_POST['extTID']) ? $_POST['extTID'] : null; // not set for upload
            $data->data = array($_POST, $_FILES);
        } else {
            die('非法的请求.');
        }
        $this->data=$data;
        self::$configApi=Config_Service::serviceConfig();
    }
    /**
     * 发送请求
     */
    public function request(){
        $response = null;
        if (is_array($this->data)) {
            $response = array();
            foreach ($this->data as $d) {
                $response[] = $this->doRpc($d);
            }
        } else {
            $response = $this->doRpc($this->data);
        }
        if ($this->isForm && $this->isUpload) {
            ob_clean();
            echo '<html><body><textarea>';
            echo json_encode($response);
            echo '</textarea></body></html>';
        } else {
            if (self::ISOBJECTTOARRAY){
                if (!isset($response["result"])){
                    foreach ($response as $key=>$response_sub) {
                        $response[$key]["result"]["data"]=$this->data_object2array($response_sub["result"]["data"]);
                    }
                }else{
                    $response["result"]["data"]=$this->data_object2array($response["result"]["data"]);
                }
            }
            echo json_encode($response);
        }
        die();
    }

    /**
     * 数据对象转换成数组
     * @param $responsedata 响应数据数组
     */
    private function data_object2array($responsedata)
    {
        if ((!empty($responsedata))&&(count($responsedata)>0)){
            foreach ($responsedata as $key=>$data) {
                if (is_object($data)){
                    $responsedata[$key]=UtilObject::object_to_array($data);
                }
            }
        }
        return $responsedata;
    }

    /**
     * 发送远程请求调用
     * @param type $cdata
     * @return type
     */
    private function doRpc($cdata) {
        try {
            //$cdata->action="MemberService";
            if (!isset(self::$configApi[$cdata->action])) {
                throw new Exception('调用未定义的Service: ' . $cdata->action);
            }

            $action = $cdata->action;
            $a = self::$configApi[$action];

            if (array_key_exists("before",$a))$this->doAroundCalls($a['before'], $cdata);

            //$cdata->method="doSelect";
            //$cdata->tid=100;

            $method = $cdata->method;

            $mdef = $a['methods'][$method];
            if (!$mdef) {
                throw new Exception("在Service里 $action 调用未定义的方法: $method ");
            }
            if (array_key_exists("before",$mdef))$this->doAroundCalls($mdef['before'], $cdata);

            $r = array(
                'type' => 'rpc',
                'tid' => $cdata->tid,
                'action' => $action,
                'method' => $method
            );
            $o = new $action();
            $params = isset($cdata->data) && is_array($cdata->data) ? $cdata->data : array();

            $params=$this->clearValuelessData($params);

            $r['result'] = call_user_func_array(array($o, $method), $params);

            if (array_key_exists("after",$mdef))$this->doAroundCalls($mdef['after'], $cdata, $r);
            if (array_key_exists("after",$a))$this->doAroundCalls($a['after'], $cdata, $r);
        } catch (Exception $e) {
            $r['type'] = 'exception';
            $r['message'] = $e->getMessage();
            $r['where'] = $e->getTraceAsString();
        }
        return $r;
    }

    /**
     * 清除无价值的数据
     */
    private function clearValuelessData($params)
    {
        if(is_array($params)&&count($params)>0)
        {
            if (is_array($params[0])&&count($params[0]>0))
            {
                unset($params[0]["extAction"]);
                unset($params[0]["extMethod"]);
                unset($params[0]["extTID"]);
                unset($params[0]["extType"]);
                unset($params[0]["extUpload"]);
            }
        }
        return $params;
    }

    /**
     * 在主程序前或者后执行方法。
     * @param type $fns
     * @param type $cdata
     * @param type $returnData
     * @return type
     */
    private function doAroundCalls($fns, $cdata, $returnData=null)
    {
        if (!$fns) {
            return;
        }
        if (is_array($fns)) {
            $returnData=array();
            foreach ($fns as $f) {
                $returnData=array_merge_recursive($returnData,$f($cdata));
            }
        } else {
            $returnData=$fns($cdata);
        }
    }
}

$remoteCall=new RemoteServiceCall();
$post_data=null;
if (isset($HTTP_RAW_POST_DATA)){
    $post_data=$HTTP_RAW_POST_DATA;
} else {
  $post_data = file_get_contents("php://input");
}
$remoteCall->initData($post_data);
$remoteCall->request();
?>
