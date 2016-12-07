<?php
$con=mysqli_connect("127.0.0.1","root","","project_test");
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// escape variables for security
$CourseID = mysqli_real_escape_string($con, $_POST['Course']);
//$AppID = mysqli_real_escape_string($con, $_POST['AppID']);
//$StudentID = mysqli_real_escape_string($con, $_POST['StudentID']);

//SEND ME $_POST['i'] = NUMBER OF RECORDS TO ADD DUAY!!!!
// THEREFORE , $_POST['AppID0'],...,$POST['AppIDi-1'] IS VALID
$len = (int)$_POST['i'];
for ($i=1;$i<=$len;$i++){
    $appidname = "AppID" . $i;
    $studentidname = "StudentID" . $i;
    $appID = $_POST[$appidname];
    if ($appID == ""){
        //AppID not given, StudentID must be given
        $studentID = $_POST[$studentidname];
        $sql = "SELECT AppID FROM student WHERE StudentID = '$studentID'";
        $student = mysqli_query($con,$sql) or die(mysql_error());
        if(mysqli_num_rows($student)==0){
            echo "Student ID " . $studentID . " does not exist!<br>";
        }
        else{
            $appID = mysqli_fetch_assoc($student)['AppID'];
            $sql = "INSERT INTO student_course(AppID, CourseID) VALUES ('$appID', '$CourseID')";
            $insert = mysqli_query($con,$sql) or die(mysql_error());
            echo "Student ID " . $studentID . "has been added to course.<br>" . $CourseID;
        }
    }
    else{
        $sql = "SELECT * FROM student WHERE AppID = '$appID'";
        $student = mysqli_query($con,$sql) or die(mysql_error());
        $sql= "SELECT * FROM student_course WHERE AppID = '$appID' AND CourseID = '$CourseID'";
        $incourse = mysqli_query($con,$sql) or die(mysql_error());
        if(mysqli_num_rows($student)==0){
            echo "App ID " . $appID . " does not exist!<br>";
        }
        else if (mysqli_num_rows($incourse)!=0){
            echo "App ID " . $appID . "already existed in this course!<br>";
        }
        else{
            $sql = "INSERT INTO student_course(AppID, CourseID) VALUES ('$appID', '$CourseID')";
            $insert = mysqli_query($con,$sql) or die(mysql_error());
            echo "App ID " . $appID . "has been added to course.<br>" . $CourseID;
        }
    }
}
header( "Refresh:1; url=http://127.0.0.1/project/form4.html", true, 303);
?>
