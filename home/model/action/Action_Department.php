<?php
/**
 +---------------------------------------<br/>
 * 控制器:用户所属部门<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Department extends ActionModel
{
    /**
     * 用户所属部门列表
     */
    public function lists()
    {
        if ($this->isDataHave(TagPageService::$linkUrl_pageFlag)){
            $nowpage=$this->data[TagPageService::$linkUrl_pageFlag];
        }else{
            $nowpage=1;
        }
        $count=Department::count();
        $this->view->countDepartments=$count;
        if($count>0){
            $bb_page=TagPageService::init($nowpage,$count);
            $departments = Department::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
            $this->view->set("departments",$departments);
        }
    }
    /**
     * 查看用户所属部门
     */
    public function view()
    {
        $departmentId=$this->data["id"];
        $department = Department::get_by_id($departmentId);
        $this->view->set("department",$department);
    }
    /**
     * 编辑用户所属部门
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $department = $this->model->Department;
            $id= $department->getId();
            $isRedirect=true;
            if (!empty($id)){
                $department->update();
            }else{
                $id=$department->save();
            }
            if ($isRedirect){
                $this->redirect("department","view","id=$id");
                exit;
            }
        }
        $departmentId=$this->data["id"];
        $department = Department::get_by_id($departmentId);
        $this->view->set("department",$department);
    }
    /**
     * 删除用户所属部门
     */
    public function delete()
    {
        $departmentId=$this->data["id"];
        $isDelete = Department::deleteByID($departmentId);
        $this->redirect("department","lists",$this->data);
    }
}

?>