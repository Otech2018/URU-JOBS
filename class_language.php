<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');


  final class Lang
  {
      const langdir = "lang/";
	  public static $language;
	  public static $word = array();
	  public static $lang;


      /**
       * Lang::__construct()
       * 
       * @return
       */
      public function __construct()
      {
		  self::get();
      }
	  
      /**
       * Lang::get()
       * 
       * @return
       */
	  private static function get()
	  {
		  if (isset($_COOKIE['LANG_DDP'])) {
			  $sel_lang = sanitize($_COOKIE['LANG_DDP'], 2);
			  $vlang = self::fetchLanguage($sel_lang);
			  if(in_array($sel_lang, $vlang)) {
				  Core::$language = $sel_lang;
			  } else {
				  Core::$language = Registry::get("Core")->lang;
			  }
			  if (file_exists(BASEPATH . self::langdir . Core::$language . "/lang.xml")) {
				  self::$word = self::set(BASEPATH . self::langdir . Core::$language . "/lang.xml", Core::$language);
			  } else {
				  self::$word = self::set(BASEPATH . self::langdir . Registry::get("Core")->lang . "/lang.xml", Registry::get("Core")->lang);
			  }
		  } else {
			  Core::$language = Registry::get("Core")->lang;
			  self::$word = self::set(BASEPATH . self::langdir . Registry::get("Core")->lang. "/lang.xml", Registry::get("Core")->lang);
			  
		  }
		  self::$lang = "_" . Core::$language;
		  return self::$word;
	  }

      /**
       * Lang::set()
       * 
       * @return
       */
	  private static function set($lang)
	  {
		  $xmlel = simplexml_load_file($lang);
		  $data = new stdClass();
		  foreach ($xmlel as $pkey) {
			  $key = (string) $pkey['data'];
			  $data->$key = (string) str_replace(array('\'', '"'), array("&apos;", "&quot;"), $pkey);
		  }
		  
		  return $data;
	  }
	  
      /**
       * Lang::fetchLanguage()
       * 
       * @return
       */
	  public static function fetchLanguage()
	  {
		  $lang_array = '';
		  $directory = BASEPATH . self::langdir;
		  if (!is_dir($directory)) {
			  return false;
		  } else {
			  $lang_array = glob($directory . "*", GLOB_ONLYDIR);
			  $lang_array = str_replace($directory, "", $lang_array);
	
		  }
	
		  return $lang_array;
	  }
  }
?>