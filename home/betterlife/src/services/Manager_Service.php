<?php
/**
 +---------------------------------------<br/>
 * 服务类:所有Service的管理类<br/>
 +---------------------------------------
 * @category betterlife
 * @package services
 * @author skygreen skygreen2001@gmail.com
 */
class Manager_Service extends Manager
{
    private static $userService;

    /**
     * 提供服务:用户
     */
    public static function userService()
    {
        if (self::$userService==null) {
            self::$userService=new ServiceUser();
        }
        return self::$userService;
    }
}
?>
