<?php

/*
	index.php is the main page for students/clients.
    If not logged in, it will redirect you to a login.php
    If logged in, it will output a table of events for students to choose from. This table is populated by data from the MySQL database


*/



    
include 'header.php';





?>



    <style>
    #loading-indicator {
          position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -32px; /* -1 * image width / 2 */
            margin-top: -32px;  /* -1 * image height / 2 */
            display: block;
    }

    p { /*this is so the description text that appears in the signup dialog does not keep on going horizontally forever*/
        word-wrap: break-word;
    }

    .warningtext{
        color: #ff0000;
    }

    </style>
    <script src="plugins/blockui.js"></script>
	<script>

        /*
        * Setup the javascript needed to run the page, including ajax calls etc.
        *
        */

		$(document).ready(function(){ //Runs these scripts when document is loaded 

			$('td button.signup').click(function(){ //sets a click handler for the signup button next to events
			    var button = this;



                var id = <?php echo "$userid"?>; //userid is a session variable initialized in headers.php


                var eventname = $(this).parent().siblings(":first").text(); //selects the parent, the row, and then gets the text from the first <td>
                var eventid = $(this).parent().find('input[type=hidden]').val(); //selects the parent (<td>) and gets the value of the hidden input text inside it.
                //var data = 'id='+id+'&eventid='+eventid; //initializes the data variable that will be sent as parameters to ajax
                var eventinfo = $(this).parent().siblings(":first").find('input[type=hidden]').val(); //selects the hidden input in the first <td> in the <tablee>
                var data = 'eventid='+eventid;


                var status = $(this).val();
                if (status == 'waitlist'){
                    bootbox.confirm("You are signing up to be waitlisted for this event! There is no guarantee that you will be able to atttend. Continue sign up?",function(result){
                        if (result){
                             $.ajax( //sends ajax request
                            {
                                type: "POST",
                                url:"ajax/getvolunteers.php",
                                data:data,
                                cache: false,
                                success: function(data)
                                {
                                    volunteers = JSON.parse(data);
                                    joinString = volunteers.join("<br>");
                                    volunteerString = "<p>"+joinString+"</p>";



                                    bootbox.confirm("\
                                    <h3>Signing up for event: "+eventname+"</h3>"+
                                    "<p class = 'warningtext'><b>Note: You will not be able to cancel your signup after you hit 'OK'! Please contact a student leader for your project or Ms. Roff at debbie.roff@kinkaid.org to cancel signups 24 HOURS IN ADVANCE. Remember, if you sign up for an event, you are making a commitment and we will expect you to fulfill it! </b></p><hr>"+
                                    "<h6>Event Information:</h6><p>"+eventinfo+"</p><hr>\
                                    <h6>Current Volunteers:</h6>"+
                                    volunteerString+
                                    "<hr><p>Please write anything you would like us to know about here. This field is NOT required.</p><br><textarea id = 'extrainfo' \
                                    style='width:80%' rows = 5 wrap ='type' placeholder='E.G. I might be late'></textarea>",function(result){
                                        if (result){
                                            var notes = $('#extrainfo').val();
                                            if (notes == ''){ //if user did not enter anything
                                                var data = 'id='+id+'&eventid='+eventid;
                                            }
                                            else{ //if the user did enter something add it to the data sent to the ajax request
                                                var data = 'id='+id+'&eventid='+eventid+'&notes='+notes;
                                            }
                                            $.ajax( //sends ajax request
                                                {
                                                    type: "POST",
                                                    url:"ajax/signupajax.php",
                                                    data:data,
                                                    cache: false,
                                                    success: function(data)
                                                    {
                                                        // info = JSON.parse(data);
                                                        // var waitlistNum = info.waitlist;
                                                        // console.log(waitlistNum);
                                                        button.disabled = true; //disables button so that user cannot sign up again
                                                        var firstName = '<?php echo $ufirstname?>';
                                                        bootbox.alert("Thanks for signing up, "+firstName+"! Your number on the waitlist is: "+data+"! If you are promoted from the waitlist you will receive an email before the event!"); //alerts user that he/she has signed up succesfully

                                                    }
                                            });
                                        }
                                    });





                                }
                            });
                        }
                        else{
                            //donothing
                        }
                    
                    });
                }
                else{
                     $.ajax( //sends ajax request
                    {
                        type: "POST",
                        url:"ajax/getvolunteers.php",
                        data:data,
                        cache: false,
                        success: function(data)
                        {
                            volunteers = JSON.parse(data);
                            joinString = volunteers.join("<br>");
                            volunteerString = "<p>"+joinString+"</p>";



                            bootbox.confirm("\
                            <h3>Signing up for event: "+eventname+"</h3>"+
                            "<p class = 'warningtext'><b>Note: You will not be able to cancel your signup after you hit 'OK'! Please contact a student leader for your project or Ms. Roff at debbie.roff@kinkaid.org to cancel signups 24 HOURS IN ADVANCE. Remember, if you sign up for an event, you are making a commitment and we will expect you to fulfill it! </b></p><hr>"+
                            "<h6>Event Information:</h6><p>"+eventinfo+"</p><hr>\
                            <h6>Current Volunteers:</h6>"+
                            volunteerString+
                            "<hr><p>Please write anything you would like us to know about here. This field is NOT required.</p><br><textarea id = 'extrainfo' \
                            style='width:80%' rows = 5 wrap ='type' placeholder='E.G. I might be late'></textarea>",function(result){
                                if (result){
                                    var notes = $('#extrainfo').val();
                                    if (notes == ''){ //if user did not enter anything
                                        var data = 'id='+id+'&eventid='+eventid;
                                    }
                                    else{ //if the user did enter something add it to the data sent to the ajax request
                                        var data = 'id='+id+'&eventid='+eventid+'&notes='+notes;
                                    }
                                    $.ajax( //sends ajax request
                                        {
                                            type: "POST",
                                            url:"ajax/signupajax.php",
                                            data:data,
                                            cache: false,
                                            success: function()
                                            {
                                                button.disabled = true; //disables button so that user cannot sign up again
                                                var firstName = '<?php echo $ufirstname?>';
                                                bootbox.alert("Thanks for signing up, "+firstName+"!"); //alerts user that he/she has signed up succesfully

                                            }
                                    });
                                }
                            });





                        }
                    });
                }

               





				$(".tooltip").tooltip();

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


<?php

/*
 * Check if the user is logged in, then output main html
 */






if ($loggedin == false || $loggedin == null){ //if user is not logged in, redirect user to login.php, and store the current url so that he can be redirected back from login.php
	$_SESSION['redirecturl'] = "index.php"; //Session variables are essentially globals
	echo "<script>window.location.replace('login.php');</script>";//javascript redirect
}


?>

		    <div class="container">




		      <div class="masthead">
                <?php echo "<p style='float:right;' >Welcome, $ufirstname $ulastname
                <br>
<a href = 'studentinfo.php'>My Community Service History</a>
                </p>
                <br>

                ";?>

		        <h3 class="muted">Sign Up</h3>
		        <div class="navbar">
		          <div class="navbar-inner">
		            <div class="container">
		              <ul class="nav">
		                
		                
		                <li  ><a href = 'index.php'>Sign Up</a></li>
		              </ul>
		            </div>
		          </div>
		        </div><!-- /.navbar -->
		      </div>



            <div class = 'container'><h3>Sign Up Forms</h3><table class = 'table'>
            <tr>
                  <th>Event Name</th>
                  <th>Location</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Current/Max Volunteers</th>
                  <!--<th>Current # of Volunteers</th>-->
                  <th>Supervisor</th>
                  <th>Sign Up</th>
            </tr>


<?php

//Create an array of months in order to output date better
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


//Query database for every event and output it in an <html> table

$query = "SELECT * FROM events where closed=0 and eventdate between NOW() and NOW() + INTERVAL 2 MONTH  ORDER BY eventdate;"; //Selects all open events and orders them by most recent in the future to furthest
$result = queryMySql($query); //stores query in a result object
$numrows = mysql_num_rows($result);
$eventid = null;
for ($i = 0;$i<$numrows;++$i){ //for loop, runs through each row of data obtained in the query
	$eventid = mysql_result($result, $i,'id'); //gets the eventid
	
	


    echo"<tr>"; //Table row

    $eventDescription = mysql_result($result,$i,'description');//gets the event description from the sql query
	echo "<td>".mysql_result($result, $i,'eventname')."<input type='hidden' name='eventinfo' value = \"$eventDescription\"></td>"; //puts the event description as a hidden input in the first <td>


	$eventLocation = mysql_result($result,$i,'location');//gets event location
	echo '<td>'.$eventLocation.'</td>';
	
	$eventdate = explode('-', mysql_result($result, $i,'eventdate')); //explode/split the string that mysql stores the date in E.G 2013-12-28
	
	echo '<td>'.$months[(int)$eventdate[1]].' '.(int)$eventdate[2].', '.$eventdate[0].'</td>'; //outputs date using the months array: month, day, year
	
	echo '<td>'.mysql_result($result, $i,'eventtime').'</td>'; //displays the eventtime
    $max = mysql_result($result, $i,'max'); //displays max volunteers
    if ($max == 0) //if admin did not set max volunteers, then just displaay nothing
	    echo '<td></td>';
    else{
        //show fraction of currently signed up volunteers to max volunteers
        $currentVolunteerQuery = "SELECT * from signups where eventid=$eventid and waitlist=0 and noshow=0 and withdrew=0 and halfcredit=0";
        $currentVolunteers = mysql_num_rows(queryMySql($currentVolunteerQuery));
        echo '<td>'.$currentVolunteers."/".$max.'</td>';
    }
        
	echo '<td>'.mysql_result($result, $i,'supervisor').'</td>'; //output supervisor
	
	$query = "SELECT * FROM signups WHERE eventid=$eventid AND studentid=$userid";
	if (mysql_num_rows(mysql_query($query)) > 0){ //if student has signed up for event already, make the button disabled
		echo '<td><button disabled="disabled" class = "btn" type = "submit">Volunteer!</button></form>';
	}
	else{ //otherwise, output a hidden input with the event's id and a button(its handler is in the script section)
        //check if currrent volunteers are equal to the maximum number of allowed voluntters. if it is, do not show it on the list of events
        $currentvolunteers = mysql_num_rows(queryMySql("SELECT * FROM signups where eventid=$eventid"));
        if ($currentvolunteers >= mysql_result($result, $i,'max') && mysql_result($result, $i,'max')!=0){
            echo '<td><input type = "hidden" name = "eventid" value = "'.mysql_result($result, $i,'id').'"/>
        <button class = "btn btn-warning signup" type = "submit" value="waitlist">Waitlist Me!</button></td>';
        }
        else{
            echo '<td><input type = "hidden" name = "eventid" value = "'.mysql_result($result, $i,'id').'"/>
        <button class = "btn btn-primary signup" type = "submit" value="signup">Volunteer!</button></td>';
        }
		
	}
	
	echo "</tr>"; //end one row, one event
	
}   
echo "</table></div>"; //end table


include "footer.php";//include a footer

?>
