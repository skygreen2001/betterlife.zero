<?php
  /**
   +--------------------------------------------------<br/>
   * 表示层对象，需要框架支持Flexy|Smarty<br/>
   +--------------------------------------------------<br/>
   * @category betterlife
   * @package core.model
   * @author skygreen
   */
  class ViewObject extends stdClass{  
    /**
     * 当前Web应用路径
     * @var string 
     */  
    public $url_base;
    
    /**
     * Css预加载语句【在未到页面之前的所有加载Css语句】
     * @var string 
     */
    public $css_ready;
    /**
     * Javascript预加载语句【在未到页面之前的所有加载Javascript语句】
     * @var string 
     */
    public $js_ready;
    
    /**
     * 构造器
     * @param Action $action 显示器所在的控制器
     */
    public function __construct($action=null) {
        $this->init($action);
    }
    
    /**
     * 初始化工作:检测显示器所在的控制器是否已经拥有了Css和Javascript的预加载语句
     * @param Action $action 显示器所在的控制器
     */
    private function init($action=null)
    {
        if (($action instanceof Action)&&isset($action->view)){
            $viewObject=$action->view->viewObject;
            if ($viewObject instanceof ViewObject){
                if ($viewObject->css_ready)
                {
                    $this->css_ready=$viewObject->css_ready;
                }
                if ($viewObject->js_ready)
                {
                     $this->js_ready=$viewObject->js_ready;
                }
            }
        }        
    }
    
    /**
    * 获取类名
    */
    public static function get_Class()
    {
       return get_class();
    }
  }
?>
