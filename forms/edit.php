<div class = "edit">
  <h2>Edit</h2>
  <form method = "POST">
    <p>
      <label for="Eemail">Email</label>
      <input id = "Eemail"    type = "email"    name = "email"     value = "<?= $User->email; ?>">
    </p>
    
    <p>
      <label for="Ename">First name</label>
      <input id = "Ename"     type = "text"     name = "firstname" value = "<?= $User->firstname; ?>">
    </p>
    
    <p>
      <label for="Elast">Last name</label>
      <input id = "Elast"     type = "text"     name = "lastname"  value = "<?= $User->lastname; ?>">
    </p>
    
    <footer>
      <button class = "SaveButton confirm" name = "edit">Save</button>
      <button class = "ProfileButton cancel">Cancel</button>
    </footer>
  </form>
</div>
