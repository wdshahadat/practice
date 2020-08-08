<?php
require_once('pages/header.php');
$baseUrl = $base_url;
$_SESSION['bank'] = 1;
$currentMonth = date('m');
$id = $_SESSION['userinfo']['id'];
$currencySymbol = $_SESSION['currency'];
$_SESSION['sec_a'] = md5(rand().'bank'.time());

/*
*
*  Earning list of the current month
*
*/

   $earnData = $c->db->get('', "SELECT * FROM fms_admin JOIN fms_bank ON fms_admin.id = fms_bank.id WHERE ba_date LIKE '$currentMonth%'");
    unset($_SESSION['userAction']);
    unset($_SESSION['costAction']);
?>

<section class="content">
   <div class="card clearfix costdetails">
        <?php if (isset($_SESSION['info_message'])) { $info_ms = $_SESSION['info_message']; ?>
        <input type="hidden" name="messageShow" value="1">
        <?php if($info_ms === 1) { ?>
            <div class="alert alert-success">
                <p><strong>Well done!</strong> Your earning amount is successfully Updated.</p>
        <?php } ?>
        <?php if(is_array($info_ms)) { ?>
            <div class="alert alert-success">
                <p><strong>Well done!</strong> Your earning  <b>Id = <?php echo $info_ms[0]; ?></b> of  amount was <b><?php echo $info_ms[1]; ?></b> successfully Deleted.</p>
        <?php } ?>
            </div>
        <?php unset($_SESSION['info_message']);} ?>

        <div class="col-lg-12 col-md-12 col-sm-12 option-c option-m">
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
        <div class="col-lg-12 col-md-12 col-sm-12 option-c">
        <table id="earn_details_table" class="table table-bordered">
           <thead>
               <tr>
                  <th width=" 27%">User</th>
                  <th width=" 10%">Amount</th>
                  <th width=" 33%">Earn Source</th>
                  <th width=" 15%">Date</th>
                  <th width=" 15%">Action</th>
               </tr>
            </thead>
            <tbody id="earn_details_tbody">
                <?php
                if(isset($earnData) && !empty($earnData)) {
                    foreach ($earnData as $key => $row) {
                        $bankId = $row->bankId;
                        $key = md5(rand().$bankId.time()); // generate bankId key
                        $key_v = md5(rand().$bankId.time()).$bankId.rand(1293, 3000); // generate bank id, to secure action
                        $keyOne = md5($key.rand()); // extra key
                        $keyOne_v = $_SESSION['sec_a'];  // extra value
                        $action = $row->id === $id ? '<a class="btn btn-warning waves-effect cu-btn" href="'.$baseUrl.'earnings_manage.php?'.$key.'='.$key_v.'&'.$keyOne.'='.$keyOne_v.'">Edit</a> <a onclick="return confirm(&#39;Are you sure you want to delete this?&#39;);" class="btn btn-danger waves-effect cu-btn" href="'.$baseUrl.'action.php?'.$key.'='.$key_v.'&'.$keyOne.'='.$keyOne_v.'">Delete</a>' : '';
                        echo '<tr>
                            <td><div class="userImg"><img src="'.$baseUrl.'uploadFiles/userPhoto/'.$row->photo.'" alt=""></div><p>'.$row->fullName.'</p></td>
                            <td>'.$row->amount.' <b>'.$currencySymbol.'</b></td>
                            <td>'.$row->earnSource.'</td>
                            <td>'.$row->ba_date.'</td>
                            <td>'.$action.'</td>
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
