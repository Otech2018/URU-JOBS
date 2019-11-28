<?php
  define("_VALID_PHP", true);
  require_once("init.php");

  $search   = ( isset($_GET['search']) && $_GET['search'] != '' ) ? $_GET['search'] : '';
  $short    = ( isset($_GET['short']) && $_GET['short'] != '' ) ? $_GET['short'] : '';
  $type   = ( isset($_GET['type']) && $_GET['type'] != '' ) ? $_GET['type'] : '';
  $city     = ( isset($_GET['city']) && $_GET['city'] != '' ) ? $_GET['city'] : '';
  $state    = ( isset($_GET['state']) && $_GET['state'] != '' ) ? $_GET['state'] : '';

  $alljobs = $jobs->getJobs($search, $short, $type, $city, $state);
?>
<?php require_once (THEMEDIR . "/browse-jobs.tpl.php");?>
