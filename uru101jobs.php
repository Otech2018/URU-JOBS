<?php



if( isset($_GET['hashem']) ){

//**new coonect by ben
 	
 	$username = "ejykmgh10_jbs";
	$password = "ejykmgh@@101";
	$host = 'mysql.vps44038.mylogin.co';
	$db ='ejykmgh10_jbs';
	$connect_ben = new PDO("mysql:host=$host;dbname=$db",$username,$password);
	
 	//***ends her by ben
 	
 	  $email_act= addslashes(htmlentities($_GET['hashem']));
 	 
 	 
 	 $reg_users="UPDATE users SET active='y' where email='$email_act' ";
 
 	if($connect_ben ->query($reg_users)){
     echo "<script> alert('Account Verified Successfully!!!'); 
     	 window.location.replace('login.php'); </script>"; 	
  	}else{
  		 echo "<script> alert('Invalid Link!!!'); 
     	 window.location.replace('index.php'); </script>"; 
  	}
 	
 	

}else{

	 echo "<script>window.location.replace(\"index.php\");</script>";


}



?>