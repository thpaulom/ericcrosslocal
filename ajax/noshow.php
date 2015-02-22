<?php

$query = "";
$result = "";
include '../functions.php';

if (isset($_POST['id']) && isset($_POST['eventid'])){
	$query = "UPDATE  signups set noshow=true where studentid=".(int)$_POST['id']." and eventid=".(int)$_POST['eventid'];
	$result = queryMySql($query);
	
}


?>