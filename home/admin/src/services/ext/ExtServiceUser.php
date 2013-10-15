<?php
//加载初始化设置
class_exists("Service")||require("../init.php");
/**
 +---------------------------------------<br/>
 * 服务类:用户<br/>
 +---------------------------------------
 * @category betterlife
 * @package admin.services
 * @subpackage ext
 * @author skygreen skygreen2001@gmail.com
 */
class ExtServiceUser extends ServiceBasic
{
	/**
	 * 保存数据对象:用户
	 * @param array|DataObject $user
	 * @return int 保存对象记录的ID标识号
	 */
	public function save($user)
	{
		if (is_array($user)){
			$userObj=new User($user);
		}
		if ($userObj instanceof User){
            $userObj->password=md5($userObj->password); 
			$data=$userObj->save();
		}else{
			$data=false;
		}
		return array(
			'success' => true,
			'data'    => $data
		); 
	}

	/**
	 * 更新数据对象 :用户
	 * @param array|DataObject $user
	 * @return boolen 是否更新成功；true为操作正常
	 */
	public function update($user)
	{                                                                            
		if (is_array($user)){
			$userObj=new User($user);
		}
		if ($userObj instanceof User){
            if(!empty($userObj->password))
            {
                $userObj->password=md5($userObj->password);     
            }else{
                $userObj->password=$user["password_old"];
            }
			$data=$userObj->update();
		}else{
			$data=false;
		}
		return array(
			'success' => true,
			'data'    => $data
		); 
	}

	/**
	 * 根据主键删除数据对象:用户的多条数据记录
	 * @param array|string $ids 数据对象编号
	 * 形式如下:
	 * 1.array:array(1,2,3,4,5)
	 * 2.字符串:1,2,3,4 
	 * @return boolen 是否删除成功；true为操作正常
	 */
	public function deleteByIds($ids)
	{
		$data=User::deleteByIds($ids);
		return array(
			'success' => true,
			'data'    => $data
		); 
	}

	/**
	 * 数据对象:用户分页查询
	 * @param stdclass $formPacket  查询条件对象
	 * 必须传递分页参数：start:分页开始数，默认从0开始
	 *                   limit:分页查询数，默认10个。
	 * @return 数据对象:用户分页查询列表
	 */
	public function queryPageUser($formPacket=null)
	{
		$start=1;
		$limit=15;
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
		$count=User::count($condition);
		if ($count>0){
			if ($limit>$count)$limit=$count;
			$data =User::queryPage($start,$limit,$condition);
			foreach ($data as $user) {
				if ($user->department_id){
					$department_instance=Department::get_by_id($user->department_id);
					$user['department_name']=$department_instance->department_name;
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
	 * 根据管理员标识显示管理员信息 
	 * @param mixed $viewId
	 */
	public function viewUser($viewId)
	{
		if (!empty($viewId)){
			$user=User::get_by_id($viewId);
			if (!empty($user))
			{
				if ($user->department_id){
					$department_instance=Department::get_by_id($user->department_id);
					$user['department_name']=$department_instance->department_name;
				}                
			}
			return array(
				'success' => true,
				'data'    => $user
			);
		}
		return array(
			'success' => false,
			'msg'     => "无法查找到需查看的用户信息！"
		);
	}

	/**
	 * 批量上传用户
	 * @param mixed $upload_file <input name="upload_file" type="file">
	 */
	public function import($files)
	{
		$diffpart=date("YmdHis");
		if (!empty($files["upload_file"])){
			$tmptail = end(explode('.', $files["upload_file"]["name"]));
			$uploadPath =GC::$attachment_path."user".DIRECTORY_SEPARATOR."import".DIRECTORY_SEPARATOR."user$diffpart.$tmptail";
			$result     =UtilFileSystem::uploadFile($files,$uploadPath);
			if ($result&&($result['success']==true)){
				if (array_key_exists('file_name',$result)){
					$arr_import_header = self::fieldsMean(User::tablename());
					$data              = UtilExcel::exceltoArray($uploadPath,$arr_import_header);
					$result=false;
					foreach ($data as $user) {
						$user=new User($user);
						$user_id=$user->getId();
						if (!empty($user_id)){
							$hadUser=User::existByID($user->getId());
							if ($hadUser){
								$result=$user->update();
							}else{
								$result=$user->save();
							}
						}else{
							$result=$user->save();
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
	 * 导出用户
	 * @param mixed $filter
	 */
	public function exportUser($filter=null)
	{
		if ($filter)$filter=$this->filtertoCondition($filter);
		$data=User::get($filter);
		$arr_output_header= self::fieldsMean(User::tablename());  
        foreach ($data as $user) {
            if ($user->department_id){
                $department_instance=Department::get_by_id($user->department_id);
                $user['department_id']=$department_instance->department_name;
            }
        }                                              
        unset($arr_output_header['updateTime'],$arr_output_header['commitTime']);
		$diffpart=date("YmdHis");
		$outputFileName=Gc::$attachment_path."user".DIRECTORY_SEPARATOR."export".DIRECTORY_SEPARATOR."user$diffpart.xls"; 
		UtilExcel::arraytoExcel($arr_output_header,$data,$outputFileName,false); 
		$downloadPath  =Gc::$attachment_url."user/export/user$diffpart.xls"; 
		return array(
			'success' => true,
			'data'    => $downloadPath
		); 
	}
}
?>