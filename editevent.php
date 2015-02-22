<?php
$username  = 'admin';
$password = 'admin';



if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
	if ($_SERVER['PHP_AUTH_USER'] == $username && $_SERVER['PHP_AUTH_PW'] == $password){
		include 'header.php';
		if (isset($_POST['eventid']) && $_POST['eventid'] != ''){
			$eventid = $_POST['eventid'];
			$query = "SELECT * FROM events where id = $eventid";
			$result = queryMySql($query);
			
			$eventdate = explode('-', mysql_result($result, 0,'eventdate'));
			$eventname = mysql_result($result, 0,'eventname');
			$eventtime = mysql_result($result, 0,'eventtime');
			$max = mysql_result($result, 0,'max');
			$supervisor = mysql_result($result, 0,'supervisor');
			$description = mysql_result($result, 0,'description');
			$location = mysql_result($result, 0,'location');
			$credits = mysql_result($result, 0,'defaultcredits');
			echo <<<END
				<style type = "text/css">
					.margindiv{
						margin-left:60px;
					}
					#max, #credits{
						width: 5%
					}
	
				</style>
				<script>
					$(document).ready(function()
					{
						$('input[type="text"]').keyup(function()
						{
							//check if required fields have been filled in
							//if not filled in, disable the submit button
							if ($("#eventname").val()!='' && $("#date").val()!='' && $("#time").val()!='' && $('#location').val()!='' && $("#credits").val()!='')
							{
								$('button[type="submit"]').removeAttr('disabled');
							}
							else{
								$('button[type="submit"]').attr('disabled','disabled');
							}
	
						});
					
						$('.selectpicker').selectpicker();
					
					});
					
				</script>
	
				</head>
END;
	
			echo <<<END
			<body>
			<div class="container">
			  <div class="row">
			    <div class = "margindiv">
				<h3 class = "muted">Edit Event</h3>
				<form class = "form-horizontal" action = "dashboard.php" method = "POST">
					<p>Note: * denotes a required field.</p>
					<fieldset>
					<legend>Editing Event: $eventname</legend>
					<br>
					<div class = "control-group">
						<label class = "control-label" for ="input01">*Event Name:</label>
						<div class = "controls">
							<input id = "eventname" type = "text" class = "input-xlarge" id = "input01" placeholder = "Enter the name of the event" name = "eventname" value = '$eventname'/>
							<!--name check logic here-->
						</div>
					</div>
					<div class = "control-group">
						<label class = "control-label" for ="input01">*Location:</label>
						<div class = "controls">
							<input id = "location" type = "text" class = "input-xlarge" id = "input01" placeholder = "Location/Address" name = "location" value = '$location'/>
							<!--name check logic here-->
						</div>
					</div>
					<div class = "control-group">
						<label class = "control-label" for ="input01">*Date:</label>
						<div class = "controls">

END;
                        /*
                         * Set up the <select> for the date option (month, day, year)
                         *
                         */
                        echo "<select  class='selectpicker' data-width='120px' name='date_month' >";
						$default = $eventdate[1];
						$months = array('1'=>'January',
								'2'=>'February',
								'3'=>'March',
								'4'=>'April',
								'5'=>'May',
								'6'=>'June',
								'7'=>'July',
								'8'=>'August',
								'9'=>'September',
								'10'=>'October',
								'11'=>'November',
								'12'=>'December');
                        //load all the month options
						foreach ($months as $key=>$val){
							echo ($key == (int)$default) ? "<option selected=\"selected\" value=\"$key\">$val</option>":"<option value=\"$key\">$val</option>";
							
						}
						echo "</select>";
						echo "<select  class='selectpicker' data-width='80px' data-size='10' name='date_day'>";
						$default = $eventdate[2];

                        //lod all the day options
						for ($i = 0;$i<32;$i++){
							echo ($i == (int)$default) ? "<option selected=\"selected\">$i</option>" : "<option>$i</option>";
						}
					    echo"</select>";
						echo "<select  class='selectpicker' data-width='100px' data-size='3' name= 'date_year'>";
						$default = $eventdate[0];

                        //load all the year options
						for ($i = date('Y');$i<date('Y', strtotime('+5 year'));$i++){
							echo ($i == (int)$default) ? "<option selected=\"selected\">$i</option>" : "<option>$i</option>";
						}
						echo "</select>";   
						echo <<<END
						</div>
					</div>
					<div class = "control-group">
						<label class = "control-label" for ="input01">*Time:</label>
						<div class = "controls">
							<input id = "time" type = "text" class = "input-xlarge" id = "input01" placeholder = "Time E.G 10:00-12:00 AM" name = "time" value = '$eventtime' />
							<!--name check logic here-->
						</div>
					</div>
					
					<div class = "control-group">
						<label class = "control-label" for ="input01">*Credits:</label>
						<div class = "controls">
							<input id = "credits" type = "text" class = "input-xlarge" id = "input01" placeholder = "Credits" name = "credits" value = $credits />
					</div>
				</div>
					<div class = "control-group">
						<label class = "control-label" for ="input01">Max. Volunteers:</label>
						<div class = "controls">
							<input id = "max" type = "text" class = "input-xlarge" id = "input01" placeholder = "Max. Volunteers" name = "max" value = '$max'/>
						</div>
					</div>
					<div class = "control-group">
						<label class = "control-label" for ="input01">Supervisor(s):</label>
						<div class = "controls">
							<input id = "supervisor" type = "text" class = "input-xlarge" id = "input01" placeholder = "Name of supervisor" name = "supervisor" value = '$supervisor' />
						</div>
					</div>
					<div class = "control-group">
						<label class = "control-label" for ="input01">Brief Description:</label>
						<div class = "controls">
							<textarea name = "description" cols "200" rows = "6" wrap ="type" >
$description</textarea>
						</div>
					</div>
					<div class = "form-actions">
						<input type = "hidden" name = "eventid" value = "$eventid"/>
						<button name = "edit" type = "submit" class = "btn btn-primary">Save Changes</button>
						<button class = "btn">Nevermind</button>
					</div>
	
					</fieldset>
	
				</form>
				</div>
			</div>
			</div>
END;
	}
	}
}
else{
	header('WWW-Authenticate: Basic realm="Restricted Section"');
	header('HTTP/1.0 401 Unauthorized');
	die("Please enter your username and password");
}


?>
</body>
</html>
