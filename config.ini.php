<?php 
	/** 
	* Configuration

	* @package Job Board
	*/
 
	 if (!defined("_VALID_PHP")) 
     die('Direct access to this location is not allowed.');
 
	/** 
	* Database Constants - these constants refer to 
	* the database configuration settings. 
	*/
	 define('DB_SERVER', 'mysql.vps44038.mylogin.co'); 
	 define('DB_USER', 'ejykmgh10_jbs'); 
	 define('DB_PASS', 'ejykmgh@@101'); 
	 define('DB_DATABASE', 'ejykmgh10_jbs');
 
 	//**new coonect by ben
 	
 	$username = "ejykmgh10_jbs";
	$password = "ejykmgh@@101";
	$host = 'mysql.vps44038.mylogin.co';
	$db ='ejykmgh10_jbs';
	$connect_ben = new PDO("mysql:host=$host;dbname=$db",$username,$password);
	
 	//***ends her by ben
 
 	
	/** 
	* Show MySql Errors. 
	* Not recomended for live site. true/false 
	*/
	 define('DEBUG', true);
 
	/** 
	* Cookie Constants - these are the parameters 
	* to the setcookie function call, change them 
	* if necessary to fit your website 
	*/
	 define('COOKIE_EXPIRE', 60 * 60 * 24 * 60); 
	 define('COOKIE_PATH', '/');
?>