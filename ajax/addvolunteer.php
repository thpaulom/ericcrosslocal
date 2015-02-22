<?php
/*
	Ajax script
*/

$query = "";
$result = "";
include '../functions.php';


if (isset($_POST['id']) && isset($_POST['eventid'])){



	$studentid = $_POST['id'];
	$eventid=$_POST['eventid'];


    //if the student is alrady in the event
    if (mysql_num_rows(mysql_query("SELECT * FROM signups where studentid=$studentid AND eventid = $eventid"))>0){
        $data = array( //label the insertion as a duplicate
          "duplicate"=>true
        );

    }
    //if the student is not the current event
    else{
        $query = "INSERT INTO signups(eventid,studentid) VALUES($eventid,$studentid)";
        $result = queryMySql($query);

        $query = "SELECT * from volunteers where id=$studentid";
        $result = queryMySql($query);
        $studentFirstName = mysql_result($result,0,'firstname');
        $studentLastName = mysql_result($result,0,'lastname');
        $studentGrade = mysql_result($result,0,'grade');
        $studentAdvisor = mysql_result($result,0,'advisor');
        $studentEmail = mysql_result($result,0,'email');
        $parentEmail = mysql_result($result,0,'parentemail');
        $studentCredits = mysql_result($result,0,'credits');


        $data = array(//label the insertion as not duplicate and send back the important data
            "duplicate" =>false,
            "firstname" => "$studentFirstName",
            "lastname" => "$studentLastName",
            "grade" => "$studentGrade",
            "advisor" => "$studentAdvisor",
            "email" => "$studentEmail",
            "parentemail" => "$parentEmail",
            "credits" => "$studentCredits"

        );
    }
    echo json_encode($data);

}

?>
