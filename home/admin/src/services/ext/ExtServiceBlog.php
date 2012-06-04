<?php
//加载初始化设置
class_exists("Service")||require("../init.php");
/**
 +---------------------------------------<br/>
 * 服务类:博客<br/>
 +---------------------------------------
 * @category betterlife
 * @package admin.services
 * @subpackage ext
 * @author skygreen skygreen2001@gmail.com
 */
class ExtServiceBlog extends ServiceBasic
{
	/**
	 * 保存数据对象:博客
	 * @param array|DataObject $blog
	 * @return int 保存对象记录的ID标识号
	 */
	public function save($blog)
	{
		if (is_array($blog)){
			$blogObj=new Blog($blog);
		}
		if ($blogObj instanceof Blog){
			$data=$blogObj->save();
		}else{
			$data=false;
		}
		return array(
			'success' => true,
			'data'    => $data
		); 
	}

	/**
	 * 更新数据对象 :博客
	 * @param array|DataObject $blog
	 * @return boolen 是否更新成功；true为操作正常
	 */
	public function update($blog)
	{
		if (is_array($blog)){
			$blogObj=new Blog($blog);
		}
		if ($blogObj instanceof Blog){
			$data=$blogObj->update();
		}else{
			$data=false;
		}
		return array(
			'success' => true,
			'data'    => $data
		); 
	}

	/**
	 * 根据主键删除数据对象:博客的多条数据记录
	 * @param array|string $ids 数据对象编号
	 * 形式如下:
	 * 1.array:array(1,2,3,4,5)
	 * 2.字符串:1,2,3,4 
	 * @return boolen 是否删除成功；true为操作正常
	 */
	public function deleteByIds($ids)
	{
		$data=Blog::deleteByIds($ids);
		return array(
			'success' => true,
			'data'    => $data
		); 
	}

	/**
	 * 数据对象:博客分页查询
	 * @param stdclass $formPacket  查询条件对象
	 * 必须传递分页参数：start:分页开始数，默认从0开始
	 *                   limit:分页查询数，默认10个。
	 * @return 数据对象:博客分页查询列表
	 */
	public function queryPageBlog($formPacket=null)
	{
		$start=1;
		$limit=10;
		$condition=UtilObject::object_to_array($formPacket);
		if (isset($condition['start'])){
			$start=$condition['start']+1;
		  }
		if (isset($condition['limit'])){
			$limit=$condition['limit']; 
			$limit=$start+$limit-1; 
		}
		unset($condition['start'],$condition['limit']);
		if (!empty($condition)&&(count($condition)>0)){
			$conditionArr=array();
			foreach ($condition as $key=>$value) {
				if (!UtilString::is_utf8($value)){
					$value=UtilString::gbk2utf8($value);
				}
				if (is_numeric($value)){ 
					$conditionArr[]=$key."=".$value;
				}else{
					$conditionArr[]=$key." like '%".$value."%'"; 
				}
			}
			$condition=implode(" and ",$conditionArr);
		}
		$count=Blog::count($condition);
		if ($count>0){
			if ($limit>$count)$limit=$count;
			$data =Blog::queryPage($start,$limit,$condition);
			foreach ($data as $blog) {
				$user=User::get_by_id($blog->user_id);
				$blog['username']=$user->username;                
				if ($blog["commitTime"]){
					UtilDateTime::ChinaTime();
					$blog["commitTime"]=UtilDateTime::timestampToDateTime($blog["commitTime"]);
				}       
			}
			if ($data==null)$data=array();
		}else{
			$data=array();
		}
		return array(
			'success' => true,
			'totalCount'=>$count,
			'data'    => $data
		); 
	}

	/**
	 * 批量上传博客
	 * @param mixed $upload_file <input name="upload_file" type="file">
	 */
	public function import($_FILES)
	{
		$diffpart=date("YmdHis");
		if (!empty($_FILES["upload_file"])){
			$tmptail = end(explode('.', $_FILES["upload_file"]["name"]));
			$uploadPath =GC::$attachment_path."blog\\import\\blog$diffpart.$tmptail";
			$result     =UtilFileSystem::uploadFile($_FILES,$uploadPath);
			if ($result&&($result['success']==true)){
				if (array_key_exists('file_name',$result)){
					$arr_import_header = self::fieldsMean(Blog::tablename());
					$data              = UtilExcel::exceltoArray($uploadPath,$arr_import_header);
					$result=false;
					foreach ($data as $blog) {
						$blog=new Blog($blog);
						$blog_id=$blog->getId();
						if (!empty($blog_id)){
							$hadBlog=Blog::get_by_id($blog->getId());
							if ($hadBlog!=null){
								$result=$blog->update();
							}else{
								$result=$blog->save();
							}
						}else{
							$result=$blog->save();
						}
					}
				}else{
					$result=false;
				}
			}else{
				return $result;
			}
		}
		return array(
			'success' => true,
			'data'    => $result
		);
	}

	/**
	 * 导出博客
	 * @param mixed $filter
	 */
	public function exportBlog($filter=null)
	{
		$data=Blog::get($filter);
		$arr_output_header= self::fieldsMean(Blog::tablename()); 
		unset($arr_output_header['updateTime']);
		unset($arr_output_header['commitTime']);
		$diffpart=date("YmdHis");
		$outputFileName=Gc::$attachment_path."blog\\export\\blog$diffpart.xls"; 
		UtilFileSystem::createDir(dirname($outputFileName)); 
		UtilExcel::arraytoExcel($arr_output_header,$data,$outputFileName,false); 
		$downloadPath  =Gc::$attachment_url."blog/export/blog$diffpart.xls"; 
		return array(
			'success' => true,
			'data'    => $downloadPath
		); 
	}
}
?>