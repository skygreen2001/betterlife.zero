<?php
/**
 * 提供业务方法服务的接口
 * @author zhouyuepu
 */
interface IServiceNormal {
    public function createUser($object);
    public function deleteUserById($id) ;
    public function updateUser($object) ;
}
?>
