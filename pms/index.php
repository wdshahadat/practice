<?php include('pages/header.php');

/*
*
*  Hom page
*
*/


// to get chart data
$earn = $c->db->get('', "SELECT amount, ba_date FROM fms_bank");
$cost = $c->db->get('', "SELECT cst_amount, cst_a_date FROM fms_cost");
$users = $c->db->get_row('', "SELECT COUNT(id) AS users FROM fms_admin WHERE userRoll = 'Partner'");

// chart data proces
function getChartData($data) {
    $monthName = [];
    $totalAmount = [];
    $monthlyAmounts = [];
    for ($i=1; $i < date('m')+1; $i++) {
        $amount = [];
        $monthObj = DateTime::createFromFormat('m', $i);
        if(is_array($data) || is_object($data)) {
            foreach ($data as $key => $m) {
                $month = isset($m->ba_date) ? $m->ba_date: $m->cst_a_date;
                $financeMonth = DateTime::createFromFormat('m-d-Y', $month);

                if(intval($financeMonth->format('m')) === $i) {
                    $totalAmount[] = isset($m->amount) ? $m->amount: $m->cst_amount;
                    $amount[] = isset($m->amount) ? $m->amount: $m->cst_amount;
                }
            }
            $total = array_sum($totalAmount);
            $monthlyAmounts[] = array_sum($amount);
        }else {
            $total = 0;
            $monthlyAmounts[] = 0;
        }
        $monthName[] = $monthObj->format('M');
    }
    return ['total' => $total, 'months' => $monthName, 'monthly_a' => $monthlyAmounts];
}
$earnData = getChartData($earn);
$costData = getChartData($cost);
$chart = json_encode( [
    'chartData' => ['earn' => $earnData['monthly_a'],'cost' => $costData['monthly_a']],
    'chartLabelData' => $earnData['months']
]);

?>
<section class="content">
   <div class="container-fluid">
         <div class="row">

          <?php if (isset($_SESSION['editSuccess'])) { if($_SESSION['userinfo']['userRoll'] === 'Manager') { ?>
             <div class="alert alert-success">
                <input type="hidden" name="messageShow" value="2">
                <p><strong>Well done!</strong> User informetion is successfully updated.</p>
                <?php if (isset($_SESSION['resetSuccess'])) { ?>
                    <p>Reset password is success.</p>
                    <p>Your account informetion has sent this <b><?php echo $_SESSION['resetSuccess']; ?></b>.</p>
                    <p>To get your account informetion check mail box.</p>
                <?php unset($_SESSION['resetSuccess']);}else { echo '<input type="hidden" name="messageShow" value="0">';} ?>
            </div>
          <?php }  unset($_SESSION['editSuccess']);} ?>
            <div class="boxContainer">
               <div class="box-cont">
                    <a href="<?php echo $base_url; ?>users.php">
                        <div class="info-box-2 bg-color hover-expand-effect">
                            <div class="content">
                                <div class="number count-to" data-from="0" data-to="<?php echo $users['users']; ?>" data-speed="1500" data-fresh-interval="1">1</div>
                            </div>
                            <div class="text">Partners</div>
                        </div>
                    </a>
                </div>
                <div class="box-cont">
                    <a href="<?php echo $base_url; ?>earning_details.php">
                        <div class="info-box-2 bg-color hover-expand-effect">
                            <div class="content">
                                <div class="icon">
                                    <i class="material-icons"><?php echo $_SESSION['currency']; ?></i>
                                </div>
                                <div class="number count-to" data-from="0" data-to="<?php echo $earnData['total']; ?>" data-speed="1000" data-fresh-interval="20">1</div>
                            </div>
                            <div class="text">Total Earn</div>
                        </div>
                    </a>
                </div>
                <div class="box-cont box-cont-last">
                    <a href="<?php echo $base_url; ?>expensesDetails.php">
                        <div class="info-box-2 bg-color hover-expand-effect">
                            <div class="content">
                                <div class="icon">
                                    <i class="material-icons"><?php echo $_SESSION['currency']; ?></i>
                                </div>
                                <div class="number count-to" data-from="0" data-to="<?php echo $costData['total']; ?>" data-speed="1000" data-fresh-interval="20">1</div>
                            </div>
                            <div class="text">Total Expense</div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="cahartContainer">
                <canvas id="barCanvas"></canvas>
            </div>

   </div>
</section>
<script>
var chartData = JSON.parse('<?php echo $chart; ?>');
</script>
<?php include('pages/footer.php') ?>
