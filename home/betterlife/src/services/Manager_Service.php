<?php
/**
 * Description of managerservice
 *
 * @author zhouyuepu
 */
class Manager_Service {
    private static $userService;

    public static function userService() {
        if (self::$userService==null) {
            self::$userService=new ServiceUser();
        }
        return self::$userService;
    }
}
?>
