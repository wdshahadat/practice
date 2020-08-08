<?php
if (session_status() === PHP_SESSION_NONE) { session_start();}
require_once(dirname(__FILE__).'/CheckerFn.php');
$c = new CheckerFn();
$post = $c->db->validation($_POST);

// /*
// *
// *  user authentication checker
// *
// */
extract($post);
extract($_SESSION);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($sec_a) && $sec_a === $cc) {
   if (isset($sign)) {
      $userName = md5(sha1($userName));
      $user = $c->db->get_row("fms_admin", ['userName' => $userName]);
      $settinsInfo = $c->db->get_row('company_settings');

      if (isset($user)) {
         if (password_verify($password, $user['password'])) {
            $_SESSION['logo'] = $settinsInfo['companyLogo']; // set company logo
            $_SESSION['company'] = $settinsInfo['companyName']; // company name
            $_SESSION['currency'] = currency_symbol($settinsInfo['userCurrency']); // user currency symble
            unset($user['password']);
            unset($user['userName']);
            unset($user['userInfo_sc']);

            $_SESSION['login'] = true;
            $_SESSION['secure_auth'] = $settinsInfo['secureAuth'];
            $_SESSION['userinfo'] = $user; // set user informetion
            return $c->redirect('index');
         } else {
            $_SESSION['pass_error'] = true; // invalid password
            return $c->redirect('login');
         }
      } else {
         $_SESSION['user_n_error'] = true; // invalid user name
         return $c->redirect('login');
      }
   }
   return $c->redirect('login');
}else {
   $c->redirect('404'); // invalid action
}
