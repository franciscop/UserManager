<style>
  #UMForms button {
    width: 100%;
    padding: 3px 0;
    }

</style>
<div class = "settings">
  <h2>Settings</h2>
  <form method = "POST">
    <p>
	    <input type="checkbox" name="check" id = "Receive_News">
	    <label for = "Receive_News">Receive news</label>
    </p>
    
    <p>
      <button type="button" class = "PrivacyButton">Privacy</button>
    </p>
    
    <p>
      <button type="button" class = "ChangePassButton">Change password</button>
    </p>
    
    <p>
      <button type="button" class = "nosend">Cancel account</button>
    </p>
    
    <footer>
      <button class = "SaveButton confirm" name = "settings">Update</button>
      <button class = "ProfileButton cancel">Cancel</button>
    </footer>
  </form>
</div>

