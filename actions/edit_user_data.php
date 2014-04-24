<?php

if (!isset($Session->email))
  throw new Attack("Anonymous user tried to edit profile", $userDB, $Visitor->ip);

foreach($_POST as $Name => $Posted) {
  // Whitelisting
  if (in_array($Name, $UMConfig->userdata)) {
    // Updating. $Name is whitelisted already
    $STH = $UMDB->prepare('UPDATE users SET ' . $Name . ' = ? WHERE email = ? LIMIT 1');
    $STH->execute(array($Posted, $User->email));
    }
  }

// If the user has submited a new email that is not empty
if ($User->email != $_POST['email'] &&
    $User->service == "usermanager" &&
    !empty($_POST['email'])) {
  // Check that the email is not already in use
  if (!Validate::is_user($_POST['email'])) {
    $Email = $_POST['email'];
    
    $STH = $UMDB->prepare('UPDATE users SET email = ? WHERE id = ? LIMIT 1');
    $STH->execute(array($Email, $User->id));
    var_dump($STH->errorInfo());
    
    // Need to change the authorization's cookie and session email.
    // It will only be updated in this device, which might be what the user intended
    $_SESSION['email'] = $Email;

    // To keep users logged in between sessions
    setcookie("email", $Email, time() + 3600 * 24 * $UMConfig->howlong);
    }
  else {
    $UMError = 1;
    $UMLog = "Sorry, email already in use";
    }
  }
