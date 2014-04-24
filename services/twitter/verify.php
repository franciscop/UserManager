<?php
session_start();
require $UMConfig->path . "/services/twitter/oauth/twitteroauth.php";

$twitteroauth = new TwitterOAuth(
  $UMConfig->twitter['consumer_key'],
  $UMConfig->twitter['consumer_secret']);

// Requesting authentication tokens, the parameter is the URL we will be redirected to
$request_token = $twitteroauth->getRequestToken('http://yourwebsite.com/getTwitterData.php');

// Saving them into the session

$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

// If everything goes well..
if ($twitteroauth->http_code == 200) {
    // Let's generate the URL and redirect
    $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
    header('Location: ' . $url);
} else {
    // It's a bad idea to kill the script, but we've got to know when there's an error.
    die('Something wrong happened.');
}







// There's already an email and the user only needs to validate it
require $UMConfig->path . "/services/facebook/sdk/base_facebook.php";
require $UMConfig->path . "/services/facebook/sdk/facebook.php";


$facebook = new Facebook(array(
  'appId'  => $UMConfig->facebook['appId'],
  'secret' => $UMConfig->facebook['secret']
  ));

// If the user is effectively logged in with that service, set it
if ($facebook->getUser()) {
  $user_profile = $facebook->api('/me');
  
  $_SESSION['service_email'] = $user_profile['email'];
  $_SESSION['service'] = 'facebook';
  
  $_SESSION['service_connect'] = "";
  unset($_SESSION['service_connect']);
  
  // Avoid possible XSS attack: http://www.php.net/manual/en/function.htmlentities.php#99896
  $href = htmlEntities($_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI], ENT_QUOTES);
  header("Location: http://" . $href);
  exit();
  }
