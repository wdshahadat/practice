<?php
/*
*
*  if forgot any user her account password
*  to get her account password
*
*/

require_once('functions/CheckerFn.php');
$c = new CheckerFn;
if(isset($_SESSION['login']) && $_SESSION['login'] === true) {
    return $c->redirect('index');
}
$_SESSION['userinfo'] = true;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Partnership Management system</title>
    <!-- Favicon-->

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
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
    <?php if(isset($_SESSION['actionfaild'])) { ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
            <input type="hidden" name="messageShow" value="0">
            <p><strong>Sorry! </strong> Actiion Faild.</p>
            <p>Please check your informetion then get account.</p>
        </div>
    <?php unset($_SESSION['actionfaild']);}
        if(isset($_SESSION['doesNotExist_e'])) { ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
            <input type="hidden" name="messageShow" value="0">
            <p><strong>Sorry! </strong> this email <b class="redColor"><?php echo $_SESSION['doesNotExist_e']; ?></b> does not exist. Enter your correct email.</p>
        </div>
    <?php unset($_SESSION['doesNotExist_e']);} ?>
    <div class="signup-page">
        <div class="signup-box">
            <div class="logo">
                <a href="javascript:void(0);"><b>Partnership</b></a>
                <small>Management System</small>
            </div>
            <div id="forgot_e" class="card collapse in">
                <div class="body">
                    <form action="<?php echo $base_url; ?>action.php" id="sign_in" method="POST">
                        <div class="msg">
                            Write down your email address that is associated with this system.
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">email</i>
                            </span>
                            <div class="form-line">
                                <input type="email" class="form-control" name="email" minlength="3" placeholder="Enter your Email Address" required>
                            </div>
                            <?php if(isset($_SESSION['invalidEmail'])) { ?>
                                <input type="hidden" name="messageShow" value="0">
                                <p'><b class="redColor" ><?php echo $_SESSION['invalidEmail']; ?></b> Invalid email format.</p>;
                            <?php unset($_SESSION['invalidEmail']); } ?>
                        </div>

                        <div class="row">
                            <div class="col-xs-6 forgot">
                                <input type="hidden" name="getBy" value="010">
                                <input class="btn btn-block bg-pink waves-effect" name="forgot" type="submit" value="Get account">
                                <a class="forgotChecker" href="#">Forgot email?</a>
                            </div>
                            <div class="col-xs-6 forgot_c">
                                <a href="<?php echo $base_url; ?>users.php">Sign in</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div id="forgot_a" class="card collapse">
                <div class="body">
                    <form action="<?php echo $base_url; ?>action.php" id="sign_up" method="POST">
                        <div class="msg">Insert the following information that was used while registering.</div>
                           <div class="input-group">
                           <span class="input-group-addon">
                               <i class="material-icons">person</i>
                           </span>
                           <div class="form-line">
                               <input type="text" class="form-control" name="fn" minlength="3" placeholder="First name" required>
                           </div>
                       </div>
                       <div class="input-group">
                           <span class="input-group-addon">
                               <i class="material-icons">person</i>
                           </span>
                           <div class="form-line">
                               <input type="text" class="form-control" name="ln" placeholder="Last naem" required>
                           </div>
                       </div>
                       <div class="input-group">
                           <span class="input-group-addon">
                               <i class="material-icons">child_care</i>
                           </span>
                           <div class="form-line">
                               <input type="text" data-toggle="datepicker" class="form-control" name="birthday" minlength="3" placeholder="Birth day" required>
                           </div>
                       </div>
                        <div class="row">
                            <div class="col-xs-6 forgot">
                                <input type="hidden" name="getBy" value="101">
                                <input class="btn btn-block bg-pink waves-effect" name="forgot" type="submit" value="Get account">
                                <a class="forgotChecker" href="#">You know email?</a>
                            </div>
                            <div class="col-xs-6 forgot_c">
                                <a href="<?php echo $base_url; ?>users.php">Sign in</a>
                            </div>
                        </div>
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
    <script src="<?php echo $base_url; ?>js/jquery.validate.js"></script>
    <script src="<?php echo $base_url; ?>js/datepicker.js"></script>

    <!-- Custom Js -->
    <script src="<?php echo $base_url; ?>js/admin.js"></script>
    <script src="<?php echo $base_url; ?>js/emailSetting.js"></script>
    <script src="<?php echo $base_url; ?>js/app.js"></script>
</body>

</html>
