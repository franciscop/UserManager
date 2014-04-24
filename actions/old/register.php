<?php
$Email = $_POST['email'];

// Validate email
if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
  throw new Exception("User Manager says: Invalid email");
  }

// Validate password
if (empty($_POST['password'])) {
  throw new Exception("You should set some password");
  }


// CREATE USER
// Insert user into the database
$STH = $UMDB->prepare("INSERT INTO users (email, firstname, lastname) VALUES (?, ?, ?)");
$STH->execute(array($_POST['email'], $_POST['firstname'], $_POST['lastname']));


// CREATE CREDENTIALS
// Generate the hash
$Hash = password_hash($_POST['password'], PASSWORD_BCRYPT, array("cost" => $UMConfig->cost));

// Store them in the authentification db
$STH = $UMDB->prepare("INSERT INTO user_auth (user_id, hash) VALUES (?, ?)");
$STH->execute(array($UMDB->lastInsertId(), $Hash));


// CREATE DEVICE
// Generate current device's token
$Token = substr(bin2hex(mcrypt_create_iv(200)), 0, 60);

// Include this device's token
$STH = $UMDB->prepare("INSERT INTO devices (user_id, token) VALUES (LAST_INSERT_ID(), ?)");
$STH->execute(array($Token));


// SESSION STORAGE
// To avoid a db lookup each page refresh, we store the data in the SESSION
$_SESSION['email'] = $Email;
  
// To keep users logged in between sessions
setcookie("email", $Email, time() + 3600 * 24 * $UMConfig->howlong);
setcookie("token", $Token, time() + 3600 * 24 * $UMConfig->howlong);
