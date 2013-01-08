<?php
/**
 +--------------------------------------------------<br/>
 * 比较直观可看的SQL修改构造器<br/>
 +--------------------------------------------------
 * @category betterlife
 * @package core.db.sql.util.crud
 * @author skygreen
 */
class Crud_Sql_Update extends Crud_SQL {
	private $values;
	/**
	 * 是否预处理SQL语句
	 *
	 * 推荐使用预处理SQL语句
	 *
	 * @param boolean $isPreparedStatement
	 */
	public function  __construct($isPreparedStatement=true) {
		$this->isPreparedStatement=$isPreparedStatement;
	}

	/**
	 * 更新表
	 * @param string $tableorclassName 表名|类名[映射表]
	 * @return SQL构造器本身
	 */
	public function update($tableorclassName) {
		if (class_exists($tableorclassName)){
			$this->tableName = Config_Db::orm($tableorclassName);;
		}else{
			$this->tableName = $tableorclassName;
		}
		return $this;
	}

	public function where() {
		$clause=func_get_args();
		$this->isPreparedStatement=false;
		return parent::where($clause);
	}

	/**
	 * 查询获取的列值，参考格式如下
	 * 未使用预处理SQL语句
	 * 1.set("id=1","name='sky'")
	 * 2.set(array("id"=>"1","name"=>"sky"))
	 * 使用预处理SQL语句
	 * 1.set("id=?","name='?'")
	 * 2.set(array("id"=>"1","name"=>"sky"))
	 */
	public function set() {
		$values=func_get_args();
		if (count($values)>0) {
			if (is_array($values[0])) {
				//第二种情况
				$values=$values[0];
				$asValues=array();
				$count=1;
				foreach ($values as $key => $value) {
					if ($this->isPreparedStatement) {
						if ($this->type_rep==1) {
							$asValues[$key]=$key.'=$'.($count++);
						}else {
							$asValues[$key]=$key."=?";
						}
					}else {
						$asValues[$key]=$key."='".$value."'";
						//修改bit类型处理
						if ($value===0){
							$asValues[$key]=$key."=0";
						}
						if ($value===1){
							$asValues[$key]=$key."=1";
						}
						if ($value===true){
							$asValues[$key]=$key."=true";
						}
						if ($value===false){
							$asValues[$key]=$key."=false";
						}
					}
				}
				$this->values =join(",",$asValues);
			}else {
				//第一种情况
				foreach ($values as &$value) {
					if (UtilString::contain($value, "=")) {
						$valueA=explode("=", $value);
						if (empty($valueA[1])) {
							$value=$valueA[0]."='".$valueA[1]."'";
						}else if (strlen(trim($valueA[1]))==0) {
							$value=$valueA[0]."='".$valueA[1]."'";
						}
					}
				}
				$this->values =join(",",$values);
			}
		}else {
			Exception_Db::recordException(Exception_Db::UPDATE_VALUE_NULL,__FILE__,__LINE__,$this);
		}
		return $this;
	}

	/**
	 * 生成需要的完整的SQL语句
	 * @return string SQL完整的语句
	 */
	public function result() {
		$this->query =self::SQL_UPDATE.$this->tableName.self::SQL_SET;
		$this->query.=$this->values;
		$this->query.=self::SQL_WHERE.$this->whereClause;
		return $this->query;
	}
}
?>
