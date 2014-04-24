<?php
// This file is called the first time a user clicks on a logo
// It's called via ajax and uses $_POST
try {
  // Fetch the posted data
  $Redirect = $url;
  $Session->redirect = $url;
  $Service = $Post->service;
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
  
  // Set up the service that is trying to do the log in
  $Session->service = $Service;
  
  // If the user was logged in
  if ($Logged == 1)
    {
    $Session->service_email = $Email;
    
    // The user was connected. Javascript will refresh the page
    echo json_encode(array("status" => "logged", "email" => $Email));
    }
  else
    {
    // If the user wasn't logged in, redirect to the service's page
    $Session->service_connect = 1;
    
    // Couldn't connect the user. Javascript will redirect to the service's page
    echo json_encode(array("status" => "connect", "url" => $loginUrl));
    }
  }
catch (Exception $e)
  {
  // If there was an error, delete user data
  $Session->remove();
  
  // Show the error on ajax refresh
  echo json_encode(array("status" => "error", "message" => $e->getMessage()));
  }
// Since it's an ajax call, we don't want anything else being shown
exit;
