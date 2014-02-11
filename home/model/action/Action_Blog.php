<?php
/**
 +---------------------------------------<br/>
 * 控制器:博客<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Blog extends ActionModel
{
    /**
     * 博客列表
     */
    public function lists()
    {
        if ($this->isDataHave(UtilPage::$linkUrl_pageFlag)){
            $nowpage=$this->data[UtilPage::$linkUrl_pageFlag];  
        }else{   
            $nowpage=1; 
        }
        $count=Blog::count();
        $bb_page=UtilPage::init($nowpage,$count);
        $this->view->countBlogs=$count;
        $blogs = Blog::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
        $this->view->set("blogs",$blogs);
    }
    /**
     * 查看博客
     */
    public function view()
    {
        $blogId=$this->data["id"]; 
        $blog = Blog::get_by_id($blogId); 
        $this->view->set("blog",$blog);
    }
    /**
     * 编辑博客
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $blog = $this->model->Blog;
            $id= $blog->getId(); 
            $isRedirect=true;
            if (!empty($id)){
                $blog->update(); 
            }else{
                $id=$blog->save();  
            }
            if ($isRedirect){
                $this->redirect("blog","view","id=$id");
                exit;
            }
        }
        $blogId=$this->data["id"];
        $blog = Blog::get_by_id($blogId);
        $this->view->set("blog",$blog); 
        //加载在线编辑器的语句要放在:$this->view->viewObject[如果有这一句]之后。
        $this->load_onlineditor('blog_content');
    }
    /**
     * 删除博客
     */
    public function delete()
    {
        $blogId=$this->data["id"]; 
        $isDelete = Blog::deleteByID($blogId); 
        $this->redirect("blog","lists",$this->data);
    }
}

?>