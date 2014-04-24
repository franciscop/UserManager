<?php
// The user has submitted the form to log in. Attempt to do so.
//
// Index:
// 1. Check that email and password are correct. Simple bot detection.
// 2. Set the device in the cookie and database for persistent login.
// 3. Set the session.
// 4. Refresh the page



// 1. Check email and password

// Retrieve them to ease the testing
$email = $Post->email;
$password = $Post->password;
// HONEYPOT. DO NOT FILL. It'll [try to] trap any automated bot
$bot = $Post->nameneverused;

// Emptiness and bot checks
if (!$email)
  throw new Exception("Please fill the email to log in.");
if (!$password)
  throw new Exception("Please fill the password to log in.");
if ($bot)
  throw new Exception("I am so sorry you little bot but your princess is in another castle");

// Verify that the user is in the database
$STH = $userDB->prepare("SELECT * FROM users WHERE email = ?");
$STH->execute(array($email));
$Auth = $STH->fetch();
$Id = $Auth['id'];

// Database check
if (empty($Auth))
  throw new Exception("Email is not registered");
if (!password_verify($password, $Auth['hash']))
  throw new Exception("Password is not valid");



// 2. Set the device as a valid's one

// Generate current device's token
$token = substr(bin2hex(mcrypt_create_iv(200)), 0, 60);

// Include this device's token
$STH = $userDB->prepare("INSERT INTO devices (user_id, token, active) VALUES (?, ?, 1)");
$Res = $STH->execute(array($Id, $token));

if (!$Res)
  throw new Exception("Couldn't store the device in the database");

// To keep users logged in between sessions
$Cookie->email = $email;
$Cookie->token = $token;



// 3. Set the session

// Set the user in the session
$Session->email = $email;



// 4. Refresh the page

redirect();




