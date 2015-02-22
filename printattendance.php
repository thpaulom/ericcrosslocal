<?php

/*
	opens a new tab and prints an attendance sheet

*/
$query = "";
$result = "";

if (isset($_POST['printattendance']) && isset($_POST['eventid'])){
		if ($_POST['eventid'] != ""){
			include 'functions.php';
			echo <<<END
			<script>
				window.print();
				window.onfocus=function(){ window.close();}
					
			</script>
			
END;
			
			$eventid = $_POST['eventid'];
			$query = "SELECT * from events where id=$eventid";
			$result = mysql_query($query);
			$eventname = mysql_result($result, 0,'eventname');
			echo <<<END
			<html>
			<head>
			<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
			</head>
			<body>
		    <div class="container">
		
		      
END;
			echo "<div class = 'container'>\n";
			
			echo "<h3>Volunteers for Event: $eventname</h3>";
			echo "<table class = 'table'>";
			echo <<<END
						<tr>
		      <th>First Name</th>
		      <th>Last Name</th>
		      <th>Grade</th>
			  <th>Advisor</th>
			  <th>Attended</th>
		</tr>
END;

			
			$query = "SELECT signups.id,signups.studentid,signups.extra, volunteers.firstname, volunteers.lastname, volunteers.grade, volunteers.advisor
FROM signups
INNER JOIN volunteers ON signups.studentid = volunteers.id
WHERE eventid =$eventid and waitlist=0 AND withdrew=0 and noshow=0
ORDER BY volunteers.lastname ASC ";


                $result = queryMySql($query);
			

			/*Created an array of students' lastname to their ids, and sorted it alphabetically by last name*/
			for ($i = 0;$i<mysql_num_rows($result);$i++){
				echo "<tr>";
				echo "<td height='25' id = 'firstname' >".mysql_result($result, $i,'firstname')."</td>";
				echo "<td id = 'lastname'>".mysql_result($result, $i,'lastname')."</td>";
				echo '<td >'.mysql_result($result, $i,'grade').'</td>';
				echo "<td >".mysql_result($result, $i,'advisor');
				echo "<td >YES / NO</td>";
				echo "</tr>";
			}


					
					
				echo "</table></div>";

		

		}
		else{
			echo "Error loading attendance list. Please try again.";
		}

		
}
else{
	echo "Error printing attendance list. Please try again.";
}
	



?>
<hr>
   
   
   <div class="footer">
        <p></p>
   </div>
      
      
      
</body>
</html>
