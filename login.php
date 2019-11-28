<?php


if( isset( $_GET['reg']) ){
  $reg = $_GET['reg'];
  if( $reg=='yes'){
    echo "<script> alert('Account Registered Successfully!, We Have Send A verification Link to your Email.'); 
     	 window.location.replace('login.php'); </script>"; 
  }
}

  define("_VALID_PHP", true);
  require_once("init.php");
  
  if (isset($_POST['passReset'])):
      $user->passReset();
  endif;
  
  if ($user->logged_in && $user->userlevel == 1){
	  redirect_to("seeker.php");
  } else if ($user->logged_in && $user->userlevel == 2){
      redirect_to("employer.php");
  }
	  
  if (isset($_POST['doLogin'])){
	  $result = $user->login($_POST['username'], $_POST['password']);
	  if ($result){
		  if ($user->userlevel == 1){
			  redirect_to("account.php");
		  } else if ($user->userlevel == 2){
			  redirect_to("account.php");
		  }
	  }
  }
?>
<?php require_once (THEMEDIR . "/login.tpl.php");?>