<?php
// Gets the $_POST variable and formats it nicely.
class Session implements Request
  {
  protected $data = array();
  
  public function __construct($session)
    {
    $this->data = $session;
    }
  
  public function __get($name)
    {
    if (isset($this->data[$name]))
      return $this->data[$name];
    }
  
  public function __set($name, $value = "")
    {
    // Delete the session
    if (empty($value) && isset($this->data[$name]))
      {
      $this->data[$name] = null;
      unset($this->data[$name]);
      $_SESSION[$name] = null;
      unset($_SESSION[$name]);
      return;
      }
    
    // Set variable
    $this->data[$name] = $value;
    $_SESSION[$name] = $value;
    }
  
  public function remove()
    {
    // http://stackoverflow.com/q/12291835/938236
    $this->data = array();
    $_SESSION = array();
    }
  }
