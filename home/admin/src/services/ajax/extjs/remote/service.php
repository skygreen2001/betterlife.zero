<?php
    //加载初始化设置
    class_exists("Service")||require(dirname(__FILE__)."/../../../../../../../init.php");

    // 获取请求参数
    $request = new ExtRequest(array('restful' => true));

    $object_name = ucfirst($request->controller);     
    $controller=new ExtController();

    if (class_exists($object_name)){
        // 分发请求                                      
        echo $controller->dispatch($object_name,$request);
    }
?>