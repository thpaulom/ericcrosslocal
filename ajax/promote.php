<?php
$query = "";
$result = "";
include '../functions.php';



if (isset($_POST['id']) && isset($_POST['eventid'])){
	$studentid = $_POST['id'];
	$eventid = $_POST['eventid'];

	 $query = "SELECT * from volunteers where id=$studentid";
    $result = queryMySql($query);
    $ufirstname = mysql_result($result, 0,'firstname');
    $ulastname = mysql_result($result, 0,'lastname');
    $uemail = mysql_result($result, 0,'email');

    $query = "SELECT * from events where id=$eventid";
    $result = queryMySql($query);
    $eventname = mysql_result($result, 0,'eventname');

	$query = "SELECT * from signups where studentid=$studentid and eventid=$eventid";
	$result = queryMySql($query);
	$currentWaitlistNum = mysql_result($result, 0,'waitlist');

	$query = "UPDATE signups set waitlist=0 where studentid=$studentid and eventid=$eventid";
	$result = queryMySql($query);

	$query = "UPDATE signups set waitlist=(waitlist-1) where eventid=$eventid and waitlist>$currentWaitlistNum";
	$result = queryMySql($query);


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
	$message = "You have been promoted off the waitlist for Event: $eventname! You will begin to receive information from the project leader."; // add message
	$recipient =  $uemail;
	$mail->AddAddress($recipient);
	
	$mail->From = 'kinkaidcommunityservice@gmail.com';
	$mail->FromName = 'Kinkaid Community Service Council';
	
	$mail->Subject = $subject;
	$mail->Body = $message;
	$mail->Send();


}

?>
