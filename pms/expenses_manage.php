<?php
require_once('pages/header.php');
unset($_SESSION['bank']);
unset($_SESSION['costAction']);
$_SESSION['costAction'] = 1;
$_SESSION['sec_a'] = md5(rand().'cost'.time());

/*
*
*  Expenses add or edit
*
*/

    if(isset($_GET) && !empty($_GET)) {
        $id = array_values($_GET);
        $id = $c->makeId($id[0]); // to get a solid id
        $edit = $c->db->get_row('fms_cost', ['cst_id' => $id]);
        if(isset($edit)) {
            $_SESSION['costEdit'] = $id;
            unset($_SESSION['costInsert']);
            $costDetails = json_decode($edit['cost_details']); // expense details data
            $productName = $costDetails->c_productName; // expense resons
            $amount = $costDetails->c_amount; // expense amounts
        }
    }else {
        unset($_SESSION['costEdit']);
        $_SESSION['costInsert'] = 1;
    }
    $currency = $_SESSION['currency'];
?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2><?php if(isset($edit)) { echo 'Edit Expense';}else {echo 'Add Expense';} ?></h2>
            </div>
            <!-- Advanced Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <?php if (isset($_SESSION['invalidAmount'])) { ?>
                            <div class="alert alert-danger">
                                <input type="hidden" name="messageShow" value="0">
                                <p><strong>Sorry!</strong> Your cash amount is <b>invalid = <?php echo $_SESSION['invalidAmount']; ?></b>.</p>
                                <p>Please enter a currect number.</p>
                            </div>
                            <?php unset($_SESSION['invalidAmount']);} ?>
                            <?php if (isset($_SESSION['info_message'])) { ?>
                            <div class="alert alert-success">
                                <input type="hidden" name="messageShow" value="0">
                                <p><strong>Well done!</strong> Your expense is successfully <?php echo $_SESSION['info_message']; ?>.</p>
                            </div>
                            <?php unset($_SESSION['info_message']);} ?>
                            <form id="form_advanced_validation" action="<?php echo $base_url; ?>action.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="sec_a" <?php echo 'value="'.$_SESSION["sec_a"].'"'; ?>>
                                <div id="message"></div>
                               <div class="form-group">
                                   <table id="anotherCostTable" class="table table-condensed otherCost">
                                      <thead>
                                         <tr>
                                            <th width="10%">No</th>
                                            <th width="35%">Expense Reason</th>
                                            <th width="30%">Amount</th>
                                            <th width="20%">Document</th>
                                         </tr>
                                      </thead>
                                      <tbody id="costDa">
                                       <?php
                                          if(isset($productName)) {
                                            $index = 0;
                                             foreach (array_combine($productName, $amount) as $product_n => $p_amount) {
                                                $index++;
                                                echo '<tr class="input-co"><td><b>'.$index .'</b></td><td><input type="text" value=" '. $product_n .'" class="form-control" name="costCn[]"></td><td><input type="text" value=" '.$p_amount.'" class="form-control" name="costCa[]"></td><td><input type="file" name="memo[]" multiple ><span class="icon-c" aria-hidden="true" edit-toggle="tooltip" edit-placement="top" title="Remove this row"><i class="material-icons done-i">done</i> <i class="material-icons delete-i">delete</i></span></td></tr>';
                                             }
                                          }
                                       ?>
                                      </tbody>
                                   </table>
                                   <button type="button" class="btn btn-success" id="plus"><i class="material-icons">add_box</i></button>
                                   <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6 col-sm-offset-1 col-sm-5 col-xs-12">
                                        <div class="totalCount-v">
                                            <h4 id="totalAdd"><?php if(isset($edit)) { echo 'Total = '.$edit['cst_amount'].' '.$currency;} ?></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="submitCost">
                                    <?php if(isset($edit)) { echo '<input type="hidden" name="upId" value="'.$id.'">';} ?>
                                    <input type="hidden" name="amount" <?php if(isset($edit)) { echo 'value= '.$edit['cst_amount'];} ?> >
                                    <input type="hidden" name="costAction" value="<?php if(isset($edit)) { echo 'ed';}else {echo 'ad';} ?>">
                                    <div class="clickChecker"><input class="btn btn-<?php echo isset($edit) ? 'warning': 'primary clickDisabled'; ?> waves-effect" type="submit" id="submit" value="<?php if(isset($edit)) { echo 'Update';}else {echo 'Submit';} ?>"></div>
                                    <?php if(isset($edit)) { echo '<a class="btn btn-info waves-effect cu-btn cost-back" href="'.$base_url.'expenses_list.php">Go back</a>';} ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php require_once('pages/footer.php') ?>
