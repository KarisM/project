<?php
$studentID = $_GET['studentID'];
$appID = $_GET['appID'];
$programID = $_GET['programID'];
$outp ="[";
$con=mysqli_connect("127.0.0.1","root","","project_test");
if (mysqli_connect_errno()) {
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$sql = "UPDATE student SET StudentID ='".$studentID."' WHERE AppID ='".$appID."'";
$con->query($sql);
$con->close();
$conn=mysqli_connect("127.0.0.1","root","","project_test");
if (mysqli_connect_errno()) {
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$sql = "SELECT AppID, StudentID, fName, lName FROM student WHERE ProgramID ='".$programID."'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
    	if ($outp != "[") {$outp .= ",";}
    	$outp .= '{"AppID":"'. $row["AppID"].'",';
    	$outp .= '"StudentID":"'. $row["StudentID"].'",';
    	$outp .= '"fName":"'. $row["fName"].'",';
    	$outp .= '"lName":"'. $row["lName"]. '"}';
    }
} 
$outp.="]";
$conn->close();
echo ($outp)
?>