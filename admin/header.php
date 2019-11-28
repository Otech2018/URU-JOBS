<?php

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $core->site_name;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="icon" href="assets/images/favicon.png" type="image/gif" sizes="16x16">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Oswald:400,300" rel="stylesheet" type="text/css">
<link href="<?php echo THEMEU . '/cache/' . Minify::cache(array('css/base.css','css/button.css','css/image.css','css/icon.css','css/breadcrumb.css','css/popup.css','css/form.css','css/input.css','css/table.css','css/label.css','css/segment.css','css/message.css','css/divider.css','css/dropdown.css','css/list.css','css/breadcrumb.css','css/progress.css','css/header.css','css/menu.css','css/datepicker.css','css/editor.css','css/utility.css','css/style.css'),'css');?>" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../assets/jquery.js"></script>
<script type="text/javascript" src="../assets/jquery-ui.js"></script>
<script type="text/javascript" src="../assets/modernizr.mq.js"></script>
<script type="text/javascript" src="../assets/global.js"></script>
<script type="text/javascript" src="../assets/editor.js"></script>
<script type="text/javascript" src="../assets/jquery.ui.touch-punch.js"></script>
<script type="text/javascript" src="../assets/editor.js"></script>
<script type="text/javascript" src="assets/js/master.js"></script>
</head>
<body>
<div id="helpbar" class="wojo wide floating info right sidebar"></div>
