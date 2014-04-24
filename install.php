<?php
// Start the session
session_start();

ini_set('memory_limit', "1000M");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the url where the problems are described
$issues = "#";

// Handle all the ajax calls
if (!empty($_POST) && isset($_POST['step']))
  {
  // Find out which kind of call it is
  switch ($_POST['step'])
    {
    // DATABASE. Get database configuration and create tables
    case 'database':
      // Definition of each table
      $tables = array(
        // User identification
        'users' => "
          CREATE TABLE `users` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `email` varchar(250) NOT NULL,
            `hash` char(60),
            `verified` bool,
            PRIMARY KEY (`id`),
            UNIQUE KEY `email` (`email`)
            )
            ENGINE=MyISAM
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci",
        // User data
        'user_data' => "
          CREATE TABLE `user_data` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `user_id` INT NOT NULL,
            `field` varchar(250),
            `value` varchar(1000),
            PRIMARY KEY (`id`),
            INDEX `user_id` (`user_id`),
            INDEX `field` (`field`)
            )
            ENGINE=MyISAM
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci",
        // Active devices (persistent login)
        'devices' => "
          CREATE TABLE `devices` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `user_id` INT NOT NULL,
            `token` char(60),
            `active` bool,
            PRIMARY KEY (`id`),
            INDEX `user_id` (`user_id`),
            INDEX `token` (`token`)
            )
            ENGINE=MyISAM
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci",
        // Users that cannot come back in
        'blocked' => "
          CREATE TABLE `blocked` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `type` varchar(50),
            `value` varchar(250),
            `message` varchar(250),
            PRIMARY KEY (`id`),
            INDEX `type` (`type`)
            )
            ENGINE=MyISAM
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci");
      
      
      // Check if we can connect to database
      try {
        $db = new PDO(
          "mysql:host=" . $_POST['host'] .
            ";dbname=" . $_POST['database'] .
            ";charset=utf8",
          $_POST['username'],
          $_POST['password']);
        }
      catch(Exception $e) {
        echo json_encode(array(
          'status' => 'error',
          'code' => 'database_data',
          'message' => 'Invalid database data.'));
        exit;
        }
      $_SESSION['database'] = $_POST;
      
      // Check if the tables are already set.
      try {
        foreach (array_keys($tables) as $Table)
          {
          // If the table already exists, throw exception
          if($db->query("SHOW TABLES LIKE '$Table'")->rowCount() > 0)
            throw new Exception($Table);
          }
        }
      catch(Exception $e) {
        echo json_encode(array(
          'status' => 'error',
          'code' => 'table_exists',
          'message' => "Table <strong>'" . $e->getMessage() . "'</strong> already exist. Cannot overwrite."));
        exit;
        }
      
      // Attempt to set the tables (implied: try only if there was no error)
      try {
        foreach ($tables as $Value)
          $db->query($Value);
        }
      catch (Exception $e) {
        echo json_encode(array(
          'status' => 'error',
          'code' => 'create_tables',
          'message' => 'There was an error creating the table.'));
        exit;
        }
      
      echo json_encode(array('status' => 'done'));
      exit;
      break;
    
    // Set the fields up
    case 'fields':
      $post = $_POST;
      // Clean all empty arrays
      foreach ($post['field'] as $Key => $Field)
        {
        if ($Field == "")
          {
          unset ($post['field'][$Key]);
          unset ($post['type'][$Key]);
          unset ($post['required'][$Key]);
          }
        }
      
      // If there was nothing valid set
      if(empty($post['field']))
        {
        $_SESSION['fields'] = array();
        }
      else
        {
        // Check if duplicated arrays
        if (count($post['field']) != count(array_unique($post['field'])))
          {
          $diff = array_diff($post['field'], array_unique($post['field']));
          $diff = $diff[0];
          echo json_encode(array(
            'status' => 'error',
            'code' => 'repeated_field',
            'message' => '<strong>' . $diff . '</strong> is duplicated.'));
          exit;
          }
        // Format properly the array
        $Fields = array();
        foreach ($post['field'] as $Key => $Value)
          {
          $Fields[$Key]['text']    = $Value;
          $name = strtolower($Value);
          $name = preg_replace('/[^\da-z]/i', '_', $name);
          $Fields[$Key]['name']     = $name;
          $Fields[$Key]['type']     = $post['type'][$Key];
          $Fields[$Key]['required'] = !empty($post['required'][$Key]) ? 1 : 0;
          }
        $_SESSION['fields'] = $Fields;
        }
      
      if (empty($_SESSION['fields']))
        {
        echo json_encode(array('status' => 'error', 'values' => 'The fields array is empty for some misterious reason'));
        exit;
        }
      
      session_write_close();
      echo json_encode(array('status' => 'done', 'values' => $_SESSION['fields']));
      exit;
    
    
    // Create it
    case 'services':
      // Check if the config.php already exists
      if (file_exists("config.php"))
        {
        echo json_encode(array(
          'status' => 'error',
          'code' => 'config_exists',
          'message' => "<strong>'config.php'</strong> already exists."));
        exit;
        }
      
      // Check that the $_SESSION array is valid. Avoiding attacks
      if (empty($_SESSION['database']) || empty($_SESSION['fields']))
        {
        echo json_encode(array(
          'status' => 'error',
          'code' => 'config_exists',
          'message' => "Some required data is not yet set"));
        var_dump($_SESSION);
        exit;
        }
      
      // Create the file config.php
      try
        {
        $config =
'<?php
class userConfig {
  private $dbhost = "' . $_SESSION['database']['host'] . '";
  private $db     = "' . $_SESSION['database']['database'] . '";
  private $dbuser = "' . $_SESSION['database']['username'] . '";
  private $dbpass = "' . $_SESSION['database']['password'] . '";
  
  // The data that will be stored and available from each user
  private $userdata = array(
';

$typerel = array(
  "text"     => 'text',
  "longtext" => 'textarea',
  "email"    => 'email',
  "password" => 'password',
  "number"   => 'number',
  "url"      => 'text',
  "bool"     => 'checkbox',
  "float"    => 'number');
  
foreach ($_SESSION['fields'] as $Key => $Field)
  {
  $config .= '    "' . $Field['name'] . '" => array(';
  $config .= '"text" => "' . $Field['text'] . '", ';
  $config .= '"type" => "' . $Field['type'] . '", ';
  $config .= '"inputtype" => "' . $typerel[$Field['type']] . '", ';
  $config .= '"required" => ' . $Field['required'] . ')';
  if ($Key < count($_SESSION['fields']) - 1)
    $config .= ',
';
  }

$config .= '
    );
  
  // Which one to display by default in the button
  private $username = "firstname";
  
  // Load jquery the first thing in the body. Disable if you already included it.
  private $jquery   = 1;
  
  // Check whether to load the css stylesheet or not. You might want not to load it if you minimize it and include in your main one
  private $css      = 1;
  
  // Link to your terms of Service. Will be include as a checkbox in the "register" page.
  private $tos      = "";
  
  // Set the time the users will be logged in (in days) after last log in
  private $howlong  = 50;
  
  // The bit of the url. For http://example.com/UserManager would be "/UserManager/"
  private $folder   = "/users/manager";
  
  // Set the base redirect url, since some services require it
  private $url = "';
  
  $config .= (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https://": "http://";
  $config .= $_SERVER["SERVER_NAME"] . '";
  
  // TODO: a compatibility list.
  // The services you want to allow your user to login with.
  private $services = array ("persona", "facebook", "google", "microsoft");
  
  // Required by password_compat.
  private $cost     = 10;
  
  // Set the absolute path
  private $path = "' . dirname(__FILE__) . '";
  
  // Set the file that will receive the errors
  private $error_log = "' . dirname(dirname(__FILE__)) . '/error_log";
  
  private $facebook = array(
    "appId"  => "",
    "secret" => ""
    );
  
  private $google = array(
    "client_id"  => "",
    "client_secret" => "",
    "developer_key" => ""
    );
  
  private $microsoft = array(
    "client_id" => "",
    "client_secret" => ""
    );
  
  private $twitter = array(
    "consumer_key"  => "",
    "consumer_secret" => ""
    );
  
  public function __get($Name) {
    if (property_exists($this, $Name)) {
      return $this->$Name;
      }
    }
  
  public function __construct() {
    
    
    // SHOULD BE MIGRATED TO INSTALL.PHP
    // Verify the services
    $Services = $this->services;
    // Iterate over each service
    foreach ($Services as $Key=>$Service)
      // If the following, required files are not included
      if (!file_exists($this->path . "/services/" . $Service . "/verify.php") ||
          !file_exists($this->path . "/services/" . $Service . "/logo.svg")   ||
          !file_exists($this->path . "/services/" . $Service . "/click.js")
          )
        // Delete service
        unset($Services[$Key]);
    
    $this->services = $Services;
    }
  }
  ';
        file_put_contents("config.php", $config);
        }
      catch (Exception $e) {
        echo json_encode(array(
          'status' => 'error',
          'code' => 'create_config',
          'message' => "Error creating '<strong>config.php</storng>'."));
        exit;
        }
      
      // From the notes in http://www.php.net/manual/en/function.session-unset.php
      /*
      session_unset();
      session_destroy();
      session_write_close();
      setcookie(session_name(),'',0,'/');
      */
      echo json_encode(array('status' => 'done'));
      exit;
      break;
    }
  // If it was an ajax call, we don't want it to keep executing
  exit;
  }


if (empty($_SESSION['database']))
  $_SESSION['database'] = array('host' => '', 'database' => '', 'username' => '', 'password' => '');
if (empty($_SESSION['fields']))
  $_SESSION['fields'] = array();
?>
<!DOCTYPE html>
<html>
  <head>
  <meta charset='utf-8'>
  <script src = "http://code.jquery.com/jquery-latest.min.js"></script>
  <script>
  $(document).ready(function(){
    var issues = "<?= $issues; ?>";
    function updatetype(input)
      {
      // Update the other input
      var text = input.val().replace(/[^a-zA-Z0-9]+/g, '_').toLowerCase();
      input.siblings(".extra").children(".dbfield").val(text);
      // Add one if you are editing the last one
      if (text.length !== 0 && input.closest("li").is(':last-child'))
        {
        $("#fields ul").append('<li>' + input.closest("li").html() + '</li>');
        $("#fields li:last-child input").val("");
        }
      // Remove the last one if there are several empty
      if($("#fields li:last-child").children('input').val().length == 0 &&
         $("#fields li:last-child").prev().children('input').val().length == 0)
        {
        $("#fields li:last-child").remove();
        }
      }
    
    $("#fields").on('keyup', '.field', function(){
      updatetype($(this));
      });
  
    $("#fields").on('click', '.field', function(){
      updatetype($(this));
      });
    
    function next(form, id)
      {
      form.hide();
      form.next().slideDown(200);
      form.next().children("input:first-child").focus();
      if (id == "services")
        {
        document.location.href = './';
        }
      }
    
    function sent(data, form, id, origin)
      {
      form.children("input[type='submit']").val(origin);
      // This is NOT the default
      if (data.status == "done")
        {
        next(form, id);
        }
      else
        {
        form.prepend('<div class = "status">Error: ' + data.message + ' <a  href = "' + issues + data.code + '">More info</a></div>');
        }
      }
    
    // A form is submited
    $("form").submit(function(e)
      {
      e.preventDefault();
      var form = $(this);
      var id = form.attr('id');
      var origin = form.children("input[type='submit']").val();
      form.children("input[type='submit']").val("Sending...");
      form.children(".status").remove();
      if (id == "start")
        {
        next(form, id);
        return 1;
        }
      $.post(window.location.href, form.serialize(), function(data) {
        sent(data, form, id, origin);
        }, "json");
      });
    });
  </script>
  <style>
    * {
      box-sizing: border-box;
      -moz-box-sizing: border-box;
      }
    
    html {
      background-color: #EEE;
      height: 100%;
      margin: 0;
      padding: 0;
      }
    
    body {
      width: 800px;
      min-height: 100%;
      margin: 0 auto;
      background-color: #FFF;
      min-height: 100%;
      box-shadow: 0 0 10px #AAA;
      padding: 0 100px 100px;
      }
    
    body > div {
      margin: 0 auto;
      text-align: center;
      }
    
    h1 {
      text-align: center;
      margin: 0;
      padding: 60px 0 20px;
      }
    
    a {
      text-decoration: none;
      }
    
    input, select {
      margin: 5px 0;
      padding: 5px;
      font-size: 1.2em;
      }
    
    input[type="submit"]:hover {
      cursor: pointer;
      }
    
    label {
      margin: 5px;
      }
    
    .status {
      background-color: yellow;
      padding: 10px;
      margin: 5px 0 15px;
      }
    
    
    /* WELCOME */
    #start {
      width: 300px;
      margin: 0 auto;
      }
    
    
    
    /* DATABASE */
    #database {
      display: none;
      width: 300px;
      margin: 0 auto;
      }
    
    #database input {
      width: 100%;
      }
    
    #database input[type="submit"] {
      margin-top: 15px;
      }
    
    #database label {
      margin: 5px 5px 5px 2px;
      }
    
    
    
    /* FIELDS */
    #fields {
      display: none;
      }
    
    ul {
      list-style: none;
      padding: 0;
      }
    
    #fields li {
      margin: 0px 0px 10px;
      padding: 0px 0px 10px;
      box-shadow: 0px 2px 2px -2px #AAA;
      }
    
    #fields input {
      margin: 5px 5px 5px 0;
      }
    
    #fields input[type="text"] {
      width: calc(50% - 9px);
      }
    
    #fields .more {
      display: inline-block;
      float: right;
      top: 1.1em;
      position: relative;
      right: 2em;
      text-align: center;
      min-width: 19px;
      padding: 0 5px;
      background-color: #AFA;
      border-radius: 10px;
      }
    
    #fields .more:hover {
      cursor: pointer;
      }
    
    
    
    /* SERVICES */
    #services {
      display: none;
      }
  </style>
  </head>
<body>
<h1>Install User Manager</h1>
<form id = "start" method = "POST">
  <p>
  Welcome,
  </p>
  <p>
  We will install <a href = "https://github.com/FranciscoP/UserManager" target="_blank">User Manager</a> when you are ready.
  Thank you for choosing us.
  </p>
  <p>
  Francisco Presencia
  </p>
  <input type = "hidden" name = "step" value = "start">
  <input type = "submit" value = "Start">
</form>

<form id = "database" method = "POST" autocomplete="off">
  <label>Host:
    <input type = "text"     name = "host"     placeholder = "localhost" value = "<?= $_SESSION['database']['host'];     ?>" autofocus>
  </label>
  <label>Database:
    <input type = "text"     name = "database" placeholder = "database"  value = "<?= $_SESSION['database']['database']; ?>">
  </label>
  <label>Username:
    <input type = "text"     name = "username" placeholder = "username"  value = "<?= $_SESSION['database']['username']; ?>"><br>
  </label>
  <label>Password:
    <input type = "password" name = "password" placeholder = "password"  value = "<?= $_SESSION['database']['password']; ?>"><br>
  </label>
  <input type = "hidden"   name = "step"     value = "database">
  <input type = "submit"   value = "Next">
</form>



<form method = "POST" id = "fields" autocomplete="off">
  <ul>
    <li>
      <input type = "text" value = "Email" disabled> <input type = "text" value = "Password" disabled>
    </li>
    <li>
      <input type = "text" name = "field[]" placeholder = "Full name" class = "field">
      <?php /* Full list http://www.php.net/manual/en/filter.filters.validate.php */ ?>
      <select name = "type[]" required>
        <option value = "text">Text</option>
        <option value = "longtext">Long text</option>
        <option value = "email">Email</option>
        <option value = "password">Password</option>
        <option value = "float">Number</option>
        <option value = "url">Url</option>
        <option value = "bool">Yes/no (bool)</option>
      </select>
      <label>
        <input type = "checkbox" name = "required[]" checked>
        Required
      </label>
    </li>
  </ul>
  <input type = "hidden" name = "step" value = "fields">
  <input type = "submit"   value = "Next">
</form>



<form method = "POST" id = "services" autocomplete="off">
  <p>
    Everything seems in order. Here'd go the "Services" form. Click "Finish" to complete the instalation.
  </p>
  <input type = "hidden" name = "step" value = "services">
  <input type = "submit"   value = "Finish">
</form>
</body>
</html>
