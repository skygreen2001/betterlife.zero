<?php
//加载初始化设置
class_exists("Service")||require(dirname(__FILE__)."/../../../../../init.php");

/** 
 * This sample service contains functions that illustrate typical
 * service operations. This code is for prototyping only.
 *
 *  Authenticate users before allowing them to call these methods.
 */

class ServiceUser extends Service implements IServiceNormal {

    public function getUsers() {
        return  self::dao()->get(new User());
    }
    
    public function getDepartments() {
        return  self::dao()->get(new Department());
    }

    public function getUserByID($ID) {
        return  self::dao()->get_by_id(new User(),$ID);
    }

    public function getUserByName($searchStr) {
        return self::dao()->get(new User(),"name LIKE ?");
    }

    public function createUser($object) {
        return  self::dao()->save($object);
    }

    public function deleteUserById($id) {
        $user=new User();
        $user->setId($id);
        return self::dao()->delete($user);
    }

    public function updateUser($object) {
        self::dao()->update($object);
    }
}

?>