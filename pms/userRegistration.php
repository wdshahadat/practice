<?php
require_once('pages/header.php');
unset($_SESSION['bank']);
unset($_SESSION['costAction']);
unset($_SESSION['userAction']);
unset($_SESSION['ridirect_l']);
$baseUrl = $base_url;

/*
*
*  manage user registration
*
*/

// user informetion add or update
$_SESSION['sec_a'] = md5(rand().'user'.time());
$checkLimit = $c->db->get_row('', "SELECT SUM(percentage) FROM fms_admin");
$currentPercentage = $checkLimit['SUM(percentage)'];
$_SESSION['userAction'] = rand();
if(isset($_GET) && !empty($_GET)) {
    $userInfo = $_SESSION['userinfo'];
    $id = array_values($_GET);
    $id = $c->makeId($id[0]);
    $_SESSION['ridirect_l'] = $_SERVER['REQUEST_URI'];
    $edit = $c->db->get_row('fms_admin', ['id' => $id]);
    $_SESSION['ridirect_l'] = $_SERVER['REQUEST_URI'];
    $_SESSION['userInformetion'] = $edit;
    if(isset($edit)) {
        $currentPercentage = intval($currentPercentage) - intval($edit['percentage']);
        $name = explode(' ', $edit['fullName']);
        $firstName = $name[0];
        $lastName = $name[1];
        if(!empty($edit['userInfo_sc'])) {
            $cc = json_decode($edit['userInfo_sc']);
            $userName = $cc->userName_sc;
            $password = $cc->password_sc;
        }
    }
}
?>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <?php if(isset($_SESSION['sorry'])) { ?>
                <div class="alert alert-warning">
                    <input type="hidden" name="messageShow" value="1">
                    <p><b class="redColor">Sorry! </b> User Action is failed.</p>
                </div>
            <?php unset($_SESSION['sorry']); } ?>
            <?php if(isset($_SESSION['invalidEmail'])) { ?>
                <input type="hidden" name="messageShow" value="1">
                <p><b class="redColor"><?php echo $_SESSION['invalidEmail']; ?></b> Invalid email format.</p>;
            <?php unset($_SESSION['invalidEmail']); } ?>
            <?php if (isset($_SESSION['doesNot_m'])) { ?>
                <input type="hidden" name="messageShow" value="0">
                <div class="alert alert-warning">
                    <p><b>Sorry!</b> Confirm password does not match password.</p>
                </div>
            <?php unset($_SESSION['doesNot_m']); } ?>
            <?php if (isset($_SESSION['emptyField'])) { ?>
                <input type="hidden" name="messageShow" value="0">
                <div class="alert alert-warning">
                    <p><b>Sorry! </b> please complete the all input field</p>
                </div>
            <?php unset($_SESSION['emptyField']); } ?>
            <?php if (isset($_SESSION['old_p'])) { ?>
                <input type="hidden" name="messageShow" value="0">
                <div class="alert alert-warning">
                    <p><b>Sorry! </b> Your entered password is <b>old</b> please create a new password</p>
                </div>
            <?php unset($_SESSION['old_p']); } ?>

            <?php if (isset($_SESSION['registerSuccess'])) { ?>
            <div class="alert alert-success">
                <input type="hidden" name="messageShow" value="1">
                <p><strong>Well done!</strong> User registration info is successfully submited.</p>
                <p>Send a email <b><?php echo $_SESSION['registerSuccess']; ?></b> to create user</p>
                <p><b>Please check</b> your email and create your user name and password.</p>
            </div>
            <?php unset($_SESSION['registerSuccess']);} ?>
            <h2><?php echo isset($edit) ? 'Edit user informetion': 'Add user'; ?></h2>
        </div>

        <!-- Advanced Validation -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                        <input type="hidden" id="currentPercentage" value="<?php echo $currentPercentage; ?>">
                      <form id="registration" action="<?php echo $baseUrl; ?>action" id="form_advanced_validation" method="post" enctype="multipart/form-data">
                            <?php
                                if(isset($cc) && $userInfo['id'] === $id) { ?>
                                    <div class="input-group">
                                       <span class="input-group-addon">
                                           <i class="material-icons">person</i>
                                       </span>
                                       <div class="form-line">
                                           <input  class="form-control"type="text" name="userName" value="<?php echo $userName; ?>" placeholder="User Name" >
                                       </div>
                                   </div>
                                   <div class="input-group">
                                       <span class="input-group-addon">
                                           <i class="material-icons">lock</i>
                                       </span>
                                       <div class="form-line">
                                           <input type="password" class="form-control" name="passworda" value="<?php echo $password; ?>"  placeholder="Enter Password" >
                                       </div>
                                   </div>
                                   <div class="input-group">
                                       <span class="input-group-addon">
                                           <i class="material-icons">forward_30</i>
                                       </span>
                                       <div class="form-line">
                                           <input type="password" class="form-control" name="passwordr" placeholder="Retype password" >
                                       </div>
                                   </div>
                                <?php }
                            ?>
                           <div class="input-group">
                               <span class="input-group-addon">
                                   <i class="material-icons">person</i>
                               </span>
                               <div class="form-line">
                                   <input type="text" <?php if(isset($edit)) {echo 'value="'.$firstName.'"';} ?> class="form-control" name="fn" minlength="3" placeholder="First Name" required>
                               </div>
                           </div>
                           <div class="input-group">
                               <span class="input-group-addon">
                                   <i class="material-icons">person</i>
                               </span>
                               <div class="form-line">
                                   <input type="text" <?php if(isset($edit)) {echo 'value="'.$lastName.'"';} ?> class="form-control" name="ln" placeholder="Last Name" required>
                               </div>
                           </div>
                           <div class="input-group">
                               <span class="input-group-addon">
                                   <i class="material-icons">email</i>
                               </span>
                               <div class="form-line">
                                   <input type="email" <?php if(isset($edit)) {echo 'value="'.$edit['email'].'"';} ?> class="form-control" name="email" placeholder="Email Address" required>
                               </div>
                           </div>
                           <div class="percentage" <?php if(isset($edit) && $edit['userRoll'] === 'Partner') { echo 'style="display:block;"';}else { echo 'style="display:none;"';} ?>>
                               <div class="input-group">
                                   <span class="input-group-addon">
                                       <i class="material-icons">group_work</i>
                                   </span>
                                   <div class="form-line">
                                       <input type="text" <?php if(isset($edit)) {echo 'value="'.$edit['percentage'].'"';} ?> class="form-control" name="percentage" placeholder="Enter Percentage">
                                   </div>
                               </div>
                           </div>
                           <div class="form-group">
                              <input type="radio" name="gender" <?php if(isset($edit)) {echo $edit['gender'] === 'Male' ? 'checked': false; } ?> value="Male" id="male" class="with-gap"  required >
                              <label for="male">Male</label>
                              <input type="radio" name="gender" <?php if(isset($edit)) {echo $edit['gender'] === 'Female' ? 'checked': false; } ?> value="Female" id="female" class="with-gap"  required>
                              <label for="female" class="m-l-20">Female</label>
                           </div>
                           <div class="input-group">
                               <span class="input-group-addon">
                                   <i class="material-icons">child_care</i>
                               </span>
                               <div class="form-line">
                                   <input type="text" <?php if(isset($edit)) {echo 'value="'.$edit['birthday'].'"';} ?> data-toggle="datepicker" class="form-control" name="birthday" minlength="3" placeholder="Date of Birth" required>
                               </div>
                           </div>
                           <div class="input-group">
                               <span class="input-group-addon">
                                   <i class="material-icons">face</i>
                               </span>
                               <div class="form-line" style="border:0">
                                  <label class="user-img" for="img">
                                      <input id="img" type="file" name="img" <?php if(!isset($edit)) {echo 'required';} ?> >
                                  </label>
                               </div>
                           </div>
                           <?php if(isset($_SESSION['userinfo']) && $_SESSION['userinfo']['userRoll'] === 'Partner') { ?>
                           <div class="form-group userRoll">
                              <input type="radio" name="userRoll" <?php if(isset($edit)) {echo $edit['userRoll'] === 'Partner' ? 'checked': false; } ?> value="Partner" id="partner" class="with-gap"  required>
                              <label for="partner">Partner</label>
                              <input type="radio" name="userRoll" <?php if(isset($edit)) {echo $edit['userRoll'] === 'Manager' ? 'checked': false; } ?> value="Manager" id="manager" class="with-gap"  required>
                              <label for="manager" class="m-l-20">Manager</label>
                           </div>
                           <?php }else {echo '<input type="hidden" name="userRoll" value="Manager">';} ?>
                          <input type="hidden" name="persentageCheck" value="0">
                           <button class="btn btn-<?php if(isset($edit)) {echo 'warning';}else {echo 'primary';} ?> waves-effect" type="submit"><?php echo isset($edit) ? 'Update user': 'Register user'; ?></button>
                            <?php if(isset($edit)) {
                                echo '<a style="float:right;color:white;" class="btn btn-info waves-effect cu-btn" href="'.$base_url.'index.php"><i class="material-icons">home</i> Go Home</a>';}
                            ?>
                            <?php if(isset($edit)) {
                              echo '<input type="hidden" name="editUserInfo" value="'.$id.'">';
                            }else {
                              echo '<input type="hidden" name="register" value="1">';
                            } ?>
                            <input type="hidden" name="sec_a" value="<?php echo $_SESSION['sec_a']; ?>">
                       </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include('pages/footer.php') ?>
