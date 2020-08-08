<?php
if (session_status() === PHP_SESSION_NONE) { session_start();}
$path = pathinfo($_SERVER['PHP_SELF']);
if($path['filename'] === 'CheckerFn') {
    header('Location: ../404.php');
}
/*
*
*  all action check in this page
*
*/
require_once(dirname(__FILE__).'/SqlQuery.php');

class CheckerFn {
    public $db;
    public $valueContainer;
    public function __construct()
    {
        $this->db = new SqlQuery;
    }

    /*
    * array or object filter by key if you want to get key parent just $parent = 1
    * default $parent = null
    */
    public function keyFilter($data, $key, $parent) {
        if(is_array($data) || is_object($data)) {
            foreach ($data as $value) {
                $array = is_object($value) ? (array) $value: $value;
                if(isset($array[$key])) {
                    $this->valueContainer[] = isset($parent) ? $array: $array[$key];
                }else {
                    $this->keyFilter($value, $key, $parent);
                }
            }
        }
        return $this->valueContainer;
    }

    public function arrayKeyFilter($data, $key, $parent = null) {
      $this->valueContainer = [];
      return $this->keyFilter($data, $key, $parent);
    }
    // End filter


    public function redirect($fileName, $getData = '')
    {
      if(!empty($fileName)) {
         return header("Location: " . Db::$url . $fileName . $getData);
      }
    }

    // input value filter start
    public function v_filter($val)
    {
      $data = trim($val);
      $data = stripcslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    public function c_valid($data_)
    {
      if (is_array($data_)) {
         $dataArray = array();
         foreach ($data_ as $value) {
            $dataArray[] = $this->v_filter($value);
         }
         return $dataArray;
      } else {
         return $this->v_filter($data_);
      }
    } // input value filter end


    // user account informetion proces start
    public function secureInfoProcess ($secData) {
        $sec =  is_array($secData) ? $secData : (array) $secData;
        if(!empty($sec)) {
            $sec_count = count($sec);
            if($sec_count > 3) {
                for ($i=0; $i < ($sec_count -3); $i++) {
                    array_pop($sec);
                }
            }
            return $sec;
        }
    } // End


    // id proces start
    public function makeId($staple) {
        $main_v = $this->c_valid($staple);
        $main_len = strlen($main_v);
        return substr(substr($main_v, 32, $main_len), 0, (strlen(substr($main_v, 32, $main_len)) - 4));
    } // End

    public function intallCheck() {
        unset($_SESSION['install']);
        $userExists = $this->db->get("fms_admin");
        $settingsExists = $this->db->get("company_settings");
        if (!isset($settingsExists)) {
            $_SESSION['atFirstSettings'] = true;
            return $this->redirect('settings');
        } elseif (!isset($userExists)) {
            $_SESSION['registerPartner'] = true;
            return $this->redirect('registerPartner');
        }else {
            $_SESSION['install'] = true;
            unset($_SESSION['atFirstSettings']);
            unset($_SESSION['registerPartner']);
        }
    }

    public function loginCheck()
    {
        $secure = $this->db->get_row("company_settings");
        if (!isset($_SESSION['secure_auth']) || empty($_SESSION['secure_auth']) || $_SESSION['secure_auth'] !== $secure['secureAuth']) {
            session_destroy();
            return $this->redirect('login');
        }

        $path = pathinfo($_SERVER['PHP_SELF']);
        $self = $path['filename'];
        $access_not_alod_manager = ['earning_details', 'expenses_details', 'manageSettings', 'my_account_details', 'users'];
        if(in_array($self, $access_not_alod_manager) && $_SESSION['userinfo']['userRoll'] === 'Manager') {
            return $this->redirect('index');
        }
    }
}
