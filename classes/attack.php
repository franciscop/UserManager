<?php
// This class should be very resilient. Therefore, let's assume as little as possible
// None of the arguments are mandtory
class Attack extends Exception {
  public function __construct($DB = null, $message = null) {
    // These two methods are easy to get around for bots. However, they add another step
    if (!headers_sent())
      {
      $s = $_SERVER;
      $host =
        isset($s['HTTP_X_FORWARDED_HOST']) ?
        $s['HTTP_X_FORWARDED_HOST'] :
        isset($s['HTTP_HOST']) ?
          $s['HTTP_HOST'] :
          $s['SERVER_NAME'];
      
      // Block from the cookie
      setcookie("blocked", 1, time() + 7 * 24 * 3600, "/", '.' . $host);
      
      // Block from the 
      if(session_id() == '')
        {
        session_start();
        }
      
      // Block the session
      $_SESSION['blocked'] = 1;
      }
    
    $IP = $_SERVER['REMOTE_ADDR'];
    
    $STH = $DB->prepare("INSERT INTO blocked (`type`, `value`, `message`) VALUES ('ip', ?, ?)");
    $Res = $STH->execute(array($IP, $message));
    
    parent::__construct($message);
    }
  }












