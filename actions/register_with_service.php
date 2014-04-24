<?php
// The user has submitted the form to register. Attempt to do so.
//
// Index:
// 1. Check that email and password have a valid format. Simple bot detection.
// 2. Check that the required fields are filled.
// 3. Set the user in the database
// 4. Set the user data in the database
// 5. Set the device in the cookie and database for persistent login.
// 6. Set the session.
// 7. Redirect the user where he initially made login



// 1. Check email and password

// Retrieve them to ease the testing
$email = $Session->service_email;
// HONEYPOT. DO NOT FILL. It'll [try to] trap any automated bot trying to log users in
$bot = $Post->nameneverused;

// Emptiness check
if (!$email)
  throw new Exception("The service returned an empty email.");
if ($bot)
  throw new Exception("I am so sorry you little bot but your princess is in another castle");

// Verify that the user is in the database
$STH = $userDB->prepare("SELECT * FROM users WHERE email = ?");
$STH->execute(array($email));
$Res = $STH->fetch();

// Database check
if (!empty($Res))
  throw new Exception("Email is already registered");

// To avoid malformed emails
if(!filter_var($email, FILTER_VALIDATE_EMAIL))
  throw new Exception("Email not valid");



// 2. Check that the required fields are filled.

// Where the data is stored
$userdata = array();

// Check that the submitted data is correct
foreach ($userConfig->userdata as $Name => $Field)
  {
  // Required fields are not left empty
  if ($Field['required'] && !$Post->$Name)
    throw new Exception("Required field <strong>" . $Name . "</strong> was not filled in.");
  // The fields are of the required type
  if (0)
    throw new Exception("Field <strong>" . $Name . "</strong> posted as <strong>" . $post[$Name] . "</strong> is not valid.");
  // Store the data in an appropriate format
  $userdata[$Name] = $_POST[$Name];
  }



// 3. Set the user in the database

// Generate the secure hash
$STH = $userDB->prepare("INSERT INTO users (email, hash, verified) VALUES (?, '', 1)");
$Res = $STH->execute(array($email));

if (!$Res)
  throw new Exception("There was a problem saving the user in the database: " . serialize($userDB->errorInfo()));



// 4. Set the user data in the database
$user_id  = $userDB->lastInsertId();
$STH = $userDB->prepare("INSERT INTO user_data (user_id, field, value) VALUES (?, ?, ?)");
foreach($userdata as $Field => $Value)
  {
  $Res = $STH->execute(array($user_id, $Field, $Value));
  if (!$Res)
    throw new Exception("Couldn't save <strong>" . $Field . "</strong> into the database: " . serialize($userDB->errorInfo()));
  }



// 5. Set the device in the cookie and database for persistent login.

// Generate current device's token
$token = substr(bin2hex(mcrypt_create_iv(200)), 0, 60);

// Include this device's token
$STH = $userDB->prepare("INSERT INTO devices (user_id, token, active) VALUES (?, ?, 1)");
$Res = $STH->execute(array($user_id, $token));

if (!$Res)
 throw new Exception("Couldn't store the device in the database " . serialize($userDB->errorInfo()));

// To keep users logged in between sessions
$Cookie->email = $email;
$Cookie->token = $token;



// 6. Set the session

// Find the original login page
$GoToUrl = $Session->redirect;

// First delete the old session
$Session->remove();

// And set only the email
$Session->email = $email;



// 7. Redirect the user where he initially made login

// If the current url is different from the redirect one
if (isset($GoToUrl) && $url != $GoToUrl)
  redirect($GoToUrl);


