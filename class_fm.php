<?php
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  
  class FM
  {
      public static $fileext = array(
          "gif",
          "jpg",
          "jpeg",
          "bmp",
          "png",
		  "psd",
          "txt",
          "nfo",
          "doc",
          "docx",
          "xls",
          "xlsx",
          "htm",
          "html",
          "zip",
          "rar",
          "tar",
          "css",
          "pdf",
          "swf",
          "avi",
          "mp4",
          "ogv",
          "webm",
          "mp3");
	  
      /**
       * FM::renderFiles()
       * 
       * @return
       */
	  private static function renderFiles()
	  {
		  $getDir = dir(Registry::get("Core")->file_dir);
		  $nofiles = 0;
		  $exclude = array(
			  'php',
			  'html',
			  //'jpg',
			  'htaccess'
			  );
		  while (false !== ($name = $getDir->read())) {
			  if ($name != "." && $name != ".." && !in_array(pathinfo($name, PATHINFO_EXTENSION), $exclude)) {
				  $nofiles++;
				  $filename[$nofiles] = $name;
			  }
		  }
		  $getDir->close();
		  return ($filename) ? $filename : 0;
	
	  }


      /**
       * FM::getDatabaseFiles()
       * 
       * @return
       */
	  public function getDatabaseFiles()
	  {
		  if ($sql = ("SELECT * FROM " . Products::fTable . " ORDER BY alias")) {
			  $result = Registry::get("Database")->query($sql);
			  $var = array();
			  while ($row = Registry::get("Database")->fetch($result, true)) {
				if(in_array($row['name'], self::renderFiles())) {
					$var[] = $row;
				}
			  }
            return $var;
		  }
	
	  }

      /**
       * FM::renderDifference()
       * 
       * @return
       */
	  public function renderDifference()
	  {
		  $getDir = dir(Registry::get("Core")->file_dir);
		  $nofiles = 0;
		  $files = $this->getDatabaseDiffFiles();
		  $exclude = array(
			  'php',
			  'html',
			  //'jpg',
			  'htaccess'
			  );
	
		  while (false !== ($name = $getDir->read())) {
			  if ($name != "." && $name != ".." && !in_array(pathinfo($name, PATHINFO_EXTENSION), $exclude)) {
				  $nofiles++;
				  $filename[$nofiles] = $name;
			  }
		  }
		  $getDir->close();
		  $result = array_diff($filename, $files);
		  return ($result) ? $result : 0;
	
	  }

      /**
       * Plugins::getDatabaseDiffFiles()
       * 
       * @return
       */
	  private function getDatabaseDiffFiles()
	  {
		  if ($sql = ("SELECT name FROM " . Products::fTable)) {
			  $result = Registry::get("Database")->query($sql);
			  $var = array();
	
			  while ($row = Registry::get("Database")->fetch($result, true))
				  $var[] = $row['name'];
			  return $var;
		  }
	
	  }

      /**
       * FM::renderDatabaseFiles()
       * 
       * @return
       */
		public function renderDatabaseFiles()
		{
			$filedata = $this->getDatabaseFiles();
			if (!$filedata) {
				print Filter::msgInfo(Lang::$word->FLM_NOFILES);
			} else {
				foreach ($filedata as $row) {
					print '
				  <li id="fileid_' . $row['id'] . '" class="file-data" data-name="' . $row['alias'] . '">
					<figure> <img src="assets/images/mime/' . self::getFileType($row['name']) . '" alt="" /> </figure>
					<p class="filetitle"> ' . $row['alias'] . ' </p>
				  </li>';
				}
				unset($row);
			}
		}

      /**
       * FM::renderListFiles()
       * 
       * @return
       */
	  public function renderListFiles()
	  {
		  $filedata = Registry::get("FM")->getDatabaseFiles();
		  if (!$filedata) {
			  print '
					<tr class="nohover">
					<td colspan="6">' . Filter::msgInfo(Lang::$word->FLM_NOFILES, false) . '</td>
					</tr>';
		  } else {
			  foreach ($filedata as $row) {
				  print '
				  <tr>
					<td><span class="editme tooltip editable-click" id="list_' . $row['id'] . '" data-rel="' . $row['alias'] . '" data-title="' . Lang::$word->FLM_RENAME . '">' . $row['alias'] . '</span></td>
					<td>' . $row['name'] . '</td>
					<td>' . getSize($row['filesize']) . '</td>
					<td>' . $row['created'] . '</td>
					<td>' . date ("F d Y H:i:s", filemtime(Registry::get("Core")->file_dir . $row['name'])) . '</td>
					<td><span class="tbicon"> <a id="item_' . $row['id'] . '" class="tooltip delete" data-type="live" data-rel="' . $row['alias'] . '" data-title="' . Lang::$word->DELETE . '"><i class="icon-trash"></i></a> </span></td>
				  </tr>';
			  }
			  unset($row);
		  }
	  }
          
      /**
       * FM::galleryUpload()
       * 
       * @return
       */
	  public function filesUpload($filename)
	  {
		  if (isset($_FILES[$filename]) && $_FILES[$filename]['error'] == 0) {
	
			  $path = Registry::get("Core")->file_dir;

			  $extension = pathinfo($_FILES[$filename]['name'], PATHINFO_EXTENSION);
			  if (!in_array(strtolower($extension), self::$fileext)) {
				  $json['status'] = "error";
				  $json['msg'] = Lang::$word->GAL_ERR;
				  print json_encode($json);
				  exit;
			  }
	
			  if (file_exists($path . $_FILES[$filename]['name'])) {
				  $json['status'] = "error";
				  $json['msg'] = Lang::$word->GAL_ERR1;
				  print json_encode($json);
				  exit;
			  }
			  
			  if (!is_writeable($path)) {
				  $json['status'] = "error";
				  $json['msg'] = Lang::$word->GAL_ERR2;
				  print json_encode($json);
				  exit; 
			  }
			  
			  if (!is_dir($path)) {
				  $json['status'] = "error";
				  $json['msg'] = Lang::$word->GAL_ERR4;
				  print json_encode($json);
				  exit;  
			  }
  
			  $newName = "FILE_" . randName();
			  $ext = substr($_FILES[$filename]['name'], strrpos($_FILES[$filename]['name'], '.') + 1);
			  $fullname = $path . $newName . "." . strtolower($ext);
			  
			  $data['alias'] = $_FILES[$filename]['name'];
			  $data['filesize'] = $_FILES[$filename]['size'];
			  $data['name'] = $newName . "." . strtolower($ext);
			  $data['created'] = "NOW()";
				  
			  if (move_uploaded_file($_FILES[$filename]['tmp_name'], $fullname)) {
				  $last_id = Registry::get("Database")->insert(Products::fTable, $data);
				  $html = '
					<div data-id="' . $last_id . '" class="item content-center" data-name="' . $data['alias'] . '"> <img src="assets/images/mime/' . self::getFileType($data['name']) . '" alt="" />
					  <p class="filetitle"> ' . $data['alias'] . ' </p>
					</div>';
				  
				  $json['status'] = "success";
				  $json['msg'] = $html;
				  print json_encode($json);
				  exit;
			  }
	
		  }
	
		  $json['status'] = "error";
		  exit;
	  }
	  
	  /**
	   * Uploader::getFileType()
	   * 
	   * @param mixed $filename
	   * @return
	   */
	  public static function getFileType($filename) {
		
		$ext = substr($filename, strrpos($filename, '.') + 1); 
		switch ($ext) {
			case "css":
				return "css.png";
				break;
				
			case "csv":
				return "csv.png";
				break;
				
			case "fla":
			case "swf":
				return "fla.png";
				break;
				
			case "mp3":
			case "wav":
				return "mp3.png";
				break;

			case "jpg":
			case "JPG":
			case "jpeg":
				return "jpg.png";
				break;
				
			case "png":
				return "png.png";
				break;
				
			case "gif":
				return "gif.png";
				break;
				
			case "bmp":
			case "dib":
				return "bmp.png";
				break;
				
			case "txt":
			case "log":
				return "txt.png";
				break;
				
			case "sql":
				return "sql.png";
				break;
								
			case "js":
				echo "js.png";
				break;
				
			case "pdf":
				return "pdf.png";
				break;
				
			case "zip":
			case "rar":
			case "tgz":
			case "gz":
				return "zip.png";
				break;
				
			case "doc":
			case "docx":
			case "rtf":
				return "doc.png";
				break;
				
			case "asp":
			case "jsp":
				echo "asp.png";
				break;
				
			case "php":
				return "php.png";
				break;
				
			case "htm":
			case "html":
				return "htm.png";
				break;
				
			case "ppt":
				return "ppt.png";
				break;
				
			case "exe":
			case "bat":
			case "com":
				return "exe.png";
				break;
				
			case "wmv":
			case "mpg":
			case "mpeg":
			case "wma":
			case "asf":
				return "wmv.png";
				break;
				
			case "midi":
			case "mid":
				return "midi.png";
				break;
				
			case "mov":
				return "mov.png";
				break;
				
			case "psd":
				return "psd.png";
				break;
				
			case "ram":
			case "rm":
				return "rm.png";
				break;
				
			case "xml":
				return "xml.png";
				break;

			case "xls":
			case "xlsx":
				return "xls.png";
				break;
				
			default:
				return "default.png";
				break;
		}	

	  }
  }
?>