<?php
  define("_VALID_PHP", true);
  require_once("init.php");

  if (!$user->logged_in)
      redirect_to("index.php");

  /* == Proccess Message == */
  if (isset($_POST['processMessage']))
      : if (intval($_POST['processMessage']) == 0 || empty($_POST['processMessage']))
      : die();
  endif;
  Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
  $content->processMessage();
  endif;

  /* == Delete Message == */
  if (isset($_POST['deleteMessage']))
      : if (intval($_POST['deleteMessage']) == 0 || empty($_POST['deleteMessage']))
      : die();
  endif;

  list($id, $uid) = explode(":", $_POST['deleteMessage']);

  $res = $db->delete("messages", "id='" . (int)$id . "'");
  $db->delete("messages", "uid1='" . (int)$uid . "'");

  $title = sanitize($_POST['title']);

  print ($res) ? Filter::msgOk(str_replace("[MESSAGE]", $title, lang('MSG_DELETE_OK'))) : Filter::msgAlert(lang('NOPROCCESS'));
  endif;

  require_once (THEMEDIR . "/messages.tpl.php");
