<?php
require_once('pages/header.php');

/*
*
*  user financial account details page
*
*/

    $db = $c->db;
    $currentMonth = date('m');
    $baseUrl = $base_url;
    $id = $_SESSION['userinfo']['id'];
    $symbol = $_SESSION['currency'];

    $partner = $db->get_row('fms_admin', ['userRoll' => 'Partner', 'id' => $_SESSION['userinfo']['id']]);
    extract($partner);

    $earning = $db->get('', "SELECT amount, id FROM fms_bank WHERE ba_date LIKE '$currentMonth%'");
    $expense = $db->get('', "SELECT cst_amount, id FROM fms_cost WHERE cst_a_date LIKE '$currentMonth%'");
    $partner = $db->get('fms_admin', ['userRoll' => 'Partner', 'id' => $_SESSION['userinfo']['id']]);

    $totalEarn = isset($earning) ? array_sum($c->arrayKeyFilter($earning, 'amount')):0;
    $totalExpense = isset($expense) ? array_sum($c->arrayKeyFilter($expense, 'cst_amount')):0;

    $expense_by_you = isset($expense) ? array_filter($expense, function ($expense) use ($id) { return ($expense->id === $id); }):[];

    $countingDetails = '';

    $persentageEarn = ($totalEarn / 100) * intval($percentage);

    $yourExpense = !empty($expense_by_you) ? array_sum($c->arrayKeyFilter($expense_by_you, 'cst_amount')):0;

    $persentageShare = ($totalExpense / 100) * intval($percentage);
    $balanceDue = ($yourExpense + $persentageEarn) - $persentageShare;
    $revenue = ($yourExpense + $persentageEarn) - $persentageShare;
    // $revenue = number_format($revenue);


?>
<section class="content">
   <div class="card">
         <div class="container-fluid">
         <div class="row clearfix partner-account-details">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="my-account-head">
                    <h3>My Share: <?php echo $percentage ?>%</h3>
                    <?php
                    $b_color = $revenue >= 0 ? 'success':'warning';
                    $myFinance = $revenue >= 0 ? 'Receivable Amount = ':'Payable Amount = ';
                    $myFinance .= number_format($revenue);
                    ?>
                    <div class="ac-head-con">
                        <div class="account-h <?php echo $b_color ?>">
                            <h4><?php echo $myFinance.' '.$symbol ?></h4>
                        </div>
                    </div>
                  </div>

                <div class="col-lg-6 col-md-6 col-sm-6"></div>
                <div class="col-lg-6 col-md-6 col-sm-6 empty-div">
                    <div class="option-c">
                        <form action="" method="post">
                            <div class="col-lg-6 col-md-6 col-sm-8">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 to-hide">
                                <label for="month">Month : </label>
                                <select name="month" id="month" class="form-control show-tick">
                                    <option hidden selected disabled >Select month</option>
                                    <?php
                                        for ($i=1; $i < 13; $i++) {
                                            $dateObj   = DateTime::createFromFormat('!m', $i);
                                            $monthName = $dateObj->format('F');
                                            $m = $dateObj->format('m');
                                            $selected = $m === $currentMonth ? 'selected':'';
                                            echo '<option value="'.$i.'" '.$selected.'>'.$monthName.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

               <div class="my-account">
                  <div class="total-cost earn user-details">
                     <div class="total-cc my-info">
                      <h4>My financial account details</h4>
                        <p id="my_expense">Expense from me: <span><?php echo number_format($yourExpense).' '.$symbol; ?></span></p>
                        <p id="my_share_expense">My share wise expense ( <?php echo $percentage ?>% ): <span><?php echo number_format($persentageShare).' '.$symbol; ?></span></p>
                        <p id="revenue">Revenue :  <span><?php echo number_format($revenue).' '.$symbol; ?></span></p>
                     </div>
                  </div>
                  <div class="total-cost user-details">
                     <div class="total-cc">
                      <h4>Financial details of the company</h4>
                        <p id="totalEarn">Total earn : <span><?php echo number_format($totalEarn).' '.$symbol; ?></span></p>
                        <p id="totalExpense">Total expense : <span><?php echo number_format($totalExpense).' '.$symbol; ?></span></p>
                        <p id="balance">Total Balance :  <span><?php echo number_format($totalEarn - $totalExpense).' '.$symbol; ?></span></p>
                        <!-- <span>Earn <b>-</b> Expense <b>=</b> Balance</span><br> -->
                        <span id="countDetails">( <?php echo number_format($totalEarn). ' <b>-</b> ' . number_format($totalExpense) . ' <b>=</b> ' . number_format($totalEarn - $totalExpense); ?> )</span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
   </div>
</section>

<?php include('pages/footer.php'); ?>
