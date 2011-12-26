<?php
/**
 +---------------------------------<br/>
 * 提供菜单分组服务<br/>
 +---------------------------------                                 
 * @category betterlife
 * @package web.back.admin.services
 * @author skygreen
 */
class ServiceMenuGroup extends Service
{             
    /**
    * 新建保存菜单分组
    */
    public function save($menuGroup)
    {
        
    }
    
    /**
    * 更新菜单分组
    */
    public function update($menuGroup)
    {
        
    }
    
    /**
    * 删除指定编号的菜单分组
    * 
    * @param mixed $id
    */
    public function delete($id)
    {
        
    }
    
    /**
     * 获取所有的菜单分组列表
     * @param mixed $formPacket
     * @return array 
     */
    public function allMenuGroup()
    {
        $data=MenuGroup::allMenuGroups(EnumReturnType::ARRAYTYPE);       
        if ($data==null)$data=array();
        return array(  
            'success'=>true,
            'data'=>$data
        ); 
    } 
}
?>
