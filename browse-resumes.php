<?php
  define("_VALID_PHP", true);
  require_once("init.php");

  $search   = ( isset($_GET['search']) && $_GET['search'] != '' ) ? $_GET['search'] : '';
  $short    = ( isset($_GET['short']) && $_GET['short'] != '' ) ? $_GET['short'] : '';
  $skills   = ( isset($_GET['skills']) && $_GET['skills'] != '' ) ? $_GET['skills'] : '';
  $city     = ( isset($_GET['city']) && $_GET['city'] != '' ) ? $_GET['city'] : '';
  $state    = ( isset($_GET['state']) && $_GET['state'] != '' ) ? $_GET['state'] : '';

  $resumes = $jobs->getResumes($search, $short, $skills, $city, $state);
?>
<?php require_once (THEMEDIR . "/browse-resumes.tpl.php");?>
