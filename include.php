<?php
// Top try-catch block
try {
  // Start the needed variables: session, config, pdo, validate
  require 'start.php';
  
  // The path of the other needed script
  $userBody = $userConfig->path . "/body.php";
    
  // Find out the request that is taking place (if any).
  require 'request.php';
  $action = request($Post, $Session, $cookie);
  
  // Find invalid requests or missing files
  if ($action && !file_exists($action_path = $userConfig->path . "/actions/" . $action . ".php"))
    throw new Exception("<strong>" . $action_path . "</strong> does not exist.");
  
  // Do the action if there was any
  if ($action) {
    include $action_path;
    }
  
  // There's a user logged in
  if ($Session->email)
    $User = new User($userDB, $Session->email);
  }

// If anything goes wrong
catch (Exception $e) {
  $Message = "Fatal error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
  // Substitude for your preferred method to log errors.
  file_put_contents($userConfig->error_log, $Message, FILE_APPEND);
  }

