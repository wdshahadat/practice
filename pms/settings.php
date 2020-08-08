<?php
/*
*
*  atfirst set company informetions settings
*
*/


// check to confirm company settings is complete
require_once('functions/CheckerFn.php');
$base_url = Db::$url;

$_SESSION['sec_a'] = md5(rand().'login'.time());
$c = new CheckerFn;

// check company setting is exists
$settings = $c->db->get_row("company_settings");
if(isset($settings) && !empty($settings)) {
    return isset($_SESSION['login']) ? $c->redirect('index'):$c->redirect('login');
}
$_SESSION['atFirstSettings'] = 1;
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
    <link href="<?php echo $base_url; ?>css/sweetalert.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?php echo $base_url; ?>css/datepicker.css" rel="stylesheet" />
    <link href="<?php echo $base_url; ?>css/style.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>css/all-themes.css" rel="stylesheet">
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
    max-width: 700px;
}

.settingsPage .signup-page .signup-box a {
    color: white;
}
.settingsPage .wizard > .steps .disabled a, .settingsPage .actions > ul li.disabled > a {
    color: #aaa;
}
.disableClick {
    pointer-events: none;
}
.wizard > .actions ul li {
    cursor: pointer;
}
</style>
    <!-- Jquery Core Js -->
    <script src="<?php echo $base_url; ?>js/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo $base_url; ?>js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo $base_url; ?>js/bootstrap-select.js"></script>
    <script src="<?php echo $base_url; ?>js/jquery.validate.js"></script>
    <script src="<?php echo $base_url; ?>js/jquery.steps.js"></script>
    <script src="<?php echo $base_url; ?>js/sweetalert.min.js"></script>
    <script src="<?php echo $base_url; ?>js/waves.js"></script>
    <script src="<?php echo $base_url; ?>js/admin.js"></script>
</head>
<body class="settingsPage settings-container">

    <!--*PRELOADING*------->
    <div id="preLoadOverlayer"></div>
    <div id="preloadContainer">
        <div class="preload">
            <img src="<?php echo $base_url; ?>img/preloadImage.svg">
            <p class="loadingText">Loading...</p>
        </div>
    </div><!-- End -->

    <div class="signup-page">
        <div class="signup-box">
            <div class="logo">
                <a href="javascript:void(0);"><b>Partnership</b></a>
                <small>Management System</small>
            </div>
            <div class="card">

                <!-- invalid smtp connection error message -->
                <?php if (isset($_SESSION['smtpInvalid'])) {
                $error = $_SESSION['smtpInvalid']; ?>
                <div class="alert alert-danger">
                    <input type="hidden" name="messageShow" value="2">
                    <p><strong>Sorry!</strong> SMTP error. Unable to establish a connection with your email. check your all information and enter the correct information then retry again.</p>
                </div>
                <?php unset($_SESSION['smtpInvalid']);} ?>

            <div class="body">
                <form id="wizard_with_validation" action="<?php echo $base_url; ?>action" id="form_advanced_validation" method="post" enctype="multipart/form-data">
                    <h3>Company Information</h3>
                    <fieldset>
                        <div class="input-group">
                            <span class="input-group-addon">
                               <img class="currency-img" src="<?php echo $base_url; ?>img/business-icon.jpg" alt="">
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" name="companyName" placeholder="Company name" required <?php echo isset($error) ? 'value="'.$error['companyName'].'"':''; ?> >
                            </div>
                        </div>

                        <div class="input-group to-hide">
                            <span class="input-group-addon">
                               <img class="currency-img" src="<?php echo $base_url; ?>img/money-icon.jpg" alt="">
                            </span>
                            <div class="form-line">
                               <select name="userCurrency" class="form-control">
                                <option selected disabled hidden >Select your currency</option>
                                 <?php  echo isset($error) ? currencies_dropdown($error['userCurrency']):currencies_dropdown(); ?>
                               </select>
                            </div>
                        </div>

                        <div class="input-group">
                            <span class="input-group-addon">
                               <img class="currency-img" src="<?php echo $base_url; ?>img/starting-icon.png" alt="">
                            </span>
                            <div class="form-line">
                               <input type="text" data-toggle="datepicker" class="form-control" name="startingDate"  placeholder="Company starting date" <?php echo isset($error) ? 'value="'.$error['startingDate'].'"': 'required"' ?>>
                            </div>
                        </div>

                        <div class="input-group">
                            <span class="input-group-addon logo-icon">
                               <i>LOGO</i>
                            </span>
                            <div class="form-line" style="border:0">
                              <label class="user-img" for="logo">
                                  <input id="logo" type="file" name="logo"
                                  <?php echo isset($error) ? ' ><input type="hidden" name="preLogo" value="'.$error['companyLogo'].'"': 'required"'; ?>>
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    <h3>Contact Email informetion</h3>
                    <fieldset>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">cloud</i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" name="smtpHost" placeholder="smtp.gmail.com" required>
                            </div>
                            <p class="placeInfo">SMTP host address</p>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">build</i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" name="smtpPort" required>
                            </div>
                            <p class="placeInfo dable-pi">
                                <span>SSL: 465</span><br>
                                <span>TLS: 587</span>
                            </p>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">lock</i>
                            </span>
                            <div class="form-line to-hide">
                                <select name="smtpAuth" id="smtpAuth" class="form-control show-tick">
                                    <option value="none">None</option>
                                    <?php
                                    $authOption = ['SSL','TLS'];
                                    foreach ($authOption as $key => $value) {
                                        echo '<option value="'.$key.'">'.$value.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <p class="placeInfo">Authentication</p>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">contact_mail</i>
                            </span>
                            <div class="form-line">
                                <input type="email" class="form-control" name="contactEmail" placeholder="Contact email Address" required <?php echo isset($error) ? 'value="'.$error['contactEmail'].'"':''; ?> >
                            </div>
                            <?php if(isset($_SESSION['invalidEmail'])) { ?>
                                <input type="hidden" name="messageShow" value="0">
                                <p><b class="redColor"><?php echo $_SESSION['invalidEmail']; ?></b> Invalid email format.</p>;
                            <?php unset($_SESSION['invalidEmail']); } ?>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">vpn_key</i>
                            </span>
                            <div class="form-line">
                                <input type="hidden" id="settings_error" data-settings_error="<?php echo isset($error) ? 1:null; ?>">
                                <input type="password" class="form-control" name="emailPassword" minlength="6" placeholder="Contact email password" required <?php echo isset($error) ? 'value="'.$error['emailPassword'].'"':''; ?> >
                            </div>
                        </div>
                    </fieldset>
                    <input type="hidden" name="sec_a" value="<?php echo $_SESSION['sec_a']; ?>">
                        <input type="submit" id="settingsSubmit">
                </form>
            </div>
        </div>
    </div>
</div>
    <script src="<?php echo $base_url; ?>js/datepicker.js"></script>
    <script src="<?php echo $base_url; ?>js/form-wizard.js"></script>
    <script src="<?php echo $base_url; ?>js/app.js"></script>
</body>
</html>
