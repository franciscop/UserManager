<?php
// User ID
$Id;

// Generate current device's token
$token = substr(bin2hex(mcrypt_create_iv(200)), 0, 60);

// Hash the token so if the db leaks, no one can impersonate other person
$token_hash = password_hash($token, PASSWORD_BCRYPT);

// Include this device's token
$STH = $userDB->prepare("INSERT INTO devices (user_id, token, active) VALUES (?, ?, 1)");
$Res = $STH->execute(array($Id, $token_hash));

if (!$Res)
 throw new Exception("Couldn't store the device in the database");

// To keep users logged in between sessions
$Cookie->email = $email;
$Cookie->token = $token;
