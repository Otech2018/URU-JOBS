<?php

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  require_once(BASEPATH . "lib/class_dbtools.php");
  Registry::set('dbTools',new dbTools());
?>
<div class="wojo wojo black message"><i class="icon pin"></i> <?php echo Core::langIcon();?><?php echo Lang::$word->SYS_INFO;?></div>
<div id="msgholder"></div>
<div class="wojo basic segment">
  <div class="header">
    <ul class="idTabs" id="tabs">
      <li><a data-tab="#cms"><?php echo Lang::$word->SYS_CMS_INF;?></a></li>
      <li><a data-tab="#php"><?php echo Lang::$word->SYS_PHP_INF;?></a></li>
      <li><a data-tab="#server"><?php echo Lang::$word->SYS_SER_INF;?></a></li>
      <li><a data-tab="#dbtables"><?php echo Lang::$word->SYS_DBTABLE_INF;?></a></li>
    </ul>
    <span><?php echo Lang::$word->SYS_SUB;?></span> </div>
  <div id="cms" class="tab_content">
    <table class="wojo two column table">
      <thead>
        <tr>
          <th colspan="2"><?php echo Lang::$word->SYS_CMS_INF;?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo Lang::$word->SYS_CMS_VER;?>:</td>
          <td>v<?php echo $core->version;?> <span id="version"> </span></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_ROOT_URL;?>:</td>
          <td><?php echo SITEURL;?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_ROOT_PATH;?>:</td>
          <td><?php echo BASEPATH;?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_UPL_URL;?>:</td>
          <td><?php echo UPLOADURL;?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_UPL_PATH;?>:</td>
          <td><?php echo UPLOADS;?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_DEF_LANG;?>:</td>
          <td><?php echo strtoupper($core->lang);?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div id="php" class="tab_content">
    <table class="wojo two column table">
      <thead>
        <tr>
          <th colspan="2"><?php echo Lang::$word->SYS_PHP_INF;?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo Lang::$word->SYS_PHP_VER;?>:</td>
          <td><?php echo phpversion();?></td>
        </tr>
        <tr>
          <?php $gdinfo = gd_info();?>
          <td><?php echo Lang::$word->SYS_GD_VER;?>:</td>
          <td><?php echo $gdinfo['GD Version'];?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_MQR;?>:</td>
          <td><?php echo (ini_get('magic_quotes_gpc')) ? Lang::$word->ON : Lang::$word->OFF;?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_LOG_ERR;?>:</td>
          <td><?php echo (ini_get('log_errors')) ? Lang::$word->ON : Lang::$word->OFF;?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_MEM_LIM;?>:</td>
          <td><?php echo ini_get('memory_limit');?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_RG;?>:</td>
          <td><?php echo (ini_get('register_globals')) ? Lang::$word->ON : Lang::$word->OFF;?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_SM;?>:</td>
          <td><?php echo (ini_get('safe_mode')) ? Lang::$word->ON : Lang::$word->OFF;?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_UMF;?>:</td>
          <td><?php echo ini_get('upload_max_filesize'); ?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_PMF;?>:</td>
          <td><?php echo ini_get('post_max_size');?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_SSP;?>:</td>
          <td><?php echo ini_get('session.save_path' );?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div id="server" class="tab_content">
    <table class="wojo two column table">
      <thead>
        <tr>
          <th colspan="2"><?php echo Lang::$word->SYS_SER_INF;?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo Lang::$word->SYS_SER_OS;?>:</td>
          <td><?php echo php_uname('s')." (".php_uname('r').")";?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_SER_API;?>:</td>
          <td><?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_SER_DB;?>:</td>
          <td><?php echo mysqli_get_client_info();?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_DBV;?>:</td>
          <td><?php echo mysqli_get_server_info($db->getLink());?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_MEMALO;?>:</td>
          <td><?php echo ini_get('memory_limit');?></td>
        </tr>
        <tr>
          <td><?php echo Lang::$word->SYS_STS;?>:</td>
          <td><?php echo getSize(disk_free_space("."));?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div id="dbtables" class="tab_content"> <?php print dbTools::optimizeDb();?> </div>
</div>