<?php
/**
 +-----------------------------------<br/> 
 * A result-set from a MySQL database.<br/> 
 +-----------------------------------<br/> 
 * @category betterlife
 * @package core.db.sql.util.query
 * @subpackage mysql
 * @author skygreen
 */
class Query_Mysql extends Query {
    /**
     * The internal MySQL handle that points to the result set.
     * @var resource
     */
    private $handle;

    /**
     * Hook the result-set given into a Query class, suitable for use by sapphire.
     * @param handle the internal mysql handle that is points to the resultset.
     */
    public function __construct($handle) {
        $this->handle = $handle;
    }

    public function __destroy() {
        mysql_free_result($this->handle);
    }

    public function seek($row) {
        return mysql_data_seek($this->handle, $row);
    }

    public function numRecords() {
        return mysql_num_rows($this->handle);
    }

    public function nextRecord() {
        // Coalesce rather than replace common fields.
        $data = @mysql_fetch_row($this->handle);
        if($data) {
            foreach($data as $columnIdx => $value) {
                $columnName = mysql_field_name($this->handle, $columnIdx);
                // $value || !$ouput[$columnName] means that the *last* occurring value is shown
                // !$ouput[$columnName] means that the *first* occurring value is shown
                if(isset($value) || !isset($output[$columnName])) {
                    $output[$columnName] = $value;
                }
            }
            return $output;
        } else {
            return false;
        }
    }


}

?>
