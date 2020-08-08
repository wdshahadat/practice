<?php
require_once('functions/CheckerFn.php');
$c = new CheckerFn;
$c->loginCheck();


/*
*
*  Expense list reload by month
*
*/
   if(isset($_POST['month']) && !empty($_POST['month'])) {
      $dateObj = DateTime::createFromFormat('!m', $_POST['month']);
      $month = $dateObj->format('m');
      $monthlyExpense = $c->db->get('', "SELECT * FROM fms_admin JOIN fms_cost ON fms_admin.id = fms_cost.id WHERE cst_a_date like '$month%'");
      $id = $_SESSION['userinfo']['id'];
      if(isset($monthlyExpense) && !empty($monthlyExpense)) {
         foreach ($monthlyExpense as $key => $row) {
            $baseUrl = $base_url;
            $key = md5(rand(). $row->cst_id.time());
            $key_v = md5(rand(). $row->cst_id.time()).$row->cst_id.rand(1293, 3000);
            $keyOne = md5($key.rand());
            $keyOne_v = $_SESSION['sec_a'];
            $checkUser = $row->id === $id ? '<a class="btn btn-warning waves-effect cu-btn" href="'.$base_url.'expenses_manage.php?'.$key.'='.$key_v.'&'.$keyOne.'='.$keyOne_v.'">Edit</a> <a onclick="return confirm(&#39;Are you sure you want to delete this?&#39;);" class="btn btn-danger waves-effect cu-btn" href="'.$base_url.'action.php?'.$key.'='.$key_v.'&'.$keyOne.'='.$keyOne_v.'">Delete</a>' : '';
            $percentage = $row->userRoll === 'Partner' ? $row->percentage: 'Manager';
            echo '<tr>
               <td><div class="userImg"><img src="'.$base_url.'uploadFiles/userPhoto/'.$row->photo.'" alt=""></div><p>'.$row->fullName.'</p></td>
               <td><b>'.$row->cst_amount.'</b> '.$_SESSION['currency'].'</td>
               <td>'.$row->cst_a_date.'</td>
               <td>
                  <a class="btn btn-info waves-effect cu-btn" href="'.$base_url.'expenses_view.php?'.$key.'='.$key_v.'&'.$keyOne.'='.$keyOne_v.'">View</a> '.$checkUser.'
               </td>
            </tr>';
        }
    }
   }
