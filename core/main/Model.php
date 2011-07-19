<?php
/**
 +--------------------------------------------------<br/>
 * Model<br/>
 * 用于MVC框架中单例实例化一个对象并对其进行管理<br/>
 +--------------------------------------------------<br/>
 * @category betterlife
 * @package core.main
 * @author skygreen
 */
class Model {
    /**
     * 来自用户请求的用户数据
     * @var array 
     */
    private $data;
    /**
     * 已经加载的单例实体
     * @var array
     */
    private $loaded = array();
    /**
     * 控制器
     * @var Action 
     */
    private $action;
    /**
     * 获取实体
     * @param string $model
     * @return 实体
     */
    public function __get($model) {
        $isExistModel=false;
        foreach (Initializer::$moduleFiles as $moduleFile) {
            if (array_key_exists($model, $moduleFile)) {
                include_once($moduleFile[$model]);
                if (empty($this->loaded[$model])) {
                    $instance_model=new $model();
                    if ($instance_model instanceof DataObject) {
                        $this->loaded[$model]=$instance_model;
                    }else {
                        throw new Exception("{$model}".Wl::ERROR_INFO_EXTENDS_CLASS);
                    }
                }
                $modelobj = $this->loaded[$model];
                UtilObject::array_to_object($this->getData(), $modelobj);
                $isExistModel=true;
                return $modelobj;
            }
        }
        if (!$isExistModel) {
            e(Wl::ERROR_INFO_MODEL_EXISTS." {$model}");
        }
    }
    /**
     * 设置控制器
     * @param object $action 
     */ 
    public function setAction($action) {
        $this->action=$action;
    }
    /**
     * 返回控制器
     * @return object 
     */
    public function getAction() {
        return  $this->action;
    }    
    /**
     * 获取数据
     * @return array 
     */
    private function getData() {
        if (empty($this->action)) {
            $this->data=array_merge($_POST,$_GET);
        }else {
            $this->data=$this->action->getData();
        }
        return $this->data;
    }
}
?>
