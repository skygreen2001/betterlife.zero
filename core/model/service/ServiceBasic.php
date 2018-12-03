<?php
/**
 * 通用的方法服务类。
 * 继承本基本服务方法类要求遵循命名规范
 *     服务类名=Service+[DataObject名称]
 *     如用户服务ServiceUser=Service+User
 * 通用的方法包括如下：
 *     数据对象的增删改
 *     根据标识ID获取数据对象
 *     根据过滤条件获取数据对象
 *     根据数据对象的计数
 *     分页获取数据对象
 *     对属性进行递增递减
 * @category betterlife
 * @package core.model
 * @subpackage service
 * @author skygreen skygreen2001@gmail.com
 */
class ServiceBasic extends Service implements IServiceBasic
{
    /**
     +--------------------------------------------------<br/>
     * 服务类和数据对象类的STD映射<br/>
     * 默认命名规则:
     *     服务类=Service+数据对象类命名
     * 示例如下：<br/>
     *     数据对象类  :Task<br/>
     *     服务类      :ServiceTask<br/>
     * 如果未按命名规范定义定义服务类，则在此处定义Service To DataObject的映射(简称STD)
     * 服务类需放置在services目录下<br/>
     * 数据库实体POJO对象需放置在domain目录下<br/>
     +--------------------------------------------------<br/>
     * @var array
     * @static
     */
    private static $std=array(
      "ServiceStorage"=>'Order'
    );

    /**
     * 保存数据对象
     * @param array|DataObject $dataobject
     * @return int 保存对象记录的ID标识号
     */
    public function save($dataobject)
    {
        $dataobject_class=self::std($this->classname());
        if (class_exists($dataobject_class)){
            if (is_array($dataobject)){
                $dataobject=new $dataobject_class($dataobject);
            }
            if ($dataobject instanceof DataObject){
                return $dataobject->save();
            }else{
                return false;
            }
        }else{
             LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
             return false;
        }
    }

    /**
     * 更新数据对象
     * @param array|DataObject $dataobject
     * @return boolen 是否更新成功；true为操作正常
     */
    public function update($dataobject)
    {
        $dataobject_class=self::std($this->classname());
        if (class_exists($dataobject_class)){
            if (is_array($dataobject)){
                $dataobject=new $dataobject_class($dataobject);
            }
            if ($dataobject instanceof DataObject){
                return $dataobject->update();
            }else{
                return false;
            }
        }else{
             LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
             return false;
        }
    }

    /**
     * 由标识删除指定ID数据对象
     * @param int $id 数据对象标识
     * @return boolen 是否删除成功；true为操作正常\r\n".
     */
    public function deleteByID($id)
    {
        $dataobject_class=self::std($this->classname());
        if (class_exists($dataobject_class)){
            return call_user_func($dataobject_class."::deleteByID",$id);
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return false;
        }
    }

   /**
    * 根据主键删除多条记录
    * @param array|string $ids 数据对象编号
    *  形式如下:
    *  1.array:array(1,2,3,4,5)
    *  2.字符串:1,2,3,4
    * @return boolen 是否删除成功；true为操作正常
    */
    public function deleteByIds($ids)
    {
        $dataobject_class=self::std($this->classname());
        if (class_exists($dataobject_class)){
            return call_user_func($dataobject_class."::deleteByIds",$ids);
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return false;
        }
    }

    /**
     * 对属性进行递增
     * @param object|string|array $filter 查询条件，在where后的条件<br/>
     * 示例如下：<br/>
     *      0."id=1,name='sky'"<br/>
     *      1.array("id=1","name='sky'")<br/>
     *      2.array("id"=>"1","name"=>"sky")<br/>
     *      3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
     * @param string $property_name 属性名称
     * @param int $incre_value 递增数
     * @return boolen 是否操作成功；true为操作正常
     */
    public function increment($filter=null,$property_name,$incre_value)
    {
        $dataobject_class=self::std($this->classname());
        if (class_exists($dataobject_class)){
            return call_user_func_array($dataobject_class."::increment",array($filter,$property_name,$incre_value));
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return false;
        }
    }

    /**
     * 对属性进行递减
     * @param object|string|array $filter 查询条件，在where后的条件<br/>
     * 示例如下：<br/>
     *      0."id=1,name='sky'"<br/>
     *      1.array("id=1","name='sky'")<br/>
     *      2.array("id"=>"1","name"=>"sky")<br/>
     *      3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
     * @param string $property_name 属性名称
     * @param int $decre_value 递减数
     * @return boolen 是否操作成功；true为操作正常
     */
    public function decrement($filter=null,$property_name,$decre_value)
    {
        $dataobject_class=self::std($this->classname());
        if (class_exists($dataobject_class)){
            return call_user_func_array($dataobject_class."::decrement",array($filter,$property_name,$decre_value));
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return false;
        }
    }

    /**
     * 查询当前对象需显示属性的列表
     * @param string $columns 指定的显示属性，同SQL语句中的Select部分。
     * 示例如下：<br/>
     *     id,name,commitTime
     * @param object|string|array $filter 查询条件，在where后的条件<br/>
     * 示例如下：<br/>
     *      0."id=1,name='sky'"<br/>
     *      1.array("id=1","name='sky'")<br/>
     *      2.array("id"=>"1","name"=>"sky")<br/>
     *      3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如："(id=1 and name='sky') or (name like 'sky')"<br/>
     * @param string $sort 排序条件<br/>
     * 示例如下：<br/>
     *      1.id asc;<br/>
     *      2.name desc;<br/>
     * @param string $limit 分页数量:limit起始数被改写，默认从1开始，如果是0，同Mysql limit语法；
     * 示例如下：<br/>
     *    6,10<br/>  从第6条开始取10条(如果是mysql的limit，意味着从第五条开始，框架里不是这个意义。)
     *    1,10<br/> (相当于第1-第10条)
     *    10 <br/>(相当于第1-第10条)
     * @return 对象列表数组
     */
    public function select($columns,$filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID, $limit=null)
    {
        $dataobject_class=self::std($this->classname());
        if (class_exists($dataobject_class)){
            return call_user_func_array($dataobject_class."::select",array($columns,$filter,$sort,$limit));
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return null;
        }

    }

    /**
     * 查询当前对象列表
     * @param object|string|array $filter 查询条件，在where后的条件<br/>
     * 示例如下：<br/>
     *      0."id=1,name='sky'"<br/>
     *      1.array("id=1","name='sky'")<br/>
     *      2.array("id"=>"1","name"=>"sky")<br/>
     *      3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如："(id=1 and name='sky') or (name like 'sky')"<br/>
     * @param string $sort 排序条件<br/>
     * 示例如下：<br/>
     *      1.id asc;<br/>
     *      2.name desc;<br/>
     * @param string $limit 分页数量:limit起始数被改写，默认从1开始，如果是0，同Mysql limit语法；
     * 示例如下：<br/>
     *    6,10<br/>  从第6条开始取10条(如果是mysql的limit，意味着从第五条开始，框架里不是这个意义。)
     *    1,10<br/> (相当于第1-第10条)
     *    10 <br/>(相当于第1-第10条)
     * @return 对象列表数组
     */
    public function get($filter=null,$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID, $limit=null)
    {
        $dataobject_class=self::std($this->classname());
        if (class_exists($dataobject_class)){
            return call_user_func_array($dataobject_class."::get",array($filter,$sort,$limit));
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return null;
        }
    }

    /**
     * 查询得到单个对象实体
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下：<br/>
     *      0."id=1,name='sky'"<br/>
     *      1.array("id=1","name='sky'")<br/>
     *      2.array("id"=>"1","name"=>"sky")<br/>
     *      3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
     * @param string $sort 排序条件<br/>
     * 示例如下：<br/>
     *      1.id asc;<br/>
     *      2.name desc;<br/>
     * @return 单个对象实体
     */
    public function get_one($filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID)
    {
        $dataobject_class=self::std($this->classname());
        if (class_exists($dataobject_class)){
            return call_user_func_array($dataobject_class."::get_one",array($filter,$sort));
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return null;
        }
    }

    /**
     * 根据表ID主键获取指定的对象[ID对应的表列]
     * @param string $id
     * @return 对象
     */
    public function get_by_id($id)
    {
        $dataobject_class=self::std($this->classname());
        if (class_exists($dataobject_class)){
            return call_user_func($dataobject_class."::get_by_id",$id);
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return null;
        }
    }

    /**
     * 对象总计数
     * @param object|string|array $filter<br/>
     *      $filter 格式示例如下：<br/>
     *          0.允许对象如new User(id="1",name="green");<br/>
     *          1."id=1","name='sky'"<br/>
     *          2.array("id=1","name='sky'")<br/>
     *          3.array("id"=>"1","name"=>"sky")
     * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
     * @return 数据对象总计数
     */
    public function count($filter=null)
    {
        $dataobject_class=self::std($this->classname());
        if (class_exists($dataobject_class)){
            return call_user_func($dataobject_class."::count",$filter);
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return 0;
        }
    }

    /**
     * 数据对象分页
     * @param int $startPoint  分页开始记录数
     * @param int $endPoint    分页结束记录数
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下：<br/>
     *      0."id=1,name='sky'"<br/>
     *      1.array("id=1","name='sky'")<br/>
     *      2.array("id"=>"1","name"=>"sky")<br/>
     *      3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
     * @param string $sort 排序条件<br/>
     * 默认为 id desc<br/>
     * 示例如下：<br/>
     *      1.id asc;<br/>
     *      2.name desc;
     * @return mixed 数据对象分页查询列表
     */
    public function queryPage($startPoint,$endPoint,$filter=null,$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID)
    {
        $dataobject_class=self::std($this->classname());
        if (class_exists($dataobject_class)){
            return call_user_func_array($dataobject_class."::queryPage",array($startPoint,$endPoint,$filter,$sort));
        }else{
            LogMe::log(Wl::ERROR_INFO_OBJECT_UNKNOWN);
            return null;
        }
    }

    /**
     * 直接执行SQL语句
     * @param mixed $sql SQL查询|更新|删除语句
     * @return array
     *  1.执行查询语句返回对象数组
     *  2.执行更新和删除SQL语句返回执行成功与否的true|null
     */
    public function sqlExecute($sql)
    {
        $dataobject_class=self::std($this->classname());
        return self::dao()->sqlExecute($sql,$dataobject_class);
    }

    /**
     * 根据数据对象服务名称获取当前数据对象
     */
    private static function std($current_servicename)
    {
        if (array_key_exists($current_servicename,self::$std)){
            $current_dataobjectname=self::$std[$current_servicename];
        }else{
            $current_servicename=str_replace("ExtService","",$current_servicename);
            $current_servicename=str_replace("Service_","",$current_servicename);
            $current_servicename=str_replace("Service","",$current_servicename);
            $current_dataobjectname=ucfirst($current_servicename);
        }
        return $current_dataobjectname;
    }

    /**
     * 将过滤条件转换成需查询的模糊条件
     * @param array|object $filter 过滤条件
     * @return string 查询条件
     */
    protected function filtertoCondition($filter)
    {
        if (is_array($filter)){
            $condition=$filter;
        }else if (is_object($filter)){
            $condition=UtilObject::object_to_array($filter);
        }
        if (!empty($condition)&&(count($condition)>0)){
            $conditionArr=array();
            foreach ($condition as $key=>$value) {
                if (empty($value)&&$value!==0&&$value!=='0')continue;
                if (!UtilString::is_utf8($value)){
                    $value=UtilString::gbk2utf8($value);
                }
                if (is_int($value)||is_bool($value)){
                    $conditionArr[]=$key."='".$value."'";
                }else if (contain($value,"T00:00:00")){
                    $value=str_replace("T00:00:00","",$value);
                    $conditionArr[]=$key."='".$value."'";
                }else{
                    if (is_numeric($value)){
                        $judgeKey=strtolower($key);
                        if (contains($judgeKey,array("type","stat"))){//如果是枚举类型
                            $conditionArr[]=$key."='".$value."'";
                            continue;
                        }
                    }
                    $conditionArr[]=$key." like '%".$value."%'";
                }

            }
            $condition=implode(" and ",$conditionArr);
        }else{
            $condition="";
        }
        return $condition;
    }

    /**
     * 上传图片文件
     * @param array $files 上传的文件对象
     * @param array $uploadFlag 上传标识,上传文件的input组件的名称
     * @param array $upload_dir 上传文件存储的所在目录[最后一级目录，一般对应图片列名称]
     * @param array $categoryId 上传文件所在的目录标识，一般为类实例名称
     * @return array 反馈信息
     */
    public function uploadImage($files,$uploadFlag,$upload_dir,$categoryId="default")
    {
        $diffpart=date("YmdHis");
        $result="";
        if (!empty($files[$uploadFlag])&&!empty($files[$uploadFlag]["name"])){
            $tmptail = end(explode('.', $files[$uploadFlag]["name"]));
            $uploadPath =GC::$upload_path."images".DS.$categoryId.DS.$upload_dir.DS.$diffpart.".".$tmptail;
            $result     =UtilFileSystem::uploadFile($files,$uploadPath,$uploadFlag);
            if ($result&&($result['success']==true)){
                $result['file_name']="$categoryId/$upload_dir/$diffpart.$tmptail";
            }else{
                return $result;
            }
        }
        return $result;
    }

    /**
     * 批量上传图片
     * @param mixed $files 上传文件服务器变量
     * @param mixed $upload_field_name 上传文件的input的name
     * @param mixed $class_name 数据对象类名
     * @param mixed $classname_comment 数据对象类名注释简介
     * @param mixed $img_column_name 数据对象指定图像列名
     * @return array 上传图片反馈信息
     */
    public function batchUploadImages($files,$upload_field_name,$class_name,$classname_comment,$img_column_name)
    {
        $instance_name=strtolower($class_name);
        if (!empty($files)&&!empty($files[$upload_field_name]["name"]))
        {
            //上传压缩文件并解压
            $filename   = date("YmdHis");
            $upload_dir = GC::$upload_path."images".DS.$instance_name.DS;
            $upload_zip_dir=$upload_dir."zip".DS;
            $tmptail    = end(explode('.', $files[$upload_field_name]["name"]));
            $uploadPath = $upload_zip_dir.$filename.".".$tmptail;
            UtilFileSystem::createDir($upload_zip_dir);
            $IsUploadSucc=move_uploaded_file($files[$upload_field_name]["tmp_name"], $uploadPath);
            $zip = new ZipArchive;
            $res = $zip->open($uploadPath);
            if ($res === TRUE) {
                $zip->extractTo($upload_zip_dir);
                $zip->close();
            } else {
                return array('success' => false,'data'    => '上传压缩文件无法解压，查看是否压缩文件不正确！');
            }
            //与数据对象的图片路径信息列比对，如果是中文名同名，转换成拼音名文件存储并更新数据库信息；
            //如果是英文字母和数字则保持原名称并覆盖同名文件。如果没有找到同名文件，提示信息未找到的文件。
            $allImageFiles=UtilFileSystem::getAllFilesInDirectory($upload_zip_dir,array("jpg","png","gif","JPG","PNG","GIF"));
            $info_noneed="";$info_failed="";
            foreach ($allImageFiles as $imgFile) {
                $extension=UtilFileSystem::fileExtension($imgFile);
                $query_imgFile=UtilString::gbk2utf8(basename($imgFile,".".$extension));
                $img_object=call_user_func("$class_name::get_one","$img_column_name like '%$query_imgFile%'");
                //$class_name::get_one("$img_column_name like '%$query_imgFile%'");
                $imgFile=basename($imgFile);
                if ($img_object){
                    if (UtilString::is_chinese($query_imgFile)){
                        $image_name=UtilPinyin::translate($query_imgFile);
                        $img_object->{$img_column_name}=$instance_name."/".$image_name.".".$extension;
                        $img_object->update();
                    }
                    if (!copy($upload_zip_dir.$imgFile, $upload_dir.$image_name.".".$extension)) {
                        $info_failed.= "上传文件失败:".$uploadPath.$imgFile."<br/>";
                    }
                }else{
                    if (UtilString::is_chinese($query_imgFile)){
                        $image_name=UtilPinyin::translate($query_imgFile);
                        $img_object=call_user_func("$class_name::get_one","$img_column_name like '%$image_name%'");
                        //Product::get_one("$img_column_name like '%$image_name%'");
                        if ($img_object){
                            if (!copy($upload_zip_dir.$imgFile, $upload_dir.$image_name.".".$extension)) {
                                $info_failed.= "上传文件失败:".$uploadPath.$imgFile."<br/>";
                            }
                        }else{
                            $info_noneed.="上传文件".$imgFile."在记录中不存在！<br/>";
                        }
                    }else{
                        $info_noneed.="上传文件".$imgFile."在记录中不存在！<br/>";
                    }
                }
            }
            if (!empty($info_noneed)){
                $info_noneed="请先批量上传$classname_comment数据(Excel文档格式)，并在图片列中指定图片文件名！<br/>".$info_noneed;
            }
            //删除压缩文件目录
            $isRmSucc=UtilFileSystem::rmdir($upload_zip_dir);
        }
        if ((empty($info_noneed))&&(empty($info_failed))){
            return array('success' => true,'data' => true);
        }else{
            $info = $info_noneed . $info_failed;
            // if ($info) $info = str_replace("<br/>", "    ", $info);
            return array('success' => false,'data' => $info);
        }
    }
}
?>
