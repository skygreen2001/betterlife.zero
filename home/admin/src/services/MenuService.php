<?php
/**
 +---------------------------------<br/>
 * 提供菜单服务<br/>
 +---------------------------------
 * @link http://www.ibm.com/developerworks/opensource/library/wa-aj-streamline/index.html[Using Ext.Direct in Ajax applications]
 * @category betterlife
 * @package web.back.admin.services
 * @author skygreen
 */
class MenuService extends Service
{   
    /**
     * 获取所有的菜单分组列表
     * @param mixed $formPacket
     * @return array 
     */
    public function AllMenuGroup()
    {
        $data=MenuGroup::allMenuGroups(EnumReturnType::ARRAYTYPE);       
        if ($data==null)$data=array();
        return array(  
            'success'=>true,
            'data'=>$data
        ); 
    } 
    
    /**
    * 分页查询:菜单列表
    */
    public function QueryPageMenu($formPacket=array())
    {
        $condition=array();
        foreach ($formPacket as $key=>$value){
           if (!empty($value)){
               if ($key=='name') {
                    $condition[]="$key contain '$value'";    
               } else if ($key=='id') {
                    $condition[]="$key contain '$value'";    
               } else {
                    $condition[$key]=$value;
               }
           } 
        }    
    }
    
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
