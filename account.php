<?php
  define("_VALID_PHP", true);
  require_once("init.php");

  if (!$user->logged_in)
      redirect_to("index.php");

    if ($user->logged_in && $user->userlevel == 1):
        require_once (THEMEDIR . "/seeker.tpl.php");
    elseif($user->logged_in && $user->userlevel == 2):
        $itemrow = $jobs->getUserTransactions();
        require_once (THEMEDIR . "/employer.tpl.php");
    endif;
