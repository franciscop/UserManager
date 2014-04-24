 <?php
// Mainly from http://www.phpclasses.org/package/7700-PHP-Authorize-and-access-APIs-using-OAuth.html

/* Get the http.php file from http://www.phpclasses.org/httpclient */
require dirname(__FILE__) . '/sdk/http.php';
require dirname(__FILE__) . '/sdk/oauth_client.php';

$client = new oauth_client_class;
$client->server = 'Microsoft';
$client->debug = false;
$client->debug_http = true;
$client->redirect_uri = 'http://spew.info/UserManager/services/microsoft/';

$client->client_id = $Config->microsoft['client_id'];
$application_line = __LINE__;
$client->client_secret = $Config->microsoft['client_secret'];

/* API permissions */
$client->scope = 'wl.basic wl.emails';

if(($success = $client->Initialize()))
  {
  if(($success = $client->Process()))
    {
        if(strlen($client->authorization_error))
        {
            $client->error = $client->authorization_error;
            $success = false;
        }
        elseif(strlen($client->access_token))
        {
            $success = $client->CallAPI(
                'https://apis.live.net/v5.0/me',
                'GET', array(), array('FailOnAccessError'=>true), $user);
            
            $_SESSION['service'] = "microsoft";
            $_SESSION['service_email'] = $user->emails->account;
            
            // Delete some nasty session variables
            foreach (array('OAUTH_STATE', 'OAUTH_ACCESS_TOKEN', ) as $Key) {
              unset($_SESSION[$Key]);
              }
            header ("Location: http://spew.info/");
            exit;
        }
    }
    $success = $client->Finalize($success);
  }

if($client->exit)
    exit;

if($success)
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Microsoft OAuth client results</title>
</head>
<body>
<?php
        echo '<h1>', HtmlSpecialChars($user->name),
            ' you have logged in successfully with Microsoft!</h1>';
        echo '<pre>', HtmlSpecialChars(print_r($user, 1)), '</pre>';
?>
</body>
</html>
<?php
    }
    else
    {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>OAuth client error</title>
</head>
<body>
<h1>OAuth client error</h1>
<pre>Error: <?php echo HtmlSpecialChars($client->error); ?></pre>
</body>
</html>
<?php
    }

?> 
