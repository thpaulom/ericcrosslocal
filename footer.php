<?php



//Output a footer below a horizontal line. If user is logged in, he can click the link to logout

echo <<<_END

	<hr>



      <div class="footer">
        <p>	<br>Part of the council? <a href="dashboard.php">Sign in here.</a>
_END;
if ($loggedin){

    /*
     * Output extra elements if student is logged in. Logout ability?
     */
	echo <<<END
	<!--<a style="float:right" href = 'logout.php'>Logout</a>-->
	
	
END;
}			
echo <<<END
	</p>
      </div>

    </div> <!-- /container -->
	<body>
	</html>

END;

?>
