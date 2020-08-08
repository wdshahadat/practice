<?php
if (session_status() == PHP_SESSION_NONE) {session_start(); }
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

/*
*
*  user all action manage in this page
* common email: shahadat01951251154@gmail.com
* common email password: shahadatkhan54
*/


//  database connect file check
require_once('functions/CheckerFn.php');
require_once('SendMail.php');



class Action
{
	public $db;
	public $checker;
	public $theDay;
	public function __construct()
	{
		$this->db = new SqlQuery;
		$this->theDay = date('m-d-Y');
		$this->checker = new CheckerFn;
	}


	// company settings manage add, update
	public function manageSettings()
	{
		$preLogo = isset($_POST['preLogo']) ? $this->db->validation($_POST['preLogo']):'';
		if (empty($_FILES['logo']['name'])) {
			$logo = $preLogo;
		} else {
			$logo_array = $_FILES['logo'];
			$name = explode('.', $logo_array['name']);
			$logo = 'img/'.md5(rand() . time()) . '.' . end($name);
		}

		$data = $this->db->validation($_POST);
		$data['companyLogo'] = $logo;
		unset($data['sec_a']);
		extract($data);

		// check smtp connection
		$smtpCheck = fsockopen($smtpHost, $smtpPort, $errno, $errstr, 5);
		if (is_bool($smtpCheck) && $smtpCheck === false) {
			$_SESSION['smtpInvalid'] = $data;
			return $this->checker->redirect('settings');
		}

		if (isset($_SESSION['atFirstSettings'])) {
			$secureAuth = md5(sha1($companyName.$smtpHost.$logo));
			$data['secureAuth'] = $secureAuth;
			$insertSuccess = $this->db->insertAction('company_settings', $data);
			if (isset($insertSuccess)) {
				unset($_SESSION['atFirstSettings']);
				move_uploaded_file($logo_array['tmp_name'], $logo);
				return $this->checker->redirect('registerPartner');
			}
		} elseif (isset($settingsEdit)) {
			$id = $settingsEdit;
			unset($data['preLogo']);
			unset($data['settingsEdit']);
			$success = $this->db->updateAction('company_settings', $data, ['id' => $id]);

			if (isset($success)) {
				if ($logo !== $preLogo) {
					$_SESSION['logo'] = $logo;
					unlink(file_exists($preLogo) ? $preLogo:false);
					move_uploaded_file($logo_array['tmp_name'], $logo);
				}
				$upData = $this->db->get_row('company_settings', ['id' => $id]);
				$_SESSION['settingsUpdated'] = 1;
				$_SESSION['logo'] = $upData['companyLogo'];
				$_SESSION['company'] = $upData['companyName'];
				$_SESSION['currency'] = currency_symbol($upData['userCurrency']);
				return $this->checker->redirect('manageSettings');
			}

		}
	}

	// user account recovery method if user forgot user account informetion
	public function getAccount($getBy)
	{

		// user account information get by email
		$getAccount = false;
		if ($getBy === '010') {
			$email = $this->checker->c_valid($_POST['email']);
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$emailExist = $this->db->get_row("fms_admin", ['email' => $email]);
				if (isset($emailExist) && $emailExist) {
					$getAccount = $emailExist;
				} else {
					$_SESSION['doesNotExist_e'] = $email;
					return $this->checker->redirect('forgotPassword');
				}
			} else {
				$_SESSION['invalidEmail'] = $email;
				return $this->checker->redirect('forgotPassword');
			}
		}


		// to get user account information by user full name & birthday
		if ($getBy === '101') {
			$fullName = $this->checker->c_valid($_POST['fn']) . ' ' . $this->checker->c_valid($_POST['ln']);
			$birthday = $this->checker->c_valid($_POST['birthday']);
			$userExist = $this->db->get_row('fms_admin', ['fullName' => $fullName, 'birthday' => $birthday]);
			if (isset($userExist)) {
				$userExist = $getAccount;
			} else {
				$_SESSION['actionfaild'] = 1;
				return $this->checker->redirect('forgotPassword');
			}
		}


		// check user is exists
		if (isset($getAccount)) {
			$email = $getAccount['email'];
			$userName = $getAccount['userName'];
			$fullName = $getAccount['fullName'];
			$sec = json_decode($getAccount['userInfo_sc']); // secure informetion container
			$password = md5(sha1($userName . rand()));
			$sec_one = md5(sha1($password . rand()));
			$sec_two = md5(sha1($sec_one . rand()));
			$setLink = Db::$url . 'resetPassword.php?' . $userName . '=' . $password . '&' . $sec_one . '=' . $sec_two;
			$accountInf = [$sec->userName_sc, $sec->password_sc];


			// this is mail data to send a mail
			$emailData = ['toMail' => $email, 'fullName' => $fullName, 'link' => $setLink, 'accountInf' => $accountInf];


			// mail sender class
			$emailClass = new SendMail;
			$sendResult = $emailClass->send_mail($emailData);

			// check, mail send is success
			if (is_bool($sendResult) && $sendResult === true) {
				$sec = $this->checker->secureInfoProcess($sec);
				$id = $getAccount['id'];
				array_push($sec, [$sec_one, $sec_two]);
				$upd = ['userInfo_sc' => json_encode($sec)];
				$edit = $this->db->updateAction('fms_admin', $upd, ['id' => $id]);
				if (isset($edit)) {
					$_SESSION['emailSend'] = $email;
					return $this->checker->redirect('login');
				} else {
					$_SESSION['actionfaild'] = 3;
					return $this->checker->redirect('forgotPassword');
				}
			} else {
				$_SESSION['actionfaild'] = 2;
				return $this->checker->redirect('forgotPassword');
			}
		}
	}


	// user account reset
	public function resetPassword($updateUser = null)
	{
		if (isset($_SESSION['userInformetion'])) {
			$info = $_SESSION['userInformetion'];
			$ridirectLink = $_SESSION['ridirect_l'];
			$protocol = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
			$ridirectLink = $protocol . '://' . $_SERVER['HTTP_HOST'] . $ridirectLink;

			if (empty($_POST['passworda']) || empty($_POST['passwordr'])) {
				$_SESSION['emptyField'] = 1;
				return header('Location: ' . $ridirectLink);
			} else {
				if ($_POST['passworda'] === $_POST['passwordr']) {
					$pas = $this->checker->c_valid($_POST['passworda']);
					$secure = json_decode($info['userInfo_sc']);
					$secure_od = $secure;
					$secure = $secure->oldPassword_sc;
					if (empty($secure)) {
						$password = $pas;
					} else {
						if (in_array($pas, $secure) || $pas === $secure_od->password_sc) {
							$_SESSION['old_p'] = 1;
							return header('Location: ' . $ridirectLink);
						} else {
							$password = $pas;
						}
					}
					
					
					if (isset($password)) {
						$email = !isset($updateUser) ? $info['email'] : $this->checker->c_valid($_POST['email']);
						$newPassword = $password;
						$fullName = !isset($updateUser) ? $info['fullName'] : $this->checker->c_valid($_POST['fn']) . ' ' . $this->checker->c_valid($_POST['ln']);
						$userName = !isset($updateUser) ? $secure_od->userName_sc : $updateUser;
						$accountInf = [$userName, $newPassword];
						$emailData = ['toMail' => $email, 'fullName' => $fullName, 'accountInf' => $accountInf];

						// mail sender class
						$emailClass = new SendMail;
						$sendResult = $emailClass->send_mail($emailData);

						if (is_bool($sendResult) && $sendResult === true) {
							$password = password_hash($password, PASSWORD_BCRYPT, ["cost" => 12]);
							$password_sc = $newPassword;
							$oldPassword_sc = $secure_od->oldPassword_sc;
							array_push($oldPassword_sc, $secure_od->password_sc);
							$new_userInfo_sc = ['userName_sc' => $userName, 'password_sc' => $password_sc, 'oldPassword_sc' => $oldPassword_sc];
							$upd = ['password' => $password, 'userInfo_sc' => json_encode($new_userInfo_sc)];

							unset($_SESSION['ridirect_l']);
							unset($_SESSION['userInformetion']);
							if (isset($updateUser)) {
								return $upd;
							} else {
								$passwordUpdate = $this->db->updateAction('fms_admin', $upd, ['id' => $info['id']]);
								if (isset($passwordUpdate)) {
									$_SESSION['resetSuccess'] = $email;
									return $this->checker->redirect('login');
								}
							}
						}
					}
				} else {
					$_SESSION['doesNot_m'] = 1;
					return header('Location: ' . $ridirectLink);
				}
			}
		}
	}

	// create user account
	public function createUserNamePassword($securAction = null)
	{
		$info = $_SESSION['userinfo'];
		$userName = $this->checker->c_valid($_POST['userName']);
		$password = $this->checker->c_valid($_POST['password']);
		$userInfo_sc = json_encode(array('userName_sc' => $userName, 'password_sc' => $password, 'oldPassword_sc' => []));
		if (ctype_alnum($userName)) {
			$email = $info['email'];
			$fullName = $info['fullName'];
			$accountInf = [$userName, $password];
			$emailData = ['toMail' => $email, 'fullName' => $fullName, 'accountInf' => $accountInf];

			// mail sender class
			$emailClass = new SendMail;
			$sendResult = $emailClass->send_mail($emailData);

			if (is_bool($sendResult) && $sendResult === true) {
				$userName = md5(sha1($userName));
				$password = password_hash($password, PASSWORD_BCRYPT, ["cost" => 12]);
				$upData = [
					'userName' => $userName,
					'password' => $password,
					'userInfo_sc' => $userInfo_sc
				];
				$success = $this->db->updateAction('fms_admin', $upData, ['id' => $info['id']]);
				if (isset($success)) {
					unset($_SESSION['userinfo']);
					$_SESSION['a_create_success'] = $email;
					return $this->checker->redirect('login');
				} else {
					return $this->checker->redirect('setUserNamePassword');
				}
			}
		}
	}

	// user informetion add or eidt manager
	function manageUsers()
	{
		if (isset($_SESSION['ridirect_l'])) {
			$ridirectLink = $_SESSION['ridirect_l'];
			$protocol = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
			$ridirectLink = $protocol . '://' . $_SERVER['HTTP_HOST'] . $ridirectLink;
		}
		if (isset($_POST['fn']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

			$file_name = 'uploadFiles/userPhoto/';
			if (!empty($_FILES['img']['name'])) {
				$photo_array = $_FILES['img'];
				$name = explode('.', $photo_array['name']);
				$file_name .= md5(rand() . time()) . '.' . end($name);
			}

			$p_data = $this->db->validation($_POST);
			$p_data['photo'] = $file_name;
			$p_data['fullName'] = $p_data['fn'].' '.$p_data['ln'];
			$keys = 'id,fullName,email,userName,password,userInfo_sc,percentage,photo,birthday,gender,userRoll,a_date,u_date';

			$postData = [];
			foreach (explode(',', $keys) as $key) {
				if(isset($p_data[$key])) {
					$postData[$key] = $p_data[$key];
				}
			}
			extract($p_data);


			if (isset($register) || isset($atFirstRegisterUser)) {
				$userName = md5(sha1($email . rand()));
				$password = md5(sha1($email . rand() . $fullName));
				$setLink = Db::$url . 'setUserNamePassword.php?' . $userName . '=' . $password;
				$emailData = ['toMail' => $email, 'fullName' => $fullName, 'link' => $setLink];

				$emailClass = new SendMail;

				$sendResult = $emailClass->send_mail($emailData);
				if (is_bool($sendResult) && $sendResult === true) {

					$postData['userName'] = $userName;
					$postData['password'] = $password;
					$postData['a_date'] = $this->theDay;
					$insertSuccess = $this->db->insertAction('fms_admin', $postData);

					if (isset($insertSuccess)) {
						$_SESSION['install'] = 1;
						$_SESSION['registerSuccess'] = $email;
						move_uploaded_file($photo_array['tmp_name'], $file_name);
						$page = isset($register) ? 'userRegistration' : 'login';
						return $this->checker->redirect($page);
					} else {
						$_SESSION['sorry'] = 'action failde';
						return $this->checker->redirect('userRegistration');
					}
				} else {
					$exists_p = $this->db->get("fms_admin");
					if (isset($exists_p)) {
						return $this->checker->redirect('userRegistration');
					}
					return $this->checker->redirect('registerPartner');
				}
			}

			if (isset($editUserInfo)) {
				$userPreInfo = $_SESSION['userInformetion'];
				$imgfile = trim($_FILES['img']['name']);
				$upFile = empty($imgfile) ? $userPreInfo['photo'] : $file_name;
				// $postData['password'] = $p_data['passworda'];
				$postData['photo'] = $upFile;

				$postData['u_date'] = $this->theDay;
				if (isset($_POST['passworda']) && !password_verify($_POST['passworda'], $userPreInfo['password'])) {

					$updatePass = $this->resetPassword($userName);
					if (is_array($updatePass) && !empty($updatePass)) {
						$resetSuccess = 1;
						$postData['userName'] = md5(sha1($userName));
						$postData['password'] = $updatePass['password'];
						$postData['userInfo_sc'] = $updatePass['userInfo_sc'];
					} else {
						return header('Location: ' . $ridirectLink);
					}
				} elseif (isset($_POST['userName'])) {
					$userName = $this->checker->c_valid($_POST['userName']);
					$postData['userName'] = md5(sha1($userName));
				}
				
				$id = $userPreInfo['id'];
				$edit = $this->db->updateAction('fms_admin', $postData, ['id' => $id]);
				if (isset($edit)) {
					$_SESSION['editSuccess'] = 1;
					isset($resetSuccess) ? $_SESSION['resetSuccess'] = $email : false;
					$user = $this->db->get_row('fms_admin', ['id' => $id]);
					unset($user['password']);
					unset($user['userName']);
					unset($user['userInfo_sc']);
					unset($_SESSION['userInformetion']);
					$_SESSION['userinfo']['id'] === $user['id'] ? $_SESSION['userinfo'] = $user : false;
					if ($upFile === $file_name) {
						move_uploaded_file($photo_array['tmp_name'], $upFile);
						$file = $userPreInfo['photo'];
						file_exists($file) ? unlink($file):'';
					}
					$page = $user['userRoll'] === 'Manager' ? 'index':'users';
					return $this->checker->redirect($page);
				} else {
					$_SESSION['editfaild'] = 1;
				}
			}
		} else {
			$_SESSION['invalidEmail'] = $_POST['email'];
			if (isset($_POST['editUserInfo'])) {
				return header('Location: ' . $ridirectLink);
			} else {
				return $this->checker->redirect('userRegistration');
			}
		}
	}

	// add user Earns
	public function cashInsert()
	{
		$source = $this->checker->c_valid($_POST['source']);
		$amount = $this->checker->c_valid($_POST['amount']);
		if (ctype_digit($amount)) {
			$id = $_SESSION['userinfo']['id'];
			$insertData = ['id' => $id, 'earnSource' => $source, 'amount' => $amount, 'currency' => '', 'ba_date' => $this->theDay, 'bu_date' => ''];
			$result = $this->db->insertAction('fms_bank', $insertData);
			if (isset($result)) {
				$_SESSION['success_ms'] = 1;
				return $this->checker->redirect('earnings_manage');
			}
		} else {
			$_SESSION['invalidAmount'] = $amount;
			return $this->checker->redirect('earnings_manage');
		}
	}

	// edit user Earns
	public function cashUpdate()
	{
		$source = $this->checker->c_valid($_POST['source']);
		$bankId = $this->checker->c_valid($_POST['updateId']);
		$amount = $this->checker->c_valid($_POST['amount']);
		if (!empty($bankId)) {
			$up_data = ['earnSource' => $source, 'amount' => $amount, 'bu_date' => $this->theDay];
			$result = $this->db->updateAction('fms_bank', $up_data, ['bankId' => $bankId]);
			if (isset($result)) {
				$_SESSION['info_message'] = 1;
				return $this->checker->redirect('earning_list');
			}
		}
	}

	// Expense maange add or edit
	public function manageExpense()
	{
		$amount = $this->checker->c_valid($_POST['amount']);

		if (isset($_SESSION['costInsert'])) {
			$memoData = [];
			$memoName = $_FILES['memo']['name'];
			$tmpName = $_FILES['memo']['tmp_name'];
			for ($i = 0; $i < count($memoName); $i++) {
				$memoName[$i];
				$tmpName[$i];
				if (!empty($memoName[$i])) {
					$name = explode('.', $memoName[$i]);
					$fileName = md5(rand() . $name[0] . time()) . '.' . end($name);
					$memoData[] = $fileName;
					move_uploaded_file($tmpName[$i], 'uploadFiles/memo/' . $fileName);
				} else {
					$memoData[] = '';
				}
			}
			$id = $_SESSION['userinfo']['id'];
			$costDetails = json_encode(['c_productName' => $_POST['costCn'], 'c_amount' => $_POST['costCa'], 'c_memo' => $memoData]);
			$insertData = ['id' => $id, 'cst_amount' => $amount, 'cst_currency' => '', 'cost_details' => $costDetails, 'cst_a_date' => $this->theDay, 'cst_u_date' => ''];
			$result = $this->db->insertAction('fms_cost', $insertData);
			if (isset($result)) {
				$_SESSION['info_message'] = 'submited';
				return $this->checker->redirect('expenses_manage');
			}
		}

		if (isset($_SESSION['costEdit'])) {
			$memoData = [];
			$upId = $_SESSION['costEdit'];
			$memoName = $_FILES['memo']['name'];
			$tmpName = $_FILES['memo']['tmp_name'];
			$preEdit = $this->db->get_row('fms_cost', ['cst_id' => $upId]);
			$oldMemo = json_decode($preEdit['cost_details']);
			$upc_memo = isset($oldMemo->c_memo) ? $oldMemo->c_memo : [];
			for ($i = 0; $i < count($memoName); $i++) {
				$memoName[$i];
				$tmpName[$i];
				if (!empty($memoName[$i])) {
					$name = explode('.', $memoName[$i]);
					$fileName = md5(rand() . $name[0] . time()) . '.' . end($name);
					$memoData[] = $fileName;
					isset($upc_memo[$i]) && !empty($upc_memo[$i]) ? unlink('uploadFiles/memo/' . $upc_memo[$i]) : false;
					move_uploaded_file($tmpName[$i], 'uploadFiles/memo/' . $fileName);
				} else {
					$memoData[] = isset($upc_memo[$i]) ? $upc_memo[$i] : '';
				}
			}

			$costDetails = json_encode(['c_productName' => $_POST['costCn'], 'c_amount' => $_POST['costCa'], 'c_memo' => $memoData]);
			$upData = ['id' => $preEdit['id'], 'cst_amount' => $amount, 'cost_details' => $costDetails, 'cst_u_date' => $this->theDay];
			$updateSuccess = $this->db->updateAction('fms_cost', $upData, ['cst_id' => $upId]);
			if (isset($updateSuccess)) {
				unset($_SESSION['costEdit']);
				$_SESSION['info_message'] = ' update';
				return $this->checker->redirect('expenses_list');
			}
		}
	}
}

$action = new Action;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	extract($_POST);
	extract($_SESSION);
	if (isset($_SESSION['atFirstSettings'])) {
		return $action->manageSettings();
	} elseif (isset($_SESSION['registerPartner'])) {
		unset($_SESSION['registerPartner']);
		return $action->manageUsers();
	} elseif (isset($_SESSION['userinfo'])) {
		$userInfo = $_SESSION['userinfo'];
		if (isset($_SESSION['createUserNamePassword'])) {
			unset($_SESSION['createUserNamePassword']);
			return $action->createUserNamePassword();
		}
		if (isset($_POST['getBy']) && !empty($_POST['getBy'])) {
			$getBy = $action->checker->c_valid($_POST['getBy']);
			if ($getBy === '101' || $getBy === '010') {
				return $action->getAccount($getBy);
			}
		}
		if (isset($_SESSION['resetAccount404'])) {
			return $action->resetAccount();
		}

		if (isset($_SESSION['csrf']) && $_SESSION['csrf'] === $_POST['cc']) {
			unset($_SESSION['csrf']);
			return $action->resetPassword();
		}

		// sec_a = secure action
		if (isset($sec_a, $sec_a) && $sec_a === $sec_a) {
			if (isset($_POST['settingsEdit'])) {
				return $action->manageSettings();
			}
			if (isset($_POST['updateEmail'])) {
				return $action->updateEmail();
			}
			if (isset($_SESSION['bank'])) {
				unset($_SESSION['bank']);
				if (isset($_SESSION['cashInsert'])) {
					unset($_SESSION['cashInsert']);
					return $action->cashInsert();
				}
				if (isset($_SESSION['cashUpdate'])) {
					unset($_SESSION['cashUpdate']);
					return $action->cashUpdate();
				}
			}
			if (isset($_SESSION['userAction'])) {
				unset($_SESSION['userAction']);
				return $action->manageUsers();
			}
			if (isset($_SESSION['costAction'])) {
				unset($_SESSION['costAction']);
				return $action->manageExpense();
			}
		}
		return $action->checker->redirect('404');
	} else {
		return $action->checker->redirect('login');
	}
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SESSION['userinfo']) && isset($_SESSION['sec_a']) && isset($_GET) && !empty($_GET)) {
	$userInfo = $_SESSION['userinfo'];
	$valueId = array_values($_GET);
	$keysId = array_keys($_GET);

	// to delete Earn row
	if (isset($_SESSION['bank']) && $_SESSION['sec_a'] === $valueId[1]) {
		$id = $action->checker->makeId($valueId[0]);
		$pre_delete = $action->db->get_row('fms_bank', ['bankId' => $id]);
		$delete = $action->db->deleteAction('fms_bank', ['bankId' => $id]);
		if (isset($delete)) {
			$_SESSION['info_message'] = [($pre_delete["bankId"]), ($pre_delete["amount"] . $_SESSION['currency'])];
			return $action->checker->redirect('earning_list');
		}
	}

	// to delete Expenses row
	if (isset($_SESSION['costAction']) && $_SESSION['sec_a'] === $valueId[1]) {
		$id = $action->checker->makeId($valueId[0]);
		$pre_delete = $action->db->get_row('fms_cost', ['cst_id' => $id]);
		$delete = $action->db->deleteAction('fms_cost', ['cst_id' => $id]);

		if (isset($delete)) {
			$memoFiles = json_decode( $pre_delete['cost_details']);
			if(!empty($memoFiles->c_memo)) {
				foreach ($memoFiles->c_memo as $memo_img) {
					unlink('uploadFiles/memo/' . $memo_img);
				}
			}
			$_SESSION['info_message'] = [($pre_delete["cst_id"]), ($pre_delete["cst_amount"] . $_SESSION['currency'])];
			return $action->checker->redirect('expenses_list');
		}
	}

	//  to delete user
	if (isset($_SESSION['user']) && $_SESSION['sec_a'] === $keysId[0]) {

		$id = $action->checker->makeId($valueId[0]); // make user id
		$user = $action->db->get_row('fms_admin', ['id' => $id]);  // user information
		$deleteSucccess = $action->db->deleteAction('fms_admin', ['id' => $id]);
		if (isset($deleteSucccess)) {
			unlink('uploadFiles/userPhoto/' . $user['photo']);
			if ($userInfo['id'] === $id) {
				return $action->checker->redirect('login');
			} else {
				$_SESSION['deleteSucccess'] = $user['fullName'];
				return $action->checker->redirect('users');
			}
		}
	}

	return $action->checker->redirect('404');
} else {
	return $action->checker->redirect('404');
}
