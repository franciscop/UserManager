<?php
class Blocked extends Exception
  {
  public function __construct($message)
    {
    echo "You're blocked.<br>" . $message;
    exit;
    }
  }
