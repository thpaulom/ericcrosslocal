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
        $studentresult = mysql_query("SELECT volunteers.id,firstname,lastname,grade FROM volunteers where credits < 1"); //All students without their service requirement
        //$childrenTheatreResult = mysql_query("SELECT volunteers.id from volunteers inner join signups on volunteers.id = signups.studentid where signups.eventid=96"); //stuents in children's theatre
        //$openEventSignupsResult = mysql_query("SELECT volunteers.id FROM  events INNER JOIN signups on events.id=signups.eventid INNER JOIN volunteers on events.studentid = volunteers.id where events.closed=0 and volunteers.credits < 1"); //students currently signed up for open events that have not fulfilled their service requirement
        $studentids = array();

        for ($i = 0;$i<mysql_num_rows($studentresult);$i++){
            $studentids[] = mysql_result($studentresult, $i,'id');
        }

        foreach ($studentids as $key => $value) {
            if(mysql_num_rows(mysql_query("SELECT * from signups where studentid=".$studentids[$key]." AND eventid=96"))>0){
                unset($studentids[$key]);
            }
        }

        foreach ($studentids as $key => $value) {
            if(mysql_num_rows(mysql_query("SELECT * from signups INNER JOIN events on signups.eventid = events.id where studentid=".$studentids[$key]." AND closed=false"))>0){
                unset($studentids[$key]);
            }
        }

        


        

        
        $studentnames = array();
        $studentgrades = array();
        $studentemails = array();
        $parentemails = array();

        foreach ($studentids as $id){
            $studentresult = mysql_query("SELECT * FROM volunteers where id=$id");
            $studentnames[] = mysql_result($studentresult, 0,'firstname') . " " . mysql_result($studentresult, 0,'lastname');
            $studentgrades[] = mysql_result($studentresult, 0, 'grade');
            $studentemails[] = mysql_result($studentresult, 0,'email');
            $parentemails[] = mysql_result($studentresult, 0,'parentemail');




        }


        for ($i = 0;$i<count($studentids);$i++) {
            echo $studentnames[$i] . " , " . $studentgrades[$i] . " , " . $studentemails[$i];
            echo "<br>";
        }

        // echo implode(' , ', $studentemails);
        // echo "<br>";
        // echo implode(' , '$parentemails);


    }

    //RUN SCRIPTS HERE
    getStudentsWithNoCredits();

    echo "<br>All SCRIPTS RUN";


?>
