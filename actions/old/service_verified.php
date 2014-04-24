<?php
// If the dialog has been shown already
if (isset($_SESSION['shown_service'])) {
  // Delete it
  include $UMConfig->path . '/actions/delete_cookies.php';
  }
// First time the dialog is shown, set it as shown
else {
  $_SESSION['shown_service'] = 1;
  
  // This only says that there's a user that needs to register
  $UMRegisterUser = $_SESSION['service_email'];
  
  $_SESSION['origin'] = htmlEntities($_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI], ENT_QUOTES);
  }
