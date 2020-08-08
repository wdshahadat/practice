<?php
require_once('functions/CheckerFn.php');
$c = new CheckerFn;
$c->loginCheck();
$db = $c->db;

/*
*
*  Earning details reload by month
*
*/
if (isset($_POST['month'])) {
	$currencySymbol = $_SESSION['currency'];
	$dateObj = DateTime::createFromFormat('!m', $_POST['month']);
	$month = $dateObj->format('m');

	// month wise earn
	$earnData = $db->get('', "SELECT * FROM fms_admin JOIN fms_bank ON fms_admin.id = fms_bank.id WHERE userRoll = 'Partner' AND ba_date like '$month%'");

	// month wise expense
	$expenseData = $db->get('', "SELECT * FROM fms_admin JOIN fms_cost ON fms_admin.id = fms_cost.id WHERE userRoll = 'Partner' AND cst_a_date like '$month%'");
	$allPartners = $db->get('fms_admin', ['userRoll' => 'Partner']);
	$allPartners = $allPartners;
	$photo;
	$fullName;
	$percentage;
	$users = array();
	$user_c = array();
	$userData_e = array();
	$userData_c = array();
	$totalEarn = array_sum($c->arrayKeyFilter($earnData, 'amount'));
	$totalExpence = array_sum($c->arrayKeyFilter($expenseData, 'cst_amount'));

	// check earn or expense data exists
	if (isset($earnData) || isset($expenseData)) {

		// earn data
		if (isset($earnData)) {
			foreach ($earnData as $e_row) {
				$users[$e_row->id] = $e_row->id; // get user from earn data
			}

			// process par user earn data
			foreach ($users as $id) {
				$userData_e[] = array_filter($earnData, function ($earnData) use ($id) {
					return ($earnData->id === $id);
				});
			}


			// check all users > earn user
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

			// process par user expense data
			foreach ($user_c as $id) {
				$userData_c[] = array_filter($expenseData, function ($expenseData) use ($id) {
					return ($expenseData->id === $id);
				});
			}

			// check all users > expense user
			if (count($allPartners) > count($user_c)) {
				$userfilter = array();
				foreach ($allPartners as $row_c) {
					$userfilter[$row_c->id] = $row_c;
				}
				foreach (array_diff_key($userfilter, $user_c) as $arrayData) {
					$userData_c[] = array($arrayData);
				}
			}
		} // end expense


		// to create par partner expense amount data
		$expenseAmount = array();
		foreach ($userData_c as $array_c) {
			$cstAmount = array();
			foreach ($array_c as $cst) {
				$cstAmount[] = isset($cst->cst_amount) ? $cst->cst_amount : 0;
			}
			$expenseAmount[] = array_sum($cstAmount);
		}

		$output = '';
		$count_c = -1;
		if (!empty($userData_e)) {

			//  user informetion output
			foreach ($userData_e as $array) {
				$amount_e = array();
				foreach ($array as $obj) {
					$photo = $obj->photo;
					$fullName = $obj->fullName;
					$amount_e[] = $obj->amount;
					$percentage = $obj->percentage;
				}

				$yourEarn = array_sum($amount_e); // par partner earn
				$persentageEarn = ($totalEarn / 100) * intval($percentage);
				$position = $persentageEarn - $yourEarn; // partner position

				$count_c++;
				$yourCost = !empty($expenseAmount) ? intval($expenseAmount[$count_c]) : 0;
				$persentageShare = ($totalExpence / 100) * intval($percentage);
				$balanceDue = $persentageShare - $yourCost;

				$receivable = $persentageEarn + $balanceDue;  // par partner Amount (receivable)
	            $warning = $receivable < 0 ? ' style="color:#ff0c00">return back ' : '>';
	            $warning_d = $receivable < 0 ? ' style="color:#ff0c00">' : '>';
				$output .= '<tr>
					<td class="photo-c"><div class="userImg"><img src="'.$base_url.'uploadFiles/userPhoto/' . $photo . '" alt=""></div><p>' . $fullName . '</p></td>
					<td>' . $percentage . ' <b>%</b></td>
					<td'.$warning . $receivable . ' '.$currencySymbol.'</td>
				</tr>';
			}
		}

		echo $output . '<tr style="display:none">
			<td><input type="hidden" name="countData" value="' . $totalEarn . ',' . array_sum($expenseAmount) .'"></td>
				</tr>';
	}
}
