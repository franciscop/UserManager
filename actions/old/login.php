<?php
// CREATE DEVICE
// Generate current device's token
$Token = substr(bin2hex(mcrypt_create_iv(200)), 0, 60);

$STH = $UMDB->prepare("SELECT * FROM users WHERE email = ?");
$STH->execute(array($_SESSION['email'])); // Identity is verified in include.php
$Res = $STH->fetch();

// Include this device's token
$STH = $UMDB->prepare("INSERT INTO devices (user_id, token) VALUES (?, ?)");
$STH->execute(array($Res['id'], $Token));

// To keep users logged in between sessions
setcookie("email", $_SESSION['email'], time() + 3600 * 24 * $UMConfig->howlong);
setcookie("token", $Token,             time() + 3600 * 24 * $UMConfig->howlong);
