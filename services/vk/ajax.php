<?php
// Thank you https://github.com/vladkens/VK

require 'sdk/vk.php';
$vk = new VK\VK('3930644', 'sIjxdrLdlsW7MBXwcZiq');
$vk->getAuthorizeURL('{API_SETTINGS}', '{CALLBACK_URL}');
if ($vk->isAuth())
  echo "Authorized!";
else
  echo "Not authorized!";
