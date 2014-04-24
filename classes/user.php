<?php
class User {
  protected $email;
  protected $DB;
  protected $Data;
  
  public function __construct(PDO $DB, $Email) {
    $this->db = $DB;
    
    $this->email = $Email;
    $this->Data = $this->loaddata($Email);
    }
  
  protected function loaddata($Email) {
    $STH = $this->db->prepare("SELECT `field`, `value` FROM `users`, `user_data` WHERE `users`.`id` = `user_id` AND `email` = ?");
    $STH->execute(array($Email));
    $Rows = $STH->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($Rows)) {
      throw new Exception("Provided email (" . $Email . ") is not in our database.");
      }
    
    foreach ($Rows as $Row) {
      $Data[$Row['field']] = $Row['value'];
      }
    return $Data;
    }
  
  public function __get($Name) {
    if (array_key_exists($Name, $this->Data)) {
      return $this->Data[$Name];
      }
    }
  }
