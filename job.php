<?php
  define("_VALID_PHP", true);
  require_once("init.php"); 
  $job = $jobs->getJob(Filter::$id);
?>
<?php require_once (THEMEDIR . "/job.tpl.php");?>