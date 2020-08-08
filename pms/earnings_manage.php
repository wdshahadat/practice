<?php
require_once('pages/header.php');
$_SESSION['bank'] = 1;
unset($_SESSION['userAction']);
unset($_SESSION['costAction']);
unset($_SESSION['cashInsert']);
unset($_SESSION['cashUpdate']);
$_SESSION['sec_a'] = md5(rand().'bank'.time());

/*
*
*  Earning amount add or edit
*
*/

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
    $id = array_values($_GET);
    $id = $c->makeId($id[0]);
    $edit = $c->db->get_row('fms_bank', ['bankId' => $id]);
    if(isset($edit)) {
        $_SESSION['cashUpdate'] = 1;
    }
}else {
    $_SESSION['cashInsert'] = 1;
}
?>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2><?php if(isset($edit)) {echo ' Update earning amount';}else {echo 'Add Earnings';} ?></h2>
            </div>
            <!-- Advanced Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <?php if (isset($_SESSION['invalidAmount'])) { ?>
                            <div class="alert alert-danger">
                                <input type="hidden" name="messageShow" value="0">
                                <p><strong>Sorry!</strong> Your earning amount is <b>invalid = <?php echo $_SESSION['invalidAmount']; ?></b>.</p>
                                <p>Please enter a currect number.</p>
                            </div>
                            <?php unset($_SESSION['invalidAmount']);} ?>
                            <?php if (isset($_SESSION['success_ms'])) { ?>
                            <div class="alert alert-success">
                                <input type="hidden" name="messageShow" value="0">
                                <p><strong>Well done!</strong> Your earning amount is successfully submited.</p>
                            </div>
                            <?php unset($_SESSION['success_ms']);} ?>
                            <form id="form_advanced_validation" action="action.php" method="POST">
                                <input type="hidden" name="sec_a" value="<?php echo $_SESSION['sec_a']; ?>">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control alpha_valid" name="source" <?php if(isset($edit)) {echo 'value="'.$edit["earnSource"].'"';} ?> placeholder="Earn source" required>
                                    </div>
                                    <p class="alphabet_er"><b>Sorry! </b> Please enter alphabet.</p>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control num_valid" name="amount" <?php if(isset($edit)) {echo 'value="'.$edit["amount"].'"';} ?> placeholder="Enter amount" required>
                                    </div>
                                    <p class="number_er"><b>Sorry! </b> Please Enter a valid number.</p>
                                </div>
                                <?php
                                    if(isset($edit['bankId'])) {
                                        echo '<input type="hidden" name="updateId" value="'.$edit['bankId'].'">';
                                    }
                                ?>
                                <input class="btn btn-<?php if(isset($edit)) {echo 'warning';}else {echo 'primary';} ?> waves-effect submit" type="submit" <?php if(isset($edit)) {echo 'value="Update"';}else {echo 'value="Submit"';} ?>>
                               <?php if(isset($edit)) { ?>
                                <a style="float:right;color:white;" class="btn btn-info waves-effect cu-btn" href="<?php echo $base_url; ?>earning_list.php">Go back</a>
                                <?php }?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php require_once('pages/footer.php') ?>
