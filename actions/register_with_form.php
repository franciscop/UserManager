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
// 7. Send verification email.



// 1. Check email and password

// Retrieve them to ease the testing
$email = $Post->email;
$password = $Post->password;
// HONEYPOT. DO NOT FILL. It'll [try to] trap any automated bot trying to log users in
$bot = $Post->nameneverused;

// Emptiness check
if (!$email)
  throw new Exception("Please fill the email to register.");
if (!$password)
  throw new Exception("Please fill the password to register.");
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
// Check for the requeriments of the pasword
if(strlen($password) < 5)
  throw new Exception("Password too short");



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
$hash = password_hash($_POST['password'], PASSWORD_BCRYPT, array("cost" => $userConfig->cost));
$STH = $userDB->prepare("INSERT INTO users (email, hash, verified) VALUES (?, ?, 0)");
$Res = $STH->execute(array($email, $hash));

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
// Set the verification code
$code = rand();
$STH->execute(array($user_id, 'verification', $code));



// 5. Set the device in the cookie and database for persistent login.
$Id = $user_id;

include __DIR__ . "/secondary/set_token.php";



// 6. Set the session

// Set the user in the session
//$_SESSION['email'] = $email;


$to      = $email;
$subject = 'Complete registration';
$message = '
<html>
  <body>
    <h1>
      Register
    </h1>
    <p>
      Complete registration by clicking the link below
    </p>
    <a href = "' . $userConfig->url . $userConfig->folder . '/actions/verify_email.php?code=' . $code . '&redirect=' . $url . '">
      Verify account
    </a>
  </body>
</html>';
$headers = "From: usermanager@francisco.io\r\n";
$headers .= "Reply-To: usermanager@francisco.io\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$Res = mail($to, $subject, $message, $headers);

if (!$Res)
  throw new Exception("Email couldn't be sent");

