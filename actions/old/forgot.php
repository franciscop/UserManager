<?
require "../start.php";

$STH = $UMDB->prepare("SELECT * FROM users WHERE email = ?");
$STH->execute(array($_POST['email']));
$Res = $STH->fetch();

// TODO. THIS IS TEMPORARY
// Reset the password and send the new one
if ($Res['email']) {
  include "../password_compat/password_compat.php";
  $NewPass = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 20);
  $NewHash = password_hash($NewPass, PASSWORD_BCRYPT, array("cost" => $UMConfig->cost));
  
  $STH = $UMDB->prepare("INSERT INTO user_auth (user_id, hash) VALUES (?, ?) ON DUPLICATE KEY UPDATE hash = ?");
  $STH->execute(array($Res['id'], $NewHash, $NewHash));
  
  $Message = "Your new password is: <strong>" . $NewPass . "</strong><br>" . 
    "Please, change it as you log in.";
  
  $headers = "From: reset@spew.info" . "\r\n" .
    "Reply-To: publicfrancisco@hotmail.com" . "\r\n" .
    "X-Mailer: PHP/" . phpversion() .
    "MIME-Version: 1.0\r\n" .
    "Content-Type: text/html; charset=UTF-8\r\n";
  
  mail ($_POST['email'] , "Forgotten password", $Message, $headers);
  
  echo json_encode(array('register' => 1));
  }
else {
  echo json_encode(array('register' => 0));
  }


