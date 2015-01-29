# 高手进阶

##父子关系处理
```
定义示例:
    class Region extends DataObject{

    	/**
    	 * 从属一对一关系
    	 */
    	static $belong_has_one=array(
    		"region_p"=>"Region"
    	);
代码如下:
    $region=Region::get_by_id("2709");
    print_r($region);
    print_r($region->region_p);
输出显示如下:
    Region Object
    (
        [region_id] => 2709
        [parent_id] => 321
        [region_name] => 普陀区
        [region_type] => 3
        [level] => 4
        [id:protected] =>
        [commitTime] => 1324018201
        [updateTime] => 2014-04-27 10:02:29
    )
    Region Object
    (
        [region_id] => 321
        [parent_id] => 25
        [region_name] => 上海
        [region_type] => 2
        [level] => 3
        [id:protected] =>
        [commitTime] => 1324018201
        [updateTime] => 2014-04-27 10:02:29
    )
```

##多个外键引用同一张表
```
说明:获取地区信息
定义示例:
    class Userdetail extends DataObject {
    	/**
    	 * 规格说明:外键声明
    	 * @var mixed
    	 */
    	public $field_spec=array(
    		EnumDataSpec::FOREIGN_ID=>array(
    			'country_r'=>"country",
    			"province_r"=>"province",
    			"city_r"=>"city",
    			'district_r'=>"district"
    		)
    	);

    	/**
    	 * 从属一对一关系
    	 */
    	static $belong_has_one=array(
    		"country_r"=>"Region",
    		"province_r"=>"Region",
    		"city_r"=>"Region",
    		"district_r"=>"Region"
    	);
代码如下:
    $user=User::get_by_id(1);
    $userDetail=$user->userdetail;
    $region["country"] =$userDetail->country_r;
    $region["province"]=$userDetail->province_r;
    $region["city"] =$userDetail->city_r;
    $region["district"] =$userDetail->district_r;
    print_r($region);
输出显示如下:
    Array
    (
        [country] => Region Object
            (
                [region_id] => 1
                [parent_id] => 0
                [region_name] => 中国
                [region_type] => 0
                [level] => 1
                [id:protected] =>
                [commitTime] => 1324018201
                [updateTime] => 2014-04-27 10:02:29
            )
```


