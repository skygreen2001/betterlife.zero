<?php
require_once '../../../Test_Parent.php';
/**
 * 测试：db.dal.pdo.Dal_Pdo
 *
 * @author zhouyuepu
 */
class Test_Dal_Pdo extends Test_Parent {
    private $id=7;
    const INSERT_FAIL="插入数据失败！";
    const DELETE_FAIL="删除数据失败！";
    const UPDATE_FAIL="修改数据失败！";
    protected function setUp() {
        $this->sharedFixture = Manager_Db::newInstance()->dao();
    }

    protected function tearDown() {
        $this->sharedFixture =null;
    }

    public function testSave() {
        $joe=new User();
        $joe->setId($this->id);
        $joe["name"]="wondercool";
        $result= $this->sharedFixture->save($joe);
        $this->assertEquals($result,$this->id,self::INSERT_FAIL);
    }
    public function testUpdate() {
        $joe=new User();
        $joe->setId($this->id);
        $joe->setName("BetterLife");
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
        $result= $this->sharedFixture->get($joe,"name='joy'");
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
        print_r($result);
    }
    public function testCount() {
        $joe=new User();
        $result= $this->sharedFixture->count($joe);
        echo($result);
    }
    public function testQueryPage() {
        $joe=new User();
        $result= $this->sharedFixture->queryPage($joe,3,5,"name='judy'");
        print_r($result);
    }

}

$test = new Test_Dal_Pdo("testGet");
$testRunner = new PHPUnit_TextUI_TestRunner();
$testRunner->run($test);
?>
