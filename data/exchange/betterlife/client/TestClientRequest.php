<?php
require_once("include/service.php");   
/**
  +---------------------------------<br/>
 * 模拟客户端发送请求到第三方<br/>
  +---------------------------------
 * @category betterlife
 * @package data.exchange.enjoyoung.client
 * @author skygreen
 */
class TestClientRequest {
    public static $id=33;
    /**
     * 会员测试驱动数据
     * @param type $user 
     */
    public static function user_data($user){       
        $user->departmentId="10";  
        $user->password="23990klr8923kldi89023";
        $user->name="skygreen";   
        
    }
    
    /**
    * 发送Get请求
    * @return type 
    */
    public static function user_get(){
        $user=new UserRO();   
        self::user_data($user);
        $result=$user->get();
        echo $result;        
    }     
    
    /**
    * 发送Post请求
    * @return type 
    */
    public static function user_post(){
        $user=new UserRO();   
        self::user_data($user);
        $orderProducts=new OrderproductsRO();
        $orderProducts->item_id=13;
        $orderProducts->product_id=1679;
        $orderProducts->product_name="冠生园蜂蜜";
        $orderProducts->amount=100;
        $orderProducts->price="11";
        $orderProducts->nums=3;
        $user->line_items[]=$orderProducts;            
        $result=$user->post();
        echo $result;        
    }    

    /**
    * 发送Put请求
    */
    public static function user_put(){
        $user=new UserRO(); 
        $user->id=self::$id; 
        self::user_data($user); 
        $result=$user->put();
        echo $result;
    }

    /**
    * 发送Delete请求
    */
    public static function user_delete(){
        $user=new UserRO(); 
        $user->id=self::$id;  
        $result=$user->delete();
        echo $result;
    }
}

TestClientRequest::user_post(); 
//TestClientRequest::user_put();
//TestClientRequest::user_delete(); 
//TestClientRequest::user_get();
?>
