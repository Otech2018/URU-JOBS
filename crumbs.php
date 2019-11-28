<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php
  $sname = $_SERVER["SCRIPT_NAME"];
  $pages = array(
      '404',
      'account',
      'activate',
      'add-job',
      'add-resume',
      'bookmarks',
      'browse-categories',
      'browse-jobs',
      'browse-resumes',
      'checkout',
      'company-settings',
      'company',
      'content',
      'invoices',
      'job',
      'login',
      'logout',
      'maintenance',
      'manage-applications',
      'manage-jobs',
      'messages',
      'profile',
      'register',
      'resume',
      'search',
      'summary',
      'tags',
      'view',
      );
  $regexp = '#' . implode('|', $pages) . '#';
  $pages = preg_match($regexp, $sname, $matches) ? $matches[0] : '';
  $html = '';

  switch ($pages) {

      case "404":
          $html =  'Not Found';
          break;

      case "account":
          $html =  Lang::$word->_UA_MYACC;
          break;

      case "activate":
          print Lang::$word->_UA_TITLE3;
          break;

      case "add-job":
          print "Post New Job";
          break;

      case "add-resume":
          print "Manage/Update Resume";
          break;

      case "bookmarks":
          print "Manage Bookmarks";
          break;

      case "browse-categories":
          print "Job Categories";
          break;

      case "browse-jobs":
          print "Browse Jobs";
          break;

      case "browse-resumes":
          print "Browse Resumes";
          break;

      case "checkout":
          $html = Lang::$word->CKO_TITLE;
          break;

      case "company-settings":
          $html = "Company Settings";
          break;

      case "company":
          $html = "Company Details";
          break;

      case "content":
          $html = ($row) ? $row->title : "";
          break;

      case "invoices":
          $html = 'Invoices';
          break;

      case "job":
          $html = 'Job Details';
          break;

      case "login":
          $html = Lang::$word->_UA_TITLE;
          break;

      case "logout":
          $html = 'Logout';
          break;

      case "maintenance":
          $html = 'Maintenance Mode';
          break;

      case "manage-applications":
          $html = 'Manage Applications';
          break;

      case "manage-jobs":
          $html = 'Manage Job Posts';
          break;

      case "messages":
          $html = 'Instant Messages';
          break;

      case "profile":
          $html = Lang::$word->_UA_TITLE4;
          break;

      case "register":
          $html = Lang::$word->_UA_TITLE2;
          break;

      case "resume":
          $html = "Resume Details";
          break;

      case "search":
          $html = Lang::$word->FSRC_TITLE;
          break;

      case "summary":
          $html = Lang::$word->SMY_TITLE;
          break;

      default:
		  $html = '';
          break;

  }

  //print '<div class="active section">' . $html . '</div>';
  return $html;
?>
