<?php
/**
 * Created by PhpStorm.
 * User: cyrieu
 * Date: 11/1/14
 * Time: 9:55 AM
 */





/*
 * Handler for closing events
 */
if ($_POST['eventid'] != "" && isset($_POST['closeevent']) ){
    include "header.php";

    $eventid = $_POST['eventid'];
    $query = "SELECT * from events where id=$eventid";
    $result = queryMySql($query);
    $eventname = mysql_result($result,0,'eventname');


    echo "<div class = 'container'>\n";

    echo "<h3>Volunteers for Event: $eventname</h3>";
    echo "<table class = 'table'>";
    echo <<<END
						<tr>
		      <th>First Name</th>
		      <th>Last Name</th>
		      <th>Grade</th>
			  <th>Advisor</th>
		</tr>
END;


    $query = "SELECT signups.id,signups.studentid,signups.extra, volunteers.firstname, volunteers.lastname, volunteers.grade, volunteers.advisor
FROM signups
INNER JOIN volunteers ON signups.studentid = volunteers.id
WHERE eventid = $eventid
ORDER BY volunteers.lastname ASC ";


    $result = queryMySql($query);


    /*Created an array of students' lastname to their ids, and sorted it alphabetically by last name*/
    for ($i = 0;$i<mysql_num_rows($result);$i++){
        echo "<tr>";
        echo "<td height='25' id = 'firstname' >".mysql_result($result, $i,'firstname')."</td>";
        echo "<td id = 'lastname'>".mysql_result($result, $i,'lastname')."</td>";
        echo '<td >'.mysql_result($result, $i,'grade').'</td>';
        echo "<td >".mysql_result($result, $i,'advisor');
        echo "</tr>";
    }




    echo "</table>";

}
?>

    <form action = 'dashboard.php' method = 'post'>

        <label>Please enter the admin password to close this event:</label>
        <input type="password" name="closeeventpassword"/>
        <input type="hidden" name = "closeevent">
        <input type = "hidden" name = "eventid" value = "<?php echo $eventid;?>"/>
        <br>
        <button type = 'submit' class = 'btn btn-danger'>Close Event</button>
    </form>




</div>
