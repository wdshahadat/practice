<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
require_once('base_url.php');
file_exists('db.php') ? header('Location: '.$base_url.'settings.php'):false;
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
<style>
    .settingHeading {
        width: 100%;
        text-align: center;
        padding: 30px 0 0 0;
    }
    .settings-section {
        width: 50%;
        float: left;
        padding: 20px;
    }
    .number {
        margin-right: 5px;
        border-radius: 50%;
        box-shadow: 0 0 2px #fff;
        border: 2px solid #486962;
        padding: 6px 8px 6px 10px;
    }
    .settingsPage .signup-page, .registerPage .login-page {
        max-width: 360px;
    }

    .settingsPage .wizard > .steps .disabled a, .settingsPage .actions > ul li.disabled > a {
        color: #aaa;
    }
    #dbSetting {
        float: right;
    }
    .error {
        color: red;
        text-align: center;
    }
</style>
</head>
<body class="settingsPage">

    <!--*PRELOADING*------->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div><!-- End -->

    <div class="signup-page">
      <div class="signup-box">
           <div class="logo">
               <a href="javascript:void(0);"><b>Partnership</b></a>
               <small>Management System</small>
           </div>
            <div class="card">

                <div id="errorMessage"></div>

                <form id="setDatabase" action="" id="form_advanced_validation" method="post" enctype="multipart/form-data">
                    <div class="body">
                        <div class="input-group">
                            <span class="input-group-addon">
                               <img class="currency-img" src="<?php echo $base_url; ?>img/server-icon.png" alt="">
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" name="dbhost" placeholder="host name" value="<?php echo $_SERVER['HTTP_HOST'] ?>">
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                               <img class="currency-img" src="<?php echo $base_url; ?>img/database-icon.png" alt="">
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" name="dbname" placeholder="database name" required <?php echo isset($dbname) ? 'value="'.$dbname.'"':''; ?>>
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                               <i class="material-icons">account_circle</i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" name="dbusername" placeholder="Database user name">
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                               <i class="material-icons">vpn_key</i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" name="dbpassword" placeholder="Database password">
                            </div>
                        </div>
                        <div class="input-group">
                            <input type="submit" id="dbSetting" class="btn btn-info btn-sm" name="submit" value="Set database">
                        </div>
                    </div>
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
    <script src="<?php echo $base_url; ?>js/jquery.steps.min.js"></script>
    <script src="<?php echo $base_url; ?>js/form-wizard.js"></script>
    <!-- Validation Plugin Js -->
    <script src="<?php echo $base_url; ?>js/datepicker.js"></script>
    <script src="<?php echo $base_url; ?>js/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="<?php echo $base_url; ?>js/admin.js"></script>
    <script src="<?php echo $base_url; ?>js/emailSetting.js"></script>
    <script src="<?php echo $base_url; ?>js/app.js"></script>
</body>
</html>
<script>
    $(document).on('click', '.waves-effect', function() {
        $('.wizard > .actions li:last-child a').text('Next step');
    });
    $(document).on('click', '.wizard > .actions li:last-child a', function() {
        $('#submit').trigger('click');
    });

    $(document).on('submit', '#setDatabase', function(e) {
        $("#preLoadOverlayer,#preloadContainer").fadeIn();
        $('#errorMessage').fadeOut();
        e.preventDefault();

        var link = dirname(window.location.href, 1);
        $.post(link+'/functions/pms_config.php', formDataToObject(new FormData(this)), function(data) {
            if(is_json(data, 1)) {
                var errorDom = '';
                for(var i in jsonToObj) {
                    errorDom += '<p class="error">'+jsonToObj[i]+'</p>';
                }
                $('#errorMessage').html(errorDom).fadeIn();
                $("#preLoadOverlayer,#preloadContainer").fadeOut("slow");
            }else if(data === 'connection is ok') {
                return window.location.replace(link+'/settings.php');
            }else {
                return $("#preLoadOverlayer,#preloadContainer").fadeOut("slow");
            }
            setTimeout(function() {$("#preLoadOverlayer,#preloadContainer").fadeOut();}, 3000);
        });
    });
</script>
