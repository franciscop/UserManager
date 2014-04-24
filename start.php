<?php
// Start the session
if(session_id() == '') {
  if (headers_sent()) {
    throw new Exception ("You must include the 'include.php' file before sending any header.");
    }
  session_start();
  }

// Autoload needed classes. Requires PHP 5.3. See PHP documentation
spl_autoload_register(function ($class) {
  include 'classes/' . strtolower($class) . '.php';
  });

// Configuration for the PHP. If not edited it'll redirect to install.php
require 'config.php';

$userConfig = new userConfig();

$userDB = new PDO ("mysql:host=" . $userConfig->dbhost .
                 ";dbname="    . $userConfig->db .
                 ";charset=utf8",
                   $userConfig->dbuser, 
                   $userConfig->dbpass);


include "password_compat/password_compat.php";

// Compatibility reasons
$UMConfig = $userConfig;
$UMDB = $userDB;

$Post    = new Post($_POST);
$Session = new Session($_SESSION);
$Cookie  = new Cookie($_COOKIE);

$session = $_SESSION;
$cookie = $_COOKIE;

// TODO: implement or delete this
$userCookie = new Cookie($_COOKIE);


$Visitor = new Visitor($userDB);

// Find the full url requested
function full_url($s)
  {
  $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
  $sp = strtolower($s['SERVER_PROTOCOL']);
  $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
  $port = $s['SERVER_PORT'];
  $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
  $host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];
  return $protocol . '://' . $host . $port . $s['REQUEST_URI'];
  }

// Redirect the user to the specified url
function redirect($url = "")
  {
  // Get the proper redirection
  $url = (empty($url)) ? full_url($_SERVER) : $url;
  
  // Send the redirection headers
  header("Location: " . $url);
  
  // Always remember to exit.
  exit('Redirection failed. <a href = "' . $url . '">Click here for manual redirection</a>');
  }

$url = full_url($_SERVER);


/**/

