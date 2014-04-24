<?php
// UPDATETE DEVICE
// Extend the time the device will be active
setcookie("email", $_COOKIE['email'], time() + 3600 * 24 * $UMConfig->howlong);
setcookie("token", $_COOKIE['token'], time() + 3600 * 24 * $UMConfig->howlong);
