<?php
/*Ajax 
Script*/

include '../functions.php';

if (isset($_POST['id']) && isset($_POST['eventid'])){

        $eventid = $_POST['eventid'];
        $studentid = $_POST['id'];

        $query = "SELECT * from volunteers where id=$studentid";
        $result = queryMySql($query);
        $ufirstname = mysql_result($result, 0,'firstname');
        $ulastname = mysql_result($result, 0,'lastname');
        $uemail = mysql_result($result, 0,'email');

        $query = "SELECT * from events where id=$eventid";
        $result = queryMySql($query);
        $eventname = mysql_result($result, 0,'eventname');

        $waitlistNum = -1;

        //check if currrent volunteers are equal to the maximum number of allowed voluntters.
		$currentvolunteers = mysql_num_rows(queryMySql("SELECT * FROM signups where eventid=$eventid"));
		if ($currentvolunteers >= mysql_result($result, 0,'max') && mysql_result($result, 0,'max')!=0){ //if signed at max or over max, put them on waitlist
			$query = "SELECT * from signups where eventid=$eventid order by waitlist DESC";
			$result = queryMySql($query);
			$highestWaitlist = mysql_result($result, 0,'waitlist');
			$waitlistNum = $highestWaitlist + 1;

			if (isset($_POST['notes'])){
				if ($_POST['notes'] != ""){
	                $notes = sanitizeString($_POST['notes']);
	                $query = "insert into signups(eventid,studentid,extra,waitlist) values($eventid,$studentid,'$notes',$waitlistNum)";
	                $result = mysql_query($query);
	            }
		    }
	        else{
	            $query = "insert into signups(eventid,studentid,waitlist) values($eventid,$studentid,$waitlistNum)";
	            $result = mysql_query($query);
	        }


		}
		else{
			if (isset($_POST['notes'])){
				if ($_POST['notes'] != ""){
	                $notes = sanitizeString($_POST['notes']);
	                $query = "insert into signups(eventid,studentid,extra) values($eventid,$studentid,'$notes')";
	                $result = mysql_query($query);
	            }
		    }
	        else{
	            $query = "insert into signups(eventid,studentid) values($eventid,$studentid)";
	            $result = mysql_query($query);
	        }


		}

		

        
        

        //email the student a confirmation email
		require '../library/phpmailer/class.phpmailer.php';
		
		$mail = new PHPMailer;
		$mail->IsSMTP();
		$mail->Host = 'smtp.gmail.com';
		$mail->Port       = 587;
		$mail->SMTPAuth = true;
		$mail->Username = 'eric.yu@kinkaid.org';
		$mail->Password = 'Hdodlq55';
		$mail->SMTPSecure = 'tls';
		
		$subject = "Event Confirmation Email";//add subject
		$message = "You have succesfully signed up for Event: $eventname!"; // add message
		$recipient =  $uemail;
		$mail->AddAddress($recipient);
		
		$mail->From = 'kinkaidcommunityservice@gmail.com';
		$mail->FromName = 'Kinkaid Community Service Council';
		
		$mail->Subject = $subject;
		$mail->Body = $message;
		$mail->Send();


		if($waitlistNum!=-1){
			
			echo json_encode($waitlistNum);
		}


	
}else{


}



?>
