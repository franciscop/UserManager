<?php
// A visitor with a cookie and no session has entered
//
// Index:
// 1. Check email and retrieve the id
// 2. Check the token
// 3. Delete the current token from both sides
// 4. Set the new token in both sides
// 5. Set the session



// 1. Check email

// Retrieve them to ease the testing
$STH = $userDB->prepare("SELECT * FROM users WHERE email = ?");
$STH->execute(array($Cookie->email));
$Res = $STH->fetch();

// Find out whether the user exists or not
if (empty($Res))
  throw new Exception("User in the cookie is not in the database");

// Save the Id for future use
$Id = $Res['id'];



// 2. Check the token

// Find out the active tokens from the database
$STH = $userDB->prepare("SELECT * FROM devices WHERE id = ?");
$STH->execute(array($Cookie->token_id));
$Res = $STH->fetch();

if (empty($Res))
  throw new Exception("User has no device associated");

if ($Res['active'] != 1)
  throw new Exception("Your device is not active");

// Treat it as if it was a password
if (password_verify($Cookie->token, $Res['token']))
  throw new Exception("User token is not the same as the one in the database");

// Delete the old token
$STH = $UMDB->prepare("UPDATE devices SET active=0 WHERE id = ?");
$Res = $STH->execute(array($Res['id']));
if (!$Res)
  throw new Exception("Couldn't delete device from database" . serialize($STH->errorInfo()));

include __DIR__ . "/secondary/set_token.php";

// The user was validated
$Session->email = $Cookie->email;
