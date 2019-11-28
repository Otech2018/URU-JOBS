<?php
  define("_VALID_PHP", true);
  require_once("init.php");

  if (!$user->logged_in)
      redirect_to("login.php");

	/* Add Resume */
  if (isset($_POST['updateResume'])){
    $pmsg = $user->updateResume();
    //redirect_to("seeker.php");
  }
  $row = $user->getResume();
  $datacountry = $content->getCountryList();
?>
<?php require_once (THEMEDIR . "/add-resume.tpl.php");?>
