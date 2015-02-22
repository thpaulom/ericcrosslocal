<?php


$query = "";
$result = "";
include '../functions.php';

if (isset($_POST['id']) && isset($_POST['eventid']) && isset($_POST['comment'])){
	$query = "UPDATE  signups set comment=".$_POST['comment']." where studentid=".(int)$_POST['id']." and eventid=".(int)$_POST['eventid'];
	$result = queryMySql($query);
}


?>