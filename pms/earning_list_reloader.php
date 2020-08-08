<?php
require_once('functions/CheckerFn.php');
$c = new CheckerFn;


/*
*
*  to reload earning amount list
*
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['userinfo']) && isset($_POST['month'])) {

    $currencySymbol = $_SESSION['currency'];
    $dateObj = DateTime::createFromFormat('!m', $_POST['month']); // get current month
    $month = $dateObj->format('m'); // current month number
    $id = $_SESSION['userinfo']['id']; // user id

    //  get all earn data of partners. In the selected month
   $earnData = $c->db->get('', "SELECT * FROM fms_admin JOIN fms_bank ON fms_admin.id = fms_bank.id WHERE ba_date LIKE '$month%'");
    if(isset($earnData) && !empty($earnData)) {
        foreach ($earnData as $key => $row) {
            $url = $base_url;
            $costId = $row->bankId;
            $key = md5(rand().$costId.time());  // generate bankId key
            $key_v = md5(rand().$costId.time()).$costId.rand(1293, 3000); // generate bank id, to secure action
            $keyOne = md5($key.rand());
            $keyOne_v = $_SESSION['sec_a'];
            $action = $row->id === $id ? '<a class="btn btn-warning waves-effect cu-btn" href="'.$url.'earnings_manage.php?'.$key.'='.$key_v.'&'.$keyOne.'='.$keyOne_v.'">Edit</a> <a onclick="return confirm(&#39;Are you sure you want to delete this?&#39;);" class="btn btn-danger waves-effect cu-btn" href="'.$url.'action.php?'.$key.'='.$key_v.'&'.$keyOne.'='.$keyOne_v.'">Delete</a>' : '';
            echo '<tr>
                <td><div class="userImg"><img src="'.$base_url.'uploadFiles/userPhoto/'.$row->photo.'" alt=""></div><p>'.$row->fullName.'</p></td>
                <td>'.$row->amount.' <b>'.$currencySymbol.'</b></td>
                <td>'.$row->earnSource.'</td>
                <td>'.$row->ba_date.'</td>
                <td>'.$action.'</td>
            </tr>';
        }
    }
}else {
    return $c->checker->redirect('404');
}
