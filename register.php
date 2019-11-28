<?php
  define("_VALID_PHP", true);
  require_once("init.php");

  if ($user->logged_in)
      redirect_to("account.php");
	  
   $numusers = countEntries("users");
   $datacountry = $content->getCountryList();
    
	/* Registration */

  if (isset($_POST['doRegister'])){
 	$result=$user->register();
    if($result=="success") redirect_to("login.php?reg=yes");
 
    	
  }
?>
<?php require_once (THEMEDIR . "/register.tpl.php");?>