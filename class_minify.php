<?php
  /**
   * Minify Class
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: class_minify.php, v1.00 2012-03-05 10:12:05 gewa Exp $
   */

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');


  class Minify
  {

      const prefix = 'master_';
	  const suffix = '.css';


      /**
       * Minify::__callStatic()
       * 
       * @param mixed $alt
       * @param mixed $source
       * @return
       */
      public static function cache($source)
      {

          $target = THEME . '/cache/';
          $last_change = self::last_change($source);
          $temp = $target . self::prefix . 'main' . self::suffix;

          if (!file_exists($temp) || $last_change > filemtime($temp)) {
              if (!self::write_cache($source, $temp)) {
                  Filter::msgError("Minify:: - Writing the file to <{$target}> failed!");
              }
          }
		  
          return basename($temp);
      }



      /**
       * Minify::last_change()
       * 
       * @param mixed $files
       * @return
       */
      protected static function last_change($files)
      {
          foreach ($files as $key => $file) {
              $files[$key] = filemtime(THEME . '/' . $file);
          }

          sort($files);
          $files = array_reverse($files);

          return $files[key($files)];
      }


      /**
       * Minify::write_cache()
       * 
       * @param mixed $files
       * @param mixed $target
       * @return
       */
      protected static function write_cache($files, $target)
      {

          $content = "";

          foreach ($files as $file) {
              $content .= file_get_contents(THEME . '/' . $file);
          }


          $content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
          $content = str_replace(array(
              "\r\n",
              "\r",
              "\n",
              "\t",
              '  ',
              '    ',
              '    '), '', $content);
          $content = str_replace(array(
              ': ',
              ' {',
              ';}'), array(
              ':',
              '{',
              '}'), $content);
			  
          if (!file_exists(THEME . '/cache/'))
              mkdir(THEME . '/cache/');
			  
          return file_put_contents($target, $content);
      }
  }
?>