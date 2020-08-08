<?php
require_once('pages/header.php');
unset($_SESSION['registerPartner']);
unset($_SESSION['partnerInfEdit']);
unset($_SESSION['bank']);
$baseUrl = $base_url;
$currentMonth = date('m');
$id = $_SESSION['userinfo']['id'];
$currency = $_SESSION['currency'];

/*
*
*  Expenses details of the current month
*
*/

   //  all expense data of partners in the current month
   $montlyExpense = $c->db->get('', "SELECT * FROM fms_admin JOIN fms_cost ON fms_admin.id = fms_cost.id WHERE userRoll = 'Partner' AND cst_a_date LIKE '$currentMonth%'");

   $allPartners = $c->db->get('fms_admin', ['userRoll' => 'Partner']);
   $montlyTotalExpense = array_sum($c->arrayKeyFilter($montlyExpense, 'cst_amount'));

   $partnerPhoto;
   $expenseData = [];
   $expenses_partners = [];
   $fullName; $percentage;

   if(isset($montlyExpense)) {
      foreach ($montlyExpense as $row) {
         $expenses_partners[$row->id] = $row->id;
      }

      foreach ($expenses_partners as $id) {
         $expenseData[] = array_filter($montlyExpense, function ($montlyExpense) use ($id) {
            return ($montlyExpense->id === $id);
         });
      }

      // check all partner and expense partner
      if(count($allPartners) > count($expenses_partners)) {
         $partnersFilter = array();
         foreach ($allPartners as $partnerRow) {
            $partnersFilter[$partnerRow->id] = $partnerRow;
         }
         foreach (array_diff_key($partnersFilter, $expenses_partners) as $arrayData) {
            $expenseData[] = array($arrayData);
         }
      }

      $output = '';
      foreach ($expenseData as $array) {
         $amount = array();
         foreach ($array as $obj) {
            $partnerPhoto = $obj->photo;
            $fullName = $obj->fullName;
            $percentage = $obj->percentage;
            $amount[] = isset($obj->cst_amount) ? $obj->cst_amount: 0;
         }


         $positionInfo = '';
         $yourExpense = array_sum($amount);
         $persentageExpense = ($montlyTotalExpense / 100) * intval($percentage);
         $position = $persentageExpense - array_sum($amount);
         if($position > 0) {
            $positionInfo = '<span class="label label-warning label-fc">Payable Amount '.$position.' '.$currency.'</span>';
         }elseif($position === 0) {
            $positionInfo = '<span class="label label-info label-fc">Paid '.$position.' '.$currency.'</span>';
         }else {
            $positionInfo = '<span class="label label-success label-fc">Receivable  Amount '.str_replace("-", "", $position).' '.$currency.'</span>';
         }

         $output .= '<tr>
            <td class="photo-c"><div class="userImg"><img src="'.Db::$url.$partnerPhoto.'" alt=""></div><p>'.$fullName.'</p></td>
            <td>'.$percentage.' <b>%</b></td>
            <td>'.$yourExpense.' '.$currency.'</td>
            <td>'.$persentageExpense.' '.$currency.'</td>
            <td>'.$positionInfo.'</td>
         </tr>';
      }
   }

 ?>
<section class="content">
   <div class="card">
      <?php if(isset($_SESSION['info_message'])) { echo $_SESSION['info_message']; unset($_SESSION['info_message']);} ?>
         <div class="container-fluid">
         <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
               <div class="col-lg-3 col-md-3 col-sm-3">
                  <div class="total-cost">
                     <div class="total-cc">
                        <h2>Total expense : <span><?php echo $montlyTotalExpense.' '.$currency; ?></span></h2>
                     </div>
                  </div>
               </div>
               <div class="col-lg-9 col-md-9 col-sm-9 option-c">
                    <form action="" method="post">
                        <div class="col-lg-4 col-md-4 col-sm-4"></div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 to-hide">
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
            <div class="details-user-fin">
               <table class="table table-bordered">
                 <thead>
                     <tr>
                        <th width=" 30%">Partners</th>
                        <th width=" 10%">Percentage</th>
                        <th width=" 15%">User expense</th>
                        <th width=" 20%">Share amount</th>
                        <th width=" 25%">Position</th>
                     </tr>
                  </thead>
                  <tbody id="expenses_details_tbody">
                     <?php
                        if(isset($montlyExpense) && count($montlyExpense) > 0 && isset($_SESSION['userinfo']) && !empty($output)) {
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
