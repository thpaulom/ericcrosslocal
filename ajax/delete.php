<?php


/*Ajax script

*/
$query = "";
$result = "";
include '../header.php';


if (isset($_POST['id']) && isset($_POST['eventid'])){
	$query = "DELETE from signups where studentid = ".(int)$_POST['id']." AND eventid = ".(int)$_POST['eventid'];
	$result = queryMySql($query);
	
}


?>
