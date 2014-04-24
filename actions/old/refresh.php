<?php
// Avoid possible XSS attack: http://www.php.net/manual/en/function.htmlentities.php#99896
$href = htmlEntities($_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI], ENT_QUOTES);
header("Location: http://" . $href);
exit();
