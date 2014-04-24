<?php

class Cookie implements Request
  {
  protected $data = array();
  
  // Expiration time from now
  protected $expire;
  // Domain
  protected $domain;
  
  // Default expiration is 7 days (7 * 3600 * 24 = 604800)
  // There's no default domain.
  public function __construct($cookie, $expire = 604800, $domain = null)
    {
    // Set up the data of this cookie
    $this->data = $cookie;
    
    // Structure the options
    $this->expire = $expire;
    
    $this->setdomain($domain);
    }
  
  public function __get($name)
    {
    if (isset($this->data[$name]))
      return $this->data[$name];
    }
  
  public function __set($name, $value = "")
    {
    // Check whether the headers are already sent or not
    if (headers_sent())
      throw new Exception("Can't change cookie " . $name . " after sending headers.");
    
    // Delete the cookie
    if (empty($value) && isset($this->data[$name]))
      {
      setcookie($name, null, time() - 10, '/', '.' . $this->domain, false, true);
      unset($this->data[$name]);
      unset($_COOKIE[$name]);
      return;
      }
    
    // Set the actual cookie
    setcookie($name, $value, time() + $this->expire, '/', $this->domain, false, true);
    $this->data[$name] = $value;
    $_COOKIE[$name] = $value;
    }
  
  public function remove()
    {
    // Check whether the headers are already sent or not
    if (headers_sent())
      throw new Exception("Can't delete cookie " . $name . " after sending headers.");
    
    foreach ($this->data as $Name => $Value)
      setcookie($Name, null, time() - 10, '/', '.' . $this->domain, false, true);
    
    $this->data = array();
    $_COOKIE = array();
    }
  
  // Set the domain for the cookies
  public function setdomain($domain = null) {
    if (!empty($domain) && filter_var($domain, FILTER_VALIDATE_URL))
      $this->domain = $domain;
    else
      // The default domain
      $this->domain = 
        isset($_SERVER['HTTP_X_FORWARDED_HOST']) ?
        $_SERVER['HTTP_X_FORWARDED_HOST'] :
        isset($_SERVER['HTTP_HOST']) ?
          $_SERVER['HTTP_HOST'] :
          $_SERVER['SERVER_NAME'];
    }
  }
