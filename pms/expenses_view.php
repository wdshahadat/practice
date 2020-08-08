<?php
require_once('pages/header.php');
/*
*
*  Expenses view
*
*/
    if(isset($_GET) && count($_GET) > 0) {
        $id = array_values($_GET);
        $id = $c->makeId($id[0]);
        $view = $c->db->get_row('fms_cost', ['cst_id' => $id]);
        if(isset($view)) {
            $_SESSION['costAction'] = $id;
            $currency = $_SESSION['currency'];
            $costDetails = json_decode($view['cost_details']);
            $productName = $costDetails->c_productName;
            $amount = $costDetails->c_amount;
            $memoData = isset($costDetails->c_memo) ? $costDetails->c_memo: [];
        }
    }
?>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Finance Expense Details</h2>
            </div>
            <!-- Advanced Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                                <div class="form-group">
                                    <div id="aniimated-thumbnials" class="list-unstyled row clearfix">
                                        <table class="table table-condensed otherCost">
                                            <thead>
                                                <tr>
                                                    <th width="10%">No</th>
                                                    <th width="35%">Expense Reason</th>
                                                    <th width="30%">Amount</th>
                                                    <th width="20%">Expense Document</th>
                                                </tr>
                                            </thead>
                                            <tbody id="costView">
                                               <?php
                                                  if(isset($productName)) {
                                                    $index = 0;
                                                     foreach (array_combine($productName, $amount) as $product_n => $p_amount) {
                                                        $index++;
                                                        $memo = isset($memoData[($index-1)]) && !empty($memoData[($index-1)]) ? '
                                                                <div class="memoContainer">
                                                                    <a href="'.$base_url.'uploadFiles/memo/'.$memoData[($index-1)].'" view-sub-html="Demo Description">
                                                                         <img class="img-responsive thumbnail" src="'.$base_url.'uploadFiles/memo/'.$memoData[($index-1)].'">
                                                                    </a>
                                                                </div>': 'Document not set';
                                                        echo '<tr class="input-co"><td><b>'.$index.'</b></td><td>'. $product_n .'</td><td>'.$p_amount .' '.$currency.'</td><td>'.$memo.'</td></tr>';
                                                     }
                                                  }
                                               ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6 col-sm-offset-1 col-sm-5 col-xs-12">
                                        <div class="totalCount-v">
                                            <h4 id="totalAdd"><?php if(isset($view)) { echo 'Total = '.$view['cst_amount'].' '.$currency;} ?></h4>
                                        </div>
                                    </div>
                              </div>
                           <?php if(isset($view)) { echo '<a class="btn btn-info waves-effect cu-btn" href="'.$base_url.'expenses_list.php">Go back</a>';} ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php include('pages/footer.php') ?>
