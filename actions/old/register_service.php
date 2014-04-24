<?php
$Email = $_SESSION['service_email'];

// CREATE USER
// Insert user into the database
$STH = $UMDB->prepare("INSERT INTO users (email, firstname, lastname) VALUES (?, ?, ?, ?)");
$STH->execute(array($Email, $_POST['firstname'], $_POST['lastname']));
var_dump($STH->errorInfo());

// CREATE DEVICE
// Generate current device's token
$Token = substr(bin2hex(mcrypt_create_iv(200)), 0, 60);

// Include this device's token
$STH = $UMDB->prepare("INSERT INTO devices (user_id, token) VALUES (LAST_INSERT_ID(), ?)");
$STH->execute(array($Token));
var_dump($STH->errorInfo());

// SESSION STORAGE
// To avoid a db lookup each page refresh, we store the data in the SESSION
$_SESSION['email'] = $Email;

// To keep users logged in between sessions
setcookie("email", $Email, time() + 3600 * 24 * 30);
setcookie("token", $Token, time() + 3600 * 24 * 30);
