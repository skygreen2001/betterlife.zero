<?php
/**
 +---------------------------------------<br/>
 * 控制器:用户详细信息<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Userdetail extends ActionModel
{
    /**
     * 用户详细信息列表
     */
    public function lists()
    {
        if ($this->isDataHave(UtilPage::$linkUrl_pageFlag)){
          $nowpage=$this->data[UtilPage::$linkUrl_pageFlag];  
        }else{   
          $nowpage=1; 
        }
        $count=Userdetail::count();
        $bb_page=UtilPage::init($nowpage,$count);
        $this->view->countUserdetails=$count;
        $userdetails = Userdetail::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
        $this->view->set("userdetails",$userdetails);
    }
    /**
     * 查看用户详细信息
     */
    public function view()
    {
        $userdetailId=$this->data["id"]; 
        $userdetail = Userdetail::get_by_id($userdetailId); 
        $this->view->set("userdetail",$userdetail);
    }
    /**
     * 编辑用户详细信息
     */
    public function edit()
    {                                                                               
        if (!empty($_POST)) {
            $userdetail = $this->model->Userdetail;
            $id= $userdetail->getId(); 
                               
            if (!empty($_FILES)&&!empty($_FILES["profileUpload"]["name"])){
                $result=$this->uploadImg($_FILES,"profileUpload","profile");
                if ($result&&($result['success']==true)){
                    if (array_key_exists('file_name',$result)){ 
                        $userdetail->profile = $result['file_name'];
                    }
                }
            }
            
            if (!empty($id)){
              $userdetail->update(); 
            }else{
              $id=$userdetail->save();  
            }
            $this->redirect("userdetail","view","id=$id");
        }else{
            $userdetailId=$this->data["id"];
            $userdetail = Userdetail::get_by_id($userdetailId);
            $this->view->set("userdetail",$userdetail); 
            //加载在线编辑器的语句要放在:$this->view->viewObject[如果有这一句]之后。
            $this->load_onlineditor('address');
        }
    }
    
    /**
     * 上传用户详细信息图片文件
     */
    public function uploadImg($files,$uploadFlag,$upload_dir)
    {
        $diffpart=date("YmdHis");
        $result="";
        if (!empty($files[$uploadFlag])&&!empty($files[$uploadFlag]["name"])){
            $tmptail = end(explode('.', $files[$uploadFlag]["name"]));
            $uploadPath =GC::$upload_path."images".DIRECTORY_SEPARATOR."userdetail".DIRECTORY_SEPARATOR.$upload_dir.DIRECTORY_SEPARATOR.$diffpart.".".$tmptail;
            $result     =UtilFileSystem::uploadFile($files,$uploadPath,$uploadFlag);
            if ($result&&($result['success']==true)){
                $result['file_name']="userdetail/$upload_dir/$diffpart.$tmptail";
            }else{
                return $result;
            }
        }
        return $result;
    }
    
    /**
     * 删除用户详细信息
     */
    public function delete()
    {
        $userdetailId=$this->data["id"]; 
        $isDelete = Userdetail::deleteByID($userdetailId); 
        $this->redirect("userdetail","lists",$this->data);
    }
}

?>