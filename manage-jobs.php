<?php
  define("_VALID_PHP", true);
  require_once("init.php");

  if (!$user->logged_in && $user->userlevel != 2)
      redirect_to("login.php");
    
  $jobs = $jobs->getEmployerJobs();
?>
<?php require_once (THEMEDIR . "/manage-jobs.tpl.php");?>