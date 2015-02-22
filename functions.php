<?php
	
	$server = 'localhost';
	$username = 'root';
	$password = 'root';
	$db = 'cs_db';
	
	mysql_connect($server,$username,$password) or die(mysql_error());
	mysql_select_db($db) or die(mysql_error());
    mysql_set_charset("utf8");

	
	function createTable($name, $query){
	    queryMySql("CREATE TABLE IF NOT EXISTS $name($query)");
	    echo "Table '$name' created or already exists.<br>";
	}
	
	function queryMySql($query){
	    $result = mysql_query($query) or die(mysql_error());
	    return $result;
	}
	
	
	function sanitizeString($var){
	    $var = strip_tags($var);
	    //$var = htmlentities($var);
	    $var = stripslashes($var);
	    return mysql_real_escape_string($var);
	}
	
	function destroySession() {
		$_SESSION=array();
		if (session_id() != "" || isset($_COOKIE[session_name()])) setcookie(session_name(), '', time()-2592000, '/');
		session_destroy();
	}

	function curPageURL() {
		 $pageURL = 'http';
		 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
	}
	
?>
