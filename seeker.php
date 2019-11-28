<?php
  define("_VALID_PHP", true);
  require_once("init.php");
  
  if (!$user->logged_in)
      redirect_to("index.php");
?>
<?php require_once (THEMEDIR . "/seeker.tpl.php");?>