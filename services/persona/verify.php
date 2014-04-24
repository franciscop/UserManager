<?php
require dirname(__FILE__) . "/library.php";

$Persona = new Persona();

$Result = $Persona->verifyAssertion($Post->assertion);

if ($Result->status == 'okay') {
  $Email = $Result->email;
  $Logged = 1;
  }

// Just tell the script above that there was an error
else {
  $Logged = null;
  }
