<?php
if (session_status() === PHP_SESSION_NONE) { session_start();}
require_once('./db.php');

class Database {
    public $db;

    public function __construct() {
        $this->db = Db::connect();
    }

    public function getData() {
        return $this->db;
        // $db = new Db;
        // $db = $db::connect();
        // $sql = "SELECT * FROM fms_bank";
        // $result = $db->query($sql);

        // while($row = $result->fetch_assoc()) {
        //     $data[] = $row;
        // }
        // return $data;
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
        } else {
            return $this->input_field_validation($data_, $null_check);
        }
    } // input value filter end


    // Where data proces
    public function where($data) {
        if(is_array($data)) {
            $data = array_map(function ($key, $value) {
                return "`{$key}`='$value'";
            }, array_keys($data), array_values($data));
            $dataCont = implode(" AND ", $data);

        }elseif(is_string($data)) {
            $dataCont = $data;
        }
        $dataCont = empty($dataCont) ? '':"WHERE ".$dataCont;
        return $dataCont;
    }

    // Edit table info by condition
    public function edit($table='', $data=[], $condition='') {
        if(!empty($data)) {
            $data = array_map(function ($key, $value) {
                return "`{$key}`='$value'";
            }, array_keys($data), array_values($data));
            $editableData = implode(", ", $data);
        }
        $condition = $this->where($condition);
        $sql = "UPDATE {$table} SET {$editableData} {$condition}";
        return $this->db->query($sql);
    }

    // Edit table info by condition
    public function delete($table='', $condition='') {
        $condition = $this->where($condition);
        $sql = "DELETE FROM {$table} {$condition}";
        return $this->db->query($sql);
    }

    // Insert data to table
    public function create($table='', $data=[]) {
        if(is_array($data) && !empty($data)) {
            $sql = "INSERT INTO `{$table}` (";
            $sql .= "`".implode('`, `', array_keys($data))."`) VALUE(";
            $sql .= "'".implode('\', \'', array_values($data))."')";
            return $this->db->query($sql);
        }
    }

    // Get data from table by condition
    public function get($table='', $condition='') {
        $condition = $this->where($condition);
        $sql = "SELECT * FROM {$table} {$condition}";
        // return $this->db;
        $result = $this->db->query($sql);
        if($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
        return NULL;
    }

}

$db = new Database;
// var_dump($db->getData());
echo '<pre>';
    // print_r($db->db);
    print_r($db->get('fms_bank'));
echo '</pre>';
