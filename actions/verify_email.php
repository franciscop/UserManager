<?php
session_start();
include "../start.php";

$STH = $userDB->prepare("SELECT * FROM user_data WHERE field = 'verification' AND value = ?");
$STH->execute(array($_GET['code']));
$Res = $STH->fetch();
$Id = $Res['user_id'];

if (empty($Res))
  {
  echo "Verification code not valid";
  exit;
  }

$STH = $userDB->prepare("SELECT * FROM users WHERE id = ?");
$STH->execute(array($Id));
$Res = $STH->fetch();

if ($Res['verified'] == 1)
  {
  echo "User already verified";
  exit;
  }

$STH = $userDB->prepare("UPDATE users SET verified = 1 WHERE id = ?");
$STH->execute(array($Id));

header("Location: " . $_GET['redirect']);
