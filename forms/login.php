<div class = "login">
  <h2>Login with</h2>

  <!-- Don't touch this. Buttons for services login will appear here -->
  <div class = "services">
    <?php foreach($userConfig->services as $Service): ?>
      <img
        id = "<?= $Service; ?>LoginButton"
        src = "<?= $userConfig->folder; ?>/services/<?= $Service; ?>/logo.svg"
        alt = "<?= $Service; ?> login"
        title = "Click this button to login with <?= $Service; ?>"
        data-service = "<?= $Service; ?>"
        >
      <? endforeach; ?>
  </div>
  
  <div class="line">
    <span>OR</span>
  </div>
  
  <!-- Default traditional login -->
  <form method = "POST">
    
    <p>
      <label for="Lemail">Email</label>
      <input required id = "UMEmail"    type = "email"    name = "email"    placeholder = "youremail@example.com">
    </p>
    
    <p>
      <label for="Lpassword">Password</label><a class = "ForgotButton">Forgot</a>
      <input required id = "UMPassword" type = "password" name = "password" placeholder = "********">
    </p>
    
    <footer>
      <input type = "hidden" name = "login" value = "1">
      <button>Login</button>
      <button class = "RegisterButton">Register ?</button>
    </footer>
  </form>
</div>
