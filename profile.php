<?php
  define("_VALID_PHP", true);
  require_once("init.php");
  
  if (!$user->logged_in)
      redirect_to("index.php");
	  
  if (isset($_POST['processUser'])):
      $pmsg = $user->updateProfile();
  endif;
  
  $row = $user->getUserData();
  $datacountry = $content->getCountryList();
?>
<?php require_once (THEMEDIR . "/profile.tpl.php");?>