<?php
/**
* 控制器:博客
*/
class Action_Blog extends Action 
{
    /**
    * 显示博客列表
    */
    public function display() 
    {    
        $this->loadCss();
        $user = HttpSession::get('userid');     
        if ($this->isDataHave(UtilPage::$linkUrl_pageFlag)){          
          $nowpage=$this->data[UtilPage::$linkUrl_pageFlag];
        }else{
          $nowpage=1; 
        }

        $count=Blog::count();
        $bb_page=UtilPage::init($nowpage,$count); 
        $posts = Blog::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
        //$posts = Manager_Db::newInstance()->dao()->sqlExecute("select top 3 * from bb_core_blog where name='阿什顿' order by id desc",Blog);
        if(!$posts) {
            $this->redirect("blog","write");
        }else {
            $view=new View_Blog($this);
            $view->posts=$posts;
            $view->countPosts=$count;            
            $this->view->viewObject=$view;
        }
    }
    /**
    * 提交评论
    */
    public function post() 
    {
        $this->loadCss();   
        $this->loadJs();           
        //UtilJavascript::loadJsReady($this->view->viewObject, "common/js/onlineditor/kindeditor/kindeditor.js");                                                                                                 
        //UtilXheditor::loadReady("comment","commentForm",$this->view->viewObject);        
        $postid= $this->data["id"];                      
        if (count($_POST)>0) {
            $comment = $this->model->Comment;
            $comment->id=null;
            $comment->blogId = $postid;
            $comment->setUserId(HttpSession::get('userid'));
            $comment->save();
            $this->view->message="评论提交成功";
            $view->view->color="green";             
        }
        $post = Blog::get_by_id($postid);   
        $view=new View_Blog($this);
        $view->post=$post;            
        $this->view->editorHtml=UtilCKEeditor::editorHtml("comment");                                
        $this->view->viewObject=$view;    
    }
    /**
    * 编写博客
    */
    public function write() 
    { 
        $this->loadCss();
        $this->loadJs();
        //UtilJavascript::loadJsReady($this->view->viewObject, "common/js/onlineditor/kindeditor/kindeditor.js");                                                                             
        //UtilXheditor::loadReady("content","postForm",$this->view->viewObject);            

        $this->view->color="green";  
        $content="";
        if (!empty($_POST)) {
            $post = $this->model->Blog;     
            $post->setUserId(HttpSession::get('userid'));
            $id= $post->getId();
            $post->setUserId(HttpSession::get('userid'));
            if (!empty($id)){
              $post->update();              
            }else{
              $post->save();              
            }    
            $content=$post->content;                                                                                                                    
            $this->view->message="博客提交成功";
            $view->view->color="green";                                                        
        }else{     
            $postid= @$this->data["id"];
            $view=new View_Blog($this); 
            if (count($_GET)>0&&$postid) {  
                $blog=Blog::get_by_id($postid); 
                $view->post=$blog;
                if ($blog){
                    $content=$blog->content;                                                                                  
                }                               
            }
            $this->view->viewObject=$view;                                                                     
        }        
        $this->view->editorHtml=UtilCKEeditor::editorHtml("content",$content);                    
    }
    /**
     * 删除博客
     */
    public function delete()
    {
      $postid= $this->data["id"]; 
      $blog=new Blog();
      $blog->setId($postid);
      $blog->delete();
      foreach($blog->comments() as $comment){
        $comment->delete();
      }
      $this->redirect("blog","display",$this->data);       
    }
}

/**
*  Blog表示层对象
*/
class View_Blog extends ViewObject
{ 
   public $post;   
   public $posts;
   public $countPosts;
   public function count_comments($post_id){
     return Comment::count("blogId=".$post_id);   
   }
}
?>
