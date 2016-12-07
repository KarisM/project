<?php
$csv = array();
$insert_data = array();
$i=0;
$colName = array();
//read file
// check there are no errors


if($_FILES['csv']['error'] == 0){

    $name = $_FILES['csv']['name'];
    $ext = strtolower(end(explode('.', $_FILES['csv']['name'])));
    $type = $_FILES['csv']['type'];
    $tmpName = $_FILES['csv']['tmp_name'];

    // check the file is a csv
    if($ext === 'csv'){

        if(($handle = fopen($tmpName, 'r')) !== FALSE) {
            // necessary if a large csv file
            set_time_limit(0);

            $row = 0;
            while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                // number of fields in the csv
                $col_count = count($data);
                //echo($col_count);

                // get the values from the csv
                for($i=0 ; $i<$col_count ;$i++)
                {   
                    $csv[$row][$i] = $data[$i];
                    //echo($csv[$row][$i]);
                    //echo(" ");
                }
                //echo "<br>";
                // inc the row
                $row++;
            }
            fclose($handle);
        }
    }
    //echo $row;
}

//keep in SQL
$con=mysqli_connect("127.0.0.1","root","","project_test");
// Check connection
if (mysqli_connect_errno()) {
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
for($j=0;$j<$col_count;$j++){
        // escape variables for security
        $colName[$j] = mysqli_real_escape_string($con, $csv[0][$j]);
        echo $colName[$j].' ';
}
for($i=1;$i<$row;$i++)
{
    echo "Pepomanz";
    for($j=0;$j<$col_count;$j++)
    {
        // escape variables for security
        $insert_data[$j] = mysqli_real_escape_string($con, $csv[$i][$j]);
        
    }
           //$sql= "INSERT INTO examroom(AppID, SubjectID, SeatNo, Room, Time, ED_year) VALUES ('$insert_data[0]', '$insert_data[1]', '$insert_data[2]', '$insert_data[3]', '$insert_data[4]', '$insert_data[5]')";  
           $sql= "UPDATE examroom SET SubjectID='$insert_data[1]' ,SeatNo='$insert_data[2]',Room='$insert_data[3]',Time='$insert_data[4]' ,ED_year='$insert_data[5]'where AppID ='$insert_data[0]'";
           if ($con->query($sql) === TRUE) {
   				 echo "New record created successfully";
			} else {
    		echo "Error: " . $sql . "<br>" . $con->error;
}   
           // $insert = mysqli_query($con,$sql) or die(mysql_error());
            echo "App ID " . $insert_data[0] . "has been added <br>" ;
}


echo "records added";
mysqli_close($con);
//header( "Refresh:1; url=http://127.0.0.1/project/form4.html", true, 303);
?>