<?php
/*
*
*  Expenses details reload by the month
*
*/

require_once('functions/CheckerFn.php');
   $c = new CheckerFn;
   $c->loginCheck();


   // month wise expense details

   if(isset($_POST['month'])) {
      $dateObj = DateTime::createFromFormat('!m', $_POST['month']);
      $month = $dateObj->format('m');
      $id = $_SESSION['userinfo']['id'];
      $financeCurrency = $_SESSION['currency'];

      // to get all partners
      $allPartners = $c->db->get('fms_admin', ['userRoll' => 'Partner']);

      // month wise expense data
      $montlyExpense = $c->db->get('', "SELECT * FROM fms_admin JOIN fms_cost ON fms_admin.id = fms_cost.id WHERE userRoll = 'Partner' AND cst_a_date like '$month%'");
      $montlyTotalExpense = array_sum($c->arrayKeyFilter($montlyExpense, 'cst_amount')); // total expense
      $partnerPhoto;
      $expenseData = array();
      $expenses_partners = array();
      $fullName; $percentage;

      // check expense data is exists
      if(isset($montlyExpense) && !empty($montlyExpense)) {
         foreach ($montlyExpense as $row) {
            $expenses_partners[$row->id] = $row->id;
         }

         // collect user from expense data
         foreach ($expenses_partners as $id) {
            $expenseData[] = array_filter($montlyExpense, function ($montlyExpense) use ($id) {
               return ($montlyExpense->id === $id);
            });
         }

         // check all partners and expense partners
         if(count($allPartners) > count($expenses_partners)) {
            $userfilter = array();
            foreach ($allPartners as $ch_row) {
               $userfilter[$ch_row->id] = $ch_row;
            }
            foreach (array_diff_key($userfilter, $expenses_partners) as $arrayData) {
               $expenseData[] = array($arrayData);
            }
         }

         $output = '';

         // to output expense result informetion
         foreach ($expenseData as $array) {
            $amount = array();
            foreach ($array as $obj) {
               $partnerPhoto = $obj->photo;
               $fullName = $obj->fullName;
               $amount[] = isset($obj->cst_amount) ? $obj->cst_amount : 0;
               $percentage = $obj->percentage;
            }

            $positionInfo = '';
            $yourExpense = array_sum($amount);
            $persentageExpense = ($montlyTotalExpense / 100) * intval($percentage);
            $position = $persentageExpense - $yourExpense;
            if($position > 0) {
               $positionInfo = '<span class="label label-warning label-fc">Payable Amount '.$position.' '.$financeCurrency.'</span>';
            }elseif($position === 0) {
               $positionInfo = '<span class="label label-info label-fc">Payable Amount '.$position.' '.$financeCurrency.'</span>';
            }else {
               $positionInfo = '<span class="label label-success label-fc">Receivable Amount '.str_replace("-", "", $position).' '.$financeCurrency.'</span>';
            }

            $output .= '<tr>
               <td class="photo-c"><div class="userImg"><img src="'.$base_url.$partnerPhoto.'" alt=""></div><p>'.$fullName.'</p></td>
               <td>'.$percentage.' <b>%</b></td>
               <td>'.$yourExpense.' '.$financeCurrency.'</td>
               <td>'.$persentageExpense.' '.$financeCurrency.'</td>
               <td>'.$positionInfo.'</td>
            </tr>';
         }
         // to get expense amount informetion in javascript script and print DOM
         echo $output.'<tr style="display:none">
            <td><input type="hidden" name="total" value="Total expense : '.$montlyTotalExpense.' '.$financeCurrency.'"></td>
         </tr>';
      }
   }
