<?php
session_start();
include "../../config.php";
$UMConfig = new UMConfig();

require $UMConfig->path . "/services/facebook/sdk/base_facebook.php";
require $UMConfig->path . '/services/facebook/sdk/facebook.php';

$facebook = new Facebook(array(
  'appId'  => $UMConfig->facebook['appId'],
  'secret' => $UMConfig->facebook['secret']
  ));

// Facebook returns a user.
if ($facebook->getUser()) {
  $user_profile = $facebook->api('/me');
  $email = $user_profile['email'];
  
  $_SESSION['service'] = "facebook";
  $_SESSION['service_email'] = $email;
  
  // The user was connected. Javascript will refresh the page
  echo json_encode(array("status" => "logged", "email" => $email));
  }

// If facebook returns no user, we need to connect the user to facebook's app
else {
  $_SESSION['service'] = "facebook";
  $_SESSION['service_connect'] = 1;
  $params = array('scope' => 'email', 'redirect_uri' => 'http://spew.info/');
  $loginUrl = $facebook->getLoginUrl($params);
  
  // Couldn't connect the user. Javascript will redirect to the service's page
  echo json_encode(array("status" => "connect", "url" => $loginUrl));
  }
