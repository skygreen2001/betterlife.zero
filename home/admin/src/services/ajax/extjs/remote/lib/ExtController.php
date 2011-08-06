<?php
/**
 * @class ApplicationController
 */
class ExtController 
{
    public $request, $id, $params;

    /**
     * dispatch
     * Dispatch request to appropriate controller-action by convention according to the HTTP method.
     */
    public function dispatch($object_name,$request) 
    {
        $this->request = $request;
        $this->id = $request->id;
        $this->params = $request->params;

        if ($request->isRestful()) {
            return $this->dispatchRestful($object_name);
        }
        if ($request->action) {
            $object=new $object_name();
            return $object->{$request->action}($this->params);
        } 
        // normal dispatch here.  discover action
    }

    protected function dispatchRestful($object_name) 
    {
        $response = new ExtResponse();
        $response->success = true;     
        $object=new $object_name();   
        switch ($this->request->method) {
            case 'GET':
                $response->message = "加载数据";
                if (isset($this->request->start)&&($this->request->start!=null)){
                    $response->data =call_user_func_array("$object_name::queryPage",array($this->request->start,$this->request->limit,$this->params));
                }else{
                    $response->data =call_user_func("$object_name::get",$this->params);
                }
                if ($response->data!=null){
                    $response->totalCount=call_user_func("$object_name::count");
                }
                break;                                                     
            case 'POST':
                foreach ($this->params as $key => $value) {
                    $methodName="set".  ucfirst($key);
                    if (method_exists($object, $methodName)){                        
                        $object->$methodName($value);
                    }
                }
                $result= $object->save();
                if ($result) {
                    $response->success = true;
                    $response->message = "创建资源库" . $result->id;
                    $response->data = $object->toArray();
                } else {
                    $response->message = "创建资源库失败！";
                }
                break;
            case 'PUT':
                foreach ($this->params as $key => $value) {
                    $methodName="set".  ucfirst($key);
                    if (method_exists($object, $methodName)){                        
                        $object->$methodName($value);
                    }
                }
                $result= $object->update();
                if ($result) {
                    $response->data = $object->toArray();
                    $response->success = true;
                    $response->message = '更新资源库 ' . $this->id;
                } else {
                    $response->message = "更新资源库失败！";
                }   
                break;
            case 'DELETE':
                $object->setId($this->id);
                $result=$object->delete();
                if ($result) {
                    $response->success = true;
                    $response->message = '删除资源库 ' . $this->id;
                } else {
                    $response->message = "删除资源库失败！";
                }         
                break;
        }           
        return $response->to_json();        
    }
}

