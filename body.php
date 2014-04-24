<?php /* Load jquery if requested. */ ?>
<?php if ($UMConfig->jquery) { ?>
  <script type = "text/javascript" src ="//code.jquery.com/jquery-latest.min.js"></script>
<?php } ?>

<?php // Load stylesheet if requested. In body OK: http://stackoverflow.com/q/1642212 ?>
<?php if ($UMConfig->css) { ?>
  <link href="<?= $UMConfig->folder; ?>/style.css" rel="stylesheet" type="text/css">
<?php } ?>

<script src = "<?= $UMConfig->folder; ?>/javascript.js"></script>
<script>
  var folder = "<?= $userConfig->folder; ?>";
  var url = "<?= $url; ?>";
  <?php if (isset($userRegister)) { ?>
    $(document).ready(function(){
      completeregister("<?= $userRegister; ?>");
      });
    <?php } ?>
</script>

<?php // Load the forms ?>
<div id = "userForms">
  <?php include "forms/menubar.php"; ?>
  <?php if (isset($User)) { ?>
    <script>
      // Assign the User Name (the text to show inside the UserButton)
      UserName = "Profile";
      // Assign the email of the current user
      email = "<?= $User->email; ?>";
    </script>
    <?php // Load the login and register forms ?>
    <!-- Profile -->
    <?php include "forms/profile.php"; ?>
    <!-- Edit profile -->
    <?php include "forms/edit.php"; ?>
    <!-- Settings -->
    <?php include "forms/settings.php"; ?>
  <?php }
  else { ?>
    <!-- Login -->
    <?php include "forms/login.php"; ?>
    <!-- Register -->
    <?php include "forms/register.php"; ?>
    <!-- Recover password -->
    <?php include "forms/forgot.php"; ?>
    <!-- Logged in -->
    <?php include "forms/logged.php"; ?>
  <?php } ?>
</div>
