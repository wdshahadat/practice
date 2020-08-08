<?php
/*
*
*  password reset page
*
*/


// if user want to change your account password
require_once('functions/base_url.php');
!file_exists('functions/db.php') ? header('Location: '.$base_url.'functions/databaseSettings.php'):'';
// require_once('functions/db.php');
require_once('functions/SqlQuery.php');
require_once('functions/CheckerFn.php');
$c = new CheckerFn;

// check partner if exists then check is logged then redirect home page
// isset($_SESSION['login']) && $_SESSION['login'] === true ? $c->redirect('index'): false;

// check query string is exists
if (isset($_GET) && count($_GET) === 2) {
    $db = $c->db;
    $key = array_keys($_GET);
    $val = array_values($_GET);
    $userName = $key[0];
    $userExists = $c->db->get_row('fms_admin', ['userName' => $userName]);
    if (isset($userExists)) {
        $check = (array) json_decode($userExists['userInfo_sc']);
        if(empty(array_diff(end($check), [$key[1], $val[1]]))) {
            $_SESSION['userInformetion'] = $userExists;
            $_SESSION['csrf'] = md5(rand().$userName.time());
            $_SESSION['ridirect_l'] = $_SERVER['REQUEST_URI'];
            $base_url = $base_url;
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
    <link href="<?php echo $base_url; ?>css/style.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>css/custom-style.css" rel="stylesheet" />
</head>
<body class="registerPage">
    <div class="alert alert-warning hider">
    </div>
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
    <?php if (isset($_SESSION['error_message'])) { ?>
        <input type="hidden" name="errorShow" value="0">
        <div class="alert alert-warning">
            Lorem ipsum dolor sit amet
        </div>
    <?php unset($_SESSION['error_message']); } ?>
    <div class="signup-page">
        <div class="signup-box">
            <div class="logo">
                <a href="javascript:void(0);"><b>Partnership</b></a>
                <small>Management System</small>
            </div>
            <div class="card">
                <div class="body">
                    <form action="<?php echo $base_url.'action.php'; ?>" id="sign_up" method="POST">
                        <div class="msg">Set new password.</div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">lock</i>
                            </span>
                            <div class="form-line">
                                <input type="password" class="form-control" name="passworda" minlength="3" placeholder="Enter new password" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">forward_30</i>
                            </span>
                            <div class="form-line">
                                <input type="hidden" name="cc" value="<?php echo $_SESSION['csrf']; ?>">
                                <input type="password" class="form-control" name="passwordr" minlength="6" placeholder="retype password" required>
                            </div>
                        </div>

                        <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">Reset password</button>
                    </form>
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

        <!-- Custom Js -->
        <script src="<?php echo $base_url; ?>js/admin.js"></script>
        <script src="<?php echo $base_url; ?>js/emailSetting.js"></script>
        <script src="<?php echo $base_url; ?>js/app.js"></script>
    </div>
</body>
</html>

<?php

} else {
    $c->redirect('404');
}
}else {
    $c->redirect('404');
}
}else {
    $c->redirect('404');
}
?>
