<?php
/*
	This generates an excel document and downloads it for the user
    Checks if user is an admin first
    If admin, generates an excel file with data taken from the MySQL databases

*/

$query = "";
$result = "";
$username  = 'admin';
$password = 'admin';

if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && isset($_POST['downloadexcel'])) {
	if ($_SERVER['PHP_AUTH_USER'] == $username && $_SERVER['PHP_AUTH_PW'] == $password){
		include 'functions.php'; //include mysql functions DO NOT include header.php, will have errors
		include 'library/phpexcel/PHPExcel.php'; //include third-party excel libraries
		include 'library/phpexcel/PHPExcel/Writer/Excel2007.php';



        $lines = file('cscouncil2014.txt');
        foreach ($line as $line_num=>$line){
            
        }

		
		$phpExcel = new PHPExcel(); //creates excel object
		$sheet=$phpExcel->getActiveSheet();
		
		/*
		 * Establish basic excel information like title, subject , etc.
		 */
		$phpExcel->getProperties()->setCreator("Kinkaid Community Service Council");
		$phpExcel->getProperties()->setLastModifiedBy("Kinkaid Community Service Council");
		$phpExcel->getProperties()->setTitle("Current Event Data");
		$phpExcel->getProperties()->setSubject("Current Event Data");
		$phpExcel->getProperties()->setDescription("Kinkaid Community Service Event data.");
		


        /*

            OUTPUT ROW OF EVENTS
        */
		$row = 2; //Third row because save top one for a title
		$col = 1; //Second Column, because the first is for student names
		$query = "SELECT * FROM events WHERE closed=true ORDER by eventdate ASC"; //selects only closed events


        $eventsresult = mysql_query($query); //stores the results from selecting all the events in the events tables


        $sheet->setCellValueByColumnAndRow($col,$row,"Total Credits");
        $col++;

        /*Output a column for student's grades*/
        $sheet->setCellValueByColumnAndRow($col,$row,"Grade");
        $col++;



        /*Output each event names at the tops of subsequent columns*/
        for ($i = 0;$i<mysql_num_rows($eventsresult);$i++){
			$sheet->setCellValueByColumnAndRow($col,$row,mysql_result($eventsresult, $i,'eventname'));
			$col++;
		}
		$highestcol = $phpExcel->setActiveSheetIndex(0)->getHighestColumn();
		$sheet->setCellValueByColumnAndRow($col,$row,'Total');
		$row++;
		
		/*Output a student name, followed by a total ccredits, grade, and then
         numbers denoting how many credits he/she earned in each event*/

        //restart from the first column
        $col = 0;
        $query = "SELECT * FROM volunteers ORDER by lastname ASC";

        $studentresult = mysql_query($query);//stores results of selecting all volunteers from the volunteer table
        for ($i = 0;$i<mysql_num_rows($studentresult);$i++){ //iterate through every student
            
            $studentid = mysql_result($studentresult, $i,'id');//get thestudent id

            //output the student's 'lastname, firstname'
            $col = 0;
            $sheet->setCellValueByColumnAndRow($col,$row,mysql_result($studentresult, $i,'lastname').", ".mysql_result($studentresult, $i,'firstname'));
            $col++;

            $sheet->setCellValueByColumnAndRow($col,$row,mysql_result($studentresult, $i,'credits'));
            $col++;

            //output the student's grade
            $sheet->setCellValueByColumnAndRow($col,$row,mysql_result($studentresult,$i,'grade'));
            $col++;

            for ($x = 0;$x<mysql_num_rows($eventsresult)+1;$x++){//loop through every event
                /*Checks if there is only 1 event.  */
                if ($x == mysql_num_rows($eventsresult)){
                    if ($highestcol != "C")
                        $sheet->setCellValueByColumnAndRow($col,$row,mysql_result($studentresult, $i,'credits'));
                    break;
                }
                
                $eventid = mysql_result($eventsresult, $x,'id');

                //checks if there is a signup enry for a the student id and an event id
                if (mysql_num_rows(mysql_query("SELECT * FROM signups WHERE eventid = '$eventid' AND studentid = '$studentid' and waitlist=0 and noshow=0 and withdrew=0"))) {
                    //if so, display in the column the credits earned by the student for that specific event
					$sheet->setCellValueByColumnAndRow($col,$row,mysql_result($eventsresult, $x,'defaultcredits'));
				}
				else{
                    //else put a 0 in the column
                    $sheet->setCellValueByColumnAndRow($col,$row,0);
                }
                
                $col++;
            }
            
            $row++; 
        }
        /*Sets up a row for totals of credits in each event*/
        $col = 0;//start at first column to write the word total
        $sheet->setCellValueByColumnAndRow($col,$row,'Event Totals');

        $col =3; //start from the column with the first event (skip total, grade)

        $finalrow = $row-1; //the row to sum to (don't sum to the row we're writing on so -1), the final row of a student
        $totalcol =  $phpExcel->setActiveSheetIndex(0)->getHighestColumn(); //the column of totals


        for ($i = 0;$i<mysql_num_rows($eventsresult);$i++){//iterate through every event
            $currentcol = PHPEXCEL_CELL::stringFromColumnIndex($col);//returns 'a' or 'b' from '1' and '2' so i can get column letters
            $sheet->setCellValueByColumnAndRow($col,$row,"=SUM($currentcol"."3:$currentcol"."$finalrow)");
            $col++;
        }


    //Begin grade calculations
        //output a row of headers
        $row += 2;//skip a line
        $col = 0;
        $sheet->setCellValueByColumnAndRow($col,$row,'Grade Totals');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row,'9th Grade');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row,'10th Grade');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row,'11th Grade');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row,'12th Grade');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row,'Total');

        $row++;
        $col = 0;
        $sheet->setCellValueByColumnAndRow($col,$row,'Total # of Students');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row,"=COUNTIF(C3:C$finalrow,9)");
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row,"=COUNTIF(C3:C$finalrow,10)");
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row,"=COUNTIF(C3:C$finalrow,11)");
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row,"=COUNTIF(C3:C$finalrow,12)");
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row,"=SUM(B$row:E$row)");


        /*
        Students who have earned their credits
        */
        $row++;
        $col = 0;
        $sheet->setCellValueByColumnAndRow($col,$row,'Met Requirements');
        for ($i=9;$i<=12;$i++){
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row,"=COUNTIFS(C3:C$finalrow".","."$i".","."$totalcol"."3:$totalcol"."$finalrow,\">=1\")");
            //echo "=COUNTIFS(B3:B$finalrow".","."$i".","."$totalcol"."3:$totalcol"."$finalrow,\">1\")";

        }
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row,"=SUM(B$row:E$row)");  


        /*  
        Students who still need credits
        */
        $row++;
        $col = 0;
        $sheet->setCellValueByColumnAndRow($col,$row,'Still Need Credit');
        for ($i=9;$i<=12;$i++){
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row,"=COUNTIFS(C3:C$finalrow".","."$i".","."$totalcol"."3:$totalcol"."$finalrow,0)");
            // echo "=COUNTIFS(B3:B$finalrow".","."$i".","."$highestcol"."3:$highestcol"."$finalrow,0)";

        }
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row,"=SUM(B$row:E$row)");

       
         /*
        Students who have fulfilled more than required
        */
        $row++;
        $col = 0;
        $sheet->setCellValueByColumnAndRow($col,$row,'Surpassed requirements');
        for ($i=9;$i<=12;$i++){
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row,"=COUNTIFS(C3:C$finalrow".","."$i".","."$totalcol"."3:$totalcol"."$finalrow,\">1\")");
            //echo "=COUNTIFS(B3:B$finalrow".","."$i".","."$totalcol"."3:$totalcol"."$finalrow,\">1\")";

        }
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row,"=SUM(B$row:E$row)");        




        foreach(range('A',PHPEXCEL_CELL::stringFromColumnIndex($sheet->getHighestColumn())) as $columnID) {
		    $phpExcel->getActiveSheet()->getColumnDimension($columnID)
		        ->setAutoSize(true);
		}

		$phpExcel->setActiveSheetIndex(0);
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="eventdata.xlsx"');
		header('Cache-Control: max-age=0');
		
		PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);

		
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
		$objWriter->save('php://output');
		
	}
}





?>
