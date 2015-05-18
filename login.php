<?php
include "header.php";

//Hi I'm Paul to the power of 7

if ($loggedin == true){ //if $logged in is already initialized and true, user is already signed in


    //if user returns to login page on his/her own will
    //log out??
    //currently redirects to signup.php
    session_destroy();

    echo "<script>window.location.replace('index.php');</script>";
}
else{
    if (isset($_POST['username'])){ //checks whether sign-in form is submitted or not


        $username = $_POST['username'];
        $username = sanitizeString($username); //sanitize username input


        $query = "SELECT * FROM volunteers where login ='$username'"; //select user from database based on first name and last name
        $result = mysql_query($query);


        if (mysql_num_rows($result)>0){

            $userid = mysql_result($result, 0,'id');

            if ($userid != ""){ //if the user has an id (valid username or password), set session variables so that they are stored
                $_SESSION['userid'] = $userid;
                $_SESSION['ufirstname'] = mysql_result($result,0,'firstname');
                $_SESSION['ulastname'] = mysql_result($result,0,'lastname');;
                $loggedin = true;

                //redirect to page that user came from

                $url = $_SESSION['redirecturl'];


                if ($url != ""){ //uses javascript redirect to redirect user to signup.php
                    echo "<script>window.location.replace('$url');</script>";
                }
                else{
                    echo "<script>window.location.replace('index.php');</script>";
                }

            }else{
                die("There was an error");
            }
        }else{
            echo <<<END



            <div class = "container">

            <div  class=" centereddiv">
				<div class="row">
					<div class=" well">
						<legend>Please Sign In</legend>
			          	<div class="alert alert-error">
			                <a class="close" data-dismiss="alert" ></a>Invalid username! Please try again.
			            </div>
						<form method="POST" action="login.php" accept-charset="UTF-8">
						<input style = "width:98%" type="text" id="username"  name="username" placeholder="Username">

						<button type="submit" name="submit" class="btn btn-info btn-block">Sign in</button>
						</form>
					</div>
				</div>
			</div>

        </div>
END;

            echo "<div class = 'container'>";
            include("footer.php");
            echo "</div> <!--end container-->";
            die();


        }
    }
    else{ //if not valid username/password then show sign in again (with a slightly different message)
        echo <<<END

			<div class = "container">

            <div  class=" centereddiv">
				<div class="row">
					<div class=" well">
						<legend>Please Sign In</legend>
			          	<div class="alert alert-error">
			                <a class="close" data-dismiss="alert" ></a>Please login!
			            </div>
						<form method="POST" action="login.php" accept-charset="UTF-8">
						<input style = "width:98%;" type="text" id="username"  name="username" placeholder="Username">

						<button type="submit" name="submit" class="btn btn-info btn-block">Sign in</button>
						</form>
					</div>
				</div>
			</div>

        </div>


END;
        echo "<div class = 'container'>";
        include("footer.php");
        echo "</div> <!--end container-->";
        die();
    }

}








?>
