<?php
if (session_status() === PHP_SESSION_NONE) { session_start();}
$path = pathinfo($_SERVER['PHP_SELF']);
if($path['filename'] === 'SqlQuery') {
    header('Location: ../404.php');
}

if(!file_exists(dirname(__FILE__).'/db.php')) {
    $path = sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        $_SERVER['PHP_SELF']
    );
    $base_url = in_array('functions', explode('/', dirname($path))) ? dirname(dirname($path)).'/':dirname($path).'/';
    header('Location: '.$base_url.'functions/databaseSettings.php');
}


define('__ROOT__', dirname(__FILE__));
require_once(__ROOT__.'/db.php');
require_once(__ROOT__.'/currencyAndIcons.php');

/*
*
*  sql query builder
*
*/

class SqlQuery {
    public $connect;

    public function __construct() {
        $this->connect = Db::connect();
    }

    // input value filter start
    public function input_field_validation($val, $null_check)
    {
        if(is_array($val)) {
            return $this->validation($val, $null_check);
        }
        $data = trim($val);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        $data = trim($data);
        if(isset($null_check)) {
            $data = isset($data) ? $data:'';
        }
        return $data;
    }

    public function validation($data_, $null_check=NULL, $remove=NULL)
    {
        if (is_array($data_)) {
            $dataArray = array();
            foreach ($data_ as $key => $value) {
                if(is_array($value)) {
                    if(!empty($value)) {
                        $sub_value_array = [];
                        foreach ($value as $sub_key => $array_value) {
                            $sub_value_array[$sub_key] = $this->input_field_validation($array_value, $null_check);
                        }
                        $dataArray[$key] = $sub_value_array;
                    }
                    $dataArray[$key] = $value;
                }else {
                    $value = $this->input_field_validation($value, $null_check);
                    if( isset($remove) && !empty($value)) {
                        $dataArray[$key] = $value;
                    }elseif(!isset($remove)) {
                        $dataArray[$key] = $value;
                    }
                }
            }
            return $dataArray;
        } elseif(!empty($data_)) {
            return $this->input_field_validation($data_, $null_check);
        }
        return $data_;
    } // input value filter end
    // input value verification
    public function v_filter($val) {
        $data = trim($val);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function c_valid($data_) {
        if (is_array($data_)) {
            $dataArray = array();
            foreach ($data_ as $value) {
                $dataArray[] = $this->v_filter($value);
            }
            return $dataArray;
        } else {
            return $this->v_filter($data_);
        }
    } // end verification

    // where condition generator
    function where($where_d, $strData = null) {
        $where = "";
        if(is_array($where_d)) {
            $amount = count($where_d)-1;
            $counter_c = count($where_d) - (count($where_d) + 1);
            foreach ($where_d as $key => $value) {
                $counter_c++;
                $andCondition = $counter_c < $amount ? ' AND ': false;
                $where .= "`{$key}` = '{$value}'".$andCondition;
            }
        }elseif(is_string($where_d) && isset($strData)) {
            $where .= "'{$where_d}' = '{$strData}'";
        }
        $w = !empty($where) && is_bool(strpos($where, 'WHERE')) !== false ? 'WHERE ': '';
        return empty($where) ? false: $w.$where;
    }

    function select($selectColumn = null) {
        $selectColumn = isset($selectColumn) ? $selectColumn : '*';
        return "SELECT ".$selectColumn." ";
    }

    function from($table = null) {
        return "FROM `{$table}` ";
    }

    function selectQueryBuilder($table, $filter = null) {
        $filter = isset($filter) && is_array($filter) ? $this->where($filter): '';
        return $this->select().$this->from($table)." ".$filter;
    }

    // get database table single row associative array
    public function get_row($table, $filter = null) {
        if(empty($table) && is_string($filter)) {
            $sql_q = $filter;
        }elseif(isset($filter) && is_array($filter)) {
            $sql_q = $this->selectQueryBuilder($table, $filter);
        }else {
            $sql_q = $this->selectQueryBuilder($table);
        }
        $result = isset($sql_q) ? $this->connect->query($sql_q): null;
        if (isset($result) && !is_bool($result)) {
            $resultArray = [];
        while ($r = $result->fetch_assoc()) {
            $resultArray[] = $r;
        }
            return !empty($resultArray) && count($resultArray) === 1 ? $resultArray[0] : null;
        }
    }

    // to get a table rows object data
    public function get($table, $filter = null) {
        if(empty($table) && is_string($filter)) {
            $sql_q = $filter;
        }elseif(isset($filter) && is_array($filter)) {
            $sql_q = $this->selectQueryBuilder($table, $filter);
        }else {
            $sql_q = $this->selectQueryBuilder($table);
        }
        $result = $this->connect->query($sql_q);
        if (isset($result) && !is_bool($result)) {
            $resultArray = [];
            while ($row = $result->fetch_object()) {
                $resultArray[] = $row;
            }
            return !empty($resultArray) ? $resultArray : null;
        }
    }


    // to insert data in database
    public function insertAction($table, $data) {
        if(is_array($data) && !empty($data)) {
            $sql = "INSERT INTO `{$table}` (";
            $sql .= "`".implode('`, `', array_keys($data))."`) VALUE(";
            $sql .= "'".implode('\', \'', array_values($data))."')";
            return $this->connect->query($sql);
        }
    }

    // update data processor
    public function updateStapleData($table, $stapleArray, $whereFilter) {
        if(is_array($stapleArray)) {
            $updateQS = [];
            foreach ($stapleArray as $key => $value) {
                $updateQS[] = "`{$key}` = '{$value}'";
            }
        }
        $data = implode(', ', $updateQS);
        $whereData = $this->where($whereFilter);
        return "UPDATE `{$table}` SET {$data} {$whereData}";
    }

    // to update database table informetion
    public function updateAction($table, $stapleArray, $whereFilter) {
        if(isset($table, $stapleArray, $whereFilter)) {
            $table = $this->c_valid($table);
            $sqlQ = $this->updateStapleData($table, $stapleArray, $whereFilter);
            $result = $this->connect->query($sqlQ);
            if (isset($result) && $result === true) {
                return $result;
            }
        }
        return null;
    }


    // to delete database table informetion
    public function deleteAction($table, $filter = null) {
        $sqlQ = "DELETE FROM {$table} ".$this->where($filter);
        $result = $this->connect->query($sqlQ);
        if (isset($result) && $result === true) {
            return $result;
        }
        return null;
    }


    // to empty a database table
    public function tableEmpty($table) {
      return $this->connect->query("TRUNCATE TABLE " . $table);
    }
}
