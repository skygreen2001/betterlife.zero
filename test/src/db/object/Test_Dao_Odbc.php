<?php
require_once '../../../Test_Parent.php';
/**
 * 测试用例Dao_Odbc
 *
 * @author zhouyuepu
 */
class Test_Dao_Odbc extends Test_Parent {

    const INSERT_FAIL="插入数据失败！";
    const UPDATE_FAIL="修改数据失败！"; 
    const DELETE_FAIL="删除数据失败！";
    
    private $id=22;
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

    public function testSave() {
        $joe=new User();
//        $joe->setId($this->id);
        $joe->setName("joy");
        $joe->setDepartmentId(5);
//        $joe["name"]="wb";
        $result= $this->sharedFixture->save($joe);
        $this->assertEquals($result,$this->id,self::INSERT_FAIL);
    }
    public function testUpdate() {
        $joe=new User();
        $joe->setId($this->id);
        $joe->setName("BetterLife");
        $joe->setDepartmentId(7);
        $joe->setPassword(md5("test"));
        $result= $this->sharedFixture->update($joe);
        $this->assertTrue($result,self::UPDATE_FAIL);
    }
    public function testDelete() {
        $joe=new User();
        $joe->setId($this->id);
        $result= $this->sharedFixture->delete($joe);
        $this->assertTrue($result,self::DELETE_FAIL);
    }
    public function testGet() {
        $joe=new User();
        $result= $this->sharedFixture->get($joe,"departmentId=5");
        print_r($result);
    }

    public function testGet_one() {
        $joe=new User();
        $result= $this->sharedFixture->get_one($joe,"name='joy'");
        print_r($result);
    }
    public function testGet_by_id() {
        $joe=new User();
        $result= $this->sharedFixture->get_by_id($joe,$this->id);
        print_r($result);
    }
    public function testSqlExecute() {
        $result= $this->sharedFixture->sqlExecute("select * from bb_user_user where name='joy'");
//        $result= $this->sharedFixture->sqlExecute("insert into  bb_user_user(name,departmentId)values('bettertime',5)");
        print_r($result);
    }
    public function testCount() {
        $joe=new User();
        $result= $this->sharedFixture->count($joe);
        echo($result);
    }
    public function testQueryPage() {
        $joe=new User();
        $result= $this->sharedFixture->queryPage($joe,4,6,"name='joy'");
        print_r($result);
    }
}

$test = new Test_Dao_Odbc("testDelete");
$testRunner = new PHPUnit_TextUI_TestRunner();
$testRunner->run($test);
?>
