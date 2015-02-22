<?php



/*
 * A form for creating new events
 *
 */

$username  = 'admin';
$password = 'admin';



if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
	if ($_SERVER['PHP_AUTH_USER'] == $username && $_SERVER['PHP_AUTH_PW'] == $password){
		include 'header.php';
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
                    //This javascript checks if the required fields have been filled

					$('button[type="submit"]').attr('disabled','disabled');
					$('input[type="text"]').keyup(function()
					{
						if ($("#eventname").val()!='' && $("#date").val()!='' && $("#time").val()!='' && $('#location').val()!='' && $('#credits').val()!='')
						{
							$('button[type="submit"]').removeAttr('disabled');
						}
						else{
							$('button[type="submit"]').attr('disabled','disabled');
						}
		
					});
					
					$('.selectpicker').selectpicker(); //Calls a function that turns <select> elements into pretty bootstrap ones (third-party library)
				
				});
			</script>
		
			</head>
END;

        //get the current year and the year after to put in the <option> for year
        $currentyear = date("Y");
        $nextyear = $currentyear + 1;


		echo <<<END
		<body>
		<div class="container">
		  <div class="row">
		    <div class = "margindiv">
			<h3 class = "muted">Create New Event</h3>
			<form class = "form-horizontal" action = "dashboard.php" method = "POST">
				<p>Note: * denotes a required field.</p>
				<fieldset>
				<legend>Event Creation Form</legend>
				<br>
				<div class = "control-group">
					<label class = "control-label" for ="input01">*Event Name:</label>
					<div class = "controls">
						<input id = "eventname" type = "text" class = "input-xlarge" id = "input01" placeholder = "Enter the name of the event" name = "eventname"/>
						<!--name check logic here-->
					</div>
				</div>
				<div class = "control-group">
					<label class = "control-label" for ="input01">*Location:</label>
					<div class = "controls">
						<input id = "location" type = "text" class = "input-xlarge" id = "input01" placeholder = "Location/Address" name = "location" />
						<!--name check logic here-->
					</div>
				</div>
				<div class = "control-group">
					<label class = "control-label" for ="input01">*Date:</label>
					<div class = "controls">
						<!--<input id = "date" type = "text" class = "input-xlarge" id = "input01" placeholder = "Date E.G August 1st 2014" name = "date" />-->
						<!--name check logic here-->
						<select  class="selectpicker" data-width="120px" name='date_month' >
					        <option value = 1>January</option>
					        <option value = 2>February</option>
							<option value = 3>March</option>
					        <option value = 4>April</option>
							<option value = 5>May</option>
					        <option value = 6>June</option>
							<option value = 7>July</option>
					        <option value = 8>August</option>
							<option value = 9>September</option>
					        <option value = 10>October</option>
							<option value = 11>November</option>
					        <option value = 12>December</option>
				    	</select>
						<select  class="selectpicker" data-width="80px" data-size='10' name='date_day'>
					        <option>1</option>
					        <option>2</option>
							<option>3</option>
					        <option>4</option>
							<option>5</option>
					        <option>6</option>
							<option>7</option>
					        <option>8</option>
							<option>9</option>
					        <option>10</option>
							<option>11</option>
					        <option>12</option>
							<option>13</option>
					        <option>14</option>
							<option>15</option>
					        <option>16</option>
							<option>17</option>
					        <option>18</option>
							<option>19</option>
					        <option>20</option>
							<option>21</option>
					        <option>22</option>
							<option>23</option>
					        <option>24</option>
							<option>25</option>
					        <option>26</option>
							<option>27</option>
					        <option>28</option>
							<option>29</option>
					        <option>30</option>
							<option>31</option>
				    	</select>
						<select  class="selectpicker" data-width="100px" data-size='3' name= 'date_year'>
					        <option>$currentyear</option>
					        <option>$nextyear</option>

	
				    	</select>
					</div>
				</div>
				<div class = "control-group">
					<label class = "control-label" for ="input01">*Time:</label>
					<div class = "controls">
						<input id = "time" type = "text" class = "input-xlarge" id = "input01" placeholder = "Time E.G 10:00-12:00 AM" name = "time" />
						<!--name check logic here-->
					</div>
				</div>
				<div class = "control-group">
					<label class = "control-label" for ="input01">*Credits:</label>
					<div class = "controls">
						<input id = "credits" type = "text" class = "input-xlarge" id = "input01" placeholder = "Credits" name = "credits" value = 1 />
					</div>
				</div>
				<div class = "control-group">
					<label class = "control-label" for ="input01">Max. Volunteers:</label>
					<div class = "controls">
						<input id = "max" type = "text" class = "input-xlarge" id = "input01" placeholder = "Max. Volunteers" name = "max" value = 0 />
					</div>
				</div>
				<div class = "control-group">
					<label class = "control-label" for ="input01">Supervisor(s):</label>
					<div class = "controls">
						<input id = "supervisor" type = "text" class = "input-xlarge" id = "input01" placeholder = "Name of supervisor" name = "supervisor" />
					</div>
				</div>
				<div class = "control-group">
					<label class = "control-label" for ="input01">Brief Description:</label>
					<div class = "controls">
						<textarea name = "description" cols "200" rows = "6" wrap ="type" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;">
A brief description of the event/important info. (not required)</textarea>
					</div>
				</div>
				<div class = "form-actions">
					<button name = "create" type = "submit" class = "btn btn-primary">Create Event!</button>
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
else{
	header('WWW-Authenticate: Basic realm="Restricted Section"');
	header('HTTP/1.0 401 Unauthorized');
	die("Please enter your username and password");
}


?>
</body>
</html>
