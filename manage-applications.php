<?php
  define("_VALID_PHP", true);
  require_once("init.php");

  if (!$user->logged_in && $user->userlevel != 2)
      redirect_to("login.php");

  if ( !isset($_GET['jobid']) || $_GET['jobid'] <= 0 )
      redirect_to("manage-jobs.php");

  $jobid = (isset($_GET['jobid']) && $_GET['jobid'] != '') ? $_GET['jobid'] : '';
  $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';
  $order = (isset($_GET['order']) && $_GET['order'] != '') ? $_GET['order'] : '';
  $applications = $jobs->getJobApplications($jobid,$status,$order);
?>
<?php require_once (THEMEDIR . "/manage-applications.tpl.php");?>
