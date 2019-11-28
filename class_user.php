<?php

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  class Users
  {
	  const uTable = "users";

      public $logged_in = null;
      public $uid = 0;
      public $userid = 0;
      public $username;
      public $sesid;
      public $email;
      public $name;
	  public $fname;
	  public $lname;
	  public $avatar;
	  public $country;
      public $userlevel;
      private $lastlogin = "NOW()";
	  public $last;

	  private static $db;


      /**
       * Users::__construct()
       *
       * @return
       */
      function __construct()
      {
		  self::$db = Registry::get("Database");
		  $this->startSession();
      }


      /**
       * Users::startSession()
       *
       * @return
       */
      private function startSession()
      {
		session_start();

		$this->logged_in = $this->loginCheck();

		if (!$this->logged_in) {
			$this->username = $_SESSION['DDP_USERNAME'] = "Guest";
			$this->sesid = sha1(session_id());
			$this->userlevel = 0;
		}
      }

	  /**
	   * Users::loginCheck()
	   *
	   * @return
	   */
	  private function loginCheck()
	  {

          if (isset($_SESSION['DDP_USERNAME']) && $_SESSION['DDP_USERNAME'] != "Guest") {

              $row = $this->getUserInfo($_SESSION['DDP_USERNAME']);
              $this->uid = $row->id;
              $this->username = $row->username;
              $this->email = $row->email;
              $this->name = $row->fname . ' ' . $row->lname;
			  $this->fname = $row->fname;
			  $this->lname = $row->lname;
              $this->userlevel = $row->userlevel;
			  $this->avatar = $row->avatar;
			  $this->country = $row->country;
              $this->sesid = sha1(session_id());
			  $this->last = $row->lastlogin;
              return true;
          } else {
              return false;
          }
	  }

	  /**
	   * Users::is_Admin()
	   *
	   * @return
	   */
	  public function is_Admin()
	  {
		  return($this->userlevel == 9);

	  }

	  /**
	   * Users::login()
	   *
	   * @param mixed $username
	   * @param mixed $pass
	   * @return
	   */
	  public function login($username, $pass)
	  {

		  if ($username == "" && $pass == "") {
			  Filter::$msgs['username'] = Lang::$word->LOGIN_R5;
		  } else {
			  $status = $this->checkStatus($username, $pass);

			  switch ($status) {
				  case 0:
					  Filter::$msgs['username'] = Lang::$word->LOGIN_R1;
					  break;

				  case 1:
					  Filter::$msgs['username'] = Lang::$word->LOGIN_R2;
					  break;

				  case 2:
					  Filter::$msgs['username'] = Lang::$word->LOGIN_R3;
					  break;

				  case 3:
					  Filter::$msgs['username'] = Lang::$word->LOGIN_R4;
					  break;
			  }
		  }
		  if (empty(Filter::$msgs) && $status == 5) {
			  $row = $this->getUserInfo($username);
			  $this->uid = $_SESSION['userid'] = $row->id;
			  $this->username = $_SESSION['DDP_USERNAME'] = $row->username;
			  $this->email = $_SESSION['email'] = $row->email;
			  $this->name = $_SESSION['name'] = $row->fname . ' ' . $row->lname;
			  $this->userlevel = $_SESSION['userlevel'] = $row->userlevel;
			  $this->avatar = $_SESSION['avatar'] = $row->avatar;
			  $this->country = $_SESSION['avatar'] = $row->country;
			  $this->last = $_SESSION['last'] = $row->lastlogin;

			  $data = array(
					'lastlogin' => $this->lastlogin,
					'lastip' => sanitize($_SERVER['REMOTE_ADDR'])
			  );
			  self::$db->update(self::uTable, $data, "username='" . $this->username . "'");

			  return true;
		  } else
			  Filter::msgStatus();
	  }

      /**
       * Users::logout()
       *
       * @return
       */
      public function logout()
      {

          unset($_SESSION['DDP_USERNAME']);
		  unset($_SESSION['email']);
		  unset($_SESSION['name']);
		  unset($_SESSION['userid']);
          unset($_SESSION['uid']);
          session_destroy();
		  session_regenerate_id();

          $this->logged_in = false;
          $this->username = "Guest";
          $this->userlevel = 0;
      }

	  /**
	   * Users::getUserInfo()
	   *
	   * @param mixed $username
	   * @return
	   */
	  private function getUserInfo($username)
	  {
		  $username = sanitize($username);
		  $username = self::$db->escape($username);

		  $sql = "SELECT *, CONCAT(fname,' ',lname) as fullname  FROM " . self::uTable . " WHERE username = '" . $username . "'";
		  $row = self::$db->first($sql);
		  if (!$username)
			  return false;

		  return ($row) ? $row : 0;
	  }

	  /**
	   * Users::checkStatus()
	   *
	   * @param mixed $username
	   * @param mixed $pass
	   * @return
	   */
	  public function checkStatus($username, $pass)
	  {
		  $username = sanitize($username);
		  $username = self::$db->escape($username);
		  $pass = sanitize($pass);

          $sql = "SELECT password, active FROM " . self::uTable
		  . "\n WHERE username = '".$username."'";
          $result = self::$db->query($sql);

		  if (self::$db->numrows($result) == 0)
			  return 0;

		  $row = self::$db->fetch($result);
		  $entered_pass = sha1($pass);

          switch ($row->active) {
              case "b":
                  return 1;
                  break;

              case "n":
                  return 2;
                  break;

              case "t":
                  return 3;
                  break;

              case "y" && $entered_pass == $row->password:
                  return 5;
                  break;
		  }
	  }

	  /**
	   * Users::getUsers()
	   *
	   * @param bool $from
	   * @return
	   */
	  public function getUsers($from = false)
	  {
		  $usertypes= array(
			'admin'		=> 9,
			'employer'	=> 2,
			'seeker'	=> 1
		  );
		  
		  $usertypecon = '';
		  $usertypecon2 = '';
		  
		  if( isset($_GET['usertype']) && ( $_GET['usertype'] == 'admin' || $_GET['usertype'] == 'employer' || $_GET['usertype'] == 'seeker')){
			  $usertypecon = " AND userlevel=" . $usertypes[$_GET['usertype']];
			  $usertypecon2 = " WHERE userlevel=" . $usertypes[$_GET['usertype']];
		  }
		  
		  if (isset($_GET['letter']) and (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '')) {
			  $enddate = date("Y-m-d");
			  $letter = sanitize($_GET['letter'], 2);
			  $fromdate = (empty($from)) ? $_POST['fromdate_submit'] : $from;
			  if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
				  $enddate = $_POST['enddate_submit'];
			  }
			  $q = "SELECT COUNT(*) FROM " . self::uTable . " WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'"
			  . "\n AND username REGEXP '^" . self::$db->escape($letter) . "'" . $usertypecon;
			  $where = " WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND username REGEXP '^" . self::$db->escape($letter) . "'" . $usertypecon;

		  } elseif (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '') {
			  $enddate = date("Y-m-d");
			  $fromdate = (empty($from)) ? $_POST['fromdate_submit'] : $from;
			  if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
				  $enddate = $_POST['enddate_submit'];
			  }
			  $q = "SELECT COUNT(*) FROM " . self::uTable . " WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'" . $usertypecon;
			  $where = " WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'" . $usertypecon;

		  } elseif(isset($_GET['letter'])) {
			  $letter = sanitize($_GET['letter'], 2);
			  $where = "WHERE username REGEXP '^" . self::$db->escape($letter) . "'" . $usertypecon;
			  $q = "SELECT COUNT(*) FROM " . self::uTable . " WHERE username REGEXP '^" . self::$db->escape($letter) . "' LIMIT 1" . $usertypecon;
		  } else {
			  $q = "SELECT COUNT(*) FROM " . self::uTable . "" . $usertypecon2 . " LIMIT 1";
			  $where = $usertypecon2;
		  }

          $record = self::$db->query($q);
          $total = self::$db->fetchrow($record);
          $counter = $total[0];

		  $pager = Paginator::instance();
		  $pager->items_total = $counter;
		  $pager->default_ipp = Registry::get("Core")->perpage;
		  $pager->paginate();

          $sql = "SELECT *, CONCAT(fname,' ',lname) as name,"
		  . "\n (SELECT COUNT(transactions.uid) FROM transactions WHERE transactions.uid = users.id) as totalitems"
		  . "\n FROM " . self::uTable
		  . "\n $where"
		  . "\n ORDER BY created DESC" . $pager->limit;
          $row = self::$db->fetch_all($sql);

		  return ($row) ? $row : 0;
	  }

	  /**
	   * Users::processUser()
	   *
	   * @return
	   */
	  public function processUser()
	  {

          if (!Filter::$id) {
              Filter::checkPost('username', Lang::$word->USERNAME);

              if ($value = $this->usernameExists($_POST['username'])) {
                  if ($value == 1)
                      Filter::$msgs['username'] = Lang::$word->USERNAME_R2;
                  if ($value == 2)
                      Filter::$msgs['username'] = Lang::$word->USERNAME_R3;
                  if ($value == 3)
                      Filter::$msgs['username'] = Lang::$word->USERNAME_R4;
              }
          }

          Filter::checkPost('fname', Lang::$word->FNAME);
          Filter::checkPost('lname', Lang::$word->LNAME);

          if (!Filter::$id) {
              Filter::checkPost('password', Lang::$word->PASSWORD);
          }

          Filter::checkPost('email', Lang::$word->EMAIL);
          if (!Filter::$id) {
              if ($this->emailExists($_POST['email']))
                  Filter::$msgs['email'] = Lang::$word->EMAIL_R2;
          }
          if (!$this->isValidEmail($_POST['email']))
              Filter::$msgs['email'] = Lang::$word->EMAIL_R3;

          if (!empty($_FILES['avatar']['name'])) {
              if (!preg_match("/(\.jpg|\.png)$/i", $_FILES['avatar']['name'])) {
                  Filter::$msgs['avatar'] = Lang::$word->CONF_LOGO_R;
              }
              $file_info = getimagesize($_FILES['avatar']['tmp_name']);
              if (empty($file_info))
                  Filter::$msgs['avatar'] = Lang::$word->CONF_LOGO_R;
          }

		  if (empty(Filter::$msgs)) {

			  $data = array(
				  'username' => sanitize($_POST['username']),
				  'email' => sanitize($_POST['email']),
				  'lname' => sanitize($_POST['lname']),
				  'fname' => sanitize($_POST['fname']),
				  'notes' => sanitize($_POST['notes']),
				  'info' => sanitize($_POST['info']),
				  'newsletter' => intval($_POST['newsletter']),
				  'userlevel' => intval($_POST['userlevel']),
				  'active' => sanitize($_POST['active'])
			  );

              if (!Filter::$id)
                  $data['created'] = "NOW()";

              if (Filter::$id)
                  $userrow = Registry::get("Core")->getRowById(self::uTable, Filter::$id);

              if ($_POST['password'] != "") {
                  $data['password'] = sha1($_POST['password']);
              } else {
                  $data['password'] = $userrow->password;
              }

              // Procces Avatar
              if (!empty($_FILES['avatar']['name'])) {
                  $thumbdir = UPLOADS . "avatars/";
                  $tName = "AVT_" . randName();
                  $text = substr($_FILES['avatar']['name'], strrpos($_FILES['avatar']['name'], '.') + 1);
                  $thumbName = $thumbdir . $tName . "." . strtolower($text);
                  if (Filter::$id && $thumb = getValueById("avatar", self::uTable, Filter::$id)) {
                      @unlink($thumbdir . $thumb);
                  }
                  move_uploaded_file($_FILES['avatar']['tmp_name'], $thumbName);
                  $data['avatar'] = $tName . "." . strtolower($text);
              }

              (Filter::$id) ? self::$db->update(self::uTable, $data, "id=" . Filter::$id) : self::$db->insert(self::uTable, $data);
              $message = (Filter::$id) ? Lang::$word->USR_UPDATED : Lang::$word->USR_ADDED;

              if (self::$db->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
				  print json_encode($json);

                  if (isset($_POST['notify']) && intval($_POST['notify']) == 1) {
                      require_once (BASEPATH . "lib/class_mailer.php");
                      $mailer = Mailer::sendMail();

                      $row = Registry::get("Core")->getRowById(Content::eTable, 3);

                      $body = str_replace(array(
                          '[USERNAME]',
                          '[PASSWORD]',
                          '[NAME]',
                          '[SITE_NAME]',
                          '[URL]'), array(
                          $data['username'],
                          $_POST['password'],
                          $data['fname'] . ' ' . $data['lname'],
                          Registry::get("Core")->site_name,
                          SITEURL), $row->body);

                      $msg = Swift_Message::newInstance()
								->setSubject($row->subject)
								->setTo(array($data['email'] => $data['fname'] . ' ' . $data['lname']))
								->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->site_name))
								->setBody(cleanOut($body), 'text/html');

                      $mailer->send($msg);
                  }
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
				  print json_encode($json);
			  }

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
	  }
	  /**
	   * Users::updateProfile()
	   *
	   * @return
	   */
	  public function updateProfile()
	  {
          Filter::checkPost('fname', Lang::$word->FNAME);
          Filter::checkPost('lname', Lang::$word->LNAME);
          Filter::checkPost('email', Lang::$word->EMAIL);

		  Filter::checkPost('address', Lang::$word->_UA_ADDRESS);
		  Filter::checkPost('city', Lang::$word->_UA_CITY);
		  Filter::checkPost('state', Lang::$word->_UA_STATE);
		  Filter::checkPost('zip', Lang::$word->_UA_ZIP);
		  Filter::checkPost('country', Lang::$word->_UA_COUNTRY);

          if (!$this->isValidEmail($_POST['email']))
              Filter::$msgs['email'] = Lang::$word->EMAIL_R3;

          if (!empty($_FILES['avatar']['name'])) {
              if (!preg_match("/(\.jpg|\.png)$/i", $_FILES['avatar']['name'])) {
                  Filter::$msgs['avatar'] = Lang::$word->CONF_LOGO_R;
              }
              $file_info = getimagesize($_FILES['avatar']['tmp_name']);
              if (empty($file_info))
                  Filter::$msgs['avatar'] = Lang::$word->CONF_LOGO_R;
          }

          if (empty(Filter::$msgs)) {

              $data = array(
                  'email' => sanitize($_POST['email']),
                  'lname' => sanitize($_POST['lname']),
                  'fname' => sanitize($_POST['fname']),
				  'address' => sanitize($_POST['address']),
				  'city' => sanitize($_POST['city']),
				  'country' => sanitize($_POST['country']),
				  'state' => sanitize($_POST['state']),
				  'zip' => sanitize($_POST['zip']),
                  'newsletter' => intval($_POST['newsletter'])
				  );

              $userpass = getValueById("password", self::uTable, $this->uid);

              if ($_POST['password'] != "") {
                  $data['password'] = sha1($_POST['password']);
              } else
                  $data['password'] = $userpass;

              // Procces Avatar
              if (!empty($_FILES['avatar']['name'])) {
                  $thumbdir = UPLOADS . "avatars/";
                  $tName = "AVT_" . randName();
                  $text = substr($_FILES['avatar']['name'], strrpos($_FILES['avatar']['name'], '.') + 1);
                  $thumbName = $thumbdir . $tName . "." . strtolower($text);
                  if (Filter::$id && $thumb = getValueById("avatar", self::uTable, Filter::$id)) {
                      @unlink($thumbdir . $thumb);
                  }
                  move_uploaded_file($_FILES['avatar']['tmp_name'], $thumbName);
                  $data['avatar'] = $tName . "." . strtolower($text);
              }

              self::$db->update(self::uTable, $data, "id=" . $this->uid);

			  if (self::$db->affected()) {
				  $pmsg = '<div class="pmsg psuccess"><i class="fa fa-check"></i> ' . Lang::$word->_UA_PROFILE_OK . '</div>';
			  } else {
				  $pmsg = '<div class="pmsg pwarning"><i class="fa fa-exclamation-triangle"></i> ' . Lang::$word->NOPROCCESS . '</div>';
			  }
			 return $pmsg;

		  } else {
			  $pmsg = Filter::msgStatus();
			  return $pmsg;
		  }
	  }

      /**
       * User::register()
       *
       * @return
       */
	  public function register()
	  {
	  
	  

          Filter::checkPost('username', Lang::$word->USERNAME);

          if ($value = $this->usernameExists($_POST['username'])) {
              if ($value == 1)
                  Filter::$msgs['username'] = Lang::$word->USERNAME_R2;
              if ($value == 2)
                  Filter::$msgs['username'] = Lang::$word->USERNAME_R3;
              if ($value == 3)
                  Filter::$msgs['username'] = Lang::$word->USERNAME_R4;
          }
		
	  
	
          Filter::checkPost('fname', Lang::$word->FNAME);
          Filter::checkPost('lname', Lang::$word->LNAME);
	  //Filter::checkPost('address', Lang::$word->_UA_ADDRESS);
	  //Filter::checkPost('city', Lang::$word->_UA_CITY);
		  //Filter::checkPost('state', Lang::$word->_UA_STATE);
		  //Filter::checkPost('zip', Lang::$word->_UA_ZIP);
		  //Filter::checkPost('country', Lang::$word->_UA_COUNTRY);
          Filter::checkPost('pass', Lang::$word->PASSWORD);


          if (strlen($_POST['pass']) < 6)
              Filter::$msgs['pass'] = Lang::$word->PASSWORD_T2;
		  elseif (!preg_match("/^.*(?=.{6,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", ($_POST['pass'] = trim($_POST['pass']))))
              Filter::$msgs['pass'] = Lang::$word->PASSWORD_R2;
          elseif ($_POST['pass'] != $_POST['pass2'])
              Filter::$msgs['pass'] = Lang::$word->PASSWORD_R3;

          Filter::checkPost('email', Lang::$word->EMAIL);

          if ($this->emailExists($_POST['email']))
              Filter::$msgs['email'] = Lang::$word->EMAIL_R2;

          if (!$this->isValidEmail($_POST['email']))
              Filter::$msgs['email'] = Lang::$word->EMAIL_R3;

		  Filter::checkPost('captcha', Lang::$word->CAPTCHA);

		  if ($_SESSION['captchacode'] != $_POST['captcha'])
			  Filter::$msgs['captcha'] = Lang::$word->CAPTCHA_E2;

		  if (empty(Filter::$msgs)) {

              $token = (Registry::get("Core")->reg_verify == 1) ? $this->generateRandID() : 0;
              $pass = sanitize($_POST['pass']);

              if (Registry::get("Core")->reg_verify == 1) {
                 // $active = "t";
                 $active = "t";
              } elseif (Registry::get("Core")->auto_verify == 0) {
               //   $active = "n";
                $active = "t";
              } else {
                  $active = "t";
              }

			  $data = array(
					  'username' => sanitize($_POST['username']),
					  'password' => sha1($_POST['pass']),
					  'email' => sanitize($_POST['email']),
					  'fname' => sanitize($_POST['fname']),
					  'lname' => sanitize($_POST['lname']),
					  'address' => sanitize($_POST['address']),
					  'city' => sanitize($_POST['city']),
					  'country' => sanitize($_POST['country']),
					  'state' => sanitize($_POST['state']),
					  'zip' => sanitize($_POST['zip']),
					  'token' => $token,
					  'active' => $active,
					  'userlevel' => intval($_POST['userlevel']),
					  'created' => "NOW()"
			  );
			  
			  print_r($data);
			  //die();

			  self::$db->insert(self::uTable, $data);
			  echo "<script> alert('Account Registered Successfully!!!');  </script>";  
			  $lastid = self::$db->insertid();
			  if( intval($_POST['userlevel']) == 2 ){
				$data1 = array(
					'uid' => $lastid,
					'created' => "NOW()"
				);
				self::$db->insert('companies', $data1);
			  } else if( intval($_POST['userlevel']) == 1 ){
				$data1 = array(
					'uid' => $lastid,
					'created' => "NOW()"
				);
				self::$db->insert('resumes', $data1);
			  }
		/***/  
			  
			$mail_uru = $_POST['email'];
  			$user_name = $_POST['pass'];
  			$pass_word = $_POST['username'];
			$user_activation_code=  md5($pass_word);
			  $base_url = "http://urujobs.tech/";  //change this baseurl value as per your file path
			$mail_body = "
			<p>Hi ". $user_name." ,</p>
			<h3>Thank You For Registering at <a href='http://urujob.tech'>URUJOBS</a></h3>
			<p>Please Open this link to verify your email address - ".$base_url."uru101jobs.php?psps=".$user_activation_code."&hashem=".$mail_uru."
			<p>Best Regards,<br /><b>Uru Jobs</b></p>
			";
			require 'class/class.phpmailer.php';
			$mail = new PHPMailer;
			$mail->IsSMTP();								//Sets Mailer to send message using SMTP
			$mail->Host = 'mail.vps44038.mylogin.co';		//Sets the SMTP hosts of your Email hosting, this for Godaddy
			$mail->Port = '25';								//Sets the default SMTP server port
			$mail->SMTPAuth = true;							//Sets SMTP authentication. Utilizes the Username and Password variables
			$mail->Username = 'support@urujobs.tech';					//Sets SMTP username
			$mail->Password = 'VTTrader@101';					//Sets SMTP password
			$mail->SMTPSecure = '';							//Sets connection prefix. Options are "", "ssl" or "tls"
			$mail->From = 'support@urujobs.tech';			//Sets the From email address for the message
			$mail->FromName = 'UruJobs';					//Sets the From name of the message
			$mail->AddAddress($mail_uru, $username);		//Adds a "To" address			
			$mail->WordWrap = 50;			//Sets word wrapping on the body of the message to a given number of characters
			$mail->IsHTML(true);							//Sets message type to HTML				
			$mail->Subject = 'Email Verification';			//Sets the Subject of the message
			$mail->Body = $mail_body;							//An HTML or plain text message body
			$mail->Send();
			
			
			/***
			  require_once(BASEPATH . "lib/class_mailer.php");
			 
			  
			  echo "<script> alert('Account Registered Successfully!!!');  </script>"; 
			  
		
              if (Registry::get("Core")->reg_verify == 1) {
              
		  $actlink = SITEURL . "/activate.php?token=" . $token . "&email=" . $data['email'];
                  $row = Registry::get("Core")->getRowById(Content::eTable, 1);
                  

                  $body = str_replace(array(
                      '[NAME]',
                      '[USERNAME]',
                      '[PASSWORD]',
                      '[TOKEN]',
                      '[EMAIL]',
                      '[URL]',
                      '[LINK]',
                      '[SITE_NAME]'), array(
                      $data['fname'] . ' ' . $data['lname'],
                      $data['username'],
                      $_POST['pass'],
                      $token,
                      $data['email'],
                      SITEURL,
                      $actlink,
                      Registry::get("Core")->site_name), $row->body);

                  $newbody = cleanOut($body);
                  return "success";
		  
                  $mailer = Mailer::sendMail();
                 
                  $message = Swift_Message::newInstance()
							->setSubject($row->subject)
							->setTo(array($data['email'] => $data['username']))
							->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->site_name))
							->setBody($newbody, 'text/html');

                  $mailer->send($message);
                  
                 

              } elseif (Registry::get("Core")->auto_verify == 0) {
                  $row = Registry::get("Core")->getRowById(Content::eTable, 14);

                  $body = str_replace(array(
                      '[NAME]',
                      '[USERNAME]',
                      '[PASSWORD]',
                      '[URL]',
                      '[SITE_NAME]'), array(
                      $data['fname'] . ' ' . $data['lname'],
                      $data['username'],
                      $_POST['pass'],
                      SITEURL,
                      Registry::get("Core") > site_name), $row->body);

                  $newbody = cleanOut($body);

                  $mailer = Mailer::sendMail();
                  $message = Swift_Message::newInstance()
							->setSubject($row->subject)
							->setTo(array($data['email'] => $data['username']))
							->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->site_name))
							->setBody($newbody, 'text/html');

                  $mailer->send($message);

			  } else {
                  $row = Registry::get("Core")->getRowById(Content::eTable, 7);

                  $body = str_replace(array(
                      '[NAME]',
                      '[USERNAME]',
                      '[PASSWORD]',
                      '[URL]',
                      '[SITE_NAME]'), array(
                      $data['fname'] . ' ' . $data['lname'],
                      $data['username'],
                      $_POST['pass'],
                      SITEURL,
                      Registry::get("Core")->site_name), $row->body);

                  $newbody = cleanOut($body);

                  $mailer = Mailer::sendMail();
                  $message = Swift_Message::newInstance()
							->setSubject($row->subject)
							->setTo(array($data['email'] => $data['username']))
							->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->site_name))
							->setBody($newbody, 'text/html');

                  $mailer->send($message);
			  }
			  
			 
              if (Registry::get("Core")->notify_admin) {
                  $arow = Registry::get("Core")->getRowById(Content::eTable, 13);

                  $abody = str_replace(array(
                      '[USERNAME]',
                      '[EMAIL]',
                      '[NAME]',
                      '[IP]'), array(
                      $data['username'],
                      $data['email'],
                      $data['fname'] . ' ' . $data['lname'],
                      $_SERVER['REMOTE_ADDR']), $arow->body);

                  $anewbody = cleanOut($abody);

                  $amailer = Mailer::sendMail();
                  $amessage = Swift_Message::newInstance()
							->setSubject($arow->subject)
							->setTo(array(Registry::get("Core")->site_email => Registry::get("Core")->site_name))
							->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->site_name))
							->setBody($anewbody, 'text/html');

                  $amailer->send($amessage);
              }
              ***/

			  if (self::$db->affected()) {
				  $pmsg = '<div class="pmsg psuccess"><i class="fa fa-check"></i> ' . Lang::$word->_UA_ACCOK . '</div>';
			  } else {
				  $pmsg = '<div class="pmsg pwarning"><i class="fa fa-exclamation-triangle"></i> ' . Lang::$word->_UA_PASSR_ERR . '</div>';
			  }
			 return "success";

		  } else {
			  $pmsg = Filter::msgStatus();
			  return $pmsg;
		  }
		 
	  }

	  /**
       * User::updateResume()
       *
       * @return
       */
	  public function updateResume()
	  {
          Filter::checkPost('email', Lang::$word->EMAIL);

		  if (!empty($_FILES['avatar']['name'])) {
              if (!preg_match("/(\.jpg|\.png)$/i", $_FILES['avatar']['name'])) {
                  Filter::$msgs['avatar'] = Lang::$word->CONF_LOGO_R;
              }
              $file_info = getimagesize($_FILES['avatar']['tmp_name']);
              if (empty($file_info))
                  Filter::$msgs['avatar'] = Lang::$word->CONF_LOGO_R;
          }

		  if (empty(Filter::$msgs)) {

			//Education
			$edu_name = $_POST['edu_name'];
			$edu_subject = $_POST['edu_subject'];
			$edu_degree = $_POST['edu_degree'];
			$edu_year = $_POST['edu_year'];
			$edu_notes = $_POST['edu_notes'];

			foreach( array_keys($edu_name) as $key ) {
				$education[$key]['name'] = mysql_real_escape_string($edu_name[$key]);
				$education[$key]['subject'] = mysql_real_escape_string($edu_subject[$key]);
				$education[$key]['degree'] = mysql_real_escape_string($edu_degree[$key]);
				$education[$key]['year'] = mysql_real_escape_string($edu_year[$key]);
				$education[$key]['notes'] = mysql_real_escape_string($edu_notes[$key]);
			}

			$string_education = serialize($education);

			//Experience
			$exp_company = $_POST['exp_company'];
			$exp_designation = $_POST['exp_designation'];
			$exp_start = $_POST['exp_start'];
			$exp_end = $_POST['exp_end'];
			$exp_notes = $_POST['exp_notes'];

			foreach( array_keys($exp_company) as $key ) {
				$experience[$key]['company'] = mysql_real_escape_string($exp_company[$key]);
				$experience[$key]['designation'] = mysql_real_escape_string($exp_designation[$key]);
				$experience[$key]['start'] = mysql_real_escape_string($exp_start[$key]);
				$experience[$key]['end'] = mysql_real_escape_string($exp_end[$key]);
				$experience[$key]['notes'] = mysql_real_escape_string($exp_notes[$key]);
			}

			$string_experience = serialize($experience);

			//Portfolio
			$port_name = $_POST['port_name'];
			$port_url = $_POST['port_url'];

			foreach( array_keys($exp_company) as $key ) {
				$portfolio[$key]['name'] = mysql_real_escape_string($port_name[$key]);
				$portfolio[$key]['url'] = mysql_real_escape_string($port_url[$key]);
			}

			$string_portfolio = serialize($portfolio);

			//Reffereces
			$ref_name = $_POST['ref_name'];
			$ref_profession = $_POST['ref_profession'];
			$ref_phone = $_POST['ref_phone'];
			$ref_email = $_POST['ref_email'];

			foreach( array_keys($exp_company) as $key ) {
				$references[$key]['name'] = mysql_real_escape_string($ref_name[$key]);
				$references[$key]['profession'] = mysql_real_escape_string($ref_profession[$key]);
				$references[$key]['phone'] = mysql_real_escape_string($ref_phone[$key]);
				$references[$key]['email'] = mysql_real_escape_string($ref_email[$key]);
			}

			$string_references = serialize($references);

			  $data = array(
				  'fullname' 			=> sanitize($_POST['fullname']),
				  'title' 				=> sanitize($_POST['title']),
				  'hourly_rate' 				=> sanitize($_POST['hourly_rate']),
				  'phone' 				=> sanitize($_POST['phone']),
				  'email' 				=> sanitize($_POST['email']),
				  'objective' 			=> sanitize($_POST['objective']),
				  'skills' 				=> implode(",", $_POST['skills']),
				  'education' 			=> $string_education,
				  'experience' 			=> $string_experience,
				  'portfolio' 			=> $string_portfolio,
				  'references' 			=> $string_references,
				  'present_address' 	=> sanitize($_POST['present_address']),
				  'permanent_address' 	=> sanitize($_POST['permanent_address']),
				  'city' 			=> sanitize($_POST['city']),
				  'state' 			=> sanitize($_POST['state']),
				  'country' 			=> sanitize($_POST['country']),
				  'website' 			=> sanitize($_POST['website']),
				  'facebook' 			=> sanitize($_POST['facebook']),
				  'twitter' 			=> sanitize($_POST['twitter']),
				  'linkedin' 			=> sanitize($_POST['linkedin']),
				  'gplus' 				=> sanitize($_POST['gplus'])
			  );

			  if (!empty($_FILES['avatar']['name'])) {
				  $thumbdir = UPLOADS . "avatars/";
				  $tName = "RES_" . randName();
				  $text = substr($_FILES['avatar']['name'], strrpos($_FILES['avatar']['name'], '.') + 1);
				  $thumbName = $thumbdir . $tName . "." . strtolower($text);
				  if (Filter::$id && $thumb = getValueById("avatar", self::uTable, Filter::$id)) {
					  @unlink($thumbdir . $thumb);
				  }
				  move_uploaded_file($_FILES['avatar']['tmp_name'], $thumbName);
				  $data['avatar'] = $tName . "." . strtolower($text);
			  }

			  self::$db->update('resumes', $data, "uid = '" . $this->uid . "'");


			  if (self::$db->affected()) {
				  $pmsg = '<div class="pmsg psuccess"><i class="fa fa-check"></i> Resume updated successfully.</div>';
			  } else {
				  $pmsg = '<div class="pmsg pwarning"><i class="fa fa-exclamation-triangle"></i> Nothing to process.</div>';
			  }
			 return $pmsg;

		  } else {
			  $pmsg = Filter::msgStatus();
			  return $pmsg;
		  }
	  }


	  /**
	   * Users::getResume()
	   *
	   * @return
	   */
	  public function getResume()
	  {
		  $sql = "SELECT *"
		  . "\n FROM resumes WHERE uid = '" . $this->uid . "'";
		  $row = self::$db->first($sql);
		  return ($row) ? $row : 0;
	  }

	  /**
       * User::updateCompany()
       *
       * @return
       */
	  public function updateCompany()
	  {
          Filter::checkPost('email', Lang::$word->EMAIL);

		  if (!empty($_FILES['avatar']['name'])) {
              if (!preg_match("/(\.jpg|\.png)$/i", $_FILES['avatar']['name'])) {
                  Filter::$msgs['avatar'] = Lang::$word->CONF_LOGO_R;
              }
              $file_info = getimagesize($_FILES['avatar']['tmp_name']);
              if (empty($file_info))
                  Filter::$msgs['avatar'] = Lang::$word->CONF_LOGO_R;
          }

		  if (empty(Filter::$msgs)) {

			  $data = array(
				  'name'				=> sanitize($_POST['name']),
				  'about' 				=> sanitize($_POST['about']),
				  'business'			=> sanitize($_POST['business']),
				  'email' 				=> sanitize($_POST['email']),
				  'phone' 				=> sanitize($_POST['phone']),
				  'address' 			=> sanitize($_POST['address']),
				  'website' 			=> sanitize($_POST['website']),
				  'facebook' 			=> sanitize($_POST['facebook']),
				  'twitter' 			=> sanitize($_POST['twitter']),
				  'linkedin' 			=> sanitize($_POST['linkedin']),
				  'gplus' 				=> sanitize($_POST['gplus'])
			  );

			  if (!empty($_FILES['avatar']['name'])) {
				  $thumbdir = UPLOADS . "avatars/";
				  $tName = "COM_" . randName();
				  $text = substr($_FILES['avatar']['name'], strrpos($_FILES['avatar']['name'], '.') + 1);
				  $thumbName = $thumbdir . $tName . "." . strtolower($text);
				  if (Filter::$id && $thumb = getValueById("avatar", self::uTable, Filter::$id)) {
					  @unlink($thumbdir . $thumb);
				  }
				  move_uploaded_file($_FILES['avatar']['tmp_name'], $thumbName);
				  $data['avatar'] = $tName . "." . strtolower($text);
			  }

			  self::$db->update('companies', $data, "uid = '" . $this->uid . "'");

			  if (self::$db->affected()) {
				  $pmsg = '<div class="pmsg psuccess"><i class="fa fa-check"></i> Company infomation updated successfully.</div>';
			  } else {
				  $pmsg = '<div class="pmsg pwarning"><i class="fa fa-exclamation-triangle"></i> Nothing to process.</div>';
			  }
			 return $pmsg;

		  } else {
			  $pmsg = Filter::msgStatus();
			  return $pmsg;
		  }
	  }


	  /**
	   * Users::getCompany()
	   *
	   * @return
	   */
	  public function getCompany()
	  {
		  $sql = "SELECT *"
		  . "\n FROM companies WHERE uid = '" . $this->uid . "'";
		  $row = self::$db->first($sql);
		  return ($row) ? $row : 0;
	  }

      /**
       * User::passReset()
       *
       * @return
       */
	  public function passReset()
	  {

          Filter::checkPost('uname', Lang::$word->USERNAME);
		  Filter::checkPost('email', Lang::$word->EMAIL);

          $uname = $this->usernameExists($_POST['uname']);
          if (strlen($_POST['uname']) < 4 || strlen($_POST['uname']) > 30 || !preg_match("/^[a-z0-9_-]{4,15}$/", $_POST['uname']) || $uname != 3)
              Filter::$msgs['uname'] = Lang::$word->USERNAME_R5;

          if (!self::$db->first("SELECT id FROM " . self::uTable . " WHERE username = '" . sanitize($_POST['uname']) . "' and email = '" . sanitize($_POST['email']) . "'")) {
              Filter::$msgs['uname'] = Lang::$word->USERNAME_R6;
		  }

          if (!$this->emailExists($_POST['email']))
              Filter::$msgs['uname'] = Lang::$word->EMAIL_R4;

		  Filter::checkPost('captcha', Lang::$word->CAPTCHA_E1);
		  if ($_SESSION['captchacode'] != $_POST['captcha'])
			  Filter::$msgs['captcha'] = Lang::$word->CAPTCHA_E2;

		  if (empty(Filter::$msgs)) {

              $user = $this->getUserInfo($_POST['uname']);
			  $randpass = $this->getUniqueCode(12);
			  $newpass = sha1($randpass);

			  $data['password'] = $newpass;

			  self::$db->update(self::uTable, $data, "username = '" . $user->username . "'");

			  require_once(BASEPATH . "lib/class_mailer.php");
			  $row = Core::getRowById("email_templates", 2);

              $body = str_replace(array(
                  '[USERNAME]',
                  '[PASSWORD]',
                  '[URL]',
                  '[LINK]',
                  '[IP]',
                  '[SITE_NAME]'), array(
                  $user->username,
                  $randpass,
                  SITEURL,
                  SITEURL,
                  $_SERVER['REMOTE_ADDR'],
                  Registry::get("Core")->site_name), $row->body);

              $newbody = cleanOut($body);

              $mailer = Mailer::sendMail();
              $message = Swift_Message::newInstance()
						->setSubject($row->subject)
						->setTo(array($user->email => $user->username))
						->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->site_name))
						->setBody($newbody, 'text/html');

			  if (self::$db->affected() and $mailer->send($message)) {
				  $json['status'] = 'success';
				  $json['message'] = Filter::msgOk(Lang::$word->_UA_PASSR_OK, false);
			  } else {
				  $json['status'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->_UA_PASSR_ERR, false);
			  }
			  print json_encode($json);

		  } else {
			  Filter::msgStatus();
		  }
	  }

      /**
       * User::activateAccount()
       *
       * @return
       */
      public function activateAccount()
      {

          $data['active'] = "y";
		  self::$db->update(self::uTable, $data, "id = '" . Filter::$id . "'");

		  require_once (BASEPATH . "lib/class_mailer.php");
		  $row = Registry::get("Core")->getRowById(Content::eTable, 15);
		  $usr = Registry::get("Core")->getRowById(self::uTable, Filter::$id);

		  $body = str_replace(array(
			  '[NAME]',
			  '[URL]',
			  '[SITE_NAME]'), array(
			  $usr->fname . ' ' .$usr->lname,
			  SITEURL,
			  Registry::get("Core")->site_name), $row->body);

		  $newbody = cleanOut($body);

		  $mailer = $mail->sendMail();
		  $message = Swift_Message::newInstance()
					->setSubject($row->subject)
					->setTo(array($usr->email => $usr->username))
					->setFrom(array(Registry::get("Core")->site_email => Registry::get("Core")->site_name))
					->setBody($newbody, 'text/html');

		  if(self::$db->affected() && $mailer->send($message)) {
			  $json['type'] = 'success';
			  $json['title'] = Lang::$word->SUCCESS;
			  $json['message'] =  Lang::$word->USR_ACCT_OK;
		  } else {
			  $json['type'] = 'error';
			  $json['title'] = Lang::$word->ERROR;
			  $json['message'] = Lang::$word->USR_ACCT_ERR;
		  }
      }

      /**
       * User::activateUser()
       *
       * @return
       */
	  public function activateUser()
	  {

		  Filter::checkPost('email', Lang::$word->EMAIL);

          if (!$this->emailExists($_POST['email']))
              Filter::$msgs['email'] = Lang::$word->EMAIL_R4;

          Filter::checkPost('token', Lang::$word->_UA_TOKEN);

          if (!$this->validateToken($_POST['token']))
              Filter::$msgs['token'] = Lang::$word->_UA_TOKEN_R;

          if (empty(Filter::$msgs)) {
              $email = sanitize($_POST['email']);
              $token = sanitize($_POST['token']);
              $message = (Registry::get("Core")->auto_verify == 1) ? Lang::$word->_UA_ACCOK_1 : Lang::$word->_UA_ACCOK_2;

              $data = array('token' => 0, 'active' => (Registry::get("Core")->auto_verify) ? "y" : "n");

              self::$db->update(self::uTable, $data, "email = '" . $email . "' AND token = '" . $token . "'");

			  if (self::$db->affected()) {
				  $json['status'] = 'success';
				  $json['message'] = Filter::msgOk($message, false);
			  } else {
				  $json['status'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->_UA_TOKEN_ERR, false);
			  }
			  print json_encode($json);

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
	  }

	  /**
	   * Users::getUserList()
	   *
	   * @return
	   */
	  public function getUserList()
	  {
		  $sql = "SELECT id, username, CONCAT(fname,' ',lname) as name FROM " . self::uTable
		  . "\n WHERE active = 'y'";
		  $row = self::$db->fetch_all($sql);

		  return ($row) ? $row : 0;
	  }

	  /**
	   * Users::getUserData()
	   *
	   * @return
	   */
	  public function getUserData()
	  {

		  $sql = "SELECT *"
		  . "\n FROM " . self::uTable
		  . "\n WHERE id = '" . $this->uid . "'";
		  $row = self::$db->first($sql);

		  return ($row) ? $row : 0;
	  }

	  /**
	   * Users::usernameExists()
	   *
	   * @param mixed $username
	   * @return
	   */
	  private function usernameExists($username)
	  {

          $username = sanitize($username);
          if (strlen(self::$db->escape($username)) < 4)
              return 1;

          //Username should contain only alphabets, numbers, underscores or hyphens.Should be between 4 to 15 characters long
		  $valid_uname = "/^[a-zA-Z0-9_-]{4,15}$/";
          if (!preg_match($valid_uname, $username))
              return 2;

          $sql = self::$db->query("SELECT username"
		  . "\n FROM " . self::uTable
		  . "\n WHERE username = '" . $username . "'"
		  . "\n LIMIT 1");

          $count = self::$db->numrows($sql);

          return ($count > 0) ? 3 : false;
	  }

	  /**
	   * User::emailExists()
	   *
	   * @param mixed $email
	   * @return
	   */
	  private function emailExists($email)
	  {
          $sql = self::$db->query("SELECT email"
		  . "\n FROM " . self::uTable
		  . "\n WHERE email = '" . sanitize($email) . "'"
		  . "\n LIMIT 1");

          if (self::$db->numrows($sql) == 1) {
              return true;
          } else
              return false;
	  }

	  /**
	   * User::isValidEmail()
	   *
	   * @param mixed $email
	   * @return
	   */
	  private function isValidEmail($email)
	  {
		  if (function_exists('filter_var')) {
			  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				  return true;
			  } else
				  return false;
		  } else
			  return preg_match('/^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $email);
	  }

	  /**
	   * User::confirmCookie()
	   *
	   * @param mixed $username
	   * @param mixed $cookie_id
	   * @return
	   */
	  private function confirmCookie($username, $cookie_id)
	  {

		  $sql = "SELECT cookie_id FROM " . self::uTable . " WHERE username = '" . self::$db->escape($username) . "'";
		  $row = self::$db->first($sql);

		  $row->cookie_id = sanitize($row->cookie_id);
		  $cookie_id = sanitize($cookie_id);

		  if ($cookie_id == $row->cookie_id) {
			  return true;
		  } else
			  return false;
	  }

      /**
       * User::validateToken()
       *
       * @param mixed $token
       * @return
       */
     private function validateToken($token)
     {
          $token = sanitize($token, 40);
          $sql = "SELECT token"
		  . "\n FROM " . self::uTable
		  . "\n WHERE token ='" . self::$db->escape($token) . "'"
		  . "\n LIMIT 1";
          $result = self::$db->query($sql);

          if (self::$db->numrows($result))
              return true;
      }

	  /**
	   * Users::getUniqueCode()
	   *
	   * @param string $length
	   * @return
	   */
	  private function getUniqueCode($length = "")
	  {
		  $code = sha1(uniqid(rand(), true));
		  if ($length != "") {
			  return substr($code, 0, $length);
		  } else
			  return $code;
	  }

	  /**
	   * Users::generateRandID()
	   *
	   * @return
	   */
	  private function generateRandID()
	  {
		  return sha1($this->getUniqueCode(24));
	  }

	  /**
	   * Users::levelCheck()
	   *
	   * @param string $levels
	   * @return
	   */
	  public function levelCheck($levels)
	  {
          $m_arr = explode(",", $levels);
          reset($m_arr);

          if ($this->logged_in and in_array($this->userlevel, $m_arr))
              return true;
	  }

      /**
       * Users::getUserLevels()
       *
       * @return
       */
      public function getUserLevels($level = false)
	  {
		  $arr = array(
				 9 => 'Super Admin',
				 1 => 'Registered User',
				 2 => 'User Level 2',
				 3 => 'User Level 3',
				 4 => 'User Level 4',
				 5 => 'User Level 5',
				 6 => 'User Level 6',
				 7 => 'User Level 7'
		  );

		  $list = '';
		  foreach ($arr as $key => $val) {
				  if ($key == $level) {
					  $list .= "<option selected=\"selected\" value=\"$key\">$val</option>\n";
				  } else
					  $list .= "<option value=\"$key\">$val</option>\n";
		  }
		  unset($val);
		  return $list;
	  }

      /**
       * Users::getUserFilter()
       *
       * @return
       */
      public static function getUserFilter()
	  {
		  $arr = array(
				 'username-ASC' => Lang::$word->USERNAME . ' &uarr;',
				 'username-DESC' => Lang::$word->USERNAME . ' &darr;',
				 'fname-ASC' => Lang::$word->FNAME . ' &uarr;',
				 'fname-DESC' => Lang::$word->FNAME . ' &darr;',
				 'lname-ASC' => Lang::$word->LNAME . ' &uarr;',
				 'lname-DESC' => Lang::$word->LNAME . ' &darr;',
				 'email-ASC' => Lang::$word->EMAIL . ' &uarr;',
				 'email-DESC' => Lang::$word->EMAIL . ' &darr;',
				 'created-ASC' => Lang::$word->REGD . ' &uarr;',
				 'created-DESC' => Lang::$word->REGD . ' &darr;',
		  );

		  $filter = '';
		  foreach ($arr as $key => $val) {
				  if ($key == get('sort')) {
					  $filter .= "<option selected=\"selected\" value=\"$key\">$val</option>\n";
				  } else
					  $filter .= "<option value=\"$key\">$val</option>\n";
		  }
		  unset($val);
		  return $filter;
	  }
  }
?>