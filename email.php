<?php
  			
  			$mail_uru = "ajahogonnaya2012@gmail.com";
  			$user_name = "Benjamin";
  			$pass_word ="12345";
			$user_activation_code=  md5($pass_word);
			  $base_url = "http://urujobs.tech/";  //change this baseurl value as per your file path
			$mail_body = "
			<p>Hi ". $user_name." ,</p>
			<h3>Thank You For Registering at <a href='http://urujob.tech'>URUJOBS</a></h3>
			<p>Please Open this link to verified your email address - ".$base_url."uru101jobs.php?psps=".$user_activation_code."&hashem=".$mail_uru."
			<p>Best Regards,<br /><b>Uru Jobs</b></p>
			";
			require 'class/class.phpmailer.php';
			$mail = new PHPMailer;
			$mail->IsSMTP();								//Sets Mailer to send message using SMTP
			$mail->Host = 'mail.vps44038.mylogin.co';		//Sets the SMTP hosts of your Email hosting, this for Godaddy
			$mail->Port = '25';								//Sets the default SMTP server port
			$mail->SMTPAuth = true;							//Sets SMTP authentication. Utilizes the Username and Password variables
			$mail->Username = 'support@urujobs.tech';					//Sets SMTP username
			$mail->Password = 'VTTrader@101';					//Sets SMTP password
			$mail->SMTPSecure = '';							//Sets connection prefix. Options are "", "ssl" or "tls"
			$mail->From = 'support@urujobs.tech';			//Sets the From email address for the message
			$mail->FromName = 'UruJobs';					//Sets the From name of the message
			$mail->AddAddress($mail_uru, $username);		//Adds a "To" address			
			$mail->WordWrap = 50;			//Sets word wrapping on the body of the message to a given number of characters
			$mail->IsHTML(true);							//Sets message type to HTML				
			$mail->Subject = 'Email Verification';			//Sets the Subject of the message
			$mail->Body = $mail_body;							//An HTML or plain text message body
			if($mail->Send()){
			
			echo "<script>alert(\"SENT \");</script>"; 
			}else{
			echo "<script>alert(\"error \");</script>"; 
			}
			
			
			
			?>