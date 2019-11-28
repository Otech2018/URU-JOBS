<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php //error_reporting(E_ALL);

  if (substr(PHP_OS, 0, 3) == "WIN") {
      $BASEPATH = str_replace("admin\\init.php", "", realpath(__FILE__));
  } else {
      $BASEPATH = str_replace("admin/init.php", "", realpath(__FILE__));
  }
  define("BASEPATH", $BASEPATH);

  $configFile = BASEPATH . "lib/config.ini.php";
  if (file_exists($configFile)) {
      require_once($configFile);
  } else {
      header("Location: setup/");
  }

  //include_once(BASEPATH . "language.php");
  require_once(BASEPATH . "lib/class_db.php");

  require_once(BASEPATH . "lib/class_registry.php");
  Registry::set('Database',new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE));
  $db = Registry::get("Database");
  $db->connect();

  //Include Functions
  require_once(BASEPATH . "lib/functions.php");
  require_once(BASEPATH . "lib/fn_seo.php");

  require_once(BASEPATH . "lib/class_filter.php");
  $request = new Filter();

  //Start Core Class
  require_once(BASEPATH . "lib/class_core.php");
  Registry::set('Core',new Core());
  $core = Registry::get("Core");

  //Start Language Class
  require_once(BASEPATH . "lib/class_language.php");
  Registry::set('Lang',new Lang());
  $lang = Registry::get("Lang");

  //StartUser Class
  require_once(BASEPATH . "lib/class_user.php");
  Registry::set('Users',new Users());
  $user = Registry::get("Users");

  //Load Content Class
  require_once(BASEPATH . "lib/class_content.php");
  Registry::set('Content',new Content());
  $content = Registry::get("Content");

  //Load Job Class
  require_once(BASEPATH . "lib/class_jobs.php");
  Registry::set('Jobs',new Jobs());
  $jobs = Registry::get("Jobs");

  //Start Minify Class
  require_once (BASEPATH . "lib/class_minify.php");
  Registry::set('Minify', new Minify());

  //Start Paginator Class
  require_once(BASEPATH . "lib/class_paginate.php");
  $pager = Paginator::instance();

  if (isset($_SERVER['HTTPS'])) {
      $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
  } else {
      $protocol = 'http';
  }

  $dir = (Registry::get("Core")->site_dir) ? '/' . Registry::get("Core")->site_dir : '';
  $url = preg_replace("#/+#", "/", $_SERVER['HTTP_HOST'] . $dir);
  $site_url = $protocol . "://" . $url;

  define("SITEURL", $site_url);
  define("ADMINURL", $site_url."/admin");
  define("UPLOADS", BASEPATH . "uploads/");
  define("UPLOADURL", SITEURL . "/uploads/");
  define("PRODIMGPATH", BASEPATH . "uploads/prod_images/");
  define("PRODIMGURL", SITEURL . "/uploads/prod_images/");
  define("PRODGALPATH", BASEPATH . "uploads/prod_gallery/");
  define("PRODGALURL", SITEURL . "/uploads/prod_gallery/");

  define("THEME", BASEPATH . "admin/assets");
  define("THEMEU", SITEURL . "/admin/assets");

  setlocale(LC_TIME, $core->setLocale());
?>
