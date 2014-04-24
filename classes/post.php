<?php
// Gets the $_POST variable and formats it nicely.
class Post implements Request
  {
  protected $data = array();
  
  public function __construct($post)
    {
    $this->data = $post;
    }
  
  public function __get($name)
    {
    if (isset($this->data[$name]))
      return $this->data[$name];
    else return null;
    }
  
  public function __set($name, $value = "")
    {
    if (isset($this->data[$name]))
      {
      if (empty($value))
        {
        unset($this->data[$name]);
        $_POST[$name] = null;
        unset($_POST[$name]);
        return 1;
        }
      $this->data[$name] = $value;
      $_POST[$name] = $value;
      return 1;
      }
    }
  
  public function remove()
    {
    $this->data = null;
    unset($this->data);
    $_POST = null;
    unset($_POST);
    }
  }



