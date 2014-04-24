<?php
class Visitor
  {
  protected $db;
  protected $user;
  
  // Initialize the object. Checks whether the IP is blocked or not.
  public function __construct(PDO $DB)
    {
    $this->db = $DB;
    $this->ip = $_SERVER['REMOTE_ADDR'];
    if ($this->isblocked('ip', $this->ip) ||
        isset($_COOKIE['blocked']) ||
        isset($_SESSION['blocked']))
      {
      throw new Blocked();
      }
    }
  
  // Load the user
  public function setuser($User)
    {
    $this->user = $User;
    if ($this->isblocked('email', $User->email) ||
        $this->isblocked('user', $User->id))
      throw new Blocked();
    }
  
  // Check whether one part of the visitor's data is blocked or not
  public function isblocked($type, $value)
    {
    $STH = $this->db->prepare("SELECT * FROM blocked WHERE type = ? AND value = ?");
    $STH->execute(array($type, $value));
    $Res = $STH->fetch();
    return !empty($Res);
    }
  
  // Block the current visitor
  public function block()
    {
    // Start a session if needed
    if(session_id() == '' && !headers_sent())
      session_start();
    
    // Insert ip in blocked
    $STH = $this->db->prepare("INSERT INTO blocked (`type`, `value`) SET type = ? AND value = ?");
    $STH->execute(array('ip', $_SERVER['REMOTE_ADDR']));
    // Block the user if there's one
    if (isset($this->user))
      {
      $STH->execute(array('user',  $this->user->id   ));
      $STH->execute(array('email', $this->user->email));
      }
    return 1;
    }
  }
