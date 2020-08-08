<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// error_reporting(E_ALL);
// ini_set('display_errors', true);
// ini_set('display_startup_errors', true);

require_once('functions/CheckerFn.php');
$base_url = Db::$url;


/*
*
*  Login page
*
*/
$_SESSION['sec_a'] = md5(rand() . 'login' . time());
$c = new CheckerFn();

// installation check
if (!isset($_SESSION['install'])) {
    $c->intallCheck();
}

// check login, if logged in then redirect home page
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    return $c->redirect('index');
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Partnership Management system</title>
    <!-- Favicon-->
    <link rel="<?php echo $base_url; ?>" href="css/favicon.ico" type="image/x-icon">

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
    <link href="<?php echo $base_url; ?>css/style.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>css/custom-style.css" rel="stylesheet" />
</head>

<body class="registerPage">
    <?php if (isset($_SESSION['registerSuccess'])) { ?>
        <div class="alert alert-success">
            <input type="hidden" name="messageShow" value="2">
            <p><strong>Well done!</strong> User registration info is successfully submited.</p>
            <p>Send a email <b><?php echo $_SESSION['registerSuccess']; ?></b> to create user</p>
            <p><b>Please check</b> your email and create your user name and password.</p>
        </div>
    <?php unset($_SESSION['registerSuccess']);
    } ?>
    <?php $datac = $_SESSION;
    if (isset($_SESSION['a_create_success'])) { ?>
        <div class="alert alert-success">
            <input type="hidden" name="messageShow" value="2">
            <p><strong>Well done!</strong> User account create is success.</p>
            <p>Your account informetion is sent this <b><?php echo $_SESSION['a_create_success']; ?></b>.</p>
            <p>To get your account informetion check mail box.</p>
        </div>
    <?php unset($_SESSION['a_create_success']);
    } ?>
    <?php if (isset($_SESSION['resetSuccess'])) { ?>
        <div class="alert alert-success">
            <input type="hidden" name="messageShow" value="1">
            <p><strong>Well done!</strong> Reset password is success.</p>
            <p>Your account informetion has sent this <b><?php echo $_SESSION['resetSuccess']; ?></b>.</p>
            <p>To get your account informetion check mail box.</p>
        </div>
    <?php unset($_SESSION['resetSuccess']);
    } ?>
    <?php if (isset($_SESSION['emailSend'])) { ?>
        <div class="alert alert-success">
            <input type="hidden" name="messageShow" value="2">
            <p><strong>Well done!</strong> Your account informetion is send this <b><?php echo $_SESSION['emailSend']; ?></b> email.</p>
            <p><b>Please check</b> your email.</p>
        </div>
    <?php unset($_SESSION['emailSend']);
    } ?>
    <div class="login-page">
        <div class="login-box">
            <div class="logo">
                <a href="javascript:void(0);"><b>Partnership</b></a>
                <small>Management System</small>
            </div>
            <?php if (isset($_SESSION['inputEmpty'])) { ?>
                <p class="col-orange"><b>Sorry! </b> Please insert your currect information.</p>;
            <?php unset($_SESSION['inputEmpty']);
            } ?>
            <div class="card">
                <div class="body">
                    <form action="<?php echo $base_url; ?>functions/auth" id="sign_in" method="POST">

                        <div class="input-group to-hide">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i>
                            </span>
                            <select name="author" id="author" class="form-control">
                                <option hidden selected disabled value="">Select Author</option>
                                <option value="partner">Partner</option>
                                <option value="manager">Manager</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i>
                            </span>
                            <div class="form-line">
                                <input type="text" id="username" class="form-control" name="userName" <?php if (isset($_SESSION['validUser'])) {
                                                                                            echo 'value="' . $_SESSION['validUser'] . '"';
                                                                                            unset($_SESSION['validUser']);
                                                                                        } ?> placeholder="Username" required autofocus>
                            </div>
                            <?php if (isset($_SESSION['invalidEmail'])) { ?>
                                <input type="hidden" name="messageShow" value="0">
                                <p><b class="redColor"><?php echo $_SESSION['invalidEmail']; ?></b> Invalid email format.</p>;
                            <?php unset($_SESSION['invalidEmail']);
                            } ?>
                            <?php if (isset($_SESSION['user_n_error'])) { ?>
                                <p class="col-orange">User name is invalid</p>
                            <?php unset($_SESSION['user_n_error']);
                            } ?>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">lock</i>
                            </span>
                            <div class="form-line">
                                <input type="hidden" name="cc" value="<?php echo $_SESSION['sec_a']; ?>">
                                <input type="password" id="password" class="form-control" name="password" placeholder="Password" required autofocus>
                            </div>
                            <?php if (isset($_SESSION['pass_error'])) { ?>
                                <p class="col-orange">User password is invalid</p>
                            <?php unset($_SESSION['pass_error']);
                            } ?>
                        </div>
                        <div class="row">
                            <div class="col-xs-8 p-t-5">
                                <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                                <label for="rememberme">Remember Me</label>
                            </div>
                            <div class="col-xs-4">
                                <input class="btn btn-block bg-pink waves-effect" name="sign" type="submit" value="SIGN IN">
                            </div>
                        </div>
                        <div class="row m-t-15 m-b--20">
                            <div class="col-xs-6">
                            </div>
                            <div class="col-xs-6 align-right">
                                <a href="<?php echo $base_url; ?>forgotPassword.php">Forgot Password?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script <?php echo $base_url; ?>src="js/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo $base_url; ?>js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo $base_url; ?>js/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="<?php echo $base_url; ?>js/jquery.validate.js"></script>
    <!-- Custom Js -->
    <script src="<?php echo $base_url; ?>js/admin.js"></script>
    <script src="<?php echo $base_url; ?>js/sign-in.js"></script>
</body>

</html>
