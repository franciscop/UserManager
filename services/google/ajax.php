<?php
session_start();
include "../../config.php";
$UMConfig = new UMConfig();

require $UMConfig->path . '/services/google/sdk/client.php';
require $UMConfig->path . '/services/google/sdk/contrib/Google_Oauth2Service.php';

$client = new Google_Client();
$client->setApplicationName('User Manager');
$client->setClientId($UMConfig->google['client_id']);
$client->setClientSecret($UMConfig->google['client_secret']);
$RedirectUrl = $UMConfig->url;
$client->setRedirectUri($RedirectUrl);
$client->setDeveloperKey($UMConfig->google['developer_key']);

// This will set the scope
// $plus = new Google_PlusService($client);
$oauth2Service = new Google_Oauth2Service($client);

if ($client->getAccessToken()) {
  $UserData = $oauth2Service->userinfo->get('email');
  echo $UserData['email'];
  
  $_SESSION['service_email'] = $UserData['email'];
  $_SESSION['service'] = 'gmail';
  
  $_SESSION['service_connect'] = "";
  unset($_SESSION['service_connect']);
  }
else {
  $_SESSION['service'] = "google";
  $_SESSION['service_connect'] = 1;
  
  // Avoid asking for permission every time
  $RedUrl = str_replace("&approval_prompt=force", "", $client->createAuthUrl());
  echo json_encode(array("status" => "connect", "url" => $RedUrl));
  }
