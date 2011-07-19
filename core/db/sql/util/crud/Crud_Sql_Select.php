<?php
/**
 +--------------------------------------------------<br/>
 * 比较直观可看的SQL查询构造器<br/>
 * 示例：<br/>
 *   $sql= new SQL_Select();<br/>
 *   $sql->select("id","name")->from("users")->where("id=1")->limit(1)->result();<br/>
 +--------------------------------------------------
 * @category betterlife
 * @package core.db.sql.util.crud
 * @author skygreen
 */
class Crud_Sql_Select extends Crud_SQL {
   /**
    * @var array 查询列 
    */
    private $selectables = array();
    /**
     * @var array 关联表名 
     */
    private $join;
    /**
     * @var string 排序字段 
     */
    private $order;
    /**
     * @var string 分页 
     */
    private $limit;
    /**
     * @var string  分页 Postgres offset语法
     */
    private $offset;
    // 数据库表达式
    protected $comparison= array('eq'=>'=','neq'=>'!=','gt'=>'>','egt'=>'>=','lt'=>'<','elt'=>'<=','notlike'=>'NOT LIKE','like'=>'LIKE');
    // 查询表达式
    //protected $selectSql  =     'SELECT%DISTINCT% %FIELDS% FROM %TABLE%%JOIN%%WHERE%%GROUP%%HAVING%%ORDER%%LIMIT%';

    /**
     * 查询获取的列值，参考格式如下<br/>
     * 1.select("id","name")<br/>
     * 2.select(array("id","name"))<br/>
     * 3.select() 选择所有的列
     */
    public function select() {
        $selectables=func_get_args();
        if (count($selectables)==1) {
            if (is_array($selectables[0])) {
                $selectables= $selectables[0];
            }else if (is_string($selectables[0])) {
                if (empty($selectables[0])) {
                    $selectables=array("*");
                }
            }
            $this->selectables=$selectables;
        }else if (count($selectables)==0) {
            $this->selectables=array("*");
        }else if (count($selectables)>1) {
            $this->selectables=$selectables;
        }
        return $this;
    }

    /**
     * 创建from Select语句子字符串
     * @param string $tableorclassName 表名|类名[映射表]
     * @return Crud_Sql_Select 
     */
    public function from($tableorclassName) {
        if (class_exists($tableorclassName)){
            $this->tableName = Config_Db::orm($tableorclassName);;
        }else{
            $this->tableName = $tableorclassName;
        }
        return $this;
    }

    /**
     * 创建join Select语句子字符串
     * @todo 将类名转换成表名，类似Hbl
     * @param mixed $join
     */
    public function join($join) {
        $this->join = $join;
        return $this;
    }

    /**
     * 根据$order排序<br/>
     * 默认为倒序<br/>
     * @param mixed $order
     * 格式示例如下：<br/>
     * 1:order("id asc")<br/>
     * 2:order("name desc")<br/>
     * @return Crud_Sql_Select
     */
    public function order($order) {
        if(!empty($order)) {
            if (!(stripos($order,"asc")!==false||stripos($order,"desc")!==false)) {
                $order.=" desc ";
            }
        }
        $this->order = $order;
        return $this;
    }

    /**
     * 分页数目:同Mysql limit语法<br/>
     * 示例如下：<br/>
     *    1:0,10<br/>
     *    2:10
     * @param mixed $limit
     * @return Crud_Sql_Select 
     */
    public function limit($limit) {
        if (is_int($limit)) {
            $this->limit = $limit;
        }else {
            $limit_arr=explode(",", $limit);
            if (count($limit_arr)>0) {
              if (empty($limit_arr[0])){
                 $limit_arr[0]=0;
              }
              if ($limit_arr[0]>0){
                 $limit_arr[0]=$limit_arr[0]-1;
              }
            }
            $this->limit=implode(",", $limit_arr);
        }
        return $this;
    }

    /**
     * 分页数目:Postgres offset语法<br/>
     * 示例如下：<br/>
     *    1:0,10<br/>
     *    2:10<br/>
     * @param mixed $limit
     */
    public function offset($offset) {
        if ($offset>0){
            $offset=$offset-1;
        }
        $this->offset = $offset;
        return $this;
    }

    /**
     * 生成需要的完整的SQL语句
     * @return string SQL完整的语句
     */
    public function result() {
        if (!empty($this->selectables)){
            $selectClause=join(",",$this->selectables);
        }else{
            $selectClause="*";
        }
        $this->query = self::SQL_SELECT.$selectClause.self::SQL_FROM.$this->tableName;
        if (!empty($this->whereClause))
            $this->query.= self::SQL_WHERE.$this->whereClause;
        if (!empty($this->join))
            $this->query.= $this->join;
        if (!empty($this->order))
            $this->query.= self::SQL_ORDERBY.$this->order;
        if (!empty($this->limit))
            $this->query.= self::SQL_LIMIT.$this->limit;
        if (!empty($this->offset))
            $this->query.= self::SQL_OFFSET.$this->offset;
        return $this->query;
    }
}
?>
