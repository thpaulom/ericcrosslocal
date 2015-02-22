<?php
$username  = 'admin';
$password = 'admin';
$query = "";
$result = "";
$eventid = 0;


if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && (isset($_POST['eventid']) || isset($_POST['PAST_eventid']))) {
	if ($_SERVER['PHP_AUTH_USER'] == $username && $_SERVER['PHP_AUTH_PW'] == $password){

		if ($_POST['eventid'] != ""){
			include 'header.php';

            //get the event details like the id and the name from the eventid
            $eventid = $_POST['eventid'];
            $query = "SELECT * from events where id=$eventid";
            $result = mysql_query($query);
            $eventname = mysql_result($result, 0,'eventname');

            $isclosed = mysql_result($result,0,'closed');//get information regarding whether the event is closed or not


            /*
             *
             * CURRENT EVENT
             */

            if ($isclosed == 0){ //if the event is still active
                //CSS and Javascript
                echo <<<END
			<style>
				table {
					table-layout:fixed;

				}
				td {
					word-wrap:break-word;
				}
				

				


			</style>

            <script src="plugins/blockui.js"></script>
            <script>
				$(document).ready(function()
				{

				    $(document.body).on('click', 'td button.delete', function() {

						var id = $(this).attr('value');
						var data = 'id='+id+'&eventid='+$eventid;
						var parent = $(this).parent().parent();
						var firstName = parent.find('td:first-child').text();
                        var lastName = parent.find('td:nth-child(2)').text();

						bootbox.confirm("Are you sure you want to remove this volunteer from the list?", function(result) {
				        if (result) {
							$.ajax(
							{
								type: "POST",
								url:"ajax/delete.php",
								data:data,
								cache: false,
								success: function()
								{
									parent.fadeOut('slow', function()
									{
										$(this).remove();



									});

								}
							});

					   		 }
						});
                    });


					$('.noshow').click(function(e){
						e.preventDefault();

						var id = $(this).attr('value');
						var data = 'id='+id+'&eventid='+$eventid;
						var parent = $(this).parent().parent();
						var firstName = parent.find('td:first-child').text();
						var grade = parent.find('td:nth-child(3)').text();
                        var lastName = parent.find('td:nth-child(2)').text();

                        $.ajax({
                        	type:"POST",
                        	url:"ajax/noshow.php",
                        	data:data,
                        	cache:false,
                        	success:function()
                        	{
                        		parent.fadeOut('slow',function(){
                        			$(this).remove();
                        		});
								 $('#noshows tr:last').after('<tr><td height=\'100\'>'+firstName+'</td><td>'+lastName+'</td><td>'+grade+'</td><td></td><td><button  style = \'width:150px\' class = \'delete btn btn-danger\' value = '+id+'>Delete Volunteer</button></td></tr>');
								
                        	}


                        });
                        



					});

					$('.withdrew').click(function(e){
						e.preventDefault();

						var id = $(this).attr('value');
						var data = 'id='+id+'&eventid='+$eventid;
						var parent = $(this).parent().parent();
						var firstName = parent.find('td:first-child').text();
						var grade = parent.find('td:nth-child(3)').text();
                        var lastName = parent.find('td:nth-child(2)').text();

                        $.ajax({
                        	type:"POST",
                        	url:"ajax/withdrew.php",
                        	data:data,
                        	cache:false,
                        	success:function()
                        	{
                        		parent.fadeOut('slow',function(){
                        			$(this).remove();
                        		});
								 $('#withdrew tr:last').after('<tr><td height=\'100\'>'+firstName+'</td><td>'+lastName+'</td><td>'+grade+'</td><td></td><td><button  style = \'width:150px\' class = \'delete btn btn-danger\' value = '+id+'>Delete Volunteer</button></td></tr>');
								
                        	}


                        });
                        



					});

					$('.halfcredit').click(function(e){
						e.preventDefault();

						var id = $(this).attr('value');
						var data = 'id='+id+'&eventid='+$eventid;
						var parent = $(this).parent().parent();
						var firstName = parent.find('td:first-child').text();
						var grade = parent.find('td:nth-child(3)').text();
                        var lastName = parent.find('td:nth-child(2)').text();

                        $.ajax({
                        	type:"POST",
                        	url:"ajax/halfcredit.php",
                        	data:data,
                        	cache:false,
                        	success:function()
                        	{
                        		parent.fadeOut('slow',function(){
                        			$(this).remove();
                        		});
								 $('#halfcredit tr:last').after('<tr><td height=\'100\'>'+firstName+'</td><td>'+lastName+'</td><td>'+grade+'</td><td></td><td><button  style = \'width:150px\' class = \'delete btn btn-danger\' value = '+id+'>Delete Volunteer</button></td></tr>');
								
                        	}


                        });
                        



					});
					
					$('.comment').click(function(e){
						e.preventDefault();

						var id = $(this).attr('value');
						var parent = $(this).parent().parent();
						var firstName = parent.find('td:first-child').text();
						var grade = parent.find('td:nth-child(3)').text();
                        var lastName = parent.find('td:nth-child(2)').text();

                        bootbox.prompt("Write a Comment for Volunteer", function(result){
                        	if (result == null){

                        	}
                        	else{

								var data = 'id='+id+'&comment=\''+result+'\'&eventid='+$eventid;

                        		$.ajax({
		                        	type:"POST",
		                        	url:"ajax/comment.php",
		                        	data:data,
		                        	cache:false,
		                        	success:function()
		                        	{
		                        		bootbox.alert("Comment Saved!");
										
		                        	}


		                        });
                        	}
                        });


					

                        
                        



					});


					$('.promote').click(function(e){
						e.preventDefault();

						var id = $(this).attr('value');
						var data = 'id='+id+'&eventid='+$eventid;
						var parent = $(this).parent().parent();
						var firstName = parent.find('td:first-child').text();
						var grade = parent.find('td:nth-child(3)').text();
                        var lastName = parent.find('td:nth-child(2)').text();

                        $.ajax({
                        	type:"POST",
                        	url:"ajax/promote.php",
                        	data:data,
                        	cache:false,
                        	success:function()
                        	{
                        		parent.fadeOut('slow',function(){
                        			$(this).remove();
                        		});
								 $('#volunteers tr:last').after('<tr><td>'+firstName+'</td><td>'+lastName+'</td><td>'+grade+'</td><td></td><td><button style = \'width:150px\' class = \'delete btn btn-danger\' value = '+id+'>Delete Volunteer</button></td></tr>');
								
                        	}


                        });
                        



					});

					
					

					$('#closeevent').submit(function(e) {
						e.preventDefault();
					    var currentForm = this;
					    bootbox.confirm("Are you sure you want to close this event? This change is permanent!", function(result) {
					        if (result) {

								

					            currentForm.submit();
					        }
					    });
					});


					//required call to implement the selectpicker library
					$('.selectpicker').selectpicker();


					$('#addstudent').click(function(e){
						e.preventDefault();
						var id = $('#studentpicker').val();
						var parent = $('#volunteers');

						if (id!='default'){
							var data = 'id='+id+'&eventid='+$eventid;
							info = new Array();
							$.ajax(
							{
								type: "POST",
								url:"ajax/addvolunteer.php",
								data:data,
								cache: false,
								success: function(data)
								{
									info = JSON.parse(data);
									if (info.duplicate == true){
									    bootbox.alert("This student is already registered for this event!");
									}else{
                                        $('#volunteers tr:last').after('<tr><td>'+info.firstname+'</td><td>'+info.lastname+'</td><td>'+info.grade+'</td><td></td><td><button  style=\'width:150px\' class = \'delete btn btn-danger\' value = '+id+'>Delete Volunteer</button></td></tr>');


									}
								}
							});



						}


					});




					/*These two scripts make sure that when an ajax request is runing, the user cannot interact with the
                    *webpage and displays a loading screen
                    */
                    $(document).ajaxSend(function(event, request, settings) {
                        $.blockUI({ message: '<h1>Loading...<img src="images/ajax-loader.gif" /> </h1>' });
                    });
                    $(document).ajaxComplete(function(event, request, settings) {
                        $.unblockUI();
                    });

				});



			</script>


			</head>


			<body>

		    <div class="container">

		      <div class="masthead">
		        <h3 class="muted">Event details for: $eventname</h3>
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



            <div class = 'container'>\n


			<form style = 'display:inline;' action = 'editevent.php' method = 'post'>
				<input type  = 'hidden' name = 'eventid' value = '$eventid'/>
				<button type = 'submit' class = 'btn btn-primary'>Edit Event Details</button>
			</form>
			<form target = "_blank" style = 'display:inline;' action = 'printattendance.php' method = 'post'>
					<input type = "hidden" name = "eventid" value = "$eventid"/>
					<button type = 'submit' class = 'btn btn-success' name = 'printattendance'>Print Attendance Sheet</button>
			</form>
			<form target = "_blank" style = 'display:inline;' action = 'emailvolunteers.php' method = 'post'>
					<input type = "hidden" name = "eventid" value = "$eventid"/>
					<button  name = 'emailvolunteers' class = 'btn btn-info' onsubmit = ''>Email Students & Parents</button>
			</form>

			<form  id='closeevent' style = 'display:inline;float:right' action = 'closeevent.php' method = 'post' >
					<input type = "hidden" name = "eventid" value = "$eventid"/>
					<input type = "hidden" name = "closeevent" value = "closeevent"/>
					<button type = 'submit' class = 'btn btn-danger'>Close Event</button>
			</form>



		<h3>Volunteers:</h3>

END;

	/*
                 * Create the <select> box that uses the "selectpicker" library
                 * and add the names of all students who are not volunteered to the <select>
                 */

                echo "<select id='studentpicker' class='selectpicker' data-width='200px' data-size='10' data-live-search='true' >";
                echo"<option value=default >Select one--</option>";

                $query = "SELECT * FROM volunteers  ORDER BY lastname ASC";
                $result = queryMySql($query);

                $numvolunteers=mysql_num_rows($result);

                for ($i = 0;$i<$numvolunteers;$i++){
                    echo "<option value=".mysql_result($result,$i,'id').">".mysql_result($result, $i,'firstname').' '.mysql_result($result, $i,'lastname')."</option>";
                }





                echo "</select>";
                //create the button that when you clicks it adds the curretly selected student in the <select> to the main
                //volunteer list. There is javascript that handles this action
                echo <<<END
			<form id='addstudentform' style = 'display:block;' action = 'eventinfo.php' method = 'post'>
				<input type  = 'hidden' name = 'eventid' value = '$eventid'/>
				<button id = 'addstudent' type = 'submit' class = 'btn btn-success' >Add Student</button>
			</form>

END;


		/*
                 * After main HTML, CSS, and Javascript are done
                 * Output all the volunteers that are signed up in the <table>
                 * Add a <select> that uses the "selectpicker" library that allows the admin to pick students to add to the event
                 *
                 */


                $query = "SELECT signups.id,signups.studentid,signups.extra, volunteers.firstname, volunteers.lastname, volunteers.grade
FROM signups
INNER JOIN volunteers ON signups.studentid = volunteers.id
WHERE eventid =$eventid and noshow!=true and waitlist=0 and withdrew=0 and halfcredit=0
ORDER BY volunteers.lastname ASC ";

                $result = queryMySql($query);
                $numvolunteers = mysql_num_rows($result);

          echo <<<END

          <h4>Total: $numvolunteers</h4>
		<table id = 'volunteers' class = 'table'>
		<tr>
		      <th>First Name</th>
		      <th>Last Name</th>
		      <th>Grade</th>
		      <th>Notes</th>
			  <th>Actions</th>
		</tr>
END;

                


                for ($i = 0;$i<$numvolunteers;$i++){


                    //first and last name of student
                	$studentid = mysql_result($result, $i,'signups.studentid');

                    echo "<tr><td id = 'firstname' height = '100'>".mysql_result($result, $i,'firstname')."</td>";
                    echo "<td id = 'lastname'>".mysql_result($result, $i,'lastname')."</td>";
                    echo '<td>'.mysql_result($result, $i,'grade').'</td>'; //grade of student


                    echo '<td>'.mysql_result($result, $i,'extra').'</td>';//extra information of student

                    echo "<td >
                    <button style ='width:150px;margin-bottom:2px' class = 'comment btn btn-primary' value = '".mysql_result($result,$i,'studentid')."'>Comment</button>
                    <br>
                    <button style ='width:150px;margin-bottom:2px' class = 'withdrew btn btn-info' value = '".mysql_result($result,$i,'studentid')."'>Withdrew</button>
                    <br>
                    <button style ='width:150px;margin-bottom:2px' class = 'halfcredit btn btn-success' value = '".mysql_result($result,$i,'studentid')."'>Half Credit</button>
                    <br>
                    <button id = 'noshow' style = 'width:150px;margin-bottom:2px' class='noshow btn btn-warning' value = '".mysql_result($result, $i,'studentid')."'>No Show</button>
					<br>
					<button style ='width:150px;margin-bottom:2px' class = 'delete btn btn-danger' value = '".mysql_result($result,$i,'studentid')."'>Delete Volunteer</button>
                    </td>";//create delete button with the id of the student as a value
                    echo "</tr>";


                }
                echo "</table>";



                $query ="SELECT signups.id,signups.studentid,signups.extra,volunteers.firstname,volunteers.lastname,volunteers.grade,volunteers.credits 
               	from signups 
               	inner join volunteers on signups.studentid = volunteers.id where eventid=$eventid and signups.waitlist!=0 
               	order by signups.waitlist ASC";

                $result = queryMySql($query);

                $numwaitlist = mysql_num_rows($result);
               ?>

               <h3>Waitlist</h3>
               <h4>Total: <?php echo $numwaitlist?></h4>
            <table id = 'waitlist' class = 'table'>
            	<tr>
            		<th>First Name</th>
            		<th> Last Name</th>
        			<th>Grade</th>
        			<th>Current # of Credits</th>
        			<th>Actions</th>
        		</tr>

        		<?php
               	

                for ($i = 0;$i<$numwaitlist;$i++){
                	//first and last name of student
                    echo "<tr><td id = 'firstname' height = '100'>".mysql_result($result, $i,'firstname')."</td>";
                    echo "<td id = 'lastname'>".mysql_result($result, $i,'lastname')."</td>";
                    echo '<td>'.mysql_result($result, $i,'grade').'</td>'; //grade of student

                    echo '<td>'.mysql_result($result, $i,'credits').'</td>';//extra information of student

                    echo "<td >
                    <button style ='width:150px;margin-bottom:2px' class = 'promote btn btn-success' value = '".mysql_result($result,$i,'studentid')."'>Promote</button></td>";//create delete button with the id of the student as a value
                    echo "</tr>";

                }

                $query ="SELECT signups.id,signups.studentid,signups.extra,volunteers.firstname,volunteers.lastname,volunteers.grade from signups inner join volunteers on signups.studentid = volunteers.id where eventid=$eventid and halfcredit=TRUE order by volunteers.lastname ASC";
                $result = queryMySql($query);
                $numhalfcredit = mysql_num_rows($result);
                ?> 
            </table>


			<h3>Half Credit</h3>
               <h4>Total: <?php echo $numhalfcredit?></h4>
               <table id = 'halfcredit' class = 'table'>
               	<tr>
               			<th>First Name</th>
               			<th>Last Name</th>
               			<th>Grade</th>
               			<th>Notes</th>
               			<th>Actions</th>
               	</tr>

               	<?php
               	

                for ($i = 0;$i<$numhalfcredit;$i++){
                	//first and last name of student
                    echo "<tr><td id = 'firstname' height = '100'>".mysql_result($result, $i,'firstname')."</td>";
                    echo "<td id = 'lastname'>".mysql_result($result, $i,'lastname')."</td>";
                    echo '<td>'.mysql_result($result, $i,'grade').'</td>'; //grade of student

                    echo '<td>'.mysql_result($result, $i,'extra').'</td>';//extra information of student

                    echo "<td ><button style ='width:150px;margin-bottom:2px' class = 'delete btn btn-danger' value = '".mysql_result($result,$i,'studentid')."'>Delete Volunteer</button></td>";//create delete button with the id of the student as a value
                    echo "</tr>";

                }
                
                $query ="SELECT signups.id,signups.studentid,signups.extra,volunteers.firstname,volunteers.lastname,volunteers.grade from signups inner join volunteers on signups.studentid = volunteers.id where eventid=$eventid and withdrew=TRUE order by volunteers.lastname ASC";
                $result = queryMySql($query);
                $numwithdrew = mysql_num_rows($result);
                ?>



            </table>



			<h3>Withdrew</h3>
               <h4>Total: <?php echo $numwithdrew?></h4>
               <table id = 'withdrew' class = 'table'>
               	<tr>
               			<th>First Name</th>
               			<th>Last Name</th>
               			<th>Grade</th>
               			<th>Notes</th>
               			<th>Actions</th>
               	</tr>

               	<?php
               	

                for ($i = 0;$i<$numwithdrew;$i++){
                	//first and last name of student
                    echo "<tr><td id = 'firstname' height = '100'>".mysql_result($result, $i,'firstname')."</td>";
                    echo "<td id = 'lastname'>".mysql_result($result, $i,'lastname')."</td>";
                    echo '<td>'.mysql_result($result, $i,'grade').'</td>'; //grade of student

                    echo '<td>'.mysql_result($result, $i,'extra').'</td>';//extra information of student

                    echo "<td ><button style ='width:150px;margin-bottom:2px' class = 'delete btn btn-danger' value = '".mysql_result($result,$i,'studentid')."'>Delete Volunteer</button></td>";//create delete button with the id of the student as a value
                    echo "</tr>";

                }
                $query ="SELECT signups.id,signups.studentid,signups.extra,volunteers.firstname,volunteers.lastname,volunteers.grade from signups inner join volunteers on signups.studentid = volunteers.id where eventid=$eventid and noshow=TRUE order by volunteers.lastname ASC";
                $result = queryMySql($query);
                $numnoshow = mysql_num_rows($result);
                ?>



            </table>



               <h3>No Shows</h3>
               <h4>Total: <?php echo $numnoshow?></h4>
               <table id = 'noshows' class = 'table'>
               	<tr>
               			<th>First Name</th>
               			<th>Last Name</th>
               			<th>Grade</th>
               			<th>Notes</th>
               			<th>Actions</th>
               	</tr>

               	<?php
               	

                for ($i = 0;$i<$numnoshow;$i++){
                	//first and last name of student
                    echo "<tr><td id = 'firstname' height = '100'>".mysql_result($result, $i,'firstname')."</td>";
                    echo "<td id = 'lastname'>".mysql_result($result, $i,'lastname')."</td>";
                    echo '<td>'.mysql_result($result, $i,'grade').'</td>'; //grade of student

                    echo '<td>'.mysql_result($result, $i,'extra').'</td>';//extra information of student

                    echo "<td ><button style ='width:150px;margin-bottom:2px' class = 'delete btn btn-danger' value = '".mysql_result($result,$i,'studentid')."'>Delete Volunteer</button></td>";//create delete button with the id of the student as a value
                    echo "</tr>";

                }

                ?>



            </table>

            

            </div>


            <?php


            }

        /*
         *
         *
         * CLOSED EVENT
         *
         *
         *
         */


		else { //Viewing a past event so event is closed

				$query = "SELECT signups.id,signups.studentid,signups.extra,signups.comment, volunteers.firstname, volunteers.lastname, volunteers.grade
FROM signups
INNER JOIN volunteers ON signups.studentid = volunteers.id
WHERE eventid =$eventid and noshow!=true and waitlist=0 and withdrew=0 and halfcredit=0
ORDER BY volunteers.lastname ASC ";
            $result = queryMySql($query);
            $numvolunteers = mysql_num_rows($result);

			echo <<<END
			<style>
				table {
					table-layout:fixed;
			
				}
				td {
					word-wrap:break-word;
				}
				


			</style>
			<script>
				$(document).ready(function(){
					$('#deleteevent').submit(function(e) {
						e.preventDefault();
					    var currentForm = this;
					    bootbox.confirm("Are you sure you want to DELETE this event? All data will be lost!", function(result) {
					        if (result) {
					            currentForm.submit();
					        }
					    });
					});		
					
					
				});
					
					
			</script>
			
			
			</head>


			<body>
		
		    <div class="container">
		
		      <div class="masthead">
		        <h3 class="muted">Event details for: $eventname</h3>
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

            <div class = 'container'>\n

			<!-- <form  id='deleteevent' style = 'display:inline' action = 'dashboard.php' method = 'post' >
					<input type = "hidden" name = "eventid" value = "$eventid"/>
					<input type = "hidden" name = "deleteevent"/>
					<button type = 'submit' class = 'btn btn-danger'>Delete Event</button>
			</form> -->
		

			
		
		<h3>Volunteers:</h3>
		<h4>Total: $numvolunteers</h4>
		<table id = 'volunteers' class = 'table'>
		<tr>
		      <th>First Name</th>
		      <th>Last Name</th>
		      <th>Grade</th>
		      <th>Comment</th>
		</tr>
END;


            


            for ($i = 0;$i<$numvolunteers;$i++){


                //first and last name of student
                echo "<tr><td id = 'firstname' height = '100'>".mysql_result($result, $i,'firstname')."</td>";
                echo "<td id = 'lastname'>".mysql_result($result, $i,'lastname')."</td>";
                echo '<td>'.mysql_result($result, $i,'grade').'</td>'; //grade of student


                echo '<td>'.mysql_result($result, $i,'comment').'</td>';//extra information of student

                //echo "<td ><button  class = 'delete btn btn-delete' value = '".mysql_result($result,$i,'studentid')."'>Delete Volunteer</button></td>";//create delete button with the id of the student as a value
                echo "</tr>";


            }
            echo "</table>";

            	$query ="SELECT signups.id,signups.studentid,signups.extra,volunteers.firstname,volunteers.lastname,volunteers.grade,volunteers.credits 
               	from signups 
               	inner join volunteers on signups.studentid = volunteers.id where eventid=$eventid and signups.waitlist!=0 
               	order by signups.waitlist ASC";

                $result = queryMySql($query);
                $numwaitlist = mysql_num_rows($result);

            ?>


            	<h3>Waitlist</h3>
            	<h4>Total: <?php echo $numwaitlist?></h4>
            <table id = 'waitlist' class = 'table'>
            	<tr>
            		<th>First Name</th>
            		<th> Last Name</th>
        			<th>Grade</th>
        			<th>Current # of Credits</th>
        		</tr>

        		<?php
               	

                for ($i = 0;$i<$numwaitlist;$i++){
                	//first and last name of student
                    echo "<tr><td id = 'firstname' height = '100'>".mysql_result($result, $i,'firstname')."</td>";
                    echo "<td id = 'lastname'>".mysql_result($result, $i,'lastname')."</td>";
                    echo '<td>'.mysql_result($result, $i,'grade').'</td>'; //grade of student

                    echo '<td>'.mysql_result($result, $i,'credits').'</td>';//extra information of student
                    echo "</tr>";

                }




                $query ="SELECT signups.id,signups.studentid,signups.extra, signups.comment, volunteers.firstname,volunteers.lastname,volunteers.grade from signups inner join volunteers on signups.studentid = volunteers.id where eventid=$eventid and halfcredit=TRUE order by volunteers.lastname ASC";
                $result = queryMySql($query);
                $numhalfcredit = mysql_num_rows($result);
                ?>

            
            </table>

				<h3>Half Credit</h3>
               <h4>Total: <?php echo $numhalfcredit?></h4>
               <table id = 'halfcredit' class = 'table'>
               	<tr>
               			<th>First Name</th>
               			<th>Last Name</th>
               			<th>Grade</th>
               			<th>Comment</th>
               	</tr>

               	<?php
               	

                for ($i = 0;$i<$numhalfcredit;$i++){
                	//first and last name of student
                    echo "<tr><td id = 'firstname' height = '100'>".mysql_result($result, $i,'firstname')."</td>";
                    echo "<td id = 'lastname'>".mysql_result($result, $i,'lastname')."</td>";
                    echo '<td>'.mysql_result($result, $i,'grade').'</td>'; //grade of student

                    echo '<td>'.mysql_result($result, $i,'comment').'</td>';//comment information of student

                    echo "</tr>";

                }
                 $query ="SELECT signups.id,signups.studentid,signups.extra,volunteers.firstname,volunteers.lastname,volunteers.grade from signups inner join volunteers on signups.studentid = volunteers.id where eventid=$eventid and withdrew=TRUE order by volunteers.lastname ASC";
                $result = queryMySql($query);
                $numnoshow = mysql_num_rows($result);
                ?>



            </table>



               <h3>Withdrew</h3>
               <h4>Total: <?php echo $numnoshow?></h4>
               <table id = 'noshows' class = 'table'>
               	<tr>
               			<th>First Name</th>
               			<th>Last Name</th>
               			<th>Grade</th>
               			<th>Notes</th>
               	</tr>

               	<?php
               	

                for ($i = 0;$i<$numnoshow;$i++){
                	//first and last name of student
                    echo "<tr><td id = 'firstname' height = '100'>".mysql_result($result, $i,'firstname')."</td>";
                    echo "<td id = 'lastname'>".mysql_result($result, $i,'lastname')."</td>";
                    echo '<td>'.mysql_result($result, $i,'grade').'</td>'; //grade of student

                    echo '<td>'.mysql_result($result, $i,'extra').'</td>';//extra information of student

                    echo "</tr>";

                }
                 $query ="SELECT signups.id,signups.studentid,signups.extra,volunteers.firstname,volunteers.lastname,volunteers.grade from signups inner join volunteers on signups.studentid = volunteers.id where eventid=$eventid and noshow=TRUE order by volunteers.lastname ASC";
                $result = queryMySql($query);
                $numnoshow = mysql_num_rows($result);
                ?>



            </table>

			</table>


               <h3>No Shows</h3>
               <h4>Total: <?php echo $numnoshow?></h4>
               <table id = 'noshows' class = 'table'>
               	<tr>
               			<th>First Name</th>
               			<th>Last Name</th>
               			<th>Grade</th>
               			<th>Notes</th>
               	</tr>

               	<?php
               	

                for ($i = 0;$i<$numnoshow;$i++){
                	//first and last name of student
                    echo "<tr><td id = 'firstname' height = '100'>".mysql_result($result, $i,'firstname')."</td>";
                    echo "<td id = 'lastname'>".mysql_result($result, $i,'lastname')."</td>";
                    echo '<td>'.mysql_result($result, $i,'grade').'</td>'; //grade of student

                    echo '<td>'.mysql_result($result, $i,'extra').'</td>';//extra information of student

                    echo "</tr>";

                }

                ?>



            </table>



        </div>

            <?php

	        }
        }
    }
	else{
		header('WWW-Authenticate: Basic realm="Restricted Section"');
		header('HTTP/1.0 401 Unauthorized');
		die("Please enter your username and password");
	
	}
}
else{
	echo "Error loading page. Please return to the <a href = 'index.php'>home page</a>.";
}




?>
<hr>
   
   
   <div class="footer">
        <p></p>
   </div>
      
      
      
</body>
</html>
