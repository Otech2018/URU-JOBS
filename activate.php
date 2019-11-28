<?php
  define("_VALID_PHP", true);
  require_once("init.php");
  
  if ($user->logged_in)
      redirect_to("account.php");
?>
<?php require_once (THEMEDIR . "/activate.tpl.php");?>