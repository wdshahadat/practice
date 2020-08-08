<?php
require_once('functions/CheckerFn.php');
$c = new CheckerFn;
$company = $c->db->get("company_settings");

/*
*
*  atfirst register partner page
*
*/


// check partner if exists then check is logged then redirect home page
if(!empty($company)) {
  $partner = $c->db->get("fms_admin");
  if(!empty($partner)) {
      return isset($_SESSION['login']) ? $c->redirect('index'):$c->redirect('login');
  }
  $_SESSION['registerPartner'] = 1;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Partnership Management system</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?php echo $base_url; ?>css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo $base_url; ?>css/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?php echo $base_url; ?>css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?php echo $base_url; ?>css/datepicker.css" rel="stylesheet" />
    <link href="<?php echo $base_url; ?>css/style.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>css/custom-style.css" rel="stylesheet">
</head>
<body class="registerPage">
    <?php if (isset($_SESSION['currencyNotSet'])) { ?>
    <div class="alert alert-danger">
        <input type="hidden" name="messageShow" value="0">
        <p><strong>Sorry!</strong> Your currency is not set</p>
        <p>Please select your currency.</p>
    </div>
    <?php unset($_SESSION['currencyNotSet']);} ?>

    <?php if (isset($_SESSION['messageShow'])) { ?>
    <div class="alert alert-success">
        <input type="hidden" name="messageShow" value="0">
        <p><strong>Well done!</strong> Your email registration is successfully submited.</p>
    </div>
    <?php unset($_SESSION['messageShow']);
} ?>
    <div class="signup-page">
      <div class="signup-box">
           <div class="logo">
               <a href="javascript:void(0);"><b>Partnership</b></a>
               <small>Management System</small>
           </div>
           <div class="card">
               <div class="body">
                  <form id="sign_up" action="<?php echo $base_url; ?>action.php" id="form_advanced_validation" method="post" enctype="multipart/form-data">
                     <input type="hidden" name="userroll" value="partner">
                       <div class="msg">Register Partner</div>
                       <div class="input-group">
                           <span class="input-group-addon">
                               <i class="material-icons">person</i>
                           </span>
                           <div class="form-line">
                               <input type="text" class="form-control" name="fn" minlength="3" placeholder="First Name" required>
                           </div>
                       </div>
                       <div class="input-group">
                           <span class="input-group-addon">
                               <i class="material-icons">person</i>
                           </span>
                           <div class="form-line">
                               <input type="text" class="form-control" name="ln" placeholder="Last Name" required>
                           </div>
                       </div>
                       <div class="input-group">
                           <span class="input-group-addon">
                               <i class="material-icons">email</i>
                           </span>
                           <div class="form-line">
                               <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                           </div>
                       </div>
                       <div class="input-group">
                           <span class="input-group-addon">
                               <i class="material-icons">group_work</i>
                           </span>
                           <div class="form-line">
                               <input type="text" class="form-control" name="percentage" placeholder="Enter Percentage" required>
                           </div>
                       </div>
                       <div class="form-group">
                          <input type="radio" name="gender" value="Male" id="male" class="with-gap"  required>
                          <label for="male">Male</label>
                          <input type="radio" name="gender" value="Female" id="female" class="with-gap"  required>
                          <label for="female" class="m-l-20">Female</label>
                       </div>
                       <div class="input-group">
                           <span class="input-group-addon">
                               <i class="material-icons">child_care</i>
                           </span>
                           <div class="form-line">
                               <input type="text" data-toggle="datepicker" class="form-control" name="birthday" minlength="3" placeholder="Date of Birth" required>
                           </div>
                       </div>
                       <div class="input-group">
                           <span class="input-group-addon">
                               <i class="material-icons">face</i>
                           </span>
                           <div class="form-line" style="border:0">
                              <label class="user-img" for="img">
                                  <input id="img" type="file" name="img"  required>
                              </label>
                           </div>
                       </div>
                       <input type="hidden" name="persentageCheck" value="0">
                       <input type="hidden" name="userRoll" value="Partner" >
                       <input type="hidden" name="atFirstRegisterUser" value="1">
                       <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">Register Partner</button>
                   </form>
               </div>
           </div>
       </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="<?php echo $base_url; ?>js/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo $base_url; ?>js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo $base_url; ?>js/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="<?php echo $base_url; ?>js/datepicker.js"></script>
    <script src="<?php echo $base_url; ?>js/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="<?php echo $base_url; ?>js/admin.js"></script>
    <script src="<?php echo $base_url; ?>js/emailSetting.js"></script>
    <script src="<?php echo $base_url; ?>js/app.js"></script>
</body>

</html>
