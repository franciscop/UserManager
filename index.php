<?php
if (!file_exists("config.php"))
  {
  header("Location: install.php");
  exit;
  }

include "include.php";

// Get the number of users registered
$Data['users'] = $userDB->query('SELECT COUNT(*) FROM users')->fetchColumn();
  ?>
<!DOCTYPE html>
<html>
  <head>
  <meta charset='utf-8'>
  <script src = "http://code.jquery.com/jquery-latest.min.js"></script>
  <script>
    
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
    
    h1 {
      text-align: center;
      margin: 0;
      padding: 60px 0 20px;
      }
  </style>
  </head>
  <body>
    <?php include $userBody; ?>
    <h1>Admin area</h1>
    Registered users: <?= $Data['users']; ?>
    <a class = "UserButton">Log in</a>
    <ul>
      <li>
        <form>
          <label>
            Block a user:
            <input type = "text">
          </label>
        </form>
      </li>
    </ul>
  </body>
</html>
