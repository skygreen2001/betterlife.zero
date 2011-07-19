<?php
require_once(dirname(__FILE__)."/../init.php");
require_once('PHPUnit/Framework.php');
require_once('PHPUnit/TextUI/TestRunner.php');

/**
 *
 *
 * @author zhouyuepu
 */
class Test_Parent extends PHPUnit_Framework_TestCase {
    const INIT="初始化PHPUnit失败！";
    /**
     * 初始化PHPUnit
     */
    public function testPHPUnitInit() {
        $this->assertTrue(true,self::INIT);
    }
}
?>
