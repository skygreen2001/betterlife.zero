<?php
/**
* 控制器:评论
*/
class Action_Comment extends Action 
{   
	/**
	* 提交评论|修改评论
	*/
	public function comment() 
	{                                                                                      
		$blog_id= $this->data["blog_id"];  
		if ($blog_id){
			$this->view->blog=Blog::get_by_id($blog_id);                      
		}else{
			$this->redirect("blog","display");
		}
		if (count($_POST)>0) {
			$comment = $this->model->Comment;     
			$comment->blog_id = $blog_id;
			$comment->user_id=HttpSession::get('user_id'); 
			if (!empty($comment->comment_id)){
			  $comment->update();    
			  $this->view->message="评论修改成功";          
			}else{
			  $comment->save();       
			  $this->view->message="评论提交成功";                        
			}              
			$this->data["comment_id"]=null;  
			$_GET["comment_id"]=null;       
			$view->view->color="green";             
		}                 
		$comment_id= $this->data["comment_id"];        
		$content="";
		if ($comment_id){
			$comment=Comment::get_by_id($comment_id);
			$content=$comment->comment;  
			$this->view->content=$content;                                                   
		}                                               													 
		$this->load_onlineditor("comment");                                                			         
	}
	/**
	 * 删除博客
	 */
	public function delete()
	{
	  $comment_id= $this->data["comment_id"]; 
	  $comment=new Comment();
	  $comment->setId($comment_id);
	  $comment->delete();   
	  unset($this->data["comment_id"]);
	  $this->redirect("comment","comment",$this->data);       
	}
	
	
}
?>
