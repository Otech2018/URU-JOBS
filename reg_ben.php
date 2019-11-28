<?php


//**new coonect by ben
 	
 	$username = "ejykmgh10_jbs";
	$password = "ejykmgh@@101";
	$host = 'mysql.vps44038.mylogin.co';
	$db ='ejykmgh10_jbs';
	$connect_ben = new PDO("mysql:host=$host;dbname=$db",$username,$password);
	
 	//***ends her by ben

  if (isset($_POST['doRegister'])){
 
     $userlevel= addslashes(htmlentities($_POST['userlevel']));
	 $fname= addslashes(htmlentities($_POST['fname']));
     $lname= addslashes(htmlentities($_POST['lname']));
     $email= addslashes(htmlentities($_POST['email']));
    $dater = date('Y-m-d H:i:s');
	$username= addslashes(htmlentities($_POST['username']));
     $pass= $_POST['pass'];
     
      $pass_enc= md5($pass);
 	$reg_users ="INSERT INTO users (username, password, fname, lname, email, userlevel, active, created ) values('$username','$pass_enc', '$fname', '$lname', '$email', '$userlevel','y','$dater')";
 	if($connect_ben ->query($reg_users)){
     echo "<script> alert('Account Registered Successfully!!!'); 
     	 window.location.replace('login.php'); </script>"; 	
  	}
    	
  }
?>