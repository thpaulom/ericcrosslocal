
<?php


include 'header.php';
$studentid = null;

if (isset($_GET['studentid']) && $_GET['studentid'] != ''){
	
	$studentid = $_GET['studentid'];

	

}
else if ($_SESSION['userid']){
	$studentid = $_SESSION['userid'];

}

if ($studentid){



	$query = "SELECT * from volunteers where id=$studentid";
	$result = queryMySQL($query);

	$firstName = mysql_result($result, 0,'firstname');
	$lastName = mysql_result($result, 0,'lastname');
	$grade = mysql_result($result, 0,'grade');
	$advisor = mysql_result($result, 0,'advisor');
	$totalCredits = mysql_result($result, 0,'credits');


	$query = "SELECT events.eventname,events.eventdate,events.eventtime,events.defaultcredits,signups.noshow,signups.withdrew,signups.waitlist,signups.halfcredit
FROM signups
INNER JOIN events ON signups.eventid = events.id
WHERE studentid =$studentid and events.closed=true 
ORDER BY events.eventdate DESC ";

	$result = queryMySQL($query);

	?>
	
	<div class = "container">
		<h3>Student Profile for <?php echo $firstName.' '.$lastName?></h3>
		<hr style = "margin:0;">
		<br>
		

		<br>
		<p>
			<strong>Grade: </strong><?php echo $grade?>

		</p>
		<p>
			<strong>Advisor: </strong><?php echo $advisor?>

		</p>
		<h3 style = "text-align:center;text-decoration:underline">Event History</h3>

		<h4 style = "color:#FFC1C1">Red Text denotes a no show.</h4>

		<h4 style = "color:#BCED91">Green Text denotes a full credit.</h4>

		<h4 style = "color:#909090 ">Grey Text denotes a half credit.</h4>

		<h4 style = "color:#66FFFF">Blue Text denotes a withdrawal.</h4>

		<h4 style = "color:#FFCC66">Orange Text denotes a student on the waitlist.</h4>
		<table class = 'table table-bordered'>
			<tr>
				<th>Event Name</th>
				<th>Event Date</th>
				<th>Event Time</th>
				<th>Possible Credits Earned</th>
			</tr>
			<?php
			for ($i = 0;$i<mysql_num_rows($result);$i++){
				if (mysql_result($result, $i,'noshow')==1){
					echo "<tr bgcolor='#FFC1C1'>";


				}	
				else if (mysql_result($result,$i,'waitlist')>=1){
					echo "<tr bgcolor='FFCC66'>";
				}
				else if (mysql_result($result,$i,'withdrew')==1){
					echo "<tr bgcolor='66FFFF'>";
				}
				else if (mysql_result($result, $i,'halfcredit')==1){
					echo "<tr bgcolor='909090'>";
				}
				else{
					echo "<tr bgcolor='#BCED91'>";
				}
				echo "<td>".mysql_result($result, $i,'eventname');
				echo "<td>".mysql_result($result, $i,'eventdate');
				echo "<td>".mysql_result($result, $i,'eventtime');
				echo "<td>".mysql_result($result, $i,'defaultcredits');
				echo "</tr>";


			}

			echo "<tr>";
			echo "<td colspan='3' style='text-align:center'><strong>Total: </strong></td>";
			echo "<td>".$totalCredits."</td>";
			echo "</tr>";

			?>

		</table>



	</div>
	</body>
	</html>


<?php

}

?>
