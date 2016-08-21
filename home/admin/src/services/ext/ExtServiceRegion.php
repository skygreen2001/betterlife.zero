<?php
//加载初始化设置
class_exists("Service")||require("../init.php");
/**
 +---------------------------------------<br/>
 * 服务类:地区<br/>
 +---------------------------------------
 * @category betterlife
 * @package admin.services
 * @subpackage ext
 * @author skygreen skygreen2001@gmail.com
 */
class ExtServiceRegion extends ServiceBasic
{
    /**
     * 保存数据对象:地区
     * @param array|DataObject $region
     * @return int 保存对象记录的ID标识号
     */
    public function save($region)
    {
        if (is_array($region)){
            $regionObj=new Region($region);
        }
        if ($regionObj instanceof Region){
            $data=$regionObj->save();
        }else{
            $data=false;
        }
        return array(
            'success' => true,
            'data'    => $data
        ); 
    }

    /**
     * 更新数据对象 :地区
     * @param array|DataObject $region
     * @return boolen 是否更新成功；true为操作正常
     */
    public function update($region)
    {
        if (is_array($region)){
            $regionObj=new Region($region);
        }
        if ($regionObj instanceof Region){
            $data=$regionObj->update();
        }else{
            $data=false;
        }
        return array(
            'success' => true,
            'data'    => $data
        ); 
    }

    /**
     * 根据主键删除数据对象:地区的多条数据记录
     * @param array|string $ids 数据对象编号
     * 形式如下:
     * 1.array:array(1,2,3,4,5)
     * 2.字符串:1,2,3,4 
     * @return boolen 是否删除成功；true为操作正常
     */
    public function deleteByIds($ids)
    {
        $data=Region::deleteByIds($ids);
        return array(
            'success' => true,
            'data'    => $data
        ); 
    }

    /**
     * 数据对象:地区分页查询
     * @param stdclass $formPacket  查询条件对象
     * 必须传递分页参数：start:分页开始数，默认从0开始
     *                   limit:分页查询数，默认15个。
     * @return 数据对象:地区分页查询列表
     */
    public function queryPageRegion($formPacket=null)
    {
        $start=1;
        $limit=15;
        $condition=UtilObject::object_to_array($formPacket);
        if (isset($condition['start'])){
            $start=$condition['start']+1;
          }
        if (isset($condition['limit'])){
            $limit=$condition['limit']; 
            $limit=$start+$limit-1; 
        }
        unset($condition['start'],$condition['limit']);
        $condition=$this->filtertoCondition($condition);
        $count=Region::count($condition);
        if ($count>0){
            if ($limit>$count)$limit=$count;
            $data =Region::queryPage($start,$limit,$condition);
            if ((!empty($data))&&(count($data)>0))
            {
                Region::propertyShow($data,array('region_type'));
            }
            foreach ($data as $region) {
                $region_instance=null;
                if ($region->parent_id){
                    $region_instance=Region::get_by_id($region->parent_id);
                    $region['region_name_parent']=$region_instance->region_name;
                }
                if ($region_instance){
                    $level=$region_instance->level;
                    $region["regionShowAll"]=$this->regionShowAll($region->parent_id,$level);
                }
            }
            if ($data==null)$data=array();
        }else{
            $data=array();
        }
        return array(
            'success' => true,
            'totalCount'=>$count,
            'data'    => $data
        ); 
    }

    /**
     * 显示父地区[全]
     * 注:采用了递归写法
     * @param 对象 $parent_id 父地区标识
     * @param mixed $level 目录层级
     */
    private function regionShowAll($parent_id,$level)
    {
        $region_p=Region::get_by_id($parent_id);
        if ($level==1){
            $regionShowAll=$region_p->region_name;
        }else{
            $parent_id=$region_p->parent_id;
            $regionShowAll=$this->regionShowAll($parent_id,$level-1)."->".$region_p->region_name;
        }
        return $regionShowAll;
    }

    /**
     * 批量上传地区
     * @param mixed $upload_file <input name="upload_file" type="file">
     */
    public function import($files)
    {
        $diffpart=date("YmdHis");
        if (!empty($files["upload_file"])){
            $tmptail = end(explode('.', $files["upload_file"]["name"]));
            $uploadPath =GC::$attachment_path."region".DS."import".DS."region$diffpart.$tmptail";
            $result     =UtilFileSystem::uploadFile($files,$uploadPath);
            if ($result&&($result['success']==true)){
                if (array_key_exists('file_name',$result)){
                    $arr_import_header = self::fieldsMean(Region::tablename());
                    $data              = UtilExcel::exceltoArray($uploadPath,$arr_import_header);
                    $result=false;
                    foreach ($data as $region) {
                        if (!is_numeric($region["parent_id"])){
                            $region_all=$region["父地区[全]"];
                            if ($region_all){
                                $region_all_arr=explode("->",$region_all);
                                if ($region_all_arr){
                                    $level=count($region_all_arr);
                                    switch ($level) {
                                        case 1:
                                            $region_p=Region::get_one(array("region_name"=>$region_all_arr[0],"level"=>1));
                                            if ($region_p)$region["parent_id"]=$region_p->parent_id;
                                            break;
                                        case 2:
                                            $region_p=Region::get_one(array("region_name"=>$region_all_arr[0],"level"=>1));
                                            if ($region_p){
                                                $region_p=Region::get_one(array("region_name"=>$region_all_arr[1],"level"=>2,"parent_id"=>$region_p->parent_id));
                                                if ($region_p)$region["parent_id"]=$region_p->parent_id;
                                            }
                                            break;
                                        case 3:
                                            $region_p=Region::get_one(array("region_name"=>$region_all_arr[0],"level"=>1));
                                            if ($region_p){
                                                $region_p=Region::get_one(array("region_name"=>$region_all_arr[1],"level"=>2,"parent_id"=>$region_p->parent_id));
                                                if ($region_p){
                                                    $region_p=Region::get_one(array("region_name"=>$region_all_arr[2],"level"=>3,"parent_id"=>$region_p->parent_id));
                                                    if ($region_p)$region["parent_id"]=$region_p->parent_id;
                                                }
                                            }
                                            break;
                                       }
                                  }
                            }
                        }
                        $region=new Region($region);
                        if (!EnumRegionType::isEnumValue($region->region_type)){
                            $region->region_type=EnumRegionType::region_typeByShow($region->region_type);
                        }
                        $region_id=$region->getId();
                        if (!empty($region_id)){
                            $hadRegion=Region::existByID($region->getId());
                            if ($hadRegion){
                                $result=$region->update();
                            }else{
                                $result=$region->save();
                            }
                        }else{
                            $result=$region->save();
                        }
                    }
                }else{
                    $result=false;
                }
            }else{
                return $result;
            }
        }
        return array(
            'success' => true,
            'data'    => $result
        );
    }

    /**
     * 导出地区
     * @param mixed $filter
     */
    public function exportRegion($filter=null)
    {
        if ($filter)$filter=$this->filtertoCondition($filter);
        $data=Region::get($filter);
        if ((!empty($data))&&(count($data)>0))
        {
            Region::propertyShow($data,array('region_type'));
        }
        $arr_output_header= self::fieldsMean(Region::tablename()); 
        foreach ($data as $region) {
            if ($region->region_typeShow){
                $region['region_type']=$region->region_typeShow;
            }
            $region_instance=null;
            if ($region->parent_id){
                $region_instance=Region::get_by_id($region->parent_id);
                $region['parent_id']=$region_instance->region_name_parent;
            }
            if ($region_instance){
                $level=$region_instance->level;
                $region["regionShowAll"]=$this->regionShowAll($region->parent_id,$level);
                $region['parent_id']=$region_instance->region_name;
                $pos=UtilArray::keyPosition($arr_output_header,"parent_id");
                UtilArray::insert($arr_output_header,$pos+1,array('regionShowAll'=>"父地区[全]"));
            }
        }
        unset($arr_output_header['updateTime'],$arr_output_header['commitTime']);
        $diffpart=date("YmdHis");
        $outputFileName=Gc::$attachment_path."region".DS."export".DS."region$diffpart.xls"; 
        UtilExcel::arraytoExcel($arr_output_header,$data,$outputFileName,false); 
        $downloadPath  =Gc::$attachment_url."region/export/region$diffpart.xls"; 
        return array(
            'success' => true,
            'data'    => $downloadPath
        ); 
    }
}
?>