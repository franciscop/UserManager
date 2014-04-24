<?php
// Include all files from the sdk
$SDKFiles = glob($userConfig->path . '/services/' . $Service . '/sdk/*.php');
foreach ($SDKFiles as $SDKFile)
  include $SDKFile;

// This file is accessed directly after attempting to connect with facebook
$Facebook = new Facebook(array(
  'appId'  => $userConfig->facebook['appId'],
  'secret' => $userConfig->facebook['secret']
  ));

// If the user is effectively logged in with that service, set it
if ($Facebook->getUser()) {
  $Profile = $Facebook->api('/me');
  $Email = $Profile['email'];
  $Logged = 1;
  }

// If facebook returns no user, retrieve the url where we can set the user up
else {
  $Params = array('scope' => 'email', 'redirect_uri' => $Redirect);
  $loginUrl = $Facebook->getLoginUrl($Params);
  $Logged = null;
  }
