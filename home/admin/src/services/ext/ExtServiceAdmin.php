<?php
//加载初始化设置
class_exists("Service")||require("../init.php");
/**
 +---------------------------------------<br/>
 * 服务类:系统管理人员<br/>
 +---------------------------------------
 * @category betterlife
 * @package admin.services
 * @subpackage ext
 * @author skygreen skygreen2001@gmail.com
 */
class ExtServiceAdmin extends ServiceBasic
{
	/**
	 * 保存数据对象:系统管理人员
	 * @param array|DataObject $admin
	 * @return int 保存对象记录的ID标识号
	 */
	public function save($admin)
	{
		if (is_array($admin)){
			$adminObj=new Admin($admin);
		}
		if ($adminObj instanceof Admin){
			$data=$adminObj->save();
		}else{
			$data=false;
		}
		return array(
			'success' => true,
			'data'    => $data
		); 
	}

	/**
	 * 更新数据对象 :系统管理人员
	 * @param array|DataObject $admin
	 * @return boolen 是否更新成功；true为操作正常
	 */
	public function update($admin)
	{
		if (!EnumRoletype::isEnumValue($admin["roletype"])){
			$admin["roletype"]=EnumRoletype::roletypeByShow($admin["roletype"]);
		}
		if (!EnumSeescope::isEnumValue($admin["seescope"])){
			$admin["seescope"]=EnumSeescope::seescopeByShow($admin["seescope"]);
		}
		if (is_array($admin)){
			$adminObj=new Admin($admin);
		}
		if ($adminObj instanceof Admin){
			$data=$adminObj->update();
		}else{
			$data=false;
		}
		return array(
			'success' => true,
			'data'    => $data
		); 
	}

	/**
	 * 根据主键删除数据对象:系统管理人员的多条数据记录
	 * @param array|string $ids 数据对象编号
	 * 形式如下:
	 * 1.array:array(1,2,3,4,5)
	 * 2.字符串:1,2,3,4 
	 * @return boolen 是否删除成功；true为操作正常
	 */
	public function deleteByIds($ids)
	{
		$data=Admin::deleteByIds($ids);
		return array(
			'success' => true,
			'data'    => $data
		); 
	}

	/**
	 * 数据对象:系统管理人员分页查询
	 * @param stdclass $formPacket  查询条件对象
	 * 必须传递分页参数：start:分页开始数，默认从0开始
	 *                   limit:分页查询数，默认10个。
	 * @return 数据对象:系统管理人员分页查询列表
	 */
	public function queryPageAdmin($formPacket=null)
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
		$condition=$this->filtertoCondition($condition);
		$count=Admin::count($condition);
		if ($count>0){
			if ($limit>$count)$limit=$count;
			$data =Admin::queryPage($start,$limit,$condition);
			if ((!empty($data))&&(count($data)>0))
			{
				Admin::propertyShow($data,array('roletype','seescope'));
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
	 * 根据管理员标识显示管理员信息 
	 * @param mixed $viewId 管理员标识
	 */
	public function viewAdmin($viewId)
	{
		if (!empty($viewId)){
			$admin=Admin::get_by_id($viewId);
			if (!empty($admin))
			{
				$admin->roletypeShow=$admin->getRoletypeShow();
				$admin->seescope=$admin->getSeescopeShow();
			}
			return array(
				'success' => true,
				'data'    => $admin
			);
		}
		return array(
			'success' => false,
			'msg'     => "无法查找到需查看的管理员信息！"
		);
	}


	/**
	 * 批量上传系统管理人员
	 * @param mixed $upload_file <input name="upload_file" type="file">
	 */
	public function import($_FILES)
	{
		$diffpart=date("YmdHis");
		if (!empty($_FILES["upload_file"])){
			$tmptail = end(explode('.', $_FILES["upload_file"]["name"]));
			$uploadPath =GC::$attachment_path."admin".DIRECTORY_SEPARATOR."import".DIRECTORY_SEPARATOR."admin$diffpart.$tmptail";
			$result     =UtilFileSystem::uploadFile($_FILES,$uploadPath);
			if ($result&&($result['success']==true)){
				if (array_key_exists('file_name',$result)){
					$arr_import_header = self::fieldsMean(Admin::tablename());
					$data              = UtilExcel::exceltoArray($uploadPath,$arr_import_header);
					$result=false;
					foreach ($data as $admin) {
						$admin=new Admin($admin);
						if (!EnumRoletype::isEnumValue($admin["roletype"])){
							$admin["roletype"]=EnumRoletype::roletypeByShow($admin["roletype"]);
						}
						if (!EnumSeescope::isEnumValue($admin["seescope"])){
							$admin["seescope"]=EnumSeescope::seescopeByShow($admin["seescope"]);
						}
						$admin_id=$admin->getId();
						if (!empty($admin_id)){
							$hadAdmin=Admin::get_by_id($admin->getId());
							if ($hadAdmin!=null){
								$result=$admin->update();
							}else{
								$result=$admin->save();
							}
						}else{
							$result=$admin->save();
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
	 * 导出系统管理人员
	 * @param mixed $filter
	 */
	public function exportAdmin($filter=null)
	{
		if ($filter)$filter=$this->filtertoCondition($filter);
		$data=Admin::get($filter);
		if ((!empty($data))&&(count($data)>0))
		{
			Admin::propertyShow($data,array('roleid'));
		}
		$arr_output_header= self::fieldsMean(Admin::tablename()); 
		unset($arr_output_header['updateTime']);
		unset($arr_output_header['commitTime']);
		$diffpart=date("YmdHis");
		$outputFileName=Gc::$attachment_path."admin".DIRECTORY_SEPARATOR."export".DIRECTORY_SEPARATOR."admin$diffpart.xls"; 
		UtilFileSystem::createDir(dirname($outputFileName)); 
		UtilExcel::arraytoExcel($arr_output_header,$data,$outputFileName,false); 
		$downloadPath  =Gc::$attachment_url."admin/export/admin$diffpart.xls"; 
		return array(
			'success' => true,
			'data'    => $downloadPath
		); 
	}
}
?>