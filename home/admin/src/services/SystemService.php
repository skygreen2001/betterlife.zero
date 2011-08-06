<?php
/**
 +---------------------------------<br/>
 * 提供系统服务<br/>
 +---------------------------------
 * @category betterlife
 * @package web.back.admin.services
 * @author skygreen
 */
class SystemService extends Service
{   
    /**
     * 获取所有的资源库信息列表
     * @param mixed $formPacket
     * @return array 
     */
    public function doLibrarySelect($formPacket=array())
    {           
        $condition=array();
        foreach ($formPacket as $key=>$value){
           if (!empty($value)){
               if ($key=='name') {
                    $condition[]="$key contain '$value'";    
               } else if ($key=='init') {
                    $condition[]="$key contain '$value'";    
               } else {
                    $condition[$key]=$value;
               }
           } 
        }     
        
        $startPoint=0;
        $endPoint=10; 
        $result= ResourceLibrary::queryPage($startPoint,$endPoint,$condition);                
        $data=$result;
        if ($data==null)$data=array();
        return array(  
            'success'=>true,
            'data'=>$data
        );              
    }
}
?>
