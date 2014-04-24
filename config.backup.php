<?php
class userConfig {
  private $dbhost = "";
  private $db     = "";
  private $dbuser = "";
  private $dbpass = "";
  
  // The data that will be stored and available from each user
  private $userdata = array(
    "first_name"   => array("text" => "First name"  , "type" => "text" , "inputtype" => "text"  , "required" => 1),
    "last_name"    => array("text" => "Last name"   , "type" => "text" , "inputtype" => "text"  , "required" => 0),
    "phone_number" => array("text" => "Phone number", "type" => "float", "inputtype" => "number", "required" => 0)
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
  private $url = "http://francisco.io/";
  
  // Set the path of the user manager
  private $path;
  
  // Set where the error should be append
  private $error_log;
  
  // TODO: a compatibility list.
  // The services you want to allow your user to login with.
  private $services = array ("persona", "facebook", "google");
  
  // Required by password_compat.
  private $cost     = 10;
  
  private $facebook = array(
    "appId"  => "",
    "secret" => ""
    );
  
  private $google = array(
    "client_id"  => "",
    "client_secret" => "",
    "email_address" => ""
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
    // Set the path if it wasn't set
    if (empty($this->path))
      $this->path = dirname(__FILE__);
    
    if (empty($this->error_log))
      $this->error_log = dirname(dirname(__FILE__)) . "/exception_log";
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
  
