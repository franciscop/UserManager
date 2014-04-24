<?php
// Delete all user request variables and device
//
// Index:
// 1. Deactivate device.
// 2. Delete data
// 3. Refresh the page



// 1. Check email and password

// Unlink this device. Better here than in garbage collector
if ($Cookie->token)
  {
  $STH = $UMDB->prepare("UPDATE devices SET active=0 WHERE token = ?");
  $Res = $STH->execute(array($Cookie->token));
  if (!$Res)
    throw new Exception("Couldn't delete device from database" . serialize($STH->errorInfo()));
  }



// 2. Delete the data

include __DIR__ . "/secondary/delete_data.php";



// 3. Refresh the current page

redirect();
