<?php
$con=mysqli_connect("127.0.0.1","root","","project_test");
// Check connection
if (mysqli_connect_errno()) {
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// escape variables for security
$SubjectID = mysqli_real_escape_string($con, $_POST['Subject']);
$Year = mysqli_real_escape_string($con, $_POST['Year']);
$FacultyID = mysqli_real_escape_string($con, $_POST['Faculty']);
$DepartmentID = mysqli_real_escape_string($con, $_POST['Department']);
$ProgramID = mysqli_real_escape_string($con, $_POST['Program']);
// Get each student's ID
echo $ProgramID;
$sql="SELECT AppID FROM student WHERE ProgramID = '$ProgramID'";
$result_select = mysqli_query($con,$sql) or die(mysql_error());
while($row = mysqli_fetch_array($result_select)) {
	$AppID = stripslashes($row['AppID']);
	$roomName = "Room".$AppID;
	echo $roomName.' ';
 	//$buffer = $_POST[$AppID];
	if ($_POST[$roomName]!=""){
		$seatName = "SeatNo".$AppID;
		$dateName = "Time".$AppID;

        $room = $_POST[$roomName];
        $seat = $_POST[$seatName];
        $time = $_POST[$dateName];
        echo $seat.' ';
        echo $room.' ';
        echo $time.' ';
        echo '<br>';
		//$sql="UPDATE student SET StudentID = '$buffer' WHERE AppID = '$AppID'";
		//SEND Semester TO ME PLEASE E DOK
		$sql="INSERT INTO examroom(AppID, SubjectID, SeatNo, Room, Time, ED_year) VALUES ('$AppID', '$SubjectID', '$seat', '$room', '$time', '$Year')";
		if (!mysqli_query($con,$sql)) {
			die('Error: ' . mysqli_error($con));
		}
		else{
			echo "Record Added: AppID ".$AppID." to examroom Subject ".$SubjectID.'<br>';

		}
	}
}

mysqli_close($con);  

//header( "Refresh:1; url=http://127.0.0.1/project/form2.html", true, 303);
?>
-->