<?
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
