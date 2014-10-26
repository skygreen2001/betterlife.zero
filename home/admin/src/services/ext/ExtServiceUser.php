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
			'data'	=> $data
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
				$userObj->password= $user["password_old"];
			}
			$data=$userObj->update();
		}else{
			$data=false;
		}
		return array(
			'success' => true,
			'data'	=> $data
		); 
	}

	/**
	 * 更新数据对象:用户包括通知
	 * @param array|DataObject $conditions
	 * @return boolen 是否更新成功；true为操作正常
	 */
	public function updateUserNotice($conditions)
	{
		//$selData:选中通知；$oldData:已选通知,状态标识active为false,则其在此次操作被取消 ；$user_id:用户标识
		if (isset($conditions->selData))$selData=$conditions->selData;else $selData=null;
		if (isset($conditions->oldData))$oldData=$conditions->oldData;else $oldData=null;
		if (isset($conditions->user_id))$user_id=$conditions->user_id;else $user_id=null;
		$success  = false;
		$addcount = 0;//新增计数
		$delcount = 0;//取消计数
		if($user_id){
			$oldRetainArr = array();//保留的已关联通知
			//处理已关联的通知
			if($oldData){
				foreach($oldData as $okey=>$ovalue){
					if(!$ovalue->active){
						$usernotice=Usernotice::get_one(array("user_id"=>$user_id,"notice_id"=>$okey));
						if($usernotice)$usernotice->delete();
						$delcount++;
					}else{
						$oldRetainArr[] = $okey;
					}
				}
			}
			if($selData){
				$selArr = array();//选择的通知
				//转为goods_id数组
				foreach($selData as $skey=>$svalue){
					$selArr[] = $skey;
				}
				$insertArr = array_diff($selArr,$oldRetainArr);//需新插入的通知
				if($insertArr){
					foreach($insertArr as $notice_id){
						$usernotice=Usernotice::get_one(array("user_id"=>$user_id,"notice_id"=>$notice_id));
						if(!$usernotice){
							$usernotice=new Usernotice(array("user_id"=>$user_id,"notice_id"=>$notice_id));
							$usernotice->save();
							$addcount++;
						}
					}
				}
			}
			$success = true;
		}
		return array(
			'success' => $success,
			'add'	 => $addcount,
			'del'	 => $delcount
		);
	}

	/**
	 * 更新数据对象:用户包括角色
	 * @param array|DataObject $conditions
	 * @return boolen 是否更新成功；true为操作正常
	 */
	public function updateUserRole($conditions)
	{
		//$selData:选中角色；$oldData:已选角色,状态标识active为false,则其在此次操作被取消 ；$user_id:用户标识
		if (isset($conditions->selData))$selData=$conditions->selData;else $selData=null;
		if (isset($conditions->oldData))$oldData=$conditions->oldData;else $oldData=null;
		if (isset($conditions->user_id))$user_id=$conditions->user_id;else $user_id=null;
		$success  = false;
		$addcount = 0;//新增计数
		$delcount = 0;//取消计数
		if($user_id){
			$oldRetainArr = array();//保留的已关联角色
			//处理已关联的角色
			if($oldData){
				foreach($oldData as $okey=>$ovalue){
					if(!$ovalue->active){
						$userrole=Userrole::get_one(array("user_id"=>$user_id,"role_id"=>$okey));
						if($userrole)$userrole->delete();
						$delcount++;
					}else{
						$oldRetainArr[] = $okey;
					}
				}
			}
			if($selData){
				$selArr = array();//选择的角色
				//转为goods_id数组
				foreach($selData as $skey=>$svalue){
					$selArr[] = $skey;
				}
				$insertArr = array_diff($selArr,$oldRetainArr);//需新插入的角色
				if($insertArr){
					foreach($insertArr as $role_id){
						$userrole=Userrole::get_one(array("user_id"=>$user_id,"role_id"=>$role_id));
						if(!$userrole){
							$userrole=new Userrole(array("user_id"=>$user_id,"role_id"=>$role_id));
							$userrole->save();
							$addcount++;
						}
					}
				}
			}
			$success = true;
		}
		return array(
			'success' => $success,
			'add'	 => $addcount,
			'del'	 => $delcount
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
			'data'	=> $data
		); 
	}

	/**
	 * 数据对象:用户分页查询
	 * @param stdclass $formPacket  查询条件对象
	 * 必须传递分页参数：start:分页开始数，默认从0开始
	 *				   limit:分页查询数，默认15个。
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
				$noticeArr=Usernotice::select("notice_id","user_id='".$user->user_id."'");
				$user->noticeStr = implode(",",$noticeArr);
				$roleArr=Userrole::select("role_id","user_id='".$user->user_id."'");
				$user->roleStr = implode(",",$roleArr);
			}
			if ($data==null)$data=array();
		}else{
			$data=array();
		}
		return array(
			'success' => true,
			'totalCount'=>$count,
			'data'	=> $data
		); 
	}

	/**
	 * 数据对象:用户包括通知分页查询
	 * @param stdclass $formPacket  查询条件对象
	 * 必须传递分页参数：start:分页开始数，默认从0开始
	 *				   limit:分页查询数，默认10个。
	 * @return 数据对象:主题包括课程分页查询列表
	 */
	public function queryPageUserNotice($formPacket=null)
	{
		$start=1;
		$limit=15;
		$user_id=$formPacket->user_id;
		$formPacket->user_id=null;
		$condition=UtilObject::object_to_array($formPacket);
		/**0:全部,1:已选择,2:未选择*/
		if (isset($condition["selectType"]))$selectType=$condition["selectType"];else $selectType=0;
		unset($condition["selectType"]);
		if (isset($condition['start']))$start=$condition['start']+1;
		if (isset($condition['limit']))$limit=$start+$condition['limit']-1;
		unset($condition['start'],$condition['limit']);
		if (isset($condition['selNotice'])){
			$selNotice=$condition['selNotice'];
			unset($condition['selNotice']);
		}
		if (!$selNotice) $selNotice = "''";
		$condition=$this->filtertoCondition($condition);
		switch ($selectType) {
		   case 0:
			 $count=Notice::count($condition);
			 break;
		   case 1:
			 $sql_count="select count(1) from ".Notice::tablename()." where notice_id in (".$selNotice.") ";
			 if (!empty($condition))$sql_count.=" and ".$condition;
			 $count=sqlExecute($sql_count);
			 break;
		   case 2:
			 $sql_count="select count(1) from ".Notice::tablename()." where notice_id not in (".$selNotice.") ";
			 if (!empty($condition))$sql_count.=" and ".$condition;
			 $count=sqlExecute($sql_count);
			 break;
		}

		if ($count>0){
			if ($limit>$count)$limit=$count;
			switch ($selectType) {
			   case 0:
				   $data =Notice::queryPage($start,$limit,$condition);
				   break;
			   case 1:
				   $sql_data="select * from ".Notice::tablename()." where notice_id in (".$selNotice.") ";
				   if (!empty($condition))$sql_data.=" and ".$condition;
				   if ($start)$start=$start-1;
				   $sql_data.=" limit $start,".($limit-$start+1);
				   $data=sqlExecute($sql_data,"Notice");
				   break;
			   case 2:
				   $sql_data="select * from ".Notice::tablename()." where notice_id not in (".$selNotice.") ";
				   if (!empty($condition))$sql_data.=" and ".$condition;
				   if ($start)$start=$start-1;
				   $sql_data.=" limit $start,".($limit-$start+1);
				   $data=sqlExecute($sql_data,"Notice");
				   break;
			}


			if ($data==null)$data=array();
		}else{
			$data=array();
		}
		return array(
			'success' => true,
			'totalCount'=>$count,
			'data'	=> $data
		);
	}

	/**
	 * 数据对象:用户包括角色分页查询
	 * @param stdclass $formPacket  查询条件对象
	 * 必须传递分页参数：start:分页开始数，默认从0开始
	 *				   limit:分页查询数，默认10个。
	 * @return 数据对象:主题包括课程分页查询列表
	 */
	public function queryPageUserRole($formPacket=null)
	{
		$start=1;
		$limit=15;
		$user_id=$formPacket->user_id;
		$formPacket->user_id=null;
		$condition=UtilObject::object_to_array($formPacket);
		/**0:全部,1:已选择,2:未选择*/
		if (isset($condition["selectType"]))$selectType=$condition["selectType"];else $selectType=0;
		unset($condition["selectType"]);
		if (isset($condition['start']))$start=$condition['start']+1;
		if (isset($condition['limit']))$limit=$start+$condition['limit']-1;
		unset($condition['start'],$condition['limit']);
		if (isset($condition['selRole'])){
			$selRole=$condition['selRole'];
			unset($condition['selRole']);
		}
		if (!$selRole) $selRole = "''";
		$condition=$this->filtertoCondition($condition);
		switch ($selectType) {
		   case 0:
			 $count=Role::count($condition);
			 break;
		   case 1:
			 $sql_count="select count(1) from ".Role::tablename()." where role_id in (".$selRole.") ";
			 if (!empty($condition))$sql_count.=" and ".$condition;
			 $count=sqlExecute($sql_count);
			 break;
		   case 2:
			 $sql_count="select count(1) from ".Role::tablename()." where role_id not in (".$selRole.") ";
			 if (!empty($condition))$sql_count.=" and ".$condition;
			 $count=sqlExecute($sql_count);
			 break;
		}

		if ($count>0){
			if ($limit>$count)$limit=$count;
			switch ($selectType) {
			   case 0:
				   $data =Role::queryPage($start,$limit,$condition);
				   break;
			   case 1:
				   $sql_data="select * from ".Role::tablename()." where role_id in (".$selRole.") ";
				   if (!empty($condition))$sql_data.=" and ".$condition;
				   if ($start)$start=$start-1;
				   $sql_data.=" limit $start,".($limit-$start+1);
				   $data=sqlExecute($sql_data,"Role");
				   break;
			   case 2:
				   $sql_data="select * from ".Role::tablename()." where role_id not in (".$selRole.") ";
				   if (!empty($condition))$sql_data.=" and ".$condition;
				   if ($start)$start=$start-1;
				   $sql_data.=" limit $start,".($limit-$start+1);
				   $data=sqlExecute($sql_data,"Role");
				   break;
			}


			if ($data==null)$data=array();
		}else{
			$data=array();
		}
		return array(
			'success' => true,
			'totalCount'=>$count,
			'data'	=> $data
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
			$uploadPath =GC::$attachment_path."user".DS."import".DS."user$diffpart.$tmptail";
			$result	 =UtilFileSystem::uploadFile($files,$uploadPath);
			if ($result&&($result['success']==true)){
				if (array_key_exists('file_name',$result)){
					$arr_import_header = self::fieldsMean(User::tablename());
					$data			  = UtilExcel::exceltoArray($uploadPath,$arr_import_header);
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
			'data'	=> $result
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
		unset($arr_output_header['updateTime'],$arr_output_header['commitTime']);
		$diffpart=date("YmdHis");
		$outputFileName=Gc::$attachment_path."user".DS."export".DS."user$diffpart.xls"; 
		UtilExcel::arraytoExcel($arr_output_header,$data,$outputFileName,false); 
		$downloadPath  =Gc::$attachment_url."user/export/user$diffpart.xls"; 
		return array(
			'success' => true,
			'data'	=> $downloadPath
		); 
	}
}
?>