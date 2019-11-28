<?php
  define("_VALID_PHP", true);
  require_once("init.php");

  if (!$user->logged_in)
      redirect_to("login.php");
    
	/* Add Resume */
  if (isset($_POST['updateCompany'])){
    $pmsg = $user->updateCompany();
  }
  $row = $user->getCompany();
?>
<?php require_once (THEMEDIR . "/company-settings.tpl.php");?>