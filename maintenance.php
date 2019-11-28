<?php
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
	  if($core->offline == 0)
	     redirect_to(SITEURL);
?>
<!doctype html>
<head>
<meta charset="utf-8">
<script src="<?php echo SITEURL; ?>/assets/jquery.js"></script>
<script src="<?php echo SITEURL;?>/assets/ud/script.js"></script>
<title><?php echo $core->site_name;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="http://fonts.googleapis.com/css?family=BenchNine" rel="stylesheet" type="text/css" />
<link href="http://fonts.googleapis.com/css?family=Raleway:300,400,600,700,800" rel="stylesheet" type="text/css">
<link href="<?php echo SITEURL;?>/assets/ud/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<!-- Start Maintenance-->
<div id="container">
  <div class="wrapper">
    <div class="logo"><?php echo ($core->logo) ? '<img src="'.SITEURL.'/uploads/'.$core->logo.'" alt="'.$core->company.'" />': $core->company;?></div>
    <h1><?php echo Lang::$word->_M_H1;?></h1>
    <h2 class="subtitle"><?php echo Lang::$word->_M_H2;?></h2>
    <div id="dashboard" class="row">
      <div class="col grid_6">
        <div class="dash weeks_dash">
          <div class="digit first">
            <div style="display:none" class="top">1</div>
            <div style="display:block" class="bottom">0</div>
          </div>
          <div class="digit last">
            <div style="display:none" class="top">3</div>
            <div style="display:block" class="bottom">0</div>
          </div>
          <span class="dash_title"><?php echo Lang::$word->_M_WEEKS;?></span> </div>
      </div>
      <div class="col grid_6">
        <div class="dash days_dash">
          <div class="digit first">
            <div style="display:none" class="top">0</div>
            <div style="display:block" class="bottom">0</div>
          </div>
          <div class="digit last">
            <div style="display:none" class="top">0</div>
            <div style="display:block" class="bottom">0</div>
          </div>
          <span class="dash_title"><?php echo Lang::$word->_M_DAYS;?></span> </div>
      </div>
      <div class="col grid_6">
        <div class="dash hours_dash">
          <div class="digit first">
            <div style="display:none" class="top">2</div>
            <div style="display:block" class="bottom">0</div>
          </div>
          <div class="digit last">
            <div style="display:none" class="top">3</div>
            <div style="display:block" class="bottom">0</div>
          </div>
          <span class="dash_title"><?php echo Lang::$word->_M_HOURS;?></span> </div>
      </div>
      <div class="col grid_6">
        <div class="dash minutes_dash">
          <div class="digit first">
            <div style="display:none" class="top">2</div>
            <div style="display:block" class="bottom">0</div>
          </div>
          <div class="digit last">
            <div style="display:none" class="top">9</div>
            <div style="display:block" class="bottom">0</div>
          </div>
          <span class="dash_title"><?php echo Lang::$word->_M_MINUTES;?></span> </div>
      </div>
    </div>
    <div class="info-box"> <?php echo cleanOut($core->offline_msg);?> </div>
  </div>
</div>
<?php 
  $d = explode("-",$core->offline_d); 
  $t = explode(":",$core->offline_t);
?>
<script type="text/javascript">
$(document).ready(function () {
	$('#dashboard').countDown({
		targetDate: {
			'day': <?php echo $d[2];?>,
			'month': <?php echo $d[1];?>,
			'year': <?php echo $d[0];?>,
			'hour': <?php echo $t[0];?>,
			'min': <?php echo $t[1];?>,
			'sec': 0
		}
	});
});
</script> 
<!-- End Maintenance/-->
</body>
</html>