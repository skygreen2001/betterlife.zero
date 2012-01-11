<?php
/**
 +---------------------------------<br/>
 * 比较直观可看的SQL构造器<br/>
 * 是所有SQL构造器的父类<br/>
 +---------------------------------
 * @category betterlife
 * @package core.db.sql
 * @subpackage crud
 * @author skygreen
 */
abstract class Crud_SQL {
    const SQL_KEYWORD_INSERT="insert";
    const SQL_KEYWORD_DELETE="delete";
    const SQL_KEYWORD_UPDATE="update";
    const SQL_KEYWORD_REPLACE="replace";
    const SQL_INSERT="insert into ";
    const SQL_INSERT_VALUE=" values ";
    const SQL_UPDATE="update ";
    const SQL_SET=" set ";
    const SQL_DELETE="delete ";
    const SQL_FROM=" from ";
    const SQL_WHERE=" where ";      
    const SQL_GROUPBY=" group by ";
    const SQL_HAVING=" having ";   
    const SQL_AND=" and ";
    const SQL_LIKE=" like ";
    const SQL_OR=" or ";
    const SQL_SELECT="select ";
    const SQL_LIMIT=" limit ";
    const SQL_OFFSET=" offset ";
    const SQL_ORDERBY=" order by ";
    const SQL_COUNT=" count(*) ";
    const SQL_MAXID=" max(#id) ";
    const SQL_ORDER_DEFAULT_ID=" #id desc ";
    const SQL_ORDER_DEFAULT_TIME=" commitTime desc ";
    const SQL_FLAG_ID="#id";
    protected $tableName;
    protected $whereClause;
    protected $query;
    private $whereConcat=self::SQL_AND;
    public $isPreparedStatement=false;
    protected $isLike=false;
    /**
     * 值的替代范式，一般$isPreparedStatement=true时配合使用；
     * 一般在insert的values和update的set里使用
     * 当$type_rep为：
     *   1：INSERT INTO REGISTRY (name, value) VALUES ($1,$2)
     *   默认：INSERT INTO REGISTRY (name, value) VALUES (?,?)
     * @var <type>
     */
    public $type_rep=0;
    /**
     * 默认是where的值如何是字符串必须带引号。
     * 但是表达关系的时候不能将where所带的值带引号，如：<br/>
     * select b.* from bb_user_re_userrole a,bb_user_role b where (a.userId=1 and b.id=a.roleId)
     * @var bool 是否忽略字符串值带引号。<br/> 
     */
    private $ignore_quotes=false;
    
    /**
     * 保证能实时开关该参数。
     * 默认是where的值如何是字符串必须带引号。
     * 但是表达关系的时候不能将where所带的值带引号，如：<br/>
     * select b.* from bb_user_re_userrole a,bb_user_role b where (a.userId=1 and b.id=a.roleId)
     * @param bool $ignore_quotes
     * @return Crud_SQL 
     */
    public function ignoreQuotes($ignore_quotes)
    {
        $this->ignore_quotes=$ignore_quotes;
        return $this;
    }

    /**
     * 查询SQL语句where条件子语句
     * @param mixex $clause where条件子语句
     +------------------------------------------------------------------------------------------------<br/>
     * 示例如下<br/>
     * 0.$sql->select("id","name")->from("users")->where("id=1,name='sky'");<br/>
     * 1.$sql->select("id","name")->from("users")->where("id=1","name='sky'");<br/>
     * 2.$sql->select("id","name")->from("users")->where(array("id=1","name='sky'"));<br/>
     * 3.$sql->select("id","name")->from("users")->where(array("id"=>"1","name"=>"sky"));<br/>
     * 4.$sql->select("id","name")->from("users")->where(new User(id="1",name="green"));//即过滤条件对象<br/>
     * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
     +-------------------------------------------------------------------------------------------------
     * @return SQL构造器本身
     */
    public function where() 
    {
        $clause=func_get_args();
        $whereClause="";
        if (count($clause)>0) {
            if ((count($clause)==1)&&empty($clause[0])){
                return $this;
            }
            if (is_array($clause[0])) {
                $detailclause=$clause[0];
                if (isset($detailclause[0])) {
                    if (is_object($detailclause[0])) {
                        //第四种情况[通过whereOr传入]
                        $detailclause=UtilObject::object_to_array($clause[0]);
                    }else {
                        if (is_array($detailclause[0])) {
                            $detailclause=$detailclause[0];
                        }
                        if (isset($detailclause[0])) {
                            if (strpos($detailclause[0], "=")) {
                                //第二种情况
                                //第零种情况|第一种情况:[通过whereOr传入]
                                $detailclause=implode(",", $detailclause);
                                if (!empty($detailclause)) {
                                    $detailclause=$this->parseValidInputParam($detailclause);
                                    if (is_string($detailclause)){
                                        $this->whereClause=$detailclause;
                                    }                                    
                                }else {
                                    return $this;
                                }
                            }
                        }else {
                            //第三种情况:无需处理[通过whereOr传入]
                        }
                    }
                }else {
                    //第三种情况:无需处理
                }
            }elseif (is_object($clause[0])) {
                //第四种情况
                $detailclause=UtilObject::object_to_array($clause[0]);
            }else {
                //第零种情况|第一种情况     
                if (is_array($clause)&&count($clause)==1){
                    $detailStr=$clause[0];
                    if (contain($detailStr, self::SQL_OR)||
                        contain($detailStr, self::SQL_LIKE)||
                        contain($detailStr, " (")){
                        $this->whereClause=$detailStr;
                        return $this;
                    }
                    if (!contain($detailStr,",")){
                        $this->whereClause=$detailStr;  
                        return $this;     
                    }
                }
                
                $detailclause=str_replace(trim(self::SQL_AND),",",$clause);
                $detailclause=implode(",", $detailclause);
                if (!empty($detailclause)) {
                    $detailclause=$this->parseValidInputParam($detailclause);
                }else {
                    return $this;
                }
            }

            $asWhereClause=array();
            foreach ($detailclause as $key => $value) {
                if ($this->isPreparedStatement) {
                    if ( $this->isLike) {
                        $asWhereClause[$key]=$key.self::SQL_LIKE." '%?%' ";
                    }else {
                        if (contains($value,array('>',"<","=",">=","<="))||(contains(strtolower($value),array("like ","between ")))){
                            $asWhereClause[$key]=$key." ".$value." ";
                        }else{
                            $asWhereClause[$key]=$key."=?";
                        }
                    }
                }else {
                    if ( $this->isLike) {
                        $asWhereClause[$key]=$key.self::SQL_LIKE." '%$value%' ";
                    }else {
                        if (is_int($key)) {
                            $asWhereClause[$key]=$value;
                        }else {  
                            if (is_numeric($value)) {
                              $asWhereClause[$key]=$key."=".$value; 
                            } else {
                              $quotes="";
                              if (!$this->ignore_quotes){
                                  if (!preg_match("/^['\"]/",trim($value))) {
                                      $quotes='\'';
                                  }                          
                              }
                              if (Config_Db::$db==EnumDbSource::DB_SQLSERVER&&((trim(strtoupper(Gc::$encoding))==Config_C::CHARACTER_UTF_8)||(trim(strtolower(Gc::$encoding))==Config_C::CHARACTER_UTF8))) {
                                  $asWhereClause[$key]=$key."=".$quotes;
                                  if (UtilString::is_utf8($value)&&Config_Adodb::driver()!=Config_Adodb::DRIVER_MSSQL_UTF8) {
                                    $asWhereClause[$key].=UtilString::utf82gbk($value).$quotes; 
                                  }else{
                                    $asWhereClause[$key].=$value.$quotes; 
                                  }
                              }else{
                                  $asWhereClause[$key]=$key."=".$quotes.$value.$quotes;     
                              }
                            }
                        }
                    }
                }
            }
            $whereClause=join($this->whereConcat,$asWhereClause);
        }

        if (!empty ($whereClause)) {
            $this->whereClause .="(".$whereClause.")";
        }
        return $this;
    }

    /**
     * 获取where语句
     * @return string where语句  
     */
    public function getWhereClause() {
        return $this->whereClause;
    }

    /**
     * 初始化where语句
     */
    public function initWhereClause() {
        $this->whereClause="";
    }

    /**
     * 过滤或条件
     +------------------------------------------------------------------------------------------------<br/>
     * 示例如下<br/>
     * 0.$sql->select("id","name")->from("users")->whereOr("id=1,name='sky'");<br/>
     * 1.$sql->select("id","name")->from("users")->whereOr("id=1","name='sky'");<br/>
     * 2.$sql->select("id","name")->from("users")->whereOr(array("id=1","name='sky'"));<br/>
     * 3.$sql->select("id","name")->from("users")->whereOr(array("id"=>"1","name"=>"sky"));<br/>
     * 4.$sql->select("id","name")->from("users")->whereOr(new User(id="1",name="green"));//即过滤条件对象<br/>
     * 默认:SQL Where条件子语句。如：(id=1 or name='sky')<br/>
     +-------------------------------------------------------------------------------------------------
     * @return SQL构造器本身
     */
    public function whereOr() {
        $clause=func_get_args();
        $this->whereConcat=self::SQL_OR;
        if (!empty($clause)&&  is_array($clause)&&count($clause)==1){
          $clause= $clause[0];
        }   
        return $this->where($clause);
    }

    /**
     * 过滤模糊条件
     * 查询SQL语句where条件子语句
     * @param mixex $clause where条件子语句
     +------------------------------------------------------------------------------------------------<br/>
     * 示例如下<br/>
     * 3.$sql->select("id","name")->from("users")->whereLike(array("id"=>"1","name"=>"sky"));<br/>
     * 4.$sql->select("id","name")->from("users")->whereLike(new User(id="1",name="green"));//即过滤条件对象<br/>
     * 默认:SQL Where条件子语句。如：name like 'sky'<br/>
     +-------------------------------------------------------------------------------------------------
     */
    public function whereLike() {
        $clause=func_get_args();
        $this->isLike=true;
        if (!empty($clause)&&  is_array($clause)&&count($clause)==1){
          $clause= $clause[0];
        }
        return $this->where($clause);
    }

    /**
     * 打印生成的SQL语句
     * @return string 打印生成的SQL语句
     */
    public function  __toString() {
        return $this->result();
    }


    /**
     * 转换成标准的预处理SQL数组格式
     * @param object|string|array $param
     * 示例如下：
     *     未使用预处理SQL语句
     *      0.允许对象如new User(id="1",name="green");
     *      1.id=1,name='sky'
     *      2.array("id=1","name='sky'")
     *      3.array("id"=>"1","name"=>"sky")
     * @return array key：列；value：值
     * 示例如下：
     *     array("id"=>"1","name"=>"sky")
     */
    public function parseValidInputParam($param) 
    {
        $result=null;
        if (empty($param)) {
            return $result;
        }
        if (is_string($param)) {
            if (contain($param, self::SQL_OR)||
                contain($param, self::SQL_LIKE)||
                contain($param, " (")){
                return $param;
            } else{           
                if (contain($param,",")){          
                    $param=explode(",", $param);
                }else{
                    return $param;
                }
            }
        }
        if (is_array($param)) {
            $filterc=each($param);
            if (is_string($filterc["value"])&&strpos($filterc["value"],"=")!==false) {
                $f_values=array_values($param);
                foreach ($f_values as $value) {
                    if ((strlen($value)>0)&&($value{0}=='(')&&($value{strlen($value)-1}==')')){
                        $value=substr($value,1,strlen($value)-2); 
                    }
                    if (contain($value,"1=1")){
                        $value=str_replace("1=1","",$value);
                        $value=str_replace("and","",$value);  
                        $value=trim($value);
                    }                          
                    if (!empty($value)){                    
                        if (contain($value, self::SQL_LIKE)){
                            $tmp=explode(trim(self::SQL_LIKE), $value);
                        }else{
                            $tmp=explode("=", $value);
                        }
                        if ($this->isPreparedStatement) {                                                
                            $result[$tmp[0]]=str_replace("'","",$tmp[1]); 
                            $result[$tmp[0]]=str_replace("\"","",$result[$tmp[0]]);    
                        }else {
                            $result[$tmp[0]]=$tmp[1];
                        }
                    }
                }
            }else {
                foreach ($param as  $key=> $value) {
                    if ($this->isPreparedStatement) {
                        $isFilter=true;
                        if (contains($value,array('"',"'"))&&(contains(strtolower($value),array('like','between')))){
                            if  ((contain(strtolower($value),'like')&&((substr_count($value,'"')==2)||(substr_count($value,"'")==2)))||
                                (contain(strtolower($value),'between')&&((substr_count($value,'"')==4)||(substr_count($value,"'")==4)))){
                                $isFilter=false;        
                            }
                        }
                        if ($isFilter){
                            $result[$key]=str_replace("'","",$value);
                            $result[$key]=str_replace("\"","",$result[$key]);
                        }else{
                            $result[$key]=$value;
                        }   
                    }else {
                        $result[$key]=$value;
                    }
                }

            }
        }
        if (is_object($param)) {
            $result=UtilObject::object_to_array($param);
        }
        return $result;
    }    

    /**
     * 生成需要的完整的SQL语句
     * @return string SQL完整的语句
     */
    abstract public function result();
}

?>
