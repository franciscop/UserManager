<?
require dirname(__FILE__) . '/sdk/client.php';
require dirname(__FILE__) . '/sdk/contrib/Google_Oauth2Service.php';

$client = new Google_Client();
$client->setApplicationName($userConfig->google['app_name']);
$client->setClientId($userConfig->google['client_id']);
$client->setClientSecret($userConfig->google['client_secret']);
$client->setRedirectUri($userConfig->url);
$client->setDeveloperKey($userConfig->google['Email address']);

$OAuth = new Google_Oauth2Service($client);

if (isset($_GET['code'])) {
  $client->authenticate();
  $client->setAccessToken($client->getAccessToken());
  }

// If the user is effectively logged in with that service, set it
if ($client->getAccessToken()) {
  $UserData = $OAuth->userinfo->get('email');
  $Email = $UserData['email'];
  $Logged = 1;
  }

else
  {
  // Avoid asking for permission every time
  $Logged = 0;
  $loginUrl = str_replace("&approval_prompt=force", "", $client->createAuthUrl());
  }
