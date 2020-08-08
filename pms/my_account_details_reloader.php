<?php
/*
*
*  user financial account details reload page
*
*/

require_once('functions/CheckerFn.php');
$c = new CheckerFn;
$c->loginCheck();

if(isset($_POST['month']) && !empty($_POST['month'])) {

    $dateObj = DateTime::createFromFormat('!m', $_POST['month']);
    $month = $dateObj->format('m');
    $id = $_SESSION['userinfo']['id'];
    $symbol = $_SESSION['currency'];

    $partner = $c->db->get_row('fms_admin', ['userRoll' => 'Partner', 'id' => $_SESSION['userinfo']['id']]);
    extract($partner);

    $earning = $c->db->get('', "SELECT amount, id FROM fms_bank WHERE ba_date LIKE '$month%'");
    $expense = $c->db->get('', "SELECT cst_amount, id FROM fms_cost WHERE cst_a_date LIKE '$month%'");
    $partner = $c->db->get('fms_admin', ['userRoll' => 'Partner', 'id' => $_SESSION['userinfo']['id']]);

    $totalEarn = isset($earning) ? array_sum($c->arrayKeyFilter($earning, 'amount')):0;
    $totalExpense = isset($expense) ? array_sum($c->arrayKeyFilter($expense, 'cst_amount')):0;

    $expense_by_you = isset($expense) ? array_filter($expense, function ($expense) use ($id) { return ($expense->id === $id); }):[];

    $countingDetails = '';

    $persentageEarn = ($totalEarn / 100) * intval($percentage);

    $yourExpense = !empty($expense_by_you) ? array_sum($c->arrayKeyFilter($expense_by_you, 'cst_amount')):0;

    $persentageShare = ($totalExpense / 100) * intval($percentage);
    $revenue = ($yourExpense + $persentageEarn) - $persentageShare;
    $balance = number_format($totalEarn - $totalExpense);
    $totalEarn = number_format($totalEarn);
    $totalExpense = number_format($totalExpense);
    $yourExpense = number_format($yourExpense);
    $persentageEarn = number_format($persentageEarn);
    $persentageShare = number_format($persentageShare);
    $countDetails = $totalEarn. ' <b>-</b> ' . $totalExpense . ' <b>=</b> '.$balance;


    $b_color = $revenue >= 0 ? 'success':'warning';
    $myFinance = $revenue >= 0 ? 'Receivable Amount = ':'Payable Amount = ';
    $myFinance .= number_format($revenue);

    $account_head = '<div class="account-h '.$b_color.'">
            <h4>'.$myFinance.' '.$symbol.'</h4>
        </div>';

    echo json_encode(['account_head' => $account_head, 'totalEarn' => $totalEarn, 'totalExpense' => $totalExpense, 'balance' => $balance, 'countDetails' => $countDetails, 'yourExpense' => $yourExpense, 'persentageShare' => $persentageShare, 'revenue' => number_format($revenue), 'persentageEarn' => $persentageEarn, 'symbol' => $symbol]);

}
