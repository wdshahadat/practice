<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) { session_start();}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('phpMailer/src/Exception.php');
require_once('phpMailer/src/PHPMailer.php');
require_once('phpMailer/src/SMTP.php');
require_once('phpMailer/src/POP3.php');


// to send mail
class SendMail {
    private $mail;
    public function getMailInfo() {
        $db = Db::connect();
        $result = $db->query("SELECT * FROM company_settings");
        if (isset($result)) {
            $resultArray = [];
            while ($r = $result->fetch_assoc()) {
                $resultArray[] = $r;
            }
            return $resultArray[0];
        }
    }
    public function __construct() {
        $this->mail = new PHPMailer(true);
    }

    public function send_mail(array $emailData = array()) {
        $getEmail = $this->getMailInfo();
        $toMail = $emailData['toMail'];
        $fullName = $emailData['fullName'];
        if(isset($emailData['accountInf']) && is_array($emailData['accountInf'])) {
            $info = $emailData['accountInf'];
            $subject_ms = 'Your '.$getEmail['companyName'].' account information';
            $p_inf = isset($emailData['link']) ? 'Password' :'New password';
            $userLink = isset($emailData['link']) ? '<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="border-collapse:collapse">
               <tbody>
                  <tr>
                     <td>
                        <p><b style="color:#FF9800;line-height:18px;margin-bottom:12px;font-size:18px;margin:0 0 8px">To reset your password please ( <a href="'.$emailData["link"].'" style="color:#439fe0;font-weight:bold;text-decoration:none;word-break:break-word" target="_blank">Click Here...</a> )</p>
                     </td>
                  </tr>
               </tbody>
            </table>': '';
            $htmlData = '<div style="max-width:600px;margin:0 auto;font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif!important;">
                <h2 style="color:#2ab27b;line-height:50px;margin:0 0 12px">Hi, '.$fullName.'</h2>
                <div>
                    <h4 style="margin:0 0 10px 0">Your '.$getEmail['companyName'].' account information</h4>
                    <div style="margin:25px 0 0 0">
                        <p style="font-size:17px;line-height:10px;margin:0 0 16px"><strong>User name : </strong> '.$info[0].'</p>
                        <p style="font-size:17px;line-height:10px;margin:0 0 16px"><strong>'.$p_inf.' : </strong> '.$info[1].'</p>
                    </div>
                </div>
                '.$userLink.'
            </div>';
        }elseif(isset($emailData['link'])) {
            $setLink = $emailData['link'];
            $subject_ms = 'User name and password generate';
            $htmlData = '<div style="max-width:600px;margin:0 auto;font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif!important;">
                <h2 style="color:#2ab27b;line-height:30px;margin:0 0 12px">Hi, '.$fullName.'</h2>
                <p style="font-size:17px;line-height:24px;margin:0 0 16px">Set your username and password to use <strong>Parntership Management System</strong> (<a href="'.$setLink.'" style="color:#439fe0;font-weight:bold;text-decoration:none;word-break:break-word" target="_blank">Click Here...</a>).</p>
                <hr style="border:none;border-bottom:1px solid #ececec;margin:1.5rem 0;width:100%">
            </div>';
        }

        // $serverInfo = ['80', '8080', '::1', '127.0.0.1'];
        // if(in_array($_SERVER['SERVER_PORT'], $serverInfo) || in_array($_SERVER['REMOTE_ADDR'], $serverInfo)) {
        //     $this->mail->isSMTP();
        // }

        $this->mail->Host = $getEmail['smtpHost'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $getEmail['contactEmail'];
        $this->mail->Password = $getEmail['emailPassword'];
        $this->mail->SMTPSecure = $getEmail['smtpAuth'] === 'none' ? 'tls': $getEmail['smtpAuth'];
        $this->mail->Port = $getEmail['smtpAuth'] === 'none' ? '587': $getEmail['smtpPort'];

        //Recipients
        $this->mail->setFrom($toMail, $getEmail['companyName']);
        $this->mail->addAddress($toMail);
        $this->mail->addReplyTo($toMail);

        //Content
        $this->mail->isHTML(true);
        $this->mail->Subject = $subject_ms;
        $this->mail->Body    = $htmlData;
        $this->mail->AltBody = strip_tags($htmlData);
        return $this->mail->send();
    }
}
