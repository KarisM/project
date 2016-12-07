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
$CourseID = mysqli_real_escape_string($con, $_POST['Course']);
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
    
    for($j=0;$j<$col_count;$j++)
    {
        // escape variables for security
        $insert_data[$j] = mysqli_real_escape_string($con, $csv[$i][$j]);
        
    }
    /*
    echo '<br>';
    for($j=1;$j<$col_count;$j++)
    {
        echo $insert_data[0].' '.$insert_data[1];
        $sql= "UPDATE student SET StudentID = '$insert_data[1]' WHERE AppID = '$insert_data[0]'";
        
        //$sql="INSERT INTO test_1 (ID, Name, Zender,Birth,IQ)
       // VALUES ('$insert_data[0]', '$insert_data[$j]', $j, 'a', 'a')";
        if (!mysqli_query($con,$sql)) {
        die('Error: ' . mysqli_error($con));
    }
    */
    echo $insert_data[0].' '.$insert_data[1];
    if ($insert_data[0] == ""){
        //AppID not given, StudentID must be given
        $sql = "SELECT AppID FROM student WHERE StudentID = '$insert_data[1]'";
        $student = mysqli_query($con,$sql) or die(mysql_error());
        if(mysqli_num_rows($student)==0){
            echo "Student ID " . $insert_data[1] . " does not exist!<br>";
        }
        else{
            $insert_data[0] = mysqli_fetch_assoc($student)['AppID'];
            $sql = "INSERT INTO student_course(AppID, CourseID) VALUES ('$insert_data[0]', '$CourseID')";
            $insert = mysqli_query($con,$sql) or die(mysql_error());
            echo "Student ID " . $insert_data[1] . "has been added to course.<br>" . $CourseID;
        }
    }
    else{
        $sql = "SELECT * FROM student WHERE AppID = '$insert_data[0]'";
        $student = mysqli_query($con,$sql) or die(mysql_error());
        $sql= "SELECT * FROM student_course WHERE AppID = '$insert_data[0]' AND CourseID = '$CourseID'";
        $incourse = mysqli_query($con,$sql) or die(mysql_error());
        if(mysqli_num_rows($student)==0){
            echo "App ID " . $insert_data[0] . " does not exist!<br>";
        }
        else if (mysqli_num_rows($incourse)!=0){
            echo "App ID " . $insert_data[0] . "already existed in this course!<br>";
        }
        else{
            $sql = "INSERT INTO student_course(AppID, CourseID) VALUES ('$insert_data[0]', '$CourseID')";
            $insert = mysqli_query($con,$sql) or die(mysql_error());
            echo "App ID " . $insert_data[0] . "has been added to course.<br>" . $CourseID;
        }
    }
}


echo "records added";
mysqli_close($con);
header( "Refresh:1; url=http://127.0.0.1/project/form4.html", true, 303);
?>