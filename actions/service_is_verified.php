<?php
// Find out if the user is already in the database or not
$STH = $userDB->prepare("SELECT * FROM users WHERE email = ?");
$STH->execute(array($Session->service_email));
$Res = $STH->fetch();

// If user is already in the database
if (!empty($Res))
  {
  $user_id = $Res['id'];
  
  // Set the device in the cookie and database for persistent login.

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
  
  $email = $Session->service_email;
  // Find the original page that was clicked
  $GoToUrl = $Session->redirect;
  
  $Session->remove();
  $Session->email = $email;
  
  // If the current url is different from the redirect one
  redirect($GoToUrl);
  }
// If user is not in the database
else
  {
  $userRegister = $Session->service_email;
  }
