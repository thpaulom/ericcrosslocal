<?php
	
	$server = 'localhost';
	$username = 'root';
	$password = 'root';
	$db = 'cs_db';
	
	mysql_connect($server,$username,$password) or die(mysql_error());
	mysql_select_db($db) or die(mysql_error());
    mysql_set_charset("utf8");

    function undoCloseEvent($id){

    	$eventResult = mysql_query("SELECT * FROM events WHERE id=".$id);
    	$defaultcredits = mysql_result($eventResult, 0,'defaultcredits'); 

    	$studentids = array();
    	$result = mysql_query("select * from  volunteers  INNER JOIN signups on signups.studentid = volunteers.id where withdrew=0 waitlist=0 noshow=0 signups.eventid=".$id.";");
    	for ($i = 0;$i<mysql_num_rows($result);$i++){
    		$studentids[$i] = mysql_result($result, $i,'signups.studentid'); 
    	}
    	
    	$query = "UPDATE volunteers SET credits=credits-".$defaultcredits." WHERE";

    	foreach ($studentids as $key=>$studentid) {
    		# code...
    		$query = $query." id=".$studentid;
    		if ($key != count($studentids)-1)
    			$query = $query." OR";


    	}

    	mysql_query($query); //subtract credits from students
    	mysql_query("UPDATE events SET closed=0 WHERE id=".$id); //repoen event

    }

    function getStudentsWithNoCredits(){
        $studentresult = mysql_query("SELECT * FROM volunteers WHERE credits<1");
        $childrenTheatreResult = mysql_query("SELECT * from volunteers inner join signups on volunteers.id = signups.studentid where signups.eventid=96");
        
        $childrenTheatre = false;


        $studentids = array();

        for ($i = 0;$i<mysql_num_rows($studentresult);$i++){
            $studentid = mysql_result($studentresult,$i,'id');

            for ($j = 0;$j<mysql_num_rows($childrenTheatreResult);$j++){
                if (mysql_result($childrenTheatreResult, $j,'id')==$studentid){
                    $childrenTheatre = true;
                    break;
                }


            }
            if ($childrenTheatre == false){
                $studentids[] = $studentid;
            }else{
                $childrenTheatre = false;
            }




        }
        //echo count($studentids);
        //print_r($studentids);


        $studentemails = array();
        $parentemails = array();

        foreach ($studentids as $id){
            $studentresult = mysql_query("SELECT * FROM volunteers where id=$id");
            $studentemails[] = mysql_result($studentresult, 0,'email');
            $parentemails[] = mysql_result($studentresult, 0,'parentemail');




        }

        echo implode(' , ', $studentemails);
        echo "<br>";
        echo implode(' , '$parentemails);


    }

    //RUN SCRIPTS HERE

    echo "<br>All SCRIPTS RUN";


?>
