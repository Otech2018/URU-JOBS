<?php
  define("_VALID_PHP", true);
  require_once("init.php");

  if (isset($_GET['id']) && $_GET['id'] != ''){
      $id   = $_GET['id'];
      $company = $jobs->getSingleCompany($id);
  } else {
      redirect_to("index.php");
  }

?>
<?php require_once (THEMEDIR . "/company.tpl.php");?>
