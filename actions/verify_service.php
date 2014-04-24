<?php
// This file is called after the page is returned from a service
// It's called via a normal page load and uses $_SESSION
try {
  // Fetch the session data
  $Redirect = $url;
  $Service = $Session->service;
  $Verify = $userConfig->path . "/services/" . $Service . "/verify.php";
  
  // The bits above are supposed to be correct. Verify them anyway
  if (empty($Redirect) || !filter_var($Redirect, FILTER_VALIDATE_URL))
    throw new Exception("The redirect url provided is not valid");
  if (!in_array($Service, $userConfig->services))
    throw new Exception("The service " . $Service . " is not a valid one according to config.php.");
  if (!file_exists($Verify))
    throw new Exception("The file " . $Verify . " does not exist");

  // Check whether the user is verified or not
  include $Verify;

  if (!$Logged)
    throw new Exception("User should be logged in at verify_service. Maybe canceled?");

  // Validation check
  if (empty($Email) || !filter_var($Email, FILTER_VALIDATE_EMAIL))
    throw new Exception("Email format invalid " . $Email . ". Service " . $Service . " might not be set properly.");

  // Now we need to check if the user is in our database. Set up the next step.
  $Session->service = $Service;
  $Session->service_email = $Email;

  // The service already is connected. We don't need this anymore
  $Session->service_connect = null;
  
  // Redirect the user to the page that was set for redirection
  redirect($Session->redirect);
  }
catch (Exception $e) {
  // If there was an error, delete user data
  $Session->remove();
  
  // Show the error on screen
  echo $e->getMessage();
  exit;
  }
