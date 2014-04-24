<?
// Unlink this device. Better here than in garbage collector
$STH = $UMDB->prepare("DELETE FROM devices WHERE token = ?");
$STH->execute(array($_COOKIE['token']));

// Needs to be unset
$User = "";
unset($User);

foreach ($_SESSION as $Key => $Val) {
  $_SESSION[$Key] = "";
  unset($_SESSION[$Key]);
  }
