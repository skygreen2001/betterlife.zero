<?php
/**
 +---------------------------------------<br/>
 * 控制器:评论<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.front.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Comment extends Action
{
    /**
     * 评论列表
     */
    public function lists()
    {
        if ($this->isDataHave(UtilPage::$linkUrl_pageFlag)){
          $nowpage=$this->data[UtilPage::$linkUrl_pageFlag];  
        }else{   
          $nowpage=1; 
        }
        $count=Comment::count();
        $bb_page=UtilPage::init($nowpage,$count);
        $this->view->countComments=$count;
        $comments = Comment::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
        $this->view->set("comments",$comments);
    }
    /**
     * 查看评论
     */
    public function view()
    {
        $commentId=$this->data["id"]; 
        $comment = Comment::get_by_id($commentId); 
        $this->view->set("comment",$comment);
    }
    /**
     * 编辑评论
     */
    public function edit()
    {
        if (!empty($_POST)) {
            $comment = $this->model->Comment;
            $id= $comment->getId(); 
            if (!empty($id)){
              $comment->update(); 
            }else{
              $id=$comment->save();  
            }
            $this->redirect("comment","view","id=$id");
        }else{
            $commentId=$this->data["id"];
            $comment = Comment::get_by_id($commentId);
            $this->view->set("comment",$comment); 
        }
    }
    /**
     * 删除评论
     */
    public function delete()
    {
        $commentId=$this->data["id"]; 
        $isDelete = Comment::deleteByID($commentId); 
        $this->redirect("comment","lists",$this->data);
    }
}

?>