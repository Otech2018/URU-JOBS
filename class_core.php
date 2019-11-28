<?php

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  class Core
  {

	  const sTable = "settings";
      public $year = null;
      public $month = null;
      public $day = null;


	  public static $language;
	  public $langlist;


      /**
       * Core::__construct()
       *
       * @return
       */
      function __construct()
      {
          $this->getSettings();

		  ($this->dtz) ? date_default_timezone_set($this->dtz) : date_default_timezone_set('GMT');

          $this->year = (get('year')) ? get('year') : strftime('%Y');
          $this->month = (get('month')) ? get('month') : strftime('%m');
          $this->day = (get('day')) ? get('day') : strftime('%d');

          return mktime(0, 0, 0, $this->month, $this->day, $this->year);
      }


      /**
       * Core::getSettings()
       *
       * @return
       */
      private function getSettings()
      {
          $sql = "SELECT * FROM " . self::sTable;
          $row = Registry::get("Database")->first($sql);

          $this->site_name = $row->site_name;
		  $this->company = $row->company;
          $this->site_url = $row->site_url;
		  $this->site_dir = $row->site_dir;
		  $this->site_email = $row->site_email;
		  $this->seo = $row->seo;
		  $this->perpage = $row->perpage;
		  $this->backup = $row->backup;
		  $this->thumb_w = $row->thumb_w;
		  $this->thumb_h = $row->thumb_h;
		  $this->img_w = $row->img_w;
		  $this->img_h = $row->img_h;
		  $this->file_dir = $row->file_dir;
		  $this->short_date = $row->short_date;
		  $this->long_date = $row->long_date;
		  $this->time_format = $row->time_format;
		  $this->dtz = $row->dtz;
		  $this->locale = $row->locale;
		  $this->featured = $row->featured;
		  $this->hlayout = $row->hlayout;
		  $this->homelist = $row->homelist;
		  $this->latest_jobs = $row->latest_jobs;
		  $this->featured_jobs = $row->featured_jobs;
		  $this->featured_resumes = $row->featured_resumes;
		  $this->popular = $row->popular;
		  $this->free_allowed = $row->free_allowed;
		  $this->logo = $row->logo;
		  $this->theme = $row->theme;
		  $this->lang = $row->lang;
		  $this->tax = $row->tax;
		  $this->psize = $row->psize;
		  $this->inv_info = $row->inv_info;
		  $this->inv_note = $row->inv_note;
		  $this->show_home = $row->show_home;
		  $this->show_slider = $row->show_slider;
		  $this->currency = $row->currency;
		  $this->cur_symbol = $row->cur_symbol;
		  $this->offline = $row->offline;
		  $this->offline_msg = $row->offline_msg;
		  $this->offline_d = $row->offline_d;
          $this->offline_t = $row->offline_t;
		  $this->facebook_url = $row->facebook_url;
		  $this->twitter_url = $row->twitter_url;
		  $this->google_plus_url = $row->google_plus_url;
		  $this->linkedin_url = $row->linkedin_url;
		  $this->copyright = $row->copyright;
		  $this->reg_verify = $row->reg_verify;
		  $this->notify_admin = $row->notify_admin;
		  $this->auto_verify = $row->auto_verify;
		  $this->reg_allowed = $row->reg_allowed;
		  $this->user_limit = $row->user_limit;
		  $this->analytics = $row->analytics;
          $this->metakeys = $row->metakeys;
          $this->metadesc = $row->metadesc;
		  $this->mailer = $row->mailer;
		  $this->smtp_host = $row->smtp_host;
		  $this->smtp_user = $row->smtp_user;
		  $this->smtp_pass = $row->smtp_pass;
		  $this->smtp_port = $row->smtp_port;
		  $this->is_ssl = $row->is_ssl;
		  $this->sendmail = $row->sendmail;

		  $this->version = $row->version;

      }

      /**
       * Core::processConfig()
       *
       * @return
       */
	  public function processConfig()
	  {

          Filter::checkPost('site_name', Lang::$word->CONF_SITE);
          Filter::checkPost('site_url', Lang::$word->CONF_URL);
          Filter::checkPost('site_email', Lang::$word->CONF_EMAIL);
          Filter::checkPost('thumb_w', Lang::$word->CONF_THUMBW);
          Filter::checkPost('thumb_h', Lang::$word->CONF_THUMBH);
          Filter::checkPost('currency',Lang::$word->CONF_CURRENCY);

		  Filter::checkPost('file_dir', Lang::$word->CONF_FPATH);
		  Filter::checkPost('latest_jobs', 'Latest Jobs');
		  Filter::checkPost('featured_jobs', 'Featured Jobs');
		  Filter::checkPost('featured_resumes', 'Featured Resumes');
		  Filter::checkPost('perpage', Lang::$word->CONF_IPP);

          switch($_POST['mailer']) {
			  case "SMTP" :
				  Filter::checkPost('smtp_host',Lang::$word->CONF_SMTP_HOST);
				  Filter::checkPost('smtp_user',Lang::$word->CONF_SMTP_USER);
				  Filter::checkPost('currency',Lang::$word->CONF_SMTP_PASS);
				  Filter::checkPost('smtp_port',Lang::$word->CONF_SMTP_PORT);
				  break;

			  case "SMAIL" :
				  Filter::checkPost('sendmail',Lang::$word->_CG_SMAILPATH);
			  break;
		  }


          if ($_POST['mailer'] == "SMTP") {
              Filter::checkPost('smtp_host', Lang::$word->CONF_SMTP_HOST);
              Filter::checkPost('smtp_user', Lang::$word->CONF_SMTP_USER);
              Filter::checkPost('smtp_pass', Lang::$word->CONF_SMTP_PASS);
              Filter::checkPost('smtp_port', Lang::$word->CONF_SMTP_PORT);
          }

          if (!empty($_FILES['logo']['name'])) {
              $file_info = getimagesize($_FILES['logo']['tmp_name']);
              if (empty($file_info))
                  Filter::$msgs['logo'] = Lang::$word->CONF_LOGO_R;
          }
          if (!empty($_FILES['favicon']['name'])) {
              $file_info = getimagesize($_FILES['favicon']['tmp_name']);
              if (empty($file_info))
                  Filter::$msgs['favicon'] = "Favicon image required.";
          }
          if (!empty($_FILES['plogo']['name'])) {
              $file_info = getimagesize($_FILES['plogo']['tmp_name']);
              if (empty($file_info))
                  Filter::$msgs['plogo'] = Lang::$word->CONF_LOGO_R;
          }
		  if (empty(Filter::$msgs)) {
			  $data = array(
					  'site_name' => sanitize($_POST['site_name']),
					  'company' => sanitize($_POST['company']),
					  'site_url' => sanitize($_POST['site_url']),
					  'site_dir' => sanitize($_POST['site_dir']),
					  'site_email' => sanitize($_POST['site_email']),
					  'seo' => intval($_POST['seo']),
					  'perpage' => intval($_POST['perpage']),
					  'thumb_w' => intval($_POST['thumb_w']),
					  'thumb_h' => intval($_POST['thumb_h']),
					  'file_dir' => sanitize($_POST['file_dir']),
					  'short_date' => sanitize($_POST['short_date']),
					  'long_date' => sanitize($_POST['long_date']),
					  'time_format' => sanitize($_POST['time_format']),
					  'dtz' => trim($_POST['dtz']),
					  'locale' => sanitize($_POST['locale']),
					  'currency' => sanitize($_POST['currency']),
					  'cur_symbol' => sanitize($_POST['cur_symbol']),
					  'offline' => intval($_POST['offline']),
					  'offline_msg' => $_POST['offline_msg'],
					  'offline_d' => sanitize($_POST['offline_d_submit']),
					  'offline_t' => sanitize($_POST['offline_t_submit']),
					  'theme' => sanitize($_POST['theme']),
					  'lang' => sanitize($_POST['lang']),
					  'tax' => intval($_POST['tax']),
					  'psize' => sanitize($_POST['psize']),
					  'inv_note' => $_POST['inv_note'],
					  'inv_info' => $_POST['inv_info'],
					  'show_home' => intval($_POST['show_home']),
					  'show_slider' => intval($_POST['show_slider']),
					  'featured' => intval($_POST['featured']),
					  'hlayout' => intval($_POST['hlayout']),
					  'homelist' => intval($_POST['homelist']),
					  'latest_jobs' => intval($_POST['latest_jobs']),
					  'featured_jobs' => intval($_POST['featured_jobs']),
					  'featured_resumes' => intval($_POST['featured_resumes']),
					  'popular' => intval($_POST['popular']),
					  'free_allowed' => intval($_POST['free_allowed']),
					  'reg_verify' => intval($_POST['reg_verify']),
					  'auto_verify' => intval($_POST['auto_verify']),
					  'reg_allowed' => intval($_POST['reg_allowed']),
					  'notify_admin' => intval($_POST['notify_admin']),
					  'user_limit' => intval($_POST['user_limit']),
					  'facebook_url' => sanitize($_POST['facebook_url']),
					  'twitter_url' => sanitize($_POST['twitter_url']),
					  'google_plus_url' => sanitize($_POST['google_plus_url']),
					  'linkedin_url' => sanitize($_POST['linkedin_url']),
					  'copyright' => sanitize($_POST['copyright']),
					  'analytics' => trim($_POST['analytics']),
					  'metadesc' => trim($_POST['metadesc']),
					  'metakeys' => trim($_POST['metakeys']),
					  'mailer' => sanitize($_POST['mailer']),
					  'sendmail' => sanitize($_POST['sendmail']),
					  'smtp_host' => sanitize($_POST['smtp_host']),
					  'smtp_user' => sanitize($_POST['smtp_user']),
					  'smtp_pass' => sanitize($_POST['smtp_pass']),
					  'smtp_port' => intval($_POST['smtp_port']),
					  'is_ssl' => intval($_POST['is_ssl'])

			  );

              if (isset($_POST['dellogo']) and $_POST['dellogo'] == 1) {
				  $data['logo'] = "NULL";
			  } elseif (!empty($_FILES['logo']['name'])) {
				  if ($this->logo) {
					  @unlink(UPLOADS . $this->logo);
				  }
					  move_uploaded_file($_FILES['logo']['tmp_name'], UPLOADS . $_FILES['logo']['name']);

				  $data['logo'] = sanitize($_FILES['logo']['name']);
			  } else {
				$data['logo'] = $this->logo;
			  }

              if (!empty($_FILES['favicon']['name'])) {
				  if (file_exists(UPLOADS . "favicon.png")) {
					  unlink(UPLOADS . "favicon.png");
				  }
				  move_uploaded_file($_FILES['favicon']['tmp_name'], UPLOADS . "favicon.png");
			  }

			  if (!empty($_FILES['plogo']['name'])) {
				  if (file_exists(UPLOADS . "print_logo.png")) {
					  unlink(UPLOADS . "print_logo.png");
				  }
				  move_uploaded_file($_FILES['plogo']['tmp_name'], UPLOADS . "print_logo.png");
			  }

			  Registry::get("Database")->update(self::sTable, $data);

			  if(Registry::get("Database")->affected()) {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgOk(Lang::$word->CONF_UPDATED, false);
			  } else {
				  $json['type'] = 'success';
				  $json['message'] = Filter::msgAlert(Lang::$word->NOPROCCESS, false);
			  }
			  print json_encode($json);

		  } else {
			  $json['message'] = Filter::msgStatus();
			  print json_encode($json);
		  }
      }

      public function countMessages()
      {
          $sql = "SELECT COUNT(uid1) as total"
          . "\n FROM messages"
          . "\n WHERE (user1='" . Registry::get("Users")->uid . "' AND user1read='no')"
          . "\n OR (user2='" . Registry::get("Users")->uid . "' AND user2read='no')"
          . "\n AND uid2 = 1";
          $row = Registry::get("Database")->first($sql);

          return ($row) ? $row->total : 0;
      }

 	  /**
	   * Core:::langIcon()
	   *
	   * @return
	   */
	  public static function langIcon()
	  {
		  return "<div class=\"wojo black bottom right attached special label\">" . strtoupper(self::$language) . "</div>";
	  }

      /**
       * Core::getShortDate()
       *
       * @return
       */
      public static function getShortDate($selected = false)
	  {

		  $format = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') ? "%#d" : "%e";

          $arr = array(
				 '%m-%d-%Y' => strftime('%m-%d-%Y') . ' (MM-DD-YYYY)',
				 $format . '-%m-%Y' => strftime($format . '-%m-%Y') . ' (D-MM-YYYY)',
				 '%m-' . $format . '-%y' => strftime('%m-' . $format . '-%y') . ' (MM-D-YY)',
				 $format . '-%m-%y' => strftime($format . '-%m-%y') . ' (D-MMM-YY)',
				 '%d %b %Y' => strftime('%d %b %Y')
		  );

		  $shortdate = '';
		  foreach ($arr as $key => $val) {
              if ($key == $selected) {
                  $shortdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $shortdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $shortdate;
      }


      /**
       * Core::getLongDate()
       *
       * @return
       */
	  public static function getLongDate($selected = false)
	  {
		  $format = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') ? "%#d" : "%e";
		  $arr = array(
			  '%B %d, %Y %I:%M %p' => strftime('%B %d, %Y %I:%M %p'),
			  '%d %B %Y %I:%M %p' => strftime('%d %B %Y %I:%M %p'),
			  '%B %d, %Y' => strftime('%B %d, %Y'),
			  '%d %B, %Y' => strftime('%d %B, %Y'),
			  '%A %d %B %Y' => strftime('%A %d %B %Y'),
			  '%A %d %B %Y %H:%M' => strftime('%A %d %B %Y %H:%M'),
			  '%a %d, %B' => strftime('%a %d, %B'));

		  $html = '';
		  foreach ($arr as $key => $val) {
			  if ($key == $selected) {
				  $html .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
			  } else
				  $html .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
		  }
		  unset($val);
		  return $html;
	  }

      /**
       * Core::getTimeFormat()
       *
       * @return
       */
      public static function getTimeFormat($selected = false)
	  {
          $arr = array(
				'%I:%M %p' => strftime('%I:%M %p'),
				'%I:%M %P' => strftime('%I:%M %P'),
				'%H:%M' => strftime('%H:%M'),
				'%k' => strftime('%k'),
		  );

		  $longdate = '';
		  foreach ($arr as $key => $val) {
              if ($key == $selected) {
                  $longdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $longdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $longdate;
      }

      /**
       * Core::getTimezones()
       *
       * @return
       */
	  public function getTimezones()
	  {
		  $data = '';
		  $tzone = DateTimeZone::listIdentifiers();
		  $data .='<select name="dtz">';
		  foreach ($tzone as $zone) {
			  $selected = ($zone == $this->dtz) ? ' selected="selected"' : '';
			  $data .= '<option value="' . $zone . '"' . $selected . '>' . $zone . '</option>';
		  }
		  $data .='</select>';
		  return $data;
	  }

      /**
       * Core::monthList()
       *
       * @return
       */
      public function monthList()
	  {
		  $selected = is_null(get('month')) ? strftime('%m') : get('month');

		  $arr = array(
				'01' => "Jan",
				'02' => "Feb",
				'03' => "Mar",
				'04' => "Apr",
				'05' => "May",
				'06' => "Jun",
				'07' => "Jul",
				'08' => "Aug",
				'09' => "Sep",
				'10' => "Oct",
				'11' => "Nov",
				'12' => "Dec"
		  );

		  $monthlist = '';
		  foreach ($arr as $key => $val) {
			  $monthlist .= "<option value=\"$key\"";
			  $monthlist .= ($key == $selected) ? ' selected="selected"' : '';
			  $monthlist .= ">$val</option>\n";
          }
          unset($val);
          return $monthlist;
      }

      /**
       * Core::yearList()
	   *
       * @param mixed $start_year
       * @param mixed $end_year
       * @return
       */
	  function yearList($start_year, $end_year)
	  {
		  $selected = is_null(get('year')) ? date('Y') : get('year');
		  $r = range($start_year, $end_year);

		  $select = '';
		  foreach ($r as $year) {
			  $select .= "<option value=\"$year\"";
			  $select .= ($year == $selected) ? ' selected="selected"' : '';
			  $select .= ">$year</option>\n";
		  }
		  return $select;
	  }

      /**
       * Core::setLocalet()
       *
       * @return
       */
	  public function setLocale()
	  {
		  return explode(',', $this->locale);
	  }

      /**
       * Core::getlocaleList()
       *
       * @return
       */
      public function getlocaleList()
      {
          $html = '';
          foreach (self::localeList() as $key => $val) {
              if ($key == $this->locale) {
                  $html .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $html .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $html;
      }

      /**
       * Core::localeList()
       *
       * @return
       */
	  public static function localeList()
	  {

		  $lang = array(
			  "af_utf8,Afrikaans,af_ZA.UTF-8,Afrikaans_South Africa.1252,WINDOWS-1252" => "Afrikaans",
			  "sq_utf8,Albanian,sq_AL.UTF-8,Albanian_Albania.1250,WINDOWS-1250" => "Albanian",
			  "ar_utf8,Arabic,ar_SA.UTF-8,Arabic_Saudi Arabia.1256,WINDOWS-1256" => "Arabic",
			  "eu_utf8,Basque,eu_ES.UTF-8,Basque_Spain.1252,WINDOWS-1252" => "Basque",
			  "be_utf8,Belarusian,be_BY.UTF-8,Belarusian_Belarus.1251,WINDOWS-1251" => "Belarusian",
			  "bs_utf8,Bosnian,bs_BA.UTF-8,Serbian (Latin),WINDOWS-1250" => "Bosnian",
			  "bg_utf8,Bulgarian,bg_BG.UTF-8,Bulgarian_Bulgaria.1251,WINDOWS-1251" => "Bulgarian",
			  "ca_utf8,Catalan,ca_ES.UTF-8,Catalan_Spain.1252,WINDOWS-1252" => "Catalan",
			  "hr_utf8,Croatian,hr_HR.UTF-8,Croatian_Croatia.1250,WINDOWS-1250" => "Croatian",
			  "zh_cn_utf8,Chinese (Simplified),zh_CN.UTF-8,Chinese_China.936" => "Chinese (Simplified)",
			  "zh_tw_utf8,Chinese (Traditional),zh_TW.UTF-8,Chinese_Taiwan.950" => "Chinese (Traditional)",
			  "cs_utf8,Czech,cs_CZ.UTF-8,Czech_Czech Republic.1250,WINDOWS-1250" => "Czech",
			  "da_utf8,Danish,da_DK.UTF-8,Danish_Denmark.1252,WINDOWS-1252" => "Danish",
			  "nl_utf8,Dutch,nl_NL.UTF-8,Dutch_Netherlands.1252,WINDOWS-1252" => "Dutch",
			  "en_utf8,English,en.UTF-8,English_Australia.1252," => "English(Australia)",
			  "en_us_utf8,English (US)" => "English",
			  "et_utf8,Estonian,et_EE.UTF-8,Estonian_Estonia.1257,WINDOWS-1257" => "Estonian",
			  "fa_utf8,Farsi,fa_IR.UTF-8,Farsi_Iran.1256,WINDOWS-1256" => "Farsi",
			  "fil_utf8,Filipino,ph_PH.UTF-8,Filipino_Philippines.1252,WINDOWS-1252" => "Filipino",
			  "fi_utf8,Finnish,fi_FI.UTF-8,Finnish_Finland.1252,WINDOWS-1252" => "Finnish",
			  "fr_utf8,French,fr_FR.UTF-8,French_France.1252,WINDOWS-1252" => "French",
			  "fr_ca_utf8,French (Canada),fr_FR.UTF-8,French_Canada.1252" => "French (Canada)",
			  "ga_utf8,Gaelic,ga.UTF-8,Gaelic; Scottish Gaelic,WINDOWS-1252" => "Gaelic",
			  "gl_utf8,Gallego,gl_ES.UTF-8,Galician_Spain.1252,WINDOWS-1252" => "Gallego",
			  "ka_utf8,Georgian,ka_GE.UTF-8,Georgian_Georgia.65001" => "Georgian",
			  "de_utf8,German,de_DE.UTF-8,German_Germany.1252,WINDOWS-1252" => "German",
			  "el_utf8,Greek,el_GR.UTF-8,Greek_Greece.1253,WINDOWS-1253" => "Greek",
			  "gu_utf8,Gujarati,gu.UTF-8,Gujarati_India.0" => "Gujarati",
			  "he_utf8,Hebrew,he_IL.utf8,Hebrew_Israel.1255,WINDOWS-1255" => "Hebrew",
			  "hi_utf8,Hindi,hi_IN.UTF-8,Hindi.65001" => "Hindi",
			  "hu_utf8,Hungarian,hu.UTF-8,Hungarian_Hungary.1250,WINDOWS-1250" => "Hungarian",
			  "is_utf8,Icelandic,is_IS.UTF-8,Icelandic_Iceland.1252,WINDOWS-1252" => "Indonesian",
			  "id_utf8,Indonesian,id_ID.UTF-8,Indonesian_indonesia.1252,WINDOWS-1252" => "Indonesian",
			  "it_utf8,Italian,it_IT.UTF-8,Italian_Italy.1252,WINDOWS-1252" => "Italian",
			  "ja_utf8,Japanese,ja_JP.UTF-8,Japanese_Japan.932" => "Japanese",
			  "kn_utf8,Kannada,kn_IN.UTF-8,Kannada.65001" => "Kannada",
			  "km_utf8,Khmer,km_KH.UTF-8,Khmer.65001" => "Khmer",
			  "ko_utf8,Korean,ko_KR.UTF-8,Korean_Korea.949" => "Korean",
			  "lo_utf8,Lao,lo_LA.UTF-8,Lao_Laos.UTF-8,WINDOWS-1257" => "Lao",
			  "lt_utf8,Lithuanian,lt_LT.UTF-8,Lithuanian_Lithuania.1257,WINDOWS-1257" => "Lithuanian",
			  "lv_utf8,Latvian,lat.UTF-8,Latvian_Latvia.1257,WINDOWS-1257" => "Latvian",
			  "ml_utf8,Malayalam,ml_IN.UTF-8,Malayalam_India.x-iscii-ma" => "Malayalam",
			  "ms_utf8,Malaysian,ms_MY.UTF-8,Malay_malaysia.1252,WINDOWS-1252" => "Malaysian",
			  "mi_tn_utf8,Maori (Ngai Tahu),mi_NZ.UTF-8,Maori.1252,WINDOWS-1252" => "Maori (Ngai Tahu)",
			  "mi_wwow_utf8,Maori (Waikoto Uni),mi_NZ.UTF-8,Maori.1252,WINDOWS-1252" => "Maori (Waikoto Uni)",
			  "mn_utf8,Mongolian,mn.UTF-8,Cyrillic_Mongolian.1251" => "Mongolian",
			  "no_utf8,Norwegian,no_NO.UTF-8,Norwegian_Norway.1252,WINDOWS-1252" => "Norwegian",
			  "nn_utf8,Nynorsk,nn_NO.UTF-8,Norwegian-Nynorsk_Norway.1252,WINDOWS-1252" => "Nynorsk",
			  "pl_utf8,Polish,pl.UTF-8,Polish_Poland.1250,WINDOWS-1250" => "Polish",
			  "pt_utf8,Portuguese,pt_PT.UTF-8,Portuguese_Portugal.1252,WINDOWS-1252" => "Portuguese",
			  "pt_br_utf8,Portuguese (Brazil),pt_BR.UTF-8,Portuguese_Brazil.1252,WINDOWS-1252" => "Portuguese (Brazil)",
			  "ro_utf8,Romanian,ro_RO.UTF-8,Romanian_Romania.1250,WINDOWS-1250" => "Romanian",
			  "ru_utf8,Russian,ru_RU.UTF-8,Russian_Russia.1251,WINDOWS-1251" => "Russian",
			  "sm_utf8,Samoan,mi_NZ.UTF-8,Maori.1252,WINDOWS-1252" => "Samoan",
			  "sr_utf8,Serbian,sr_CS.UTF-8,Serbian (Cyrillic)_Serbia and Montenegro.1251,WINDOWS-1251" => "Serbian",
			  "sk_utf8,Slovak,sk_SK.UTF-8,Slovak_Slovakia.1250,WINDOWS-1250" => "Slovak",
			  "sl_utf8,Slovenian,sl_SI.UTF-8,Slovenian_Slovenia.1250,WINDOWS-1250" => "Slovenian",
			  "so_utf8,Somali,so_SO.UTF-8" => "Somali",
			  "es_utf8,Spanish (International),es_ES.UTF-8,Spanish_Spain.1252,WINDOWS-1252" => "Spanish",
			  "sv_utf8,Swedish,sv_SE.UTF-8,Swedish_Sweden.1252,WINDOWS-1252" => "Swedish",
			  "tl_utf8,Tagalog,tl.UTF-8" => "Tagalog",
			  "ta_utf8,Tamil,ta_IN.UTF-8,English_Australia.1252" => "Tamil",
			  "th_utf8,Thai,th_TH.UTF-8,Thai_Thailand.874,WINDOWS-874" => "Thai",
			  "to_utf8,Tongan,mi_NZ.UTF-8',Maori.1252,WINDOWS-1252" => "Tongan",
			  "tr_utf8,Turkish,tr_TR.UTF-8,Turkish_Turkey.1254,WINDOWS-1254" => "Turkish",
			  "uk_utf8,Ukrainian,uk_UA.UTF-8,Ukrainian_Ukraine.1251,WINDOWS-1251" => "Ukrainian",
			  "vi_utf8,Vietnamese,vi_VN.UTF-8,Vietnamese_Viet Nam.1258,WINDOWS-1258" => "Vietnamese",
			  );

		  return $lang;
	  }

	  /**
	   * Core::formatMoney()
	   *
	   * @param mixed $amount
	   * @param mixed $free
	   * @return
	   */
	  public function formatMoney($amount, $free = true)
	  {
		  $is_free = ($free == true) ? Lang::$word->FREE : "0.00";
		  return ($amount == 0) ? $is_free : $this->cur_symbol . number_format($amount, 2, '.', ',') . ' ' . $this->currency;
	  }

      /**
       * Core::verifyTxnId()
       *
       * @param mixed $txn_id
       * @return
       */
      public function verifyTxnId($txn_id)
      {

          $sql = Registry::get("Database")->query("SELECT id"
				. "\n FROM transactions"
				. "\n WHERE txn_id = '" . sanitize($txn_id) . "'"
				. "\n LIMIT 1");

          if (Registry::get("Database")->numrows($sql) > 0)
              return false;
          else
              return true;
      }

      /**
       * Core::getRowById()
       *
       * @param mixed $table
       * @param mixed $id
       * @param bool $and
       * @param bool $is_admin
       * @return
       */
      public static function getRowById($table, $id, $and = false, $is_admin = true)
      {
          $id = sanitize($id, 8, true);
          if ($and) {
              $sql = "SELECT * FROM " . (string )$table . " WHERE id = '" . Registry::get("Database")->escape((int)$id) . "' AND " . Registry::get("Database")->escape($and) . "";
          } else
              $sql = "SELECT * FROM " . (string )$table . " WHERE id = '" . Registry::get("Database")->escape((int)$id) . "'";

          $row = Registry::get("Database")->first($sql);

          if ($row) {
              return $row;
          } else {
              if ($is_admin)
                  Filter::error("You have selected an Invalid Id - #" . $id, "Core::getRowById()");
          }
      }

      /**
       * Core::getValueById()
       *
       * @param mixed $table
       * @param mixed $id
       * @param bool $and
       * @param bool $is_admin
       * @return
       */
      public static function getValueById($value, $table, $and = false, $is_admin = true)
      {
          if ($and) {
              $sql = "SELECT $value FROM " . (string )$table . " WHERE id = '" . Filter::$id . "' AND " . Registry::get("Database")->escape($and) . "";
          } else
              $sql = "SELECT $value FROM " . (string )$table . " WHERE id = '" . Filter::$id  . "'";

          $row = Registry::get("Database")->first($sql);

          if ($row) {
              return $row->$value;
          } else {
              if ($is_admin)
                  Filter::error("You have selected an Invalid Id - #" . Filter::$id, "Core::getValueById()");
          }
      }

      /**
       * Core::renderCartButton()
       *
       * @param mixed $txn_id
       * @return
       */
	  public static function renderCartButton($price, $pid, $ext, $class = false, $fluid = false)
	  {
		  $full = ($fluid) ? " fluid" : null;
		  $is_class = ($class) ? $class : "circular inverted";
		  if ($price == 0) {
			  if (Registry::get("Core")->free_allowed == 0 && !Registry::get("Users")->logged_in) {
				  print '<a href="' . SITEURL . '/login.php"><i class="icon ' . $is_class . ' lock"></i> ' . Lang::$word->LOGDOWN . '</a>';
			  } else {
				  print '<a href="' . SITEURL . '/download.php?fid=' . $pid . '"><i class="icon ' . $is_class . ' download alt"></i> ' . Lang::$word->DOWNLOAD . '</a>';
			  }
		  } else {

			  if ($ext) {
				  print '<a target="_blank" href="' . $ext . '"><i class="icon ' . $is_class . ' sign out"></i> ' . Lang::$word->VSITE . '</a>';
			  } else {
				  print '<a data-cart-item="true" data-id="' . $pid . '" rel="nofollow"><i class="icon ' . $is_class . ' shopping cart"></i> ' . Lang::$word->ADDCART . '</a>';
			  }
		  }
	  }

	  /**
	   * Core::layoutSwitchList()
	   *
	   * @return
	   */
	  public static function layoutSwitchList($mode)
	  {
		  $active = '';
		  if (isset($_COOKIE[$mode . 'VIEW_DDP']) && $_COOKIE[$mode . 'VIEW_DDP'] == 'list') {
			  $active = ' active';
		  } elseif(Registry::get('Core')->hlayout == 0 && !isset($_COOKIE[$mode . 'VIEW_DDP'])) {
			  $active = ' active';
		  }

		  return $active;
	  }

	  /**
	   * Core::layoutSwitchGrid()
	   *
	   * @return
	   */
	  public static function layoutSwitchGrid($mode)
	  {
		  $active = '';
		  if (isset($_COOKIE[$mode . 'VIEW_DDP']) && $_COOKIE[$mode . 'VIEW_DDP'] == 'grid') {
			  $active = ' active';
		  } elseif(Registry::get('Core')->hlayout == 1 && !isset($_COOKIE[$mode . 'VIEW_DDP'])) {
			  $active = ' active';
		  }

		  return $active;
	  }

	  /**
	   * Core::loopOptions()
	   *
	   * @param mixed $array
	   * @return
	   */
	  public static function loopOptions($array, $key, $value,  $selected = false)
	  {
		  $html = '';
          if (is_array($array)) {
			  foreach ($array as $row) {
				  $html .= "<option value=\"" . $row->$key . "\"";
				  $html .= ($row->$key == $selected) ? ' selected="selected"' : '';
				  $html .= ">" . $row->$value . "</option>\n";
			  }
			  return $html;
          }
		  return false;
	  }

	  /**
	   * Core::downloadErrors()
	   *
	   * @return
	   */
	  public static function downloadErrors()
	  {
		  print '<div id="showerror" style="display:none"> ';
		  switch($_GET['msg']) {
			  case 1 :
				print '<p class="bluetip">' . Lang::$word->D_ERROR1 . '</p>';
			  break;

			  case 2 :
				print '<p class="bluetip">' . Lang::$word->D_ERROR2 . '</p>';
			  break;

			  case 3 :
				print '<p class="bluetip">' . Lang::$word->D_ERROR3 . '</p>';
			  break;

			  case 4 :
				print '<p class="bluetip">' . Lang::$word->D_ERROR4 . '</p>';
			  break;

			  case 5 :
				print '<p class="redtip">' . str_replace("[IP]", $_SERVER['REMOTE_ADDR'],Lang::$word->D_ERROR5) . '</p>';
			  break;

			  case 6 :
				print '<p class="redtip">' . str_replace("[IP]", $_SERVER['REMOTE_ADDR'],Lang::$word->D_ERROR6) . '</p>';
			  break;
		  }
		  print '</div>';
	  }
  }
?>
