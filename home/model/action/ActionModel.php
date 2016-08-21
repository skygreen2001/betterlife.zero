<?php
/**
 +----------------------------------------------<br/>
 * 所有Model应用控制器的父类<br/>
 +----------------------------------------------
 * @category betterlife
 * @package web.model
 * @author skygreen skygreen2001@gmail.com
 */
class ActionModel extends ActionBasic
{
    /**
     * 在Action所有的方法执行之前可以执行的方法
     */
    public function beforeAction()
    {
        parent::beforeAction();
    }

    /**
     * 在Action所有的方法执行之后可以执行的方法
     */
    public function afterAction()
    {
        parent::afterAction();
    }

    /**
     * 上传图片文件
     * @param array $files 上传的文件对象
     * @param array $uploadFlag 上传标识,上传文件的input组件的名称
     * @param array $upload_dir 上传文件存储的所在目录[最后一级目录，一般对应图片列名称]
     * @param array $categoryId 上传文件所在的目录标识，一般为类实例名称
     * @return array 是否创建成功。
     */
    public function uploadImg($files,$uploadFlag,$upload_dir,$categoryId="default")
    {
        $diffpart=date("YmdHis");
        $result="";
        if (!empty($files[$uploadFlag])&&!empty($files[$uploadFlag]["name"])){
            $tmptail = end(explode('.', $files[$uploadFlag]["name"]));
            $uploadPath =GC::$upload_path."images".DS.$categoryId.DS.$upload_dir.DS.$diffpart.".".$tmptail;
            $result     =UtilFileSystem::uploadFile($files,$uploadPath,$uploadFlag);
            if ($result&&($result['success']==true)){
                $result['file_name']="$categoryId/$upload_dir/$diffpart.$tmptail";
            }else{
                return $result;
            }
        }
        return $result;
    }
}

?>