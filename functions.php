<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  /**
   * redirect_to()
   *
   * @param mixed $location
   * @return
   */
  function redirect_to($location)
  {
      if (!headers_sent()) {
          header('Location: ' . $location);
		  exit;
	  } else
          echo '<script type="text/javascript">';
          echo 'window.location.href="' . $location . '";';
          echo '</script>';
          echo '<noscript>';
          echo '<meta http-equiv="refresh" content="0;url=' . $location . '" />';
          echo '</noscript>';
  }

  function dodate($date) {
	$format = '%b %d, %Y';
	return strftime($format, strtotime($date));
  }

  /**
   * countEntries()
   *
   * @param mixed $table
   * @param string $where
   * @param string $what
   * @return
   */
  function countEntries($table, $where = '', $what = '')
  {
      if (!empty($where) && isset($what)) {
          $q = "SELECT COUNT(*) FROM " . $table . "  WHERE " . $where . " = '" . $what . "' LIMIT 1";
      } else
          $q = "SELECT COUNT(*) FROM " . $table . " LIMIT 1";

      $record = Registry::get("Database")->query($q);
      $total = Registry::get("Database")->fetchrow($record);
      return $total[0];
  }

  /**
   * getChecked()
   *
   * @param mixed $row
   * @param mixed $status
   * @return
   */
  function getChecked($row, $status)
  {
      if ($row == $status) {
          echo "checked=\"checked\"";
      }
  }

  /**
   * post()
   *
   * @param mixed $var
   * @return
   */
  function post($var)
  {
      if (isset($_POST[$var]))
          return $_POST[$var];
  }

  /**
   * get()
   *
   * @param mixed $var
   * @return
   */
  function get($var)
  {
      if (isset($_GET[$var]))
          return $_GET[$var];
  }

  /**
   * sanitize()
   *
   * @param mixed $string
   * @param bool $trim
   * @return
   */
  function sanitize($string, $trim = false,  $end_char = '&#8230;', $int = false, $str = false)
  {
      $string = filter_var($string, FILTER_SANITIZE_STRING);
      $string = trim($string);
      $string = stripslashes($string);
      $string = strip_tags($string);
      $string = str_replace(array('‘', '’', '“', '”'), array("'", "'", '"', '"'), $string);

	  if ($trim) {
        if (strlen($string) < $trim)
        {
            return $string;
        }

        $string = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $string));

        if (strlen($string) <= $trim)
        {
            return $string;
        }

        $out = "";
        foreach (explode(' ', trim($string)) as $val)
        {
            $out .= $val.' ';

            if (strlen($out) >= $trim)
            {
                $out = trim($out);
                return (strlen($out) == strlen($string)) ? $out : $out.$end_char;
            }
        }

          //$string = substr($string, 0, $trim);

	  }
      if ($int)
		  $string = preg_replace("/[^0-9\s]/", "", $string);
      if ($str)
		  $string = preg_replace("/[^a-zA-Z\s]/", "", $string);

      return $string;
  }

  /**
   * truncate()
   *
   * @param mixed $string
   * @param mixed $length
   * @param bool $ellipsis
   * @return
   */
  function truncate($string, $length, $ellipsis = true)
  {
      $wide = strlen(preg_replace('/[^A-Z0-9_@#%$&]/', '', $string));
      $length = round($length - $wide * 0.2);
      $clean_string = preg_replace('/&[^;]+;/', '-', $string);
      if (strlen($clean_string) <= $length)
          return $string;
      $difference = $length - strlen($clean_string);
      $result = substr($string, 0, $difference);
      if ($result != $string and $ellipsis) {
          $result = add_ellipsis($result);
      }
      return $result;
  }

  /**
   * add_ellipsis()
   *
   * @param mixed $string
   * @return
   */
  function add_ellipsis($string)
  {
      $string = substr($string, 0, strlen($string) - 3);
      return trim(preg_replace('/ .{1,3}$/', '', $string)) . '...';
  }

  /**
   * getValue()
   *
   * @param mixed $stwhatring
   * @param mixed $table
   * @param mixed $where
   * @return
   */
  function getValue($what, $table, $where)
  {
      $sql = "SELECT $what FROM $table WHERE $where";
      $row = Registry::get("Database")->first($sql);
      return ($row) ? $row->$what : '';
  }

  /**
   * getValueById()
   *
   * @param mixed $what
   * @param mixed $table
   * @param mixed $id
   * @return
   */
  function getValueById($what, $table, $id)
  {
      $sql = "SELECT $what FROM $table WHERE id = $id";
      $row = Registry::get("Database")->first($sql);
      return ($row) ? $row->$what : '';
  }

  /**
   * tooltip()
   *
   * @param mixed $tip
   * @return
   */
  function tooltip($tip)
  {
      return '<img src="'.ADMINURL.'/images/tooltip.png" alt="Tip" class="tooltip" title="' . $tip . '" />';
  }

  /**
   * required()
   *
   * @return
   */
  function required()
  {
      return '<img src="'.ADMINURL.'/images/required.png" alt="Required Field" class="tooltip" title="Required Field" />';
  }

  /**
   * cleanOut()
   *
   * @param mixed $text
   * @return
   */
  function cleanOut($text) {
	 $text =  strtr($text, array('\r\n' => "", '\r' => "", '\n' => ""));
	 $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
	 //$text = str_replace('<br>', '<br />', $text);
	 return stripslashes($text);
  }

  /**
   * cleanSanitize()
   *
   * @param mixed $string
   * @param bool $trim
   * @return
   */
  function cleanSanitize($string, $trim = false,  $end_char = '&#8230;')
  {
	  $string = cleanOut($string);
      $string = filter_var($string, FILTER_SANITIZE_STRING);
      $string = trim($string);
      $string = stripslashes($string);
      $string = strip_tags($string);
      $string = str_replace(array('‘', '’', '“', '”'), array("'", "'", '"', '"'), $string);

	  if ($trim) {
        if (strlen($string) < $trim)
        {
            return $string;
        }

        $string = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $string));

        if (strlen($string) <= $trim)
        {
            return $string;
        }

        $out = "";
        foreach (explode(' ', trim($string)) as $val)
        {
            $out .= $val.' ';

            if (strlen($out) >= $trim)
            {
                $out = trim($out);
                return (strlen($out) == strlen($string)) ? $out : $out.$end_char;
            }
        }
	  }
      return $string;
  }


  /**
   * phpself()
   *
   * @return
   */
  function phpself()
  {
      return htmlspecialchars($_SERVER['PHP_SELF']);
  }

  /**
   * alphaBits()
   *
   * @param bool $all
   * @param array $vars
   * @return
   */
  function alphaBits($all = false, $vars)
  {
      if (!empty($_SERVER['QUERY_STRING'])) {
          $parts = explode("&amp;", $_SERVER['QUERY_STRING']);
          $vars = str_replace(" ", "", $vars);
          $c_vars = explode(",", $vars);
          $newParts = array();
          foreach ($parts as $val) {
              $val_parts = explode("=", $val);
              if (!in_array($val_parts[0], $c_vars)) {
                  array_push($newParts, $val);
              }
          }
          if (count($newParts) != 0) {
              $qs = "&amp;" . implode("&amp;", $newParts);
          } else {
              return false;
          }

		  $html = '';
          $charset = explode(",", Lang::$word->ALPHA);
          $html .= "<div class=\"wojo small pagination menu\">\n";
          foreach ($charset as $key) {
			  $active = ($key == get('letter')) ? ' active' : null;
              $html .= "<a class=\"item$active\" href=\"" . phpself() . "?letter=" . $key . $qs . "\">" . $key . "</a>\n";
          }
          $viewAll = ($all === false) ? phpself() : $all;
          $html .= "<a class=\"item\" href=\"" . $viewAll . "\">" . Lang::$word->VIEWALL . "</a>\n";
          $html .= "</div>\n";
		  unset($key);

		  return $html;
	  } else {
		  return false;
	  }
  }

  /**
   * isAdmin()
   *
   * @param mixed $userlevel
   * @return
   */
  function isAdmin($userlevel)
  {
	  switch ($userlevel) {
		  case 9:
		     $display = '<img src="'.SITEURL.'/images/superadmin.png" alt="" class="tooltip" title="Super Admin"/>';
			 break;

		  case 7:
		     $display = '<img src="'.SITEURL.'/images/level7.png" alt="" class="tooltip" title="User Level 7"/>';
			 break;

		  case 6:
		     $display = '<img src="'.SITEURL.'/images/level6.png" alt="" class="tooltip" title="User Level 6"/>';
			 break;

		  case 5:
		     $display = '<img src="'.SITEURL.'/images/level5.png" alt="" class="tooltip" title="User Level 5"/>';
			 break;

		  case 4:
		     $display = '<img src="'.SITEURL.'/images/level4.png" alt="" class="tooltip" title="User Level 4"/>';
			 break;

		  case 3:
		     $display = '<img src="'.SITEURL.'/images/level6.png" alt="" class="tooltip" title="User Level 3"/>';
			 break;

		  case 2:
		     $display = '<img src="'.SITEURL.'/images/level5.png" alt="" class="tooltip" title="User Level 2"/>';
			 break;

		  case 1:
		     $display = '<img src="'.SITEURL.'/images/user.png" alt="" class="tooltip" title="User"/>';
			 break;
	  }

      return $display;;
  }

  /**
   * userStatus()
   *
   * @param mixed $id
   * @return
   */
  function userStatus($status, $id)
  {
      switch ($status) {
          case "y":
              $display = '<span class="wojo positive label">' . Lang::$word->ACTIVE . '</span>';
              break;

          case "n":
              $display = '<a data-id="' . $id . '" class="activate wojo info label"><i class="icon adjust"></i> ' . Lang::$word->INACTIVE . '</a>';
              break;

          case "t":
              $display = '<span class="wojo warning label">' . Lang::$word->PENDING . '</span>';
              break;

          case "b":
              $display = '<span class="wojo negative label">' . Lang::$word->BANNED . '</span>';
              break;
      }

      return $display;
  }

  function jobFeatured($featured, $id)
  {
      switch ($featured) {
          case 'notfeatured':
              $display = '<a id="jobFeatured_' . $id . '" data-id="' . $id . '" class="toggleJobFeatured wojo notfeatured label"><i class="icon star"></i></a>';
              break;

          case 'featured':
               $display = '<a id="jobFeatured_' . $id . '" data-id="' . $id . '" class="toggleJobFeatured wojo featured label"><i class="icon star"></i> Featured</a>';
              break;
      }

      return $display;
  }

  function resumeFeatured($featured, $id)
  {
      switch ($featured) {
          case 'notfeatured':
              $display = '<a id="resumeFeatured_' . $id . '" data-id="' . $id . '" class="toggleResumeFeatured wojo notfeatured label"><i class="icon star"></i> Not Featured</a>';
              break;

          case 'featured':
               $display = '<a id="resumeFeatured_' . $id . '" data-id="' . $id . '" class="toggleResumeFeatured wojo featured label"><i class="icon star"></i> Featured</a>';
              break;
      }

      return $display;
  }

  function jobStatus($status, $id)
  {
      switch ($status) {
          case "approved":
              $display = '<span class="wojo approved label">Approved</span>';
              break;

          case "pending":
              $display = '<a id="jobStatus_' . $id . '" data-id="' . $id . '" class="activate wojo pending label"><i class="icon adjust"></i> Pending</a>';
              break;

          case "declined":
              $display = '<span class="wojo declined label">Declined</span>';
              break;
      }

      return $display;
  }


  function jobType($status, $id)
  {
      switch ($status) {
          case "approved":
              $display = '<span class="wojo approved label">Approved</span>';
              break;

          case "pending":
              $display = '<a data-id="' . $id . '" class="activate wojo pending label"><i class="icon adjust"></i> Pending</a>';
              break;

          case "declined":
              $display = '<span class="wojo declined label">Declined</span>';
              break;
      }

      return $display;
  }

  /**
   * isActive()
   *
   * @param mixed $id
   * @return
   */
  function isActive($id)
  {
      if ($id == 1) {
          $display = '<i data-content="' . Lang::$word->ACTIVE . '" class="circular inverted icon check"></i>';
      } else {
          $display = '<i data-content="' . Lang::$word->PENDING . '" class="circular inverted icon time"></i>';
      }

      return $display;
  }

  /**
   * getTemplates()
   *
   * @param mixed $dir
   * @param mixed $site
   * @return
   */
  function getTemplates($dir, $site)
  {
      $getDir = dir($dir);
      while (false !== ($templDir = $getDir->read())) {
          if ($templDir != "." && $templDir != ".." && $templDir != "index.php") {
              $selected = ($site == $templDir) ? " selected=\"selected\"" : "";
              echo "<option value=\"{$templDir}\"{$selected}>{$templDir}</option>\n";
          }
      }
      $getDir->close();
  }

  /**
   * timesince()
   *
   * @param int $original
   * @return
   */
  function timesince($original)
  {
      // array of time period chunks
      $chunks = array(
          array(60 * 60 * 24 * 365, 'year'),
          array(60 * 60 * 24 * 30, 'month'),
          array(60 * 60 * 24 * 7, 'week'),
          array(60 * 60 * 24, 'day'),
          array(60 * 60, 'hour'),
          array(60, 'min'),
          array(1, 'sec'),
          );

      $today = time();
       /* Current unix time  */
      $since = $today - $original;

      // $j saves performing the count function each time around the loop
      for ($i = 0, $j = count($chunks); $i < $j; $i++) {
          $seconds = $chunks[$i][0];
          $name = $chunks[$i][1];

          // finding the biggest chunk (if the chunk fits, break)
          if (($count = floor($since / $seconds)) != 0) {
              break;
          }
      }

      $print = ($count == 1) ? '1 ' . $name : "$count {$name}s";

      if ($i + 1 < $j) {
          // now getting the second item
          $seconds2 = $chunks[$i + 1][0];
          $name2 = $chunks[$i + 1][1];

          // add second item if its greater than 0
          if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
              $print .= ($count2 == 1) ? ', 1 ' . $name2 : " $count2 {$name2}s";
          }
      }
      return $print . ' ' . Lang::$word->AGO;
  }

  /**
   * compareFloatNumbers()
   *
   * @param mixed $float1
   * @param mixed $float2
   * @param string $operator
   * @return
   */
  function compareFloatNumbers($float1, $float2, $operator='=')
  {
	  // Check numbers to 5 digits of precision
	  $epsilon = 0.00001;

	  $float1 = (float)$float1;
	  $float2 = (float)$float2;

	  switch ($operator)
	  {
		  // equal
		  case "=":
		  case "eq":
			  if (abs($float1 - $float2) < $epsilon) {
				  return true;
			  }
			  break;
		  // less than
		  case "<":
		  case "lt":
			  if (abs($float1 - $float2) < $epsilon) {
				  return false;
			  } else {
				  if ($float1 < $float2) {
					  return true;
				  }
			  }
			  break;
		  // less than or equal
		  case "<=":
		  case "lte":
			  if (compareFloatNumbers($float1, $float2, '<') || compareFloatNumbers($float1, $float2, '=')) {
				  return true;
			  }
			  break;
		  // greater than
		  case ">":
		  case "gt":
			  if (abs($float1 - $float2) < $epsilon) {
				  return false;
			  } else {
				  if ($float1 > $float2) {
					  return true;
				  }
			  }
			  break;
		  // greater than or equal
		  case ">=":
		  case "gte":
			  if (compareFloatNumbers($float1, $float2, '>') || compareFloatNumbers($float1, $float2, '=')) {
				  return true;
			  }
			  break;

		  case "<>":
		  case "!=":
		  case "ne":
			  if (abs($float1 - $float2) > $epsilon) {
				  return true;
			  }
			  break;
		  default:
			  die("Unknown operator '".$operator."' in compareFloatNumbers()");
	  }

	  return false;
  }

  /**
   * getSize()
   *
   * @param mixed $size
   * @param integer $precision
   * @param bool $long_name
   * @param bool $real_size
   * @return
   */
  function getSize($size, $precision = 2, $long_name = false, $real_size = true)
  {
	  $base = $real_size ? 1024 : 1000;
	  $pos = 0;
	  while ($size > $base) {
		  $size /= $base;
		  $pos++;
	  }
	  $prefix = _getSizePrefix($pos);
	  @$size_name = ($long_name) ? $prefix . "bytes" : $prefix[0] . "B";
	  return round($size, $precision) . ' ' . ucfirst($size_name);
  }

  /**
   * _getSizePrefix()
   *
   * @param mixed $pos
   * @return
   */
  function _getSizePrefix($pos)
  {
	  switch ($pos) {
		  case 00:
			  return "";
		  case 01:
			  return "kilo";
		  case 02:
			  return "mega";
		  case 03:
			  return "giga";
		  default:
			  return "?-";
	  }
  }

  /**
   * getFileType()
   *
   * @param mixed $filename
   * @return
   */
  function getFileType($filename)
  {
	  if (preg_match("/^.*\.(jpg|jpeg|png|gif|bmp)$/i", $filename) != 0) {
		  return 'image';

	  } elseif (preg_match("/^.*\.(txt|css|php|sql|js)$/i", $filename) != 0) {
		  return 'text';

	  } elseif (preg_match("/^.*\.(zip)$/i", $filename) != 0) {
		  return 'zip';
	  }
	  return 'generic';
  }
  /**
   * getMIMEtype()
   *
   * @param mixed $filename
   * @return
   */
  function getMIMEtype($filename)
  {
	  preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);

	  $fs = (isset($fileSuffix[1])) ? $fileSuffix[1] : null;

	  switch(strtolower($fs))
	  {
		  case "js" :
			  return "application/x-javascript";

		  case "json" :
			  return "application/json";

		  case "jpg" :
		  case "jpeg" :
		  case "jpe" :
			  return "image/jpg";

		  case "png" :
		  case "gif" :
		  case "bmp" :
		  case "tiff" :
			  return "image/".strtolower($fs);

		  case "css" :
			  return "text/css";

		  case "xml" :
			  return "application/xml";

		  case "doc" :
		  case "docx" :
			  return "application/msword";

		  case "xls" :
		  case "xlt" :
		  case "xlm" :
		  case "xld" :
		  case "xla" :
		  case "xlc" :
		  case "xlw" :
		  case "xll" :
			  return "application/vnd.ms-excel";

		  case "ppt" :
		  case "pps" :
			  return "application/vnd.ms-powerpoint";

		  case "rtf" :
			  return "application/rtf";

		  case "pdf" :
			  return "application/pdf";

		  case "html" :
		  case "htm" :
		  case "php" :
			  return "text/html";

		  case "txt" :
			  return "text/plain";

		  case "mpeg" :
		  case "mpg" :
		  case "mpe" :
			  return "video/mpeg";

		  case "mp3" :
			  return "audio/mpeg3";

		  case "wav" :
			  return "audio/wav";

		  case "aiff" :
		  case "aif" :
			  return "audio/aiff";

		  case "avi" :
			  return "video/msvideo";

		  case "wmv" :
			  return "video/x-ms-wmv";

		  case "mov" :
			  return "video/quicktime";

		  case "zip" :
			  return "application/zip";

		  case "tar" :
			  return "application/x-tar";

		  case "swf" :
			  return "application/x-shockwave-flash";

		  default :
		  if(function_exists("mime_content_type"))
		  {
			  $fileSuffix = mime_content_type($filename);
		  }

		  return "unknown/" . trim($fs, ".");
	  }
  }

  /**
   * randName()
   *
   * @return
   */
  function siteurl(){
	  if(isset($_SERVER['HTTPS'])){
		  $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
	  }
	  else{
		  $protocol = 'http';
	  }
	    $ds = '/';
		$url = preg_replace("#/+#", "/", $_SERVER['HTTP_HOST'] . $ds . Registry::get("Core")->site_dir);

	  return $protocol . "://" . $url;
  }

  /**
   * randName()
   *
   * @return
   */
  function randName() {
	  $code = '';
	  for($x = 0; $x<6; $x++) {
		  $code .= '-'.substr(strtoupper(sha1(rand(0,999999999999999))),2,6);
	  }
	  $code = substr($code,1);
	  return $code;
  }

  /**
   * downloadFile()
   *
   * @return
   */
  function downloadFile($fileLocation, $fileName, $update = false, $tid = 1, $maxSpeed = 1024)
  {
      if (connection_status() != 0)
          return (false);
      //$extension = strtolower(end(explode('.', $fileName)));
	  $extension = strtolower(substr($fileName, strrpos($fileName, '.') + 1));

      /* List of File Types */
      $fileTypes['swf'] = 'application/x-shockwave-flash';
      $fileTypes['pdf'] = 'application/pdf';
      $fileTypes['exe'] = 'application/octet-stream';
      $fileTypes['zip'] = 'application/zip';
      $fileTypes['doc'] = 'application/msword';
      $fileTypes['xls'] = 'application/vnd.ms-excel';
      $fileTypes['ppt'] = 'application/vnd.ms-powerpoint';
      $fileTypes['gif'] = 'image/gif';
      $fileTypes['png'] = 'image/png';
      $fileTypes['jpeg'] = 'image/jpg';
      $fileTypes['jpg'] = 'image/jpg';
      $fileTypes['rar'] = 'application/rar';

      $fileTypes['ra'] = 'audio/x-pn-realaudio';
      $fileTypes['ram'] = 'audio/x-pn-realaudio';
      $fileTypes['ogg'] = 'audio/x-pn-realaudio';

      $fileTypes['wav'] = 'video/x-msvideo';
      $fileTypes['wmv'] = 'video/x-msvideo';
      $fileTypes['avi'] = 'video/x-msvideo';
      $fileTypes['asf'] = 'video/x-msvideo';
      $fileTypes['divx'] = 'video/x-msvideo';

      $fileTypes['mp3'] = 'audio/mpeg';
      $fileTypes['mp4'] = 'audio/mpeg';
      $fileTypes['mpeg'] = 'video/mpeg';
      $fileTypes['mpg'] = 'video/mpeg';
      $fileTypes['mpe'] = 'video/mpeg';
      $fileTypes['mov'] = 'video/quicktime';
      $fileTypes['swf'] = 'video/quicktime';
      $fileTypes['3gp'] = 'video/quicktime';
      $fileTypes['m4a'] = 'video/quicktime';
      $fileTypes['aac'] = 'video/quicktime';
      $fileTypes['m3u'] = 'video/quicktime';

      $contentType = $fileTypes[$extension];


      header("Cache-Control: public");
      header("Content-Transfer-Encoding: binary\n");
      header('Content-Type: $contentType');

      $contentDisposition = 'attachment';

      if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
          $fileName = preg_replace('/\./', '%2e', $fileName, substr_count($fileName, '.') - 1);
          header("Content-Disposition: $contentDisposition;filename=\"$fileName\"");
      } else {
          header("Content-Disposition: $contentDisposition;filename=\"$fileName\"");
      }

      header("Accept-Ranges: bytes");
      $range = 0;
      $size = filesize($fileLocation);

      if (isset($_SERVER['HTTP_RANGE'])) {
          list($a, $range) = explode("=", $_SERVER['HTTP_RANGE']);
          str_replace($range, "-", $range);
          $size2 = $size - 1;
          $new_length = $size - $range;
          header("HTTP/1.1 206 Partial Content");
          header("Content-Length: $new_length");
          header("Content-Range: bytes $range$size2/$size");
      } else {
          $size2 = $size - 1;
          header("Content-Range: bytes 0-$size2/$size");
          header("Content-Length: " . $size);
      }

      if ($size == 0) {
          die('Zero byte file! Aborting download');
      }

      $fp = fopen("$fileLocation", "rb");

      fseek($fp, $range);

      while (!feof($fp) and (connection_status() == 0)) {
          set_time_limit(0);
          print (fread($fp, 1024 * $maxSpeed));
          flush();
          @ob_flush();
          sleep(1);
      }
      fclose($fp);

	  if($update){
		  $data['downloads'] = "INC(1)";
		  Registry::get("Database")->update(Products::tTable, $data, "id='" . $tid . "'");
	  }
      exit;

      return ((connection_status() == 0) and !connection_aborted());
  }


  $params = array(
      'icons' => array(
        'fa fa-left'               => 'Left',
        'fa fa-right'              => 'Right',
        'fa fa-add-sign-box'       => 'Add Sign Box',
        'fa fa-add-sign'           => 'Add Sign',
        'fa fa-add'                => 'Add',
        'fa fa-adjust'             => 'Adjust',
        'fa fa-adn'=>'Adn',
        'fa fa-align-center'=>'Align Center',
        'fa fa-align-justify'=>'Align Justify',
        'fa fa-align-left'=>'Align Left',
        'fa fa-align-right'=>'Align Right',
        'fa fa-ambulance'=> 'Ambulance',
        'fa fa-anchor'=>'Anchor',
        'fa fa-android'=>'Android',
        'fa fa-angle-down'=>'Angle Down',
        'fa fa-angle-left'=>'Angle Left',
        'fa fa-angle-right'=>'Angle Right',
        'fa fa-angle-up'=>'Angle Up',
        'fa fa-apple'=>'Apple',
        'fa fa-archive'=>'Archive',
        'fa fa-arrow-down'=>'Arrow Down',
        'fa fa-arrow-left'=>'Arrow Left',
        'fa fa-arrow-right'=>'Arrow Right',
        'fa fa-arrow-sign-down'=>'Arrow Sign Down',
        'fa fa-arrow-sign-left'=>'Arrow Sign Left',
        'fa fa-arrow-sign-right'=>'Arrow Sign Right',
        'fa fa-arrow-sign-up'=>'Arrow Sign Up',
        'fa fa-arrow-up'=>'Arrow Up',
        'fa fa-line-chart'=>'Line Chart',
        'fa fa-building-o'=>'Building',
        'fa fa-graduation-cap'=>'Graduation Cap',
        'fa fa-medkit'=>'Medical Kit',
        'fa fa-bars'=>'Bars',



        'ln ln-icon-Add-Bag' => 'Add Bag',
        'ln ln-icon-Add-Basket' => 'Add Basket',
        'ln ln-icon-Add-Cart' => 'Add Cart',
        'ln ln-icon-Add-File' => 'Add File',
        'ln ln-icon-Address-Book' => 'Address Book',
        'ln ln-icon-Address-Book2' => 'Address Book2',
        'ln ln-icon-Administrator' => 'Administrator',
        'ln ln-icon-Aerobics-2' => 'Aerobics 2',
        'ln ln-icon-Aerobics-3' => 'Aerobics 3',
        'ln ln-icon-Aerobics' => 'Aerobics',
        'ln ln-icon-Affiliate' => 'Affiliate',
        'ln ln-icon-Aim' => 'Aim',
        'ln ln-icon-Air-Balloon' => 'Air Balloon',
        'ln ln-icon-Airbrush' => 'Airbrush',
        'ln ln-icon-Airship' => 'Airship',
        'ln ln-icon-Alarm-Clock' => 'Alarm Clock',
        'ln ln-icon-Alarm-Clock2' => 'Alarm Clock2',
        'ln ln-icon-Alarm' => 'Alarm',
        'ln ln-icon-Alien-2' => 'Alien 2',
        'ln ln-icon-Alien' => 'Alien',
        'ln ln-icon-Wordpress' => 'Wordpress',
        'ln ln-icon-Code-Window' => 'Code Window',
        'ln ln-icon-Coding' => 'Coding',
        'ln ln-icon-Android' => 'Android',
        'ln ln-icon-Cloud-Secure' => 'Cloud Secure',
        'ln ln-icon-Pencil-Ruler' => 'Pencil Ruler',
        'ln ln-icon-Plane' => 'Plane',
        'ln ln-icon-Email' => 'Email',
        'ln ln-icon-Target-Market' => 'Target Market',
        'ln ln-icon-Target' => 'Target',



        /*
        ln ln-icon-Aligator:before {
            content: "\e61c"
        }

        ln ln-icon-Align-Center:before {
            content: "\e61d"
        }

        ln ln-icon-Align-JustifyAll:before {
            content: "\e61e"
        }

        ln ln-icon-Align-JustifyCenter:before {
            content: "\e61f"
        }

        ln ln-icon-Align-JustifyLeft:before {
            content: "\e620"
        }

        ln ln-icon-Align-JustifyRight:before {
            content: "\e621"
        }

        ln ln-icon-Align-Left:before {
            content: "\e622"
        }

        ln ln-icon-Align-Right:before {
            content: "\e623"
        }

        ln ln-icon-Alpha:before {
            content: "\e624"
        }

        ln ln-icon-Ambulance:before {
            content: "\e625"
        }

        ln ln-icon-AMX:before {
            content: "\e626"
        }

        ln ln-icon-Anchor-2:before {
            content: "\e627"
        }

        ln ln-icon-Anchor:before {
            content: "\e628"
        }

        ln ln-icon-Android-Store:before {
            content: "\e629"
        }



        ln ln-icon-Angel-Smiley:before {
            content: "\e62b"
        }

        ln ln-icon-Angel:before {
            content: "\e62c"
        }

        ln ln-icon-Angry:before {
            content: "\e62d"
        }

        ln ln-icon-Apple-Bite:before {
            content: "\e62e"
        }

        ln ln-icon-Apple-Store:before {
            content: "\e62f"
        }

        ln ln-icon-Apple:before {
            content: "\e630"
        }

        ln ln-icon-Approved-Window:before {
            content: "\e631"
        }

        ln ln-icon-Aquarius-2:before {
            content: "\e632"
        }

        ln ln-icon-Aquarius:before {
            content: "\e633"
        }

        ln ln-icon-Archery-2:before {
            content: "\e634"
        }

        ln ln-icon-Archery:before {
            content: "\e635"
        }

        ln ln-icon-Argentina:before {
            content: "\e636"
        }

        ln ln-icon-Aries-2:before {
            content: "\e637"
        }

        ln ln-icon-Aries:before {
            content: "\e638"
        }

        ln ln-icon-Army-Key:before {
            content: "\e639"
        }

        ln ln-icon-Arrow-Around:before {
            content: "\e63a"
        }

        ln ln-icon-Arrow-Back3:before {
            content: "\e63b"
        }

        ln ln-icon-Arrow-Back:before {
            content: "\e63c"
        }

        ln ln-icon-Arrow-Back2:before {
            content: "\e63d"
        }

        ln ln-icon-Arrow-Barrier:before {
            content: "\e63e"
        }

        ln ln-icon-Arrow-Circle:before {
            content: "\e63f"
        }

        ln ln-icon-Arrow-Cross:before {
            content: "\e640"
        }

        ln ln-icon-Arrow-Down:before {
            content: "\e641"
        }

        ln ln-icon-Arrow-Down2:before {
            content: "\e642"
        }

        ln ln-icon-Arrow-Down3:before {
            content: "\e643"
        }

        ln ln-icon-Arrow-DowninCircle:before {
            content: "\e644"
        }

        ln ln-icon-Arrow-Fork:before {
            content: "\e645"
        }

        ln ln-icon-Arrow-Forward:before {
            content: "\e646"
        }

        ln ln-icon-Arrow-Forward2:before {
            content: "\e647"
        }

        ln ln-icon-Arrow-From:before {
            content: "\e648"
        }

        ln ln-icon-Arrow-Inside:before {
            content: "\e649"
        }

        ln ln-icon-Arrow-Inside45:before {
            content: "\e64a"
        }

        ln ln-icon-Arrow-InsideGap:before {
            content: "\e64b"
        }

        ln ln-icon-Arrow-InsideGap45:before {
            content: "\e64c"
        }

        ln ln-icon-Arrow-Into:before {
            content: "\e64d"
        }

        ln ln-icon-Arrow-Join:before {
            content: "\e64e"
        }

        ln ln-icon-Arrow-Junction:before {
            content: "\e64f"
        }

        ln ln-icon-Arrow-Left:before {
            content: "\e650"
        }

        ln ln-icon-Arrow-Left2:before {
            content: "\e651"
        }

        ln ln-icon-Arrow-LeftinCircle:before {
            content: "\e652"
        }

        ln ln-icon-Arrow-Loop:before {
            content: "\e653"
        }

        ln ln-icon-Arrow-Merge:before {
            content: "\e654"
        }

        ln ln-icon-Arrow-Mix:before {
            content: "\e655"
        }

        ln ln-icon-Arrow-Next:before {
            content: "\e656"
        }

        ln ln-icon-Arrow-OutLeft:before {
            content: "\e657"
        }

        ln ln-icon-Arrow-OutRight:before {
            content: "\e658"
        }

        ln ln-icon-Arrow-Outside:before {
            content: "\e659"
        }

        ln ln-icon-Arrow-Outside45:before {
            content: "\e65a"
        }

        ln ln-icon-Arrow-OutsideGap:before {
            content: "\e65b"
        }

        ln ln-icon-Arrow-OutsideGap45:before {
            content: "\e65c"
        }

        ln ln-icon-Arrow-Over:before {
            content: "\e65d"
        }

        ln ln-icon-Arrow-Refresh:before {
            content: "\e65e"
        }

        ln ln-icon-Arrow-Refresh2:before {
            content: "\e65f"
        }

        ln ln-icon-Arrow-Right:before {
            content: "\e660"
        }

        ln ln-icon-Arrow-Right2:before {
            content: "\e661"
        }

        ln ln-icon-Arrow-RightinCircle:before {
            content: "\e662"
        }

        ln ln-icon-Arrow-Shuffle:before {
            content: "\e663"
        }

        ln ln-icon-Arrow-Squiggly:before {
            content: "\e664"
        }

        ln ln-icon-Arrow-Through:before {
            content: "\e665"
        }

        ln ln-icon-Arrow-To:before {
            content: "\e666"
        }

        ln ln-icon-Arrow-TurnLeft:before {
            content: "\e667"
        }

        ln ln-icon-Arrow-TurnRight:before {
            content: "\e668"
        }

        ln ln-icon-Arrow-Up:before {
            content: "\e669"
        }

        ln ln-icon-Arrow-Up2:before {
            content: "\e66a"
        }

        ln ln-icon-Arrow-Up3:before {
            content: "\e66b"
        }

        ln ln-icon-Arrow-UpinCircle:before {
            content: "\e66c"
        }

        ln ln-icon-Arrow-XLeft:before {
            content: "\e66d"
        }

        ln ln-icon-Arrow-XRight:before {
            content: "\e66e"
        }

        ln ln-icon-Ask:before {
            content: "\e66f"
        }

        ln ln-icon-Assistant:before {
            content: "\e670"
        }

        ln ln-icon-Astronaut:before {
            content: "\e671"
        }

        ln ln-icon-At-Sign:before {
            content: "\e672"
        }

        ln ln-icon-ATM:before {
            content: "\e673"
        }

        ln ln-icon-Atom:before {
            content: "\e674"
        }

        ln ln-icon-Audio:before {
            content: "\e675"
        }

        ln ln-icon-Auto-Flash:before {
            content: "\e676"
        }

        ln ln-icon-Autumn:before {
            content: "\e677"
        }

        ln ln-icon-Baby-Clothes:before {
            content: "\e678"
        }

        ln ln-icon-Baby-Clothes2:before {
            content: "\e679"
        }

        ln ln-icon-Baby-Cry:before {
            content: "\e67a"
        }

        ln ln-icon-Baby:before {
            content: "\e67b"
        }

        ln ln-icon-Back2:before {
            content: "\e67c"
        }

        ln ln-icon-Back-Media:before {
            content: "\e67d"
        }

        ln ln-icon-Back-Music:before {
            content: "\e67e"
        }

        ln ln-icon-Back:before {
            content: "\e67f"
        }

        ln ln-icon-Background:before {
            content: "\e680"
        }

        ln ln-icon-Bacteria:before {
            content: "\e681"
        }

        ln ln-icon-Bag-Coins:before {
            content: "\e682"
        }

        ln ln-icon-Bag-Items:before {
            content: "\e683"
        }

        ln ln-icon-Bag-Quantity:before {
            content: "\e684"
        }

        ln ln-icon-Bag:before {
            content: "\e685"
        }

        ln ln-icon-Bakelite:before {
            content: "\e686"
        }

        ln ln-icon-Ballet-Shoes:before {
            content: "\e687"
        }

        ln ln-icon-Balloon:before {
            content: "\e688"
        }

        ln ln-icon-Banana:before {
            content: "\e689"
        }

        ln ln-icon-Band-Aid:before {
            content: "\e68a"
        }

        ln ln-icon-Bank:before {
            content: "\e68b"
        }

        ln ln-icon-Bar-Chart:before {
            content: "\e68c"
        }

        ln ln-icon-Bar-Chart2:before {
            content: "\e68d"
        }

        ln ln-icon-Bar-Chart3:before {
            content: "\e68e"
        }

        ln ln-icon-Bar-Chart4:before {
            content: "\e68f"
        }

        ln ln-icon-Bar-Chart5:before {
            content: "\e690"
        }

        ln ln-icon-Bar-Code:before {
            content: "\e691"
        }

        ln ln-icon-Barricade-2:before {
            content: "\e692"
        }

        ln ln-icon-Barricade:before {
            content: "\e693"
        }

        ln ln-icon-Baseball:before {
            content: "\e694"
        }

        ln ln-icon-Basket-Ball:before {
            content: "\e695"
        }

        ln ln-icon-Basket-Coins:before {
            content: "\e696"
        }

        ln ln-icon-Basket-Items:before {
            content: "\e697"
        }

        ln ln-icon-Basket-Quantity:before {
            content: "\e698"
        }

        ln ln-icon-Bat-2:before {
            content: "\e699"
        }

        ln ln-icon-Bat:before {
            content: "\e69a"
        }

        ln ln-icon-Bathrobe:before {
            content: "\e69b"
        }

        ln ln-icon-Batman-Mask:before {
            content: "\e69c"
        }

        ln ln-icon-Battery-0:before {
            content: "\e69d"
        }

        ln ln-icon-Battery-25:before {
            content: "\e69e"
        }

        ln ln-icon-Battery-50:before {
            content: "\e69f"
        }

        ln ln-icon-Battery-75:before {
            content: "\e6a0"
        }

        ln ln-icon-Battery-100:before {
            content: "\e6a1"
        }

        ln ln-icon-Battery-Charge:before {
            content: "\e6a2"
        }

        ln ln-icon-Bear:before {
            content: "\e6a3"
        }

        ln ln-icon-Beard-2:before {
            content: "\e6a4"
        }

        ln ln-icon-Beard-3:before {
            content: "\e6a5"
        }

        ln ln-icon-Beard:before {
            content: "\e6a6"
        }

        ln ln-icon-Bebo:before {
            content: "\e6a7"
        }

        ln ln-icon-Bee:before {
            content: "\e6a8"
        }

        ln ln-icon-Beer-Glass:before {
            content: "\e6a9"
        }

        ln ln-icon-Beer:before {
            content: "\e6aa"
        }

        ln ln-icon-Bell-2:before {
            content: "\e6ab"
        }

        ln ln-icon-Bell:before {
            content: "\e6ac"
        }

        ln ln-icon-Belt-2:before {
            content: "\e6ad"
        }

        ln ln-icon-Belt-3:before {
            content: "\e6ae"
        }

        ln ln-icon-Belt:before {
            content: "\e6af"
        }

        ln ln-icon-Berlin-Tower:before {
            content: "\e6b0"
        }

        ln ln-icon-Beta:before {
            content: "\e6b1"
        }

        ln ln-icon-Betvibes:before {
            content: "\e6b2"
        }

        ln ln-icon-Bicycle-2:before {
            content: "\e6b3"
        }

        ln ln-icon-Bicycle-3:before {
            content: "\e6b4"
        }

        ln ln-icon-Bicycle:before {
            content: "\e6b5"
        }

        ln ln-icon-Big-Bang:before {
            content: "\e6b6"
        }

        ln ln-icon-Big-Data:before {
            content: "\e6b7"
        }

        ln ln-icon-Bike-Helmet:before {
            content: "\e6b8"
        }

        ln ln-icon-Bikini:before {
            content: "\e6b9"
        }

        ln ln-icon-Bilk-Bottle2:before {
            content: "\e6ba"
        }

        ln ln-icon-Billing:before {
            content: "\e6bb"
        }

        ln ln-icon-Bing:before {
            content: "\e6bc"
        }

        ln ln-icon-Binocular:before {
            content: "\e6bd"
        }

        ln ln-icon-Bio-Hazard:before {
            content: "\e6be"
        }

        ln ln-icon-Biotech:before {
            content: "\e6bf"
        }

        ln ln-icon-Bird-DeliveringLetter:before {
            content: "\e6c0"
        }

        ln ln-icon-Bird:before {
            content: "\e6c1"
        }

        ln ln-icon-Birthday-Cake:before {
            content: "\e6c2"
        }

        ln ln-icon-Bisexual:before {
            content: "\e6c3"
        }

        ln ln-icon-Bishop:before {
            content: "\e6c4"
        }

        ln ln-icon-Bitcoin:before {
            content: "\e6c5"
        }

        ln ln-icon-Black-Cat:before {
            content: "\e6c6"
        }

        ln ln-icon-Blackboard:before {
            content: "\e6c7"
        }

        ln ln-icon-Blinklist:before {
            content: "\e6c8"
        }

        ln ln-icon-Block-Cloud:before {
            content: "\e6c9"
        }

        ln ln-icon-Block-Window:before {
            content: "\e6ca"
        }

        ln ln-icon-Blogger:before {
            content: "\e6cb"
        }

        ln ln-icon-Blood:before {
            content: "\e6cc"
        }

        ln ln-icon-Blouse:before {
            content: "\e6cd"
        }

        ln ln-icon-Blueprint:before {
            content: "\e6ce"
        }

        ln ln-icon-Board:before {
            content: "\e6cf"
        }

        ln ln-icon-Bodybuilding:before {
            content: "\e6d0"
        }

        ln ln-icon-Bold-Text:before {
            content: "\e6d1"
        }

        ln ln-icon-Bone:before {
            content: "\e6d2"
        }

        ln ln-icon-Bones:before {
            content: "\e6d3"
        }

        ln ln-icon-Book:before {
            content: "\e6d4"
        }

        ln ln-icon-Bookmark:before {
            content: "\e6d5"
        }

        ln ln-icon-Books-2:before {
            content: "\e6d6"
        }

        ln ln-icon-Books:before {
            content: "\e6d7"
        }

        ln ln-icon-Boom:before {
            content: "\e6d8"
        }

        ln ln-icon-Boot-2:before {
            content: "\e6d9"
        }

        ln ln-icon-Boot:before {
            content: "\e6da"
        }

        ln ln-icon-Bottom-ToTop:before {
            content: "\e6db"
        }

        ln ln-icon-Bow-2:before {
            content: "\e6dc"
        }

        ln ln-icon-Bow-3:before {
            content: "\e6dd"
        }

        ln ln-icon-Bow-4:before {
            content: "\e6de"
        }

        ln ln-icon-Bow-5:before {
            content: "\e6df"
        }

        ln ln-icon-Bow-6:before {
            content: "\e6e0"
        }

        ln ln-icon-Bow:before {
            content: "\e6e1"
        }

        ln ln-icon-Bowling-2:before {
            content: "\e6e2"
        }

        ln ln-icon-Bowling:before {
            content: "\e6e3"
        }

        ln ln-icon-Box2:before {
            content: "\e6e4"
        }

        ln ln-icon-Box-Close:before {
            content: "\e6e5"
        }

        ln ln-icon-Box-Full:before {
            content: "\e6e6"
        }

        ln ln-icon-Box-Open:before {
            content: "\e6e7"
        }

        ln ln-icon-Box-withFolders:before {
            content: "\e6e8"
        }

        ln ln-icon-Box:before {
            content: "\e6e9"
        }

        ln ln-icon-Boy:before {
            content: "\e6ea"
        }

        ln ln-icon-Bra:before {
            content: "\e6eb"
        }

        ln ln-icon-Brain-2:before {
            content: "\e6ec"
        }

        ln ln-icon-Brain-3:before {
            content: "\e6ed"
        }

        ln ln-icon-Brain:before {
            content: "\e6ee"
        }

        ln ln-icon-Brazil:before {
            content: "\e6ef"
        }

        ln ln-icon-Bread-2:before {
            content: "\e6f0"
        }

        ln ln-icon-Bread:before {
            content: "\e6f1"
        }

        ln ln-icon-Bridge:before {
            content: "\e6f2"
        }

        ln ln-icon-Brightkite:before {
            content: "\e6f3"
        }

        ln ln-icon-Broke-Link2:before {
            content: "\e6f4"
        }

        ln ln-icon-Broken-Link:before {
            content: "\e6f5"
        }

        ln ln-icon-Broom:before {
            content: "\e6f6"
        }

        ln ln-icon-Brush:before {
            content: "\e6f7"
        }

        ln ln-icon-Bucket:before {
            content: "\e6f8"
        }

        ln ln-icon-Bug:before {
            content: "\e6f9"
        }

        ln ln-icon-Building:before {
            content: "\e6fa"
        }

        ln ln-icon-Bulleted-List:before {
            content: "\e6fb"
        }

        ln ln-icon-Bus-2:before {
            content: "\e6fc"
        }

        ln ln-icon-Bus:before {
            content: "\e6fd"
        }

        ln ln-icon-Business-Man:before {
            content: "\e6fe"
        }

        ln ln-icon-Business-ManWoman:before {
            content: "\e6ff"
        }

        ln ln-icon-Business-Mens:before {
            content: "\e700"
        }

        ln ln-icon-Business-Woman:before {
            content: "\e701"
        }

        ln ln-icon-Butterfly:before {
            content: "\e702"
        }

        ln ln-icon-Button:before {
            content: "\e703"
        }

        ln ln-icon-Cable-Car:before {
            content: "\e704"
        }

        ln ln-icon-Cake:before {
            content: "\e705"
        }

        ln ln-icon-Calculator-2:before {
            content: "\e706"
        }

        ln ln-icon-Calculator-3:before {
            content: "\e707"
        }

        ln ln-icon-Calculator:before {
            content: "\e708"
        }

        ln ln-icon-Calendar-2:before {
            content: "\e709"
        }

        ln ln-icon-Calendar-3:before {
            content: "\e70a"
        }

        ln ln-icon-Calendar-4:before {
            content: "\e70b"
        }

        ln ln-icon-Calendar-Clock:before {
            content: "\e70c"
        }

        ln ln-icon-Calendar:before {
            content: "\e70d"
        }

        ln ln-icon-Camel:before {
            content: "\e70e"
        }

        ln ln-icon-Camera-2:before {
            content: "\e70f"
        }

        ln ln-icon-Camera-3:before {
            content: "\e710"
        }

        ln ln-icon-Camera-4:before {
            content: "\e711"
        }

        ln ln-icon-Camera-5:before {
            content: "\e712"
        }

        ln ln-icon-Camera-Back:before {
            content: "\e713"
        }

        ln ln-icon-Camera:before {
            content: "\e714"
        }

        ln ln-icon-Can-2:before {
            content: "\e715"
        }

        ln ln-icon-Can:before {
            content: "\e716"
        }

        ln ln-icon-Canada:before {
            content: "\e717"
        }

        ln ln-icon-Cancer-2:before {
            content: "\e718"
        }

        ln ln-icon-Cancer-3:before {
            content: "\e719"
        }

        ln ln-icon-Cancer:before {
            content: "\e71a"
        }

        ln ln-icon-Candle:before {
            content: "\e71b"
        }

        ln ln-icon-Candy-Cane:before {
            content: "\e71c"
        }

        ln ln-icon-Candy:before {
            content: "\e71d"
        }

        ln ln-icon-Cannon:before {
            content: "\e71e"
        }

        ln ln-icon-Cap-2:before {
            content: "\e71f"
        }

        ln ln-icon-Cap-3:before {
            content: "\e720"
        }

        ln ln-icon-Cap-Smiley:before {
            content: "\e721"
        }

        ln ln-icon-Cap:before {
            content: "\e722"
        }

        ln ln-icon-Capricorn-2:before {
            content: "\e723"
        }

        ln ln-icon-Capricorn:before {
            content: "\e724"
        }

        ln ln-icon-Car-2:before {
            content: "\e725"
        }

        ln ln-icon-Car-3:before {
            content: "\e726"
        }

        ln ln-icon-Car-Coins:before {
            content: "\e727"
        }

        ln ln-icon-Car-Items:before {
            content: "\e728"
        }

        ln ln-icon-Car-Wheel:before {
            content: "\e729"
        }

        ln ln-icon-Car:before {
            content: "\e72a"
        }

        ln ln-icon-Cardigan:before {
            content: "\e72b"
        }

        ln ln-icon-Cardiovascular:before {
            content: "\e72c"
        }

        ln ln-icon-Cart-Quantity:before {
            content: "\e72d"
        }

        ln ln-icon-Casette-Tape:before {
            content: "\e72e"
        }

        ln ln-icon-Cash-Register:before {
            content: "\e72f"
        }

        ln ln-icon-Cash-register2:before {
            content: "\e730"
        }

        ln ln-icon-Castle:before {
            content: "\e731"
        }

        ln ln-icon-Cat:before {
            content: "\e732"
        }

        ln ln-icon-Cathedral:before {
            content: "\e733"
        }

        ln ln-icon-Cauldron:before {
            content: "\e734"
        }

        ln ln-icon-CD-2:before {
            content: "\e735"
        }

        ln ln-icon-CD-Cover:before {
            content: "\e736"
        }

        ln ln-icon-CD:before {
            content: "\e737"
        }

        ln ln-icon-Cello:before {
            content: "\e738"
        }

        ln ln-icon-Celsius:before {
            content: "\e739"
        }

        ln ln-icon-Chacked-Flag:before {
            content: "\e73a"
        }

        ln ln-icon-Chair:before {
            content: "\e73b"
        }

        ln ln-icon-Charger:before {
            content: "\e73c"
        }

        ln ln-icon-Check-2:before {
            content: "\e73d"
        }

        ln ln-icon-Check:before {
            content: "\e73e"
        }

        ln ln-icon-Checked-User:before {
            content: "\e73f"
        }

        ln ln-icon-Checkmate:before {
            content: "\e740"
        }

        ln ln-icon-Checkout-Bag:before {
            content: "\e741"
        }

        ln ln-icon-Checkout-Basket:before {
            content: "\e742"
        }

        ln ln-icon-Checkout:before {
            content: "\e743"
        }

        ln ln-icon-Cheese:before {
            content: "\e744"
        }

        ln ln-icon-Cheetah:before {
            content: "\e745"
        }

        ln ln-icon-Chef-Hat:before {
            content: "\e746"
        }

        ln ln-icon-Chef-Hat2:before {
            content: "\e747"
        }

        ln ln-icon-Chef:before {
            content: "\e748"
        }

        ln ln-icon-Chemical-2:before {
            content: "\e749"
        }

        ln ln-icon-Chemical-3:before {
            content: "\e74a"
        }

        ln ln-icon-Chemical-4:before {
            content: "\e74b"
        }

        ln ln-icon-Chemical-5:before {
            content: "\e74c"
        }

        ln ln-icon-Chemical:before {
            content: "\e74d"
        }

        ln ln-icon-Chess-Board:before {
            content: "\e74e"
        }

        ln ln-icon-Chess:before {
            content: "\e74f"
        }

        ln ln-icon-Chicken:before {
            content: "\e750"
        }

        ln ln-icon-Chile:before {
            content: "\e751"
        }

        ln ln-icon-Chimney:before {
            content: "\e752"
        }

        ln ln-icon-China:before {
            content: "\e753"
        }

        ln ln-icon-Chinese-Temple:before {
            content: "\e754"
        }

        ln ln-icon-Chip:before {
            content: "\e755"
        }

        ln ln-icon-Chopsticks-2:before {
            content: "\e756"
        }

        ln ln-icon-Chopsticks:before {
            content: "\e757"
        }

        ln ln-icon-Christmas-Ball:before {
            content: "\e758"
        }

        ln ln-icon-Christmas-Bell:before {
            content: "\e759"
        }

        ln ln-icon-Christmas-Candle:before {
            content: "\e75a"
        }

        ln ln-icon-Christmas-Hat:before {
            content: "\e75b"
        }

        ln ln-icon-Christmas-Sleigh:before {
            content: "\e75c"
        }

        ln ln-icon-Christmas-Snowman:before {
            content: "\e75d"
        }

        ln ln-icon-Christmas-Sock:before {
            content: "\e75e"
        }

        ln ln-icon-Christmas-Tree:before {
            content: "\e75f"
        }

        ln ln-icon-Christmas:before {
            content: "\e760"
        }

        ln ln-icon-Chrome:before {
            content: "\e761"
        }

        ln ln-icon-Chrysler-Building:before {
            content: "\e762"
        }

        ln ln-icon-Cinema:before {
            content: "\e763"
        }

        ln ln-icon-Circular-Point:before {
            content: "\e764"
        }

        ln ln-icon-City-Hall:before {
            content: "\e765"
        }

        ln ln-icon-Clamp:before {
            content: "\e766"
        }

        ln ln-icon-Clapperboard-Close:before {
            content: "\e767"
        }

        ln ln-icon-Clapperboard-Open:before {
            content: "\e768"
        }

        ln ln-icon-Claps:before {
            content: "\e769"
        }

        ln ln-icon-Clef:before {
            content: "\e76a"
        }

        ln ln-icon-Clinic:before {
            content: "\e76b"
        }

        ln ln-icon-Clock-2:before {
            content: "\e76c"
        }

        ln ln-icon-Clock-3:before {
            content: "\e76d"
        }

        ln ln-icon-Clock-4:before {
            content: "\e76e"
        }

        ln ln-icon-Clock-Back:before {
            content: "\e76f"
        }

        ln ln-icon-Clock-Forward:before {
            content: "\e770"
        }

        ln ln-icon-Clock:before {
            content: "\e771"
        }

        ln ln-icon-Close-Window:before {
            content: "\e772"
        }

        ln ln-icon-Close:before {
            content: "\e773"
        }

        ln ln-icon-Clothing-Store:before {
            content: "\e774"
        }

        ln ln-icon-Cloud--:before {
            content: "\e775"
        }

        ln ln-icon-Cloud-:before {
            content: "\e776"
        }

        ln ln-icon-Cloud-Camera:before {
            content: "\e777"
        }

        ln ln-icon-Cloud-Computer:before {
            content: "\e778"
        }

        ln ln-icon-Cloud-Email:before {
            content: "\e779"
        }

        ln ln-icon-Cloud-Hail:before {
            content: "\e77a"
        }

        ln ln-icon-Cloud-Laptop:before {
            content: "\e77b"
        }

        ln ln-icon-Cloud-Lock:before {
            content: "\e77c"
        }

        ln ln-icon-Cloud-Moon:before {
            content: "\e77d"
        }

        ln ln-icon-Cloud-Music:before {
            content: "\e77e"
        }

        ln ln-icon-Cloud-Picture:before {
            content: "\e77f"
        }

        ln ln-icon-Cloud-Rain:before {
            content: "\e780"
        }

        ln ln-icon-Cloud-Remove:before {
            content: "\e781"
        }


        ln ln-icon-Cloud-Settings:before {
            content: "\e783"
        }

        ln ln-icon-Cloud-Smartphone:before {
            content: "\e784"
        }

        ln ln-icon-Cloud-Snow:before {
            content: "\e785"
        }

        ln ln-icon-Cloud-Sun:before {
            content: "\e786"
        }

        ln ln-icon-Cloud-Tablet:before {
            content: "\e787"
        }

        ln ln-icon-Cloud-Video:before {
            content: "\e788"
        }

        ln ln-icon-Cloud-Weather:before {
            content: "\e789"
        }

        ln ln-icon-Cloud:before {
            content: "\e78a"
        }

        ln ln-icon-Clouds-Weather:before {
            content: "\e78b"
        }

        ln ln-icon-Clouds:before {
            content: "\e78c"
        }

        ln ln-icon-Clown:before {
            content: "\e78d"
        }

        ln ln-icon-CMYK:before {
            content: "\e78e"
        }

        ln ln-icon-Coat:before {
            content: "\e78f"
        }

        ln ln-icon-Cocktail:before {
            content: "\e790"
        }

        ln ln-icon-Coconut:before {
            content: "\e791"
        }



        ln ln-icon-Coffee-2:before {
            content: "\e794"
        }

        ln ln-icon-Coffee-Bean:before {
            content: "\e795"
        }

        ln ln-icon-Coffee-Machine:before {
            content: "\e796"
        }

        ln ln-icon-Coffee-toGo:before {
            content: "\e797"
        }

        ln ln-icon-Coffee:before {
            content: "\e798"
        }

        ln ln-icon-Coffin:before {
            content: "\e799"
        }

        ln ln-icon-Coin:before {
            content: "\e79a"
        }

        ln ln-icon-Coins-2:before {
            content: "\e79b"
        }

        ln ln-icon-Coins-3:before {
            content: "\e79c"
        }

        ln ln-icon-Coins:before {
            content: "\e79d"
        }

        ln ln-icon-Colombia:before {
            content: "\e79e"
        }

        ln ln-icon-Colosseum:before {
            content: "\e79f"
        }

        ln ln-icon-Column-2:before {
            content: "\e7a0"
        }

        ln ln-icon-Column-3:before {
            content: "\e7a1"
        }

        ln ln-icon-Column:before {
            content: "\e7a2"
        }

        ln ln-icon-Comb-2:before {
            content: "\e7a3"
        }

        ln ln-icon-Comb:before {
            content: "\e7a4"
        }

        ln ln-icon-Communication-Tower:before {
            content: "\e7a5"
        }

        ln ln-icon-Communication-Tower2:before {
            content: "\e7a6"
        }

        ln ln-icon-Compass-2:before {
            content: "\e7a7"
        }

        ln ln-icon-Compass-3:before {
            content: "\e7a8"
        }

        ln ln-icon-Compass-4:before {
            content: "\e7a9"
        }

        ln ln-icon-Compass-Rose:before {
            content: "\e7aa"
        }

        ln ln-icon-Compass:before {
            content: "\e7ab"
        }

        ln ln-icon-Computer-2:before {
            content: "\e7ac"
        }

        ln ln-icon-Computer-3:before {
            content: "\e7ad"
        }

        ln ln-icon-Computer-Secure:before {
            content: "\e7ae"
        }

        ln ln-icon-Computer:before {
            content: "\e7af"
        }

        ln ln-icon-Conference:before {
            content: "\e7b0"
        }

        ln ln-icon-Confused:before {
            content: "\e7b1"
        }

        ln ln-icon-Conservation:before {
            content: "\e7b2"
        }

        ln ln-icon-Consulting:before {
            content: "\e7b3"
        }

        ln ln-icon-Contrast:before {
            content: "\e7b4"
        }

        ln ln-icon-Control-2:before {
            content: "\e7b5"
        }

        ln ln-icon-Control:before {
            content: "\e7b6"
        }

        ln ln-icon-Cookie-Man:before {
            content: "\e7b7"
        }

        ln ln-icon-Cookies:before {
            content: "\e7b8"
        }

        ln ln-icon-Cool-Guy:before {
            content: "\e7b9"
        }

        ln ln-icon-Cool:before {
            content: "\e7ba"
        }

        ln ln-icon-Copyright:before {
            content: "\e7bb"
        }

        ln ln-icon-Costume:before {
            content: "\e7bc"
        }

        ln ln-icon-Couple-Sign:before {
            content: "\e7bd"
        }

        ln ln-icon-Cow:before {
            content: "\e7be"
        }

        ln ln-icon-CPU:before {
            content: "\e7bf"
        }

        ln ln-icon-Crane:before {
            content: "\e7c0"
        }

        ln ln-icon-Cranium:before {
            content: "\e7c1"
        }

        ln ln-icon-Credit-Card:before {
            content: "\e7c2"
        }

        ln ln-icon-Credit-Card2:before {
            content: "\e7c3"
        }

        ln ln-icon-Credit-Card3:before {
            content: "\e7c4"
        }

        ln ln-icon-Cricket:before {
            content: "\e7c5"
        }

        ln ln-icon-Criminal:before {
            content: "\e7c6"
        }

        ln ln-icon-Croissant:before {
            content: "\e7c7"
        }

        ln ln-icon-Crop-2:before {
            content: "\e7c8"
        }

        ln ln-icon-Crop-3:before {
            content: "\e7c9"
        }

        ln ln-icon-Crown-2:before {
            content: "\e7ca"
        }

        ln ln-icon-Crown:before {
            content: "\e7cb"
        }

        ln ln-icon-Crying:before {
            content: "\e7cc"
        }

        ln ln-icon-Cube-Molecule:before {
            content: "\e7cd"
        }

        ln ln-icon-Cube-Molecule2:before {
            content: "\e7ce"
        }

        ln ln-icon-Cupcake:before {
            content: "\e7cf"
        }

        ln ln-icon-Cursor-Click:before {
            content: "\e7d0"
        }

        ln ln-icon-Cursor-Click2:before {
            content: "\e7d1"
        }

        ln ln-icon-Cursor-Move:before {
            content: "\e7d2"
        }

        ln ln-icon-Cursor-Move2:before {
            content: "\e7d3"
        }

        ln ln-icon-Cursor-Select:before {
            content: "\e7d4"
        }

        ln ln-icon-Cursor:before {
            content: "\e7d5"
        }

        ln ln-icon-D-Eyeglasses:before {
            content: "\e7d6"
        }

        ln ln-icon-D-Eyeglasses2:before {
            content: "\e7d7"
        }

        ln ln-icon-Dam:before {
            content: "\e7d8"
        }

        ln ln-icon-Danemark:before {
            content: "\e7d9"
        }

        ln ln-icon-Danger-2:before {
            content: "\e7da"
        }

        ln ln-icon-Danger:before {
            content: "\e7db"
        }

        ln ln-icon-Dashboard:before {
            content: "\e7dc"
        }

        ln ln-icon-Data-Backup:before {
            content: "\e7dd"
        }

        ln ln-icon-Data-Block:before {
            content: "\e7de"
        }

        ln ln-icon-Data-Center:before {
            content: "\e7df"
        }

        ln ln-icon-Data-Clock:before {
            content: "\e7e0"
        }

        ln ln-icon-Data-Cloud:before {
            content: "\e7e1"
        }

        ln ln-icon-Data-Compress:before {
            content: "\e7e2"
        }

        ln ln-icon-Data-Copy:before {
            content: "\e7e3"
        }

        ln ln-icon-Data-Download:before {
            content: "\e7e4"
        }

        ln ln-icon-Data-Financial:before {
            content: "\e7e5"
        }

        ln ln-icon-Data-Key:before {
            content: "\e7e6"
        }

        ln ln-icon-Data-Lock:before {
            content: "\e7e7"
        }

        ln ln-icon-Data-Network:before {
            content: "\e7e8"
        }

        ln ln-icon-Data-Password:before {
            content: "\e7e9"
        }

        ln ln-icon-Data-Power:before {
            content: "\e7ea"
        }

        ln ln-icon-Data-Refresh:before {
            content: "\e7eb"
        }

        ln ln-icon-Data-Save:before {
            content: "\e7ec"
        }

        ln ln-icon-Data-Search:before {
            content: "\e7ed"
        }

        ln ln-icon-Data-Security:before {
            content: "\e7ee"
        }

        ln ln-icon-Data-Settings:before {
            content: "\e7ef"
        }

        ln ln-icon-Data-Sharing:before {
            content: "\e7f0"
        }

        ln ln-icon-Data-Shield:before {
            content: "\e7f1"
        }

        ln ln-icon-Data-Signal:before {
            content: "\e7f2"
        }

        ln ln-icon-Data-Storage:before {
            content: "\e7f3"
        }

        ln ln-icon-Data-Stream:before {
            content: "\e7f4"
        }

        ln ln-icon-Data-Transfer:before {
            content: "\e7f5"
        }

        ln ln-icon-Data-Unlock:before {
            content: "\e7f6"
        }

        ln ln-icon-Data-Upload:before {
            content: "\e7f7"
        }

        ln ln-icon-Data-Yes:before {
            content: "\e7f8"
        }

        ln ln-icon-Data:before {
            content: "\e7f9"
        }

        ln ln-icon-David-Star:before {
            content: "\e7fa"
        }

        ln ln-icon-Daylight:before {
            content: "\e7fb"
        }

        ln ln-icon-Death:before {
            content: "\e7fc"
        }

        ln ln-icon-Debian:before {
            content: "\e7fd"
        }

        ln ln-icon-Dec:before {
            content: "\e7fe"
        }

        ln ln-icon-Decrase-Inedit:before {
            content: "\e7ff"
        }

        ln ln-icon-Deer-2:before {
            content: "\e800"
        }

        ln ln-icon-Deer:before {
            content: "\e801"
        }

        ln ln-icon-Delete-File:before {
            content: "\e802"
        }

        ln ln-icon-Delete-Window:before {
            content: "\e803"
        }

        ln ln-icon-Delicious:before {
            content: "\e804"
        }

        ln ln-icon-Depression:before {
            content: "\e805"
        }

        ln ln-icon-Deviantart:before {
            content: "\e806"
        }

        ln ln-icon-Device-SyncwithCloud:before {
            content: "\e807"
        }

        ln ln-icon-Diamond:before {
            content: "\e808"
        }

        ln ln-icon-Dice-2:before {
            content: "\e809"
        }

        ln ln-icon-Dice:before {
            content: "\e80a"
        }

        ln ln-icon-Digg:before {
            content: "\e80b"
        }

        ln ln-icon-Digital-Drawing:before {
            content: "\e80c"
        }

        ln ln-icon-Diigo:before {
            content: "\e80d"
        }

        ln ln-icon-Dinosaur:before {
            content: "\e80e"
        }

        ln ln-icon-Diploma-2:before {
            content: "\e80f"
        }

        ln ln-icon-Diploma:before {
            content: "\e810"
        }

        ln ln-icon-Direction-East:before {
            content: "\e811"
        }

        ln ln-icon-Direction-North:before {
            content: "\e812"
        }

        ln ln-icon-Direction-South:before {
            content: "\e813"
        }

        ln ln-icon-Direction-West:before {
            content: "\e814"
        }

        ln ln-icon-Director:before {
            content: "\e815"
        }

        ln ln-icon-Disk:before {
            content: "\e816"
        }

        ln ln-icon-Dj:before {
            content: "\e817"
        }

        ln ln-icon-DNA-2:before {
            content: "\e818"
        }

        ln ln-icon-DNA-Helix:before {
            content: "\e819"
        }

        ln ln-icon-DNA:before {
            content: "\e81a"
        }

        ln ln-icon-Doctor:before {
            content: "\e81b"
        }

        ln ln-icon-Dog:before {
            content: "\e81c"
        }

        ln ln-icon-Dollar-Sign:before {
            content: "\e81d"
        }

        ln ln-icon-Dollar-Sign2:before {
            content: "\e81e"
        }

        ln ln-icon-Dollar:before {
            content: "\e81f"
        }

        ln ln-icon-Dolphin:before {
            content: "\e820"
        }

        ln ln-icon-Domino:before {
            content: "\e821"
        }

        ln ln-icon-Door-Hanger:before {
            content: "\e822"
        }

        ln ln-icon-Door:before {
            content: "\e823"
        }

        ln ln-icon-Doplr:before {
            content: "\e824"
        }

        ln ln-icon-Double-Circle:before {
            content: "\e825"
        }

        ln ln-icon-Double-Tap:before {
            content: "\e826"
        }

        ln ln-icon-Doughnut:before {
            content: "\e827"
        }

        ln ln-icon-Dove:before {
            content: "\e828"
        }

        ln ln-icon-Down-2:before {
            content: "\e829"
        }

        ln ln-icon-Down-3:before {
            content: "\e82a"
        }

        ln ln-icon-Down-4:before {
            content: "\e82b"
        }

        ln ln-icon-Down:before {
            content: "\e82c"
        }

        ln ln-icon-Download-2:before {
            content: "\e82d"
        }

        ln ln-icon-Download-fromCloud:before {
            content: "\e82e"
        }

        ln ln-icon-Download-Window:before {
            content: "\e82f"
        }

        ln ln-icon-Download:before {
            content: "\e830"
        }

        ln ln-icon-Downward:before {
            content: "\e831"
        }

        ln ln-icon-Drag-Down:before {
            content: "\e832"
        }

        ln ln-icon-Drag-Left:before {
            content: "\e833"
        }

        ln ln-icon-Drag-Right:before {
            content: "\e834"
        }

        ln ln-icon-Drag-Up:before {
            content: "\e835"
        }

        ln ln-icon-Drag:before {
            content: "\e836"
        }

        ln ln-icon-Dress:before {
            content: "\e837"
        }

        ln ln-icon-Drill-2:before {
            content: "\e838"
        }

        ln ln-icon-Drill:before {
            content: "\e839"
        }

        ln ln-icon-Drop:before {
            content: "\e83a"
        }

        ln ln-icon-Dropbox:before {
            content: "\e83b"
        }

        ln ln-icon-Drum:before {
            content: "\e83c"
        }

        ln ln-icon-Dry:before {
            content: "\e83d"
        }

        ln ln-icon-Duck:before {
            content: "\e83e"
        }

        ln ln-icon-Dumbbell:before {
            content: "\e83f"
        }

        ln ln-icon-Duplicate-Layer:before {
            content: "\e840"
        }

        ln ln-icon-Duplicate-Window:before {
            content: "\e841"
        }

        ln ln-icon-DVD:before {
            content: "\e842"
        }

        ln ln-icon-Eagle:before {
            content: "\e843"
        }

        ln ln-icon-Ear:before {
            content: "\e844"
        }

        ln ln-icon-Earphones-2:before {
            content: "\e845"
        }

        ln ln-icon-Earphones:before {
            content: "\e846"
        }

        ln ln-icon-Eci-Icon:before {
            content: "\e847"
        }

        ln ln-icon-Edit-Map:before {
            content: "\e848"
        }

        ln ln-icon-Edit:before {
            content: "\e849"
        }

        ln ln-icon-Eggs:before {
            content: "\e84a"
        }

        ln ln-icon-Egypt:before {
            content: "\e84b"
        }

        ln ln-icon-Eifel-Tower:before {
            content: "\e84c"
        }

        ln ln-icon-eject-2:before {
            content: "\e84d"
        }

        ln ln-icon-Eject:before {
            content: "\e84e"
        }

        ln ln-icon-El-Castillo:before {
            content: "\e84f"
        }

        ln ln-icon-Elbow:before {
            content: "\e850"
        }

        ln ln-icon-Electric-Guitar:before {
            content: "\e851"
        }

        ln ln-icon-Electricity:before {
            content: "\e852"
        }

        ln ln-icon-Elephant:before {
            content: "\e853"
        }



        ln ln-icon-Embassy:before {
            content: "\e855"
        }

        ln ln-icon-Empire-StateBuilding:before {
            content: "\e856"
        }

        ln ln-icon-Empty-Box:before {
            content: "\e857"
        }

        ln ln-icon-End2:before {
            content: "\e858"
        }

        ln ln-icon-End-2:before {
            content: "\e859"
        }

        ln ln-icon-End:before {
            content: "\e85a"
        }

        ln ln-icon-Endways:before {
            content: "\e85b"
        }

        ln ln-icon-Engineering:before {
            content: "\e85c"
        }

        ln ln-icon-Envelope-2:before {
            content: "\e85d"
        }

        ln ln-icon-Envelope:before {
            content: "\e85e"
        }

        ln ln-icon-Environmental-2:before {
            content: "\e85f"
        }

        ln ln-icon-Environmental-3:before {
            content: "\e860"
        }

        ln ln-icon-Environmental:before {
            content: "\e861"
        }

        ln ln-icon-Equalizer:before {
            content: "\e862"
        }

        ln ln-icon-Eraser-2:before {
            content: "\e863"
        }

        ln ln-icon-Eraser-3:before {
            content: "\e864"
        }

        ln ln-icon-Eraser:before {
            content: "\e865"
        }

        ln ln-icon-Error-404Window:before {
            content: "\e866"
        }

        ln ln-icon-Euro-Sign:before {
            content: "\e867"
        }

        ln ln-icon-Euro-Sign2:before {
            content: "\e868"
        }

        ln ln-icon-Euro:before {
            content: "\e869"
        }

        ln ln-icon-Evernote:before {
            content: "\e86a"
        }

        ln ln-icon-Evil:before {
            content: "\e86b"
        }

        ln ln-icon-Explode:before {
            content: "\e86c"
        }

        ln ln-icon-Eye-2:before {
            content: "\e86d"
        }

        ln ln-icon-Eye-Blind:before {
            content: "\e86e"
        }

        ln ln-icon-Eye-Invisible:before {
            content: "\e86f"
        }

        ln ln-icon-Eye-Scan:before {
            content: "\e870"
        }

        ln ln-icon-Eye-Visible:before {
            content: "\e871"
        }

        ln ln-icon-Eye:before {
            content: "\e872"
        }

        ln ln-icon-Eyebrow-2:before {
            content: "\e873"
        }

        ln ln-icon-Eyebrow-3:before {
            content: "\e874"
        }

        ln ln-icon-Eyebrow:before {
            content: "\e875"
        }

        ln ln-icon-Eyeglasses-Smiley:before {
            content: "\e876"
        }

        ln ln-icon-Eyeglasses-Smiley2:before {
            content: "\e877"
        }

        ln ln-icon-Face-Style:before {
            content: "\e878"
        }

        ln ln-icon-Face-Style2:before {
            content: "\e879"
        }

        ln ln-icon-Face-Style3:before {
            content: "\e87a"
        }

        ln ln-icon-Face-Style4:before {
            content: "\e87b"
        }

        ln ln-icon-Face-Style5:before {
            content: "\e87c"
        }

        ln ln-icon-Face-Style6:before {
            content: "\e87d"
        }

        ln ln-icon-Facebook-2:before {
            content: "\e87e"
        }

        ln ln-icon-Facebook:before {
            content: "\e87f"
        }

        ln ln-icon-Factory-2:before {
            content: "\e880"
        }

        ln ln-icon-Factory:before {
            content: "\e881"
        }

        ln ln-icon-Fahrenheit:before {
            content: "\e882"
        }

        ln ln-icon-Family-Sign:before {
            content: "\e883"
        }

        ln ln-icon-Fan:before {
            content: "\e884"
        }

        ln ln-icon-Farmer:before {
            content: "\e885"
        }

        ln ln-icon-Fashion:before {
            content: "\e886"
        }

        ln ln-icon-Favorite-Window:before {
            content: "\e887"
        }

        ln ln-icon-Fax:before {
            content: "\e888"
        }

        ln ln-icon-Feather:before {
            content: "\e889"
        }

        ln ln-icon-Feedburner:before {
            content: "\e88a"
        }

        ln ln-icon-Female-2:before {
            content: "\e88b"
        }

        ln ln-icon-Female-Sign:before {
            content: "\e88c"
        }

        ln ln-icon-Female:before {
            content: "\e88d"
        }

        ln ln-icon-File-Block:before {
            content: "\e88e"
        }

        ln ln-icon-File-Bookmark:before {
            content: "\e88f"
        }

        ln ln-icon-File-Chart:before {
            content: "\e890"
        }

        ln ln-icon-File-Clipboard:before {
            content: "\e891"
        }

        ln ln-icon-File-ClipboardFileText:before {
            content: "\e892"
        }

        ln ln-icon-File-ClipboardTextImage:before {
            content: "\e893"
        }

        ln ln-icon-File-Cloud:before {
            content: "\e894"
        }

        ln ln-icon-File-Copy:before {
            content: "\e895"
        }

        ln ln-icon-File-Copy2:before {
            content: "\e896"
        }

        ln ln-icon-File-CSV:before {
            content: "\e897"
        }

        ln ln-icon-File-Download:before {
            content: "\e898"
        }

        ln ln-icon-File-Edit:before {
            content: "\e899"
        }

        ln ln-icon-File-Excel:before {
            content: "\e89a"
        }

        ln ln-icon-File-Favorite:before {
            content: "\e89b"
        }

        ln ln-icon-File-Fire:before {
            content: "\e89c"
        }

        ln ln-icon-File-Graph:before {
            content: "\e89d"
        }

        ln ln-icon-File-Hide:before {
            content: "\e89e"
        }

        ln ln-icon-File-Horizontal:before {
            content: "\e89f"
        }

        ln ln-icon-File-HorizontalText:before {
            content: "\e8a0"
        }

        ln ln-icon-File-HTML:before {
            content: "\e8a1"
        }

        ln ln-icon-File-JPG:before {
            content: "\e8a2"
        }

        ln ln-icon-File-Link:before {
            content: "\e8a3"
        }

        ln ln-icon-File-Loading:before {
            content: "\e8a4"
        }

        ln ln-icon-File-Lock:before {
            content: "\e8a5"
        }

        ln ln-icon-File-Love:before {
            content: "\e8a6"
        }

        ln ln-icon-File-Music:before {
            content: "\e8a7"
        }

        ln ln-icon-File-Network:before {
            content: "\e8a8"
        }

        ln ln-icon-File-Pictures:before {
            content: "\e8a9"
        }

        ln ln-icon-File-Pie:before {
            content: "\e8aa"
        }

        ln ln-icon-File-Presentation:before {
            content: "\e8ab"
        }

        ln ln-icon-File-Refresh:before {
            content: "\e8ac"
        }

        ln ln-icon-File-Search:before {
            content: "\e8ad"
        }

        ln ln-icon-File-Settings:before {
            content: "\e8ae"
        }

        ln ln-icon-File-Share:before {
            content: "\e8af"
        }

        ln ln-icon-File-TextImage:before {
            content: "\e8b0"
        }

        ln ln-icon-File-Trash:before {
            content: "\e8b1"
        }

        ln ln-icon-File-TXT:before {
            content: "\e8b2"
        }

        ln ln-icon-File-Upload:before {
            content: "\e8b3"
        }

        ln ln-icon-File-Video:before {
            content: "\e8b4"
        }

        ln ln-icon-File-Word:before {
            content: "\e8b5"
        }

        ln ln-icon-File-Zip:before {
            content: "\e8b6"
        }

        ln ln-icon-File:before {
            content: "\e8b7"
        }

        ln ln-icon-Files:before {
            content: "\e8b8"
        }

        ln ln-icon-Film-Board:before {
            content: "\e8b9"
        }

        ln ln-icon-Film-Cartridge:before {
            content: "\e8ba"
        }

        ln ln-icon-Film-Strip:before {
            content: "\e8bb"
        }

        ln ln-icon-Film-Video:before {
            content: "\e8bc"
        }

        ln ln-icon-Film:before {
            content: "\e8bd"
        }

        ln ln-icon-Filter-2:before {
            content: "\e8be"
        }

        ln ln-icon-Filter:before {
            content: "\e8bf"
        }

        ln ln-icon-Financial:before {
            content: "\e8c0"
        }

        ln ln-icon-Find-User:before {
            content: "\e8c1"
        }

        ln ln-icon-Finger-DragFourSides:before {
            content: "\e8c2"
        }

        ln ln-icon-Finger-DragTwoSides:before {
            content: "\e8c3"
        }

        ln ln-icon-Finger-Print:before {
            content: "\e8c4"
        }

        ln ln-icon-Finger:before {
            content: "\e8c5"
        }

        ln ln-icon-Fingerprint-2:before {
            content: "\e8c6"
        }

        ln ln-icon-Fingerprint:before {
            content: "\e8c7"
        }

        ln ln-icon-Fire-Flame:before {
            content: "\e8c8"
        }

        ln ln-icon-Fire-Flame2:before {
            content: "\e8c9"
        }

        ln ln-icon-Fire-Hydrant:before {
            content: "\e8ca"
        }

        ln ln-icon-Fire-Staion:before {
            content: "\e8cb"
        }

        ln ln-icon-Firefox:before {
            content: "\e8cc"
        }

        ln ln-icon-Firewall:before {
            content: "\e8cd"
        }

        ln ln-icon-First-Aid:before {
            content: "\e8ce"
        }

        ln ln-icon-First:before {
            content: "\e8cf"
        }

        ln ln-icon-Fish-Food:before {
            content: "\e8d0"
        }

        ln ln-icon-Fish:before {
            content: "\e8d1"
        }

        ln ln-icon-Fit-To:before {
            content: "\e8d2"
        }

        ln ln-icon-Fit-To2:before {
            content: "\e8d3"
        }

        ln ln-icon-Five-Fingers:before {
            content: "\e8d4"
        }

        ln ln-icon-Five-FingersDrag:before {
            content: "\e8d5"
        }

        ln ln-icon-Five-FingersDrag2:before {
            content: "\e8d6"
        }

        ln ln-icon-Five-FingersTouch:before {
            content: "\e8d7"
        }

        ln ln-icon-Flag-2:before {
            content: "\e8d8"
        }

        ln ln-icon-Flag-3:before {
            content: "\e8d9"
        }

        ln ln-icon-Flag-4:before {
            content: "\e8da"
        }

        ln ln-icon-Flag-5:before {
            content: "\e8db"
        }

        ln ln-icon-Flag-6:before {
            content: "\e8dc"
        }

        ln ln-icon-Flag:before {
            content: "\e8dd"
        }

        ln ln-icon-Flamingo:before {
            content: "\e8de"
        }

        ln ln-icon-Flash-2:before {
            content: "\e8df"
        }

        ln ln-icon-Flash-Video:before {
            content: "\e8e0"
        }

        ln ln-icon-Flash:before {
            content: "\e8e1"
        }

        ln ln-icon-Flashlight:before {
            content: "\e8e2"
        }

        ln ln-icon-Flask-2:before {
            content: "\e8e3"
        }

        ln ln-icon-Flask:before {
            content: "\e8e4"
        }

        ln ln-icon-Flick:before {
            content: "\e8e5"
        }

        ln ln-icon-Flickr:before {
            content: "\e8e6"
        }

        ln ln-icon-Flowerpot:before {
            content: "\e8e7"
        }

        ln ln-icon-Fluorescent:before {
            content: "\e8e8"
        }

        ln ln-icon-Fog-Day:before {
            content: "\e8e9"
        }

        ln ln-icon-Fog-Night:before {
            content: "\e8ea"
        }

        ln ln-icon-Folder-Add:before {
            content: "\e8eb"
        }

        ln ln-icon-Folder-Archive:before {
            content: "\e8ec"
        }

        ln ln-icon-Folder-Binder:before {
            content: "\e8ed"
        }

        ln ln-icon-Folder-Binder2:before {
            content: "\e8ee"
        }

        ln ln-icon-Folder-Block:before {
            content: "\e8ef"
        }

        ln ln-icon-Folder-Bookmark:before {
            content: "\e8f0"
        }

        ln ln-icon-Folder-Close:before {
            content: "\e8f1"
        }

        ln ln-icon-Folder-Cloud:before {
            content: "\e8f2"
        }

        ln ln-icon-Folder-Delete:before {
            content: "\e8f3"
        }

        ln ln-icon-Folder-Download:before {
            content: "\e8f4"
        }

        ln ln-icon-Folder-Edit:before {
            content: "\e8f5"
        }

        ln ln-icon-Folder-Favorite:before {
            content: "\e8f6"
        }

        ln ln-icon-Folder-Fire:before {
            content: "\e8f7"
        }

        ln ln-icon-Folder-Hide:before {
            content: "\e8f8"
        }

        ln ln-icon-Folder-Link:before {
            content: "\e8f9"
        }

        ln ln-icon-Folder-Loading:before {
            content: "\e8fa"
        }

        ln ln-icon-Folder-Lock:before {
            content: "\e8fb"
        }

        ln ln-icon-Folder-Love:before {
            content: "\e8fc"
        }

        ln ln-icon-Folder-Music:before {
            content: "\e8fd"
        }

        ln ln-icon-Folder-Network:before {
            content: "\e8fe"
        }

        ln ln-icon-Folder-Open:before {
            content: "\e8ff"
        }

        ln ln-icon-Folder-Open2:before {
            content: "\e900"
        }

        ln ln-icon-Folder-Organizing:before {
            content: "\e901"
        }

        ln ln-icon-Folder-Pictures:before {
            content: "\e902"
        }

        ln ln-icon-Folder-Refresh:before {
            content: "\e903"
        }

        ln ln-icon-Folder-Remove-:before {
            content: "\e904"
        }

        ln ln-icon-Folder-Search:before {
            content: "\e905"
        }

        ln ln-icon-Folder-Settings:before {
            content: "\e906"
        }

        ln ln-icon-Folder-Share:before {
            content: "\e907"
        }

        ln ln-icon-Folder-Trash:before {
            content: "\e908"
        }

        ln ln-icon-Folder-Upload:before {
            content: "\e909"
        }

        ln ln-icon-Folder-Video:before {
            content: "\e90a"
        }

        ln ln-icon-Folder-WithDocument:before {
            content: "\e90b"
        }

        ln ln-icon-Folder-Zip:before {
            content: "\e90c"
        }

        ln ln-icon-Folder:before {
            content: "\e90d"
        }

        ln ln-icon-Folders:before {
            content: "\e90e"
        }

        ln ln-icon-Font-Color:before {
            content: "\e90f"
        }

        ln ln-icon-Font-Name:before {
            content: "\e910"
        }

        ln ln-icon-Font-Size:before {
            content: "\e911"
        }

        ln ln-icon-Font-Style:before {
            content: "\e912"
        }

        ln ln-icon-Font-StyleSubscript:before {
            content: "\e913"
        }

        ln ln-icon-Font-StyleSuperscript:before {
            content: "\e914"
        }

        ln ln-icon-Font-Window:before {
            content: "\e915"
        }

        ln ln-icon-Foot-2:before {
            content: "\e916"
        }

        ln ln-icon-Foot:before {
            content: "\e917"
        }

        ln ln-icon-Football-2:before {
            content: "\e918"
        }

        ln ln-icon-Football:before {
            content: "\e919"
        }

        ln ln-icon-Footprint-2:before {
            content: "\e91a"
        }

        ln ln-icon-Footprint-3:before {
            content: "\e91b"
        }

        ln ln-icon-Footprint:before {
            content: "\e91c"
        }

        ln ln-icon-Forest:before {
            content: "\e91d"
        }

        ln ln-icon-Fork:before {
            content: "\e91e"
        }

        ln ln-icon-Formspring:before {
            content: "\e91f"
        }

        ln ln-icon-Formula:before {
            content: "\e920"
        }

        ln ln-icon-Forsquare:before {
            content: "\e921"
        }

        ln ln-icon-Forward:before {
            content: "\e922"
        }

        ln ln-icon-Fountain-Pen:before {
            content: "\e923"
        }

        ln ln-icon-Four-Fingers:before {
            content: "\e924"
        }

        ln ln-icon-Four-FingersDrag:before {
            content: "\e925"
        }

        ln ln-icon-Four-FingersDrag2:before {
            content: "\e926"
        }

        ln ln-icon-Four-FingersTouch:before {
            content: "\e927"
        }

        ln ln-icon-Fox:before {
            content: "\e928"
        }

        ln ln-icon-Frankenstein:before {
            content: "\e929"
        }

        ln ln-icon-French-Fries:before {
            content: "\e92a"
        }

        ln ln-icon-Friendfeed:before {
            content: "\e92b"
        }

        ln ln-icon-Friendster:before {
            content: "\e92c"
        }

        ln ln-icon-Frog:before {
            content: "\e92d"
        }

        ln ln-icon-Fruits:before {
            content: "\e92e"
        }

        ln ln-icon-Fuel:before {
            content: "\e92f"
        }

        ln ln-icon-Full-Bag:before {
            content: "\e930"
        }

        ln ln-icon-Full-Basket:before {
            content: "\e931"
        }

        ln ln-icon-Full-Cart:before {
            content: "\e932"
        }

        ln ln-icon-Full-Moon:before {
            content: "\e933"
        }

        ln ln-icon-Full-Screen:before {
            content: "\e934"
        }

        ln ln-icon-Full-Screen2:before {
            content: "\e935"
        }

        ln ln-icon-Full-View:before {
            content: "\e936"
        }

        ln ln-icon-Full-View2:before {
            content: "\e937"
        }

        ln ln-icon-Full-ViewWindow:before {
            content: "\e938"
        }

        ln ln-icon-Function:before {
            content: "\e939"
        }

        ln ln-icon-Funky:before {
            content: "\e93a"
        }

        ln ln-icon-Funny-Bicycle:before {
            content: "\e93b"
        }

        ln ln-icon-Furl:before {
            content: "\e93c"
        }

        ln ln-icon-Gamepad-2:before {
            content: "\e93d"
        }

        ln ln-icon-Gamepad:before {
            content: "\e93e"
        }

        ln ln-icon-Gas-Pump:before {
            content: "\e93f"
        }

        ln ln-icon-Gaugage-2:before {
            content: "\e940"
        }

        ln ln-icon-Gaugage:before {
            content: "\e941"
        }

        ln ln-icon-Gay:before {
            content: "\e942"
        }

        ln ln-icon-Gear-2:before {
            content: "\e943"
        }

        ln ln-icon-Gear:before {
            content: "\e944"
        }

        ln ln-icon-Gears-2:before {
            content: "\e945"
        }

        ln ln-icon-Gears:before {
            content: "\e946"
        }

        ln ln-icon-Geek-2:before {
            content: "\e947"
        }

        ln ln-icon-Geek:before {
            content: "\e948"
        }

        ln ln-icon-Gemini-2:before {
            content: "\e949"
        }

        ln ln-icon-Gemini:before {
            content: "\e94a"
        }

        ln ln-icon-Genius:before {
            content: "\e94b"
        }

        ln ln-icon-Gentleman:before {
            content: "\e94c"
        }

        ln ln-icon-Geo--:before {
            content: "\e94d"
        }

        ln ln-icon-Geo-:before {
            content: "\e94e"
        }

        ln ln-icon-Geo-Close:before {
            content: "\e94f"
        }

        ln ln-icon-Geo-Love:before {
            content: "\e950"
        }

        ln ln-icon-Geo-Number:before {
            content: "\e951"
        }

        ln ln-icon-Geo-Star:before {
            content: "\e952"
        }

        ln ln-icon-Geo:before {
            content: "\e953"
        }

        ln ln-icon-Geo2--:before {
            content: "\e954"
        }

        ln ln-icon-Geo2-:before {
            content: "\e955"
        }

        ln ln-icon-Geo2-Close:before {
            content: "\e956"
        }

        ln ln-icon-Geo2-Love:before {
            content: "\e957"
        }

        ln ln-icon-Geo2-Number:before {
            content: "\e958"
        }

        ln ln-icon-Geo2-Star:before {
            content: "\e959"
        }

        ln ln-icon-Geo2:before {
            content: "\e95a"
        }

        ln ln-icon-Geo3--:before {
            content: "\e95b"
        }

        ln ln-icon-Geo3-:before {
            content: "\e95c"
        }

        ln ln-icon-Geo3-Close:before {
            content: "\e95d"
        }

        ln ln-icon-Geo3-Love:before {
            content: "\e95e"
        }

        ln ln-icon-Geo3-Number:before {
            content: "\e95f"
        }

        ln ln-icon-Geo3-Star:before {
            content: "\e960"
        }

        ln ln-icon-Geo3:before {
            content: "\e961"
        }

        ln ln-icon-Gey:before {
            content: "\e962"
        }

        ln ln-icon-Gift-Box:before {
            content: "\e963"
        }

        ln ln-icon-Giraffe:before {
            content: "\e964"
        }

        ln ln-icon-Girl:before {
            content: "\e965"
        }

        ln ln-icon-Glass-Water:before {
            content: "\e966"
        }

        ln ln-icon-Glasses-2:before {
            content: "\e967"
        }

        ln ln-icon-Glasses-3:before {
            content: "\e968"
        }

        ln ln-icon-Glasses:before {
            content: "\e969"
        }

        ln ln-icon-Global-Position:before {
            content: "\e96a"
        }

        ln ln-icon-Globe-2:before {
            content: "\e96b"
        }

        ln ln-icon-Globe:before {
            content: "\e96c"
        }

        ln ln-icon-Gloves:before {
            content: "\e96d"
        }

        ln ln-icon-Go-Bottom:before {
            content: "\e96e"
        }

        ln ln-icon-Go-Top:before {
            content: "\e96f"
        }

        ln ln-icon-Goggles:before {
            content: "\e970"
        }

        ln ln-icon-Golf-2:before {
            content: "\e971"
        }

        ln ln-icon-Golf:before {
            content: "\e972"
        }

        ln ln-icon-Google-Buzz:before {
            content: "\e973"
        }

        ln ln-icon-Google-Drive:before {
            content: "\e974"
        }

        ln ln-icon-Google-Play:before {
            content: "\e975"
        }

        ln ln-icon-Google-Plus:before {
            content: "\e976"
        }

        ln ln-icon-Google:before {
            content: "\e977"
        }

        ln ln-icon-Gopro:before {
            content: "\e978"
        }

        ln ln-icon-Gorilla:before {
            content: "\e979"
        }

        ln ln-icon-Gowalla:before {
            content: "\e97a"
        }

        ln ln-icon-Grave:before {
            content: "\e97b"
        }

        ln ln-icon-Graveyard:before {
            content: "\e97c"
        }

        ln ln-icon-Greece:before {
            content: "\e97d"
        }

        ln ln-icon-Green-Energy:before {
            content: "\e97e"
        }

        ln ln-icon-Green-House:before {
            content: "\e97f"
        }

        ln ln-icon-Guitar:before {
            content: "\e980"
        }

        ln ln-icon-Gun-2:before {
            content: "\e981"
        }

        ln ln-icon-Gun-3:before {
            content: "\e982"
        }

        ln ln-icon-Gun:before {
            content: "\e983"
        }

        ln ln-icon-Gymnastics:before {
            content: "\e984"
        }

        ln ln-icon-Hair-2:before {
            content: "\e985"
        }

        ln ln-icon-Hair-3:before {
            content: "\e986"
        }

        ln ln-icon-Hair-4:before {
            content: "\e987"
        }

        ln ln-icon-Hair:before {
            content: "\e988"
        }

        ln ln-icon-Half-Moon:before {
            content: "\e989"
        }

        ln ln-icon-Halloween-HalfMoon:before {
            content: "\e98a"
        }

        ln ln-icon-Halloween-Moon:before {
            content: "\e98b"
        }

        ln ln-icon-Hamburger:before {
            content: "\e98c"
        }

        ln ln-icon-Hammer:before {
            content: "\e98d"
        }

        ln ln-icon-Hand-Touch:before {
            content: "\e98e"
        }

        ln ln-icon-Hand-Touch2:before {
            content: "\e98f"
        }

        ln ln-icon-Hand-TouchSmartphone:before {
            content: "\e990"
        }

        ln ln-icon-Hand:before {
            content: "\e991"
        }

        ln ln-icon-Hands:before {
            content: "\e992"
        }

        ln ln-icon-Handshake:before {
            content: "\e993"
        }

        ln ln-icon-Hanger:before {
            content: "\e994"
        }

        ln ln-icon-Happy:before {
            content: "\e995"
        }

        ln ln-icon-Hat-2:before {
            content: "\e996"
        }

        ln ln-icon-Hat:before {
            content: "\e997"
        }

        ln ln-icon-Haunted-House:before {
            content: "\e998"
        }

        ln ln-icon-HD-Video:before {
            content: "\e999"
        }

        ln ln-icon-HD:before {
            content: "\e99a"
        }

        ln ln-icon-HDD:before {
            content: "\e99b"
        }

        ln ln-icon-Headphone:before {
            content: "\e99c"
        }

        ln ln-icon-Headphones:before {
            content: "\e99d"
        }

        ln ln-icon-Headset:before {
            content: "\e99e"
        }

        ln ln-icon-Heart-2:before {
            content: "\e99f"
        }

        ln ln-icon-Heart:before {
            content: "\e9a0"
        }

        ln ln-icon-Heels-2:before {
            content: "\e9a1"
        }

        ln ln-icon-Heels:before {
            content: "\e9a2"
        }

        ln ln-icon-Height-Window:before {
            content: "\e9a3"
        }

        ln ln-icon-Helicopter-2:before {
            content: "\e9a4"
        }

        ln ln-icon-Helicopter:before {
            content: "\e9a5"
        }

        ln ln-icon-Helix-2:before {
            content: "\e9a6"
        }

        ln ln-icon-Hello:before {
            content: "\e9a7"
        }

        ln ln-icon-Helmet-2:before {
            content: "\e9a8"
        }

        ln ln-icon-Helmet-3:before {
            content: "\e9a9"
        }

        ln ln-icon-Helmet:before {
            content: "\e9aa"
        }

        ln ln-icon-Hipo:before {
            content: "\e9ab"
        }

        ln ln-icon-Hipster-Glasses:before {
            content: "\e9ac"
        }

        ln ln-icon-Hipster-Glasses2:before {
            content: "\e9ad"
        }

        ln ln-icon-Hipster-Glasses3:before {
            content: "\e9ae"
        }

        ln ln-icon-Hipster-Headphones:before {
            content: "\e9af"
        }

        ln ln-icon-Hipster-Men:before {
            content: "\e9b0"
        }

        ln ln-icon-Hipster-Men2:before {
            content: "\e9b1"
        }

        ln ln-icon-Hipster-Men3:before {
            content: "\e9b2"
        }

        ln ln-icon-Hipster-Sunglasses:before {
            content: "\e9b3"
        }

        ln ln-icon-Hipster-Sunglasses2:before {
            content: "\e9b4"
        }

        ln ln-icon-Hipster-Sunglasses3:before {
            content: "\e9b5"
        }

        ln ln-icon-Hokey:before {
            content: "\e9b6"
        }

        ln ln-icon-Holly:before {
            content: "\e9b7"
        }

        ln ln-icon-Home-2:before {
            content: "\e9b8"
        }

        ln ln-icon-Home-3:before {
            content: "\e9b9"
        }

        ln ln-icon-Home-4:before {
            content: "\e9ba"
        }

        ln ln-icon-Home-5:before {
            content: "\e9bb"
        }

        ln ln-icon-Home-Window:before {
            content: "\e9bc"
        }

        ln ln-icon-Home:before {
            content: "\e9bd"
        }

        ln ln-icon-Homosexual:before {
            content: "\e9be"
        }

        ln ln-icon-Honey:before {
            content: "\e9bf"
        }

        ln ln-icon-Hong-Kong:before {
            content: "\e9c0"
        }

        ln ln-icon-Hoodie:before {
            content: "\e9c1"
        }

        ln ln-icon-Horror:before {
            content: "\e9c2"
        }

        ln ln-icon-Horse:before {
            content: "\e9c3"
        }

        ln ln-icon-Hospital-2:before {
            content: "\e9c4"
        }

        ln ln-icon-Hospital:before {
            content: "\e9c5"
        }

        ln ln-icon-Host:before {
            content: "\e9c6"
        }

        ln ln-icon-Hot-Dog:before {
            content: "\e9c7"
        }

        ln ln-icon-Hotel:before {
            content: "\e9c8"
        }

        ln ln-icon-Hour:before {
            content: "\e9c9"
        }

        ln ln-icon-Hub:before {
            content: "\e9ca"
        }

        ln ln-icon-Humor:before {
            content: "\e9cb"
        }

        ln ln-icon-Hurt:before {
            content: "\e9cc"
        }

        ln ln-icon-Ice-Cream:before {
            content: "\e9cd"
        }

        ln ln-icon-ICQ:before {
            content: "\e9ce"
        }

        ln ln-icon-ID-2:before {
            content: "\e9cf"
        }

        ln ln-icon-ID-3:before {
            content: "\e9d0"
        }

        ln ln-icon-ID-Card:before {
            content: "\e9d1"
        }

        ln ln-icon-Idea-2:before {
            content: "\e9d2"
        }

        ln ln-icon-Idea-3:before {
            content: "\e9d3"
        }

        ln ln-icon-Idea-4:before {
            content: "\e9d4"
        }

        ln ln-icon-Idea-5:before {
            content: "\e9d5"
        }

        ln ln-icon-Idea:before {
            content: "\e9d6"
        }

        ln ln-icon-Identification-Badge:before {
            content: "\e9d7"
        }

        ln ln-icon-ImDB:before {
            content: "\e9d8"
        }

        ln ln-icon-Inbox-Empty:before {
            content: "\e9d9"
        }

        ln ln-icon-Inbox-Forward:before {
            content: "\e9da"
        }

        ln ln-icon-Inbox-Full:before {
            content: "\e9db"
        }

        ln ln-icon-Inbox-Into:before {
            content: "\e9dc"
        }

        ln ln-icon-Inbox-Out:before {
            content: "\e9dd"
        }

        ln ln-icon-Inbox-Reply:before {
            content: "\e9de"
        }

        ln ln-icon-Inbox:before {
            content: "\e9df"
        }

        ln ln-icon-Increase-Inedit:before {
            content: "\e9e0"
        }

        ln ln-icon-Indent-FirstLine:before {
            content: "\e9e1"
        }

        ln ln-icon-Indent-LeftMargin:before {
            content: "\e9e2"
        }

        ln ln-icon-Indent-RightMargin:before {
            content: "\e9e3"
        }

        ln ln-icon-India:before {
            content: "\e9e4"
        }

        ln ln-icon-Info-Window:before {
            content: "\e9e5"
        }

        ln ln-icon-Information:before {
            content: "\e9e6"
        }

        ln ln-icon-Inifity:before {
            content: "\e9e7"
        }

        ln ln-icon-Instagram:before {
            content: "\e9e8"
        }

        ln ln-icon-Internet-2:before {
            content: "\e9e9"
        }

        ln ln-icon-Internet-Explorer:before {
            content: "\e9ea"
        }

        ln ln-icon-Internet-Smiley:before {
            content: "\e9eb"
        }

        ln ln-icon-Internet:before {
            content: "\e9ec"
        }

        ln ln-icon-iOS-Apple:before {
            content: "\e9ed"
        }

        ln ln-icon-Israel:before {
            content: "\e9ee"
        }

        ln ln-icon-Italic-Text:before {
            content: "\e9ef"
        }

        ln ln-icon-Jacket-2:before {
            content: "\e9f0"
        }

        ln ln-icon-Jacket:before {
            content: "\e9f1"
        }

        ln ln-icon-Jamaica:before {
            content: "\e9f2"
        }

        ln ln-icon-Japan:before {
            content: "\e9f3"
        }

        ln ln-icon-Japanese-Gate:before {
            content: "\e9f4"
        }

        ln ln-icon-Jeans:before {
            content: "\e9f5"
        }

        ln ln-icon-Jeep-2:before {
            content: "\e9f6"
        }

        ln ln-icon-Jeep:before {
            content: "\e9f7"
        }

        ln ln-icon-Jet:before {
            content: "\e9f8"
        }

        ln ln-icon-Joystick:before {
            content: "\e9f9"
        }

        ln ln-icon-Juice:before {
            content: "\e9fa"
        }

        ln ln-icon-Jump-Rope:before {
            content: "\e9fb"
        }

        ln ln-icon-Kangoroo:before {
            content: "\e9fc"
        }

        ln ln-icon-Kenya:before {
            content: "\e9fd"
        }

        ln ln-icon-Key-2:before {
            content: "\e9fe"
        }

        ln ln-icon-Key-3:before {
            content: "\e9ff"
        }

        ln ln-icon-Key-Lock:before {
            content: "\ea00"
        }

        ln ln-icon-Key:before {
            content: "\ea01"
        }

        ln ln-icon-Keyboard:before {
            content: "\ea02"
        }

        ln ln-icon-Keyboard3:before {
            content: "\ea03"
        }

        ln ln-icon-Keypad:before {
            content: "\ea04"
        }

        ln ln-icon-King-2:before {
            content: "\ea05"
        }

        ln ln-icon-King:before {
            content: "\ea06"
        }

        ln ln-icon-Kiss:before {
            content: "\ea07"
        }

        ln ln-icon-Knee:before {
            content: "\ea08"
        }

        ln ln-icon-Knife-2:before {
            content: "\ea09"
        }

        ln ln-icon-Knife:before {
            content: "\ea0a"
        }

        ln ln-icon-Knight:before {
            content: "\ea0b"
        }

        ln ln-icon-Koala:before {
            content: "\ea0c"
        }

        ln ln-icon-Korea:before {
            content: "\ea0d"
        }

        ln ln-icon-Lamp:before {
            content: "\ea0e"
        }

        ln ln-icon-Landscape-2:before {
            content: "\ea0f"
        }

        ln ln-icon-Landscape:before {
            content: "\ea10"
        }

        ln ln-icon-Lantern:before {
            content: "\ea11"
        }

        ln ln-icon-Laptop-2:before {
            content: "\ea12"
        }

        ln ln-icon-Laptop-3:before {
            content: "\ea13"
        }

        ln ln-icon-Laptop-Phone:before {
            content: "\ea14"
        }

        ln ln-icon-Laptop-Secure:before {
            content: "\ea15"
        }

        ln ln-icon-Laptop-Tablet:before {
            content: "\ea16"
        }

        ln ln-icon-Laptop:before {
            content: "\ea17"
        }

        ln ln-icon-Laser:before {
            content: "\ea18"
        }

        ln ln-icon-Last-FM:before {
            content: "\ea19"
        }

        ln ln-icon-Last:before {
            content: "\ea1a"
        }

        ln ln-icon-Laughing:before {
            content: "\ea1b"
        }

        ln ln-icon-Layer-1635:before {
            content: "\ea1c"
        }

        ln ln-icon-Layer-1646:before {
            content: "\ea1d"
        }

        ln ln-icon-Layer-Backward:before {
            content: "\ea1e"
        }

        ln ln-icon-Layer-Forward:before {
            content: "\ea1f"
        }

        ln ln-icon-Leafs-2:before {
            content: "\ea20"
        }

        ln ln-icon-Leafs:before {
            content: "\ea21"
        }

        ln ln-icon-Leaning-Tower:before {
            content: "\ea22"
        }

        ln ln-icon-Left--Right:before {
            content: "\ea23"
        }

        ln ln-icon-Left--Right3:before {
            content: "\ea24"
        }

        ln ln-icon-Left-2:before {
            content: "\ea25"
        }

        ln ln-icon-Left-3:before {
            content: "\ea26"
        }

        ln ln-icon-Left-4:before {
            content: "\ea27"
        }

        ln ln-icon-Left-ToRight:before {
            content: "\ea28"
        }

        ln ln-icon-Left:before {
            content: "\ea29"
        }

        ln ln-icon-Leg-2:before {
            content: "\ea2a"
        }

        ln ln-icon-Leg:before {
            content: "\ea2b"
        }

        ln ln-icon-Lego:before {
            content: "\ea2c"
        }

        ln ln-icon-Lemon:before {
            content: "\ea2d"
        }

        ln ln-icon-Len-2:before {
            content: "\ea2e"
        }

        ln ln-icon-Len-3:before {
            content: "\ea2f"
        }

        ln ln-icon-Len:before {
            content: "\ea30"
        }

        ln ln-icon-Leo-2:before {
            content: "\ea31"
        }

        ln ln-icon-Leo:before {
            content: "\ea32"
        }

        ln ln-icon-Leopard:before {
            content: "\ea33"
        }

        ln ln-icon-Lesbian:before {
            content: "\ea34"
        }

        ln ln-icon-Lesbians:before {
            content: "\ea35"
        }

        ln ln-icon-Letter-Close:before {
            content: "\ea36"
        }

        ln ln-icon-Letter-Open:before {
            content: "\ea37"
        }

        ln ln-icon-Letter-Sent:before {
            content: "\ea38"
        }

        ln ln-icon-Libra-2:before {
            content: "\ea39"
        }

        ln ln-icon-Libra:before {
            content: "\ea3a"
        }

        ln ln-icon-Library-2:before {
            content: "\ea3b"
        }

        ln ln-icon-Library:before {
            content: "\ea3c"
        }

        ln ln-icon-Life-Jacket:before {
            content: "\ea3d"
        }

        ln ln-icon-Life-Safer:before {
            content: "\ea3e"
        }

        ln ln-icon-Light-Bulb:before {
            content: "\ea3f"
        }

        ln ln-icon-Light-Bulb2:before {
            content: "\ea40"
        }

        ln ln-icon-Light-BulbLeaf:before {
            content: "\ea41"
        }

        ln ln-icon-Lighthouse:before {
            content: "\ea42"
        }

        ln ln-icon-Like-2:before {
            content: "\ea43"
        }

        ln ln-icon-Like:before {
            content: "\ea44"
        }

        ln ln-icon-Line-Chart:before {
            content: "\ea45"
        }

        ln ln-icon-Line-Chart2:before {
            content: "\ea46"
        }

        ln ln-icon-Line-Chart3:before {
            content: "\ea47"
        }

        ln ln-icon-Line-Chart4:before {
            content: "\ea48"
        }

        ln ln-icon-Line-Spacing:before {
            content: "\ea49"
        }

        ln ln-icon-Line-SpacingText:before {
            content: "\ea4a"
        }

        ln ln-icon-Link-2:before {
            content: "\ea4b"
        }

        ln ln-icon-Link:before {
            content: "\ea4c"
        }

        ln ln-icon-Linkedin-2:before {
            content: "\ea4d"
        }

        ln ln-icon-Linkedin:before {
            content: "\ea4e"
        }

        ln ln-icon-Linux:before {
            content: "\ea4f"
        }

        ln ln-icon-Lion:before {
            content: "\ea50"
        }

        ln ln-icon-Livejournal:before {
            content: "\ea51"
        }

        ln ln-icon-Loading-2:before {
            content: "\ea52"
        }

        ln ln-icon-Loading-3:before {
            content: "\ea53"
        }

        ln ln-icon-Loading-Window:before {
            content: "\ea54"
        }

        ln ln-icon-Loading:before {
            content: "\ea55"
        }

        ln ln-icon-Location-2:before {
            content: "\ea56"
        }

        ln ln-icon-Location:before {
            content: "\ea57"
        }

        ln ln-icon-Lock-2:before {
            content: "\ea58"
        }

        ln ln-icon-Lock-3:before {
            content: "\ea59"
        }

        ln ln-icon-Lock-User:before {
            content: "\ea5a"
        }

        ln ln-icon-Lock-Window:before {
            content: "\ea5b"
        }

        ln ln-icon-Lock:before {
            content: "\ea5c"
        }

        ln ln-icon-Lollipop-2:before {
            content: "\ea5d"
        }

        ln ln-icon-Lollipop-3:before {
            content: "\ea5e"
        }

        ln ln-icon-Lollipop:before {
            content: "\ea5f"
        }

        ln ln-icon-Loop:before {
            content: "\ea60"
        }

        ln ln-icon-Loud:before {
            content: "\ea61"
        }

        ln ln-icon-Loudspeaker:before {
            content: "\ea62"
        }

        ln ln-icon-Love-2:before {
            content: "\ea63"
        }

        ln ln-icon-Love-User:before {
            content: "\ea64"
        }

        ln ln-icon-Love-Window:before {
            content: "\ea65"
        }

        ln ln-icon-Love:before {
            content: "\ea66"
        }

        ln ln-icon-Lowercase-Text:before {
            content: "\ea67"
        }

        ln ln-icon-Luggafe-Front:before {
            content: "\ea68"
        }

        ln ln-icon-Luggage-2:before {
            content: "\ea69"
        }

        ln ln-icon-Macro:before {
            content: "\ea6a"
        }

        ln ln-icon-Magic-Wand:before {
            content: "\ea6b"
        }

        ln ln-icon-Magnet:before {
            content: "\ea6c"
        }

        ln ln-icon-Magnifi-Glass-:before {
            content: "\ea6d"
        }

        ln ln-icon-Magnifi-Glass:before {
            content: "\ea6e"
        }

        ln ln-icon-Magnifi-Glass2:before {
            content: "\ea6f"
        }

        ln ln-icon-Mail-2:before {
            content: "\ea70"
        }

        ln ln-icon-Mail-3:before {
            content: "\ea71"
        }

        ln ln-icon-Mail-Add:before {
            content: "\ea72"
        }

        ln ln-icon-Mail-Attachement:before {
            content: "\ea73"
        }

        ln ln-icon-Mail-Block:before {
            content: "\ea74"
        }

        ln ln-icon-Mail-Delete:before {
            content: "\ea75"
        }

        ln ln-icon-Mail-Favorite:before {
            content: "\ea76"
        }

        ln ln-icon-Mail-Forward:before {
            content: "\ea77"
        }

        ln ln-icon-Mail-Gallery:before {
            content: "\ea78"
        }

        ln ln-icon-Mail-Inbox:before {
            content: "\ea79"
        }

        ln ln-icon-Mail-Link:before {
            content: "\ea7a"
        }

        ln ln-icon-Mail-Lock:before {
            content: "\ea7b"
        }

        ln ln-icon-Mail-Love:before {
            content: "\ea7c"
        }

        ln ln-icon-Mail-Money:before {
            content: "\ea7d"
        }

        ln ln-icon-Mail-Open:before {
            content: "\ea7e"
        }

        ln ln-icon-Mail-Outbox:before {
            content: "\ea7f"
        }

        ln ln-icon-Mail-Password:before {
            content: "\ea80"
        }

        ln ln-icon-Mail-Photo:before {
            content: "\ea81"
        }

        ln ln-icon-Mail-Read:before {
            content: "\ea82"
        }

        ln ln-icon-Mail-Removex:before {
            content: "\ea83"
        }

        ln ln-icon-Mail-Reply:before {
            content: "\ea84"
        }

        ln ln-icon-Mail-ReplyAll:before {
            content: "\ea85"
        }

        ln ln-icon-Mail-Search:before {
            content: "\ea86"
        }

        ln ln-icon-Mail-Send:before {
            content: "\ea87"
        }

        ln ln-icon-Mail-Settings:before {
            content: "\ea88"
        }

        ln ln-icon-Mail-Unread:before {
            content: "\ea89"
        }

        ln ln-icon-Mail-Video:before {
            content: "\ea8a"
        }

        ln ln-icon-Mail-withAtSign:before {
            content: "\ea8b"
        }

        ln ln-icon-Mail-WithCursors:before {
            content: "\ea8c"
        }

        ln ln-icon-Mail:before {
            content: "\ea8d"
        }

        ln ln-icon-Mailbox-Empty:before {
            content: "\ea8e"
        }

        ln ln-icon-Mailbox-Full:before {
            content: "\ea8f"
        }

        ln ln-icon-Male-2:before {
            content: "\ea90"
        }

        ln ln-icon-Male-Sign:before {
            content: "\ea91"
        }

        ln ln-icon-Male:before {
            content: "\ea92"
        }

        ln ln-icon-MaleFemale:before {
            content: "\ea93"
        }

        ln ln-icon-Man-Sign:before {
            content: "\ea94"
        }

        ln ln-icon-Management:before {
            content: "\ea95"
        }

        ln ln-icon-Mans-Underwear:before {
            content: "\ea96"
        }

        ln ln-icon-Mans-Underwear2:before {
            content: "\ea97"
        }

        ln ln-icon-Map-Marker:before {
            content: "\ea98"
        }

        ln ln-icon-Map-Marker2:before {
            content: "\ea99"
        }

        ln ln-icon-Map-Marker3:before {
            content: "\ea9a"
        }

        ln ln-icon-Map:before {
            content: "\ea9b"
        }

        ln ln-icon-Map2:before {
            content: "\ea9c"
        }

        ln ln-icon-Marker-2:before {
            content: "\ea9d"
        }

        ln ln-icon-Marker-3:before {
            content: "\ea9e"
        }

        ln ln-icon-Marker:before {
            content: "\ea9f"
        }

        ln ln-icon-Martini-Glass:before {
            content: "\eaa0"
        }

        ln ln-icon-Mask:before {
            content: "\eaa1"
        }

        ln ln-icon-Master-Card:before {
            content: "\eaa2"
        }

        ln ln-icon-Maximize-Window:before {
            content: "\eaa3"
        }

        ln ln-icon-Maximize:before {
            content: "\eaa4"
        }

        ln ln-icon-Medal-2:before {
            content: "\eaa5"
        }

        ln ln-icon-Medal-3:before {
            content: "\eaa6"
        }

        ln ln-icon-Medal:before {
            content: "\eaa7"
        }

        ln ln-icon-Medical-Sign:before {
            content: "\eaa8"
        }

        ln ln-icon-Medicine-2:before {
            content: "\eaa9"
        }

        ln ln-icon-Medicine-3:before {
            content: "\eaaa"
        }

        ln ln-icon-Medicine:before {
            content: "\eaab"
        }

        ln ln-icon-Megaphone:before {
            content: "\eaac"
        }

        ln ln-icon-Memory-Card:before {
            content: "\eaad"
        }

        ln ln-icon-Memory-Card2:before {
            content: "\eaae"
        }

        ln ln-icon-Memory-Card3:before {
            content: "\eaaf"
        }

        ln ln-icon-Men:before {
            content: "\eab0"
        }

        ln ln-icon-Menorah:before {
            content: "\eab1"
        }

        ln ln-icon-Mens:before {
            content: "\eab2"
        }

        ln ln-icon-Metacafe:before {
            content: "\eab3"
        }

        ln ln-icon-Mexico:before {
            content: "\eab4"
        }

        ln ln-icon-Mic:before {
            content: "\eab5"
        }

        ln ln-icon-Microphone-2:before {
            content: "\eab6"
        }

        ln ln-icon-Microphone-3:before {
            content: "\eab7"
        }

        ln ln-icon-Microphone-4:before {
            content: "\eab8"
        }

        ln ln-icon-Microphone-5:before {
            content: "\eab9"
        }

        ln ln-icon-Microphone-6:before {
            content: "\eaba"
        }

        ln ln-icon-Microphone-7:before {
            content: "\eabb"
        }

        ln ln-icon-Microphone:before {
            content: "\eabc"
        }

        ln ln-icon-Microscope:before {
            content: "\eabd"
        }

        ln ln-icon-Milk-Bottle:before {
            content: "\eabe"
        }

        ln ln-icon-Mine:before {
            content: "\eabf"
        }

        ln ln-icon-Minimize-Maximize-Close-Window:before {
            content: "\eac0"
        }

        ln ln-icon-Minimize-Window:before {
            content: "\eac1"
        }

        ln ln-icon-Minimize:before {
            content: "\eac2"
        }

        ln ln-icon-Mirror:before {
            content: "\eac3"
        }

        ln ln-icon-Mixer:before {
            content: "\eac4"
        }

        ln ln-icon-Mixx:before {
            content: "\eac5"
        }

        ln ln-icon-Money-2:before {
            content: "\eac6"
        }

        ln ln-icon-Money-Bag:before {
            content: "\eac7"
        }

        ln ln-icon-Money-Smiley:before {
            content: "\eac8"
        }

        ln ln-icon-Money:before {
            content: "\eac9"
        }

        ln ln-icon-Monitor-2:before {
            content: "\eaca"
        }

        ln ln-icon-Monitor-3:before {
            content: "\eacb"
        }

        ln ln-icon-Monitor-4:before {
            content: "\eacc"
        }

        ln ln-icon-Monitor-5:before {
            content: "\eacd"
        }

        ln ln-icon-Monitor-Analytics:before {
            content: "\eace"
        }

        ln ln-icon-Monitor-Laptop:before {
            content: "\eacf"
        }

        ln ln-icon-Monitor-phone:before {
            content: "\ead0"
        }

        ln ln-icon-Monitor-Tablet:before {
            content: "\ead1"
        }

        ln ln-icon-Monitor-Vertical:before {
            content: "\ead2"
        }

        ln ln-icon-Monitor:before {
            content: "\ead3"
        }

        ln ln-icon-Monitoring:before {
            content: "\ead4"
        }

        ln ln-icon-Monkey:before {
            content: "\ead5"
        }

        ln ln-icon-Monster:before {
            content: "\ead6"
        }

        ln ln-icon-Morocco:before {
            content: "\ead7"
        }

        ln ln-icon-Motorcycle:before {
            content: "\ead8"
        }

        ln ln-icon-Mouse-2:before {
            content: "\ead9"
        }

        ln ln-icon-Mouse-3:before {
            content: "\eada"
        }

        ln ln-icon-Mouse-4:before {
            content: "\eadb"
        }

        ln ln-icon-Mouse-Pointer:before {
            content: "\eadc"
        }

        ln ln-icon-Mouse:before {
            content: "\eadd"
        }

        ln ln-icon-Moustache-Smiley:before {
            content: "\eade"
        }

        ln ln-icon-Movie-Ticket:before {
            content: "\eadf"
        }

        ln ln-icon-Movie:before {
            content: "\eae0"
        }

        ln ln-icon-Mp3-File:before {
            content: "\eae1"
        }

        ln ln-icon-Museum:before {
            content: "\eae2"
        }

        ln ln-icon-Mushroom:before {
            content: "\eae3"
        }

        ln ln-icon-Music-Note:before {
            content: "\eae4"
        }

        ln ln-icon-Music-Note2:before {
            content: "\eae5"
        }

        ln ln-icon-Music-Note3:before {
            content: "\eae6"
        }

        ln ln-icon-Music-Note4:before {
            content: "\eae7"
        }

        ln ln-icon-Music-Player:before {
            content: "\eae8"
        }

        ln ln-icon-Mustache-2:before {
            content: "\eae9"
        }

        ln ln-icon-Mustache-3:before {
            content: "\eaea"
        }

        ln ln-icon-Mustache-4:before {
            content: "\eaeb"
        }

        ln ln-icon-Mustache-5:before {
            content: "\eaec"
        }

        ln ln-icon-Mustache-6:before {
            content: "\eaed"
        }

        ln ln-icon-Mustache-7:before {
            content: "\eaee"
        }

        ln ln-icon-Mustache-8:before {
            content: "\eaef"
        }

        ln ln-icon-Mustache:before {
            content: "\eaf0"
        }

        ln ln-icon-Mute:before {
            content: "\eaf1"
        }

        ln ln-icon-Myspace:before {
            content: "\eaf2"
        }

        ln ln-icon-Navigat-Start:before {
            content: "\eaf3"
        }

        ln ln-icon-Navigate-End:before {
            content: "\eaf4"
        }

        ln ln-icon-Navigation-LeftWindow:before {
            content: "\eaf5"
        }

        ln ln-icon-Navigation-RightWindow:before {
            content: "\eaf6"
        }

        ln ln-icon-Nepal:before {
            content: "\eaf7"
        }

        ln ln-icon-Netscape:before {
            content: "\eaf8"
        }

        ln ln-icon-Network-Window:before {
            content: "\eaf9"
        }

        ln ln-icon-Network:before {
            content: "\eafa"
        }

        ln ln-icon-Neutron:before {
            content: "\eafb"
        }

        ln ln-icon-New-Mail:before {
            content: "\eafc"
        }

        ln ln-icon-New-Tab:before {
            content: "\eafd"
        }

        ln ln-icon-Newspaper-2:before {
            content: "\eafe"
        }

        ln ln-icon-Newspaper:before {
            content: "\eaff"
        }

        ln ln-icon-Newsvine:before {
            content: "\eb00"
        }

        ln ln-icon-Next2:before {
            content: "\eb01"
        }

        ln ln-icon-Next-3:before {
            content: "\eb02"
        }

        ln ln-icon-Next-Music:before {
            content: "\eb03"
        }

        ln ln-icon-Next:before {
            content: "\eb04"
        }

        ln ln-icon-No-Battery:before {
            content: "\eb05"
        }

        ln ln-icon-No-Drop:before {
            content: "\eb06"
        }

        ln ln-icon-No-Flash:before {
            content: "\eb07"
        }

        ln ln-icon-No-Smoking:before {
            content: "\eb08"
        }

        ln ln-icon-Noose:before {
            content: "\eb09"
        }

        ln ln-icon-Normal-Text:before {
            content: "\eb0a"
        }

        ln ln-icon-Note:before {
            content: "\eb0b"
        }

        ln ln-icon-Notepad-2:before {
            content: "\eb0c"
        }

        ln ln-icon-Notepad:before {
            content: "\eb0d"
        }

        ln ln-icon-Nuclear:before {
            content: "\eb0e"
        }

        ln ln-icon-Numbering-List:before {
            content: "\eb0f"
        }

        ln ln-icon-Nurse:before {
            content: "\eb10"
        }

        ln ln-icon-Office-Lamp:before {
            content: "\eb11"
        }

        ln ln-icon-Office:before {
            content: "\eb12"
        }

        ln ln-icon-Oil:before {
            content: "\eb13"
        }

        ln ln-icon-Old-Camera:before {
            content: "\eb14"
        }

        ln ln-icon-Old-Cassette:before {
            content: "\eb15"
        }

        ln ln-icon-Old-Clock:before {
            content: "\eb16"
        }

        ln ln-icon-Old-Radio:before {
            content: "\eb17"
        }

        ln ln-icon-Old-Sticky:before {
            content: "\eb18"
        }

        ln ln-icon-Old-Sticky2:before {
            content: "\eb19"
        }

        ln ln-icon-Old-Telephone:before {
            content: "\eb1a"
        }

        ln ln-icon-Old-TV:before {
            content: "\eb1b"
        }

        ln ln-icon-On-Air:before {
            content: "\eb1c"
        }

        ln ln-icon-On-Off-2:before {
            content: "\eb1d"
        }

        ln ln-icon-On-Off-3:before {
            content: "\eb1e"
        }

        ln ln-icon-On-off:before {
            content: "\eb1f"
        }

        ln ln-icon-One-Finger:before {
            content: "\eb20"
        }

        ln ln-icon-One-FingerTouch:before {
            content: "\eb21"
        }

        ln ln-icon-One-Window:before {
            content: "\eb22"
        }

        ln ln-icon-Open-Banana:before {
            content: "\eb23"
        }

        ln ln-icon-Open-Book:before {
            content: "\eb24"
        }

        ln ln-icon-Opera-House:before {
            content: "\eb25"
        }

        ln ln-icon-Opera:before {
            content: "\eb26"
        }

        ln ln-icon-Optimization:before {
            content: "\eb27"
        }

        ln ln-icon-Orientation-2:before {
            content: "\eb28"
        }

        ln ln-icon-Orientation-3:before {
            content: "\eb29"
        }

        ln ln-icon-Orientation:before {
            content: "\eb2a"
        }

        ln ln-icon-Orkut:before {
            content: "\eb2b"
        }

        ln ln-icon-Ornament:before {
            content: "\eb2c"
        }

        ln ln-icon-Over-Time:before {
            content: "\eb2d"
        }

        ln ln-icon-Over-Time2:before {
            content: "\eb2e"
        }

        ln ln-icon-Owl:before {
            content: "\eb2f"
        }

        ln ln-icon-Pac-Man:before {
            content: "\eb30"
        }

        ln ln-icon-Paint-Brush:before {
            content: "\eb31"
        }

        ln ln-icon-Paint-Bucket:before {
            content: "\eb32"
        }

        ln ln-icon-Paintbrush:before {
            content: "\eb33"
        }

        ln ln-icon-Palette:before {
            content: "\eb34"
        }

        ln ln-icon-Palm-Tree:before {
            content: "\eb35"
        }

        ln ln-icon-Panda:before {
            content: "\eb36"
        }

        ln ln-icon-Panorama:before {
            content: "\eb37"
        }

        ln ln-icon-Pantheon:before {
            content: "\eb38"
        }

        ln ln-icon-Pantone:before {
            content: "\eb39"
        }

        ln ln-icon-Pants:before {
            content: "\eb3a"
        }

        ln ln-icon-Paper-Plane:before {
            content: "\eb3b"
        }

        ln ln-icon-Paper:before {
            content: "\eb3c"
        }

        ln ln-icon-Parasailing:before {
            content: "\eb3d"
        }

        ln ln-icon-Parrot:before {
            content: "\eb3e"
        }

        ln ln-icon-Password-2shopping:before {
            content: "\eb3f"
        }

        ln ln-icon-Password-Field:before {
            content: "\eb40"
        }

        ln ln-icon-Password-shopping:before {
            content: "\eb41"
        }

        ln ln-icon-Password:before {
            content: "\eb42"
        }

        ln ln-icon-pause-2:before {
            content: "\eb43"
        }

        ln ln-icon-Pause:before {
            content: "\eb44"
        }

        ln ln-icon-Paw:before {
            content: "\eb45"
        }

        ln ln-icon-Pawn:before {
            content: "\eb46"
        }

        ln ln-icon-Paypal:before {
            content: "\eb47"
        }

        ln ln-icon-Pen-2:before {
            content: "\eb48"
        }

        ln ln-icon-Pen-3:before {
            content: "\eb49"
        }

        ln ln-icon-Pen-4:before {
            content: "\eb4a"
        }

        ln ln-icon-Pen-5:before {
            content: "\eb4b"
        }

        ln ln-icon-Pen-6:before {
            content: "\eb4c"
        }

        ln ln-icon-Pen:before {
            content: "\eb4d"
        }



        ln ln-icon-Pencil:before {
            content: "\eb4f"
        }

        ln ln-icon-Penguin:before {
            content: "\eb50"
        }

        ln ln-icon-Pentagon:before {
            content: "\eb51"
        }

        ln ln-icon-People-onCloud:before {
            content: "\eb52"
        }

        ln ln-icon-Pepper-withFire:before {
            content: "\eb53"
        }

        ln ln-icon-Pepper:before {
            content: "\eb54"
        }

        ln ln-icon-Petrol:before {
            content: "\eb55"
        }

        ln ln-icon-Petronas-Tower:before {
            content: "\eb56"
        }

        ln ln-icon-Philipines:before {
            content: "\eb57"
        }

        ln ln-icon-Phone-2:before {
            content: "\eb58"
        }

        ln ln-icon-Phone-3:before {
            content: "\eb59"
        }

        ln ln-icon-Phone-3G:before {
            content: "\eb5a"
        }

        ln ln-icon-Phone-4G:before {
            content: "\eb5b"
        }

        ln ln-icon-Phone-Simcard:before {
            content: "\eb5c"
        }

        ln ln-icon-Phone-SMS:before {
            content: "\eb5d"
        }

        ln ln-icon-Phone-Wifi:before {
            content: "\eb5e"
        }

        ln ln-icon-Phone:before {
            content: "\eb5f"
        }

        ln ln-icon-Photo-2:before {
            content: "\eb60"
        }

        ln ln-icon-Photo-3:before {
            content: "\eb61"
        }

        ln ln-icon-Photo-Album:before {
            content: "\eb62"
        }

        ln ln-icon-Photo-Album2:before {
            content: "\eb63"
        }

        ln ln-icon-Photo-Album3:before {
            content: "\eb64"
        }

        ln ln-icon-Photo:before {
            content: "\eb65"
        }

        ln ln-icon-Photos:before {
            content: "\eb66"
        }

        ln ln-icon-Physics:before {
            content: "\eb67"
        }

        ln ln-icon-Pi:before {
            content: "\eb68"
        }

        ln ln-icon-Piano:before {
            content: "\eb69"
        }

        ln ln-icon-Picasa:before {
            content: "\eb6a"
        }

        ln ln-icon-Pie-Chart:before {
            content: "\eb6b"
        }

        ln ln-icon-Pie-Chart2:before {
            content: "\eb6c"
        }

        ln ln-icon-Pie-Chart3:before {
            content: "\eb6d"
        }

        ln ln-icon-Pilates-2:before {
            content: "\eb6e"
        }

        ln ln-icon-Pilates-3:before {
            content: "\eb6f"
        }

        ln ln-icon-Pilates:before {
            content: "\eb70"
        }

        ln ln-icon-Pilot:before {
            content: "\eb71"
        }

        ln ln-icon-Pinch:before {
            content: "\eb72"
        }

        ln ln-icon-Ping-Pong:before {
            content: "\eb73"
        }

        ln ln-icon-Pinterest:before {
            content: "\eb74"
        }

        ln ln-icon-Pipe:before {
            content: "\eb75"
        }

        ln ln-icon-Pipette:before {
            content: "\eb76"
        }

        ln ln-icon-Piramids:before {
            content: "\eb77"
        }

        ln ln-icon-Pisces-2:before {
            content: "\eb78"
        }

        ln ln-icon-Pisces:before {
            content: "\eb79"
        }

        ln ln-icon-Pizza-Slice:before {
            content: "\eb7a"
        }

        ln ln-icon-Pizza:before {
            content: "\eb7b"
        }

        ln ln-icon-Plane-2:before {
            content: "\eb7c"
        }



        ln ln-icon-Plant:before {
            content: "\eb7e"
        }

        ln ln-icon-Plasmid:before {
            content: "\eb7f"
        }

        ln ln-icon-Plaster:before {
            content: "\eb80"
        }

        ln ln-icon-Plastic-CupPhone:before {
            content: "\eb81"
        }

        ln ln-icon-Plastic-CupPhone2:before {
            content: "\eb82"
        }

        ln ln-icon-Plate:before {
            content: "\eb83"
        }

        ln ln-icon-Plates:before {
            content: "\eb84"
        }

        ln ln-icon-Plaxo:before {
            content: "\eb85"
        }

        ln ln-icon-Play-Music:before {
            content: "\eb86"
        }

        ln ln-icon-Plug-In:before {
            content: "\eb87"
        }

        ln ln-icon-Plug-In2:before {
            content: "\eb88"
        }

        ln ln-icon-Plurk:before {
            content: "\eb89"
        }

        ln ln-icon-Pointer:before {
            content: "\eb8a"
        }

        ln ln-icon-Poland:before {
            content: "\eb8b"
        }

        ln ln-icon-Police-Man:before {
            content: "\eb8c"
        }

        ln ln-icon-Police-Station:before {
            content: "\eb8d"
        }

        ln ln-icon-Police-Woman:before {
            content: "\eb8e"
        }

        ln ln-icon-Police:before {
            content: "\eb8f"
        }

        ln ln-icon-Polo-Shirt:before {
            content: "\eb90"
        }

        ln ln-icon-Portrait:before {
            content: "\eb91"
        }

        ln ln-icon-Portugal:before {
            content: "\eb92"
        }

        ln ln-icon-Post-Mail:before {
            content: "\eb93"
        }

        ln ln-icon-Post-Mail2:before {
            content: "\eb94"
        }

        ln ln-icon-Post-Office:before {
            content: "\eb95"
        }

        ln ln-icon-Post-Sign:before {
            content: "\eb96"
        }

        ln ln-icon-Post-Sign2ways:before {
            content: "\eb97"
        }

        ln ln-icon-Posterous:before {
            content: "\eb98"
        }

        ln ln-icon-Pound-Sign:before {
            content: "\eb99"
        }

        ln ln-icon-Pound-Sign2:before {
            content: "\eb9a"
        }

        ln ln-icon-Pound:before {
            content: "\eb9b"
        }

        ln ln-icon-Power-2:before {
            content: "\eb9c"
        }

        ln ln-icon-Power-3:before {
            content: "\eb9d"
        }

        ln ln-icon-Power-Cable:before {
            content: "\eb9e"
        }

        ln ln-icon-Power-Station:before {
            content: "\eb9f"
        }

        ln ln-icon-Power:before {
            content: "\eba0"
        }

        ln ln-icon-Prater:before {
            content: "\eba1"
        }

        ln ln-icon-Present:before {
            content: "\eba2"
        }

        ln ln-icon-Presents:before {
            content: "\eba3"
        }

        ln ln-icon-Press:before {
            content: "\eba4"
        }

        ln ln-icon-Preview:before {
            content: "\eba5"
        }

        ln ln-icon-Previous:before {
            content: "\eba6"
        }

        ln ln-icon-Pricing:before {
            content: "\eba7"
        }

        ln ln-icon-Printer:before {
            content: "\eba8"
        }

        ln ln-icon-Professor:before {
            content: "\eba9"
        }

        ln ln-icon-Profile:before {
            content: "\ebaa"
        }

        ln ln-icon-Project:before {
            content: "\ebab"
        }

        ln ln-icon-Projector-2:before {
            content: "\ebac"
        }

        ln ln-icon-Projector:before {
            content: "\ebad"
        }

        ln ln-icon-Pulse:before {
            content: "\ebae"
        }

        ln ln-icon-Pumpkin:before {
            content: "\ebaf"
        }

        ln ln-icon-Punk:before {
            content: "\ebb0"
        }

        ln ln-icon-Punker:before {
            content: "\ebb1"
        }

        ln ln-icon-Puzzle:before {
            content: "\ebb2"
        }

        ln ln-icon-QIK:before {
            content: "\ebb3"
        }

        ln ln-icon-QR-Code:before {
            content: "\ebb4"
        }

        ln ln-icon-Queen-2:before {
            content: "\ebb5"
        }

        ln ln-icon-Queen:before {
            content: "\ebb6"
        }

        ln ln-icon-Quill-2:before {
            content: "\ebb7"
        }

        ln ln-icon-Quill-3:before {
            content: "\ebb8"
        }

        ln ln-icon-Quill:before {
            content: "\ebb9"
        }

        ln ln-icon-Quotes-2:before {
            content: "\ebba"
        }

        ln ln-icon-Quotes:before {
            content: "\ebbb"
        }

        ln ln-icon-Radio:before {
            content: "\ebbc"
        }

        ln ln-icon-Radioactive:before {
            content: "\ebbd"
        }

        ln ln-icon-Rafting:before {
            content: "\ebbe"
        }

        ln ln-icon-Rain-Drop:before {
            content: "\ebbf"
        }

        ln ln-icon-Rainbow-2:before {
            content: "\ebc0"
        }

        ln ln-icon-Rainbow:before {
            content: "\ebc1"
        }

        ln ln-icon-Ram:before {
            content: "\ebc2"
        }

        ln ln-icon-Razzor-Blade:before {
            content: "\ebc3"
        }

        ln ln-icon-Receipt-2:before {
            content: "\ebc4"
        }

        ln ln-icon-Receipt-3:before {
            content: "\ebc5"
        }

        ln ln-icon-Receipt-4:before {
            content: "\ebc6"
        }

        ln ln-icon-Receipt:before {
            content: "\ebc7"
        }

        ln ln-icon-Record2:before {
            content: "\ebc8"
        }

        ln ln-icon-Record-3:before {
            content: "\ebc9"
        }

        ln ln-icon-Record-Music:before {
            content: "\ebca"
        }

        ln ln-icon-Record:before {
            content: "\ebcb"
        }

        ln ln-icon-Recycling-2:before {
            content: "\ebcc"
        }

        ln ln-icon-Recycling:before {
            content: "\ebcd"
        }

        ln ln-icon-Reddit:before {
            content: "\ebce"
        }

        ln ln-icon-Redhat:before {
            content: "\ebcf"
        }

        ln ln-icon-Redirect:before {
            content: "\ebd0"
        }

        ln ln-icon-Redo:before {
            content: "\ebd1"
        }

        ln ln-icon-Reel:before {
            content: "\ebd2"
        }

        ln ln-icon-Refinery:before {
            content: "\ebd3"
        }

        ln ln-icon-Refresh-Window:before {
            content: "\ebd4"
        }

        ln ln-icon-Refresh:before {
            content: "\ebd5"
        }

        ln ln-icon-Reload-2:before {
            content: "\ebd6"
        }

        ln ln-icon-Reload-3:before {
            content: "\ebd7"
        }

        ln ln-icon-Reload:before {
            content: "\ebd8"
        }

        ln ln-icon-Remote-Controll:before {
            content: "\ebd9"
        }

        ln ln-icon-Remote-Controll2:before {
            content: "\ebda"
        }

        ln ln-icon-Remove-Bag:before {
            content: "\ebdb"
        }

        ln ln-icon-Remove-Basket:before {
            content: "\ebdc"
        }

        ln ln-icon-Remove-Cart:before {
            content: "\ebdd"
        }

        ln ln-icon-Remove-File:before {
            content: "\ebde"
        }

        ln ln-icon-Remove-User:before {
            content: "\ebdf"
        }

        ln ln-icon-Remove-Window:before {
            content: "\ebe0"
        }

        ln ln-icon-Remove:before {
            content: "\ebe1"
        }

        ln ln-icon-Rename:before {
            content: "\ebe2"
        }

        ln ln-icon-Repair:before {
            content: "\ebe3"
        }

        ln ln-icon-Repeat-2:before {
            content: "\ebe4"
        }

        ln ln-icon-Repeat-3:before {
            content: "\ebe5"
        }

        ln ln-icon-Repeat-4:before {
            content: "\ebe6"
        }

        ln ln-icon-Repeat-5:before {
            content: "\ebe7"
        }

        ln ln-icon-Repeat-6:before {
            content: "\ebe8"
        }

        ln ln-icon-Repeat-7:before {
            content: "\ebe9"
        }

        ln ln-icon-Repeat:before {
            content: "\ebea"
        }

        ln ln-icon-Reset:before {
            content: "\ebeb"
        }

        ln ln-icon-Resize:before {
            content: "\ebec"
        }

        ln ln-icon-Restore-Window:before {
            content: "\ebed"
        }

        ln ln-icon-Retouching:before {
            content: "\ebee"
        }

        ln ln-icon-Retro-Camera:before {
            content: "\ebef"
        }

        ln ln-icon-Retro:before {
            content: "\ebf0"
        }

        ln ln-icon-Retweet:before {
            content: "\ebf1"
        }

        ln ln-icon-Reverbnation:before {
            content: "\ebf2"
        }

        ln ln-icon-Rewind:before {
            content: "\ebf3"
        }

        ln ln-icon-RGB:before {
            content: "\ebf4"
        }

        ln ln-icon-Ribbon-2:before {
            content: "\ebf5"
        }

        ln ln-icon-Ribbon-3:before {
            content: "\ebf6"
        }

        ln ln-icon-Ribbon:before {
            content: "\ebf7"
        }

        ln ln-icon-Right-2:before {
            content: "\ebf8"
        }

        ln ln-icon-Right-3:before {
            content: "\ebf9"
        }

        ln ln-icon-Right-4:before {
            content: "\ebfa"
        }

        ln ln-icon-Right-ToLeft:before {
            content: "\ebfb"
        }

        ln ln-icon-Right:before {
            content: "\ebfc"
        }

        ln ln-icon-Road-2:before {
            content: "\ebfd"
        }

        ln ln-icon-Road-3:before {
            content: "\ebfe"
        }

        ln ln-icon-Road:before {
            content: "\ebff"
        }

        ln ln-icon-Robot-2:before {
            content: "\ec00"
        }

        ln ln-icon-Robot:before {
            content: "\ec01"
        }

        ln ln-icon-Rock-andRoll:before {
            content: "\ec02"
        }

        ln ln-icon-Rocket:before {
            content: "\ec03"
        }

        ln ln-icon-Roller:before {
            content: "\ec04"
        }

        ln ln-icon-Roof:before {
            content: "\ec05"
        }

        ln ln-icon-Rook:before {
            content: "\ec06"
        }

        ln ln-icon-Rotate-Gesture:before {
            content: "\ec07"
        }

        ln ln-icon-Rotate-Gesture2:before {
            content: "\ec08"
        }

        ln ln-icon-Rotate-Gesture3:before {
            content: "\ec09"
        }

        ln ln-icon-Rotation-390:before {
            content: "\ec0a"
        }

        ln ln-icon-Rotation:before {
            content: "\ec0b"
        }

        ln ln-icon-Router-2:before {
            content: "\ec0c"
        }

        ln ln-icon-Router:before {
            content: "\ec0d"
        }

        ln ln-icon-RSS:before {
            content: "\ec0e"
        }

        ln ln-icon-Ruler-2:before {
            content: "\ec0f"
        }

        ln ln-icon-Ruler:before {
            content: "\ec10"
        }

        ln ln-icon-Running-Shoes:before {
            content: "\ec11"
        }

        ln ln-icon-Running:before {
            content: "\ec12"
        }

        ln ln-icon-Safari:before {
            content: "\ec13"
        }

        ln ln-icon-Safe-Box:before {
            content: "\ec14"
        }

        ln ln-icon-Safe-Box2:before {
            content: "\ec15"
        }

        ln ln-icon-Safety-PinClose:before {
            content: "\ec16"
        }

        ln ln-icon-Safety-PinOpen:before {
            content: "\ec17"
        }

        ln ln-icon-Sagittarus-2:before {
            content: "\ec18"
        }

        ln ln-icon-Sagittarus:before {
            content: "\ec19"
        }

        ln ln-icon-Sailing-Ship:before {
            content: "\ec1a"
        }

        ln ln-icon-Sand-watch:before {
            content: "\ec1b"
        }

        ln ln-icon-Sand-watch2:before {
            content: "\ec1c"
        }

        ln ln-icon-Santa-Claus:before {
            content: "\ec1d"
        }

        ln ln-icon-Santa-Claus2:before {
            content: "\ec1e"
        }

        ln ln-icon-Santa-onSled:before {
            content: "\ec1f"
        }

        ln ln-icon-Satelite-2:before {
            content: "\ec20"
        }

        ln ln-icon-Satelite:before {
            content: "\ec21"
        }

        ln ln-icon-Save-Window:before {
            content: "\ec22"
        }

        ln ln-icon-Save:before {
            content: "\ec23"
        }

        ln ln-icon-Saw:before {
            content: "\ec24"
        }

        ln ln-icon-Saxophone:before {
            content: "\ec25"
        }

        ln ln-icon-Scale:before {
            content: "\ec26"
        }

        ln ln-icon-Scarf:before {
            content: "\ec27"
        }

        ln ln-icon-Scissor:before {
            content: "\ec28"
        }

        ln ln-icon-Scooter-Front:before {
            content: "\ec29"
        }

        ln ln-icon-Scooter:before {
            content: "\ec2a"
        }

        ln ln-icon-Scorpio-2:before {
            content: "\ec2b"
        }

        ln ln-icon-Scorpio:before {
            content: "\ec2c"
        }

        ln ln-icon-Scotland:before {
            content: "\ec2d"
        }

        ln ln-icon-Screwdriver:before {
            content: "\ec2e"
        }

        ln ln-icon-Scroll-Fast:before {
            content: "\ec2f"
        }

        ln ln-icon-Scroll:before {
            content: "\ec30"
        }

        ln ln-icon-Scroller-2:before {
            content: "\ec31"
        }

        ln ln-icon-Scroller:before {
            content: "\ec32"
        }

        ln ln-icon-Sea-Dog:before {
            content: "\ec33"
        }

        ln ln-icon-Search-onCloud:before {
            content: "\ec34"
        }

        ln ln-icon-Search-People:before {
            content: "\ec35"
        }

        ln ln-icon-secound:before {
            content: "\ec36"
        }

        ln ln-icon-secound2:before {
            content: "\ec37"
        }

        ln ln-icon-Security-Block:before {
            content: "\ec38"
        }

        ln ln-icon-Security-Bug:before {
            content: "\ec39"
        }

        ln ln-icon-Security-Camera:before {
            content: "\ec3a"
        }

        ln ln-icon-Security-Check:before {
            content: "\ec3b"
        }

        ln ln-icon-Security-Settings:before {
            content: "\ec3c"
        }

        ln ln-icon-Security-Smiley:before {
            content: "\ec3d"
        }

        ln ln-icon-Securiy-Remove:before {
            content: "\ec3e"
        }

        ln ln-icon-Seed:before {
            content: "\ec3f"
        }

        ln ln-icon-Selfie:before {
            content: "\ec40"
        }

        ln ln-icon-Serbia:before {
            content: "\ec41"
        }

        ln ln-icon-Server-2:before {
            content: "\ec42"
        }

        ln ln-icon-Server:before {
            content: "\ec43"
        }

        ln ln-icon-Servers:before {
            content: "\ec44"
        }

        ln ln-icon-Settings-Window:before {
            content: "\ec45"
        }

        ln ln-icon-Sewing-Machine:before {
            content: "\ec46"
        }

        ln ln-icon-Sexual:before {
            content: "\ec47"
        }

        ln ln-icon-Share-onCloud:before {
            content: "\ec48"
        }

        ln ln-icon-Share-Window:before {
            content: "\ec49"
        }

        ln ln-icon-Share:before {
            content: "\ec4a"
        }

        ln ln-icon-Sharethis:before {
            content: "\ec4b"
        }

        ln ln-icon-Shark:before {
            content: "\ec4c"
        }

        ln ln-icon-Sheep:before {
            content: "\ec4d"
        }

        ln ln-icon-Sheriff-Badge:before {
            content: "\ec4e"
        }

        ln ln-icon-Shield:before {
            content: "\ec4f"
        }

        ln ln-icon-Ship-2:before {
            content: "\ec50"
        }

        ln ln-icon-Ship:before {
            content: "\ec51"
        }

        ln ln-icon-Shirt:before {
            content: "\ec52"
        }

        ln ln-icon-Shoes-2:before {
            content: "\ec53"
        }

        ln ln-icon-Shoes-3:before {
            content: "\ec54"
        }

        ln ln-icon-Shoes:before {
            content: "\ec55"
        }

        ln ln-icon-Shop-2:before {
            content: "\ec56"
        }

        ln ln-icon-Shop-3:before {
            content: "\ec57"
        }

        ln ln-icon-Shop-4:before {
            content: "\ec58"
        }

        ln ln-icon-Shop:before {
            content: "\ec59"
        }

        ln ln-icon-Shopping-Bag:before {
            content: "\ec5a"
        }

        ln ln-icon-Shopping-Basket:before {
            content: "\ec5b"
        }

        ln ln-icon-Shopping-Cart:before {
            content: "\ec5c"
        }

        ln ln-icon-Short-Pants:before {
            content: "\ec5d"
        }

        ln ln-icon-Shoutwire:before {
            content: "\ec5e"
        }

        ln ln-icon-Shovel:before {
            content: "\ec5f"
        }

        ln ln-icon-Shuffle-2:before {
            content: "\ec60"
        }

        ln ln-icon-Shuffle-3:before {
            content: "\ec61"
        }

        ln ln-icon-Shuffle-4:before {
            content: "\ec62"
        }

        ln ln-icon-Shuffle:before {
            content: "\ec63"
        }

        ln ln-icon-Shutter:before {
            content: "\ec64"
        }

        ln ln-icon-Sidebar-Window:before {
            content: "\ec65"
        }

        ln ln-icon-Signal:before {
            content: "\ec66"
        }

        ln ln-icon-Singapore:before {
            content: "\ec67"
        }

        ln ln-icon-Skate-Shoes:before {
            content: "\ec68"
        }

        ln ln-icon-Skateboard-2:before {
            content: "\ec69"
        }

        ln ln-icon-Skateboard:before {
            content: "\ec6a"
        }

        ln ln-icon-Skeleton:before {
            content: "\ec6b"
        }

        ln ln-icon-Ski:before {
            content: "\ec6c"
        }

        ln ln-icon-Skirt:before {
            content: "\ec6d"
        }

        ln ln-icon-Skrill:before {
            content: "\ec6e"
        }

        ln ln-icon-Skull:before {
            content: "\ec6f"
        }

        ln ln-icon-Skydiving:before {
            content: "\ec70"
        }

        ln ln-icon-Skype:before {
            content: "\ec71"
        }

        ln ln-icon-Sled-withGifts:before {
            content: "\ec72"
        }

        ln ln-icon-Sled:before {
            content: "\ec73"
        }

        ln ln-icon-Sleeping:before {
            content: "\ec74"
        }

        ln ln-icon-Sleet:before {
            content: "\ec75"
        }

        ln ln-icon-Slippers:before {
            content: "\ec76"
        }

        ln ln-icon-Smart:before {
            content: "\ec77"
        }

        ln ln-icon-Smartphone-2:before {
            content: "\ec78"
        }

        ln ln-icon-Smartphone-3:before {
            content: "\ec79"
        }

        ln ln-icon-Smartphone-4:before {
            content: "\ec7a"
        }

        ln ln-icon-Smartphone-Secure:before {
            content: "\ec7b"
        }

        ln ln-icon-Smartphone:before {
            content: "\ec7c"
        }

        ln ln-icon-Smile:before {
            content: "\ec7d"
        }

        ln ln-icon-Smoking-Area:before {
            content: "\ec7e"
        }

        ln ln-icon-Smoking-Pipe:before {
            content: "\ec7f"
        }

        ln ln-icon-Snake:before {
            content: "\ec80"
        }

        ln ln-icon-Snorkel:before {
            content: "\ec81"
        }

        ln ln-icon-Snow-2:before {
            content: "\ec82"
        }

        ln ln-icon-Snow-Dome:before {
            content: "\ec83"
        }

        ln ln-icon-Snow-Storm:before {
            content: "\ec84"
        }

        ln ln-icon-Snow:before {
            content: "\ec85"
        }

        ln ln-icon-Snowflake-2:before {
            content: "\ec86"
        }

        ln ln-icon-Snowflake-3:before {
            content: "\ec87"
        }

        ln ln-icon-Snowflake-4:before {
            content: "\ec88"
        }

        ln ln-icon-Snowflake:before {
            content: "\ec89"
        }

        ln ln-icon-Snowman:before {
            content: "\ec8a"
        }

        ln ln-icon-Soccer-Ball:before {
            content: "\ec8b"
        }

        ln ln-icon-Soccer-Shoes:before {
            content: "\ec8c"
        }

        ln ln-icon-Socks:before {
            content: "\ec8d"
        }

        ln ln-icon-Solar:before {
            content: "\ec8e"
        }

        ln ln-icon-Sound-Wave:before {
            content: "\ec8f"
        }

        ln ln-icon-Sound:before {
            content: "\ec90"
        }

        ln ln-icon-Soundcloud:before {
            content: "\ec91"
        }

        ln ln-icon-Soup:before {
            content: "\ec92"
        }

        ln ln-icon-South-Africa:before {
            content: "\ec93"
        }

        ln ln-icon-Space-Needle:before {
            content: "\ec94"
        }

        ln ln-icon-Spain:before {
            content: "\ec95"
        }

        ln ln-icon-Spam-Mail:before {
            content: "\ec96"
        }

        ln ln-icon-Speach-Bubble:before {
            content: "\ec97"
        }

        ln ln-icon-Speach-Bubble2:before {
            content: "\ec98"
        }

        ln ln-icon-Speach-Bubble3:before {
            content: "\ec99"
        }

        ln ln-icon-Speach-Bubble4:before {
            content: "\ec9a"
        }

        ln ln-icon-Speach-Bubble5:before {
            content: "\ec9b"
        }

        ln ln-icon-Speach-Bubble6:before {
            content: "\ec9c"
        }

        ln ln-icon-Speach-Bubble7:before {
            content: "\ec9d"
        }

        ln ln-icon-Speach-Bubble8:before {
            content: "\ec9e"
        }

        ln ln-icon-Speach-Bubble9:before {
            content: "\ec9f"
        }

        ln ln-icon-Speach-Bubble10:before {
            content: "\eca0"
        }

        ln ln-icon-Speach-Bubble11:before {
            content: "\eca1"
        }

        ln ln-icon-Speach-Bubble12:before {
            content: "\eca2"
        }

        ln ln-icon-Speach-Bubble13:before {
            content: "\eca3"
        }

        ln ln-icon-Speach-BubbleAsking:before {
            content: "\eca4"
        }

        ln ln-icon-Speach-BubbleComic:before {
            content: "\eca5"
        }

        ln ln-icon-Speach-BubbleComic2:before {
            content: "\eca6"
        }

        ln ln-icon-Speach-BubbleComic3:before {
            content: "\eca7"
        }

        ln ln-icon-Speach-BubbleComic4:before {
            content: "\eca8"
        }

        ln ln-icon-Speach-BubbleDialog:before {
            content: "\eca9"
        }

        ln ln-icon-Speach-Bubbles:before {
            content: "\ecaa"
        }

        ln ln-icon-Speak-2:before {
            content: "\ecab"
        }

        ln ln-icon-Speak:before {
            content: "\ecac"
        }

        ln ln-icon-Speaker-2:before {
            content: "\ecad"
        }

        ln ln-icon-Speaker:before {
            content: "\ecae"
        }

        ln ln-icon-Spell-Check:before {
            content: "\ecaf"
        }

        ln ln-icon-Spell-CheckABC:before {
            content: "\ecb0"
        }

        ln ln-icon-Spermium:before {
            content: "\ecb1"
        }

        ln ln-icon-Spider:before {
            content: "\ecb2"
        }

        ln ln-icon-Spiderweb:before {
            content: "\ecb3"
        }

        ln ln-icon-Split-FourSquareWindow:before {
            content: "\ecb4"
        }

        ln ln-icon-Split-Horizontal:before {
            content: "\ecb5"
        }

        ln ln-icon-Split-Horizontal2Window:before {
            content: "\ecb6"
        }

        ln ln-icon-Split-Vertical:before {
            content: "\ecb7"
        }

        ln ln-icon-Split-Vertical2:before {
            content: "\ecb8"
        }

        ln ln-icon-Split-Window:before {
            content: "\ecb9"
        }

        ln ln-icon-Spoder:before {
            content: "\ecba"
        }

        ln ln-icon-Spoon:before {
            content: "\ecbb"
        }

        ln ln-icon-Sport-Mode:before {
            content: "\ecbc"
        }

        ln ln-icon-Sports-Clothings1:before {
            content: "\ecbd"
        }

        ln ln-icon-Sports-Clothings2:before {
            content: "\ecbe"
        }

        ln ln-icon-Sports-Shirt:before {
            content: "\ecbf"
        }

        ln ln-icon-Spot:before {
            content: "\ecc0"
        }

        ln ln-icon-Spray:before {
            content: "\ecc1"
        }

        ln ln-icon-Spread:before {
            content: "\ecc2"
        }

        ln ln-icon-Spring:before {
            content: "\ecc3"
        }

        ln ln-icon-Spurl:before {
            content: "\ecc4"
        }

        ln ln-icon-Spy:before {
            content: "\ecc5"
        }

        ln ln-icon-Squirrel:before {
            content: "\ecc6"
        }

        ln ln-icon-SSL:before {
            content: "\ecc7"
        }

        ln ln-icon-St-BasilsCathedral:before {
            content: "\ecc8"
        }

        ln ln-icon-St-PaulsCathedral:before {
            content: "\ecc9"
        }

        ln ln-icon-Stamp-2:before {
            content: "\ecca"
        }

        ln ln-icon-Stamp:before {
            content: "\eccb"
        }

        ln ln-icon-Stapler:before {
            content: "\eccc"
        }

        ln ln-icon-Star-Track:before {
            content: "\eccd"
        }

        ln ln-icon-Star:before {
            content: "\ecce"
        }

        ln ln-icon-Starfish:before {
            content: "\eccf"
        }

        ln ln-icon-Start2:before {
            content: "\ecd0"
        }

        ln ln-icon-Start-3:before {
            content: "\ecd1"
        }

        ln ln-icon-Start-ways:before {
            content: "\ecd2"
        }

        ln ln-icon-Start:before {
            content: "\ecd3"
        }

        ln ln-icon-Statistic:before {
            content: "\ecd4"
        }

        ln ln-icon-Stethoscope:before {
            content: "\ecd5"
        }

        ln ln-icon-stop--2:before {
            content: "\ecd6"
        }

        ln ln-icon-Stop-Music:before {
            content: "\ecd7"
        }

        ln ln-icon-Stop:before {
            content: "\ecd8"
        }

        ln ln-icon-Stopwatch-2:before {
            content: "\ecd9"
        }

        ln ln-icon-Stopwatch:before {
            content: "\ecda"
        }

        ln ln-icon-Storm:before {
            content: "\ecdb"
        }

        ln ln-icon-Street-View:before {
            content: "\ecdc"
        }

        ln ln-icon-Street-View2:before {
            content: "\ecdd"
        }

        ln ln-icon-Strikethrough-Text:before {
            content: "\ecde"
        }

        ln ln-icon-Stroller:before {
            content: "\ecdf"
        }

        ln ln-icon-Structure:before {
            content: "\ece0"
        }

        ln ln-icon-Student-Female:before {
            content: "\ece1"
        }

        ln ln-icon-Student-Hat:before {
            content: "\ece2"
        }

        ln ln-icon-Student-Hat2:before {
            content: "\ece3"
        }

        ln ln-icon-Student-Male:before {
            content: "\ece4"
        }

        ln ln-icon-Student-MaleFemale:before {
            content: "\ece5"
        }

        ln ln-icon-Students:before {
            content: "\ece6"
        }

        ln ln-icon-Studio-Flash:before {
            content: "\ece7"
        }

        ln ln-icon-Studio-Lightbox:before {
            content: "\ece8"
        }

        ln ln-icon-Stumbleupon:before {
            content: "\ece9"
        }

        ln ln-icon-Suit:before {
            content: "\ecea"
        }

        ln ln-icon-Suitcase:before {
            content: "\eceb"
        }

        ln ln-icon-Sum-2:before {
            content: "\ecec"
        }

        ln ln-icon-Sum:before {
            content: "\eced"
        }

        ln ln-icon-Summer:before {
            content: "\ecee"
        }

        ln ln-icon-Sun-CloudyRain:before {
            content: "\ecef"
        }

        ln ln-icon-Sun:before {
            content: "\ecf0"
        }

        ln ln-icon-Sunglasses-2:before {
            content: "\ecf1"
        }

        ln ln-icon-Sunglasses-3:before {
            content: "\ecf2"
        }

        ln ln-icon-Sunglasses-Smiley:before {
            content: "\ecf3"
        }

        ln ln-icon-Sunglasses-Smiley2:before {
            content: "\ecf4"
        }

        ln ln-icon-Sunglasses-W:before {
            content: "\ecf5"
        }

        ln ln-icon-Sunglasses-W2:before {
            content: "\ecf6"
        }

        ln ln-icon-Sunglasses-W3:before {
            content: "\ecf7"
        }

        ln ln-icon-Sunglasses:before {
            content: "\ecf8"
        }

        ln ln-icon-Sunrise:before {
            content: "\ecf9"
        }

        ln ln-icon-Sunset:before {
            content: "\ecfa"
        }

        ln ln-icon-Superman:before {
            content: "\ecfb"
        }

        ln ln-icon-Support:before {
            content: "\ecfc"
        }

        ln ln-icon-Surprise:before {
            content: "\ecfd"
        }

        ln ln-icon-Sushi:before {
            content: "\ecfe"
        }

        ln ln-icon-Sweden:before {
            content: "\ecff"
        }

        ln ln-icon-Swimming-Short:before {
            content: "\ed00"
        }

        ln ln-icon-Swimming:before {
            content: "\ed01"
        }

        ln ln-icon-Swimmwear:before {
            content: "\ed02"
        }

        ln ln-icon-Switch:before {
            content: "\ed03"
        }

        ln ln-icon-Switzerland:before {
            content: "\ed04"
        }

        ln ln-icon-Sync-Cloud:before {
            content: "\ed05"
        }

        ln ln-icon-Sync:before {
            content: "\ed06"
        }

        ln ln-icon-Synchronize-2:before {
            content: "\ed07"
        }

        ln ln-icon-Synchronize:before {
            content: "\ed08"
        }

        ln ln-icon-T-Shirt:before {
            content: "\ed09"
        }

        ln ln-icon-Tablet-2:before {
            content: "\ed0a"
        }

        ln ln-icon-Tablet-3:before {
            content: "\ed0b"
        }

        ln ln-icon-Tablet-Orientation:before {
            content: "\ed0c"
        }

        ln ln-icon-Tablet-Phone:before {
            content: "\ed0d"
        }

        ln ln-icon-Tablet-Secure:before {
            content: "\ed0e"
        }

        ln ln-icon-Tablet-Vertical:before {
            content: "\ed0f"
        }

        ln ln-icon-Tablet:before {
            content: "\ed10"
        }

        ln ln-icon-Tactic:before {
            content: "\ed11"
        }

        ln ln-icon-Tag-2:before {
            content: "\ed12"
        }

        ln ln-icon-Tag-3:before {
            content: "\ed13"
        }

        ln ln-icon-Tag-4:before {
            content: "\ed14"
        }

        ln ln-icon-Tag-5:before {
            content: "\ed15"
        }

        ln ln-icon-Tag:before {
            content: "\ed16"
        }

        ln ln-icon-Taj-Mahal:before {
            content: "\ed17"
        }

        ln ln-icon-Talk-Man:before {
            content: "\ed18"
        }

        ln ln-icon-Tap:before {
            content: "\ed19"
        }



        ln ln-icon-Taurus-2:before {
            content: "\ed1c"
        }

        ln ln-icon-Taurus:before {
            content: "\ed1d"
        }

        ln ln-icon-Taxi-2:before {
            content: "\ed1e"
        }

        ln ln-icon-Taxi-Sign:before {
            content: "\ed1f"
        }

        ln ln-icon-Taxi:before {
            content: "\ed20"
        }

        ln ln-icon-Teacher:before {
            content: "\ed21"
        }

        ln ln-icon-Teapot:before {
            content: "\ed22"
        }

        ln ln-icon-Technorati:before {
            content: "\ed23"
        }

        ln ln-icon-Teddy-Bear:before {
            content: "\ed24"
        }

        ln ln-icon-Tee-Mug:before {
            content: "\ed25"
        }

        ln ln-icon-Telephone-2:before {
            content: "\ed26"
        }

        ln ln-icon-Telephone:before {
            content: "\ed27"
        }

        ln ln-icon-Telescope:before {
            content: "\ed28"
        }

        ln ln-icon-Temperature-2:before {
            content: "\ed29"
        }

        ln ln-icon-Temperature-3:before {
            content: "\ed2a"
        }

        ln ln-icon-Temperature:before {
            content: "\ed2b"
        }

        ln ln-icon-Temple:before {
            content: "\ed2c"
        }

        ln ln-icon-Tennis-Ball:before {
            content: "\ed2d"
        }

        ln ln-icon-Tennis:before {
            content: "\ed2e"
        }

        ln ln-icon-Tent:before {
            content: "\ed2f"
        }

        ln ln-icon-Test-Tube:before {
            content: "\ed30"
        }

        ln ln-icon-Test-Tube2:before {
            content: "\ed31"
        }

        ln ln-icon-Testimonal:before {
            content: "\ed32"
        }

        ln ln-icon-Text-Box:before {
            content: "\ed33"
        }

        ln ln-icon-Text-Effect:before {
            content: "\ed34"
        }

        ln ln-icon-Text-HighlightColor:before {
            content: "\ed35"
        }

        ln ln-icon-Text-Paragraph:before {
            content: "\ed36"
        }

        ln ln-icon-Thailand:before {
            content: "\ed37"
        }

        ln ln-icon-The-WhiteHouse:before {
            content: "\ed38"
        }

        ln ln-icon-This-SideUp:before {
            content: "\ed39"
        }

        ln ln-icon-Thread:before {
            content: "\ed3a"
        }

        ln ln-icon-Three-ArrowFork:before {
            content: "\ed3b"
        }

        ln ln-icon-Three-Fingers:before {
            content: "\ed3c"
        }

        ln ln-icon-Three-FingersDrag:before {
            content: "\ed3d"
        }

        ln ln-icon-Three-FingersDrag2:before {
            content: "\ed3e"
        }

        ln ln-icon-Three-FingersTouch:before {
            content: "\ed3f"
        }

        ln ln-icon-Thumb:before {
            content: "\ed40"
        }

        ln ln-icon-Thumbs-DownSmiley:before {
            content: "\ed41"
        }

        ln ln-icon-Thumbs-UpSmiley:before {
            content: "\ed42"
        }

        ln ln-icon-Thunder:before {
            content: "\ed43"
        }

        ln ln-icon-Thunderstorm:before {
            content: "\ed44"
        }

        ln ln-icon-Ticket:before {
            content: "\ed45"
        }

        ln ln-icon-Tie-2:before {
            content: "\ed46"
        }

        ln ln-icon-Tie-3:before {
            content: "\ed47"
        }

        ln ln-icon-Tie-4:before {
            content: "\ed48"
        }

        ln ln-icon-Tie:before {
            content: "\ed49"
        }

        ln ln-icon-Tiger:before {
            content: "\ed4a"
        }

        ln ln-icon-Time-Backup:before {
            content: "\ed4b"
        }

        ln ln-icon-Time-Bomb:before {
            content: "\ed4c"
        }

        ln ln-icon-Time-Clock:before {
            content: "\ed4d"
        }

        ln ln-icon-Time-Fire:before {
            content: "\ed4e"
        }

        ln ln-icon-Time-Machine:before {
            content: "\ed4f"
        }

        ln ln-icon-Time-Window:before {
            content: "\ed50"
        }

        ln ln-icon-Timer-2:before {
            content: "\ed51"
        }

        ln ln-icon-Timer:before {
            content: "\ed52"
        }

        ln ln-icon-To-Bottom:before {
            content: "\ed53"
        }

        ln ln-icon-To-Bottom2:before {
            content: "\ed54"
        }

        ln ln-icon-To-Left:before {
            content: "\ed55"
        }

        ln ln-icon-To-Right:before {
            content: "\ed56"
        }

        ln ln-icon-To-Top:before {
            content: "\ed57"
        }

        ln ln-icon-To-Top2:before {
            content: "\ed58"
        }

        ln ln-icon-Token-:before {
            content: "\ed59"
        }

        ln ln-icon-Tomato:before {
            content: "\ed5a"
        }

        ln ln-icon-Tongue:before {
            content: "\ed5b"
        }

        ln ln-icon-Tooth-2:before {
            content: "\ed5c"
        }

        ln ln-icon-Tooth:before {
            content: "\ed5d"
        }

        ln ln-icon-Top-ToBottom:before {
            content: "\ed5e"
        }

        ln ln-icon-Touch-Window:before {
            content: "\ed5f"
        }

        ln ln-icon-Tourch:before {
            content: "\ed60"
        }

        ln ln-icon-Tower-2:before {
            content: "\ed61"
        }

        ln ln-icon-Tower-Bridge:before {
            content: "\ed62"
        }

        ln ln-icon-Tower:before {
            content: "\ed63"
        }

        ln ln-icon-Trace:before {
            content: "\ed64"
        }

        ln ln-icon-Tractor:before {
            content: "\ed65"
        }

        ln ln-icon-traffic-Light:before {
            content: "\ed66"
        }

        ln ln-icon-Traffic-Light2:before {
            content: "\ed67"
        }

        ln ln-icon-Train-2:before {
            content: "\ed68"
        }

        ln ln-icon-Train:before {
            content: "\ed69"
        }

        ln ln-icon-Tram:before {
            content: "\ed6a"
        }

        ln ln-icon-Transform-2:before {
            content: "\ed6b"
        }

        ln ln-icon-Transform-3:before {
            content: "\ed6c"
        }

        ln ln-icon-Transform-4:before {
            content: "\ed6d"
        }

        ln ln-icon-Transform:before {
            content: "\ed6e"
        }

        ln ln-icon-Trash-withMen:before {
            content: "\ed6f"
        }

        ln ln-icon-Tree-2:before {
            content: "\ed70"
        }

        ln ln-icon-Tree-3:before {
            content: "\ed71"
        }

        ln ln-icon-Tree-4:before {
            content: "\ed72"
        }

        ln ln-icon-Tree-5:before {
            content: "\ed73"
        }

        ln ln-icon-Tree:before {
            content: "\ed74"
        }

        ln ln-icon-Trekking:before {
            content: "\ed75"
        }

        ln ln-icon-Triangle-ArrowDown:before {
            content: "\ed76"
        }

        ln ln-icon-Triangle-ArrowLeft:before {
            content: "\ed77"
        }

        ln ln-icon-Triangle-ArrowRight:before {
            content: "\ed78"
        }

        ln ln-icon-Triangle-ArrowUp:before {
            content: "\ed79"
        }

        ln ln-icon-Tripod-2:before {
            content: "\ed7a"
        }

        ln ln-icon-Tripod-andVideo:before {
            content: "\ed7b"
        }

        ln ln-icon-Tripod-withCamera:before {
            content: "\ed7c"
        }

        ln ln-icon-Tripod-withGopro:before {
            content: "\ed7d"
        }

        ln ln-icon-Trophy-2:before {
            content: "\ed7e"
        }

        ln ln-icon-Trophy:before {
            content: "\ed7f"
        }

        ln ln-icon-Truck:before {
            content: "\ed80"
        }

        ln ln-icon-Trumpet:before {
            content: "\ed81"
        }

        ln ln-icon-Tumblr:before {
            content: "\ed82"
        }

        ln ln-icon-Turkey:before {
            content: "\ed83"
        }

        ln ln-icon-Turn-Down:before {
            content: "\ed84"
        }

        ln ln-icon-Turn-Down2:before {
            content: "\ed85"
        }

        ln ln-icon-Turn-DownFromLeft:before {
            content: "\ed86"
        }

        ln ln-icon-Turn-DownFromRight:before {
            content: "\ed87"
        }

        ln ln-icon-Turn-Left:before {
            content: "\ed88"
        }

        ln ln-icon-Turn-Left3:before {
            content: "\ed89"
        }

        ln ln-icon-Turn-Right:before {
            content: "\ed8a"
        }

        ln ln-icon-Turn-Right3:before {
            content: "\ed8b"
        }

        ln ln-icon-Turn-Up:before {
            content: "\ed8c"
        }

        ln ln-icon-Turn-Up2:before {
            content: "\ed8d"
        }

        ln ln-icon-Turtle:before {
            content: "\ed8e"
        }

        ln ln-icon-Tuxedo:before {
            content: "\ed8f"
        }

        ln ln-icon-TV:before {
            content: "\ed90"
        }

        ln ln-icon-Twister:before {
            content: "\ed91"
        }

        ln ln-icon-Twitter-2:before {
            content: "\ed92"
        }

        ln ln-icon-Twitter:before {
            content: "\ed93"
        }

        ln ln-icon-Two-Fingers:before {
            content: "\ed94"
        }

        ln ln-icon-Two-FingersDrag:before {
            content: "\ed95"
        }

        ln ln-icon-Two-FingersDrag2:before {
            content: "\ed96"
        }

        ln ln-icon-Two-FingersScroll:before {
            content: "\ed97"
        }

        ln ln-icon-Two-FingersTouch:before {
            content: "\ed98"
        }

        ln ln-icon-Two-Windows:before {
            content: "\ed99"
        }

        ln ln-icon-Type-Pass:before {
            content: "\ed9a"
        }

        ln ln-icon-Ukraine:before {
            content: "\ed9b"
        }

        ln ln-icon-Umbrela:before {
            content: "\ed9c"
        }

        ln ln-icon-Umbrella-2:before {
            content: "\ed9d"
        }

        ln ln-icon-Umbrella-3:before {
            content: "\ed9e"
        }

        ln ln-icon-Under-LineText:before {
            content: "\ed9f"
        }

        ln ln-icon-Undo:before {
            content: "\eda0"
        }

        ln ln-icon-United-Kingdom:before {
            content: "\eda1"
        }

        ln ln-icon-United-States:before {
            content: "\eda2"
        }

        ln ln-icon-University-2:before {
            content: "\eda3"
        }

        ln ln-icon-University:before {
            content: "\eda4"
        }

        ln ln-icon-Unlike-2:before {
            content: "\eda5"
        }

        ln ln-icon-Unlike:before {
            content: "\eda6"
        }

        ln ln-icon-Unlock-2:before {
            content: "\eda7"
        }

        ln ln-icon-Unlock-3:before {
            content: "\eda8"
        }

        ln ln-icon-Unlock:before {
            content: "\eda9"
        }

        ln ln-icon-Up--Down:before {
            content: "\edaa"
        }

        ln ln-icon-Up--Down3:before {
            content: "\edab"
        }

        ln ln-icon-Up-2:before {
            content: "\edac"
        }

        ln ln-icon-Up-3:before {
            content: "\edad"
        }

        ln ln-icon-Up-4:before {
            content: "\edae"
        }

        ln ln-icon-Up:before {
            content: "\edaf"
        }

        ln ln-icon-Upgrade:before {
            content: "\edb0"
        }

        ln ln-icon-Upload-2:before {
            content: "\edb1"
        }

        ln ln-icon-Upload-toCloud:before {
            content: "\edb2"
        }

        ln ln-icon-Upload-Window:before {
            content: "\edb3"
        }

        ln ln-icon-Upload:before {
            content: "\edb4"
        }

        ln ln-icon-Uppercase-Text:before {
            content: "\edb5"
        }

        ln ln-icon-Upward:before {
            content: "\edb6"
        }

        ln ln-icon-URL-Window:before {
            content: "\edb7"
        }

        ln ln-icon-Usb-2:before {
            content: "\edb8"
        }

        ln ln-icon-Usb-Cable:before {
            content: "\edb9"
        }

        ln ln-icon-Usb:before {
            content: "\edba"
        }

        ln ln-icon-User:before {
            content: "\edbb"
        }

        ln ln-icon-Ustream:before {
            content: "\edbc"
        }

        ln ln-icon-Vase:before {
            content: "\edbd"
        }

        ln ln-icon-Vector-2:before {
            content: "\edbe"
        }

        ln ln-icon-Vector-3:before {
            content: "\edbf"
        }

        ln ln-icon-Vector-4:before {
            content: "\edc0"
        }

        ln ln-icon-Vector-5:before {
            content: "\edc1"
        }

        ln ln-icon-Vector:before {
            content: "\edc2"
        }

        ln ln-icon-Venn-Diagram:before {
            content: "\edc3"
        }

        ln ln-icon-Vest-2:before {
            content: "\edc4"
        }

        ln ln-icon-Vest:before {
            content: "\edc5"
        }

        ln ln-icon-Viddler:before {
            content: "\edc6"
        }

        ln ln-icon-Video-2:before {
            content: "\edc7"
        }

        ln ln-icon-Video-3:before {
            content: "\edc8"
        }

        ln ln-icon-Video-4:before {
            content: "\edc9"
        }

        ln ln-icon-Video-5:before {
            content: "\edca"
        }

        ln ln-icon-Video-6:before {
            content: "\edcb"
        }

        ln ln-icon-Video-GameController:before {
            content: "\edcc"
        }

        ln ln-icon-Video-Len:before {
            content: "\edcd"
        }

        ln ln-icon-Video-Len2:before {
            content: "\edce"
        }

        ln ln-icon-Video-Photographer:before {
            content: "\edcf"
        }

        ln ln-icon-Video-Tripod:before {
            content: "\edd0"
        }

        ln ln-icon-Video:before {
            content: "\edd1"
        }

        ln ln-icon-Vietnam:before {
            content: "\edd2"
        }

        ln ln-icon-View-Height:before {
            content: "\edd3"
        }

        ln ln-icon-View-Width:before {
            content: "\edd4"
        }

        ln ln-icon-Vimeo:before {
            content: "\edd5"
        }

        ln ln-icon-Virgo-2:before {
            content: "\edd6"
        }

        ln ln-icon-Virgo:before {
            content: "\edd7"
        }

        ln ln-icon-Virus-2:before {
            content: "\edd8"
        }

        ln ln-icon-Virus-3:before {
            content: "\edd9"
        }

        ln ln-icon-Virus:before {
            content: "\edda"
        }

        ln ln-icon-Visa:before {
            content: "\eddb"
        }

        ln ln-icon-Voice:before {
            content: "\eddc"
        }

        ln ln-icon-Voicemail:before {
            content: "\eddd"
        }

        ln ln-icon-Volleyball:before {
            content: "\edde"
        }

        ln ln-icon-Volume-Down:before {
            content: "\eddf"
        }

        ln ln-icon-Volume-Up:before {
            content: "\ede0"
        }

        ln ln-icon-VPN:before {
            content: "\ede1"
        }

        ln ln-icon-Wacom-Tablet:before {
            content: "\ede2"
        }

        ln ln-icon-Waiter:before {
            content: "\ede3"
        }

        ln ln-icon-Walkie-Talkie:before {
            content: "\ede4"
        }

        ln ln-icon-Wallet-2:before {
            content: "\ede5"
        }

        ln ln-icon-Wallet-3:before {
            content: "\ede6"
        }

        ln ln-icon-Wallet:before {
            content: "\ede7"
        }

        ln ln-icon-Warehouse:before {
            content: "\ede8"
        }

        ln ln-icon-Warning-Window:before {
            content: "\ede9"
        }

        ln ln-icon-Watch-2:before {
            content: "\edea"
        }

        ln ln-icon-Watch-3:before {
            content: "\edeb"
        }

        ln ln-icon-Watch:before {
            content: "\edec"
        }

        ln ln-icon-Wave-2:before {
            content: "\eded"
        }

        ln ln-icon-Wave:before {
            content: "\edee"
        }

        ln ln-icon-Webcam:before {
            content: "\edef"
        }

        ln ln-icon-weight-Lift:before {
            content: "\edf0"
        }

        ln ln-icon-Wheelbarrow:before {
            content: "\edf1"
        }

        ln ln-icon-Wheelchair:before {
            content: "\edf2"
        }

        ln ln-icon-Width-Window:before {
            content: "\edf3"
        }

        ln ln-icon-Wifi-2:before {
            content: "\edf4"
        }

        ln ln-icon-Wifi-Keyboard:before {
            content: "\edf5"
        }

        ln ln-icon-Wifi:before {
            content: "\edf6"
        }

        ln ln-icon-Wind-Turbine:before {
            content: "\edf7"
        }

        ln ln-icon-Windmill:before {
            content: "\edf8"
        }

        ln ln-icon-Window-2:before {
            content: "\edf9"
        }

        ln ln-icon-Window:before {
            content: "\edfa"
        }

        ln ln-icon-Windows-2:before {
            content: "\edfb"
        }

        ln ln-icon-Windows-Microsoft:before {
            content: "\edfc"
        }

        ln ln-icon-Windows:before {
            content: "\edfd"
        }

        ln ln-icon-Windsock:before {
            content: "\edfe"
        }

        ln ln-icon-Windy:before {
            content: "\edff"
        }

        ln ln-icon-Wine-Bottle:before {
            content: "\ee00"
        }

        ln ln-icon-Wine-Glass:before {
            content: "\ee01"
        }

        ln ln-icon-Wink:before {
            content: "\ee02"
        }

        ln ln-icon-Winter-2:before {
            content: "\ee03"
        }

        ln ln-icon-Winter:before {
            content: "\ee04"
        }

        ln ln-icon-Wireless:before {
            content: "\ee05"
        }

        ln ln-icon-Witch-Hat:before {
            content: "\ee06"
        }

        ln ln-icon-Witch:before {
            content: "\ee07"
        }

        ln ln-icon-Wizard:before {
            content: "\ee08"
        }

        ln ln-icon-Wolf:before {
            content: "\ee09"
        }

        ln ln-icon-Woman-Sign:before {
            content: "\ee0a"
        }

        ln ln-icon-WomanMan:before {
            content: "\ee0b"
        }

        ln ln-icon-Womans-Underwear:before {
            content: "\ee0c"
        }

        ln ln-icon-Womans-Underwear2:before {
            content: "\ee0d"
        }

        ln ln-icon-Women:before {
            content: "\ee0e"
        }

        ln ln-icon-Wonder-Woman:before {
            content: "\ee0f"
        }


        ln ln-icon-Worker-Clothes:before {
            content: "\ee11"
        }

        ln ln-icon-Worker:before {
            content: "\ee12"
        }

        ln ln-icon-Wrap-Text:before {
            content: "\ee13"
        }

        ln ln-icon-Wreath:before {
            content: "\ee14"
        }

        ln ln-icon-Wrench:before {
            content: "\ee15"
        }

        ln ln-icon-X-Box:before {
            content: "\ee16"
        }

        ln ln-icon-X-ray:before {
            content: "\ee17"
        }

        ln ln-icon-Xanga:before {
            content: "\ee18"
        }

        ln ln-icon-Xing:before {
            content: "\ee19"
        }

        ln ln-icon-Yacht:before {
            content: "\ee1a"
        }

        ln ln-icon-Yahoo-Buzz:before {
            content: "\ee1b"
        }

        ln ln-icon-Yahoo:before {
            content: "\ee1c"
        }

        ln ln-icon-Yelp:before {
            content: "\ee1d"
        }

        ln ln-icon-Yes:before {
            content: "\ee1e"
        }

        ln ln-icon-Ying-Yang:before {
            content: "\ee1f"
        }

        ln ln-icon-Youtube:before {
            content: "\ee20"
        }

        ln ln-icon-Z-A:before {
            content: "\ee21"
        }

        ln ln-icon-Zebra:before {
            content: "\ee22"
        }

        ln ln-icon-Zombie:before {
            content: "\ee23"
        }

        ln ln-icon-Zoom-Gesture:before {
            content: "\ee24"
        }

        ln ln-icon-Zootool:before {
            content: "\ee25"
        }


*/



















      ),
  );



?>
