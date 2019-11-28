<?php
  define("_VALID_PHP", true);
  require_once("init.php");

  $row = $content->renderPages();
?>
<!-- Start Content Page-->
<?php require_once (THEMEDIR . "/content.tpl.php");?>
<!-- End Content Page/-->
