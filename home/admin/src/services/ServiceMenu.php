<?php
/**
 +---------------------------------<br/>
 * 提供菜单服务<br/>
 +---------------------------------
 * @link http://www.ibm.com/developerworks/opensource/library/wa-aj-streamline/index.html?ca=drs-
 * @link http://blog.extjs.eu/know-how/mastering-ext-direct-part-1/
 * @category betterlife
 * @package web.back.admin.services
 * @author skygreen
 */
class ServiceMenu extends Service
{     
    /**
    * 新建保存菜单
    */
    public function save($menu)
    {             
        return array(  
            'success'=>true,  
            'data'=>true
        ); 
        
    }
    
    /**
    * 更新菜单
    */
    public function update($menu)
    {
        
    }
    
    /**
    * 删除指定编号的菜单
    * 
    * @param mixed $id
    */
    public function delete($id)
    {
        
    }

    /**
    * 根据菜单分组标识获取所有相关的菜单
    * 
    * @param mixed $menuGroup_id
    */
    public function getMenusByGroupId($menuGroup_id)
    {           
        $menugroup=new MenuGroup($menuGroup_id);
        $menugroup->getByID();   
        $menus=$menugroup->getMenus();         
        $data=array();
        if ($menus){
            foreach($menus as $menu){
               $menu->menuGroup_id=$menuGroup_id; 
               $data[]=UtilObject::object_to_array($menu);  
            }       
        }
        if ($data==null)$data=array();             
        return array(  
            'success'=>true,  
            'data'=>$data
        ); 
    }
    
    /**
    * 分页查询:菜单列表
    */
    public function queryPageMenuForm($formPacket=array())
    {
        return $this->QueryPageMenu($formPacket);
    }
    
    /**
    * 分页查询:菜单列表
    */
    public function queryPageMenu($formPacket=array())
    {
        $condition=array();
        if (is_object($formPacket)){
            $formPacket=UtilObject::object_to_array($formPacket);
        }
        foreach ($formPacket as $key=>$value){
           if (!empty($value)){
               if ($key=='name') {
                    $condition[]="$key contain '$value'";    
               } else if ($key=='address') {
                    $condition[]="$key contain '$value'";    
               } else {
                    $condition[$key]=$value;
               }
           } 
        }  
        
        $startPoint=0;
        $endPoint=10;  
        if (isset($formPacket['start'])){
            $startPoint=$formPacket['start'];
        }          
        if (isset($formPacket['limit'])){
            $endPoint=$formPacket['limit']; 
        }
        
        unset($condition['start']);
        unset($condition['limit']);   
        $count=MenuGroup::count($condition);
        $data=MenuGroup::queryPage($startPoint,$endPoint,$condition);          
        return array(  
            'success'=>true,  
            'totalCount'=>$count,    
            'data'=>$data
        );          
    }      
}
?>
