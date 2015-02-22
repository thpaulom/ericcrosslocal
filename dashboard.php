<?php

/*
	The dashboard is the main admin interface, where other admin actions are available
	most forms redirect to this page, and the if statements on this page serve to handle
	these actions 
*/
$username  = 'admin';
$password = 'admin';
$query = "";
$result = "";


if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){ //prompts admin username and password
	if ($_SERVER['PHP_AUTH_USER'] == $username && $_SERVER['PHP_AUTH_PW'] == $password){
		include 'header.php';
		//output some basic css and javascript

        ?>

		<style>
			.btn-export {
						display:inline;
						float:right;
					  background-color: hsl(110, 56%, 31%) !important;
					  background-repeat: repeat-x;
					  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#53c83c", endColorstr="#317b22");
					  background-image: -khtml-gradient(linear, left top, left bottom, from(#53c83c), to(#317b22));
					  background-image: -moz-linear-gradient(top, #53c83c, #317b22);
					  background-image: -ms-linear-gradient(top, #53c83c, #317b22);
					  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #53c83c), color-stop(100%, #317b22));
					  background-image: -webkit-linear-gradient(top, #53c83c, #317b22);
					  background-image: -o-linear-gradient(top, #53c83c, #317b22);
					  background-image: linear-gradient(#53c83c, #317b22);
					  border-color: #317b22 #317b22 hsl(110, 56%, 26%);
					  color: #fff !important;
					  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.33);
					  -webkit-font-smoothing: antialiased;
					}
			
		</style>

		<script>

		$(document).ready(function(){
			//required call to implement the selectpicker library
			$('.selectpicker').selectpicker();

		});

		</script>

        <?php
		

		/*Handler for 'editevent.php'
		 * This if statement is triggered when the user submits the form from editevent.php
		 * Changes the event details as described by the user
		 *
		 */

		if (isset($_POST['edit']) && isset($_POST['eventname']) && isset($_POST['eventid'])  && isset($_POST['time']) && isset($_POST['location'])){ //handles editevent.php form submit
			
			if ($_POST['eventid'] == null ||  $_POST['eventname'] == "" || $_POST['time'] == "" || $_POST['location'] == ""){
				echo "<script>alert('Error editing event. Please try again.');</script>";
			}
			else{
				$eventid = $_POST['eventid'];
                $eventname = sanitizeString($_POST['eventname']);
				$location = sanitizeString($_POST['location']);
				$eventdate = $_POST['date_year'].'-'.$_POST['date_month'].'-'.$_POST['date_day'];
				$eventtime = $_POST['time'];
				if (intval($_POST['max']) == null)
					$max = 0;
				else 
					$max = intval($_POST['max']);
				$supervisor = $_POST['supervisor'];
				$description = sanitizeString($_POST['description']);
				$credits = $_POST['credits'];


				$query = "UPDATE events SET eventname = '$eventname',location = '$location',eventdate = '$eventdate',eventtime = '$eventtime',max = $max,supervisor = '$supervisor',description = '$description', defaultcredits = $credits where id = $eventid ";
				$result = queryMySql($query);

                //Show a message and redirect user to refresh POST data
				echo "<body><script>bootbox.alert('Succesfully edited event: $eventname', function() {
				window.location.replace('dashboard.php');});</script>";
			}
		}

        /*
         * Handler for 'closeevent'
         * This if statement is triggered when the user clicks the 'Close Event' button on the eventinfo.php page
         * This will close an active event, but not delete its data from the database
         * Adds the defaultcredit amount to every student who participated in the event,
         * and sets the event as 'closed'
         */

		if (isset($_POST['closeevent'])){
			$eventid = $_POST['eventid'];
            $enteredpassword = sanitizeString($_POST['closeeventpassword']);

            if ($enteredpassword == "admin"){
                $query = "SELECT * FROM events WHERE id = $eventid";
                $result = queryMySql($query);
                $eventname = mysql_result($result, 0,'eventname');
                $credits = mysql_result($result, 0,'defaultcredits');
                $halfcredits = $credits/2;

                //students with full credit
                $query = "SELECT * FROM signups where eventid=$eventid and noshow=false and waitlist=0 and withdrew=0 and halfcredit=0";
                $result = queryMySql($query);
                $numrows = mysql_num_rows($result);
                for ($i = 0;$i<$numrows;$i++){
                    $studentid = mysql_result($result, $i,'studentid');
                    $query = "UPDATE volunteers SET credits = credits + $credits where id=$studentid"; //adds credits to students
                    $studenresult = queryMySql($query);
                }

                //students with half credit
                $query = "SELECT * FROM signups where eventid=$eventid and noshow=false and waitlist=0 and withdrew=0 and halfcredit=1";
                $result = queryMySql($query);
                $numrows = mysql_num_rows($result);
                for ($i = 0;$i<$numrows;$i++){
                    $studentid = mysql_result($result, $i,'studentid');
                    $query = "UPDATE volunteers SET credits = credits + $halfcredits where id=$studentid"; //adds credits to students
                    $studenresult = queryMySql($query);
                }

                $query = "UPDATE events SET closed=true WHERE id = '$eventid'";
                $result = queryMySql($query);

                echo "<body><script>bootbox.alert('Event: $eventname has been closed.', function() {
				window.location.replace('dashboard.php');});</script>";
            }else{
                echo "<body><script>bootbox.alert('Incorrect password - Please try again.');</script>";
            }
			

		}


        /*
         * Handler for 'delete event'
         * This if statement is triggered when the admin is viewing a closed event and clicks 'delete event'
         * This will delete all data related to the event from the databases 'events' and 'signups'
         *
         */

		if (isset($_POST['deleteevent'])){
			$eventid = $_POST['eventid'];
				
			$query = "SELECT * FROM events WHERE id = $eventid";
			 $result = queryMySql($query);

			 $eventname = mysql_result($result, 0,'eventname');
            $defaultcredits = mysql_result($result,0,'defaultcredits');

			$query = "SELECT * FROM signups where eventid=$eventid and noshow=FALSE and waitlist=0";
			$result = queryMySql($query);
			$numrows = mysql_num_rows($result);
            for ($i = 0;$i<$numrows;$i++){
                $studentid = mysql_result($result,0,'studentid');
                $query = "UPDATE volunteers SET credits = credits-$defaultcredits WHERE id=$studentid";
                queryMySql($query);



            }

			$query = "DELETE FROM signups where eventid=$eventid";
			$result = queryMySql($query);
			$query = "DELETE FROM events where id=$eventid";
			$result = queryMySql($query);
				
				
			echo "<body><script>bootbox.alert('Event: $eventname has been DELETED.', function() {
			window.location.replace('dashboard.php');});</script>";
		}
		
		/*
		 * Handler for newevent.php
		 * This if statement is triggered when the admin submits the form on newevent.php
		 * It will insert a new event into the 'events' database
		 */
		if (isset($_POST['eventname']) && isset($_POST['time']) && isset($_POST['location']) && isset($_POST['create']) && isset($_POST['credits'])){ //handles newevent.php form submission
			if ($_POST['eventname'] == "" ||  $_POST['time'] == "" || $_POST['location'] == "" && $_POST['credits'] == ""){
				echo "<script>alert('Error creating event. Please try again.');</script>";
			}
			else{
                $eventname = sanitizeString($_POST['eventname']);
                $location = sanitizeString($_POST['location']);
				
				$eventdate = $_POST[date_year].'-'.$_POST['date_month'].'-'.$_POST['date_day'];
				
				$eventtime = $_POST['time'];
				$max = intval($_POST['max']);
				$supervisor = $_POST['supervisor'];
				if ($_POST['description'] == "A brief description of the event/important info. (not required)")
					$description = "";
				else
					$description = sanitizeString($_POST['description']);
				
				$defaultcredits = $_POST['credits'];
				
				
				
				$query = "INSERT INTO events(eventname,eventdate,eventtime,max,supervisor,description,location,closed,defaultcredits)
						values('$eventname','$eventdate','$eventtime',$max,'$supervisor','$description','$location',false,$defaultcredits)";
				$result = queryMySql($query);
				echo "<script>window.location.replace('dashboard.php');</script>";
			}
		}

		//After all form submissions have been checked and handled, display a navbar and then a table of events
		echo <<<END


		    <div class="container">
		
		      <div class="masthead">
		        <h3 class="muted">Dashboard</h3>
		        <div class="navbar">
		          <div class="navbar-inner">
		            <div class="container">
		              <ul class="nav">
						<li><a href = "dashboard.php">Dashboard</a></li>
		                
		                <li ><a href = 'index.php'>Sign Up</a></li>
		              </ul>
		            </div>
		          </div>
		        </div><!-- /.navbar -->
		      </div>

		      <div class = 'container'>
END;

        //Create a button that allows the admin to create a new event

		echo "<h3>Student Profiles</h3><hr style = 'margin-bottom:10px;margin-top:0'>";
		 echo "<form action = 'studentinfo.php'><select id='studentpicker' name = 'studentid' class='selectpicker' data-width='200px' data-size='10' data-live-search='true' >";
        echo"<option value=default >Select one--</option>";

        $query = "SELECT * FROM volunteers  ORDER BY lastname ASC";
        $result = queryMySql($query);

        for ($i = 0;$i<mysql_num_rows($result);$i++){
            echo "<option value=".mysql_result($result,$i,'id').">".mysql_result($result, $i,'firstname').' '.mysql_result($result, $i,'lastname')."</option>";
        }





        echo "</select><br>";
        
        echo "<button type = 'submit' class = 'btn btn-primary'>View Profile</button>";

        echo "</form>";

		echo "<h3>Events</h3><hr style = 'margin-bottom:10px;margin-top:0'><a href = 'newevent.php'><button class='btn btn-primary' >Create New Event</button></a>";

        //Create a button that allows admin to download an excel file with all the information stored in the databases
        //Also, add a handler to the button that allows the admin to confirm his/her decision
        echo <<<END
			<form id="downloadexcel"  action = 'downloadexcel.php' method = 'post' >
					<input type = 'hidden' name = 'downloadexcel'/>
					<button style = 'margin-top:10px' type = 'submit' class = 'btn btn-success' name = 'downloadexcel'>Export Database to Excel</button>		
			</form>		

		<script>	
		$('#downloadexcel').submit(function(e) {
			e.preventDefault();
		    var currentForm = this;
            var pastEventsTableNumRows = $('#pastevents').find('tr').length;
            console.log(pastEventsTableNumRows);
            if (pastEventsTableNumRows == 1){
                bootbox.alert("There are not enough closed events to create an excel file!");

            }
            else{
                bootbox.confirm("Would you like to download current year\'s information into an excel file?", function(result) {
                    if (result) {
                        currentForm.submit();
                    }
                });
            }

		});
		</script>
			
END;
        //Output a table of active events
		echo <<<END
		<h4>Current</h4>
		<table id = 'currentevents' class = 'table'>
		<tr>
		      <th>Event Name</th>
			  <th>Location</th>
		      <th>Date</th>
		      <th>Time</th>
		      <th>Current/Max Volunteers</th>
		      <th>Supervisor</th>
		      <th>Details</th>
		</tr>
END;
		
		//select OPEN events ordered from most recent to those most farthest in the future
		$query = "SELECT * FROM events where closed=0  ORDER BY eventdate";
		
		$result = queryMySql($query);
		
		
		$months = array('1'=>'January',
				'2'=>'February',
				'3'=>'March,',
				'4'=>'April',
				'5'=>'May',
				'6'=>'June',
				'7'=>'July',
				'8'=>'August',
				'9'=>'September',
				'10'=>'October',
				'11'=>'November',
				'12'=>'December');
		
		$numrows = mysql_num_rows($result);
		//display each event as its own row in a table
		for ($i = 0;$i<$numrows;++$i){
			$eventid = mysql_result($result, $i,'id');
			echo"<tr>";
			echo '<td>'.mysql_result($result, $i,'eventname').'</td>';
			echo '<td>'.mysql_result($result, $i,'location').'</td>';
			
			$eventdate = explode('-', mysql_result($result, $i,'eventdate'));
			echo '<td>'.$months[(int)$eventdate[1]].' '.(int)$eventdate[2].', '.$eventdate[0].'</td>';
			
			echo '<td>'.mysql_result($result, $i,'eventtime').'</td>';
			if (mysql_result($result, $i,'max') == 0){
				echo "<td></td>";
			}
			else{
				//show a fraction of currently signed up volunteers to max volunteers
				$currentVolunteerQuery = "SELECT * from signups where eventid=$eventid and waitlist=0 and noshow=0 and withdrew=0 and halfcredit=0";
				$currentVolunteers = mysql_num_rows(queryMySql($currentVolunteerQuery));


				echo '<td>'.$currentVolunteers."/".mysql_result($result, $i,'max').'</td>';
			}
			echo '<td>'.mysql_result($result, $i,'supervisor').'</td>';

            /*
              * Output a button in the very last <td> that takes the user to eventinfo.php as an PAST event
              * The difference is the name of the hidden field submitted as a post value
              * An active event sends:<input type = "hidden" name = "eventid" value = "'.mysql_result($result, $i,'id').'"/>
              * A closed one sends: <input type = "hidden" name = "PAST_eventid" value = "'.mysql_result($result, $i,'id').'"/>
              */
			echo '<td><form action = "eventinfo.php" method = "POST"><input type = "hidden" name = "eventid" value = "'.mysql_result($result, $i,'id').'"/> 
					<button class = "btn" type = "submit">View Details</button></form>';
			echo "</tr>";
			
		}   


		//select all closed (past) events sorted by their date and output them to a table
		$query = "SELECT * FROM events where closed=1 ORDER by eventdate DESC";
		
		$result = queryMySql($query);
		
		echo "</table>";
		echo <<<END
		<h4>Closed</h4>
		<table id = 'pastevents' class = 'table'>
		<tr>
		      <th>Event Name</th>
			  <th>Location</th>
		      <th>Date</th>
		      <th>Time</th>
		      <th>Final/Max Volunteers</th>
		      <th>Supervisor</th>
		      <th>Details</th>
		</tr>
END;
		$numrows = mysql_num_rows($result);
		for ($i = 0;$i<$numrows;++$i){
			$eventid = mysql_result($result, $i,'id');
			echo"<tr>";
			echo '<td>'.mysql_result($result, $i,'eventname').'</td>';
			echo '<td>'.mysql_result($result, $i,'location').'</td>';
			
			$eventdate = explode('-', mysql_result($result, $i,'eventdate'));
			echo '<td>'.$months[(int)$eventdate[1]].' '.(int)$eventdate[2].', '.$eventdate[0].'</td>';
			
			echo '<td>'.mysql_result($result, $i,'eventtime').'</td>';
			if (mysql_result($result, $i,'max') == 0){
				echo "<td></td>";
			}
			else{
				//show a fraction of currently signed up volunteers to max volunteers
				$finalVolunteerQuery = "SELECT * from signups where eventid=$eventid and waitlist=0 and noshow=0 and withdrew=0 and halfcredit=0";
				$finalVolunteers = mysql_num_rows(queryMySql($finalVolunteerQuery));
				echo '<td>'.$finalVolunteers."/".mysql_result($result, $i,'max').'</td>';
			}
			echo '<td>'.mysql_result($result, $i,'supervisor').'</td>';


			echo '<td><form action = "eventinfo.php" method = "POST"><input type = "hidden" name = "eventid" value = "'.mysql_result($result, $i,'id').'"/>
					<button class = "btn" type = "submit">View Details</button></form>';
			echo "</tr>";
			
		}  
		echo "</table>";
		
		
		echo "</div>";
		
	}
	else{
		header('WWW-Authenticate: Basic realm="Restricted Section"');
		header('HTTP/1.0 401 Unauthorized');
		die("Authentication of user failed.");
	
	}
}
else{
	header('WWW-Authenticate: Basic realm="Restricted Section"');
	header('HTTP/1.0 401 Unauthorized');
	die("Authentication of user failed.");
}




?>
<hr>
   
   
   <div class="footer">
        <p></p>
   </div>
      
      
      
</body>
</html>
