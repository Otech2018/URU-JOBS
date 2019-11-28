<?php
  define("_VALID_PHP", true);
  require_once("init.php");

  if (isset($_GET['resumeid']) && $_GET['resumeid'] != ''){
      $id   = $_GET['resumeid'];
      $resume = $jobs->getSingleResume($id);
  } else {
      redirect_to("index.php");
  }

?>
<?php require_once (THEMEDIR . "/resume.tpl.php");?>
