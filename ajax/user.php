<?php
  define("_VALID_PHP", true);
  require_once("../init.php");
?>
<?php
  /* Password Reset */
  if (isset($_POST['passReset'])):
      $user->passReset();
  endif;

  /* Account Acctivation */
  if (isset($_POST['accActivate'])):
      $user->activateUser();
  endif;
?>