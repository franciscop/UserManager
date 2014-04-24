<div class = "register">
  <h2>Register</h2>
  <form method = "POST">
    <p>
      <label>
        Email
        <input type = "email"    name = "email" placeholder = "johndoe@example.com" required>
      </label>
    </p>
    <p>
      <label>
        Password
        <input type = "password" name = "password" placeholder = "******" required>
      </label>
    </p>
    <?php foreach ($userConfig->userdata as $Name => $Field) : ?>
    <p>
      <label>
        <?= $Field['text']; ?>
        <input
          type = "<?= $Field['inputtype']; ?>"
          name = "<?= $Name; ?>"
          placeholder = "<?= $Field['text']; ?>"
          >
      </label>
    </p>
    <?php endforeach; ?>
    
    <!-- This should be ignored. It's here to trap the bots that try to register users -->
    <input type = "text" name = "nameneverused" required id = "nameneverused" placeholder = "Please ignore me and don't fill me">
    <script>
      var elementtoremove = document.getElementById("nameneverused");
      elementtoremove.parentNode.removeChild(elementtoremove);
    </script>
    
    <? if ($userConfig->tos) { ?>
    <p>
      <input type = "checkbox" required id = "TOSAgree">
      <label for = "TOSAgree">I agree with the <a href = "<?= $userConfig->tos; ?>" target = _blank>TOS</a></label>
    </p>
    <? } ?>
    
    <footer>
      <input type = "hidden" name = "register" value = "1">
      <button>Register</button>
      <button class = "LoginButton">Login ?</button>
    </footer>
  </form>
</div>
