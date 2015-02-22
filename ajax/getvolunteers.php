<?php

include "../functions.php";

if (isset($_POST['eventid'])){
    $eventid = $_POST['eventid'];
    $query = "SELECT  volunteers.firstname, volunteers.lastname
FROM signups
INNER JOIN volunteers ON signups.studentid = volunteers.id
WHERE eventid =$eventid and waitlist=0
ORDER BY volunteers.lastname ASC ";
    $result = queryMySql($query);


    $data = array();
    for ($i = 0;$i<mysql_num_rows($result);$i++){
        $data[$i] = mysql_result($result,$i,'volunteers.firstname')." ".mysql_result($result,$i,'volunteers.lastname');




    }
    echo json_encode($data);
}





?>
