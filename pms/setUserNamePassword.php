<?php
/*
*
*  create a user account
*
*/


// to create a user account
// to velidetion user link
if (isset($_GET) && count($_GET) === 1) {
    require_once('functions/CheckerFn.php');
    $c = new CheckerFn;
    $db = $c->db;

    $userName_k = $db->validation(key($_GET));
    $password = $db->validation(reset($_GET));
    $userExists = $db->get_row('fms_admin', ['userName' => $userName_k, 'password' => $password]);
    if (isset($userExists)) {
        unset($userExists['password']);
        unset($userExists['userInfo_sc']);
        $_SESSION['userinfo'] = $userExists;
        $_SESSION['createUserNamePassword'] = $userExists;
?><!DOCTYPE html>
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
    <link href="<?php echo Db::$url ?>css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo Db::$url ?>css/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?php echo Db::$url ?>css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?php echo Db::$url ?>css/style.css" rel="stylesheet">
    <link href="<?php echo Db::$url ?>css/custom-style.css" rel="stylesheet" />
</head>
<body class="registerPage">
    <?php if (isset($_SESSION['error_message'])) { ?>
        <input type="hidden" name="errorShow" value="0">
        <div class="alert alert-warning alert-dismissible" role="alert">
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

                    <form action="action.php" id="sign_up" method="POST">
                        <div class="msg">Set your user name and password.</div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" name="userName" minlength="3" placeholder="Enter your choiceful name" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">lock</i>
                            </span>
                            <div class="form-line">
                                <input type="password" class="form-control" name="password" minlength="6" placeholder="Enter assword" required>
                            </div>
                        </div>

                        <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">Create user</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Jquery Core Js -->
        <script src="<?php echo Db::$url ?>js/jquery.min.js"></script>

        <!-- Bootstrap Core Js -->
        <script src="<?php echo Db::$url ?>js/bootstrap.js"></script>

        <!-- Waves Effect Plugin Js -->
        <script src="<?php echo Db::$url ?>js/waves.js"></script>

        <!-- Validation Plugin Js -->
        <script src="<?php echo Db::$url ?>js/jquery.validate.js"></script>

        <!-- Custom Js -->
        <script src="<?php echo Db::$url ?>js/admin.js"></script>
        <script src="<?php echo Db::$url ?>js/emailSetting.js"></script>
        <script src="<?php echo Db::$url ?>js/app.js"></script>
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
?>
