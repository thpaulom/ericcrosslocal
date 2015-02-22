<?php

/*Quickly logs hte user out and redirects to the main page (logging out means destroying the session)
*/

include 'header.php';
session_unset();
session_destroy();

echo"<script>window.location.replace('signup.php');</script>";


?>
