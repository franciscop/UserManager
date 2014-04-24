<div class = "profile">
  <h2>Profile</h2>
  
  <p><?= $User->firstname; ?> <?= $User->lastname; ?></p>
  
  <p>
    <button onclick = "alert('Not yet!')">Messages</button>
  </p>
  
  <p>
    <button onclick = "alert('Try next one (;');">Notifications</button>
  </p>
  
  <footer>
    <button class = "SettingsButton">Settings</button>
    <button class = "EditButton">Edit</button>
    <form method = "POST">
      <input type = "hidden" name = "logout" value = "1">
      <button class = "LogoutButton cancel">Log out</button>
    </form>
  </footer>
</div>
