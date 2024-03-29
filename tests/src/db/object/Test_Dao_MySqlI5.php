<?php
require_once '../../../Test_Parent.php';

/**
 * Test class for GenderFilter.
 * Generated by PHPUnit on 2010-06-30 at 02:24:33.
 */
class Test_Dao_MySqlI5 extends Test_Parent {
    const INSERT_FAIL="插入数据失败！";
    const DELETE_FAIL="删除数据失败！";
    const UPDATE_FAIL="修改数据失败！";
    private $id=12;
    private $username="test";

    /**
     * @var GenderFilter
     */
//    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->sharedFixture = Manager_Db::newInstance()->dao();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        $this->sharedFixture =null;
    }


    /**
     * @todo Implement testAccept().
     */
    public function testSave() {
        $joe=new User();
//        $joe->setId($this->id);
//        $joe->setName("abc");
        $joe["username"]=$username;
        $joe->setEmail("skygreen2001@gmail.com");
        $result= $this->sharedFixture->save($joe);
        $this->assertEquals($result,$this->id,self::INSERT_FAIL);
    }

    public function testDelete() {
        $joe=new User();
        $joe->setId($this->id);
        $result= $this->sharedFixture->delete($joe);
        $this->assertTrue($result,self::DELETE_FAIL);
    }

    public function testGet_one() {
        $joe=$this->sharedFixture->get_one(new User(), "username='$username'");
        print_r($joe);
    }

    public function testSqlQuery() {
        $joe=$this->sharedFixture->sqlExecute("select * from bb_user_user where username='$username'","User");
        print_r($joe);
    }

    public function testGet() {
        $joe=new User();
        $result= $this->sharedFixture->get($joe,"id=3");
        print_r($result);
    }

    public function testGet_by_id() {
        $joe=$this->sharedFixture->get_by_id(new User(), $this->id);
        print_r($joe);
    }
    /**
     * @todo Implement testAccept().
     */
    public function testUpdate() {
        $joe=new User();
        $joe->setId($this->id);
//        $joe->setName("zhangwenyan");
        $joe["username"]="onlyyou";
        $joe->setPassword(md5("test"));
        $result= $this->sharedFixture->update($joe);
        $this->assertTrue($result,self::UPDATE_FAIL);

    }
    public function testCount() {
        $joe=new User();
        $result= $this->sharedFixture->count($joe,"username='$username'");
        echo($result);
    }
    public function testQueryPage() {
        $joe=new User();
        $result= $this->sharedFixture->queryPage($joe,1,3);
        print_r($result);
    }

}

$test = new Test_Dao_MySqlI5("testQueryPage");
$testRunner = new PHPUnit_TextUI_TestRunner();
$testRunner->run($test);
?>
