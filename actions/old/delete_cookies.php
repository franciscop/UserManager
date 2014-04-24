<?php
// Unset the session
$_SESSION['email'] = "";
$_SESSION['service'] = "";
$_SESSION['shown_service'] = "";
$_SESSION['service_email'] = "";
unset ($_SESSION['email']);
unset ($_SESSION['service']);
unset ($_SESSION['shown_service']);
unset ($_SESSION['service_email']);

// The whole thing (sent when headers are sent)
setcookie("email",    "", time() - 3600);
setcookie("token",    "", time() - 3600);
setcookie("service",  "", time() - 3600);

// This single run of the script
$_COOKIE['email']   = "";
$_COOKIE['token']   = "";
$_COOKIE['service'] = "";
