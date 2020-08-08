<?php
require_once('pages/header.php');
unset($_SESSION['bank']);
unset($_SESSION['userAction']);
$db = $c->db;
$currentMonth = date('m');
$baseUrl = $base_url;
$_SESSION['costAction'] = 1;
$id = $_SESSION['userinfo']['id'];
$_SESSION['sec_a'] = md5(rand().'cost'.time());


/*
*
*  Expenses list of current month
*
*/

   $sql_q = "SELECT * FROM fms_admin JOIN fms_cost ON fms_admin.id = fms_cost.id WHERE cst_a_date LIKE '$currentMonth%'";
   $expenseData = $db->get('', $sql_q);
?>

<section class="content">
   <div class="card clearfix costdetails">
        <?php if (isset($_SESSION['info_message'])) { $info_ms = $_SESSION['info_message'];?>
        <input type="hidden" name="messageShow" value="0">
            <div class="alert alert-success">
              <?php if(!is_array($info_ms)) { ?>
                <p><strong>Well done!</strong> Your cash amount is successfully <?php echo $info_ms ?> .</p>
            <?php }
            if(is_array($info_ms)) { ?>
                <p><strong>Well done!</strong> Your expenses <b>Id = <?php echo $info_ms[0]; ?></b> of  amount was <b><?php echo $info_ms[1]; ?></b> successfully Deleted.</p>
            <?php } ?>
            </div>
        <?php unset($_SESSION['info_message']);} ?>
        <div class="col-lg-12 col-md-12 col-sm-12 option-c">
            <form action="" method="post">
                <div class="col-lg-4 col-md-4 col-sm-4"></div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 to-hide">
                    <select name="month" id="month" class="form-control show-tick">
                        <option hidden selected disabled >Select month</option>
                        <?php
                            for ($i=1; $i < 13; $i++) {
                                $dateObj   = DateTime::createFromFormat('!m', $i);
                                $monthName = $dateObj->format('F');
                                $m = $dateObj->format('m');
                                $selected = $m === $currentMonth ? 'selected':'';
                                echo '<option value="'.$i.'" '.$selected.'>'.$monthName.'</option>';
                            }
                        ?>
                    </select>
                </div>
            </form>
        </div>
      <div class="col-lg-12 col-md-12 col-sm-12">
         <table id="expenses_list_table" class="table table-bordered">
           <thead>
               <tr>
                  <th width=" 30%">Users</th>
                  <th width=" 20%">Cost Amount</th>
                  <th width=" 20%">Date</th>
                  <th width=" 20%">Action</th>
               </tr>
            </thead>
            <tbody id="expenses_list_tbody">
               <?php

                  if(isset($expenseData) && count($expenseData) > 0) {
                     foreach ($expenseData as $key => $row) {
                        $currency = $_SESSION['currency']; // user currency symble

                        // to create secure link
                        $key = md5(rand(). $row->cst_id.time());
                        $key_v = md5(rand(). $row->cst_id.time()).$row->cst_id.rand(1293, 3000);
                        $keyOne = md5($key.rand());
                        $keyOne_v = $_SESSION['sec_a'];
                        $currency = $_SESSION['currency'];
                        $deleteLink = $base_url.'action.php?'.$key.'='.$key_v.'&'.$keyOne.'='.$keyOne_v;
                        $checkUser = $row->id === $id ? '<a class="btn btn-warning waves-effect cu-btn" href="'.$base_url.'expenses_manage.php?'.$key.'='.$key_v.'&'.$keyOne.'='.$keyOne_v.'">Edit</a> <a onclick="return confirm(&#39;Are you sure you want to delete this?&#39;);" class="btn btn-danger waves-effect cu-btn" href="'.$deleteLink.'">Delete</a>' : '';
                        $percentage = $row->userRoll === 'Partner' ? $row->percentage: 'Manager';
                        echo '<tr>
                           <td><div class="userImg"><img src="'.$base_url.'uploadFiles/userPhoto/'.$row->photo.'" alt=""></div><p>'.$row->fullName.'</p></td>
                           <td><b>'.$row->cst_amount.'</b> '.$currency.'</td>
                           <td>'.$row->cst_a_date.'</td>
                           <td>
                              <a class="btn btn-info waves-effect cu-btn" href="'.$base_url.'expenses_view.php?'.$key.'='.$key_v.'&'.$keyOne.'='.$keyOne_v.'">View</a> '.$checkUser.'
                           </td>
                        </tr>';
                     }
                  }
               ?>
            </tbody>
         </table>
      </div>
   </div>
</section>

<?php include('pages/footer.php'); ?>
