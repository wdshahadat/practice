<?php
require_once('pages/header.php');

/*
*
*  Earning details of the current month
*
*/


$currentMonth = date('m');
$baseUrl = $base_url;
$id = $_SESSION['userinfo']['id'];
$currencySymbol = $_SESSION['currency'];
$earnData = $c->db->get('', "SELECT * FROM fms_admin JOIN fms_bank ON fms_admin.id = fms_bank.id WHERE userRoll = 'Partner' AND ba_date LIKE '$currentMonth%'");
$expenseData = $c->db->get('', "SELECT * FROM fms_admin JOIN fms_cost ON fms_admin.id = fms_cost.id WHERE userRoll = 'Partner' AND cst_a_date LIKE '$currentMonth%'");
$allPartners = $c->db->get('fms_admin', ['userRoll' => 'Partner']);

// _e = means earn
// _c = means cost/expense
$photo;
$fullName;
$percentage;
$users = array();
$user_c = array();
$userData_e = array();
$userData_c = array();
$totalEarn = array_sum($c->arrayKeyFilter($earnData, 'amount'));
$totalExpence = array_sum($c->arrayKeyFilter($expenseData, 'cst_amount'));

if (isset($earnData) || isset($expenseData)) {
  if (isset($earnData)) {
    foreach ($earnData as $earnRow) {
      $users[$earnRow->id] = $earnRow->id;
    }

    foreach ($users as $id) {
      $userData_e[] = array_filter($earnData, function ($earnData) use ($id) {
        return ($earnData->id === $id);
      });
    }

    if (count($allPartners) > count($users)) {
      $userfilter_e = array();
      foreach ($allPartners as $row_e) {
        $userfilter_e[$row_e->id] = $row_e;
      }
      foreach (array_diff_key($userfilter_e, $users) as $arrayData_e) {
        $userData_e[] = array($arrayData_e);
      }
    }
  }

    // start expense
  if (isset($expenseData)) {
    foreach ($expenseData as $row) {
      $user_c[$row->id] = $row->id;
    }

    foreach ($user_c as $id) {
      $userData_c[] = array_filter($expenseData, function ($expenseData) use ($id) {
        return ($expenseData->id === $id);
      });
    }

    if (count($allPartners) > count($user_c)) {
      $userfilter = array();
      foreach ($allPartners as $expenseRow) {
        $userfilter[$expenseRow->id] = $expenseRow;
      }
      foreach (array_diff_key($userfilter, $user_c) as $arrayData) {
        $userData_c[] = array($arrayData);
      }
    }
  }
    // End expense

  $costAmount = array();
  if (count($userData_c) > 0) {
    foreach ($userData_c as $expenseArray) {
      $cstAmount = array();
      foreach ($expenseArray as $cost) {
        $cstAmount[] = isset($cost->cst_amount) ? $cost->cst_amount : 0;
      }
      $costAmount[] = array_sum($cstAmount);
    }
  }

  $output = '';
  $expenseCount = -1;
  if (!empty($userData_e)) {
    foreach ($userData_e as $array) {
      $amount_e = array();
      foreach ($array as $obj) {
        $photo = $obj->photo;
        $fullName = $obj->fullName;
        $amount_e[] = isset($obj->amount) ? $obj->amount:0;
        $percentage = $obj->percentage;
      }


        $yourEarn = array_sum($amount_e);
        $persentageEarn = ($totalEarn / 100) * intval($percentage);
        $position = $persentageEarn - $yourEarn;

        $expenseCount++;
        $yourExpense = !empty($costAmount) ? intval($costAmount[$expenseCount]) : 0;

        $persentageShare = (array_sum($costAmount) / 100) * intval($percentage);
        $balanceDue = $persentageShare - $yourExpense;

        $receivable = $persentageEarn + $balanceDue;
        $warning = $receivable < 0 ? ' style="color:#ff0c00">return back ' : '>';
        $warning_d = $receivable < 0 ? ' style="color:#ff0c00">' : '>';
      $output .= '<tr>
          <td class="photo-c"><div class="userImg"><img src="'.$base_url. $photo . '" alt=""></div><p>' . $fullName . '</p></td>
          <td>' . $percentage . ' <b>%</b></td>
          <td'.$warning . $receivable . ' '.$currencySymbol.'</td>
        </tr>';
    }
  }
}
?>
<section class="content">
   <div class="card">
         <div class="container-fluid">
         <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
               <div class="col-lg-3 col-md-5 col-sm-6">
                  <div class="total-cost earn">
                     <div class="total-cc">
                        <p id="totalEarn">Total earn : <span><?php echo $totalEarn.' '.$currencySymbol; ?></span></p>
                        <p id="balance">Balance :  <span><?php echo ($totalEarn - $totalExpence).' '.$currencySymbol;; ?></span></p>
                        <span id="countDetails">( <?php echo $totalEarn. ' - ' . $totalExpence . ' = ' . ($totalEarn - $totalExpence); ?> )</span>
                     </div>
                  </div>
               </div>
               <div class="col-lg-9 col-md-7 col-sm-6 option-c">
                    <form action="" method="post">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 to-hide">
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
                        </div>
                    </form>
                </div>
            </div>
            <div class="details-user-fin">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width=" 40%">Users</th>
                            <th width=" 20%">Percentage</th>
                            <th width=" 40%">Amount (receivable)</th>
                        </tr>
                    </thead>
                    <tbody id="earning_details_tbody">
                    <?php
              if (isset($earnData) && count($earnData) > 0 && isset($_SESSION['userinfo']) && !empty($output)) {
                echo $output;
              }
          ?>
                    </tbody>
                </table>
            </div>
         </div>

   </div>
</section>

<?php include('pages/footer.php'); ?>
