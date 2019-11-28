<?php
  define("_VALID_PHP", true);
  require_once("../init.php");
?>
<?php
  //$msgs = array();
  $post = (!empty($_POST)) ? true : false;

  if ($post) {
      if (empty($_POST['name']))
          Filter::$msgs['name'] = Lang::$word->PLG_CT_NAME;

      if (empty($_POST['captcha']))
          Filter::$msgs['code'] = Lang::$word->CAPTCHA_E1;

	  if ($_SESSION['captchacode'] != $_POST['captcha'])
          Filter::$msgs['code'] = Lang::$word->CAPTCHA_E2;

      if (empty($_POST['email']))
          Filter::$msgs['email'] = Lang::$word->EMAIL;

      if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $_POST['email']))
          Filter::$msgs['email'] = Lang::$word->EMAIL_R3;

      if (empty($_POST['message']))
          Filter::$msgs['message'] = Lang::$word->PLG_CT_MSG;

      if (empty(Filter::$msgs)) {

          $sender_email = sanitize($_POST['email']);
          $name = sanitize($_POST['name']);
          $message = strip_tags($_POST['message']);
		  $mailsubject = sanitize($_POST['subject']);
		  $ip = sanitize($_SERVER['REMOTE_ADDR']);

		  require_once(BASEPATH . "lib/class_mailer.php");
		  $mailer = Mailer::sendMail();

		  $row = Core::getRowById("email_templates", 10);

		  $body = str_replace(array('[MESSAGE]', '[SENDER]', '[NAME]', '[MAILSUBJECT]', '[IP]', '[SITE_NAME]', '[URL]'),
		  array($message, $sender_email, $name, $mailsubject, $ip, $core->site_name, SITEURL), $row->body);

		  $msg = Swift_Message::newInstance()
					->setSubject($row->subject)
					->setTo(array($core->site_email => $core->site_name))
					->setFrom(array($sender_email => $name))
					->setBody(cleanOut($body), 'text/html');


		  if ($mailer->send($msg)) {
			  $json['status'] = 'success';
			  $json['message'] = Filter::msgOk(Lang::$word->PLG_CT_OK, false);
			  print json_encode($json);
		  } else {
			  $json['message'] = Filter::msgAlert(Lang::$word->PLG_CT_ERR, false);
			  print json_encode($json);
		  }

      } else {
		  $json['message'] = Filter::msgStatus();
		  print json_encode($json);
	  }
  }
?>
