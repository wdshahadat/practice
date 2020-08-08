<?php
require_once('pages/header.php');

/*
*
*  manage company informetions settings
*
*/

$currency = $c->db->get_row('company_settings');
$_SESSION['sec_a'] = md5(rand().'currency'.time());
extract($currency); // company setting informetion array data extract
?>
<style>

    .setting-table.table > thead:first-child > tr:first-child > th {
        font-size: 18px;
    }
</style>
<section class="content editContainer settings-container">
    <div class="container-fluid">
        <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <div>
                                <button type="button" class="btn btn-primary btn-sm settingButton clickCheck">Close</button>
                                <form id="registration" action="<?php echo $base_url; ?>action" id="form_advanced_validation" method="post" enctype="multipart/form-data">
                                    <div id="wizard_horizontal">
                                        <h2>Company Information</h2>
                                        <section>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                   <img class="currency-img" src="<?php echo $base_url; ?>img/business-icon.jpg" alt="">
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="companyName" value="<?php echo $companyName; ?>"  placeholder="Company name" >
                                                </div>
                                            </div>
                                            <div class="input-group to-hide">
                                                <span class="input-group-addon">
                                                   <img class="currency-img" src="<?php echo $base_url; ?>img/money-icon.jpg" alt="">
                                                </span>
                                                <div class="form-line">
                                                   <select name="userCurrency" id="userCurrency" class="form-control">
                                                    <option selected disabled hidden >Select your currency</option>
                                                     <?php echo currencies_dropdown($userCurrency); ?>
                                                   </select>
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                   <img class="currency-img" src="<?php echo $base_url; ?>img/starting-icon.png" alt="">
                                                </span>
                                                <div class="form-line">
                                                   <input type="text" data-toggle="datepicker" class="form-control date_pic" name="startingDate" placeholder="Company starting date" required value="<?php echo $startingDate;?>">
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-addon logo-icon">
                                                   <i>LOGO</i>
                                                </span>
                                                <div class="form-line" style="border:0">
                                                  <label class="user-img" for="logo">
                                                      <input id="logo" type="file" name="logo">
                                                      <?php echo '<input type="hidden" name="preLogo" value="'.$companyLogo.'">';?>

                                                    </label>
                                                </div>
                                            </div>
                                        </section>

                                        <h2>Email setting</h2>
                                        <section>
                                           <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">cloud</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="smtpHost" placeholder="smtp.gmail.com" required value="<?php echo $smtpHost; ?>">
                                                </div>
                                                <p class="placeInfo">SMTP host address</p>
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">build</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="smtpPort" value="<?php echo $smtpPort; ?>">
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
                                                        <option value="none" <?php echo isset($smtpAuth) && $smtpAuth === 'none' ? 'selected':'' ?>>None</option>
                                                        <?php
                                                        $authOption = ['SSL','TLS'];
                                                        foreach ($authOption as $value) {
                                                            $selected = isset($smtpAuth) && $smtpAuth === $value ? 'selected': '';
                                                            echo '<option value="'.$value.'" '.$selected.' >'.strtoupper($value).'</option>';
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
                                                    <input type="email" class="form-control" name="contactEmail" placeholder="Email Address" value="<?php echo $contactEmail; ?>">
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
                                                    <input type="password" class="form-control" name="emailPassword" minlength="6" placeholder="Password" required value="<?php echo $emailPassword; ?>">
                                                </div>
                                            </div>
                                        <input type="hidden" name="settingsEdit" value="<?php echo $id; ?>">
                                        <input type="hidden" name="sec_a" value="<?php echo $_SESSION['sec_a']; ?>">
                                        <input type="submit" id="submit" hidden>
                                        </div>
                                        </section>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>
<section class="content detailsContainer">
    <div class="container-fluid">
        <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                    <?php if (isset($_SESSION['settingsUpdated'])) { ?>
                    <div class="alert alert-success">
                        <input type="hidden" name="messageShow" value="1">
                        <p><strong>Well done!</strong> Your company settings is successfully Updated.</p>
                    </div>
                    <?php unset($_SESSION['settingsUpdated']);} ?>

                        <div class="body">
                            <button type="button" class="btn btn-warning btn-sm settingButton clickCheck">Edit</button>
                            <div class="logoContainer">
                                <img src="<?php echo $base_url.$companyLogo; ?>" alt="">
                            </div>
                            <table class="setting-table table">
                                <thead>
                                    <tr>
                                        <th width="50%">Company Information</th>
                                        <th width="50%">Email Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><label>Company name: </label> <?php echo $companyName; ?></td>
                                        <td><label>SMTP host: </label> <?php echo $smtpHost; ?></td>
                                    </tr>
                                    <tr>
                                        <td><label>Your currency symbol: </label> <b><?php echo $_SESSION['currency']; ?></b></td>
                                        <td><label>SMPT port: </label> <?php echo $smtpPort; ?></td>
                                    </tr>
                                    <tr>
                                        <td><label>Starting Date: </label> <?php echo $startingDate; ?></td>
                                        <td><label>Authentication: </label> <?php echo $smtpAuth; ?></td>
                                    </tr>
                                    <tr>
                                        <td><label>Contact email: </label> <?php echo $contactEmail; ?></td>
                                        <td><label>Password: </label> <?php
                                            $pass = strlen($emailPassword);
                                            $password = '';
                                            for ($i=0; $i < $pass; $i++) {
                                                $password .= '*';
                                            }
                                            echo $password;
                                        ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>
<script src="<?php echo $base_url; ?>js/jquery.steps.min.js"></script>
<script src="<?php echo $base_url; ?>js/form-wizard.js"></script>
<?php require_once('pages/footer.php');?>
