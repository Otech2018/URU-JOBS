<?php
  define("_VALID_PHP", true);
  require_once("init.php");

  $home = $content->getHomePage();
  $news = $content->renderNews();
?>
<?php require_once (THEMEDIR . "/index.tpl.php");?>
