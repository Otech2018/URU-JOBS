<?php
  define("_VALID_PHP", true);
  require_once("init.php");

  if (!$user->logged_in)
      redirect_to("login.php");

  $bookmarked_jobs = $jobs->getJobBookmarks();
  $bookmarked_resumes = $jobs->getResumeBookmarks();
?>
<?php require_once (THEMEDIR . "/bookmarks.tpl.php");?>
