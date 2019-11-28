<?php
  define("_VALID_PHP", true);
  require_once("init.php");

  if (!$user->logged_in || $user->userlevel != 2)
      redirect_to(SITEURL ."/login.php");

  if (isset($_POST['addJob'])){
    $pmsg = $jobs->addJob();

  	if( is_int($pmsg) == true ){
  		redirect_to("manage-jobs.php?jobid=$pmsg");
  	}
  }
?>
<?php require_once (THEMEDIR . "/add-job.tpl.php");?>
