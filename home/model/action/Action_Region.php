<?php
/**
 +---------------------------------------<br/>
 * 控制器:地区<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Region extends ActionModel
{
    /**
     * 地区列表
     */
    public function lists()
    {
        if ($this->isDataHave(TagPageService::$linkUrl_pageFlag)){
            $nowpage=$this->data[TagPageService::$linkUrl_pageFlag];
        }else{
            $nowpage=1;
        }
        $count=Region::count();
        $this->view->countRegions=$count;
        if($count>0){
            $bb_page=TagPageService::init($nowpage,$count);
            $regions = Region::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
            foreach ($regions as $region) {
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
            $this->view->set("regions",$regions);
        }
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
     * 查看地区
     */
    public function view()
    {
        $regionId=$this->data["id"];
        $region = Region::get_by_id($regionId);
        $region_instance=null;
        if ($region->parent_id){
            $region_instance=Region::get_by_id($region->parent_id);
            $region['region_name_parent']=$region_instance->region_name;
        }
        if ($region_instance){
            $level=$region_instance->level;
            $region["regionShowAll"]=$this->regionShowAll($region->parent_id,$level);
        }
        $this->view->set("region",$region);
    }
    /**
     * 编辑地区
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $region = $this->model->Region;
            $id= $region->getId();
            $isRedirect=true;
            if (!empty($id)){
                $region->update();
            }else{
                $id=$region->save();
            }
            if ($isRedirect){
                $this->redirect("region","view","id=$id");
                exit;
            }
        }
        $regionId=$this->data["id"];
        $region = Region::get_by_id($regionId);
        $this->view->set("region",$region);
    }
    /**
     * 删除地区
     */
    public function delete()
    {
        $regionId=$this->data["id"];
        $isDelete = Region::deleteByID($regionId);
        $this->redirect("region","lists",$this->data);
    }
}

?>