<?php
// Declare the interface Request for all the data requests
interface Request
  {
  public function __construct($all);
  public function __get($name);
  public function __set($name, $value);
  public function remove();
  }
