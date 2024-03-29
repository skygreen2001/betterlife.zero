<?php
/**
 +---------------------------------------<br/>
 * 服务类:所有ExtService的管理类<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.back.admin.services
 * @subpackage ext
 * @author skygreen skygreen2001@gmail.com
 */
class Manager_ExtService extends Manager
{
    private static $blogService;
    private static $commentService;
    private static $adminService;
    private static $regionService;

    /**
     * 提供服务:博客
     */
    public static function blogService()
    {
        if (self::$blogService==null) {
            self::$blogService=new ExtServiceBlog();
        }
        return self::$blogService;
    }

    /**
     * 提供服务:评论
     */
    public static function commentService()
    {
        if (self::$commentService==null) {
            self::$commentService=new ExtServiceComment();
        }
        return self::$commentService;
    }

    /**
     * 提供服务:系统管理人员
     */
    public static function adminService()
    {
        if (self::$adminService==null) {
            self::$adminService=new ExtServiceAdmin();
        }
        return self::$adminService;
    }

    /**
     * 提供服务:地区
     */
    public static function regionService()
    {
        if (self::$regionService==null) {
            self::$regionService=new ExtServiceRegion();
        }
        return self::$regionService;
    }
}
?>
