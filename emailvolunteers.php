
<?php
/*
Self submitting php file. If user has not submitted anything, will display form items needed to send email
Once user has submitted the form to this file again, it will automatically send the email

*/


require 'library/phpmailer/class.phpmailer.php'; //import phpmailer library
function spamcheck($field)
  {
	  //filter_var() sanitizes the e-mail
	  //address using FILTER_SANITIZE_EMAIL
	  $field=filter_var($field, FILTER_SANITIZE_EMAIL);
	
	  //filter_var() validates the e-mail
	  //address using FILTER_VALIDATE_EMAIL
	  if(filter_var($field, FILTER_VALIDATE_EMAIL))
	    {
	    	return TRUE;
	    }
	  else
	    {
	    	return FALSE;
	    }
  }

if (isset($_REQUEST['email']))
	{//if "email" is filled out, proceed, the form has been submitted
	
	//check if the email address is invalid
		//$mailcheck = spamcheck($_REQUEST['email']);
		//if ($mailcheck==FALSE)
		//{
		//	echo "Invalid input. Please try <a href = 'dashboard.php'>again</a>";
		//}
		//else
		//{//send email
			$mail = new PHPMailer; //create new php object
			//fill out basic email information
			$mail->IsSMTP();
			$mail->IsHtml(true);
			$mail->Host = 'smtp.gmail.com';
			$mail->Port       = 587;
			$mail->SMTPAuth = true;
			$mail->Username = 'eric.yu@kinkaid.org';
			$mail->Password = 'Hdodlq55';
			$mail->SMTPSecure = 'tls';
			
			//stores the POST data from the textareas in varibales
			$recipients = $_POST['email'];
			$bcc = $_POST['bcc'];
			$cc = $_POST['cc'];
			$subject = $_POST['subject'];
			$message = $_POST['message'];
			
			//split the huge string of recipients into an array by splitting the comma
			$recipientsarray = explode(" , ", $recipients);
			$bccarray = explode(" , ", $bcc);
			$ccarray = explode(" , ", $cc);
			
			
			$mail->From = 'eric.yu@kinkaid.org';
			$mail->FromName = 'Kinkaid Community Service Council';


			//Add all the recipients to the object information
			for ($i = 0;$i<count($recipientsarray);$i++){
				$mail->AddAddress($recipientsarray[$i]);
				
			}
			for ($i = 0;$i<count($bccarray);$i++){
				$mail->AddBCC($bccarray[$i]);
			}
			for ($i = 0;$i<count($ccarray);$i++){
				$mail->AddCC($ccarray[$i]);
			}
			
			
			$mail->Subject = $subject;
			$mail->Body = $message;
			
			if(!$mail->Send()) {
				echo 'Message could not be sent.';
				echo 'Mailer Error: ' . $mail->ErrorInfo;
				exit;
			}
			
			//give admin a link to return to dashboard
			echo "Message has been sent. You can close this tab.";
			

		//}
	}
	else
	{//if "email" is not filled out, display the form
		if (isset($_POST['emailvolunteers']) && isset($_POST['eventid'])){
			include 'header.php';

			//get post data and store it in variables
			$eventid = $_POST['eventid'];
			$query = "SELECT * FROM events where id='$eventid'";
			$result = queryMySql($query);
			$eventname = mysql_result($result, 0,'eventname');

			//get the signup information
			$query = "SELECT * FROM signups where eventid=$eventid and waitlist=0 and noshow=false and withdrew=0";
			$result = queryMySql($query);
			
			//set up two arrays to store student and parent emails
			$studentemails[] = array();
			$parentemails[] = array();

			//iterate through each student entry and to the email arrays the student's email and the parent's email
			for ($i = 0;$i<mysql_num_rows($result);$i++){
				//get the id of a tusdent
				$studentid = mysql_result($result, $i,'studentid');
				//get the entry of the student from 'volunteers'
				$query = "SELECT * FROM volunteers where id=$studentid";
				$studentresult = queryMySql($query);

				//store the student email and parent email in the arrays
				$studentemails[$i] = mysql_result($studentresult, 0,'email');
				$parentemails[$i] = mysql_result($studentresult, 0,'parentemail');
			}

			//turn the array of emails into a long string, each email seperated by a comma
			$studentemailstring = implode(" , ", $studentemails);
			$parentemailstring = implode(" , ", $parentemails);



			//display the form
			echo <<<END
			
			
			<div class="container">
	         	<div class="row ">
	            <div class = "margindiv">
	            <h3 class = "muted">Email Form</h3>
	            <form class = "form-horizontal" action = "emailvolunteers.php" method = "POST">
	                <fieldset>
	                    
	                    
	                <legend>Emailing Volunteers for Event: $eventname</legend>
	                <p>You can either copy the emails into gmail, or use the simple messaging interface on the site. If you choose to email from the site, make sure there is a <b>space, comma, and space</b> after <b>every</b> email entry. E.G. 'eric.yu@kinkaid.org , someonelse@kinkaid.org'. Otherwise it will not work properly.</p>
	                <br>
	                    
	                    
	                <div class = " control-group">
	                    <label class = "control-label" >To:</label>
	                    <div class = "controls">
	                        <input class = "addressfield" type="text" onclick="this.focus();this.select()" name = 'email' value = "$studentemailstring"/>
	                    </div>
	                </div>
	                
	                
	                <div class = "control-group">
	                    <label  class = "control-label" >BCC:</label>
	                    <div class = "controls">
	                        <input class = "addressfield" type = "text" onclick="this.focus();this.select()" name = 'bcc' value = "$parentemailstring" />
	                    </div>
	                    
	                </div>
	                
	                <div class = "control-group">
	                     <label   class = "control-label" >CC:</label>
	                    <div class = "controls">
	                        <input class  ="addressfield" type = "text" onclick="this.focus();this.select()" name = 'cc' />
	                    </div>
	                </div>
	                                    
	                    
	                <hr>

	                <div  class = "control-group">
	                    <label class = "control-label" >Subject:</label>
	                    <div class ="controls">
	                        <input name = 'subject' type = "text" class = "input-xlarge" id = "input01"/>
	                    </div>
	                </div>
	                    
	                <div class = "control-group">
	                    <label  class = "control-label" >Message:</label>
	                    <br>
	                    <br>
	                    <textarea class = "ckeditor" name = "message" wrap ="type" ></textarea>
	                </div>

	                <div class = "form-actions">
	                    <button name = "edit" type = "submit" class = "btn btn-primary">Send Message</button>
	                </div>

	              
	              
	              
	                </fieldset>

	            </form>
	            </div>
	        </div>
	        </div>




			
			<script type = 'text/javascript' src='plugins/ckeditor/ckeditor.js'></script>

			
END;

	}
}


?>

      
      
      
</body>
</html>
