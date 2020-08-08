<?php
require_once('pages/header.php');

/*
*
*  All user list in this page
*
*/


// if you want to manage user edit or delete that is possible in ths page
$_SESSION['user'] = 1;
unset($_SESSION['bank']);
unset($_SESSION['userAction']);
unset($_SESSION['costAction']);
$_SESSION['sec_a'] = md5(rand().'secUserAction876'.time());
$allUserData = $c->db->get('fms_admin');
?>
<section class="content">
   <div class="card">
      <?php if (isset($_SESSION['deleteSucccess'])) { ?>
         <div class="alert alert-success">
            <input type="hidden" name="messageShow" value="1">
            <p><strong>Well done!</strong> <?php  echo '<b>'.$_SESSION['deleteSucccess'].'</b> account is successfully Deleted';  ?>.</p>
        </div>
      <?php unset($_SESSION['deleteSucccess']);} ?>
      <?php if (isset($_SESSION['editSuccess'])) { ?>
         <div class="alert alert-success">
            <input type="hidden" name="messageShow" value="2">
            <p><strong>Well done!</strong> User informetion is successfully updated.</p>
            <?php if (isset($_SESSION['resetSuccess'])) { ?>
                <p>Reset password is success.</p>
                <p>Your account informetion has sent this <b><?php echo $_SESSION['resetSuccess']; ?></b>.</p>
                <p>To get your account informetion check mail box.</p>
            <?php unset($_SESSION['resetSuccess']);}else { echo '<input type="hidden" name="messageShow" value="0">';} ?>
        </div>
      <?php unset($_SESSION['editSuccess']);} ?>
      <div class="user-head">
          <h2>All User</h2>
      </div>
      <table id="userTable" class="table table-bordered">
        <thead>
            <tr>
               <th width=" 25%">Users</th>
               <th width=" 10%">Percentage</th>
               <th width=" 30%">Email</th>
               <th width=" 15%">Date</th>
               <th width=" 20%">Action</th>
            </tr>
         </thead>
         <tbody>
            <?php

            if (isset($allUserData) && count($allUserData) > 0) {
               foreach ($allUserData as $key => $row) {
                  $key = $_SESSION['sec_a'];
                  $key_v = md5(rand(). $row->id.time()).$row->id.rand(1293, 3000);
                  $percentage = $row->userRoll === "Partner" ? $row->percentage. '<b>%</b>' : $row->userRoll;
                  echo '<tr>
                        <td><div class="userImg"><img src="'.$base_url. $row->photo . '" alt=""></div><p>' . $row->fullName . '</p></td>
                        <td>' . $percentage . '</td>
                        <td>' . $row->email . '</td>
                        <td>' . $row->a_date . '</td>
                        <td>
                           <a class="btn btn-info waves-effect cu-btn" href="'.$base_url.'userProfile.php?'.$key.'=' . $key_v . '">View</a>
                           <a class="btn btn-warning waves-effect cu-btn" href="'.$base_url.'userRegistration.php?'.$key.'=' . $key_v . '">Edit</a>
                           <a class="btn btn-danger waves-effect cu-btn" href="'.$base_url.'action.php?'.$key.'=' . $key_v . '" onclick="return confirm(&#39;Are you sure you want to delete this?&#39;);" >Delete</a>
                        </td>
                     </tr>';
               }
            }
            ?>
         </tbody>
      </table>
   </div>
</section>

<?php include('pages/footer.php'); ?>
