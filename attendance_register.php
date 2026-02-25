<?php
// sets the timezone
date_default_timezone_set('Asia/Manila');

// attendance_register.php

//Creates a connection to MySQL Database
$conn = new mysqli("localhost","root","", "attendance_system");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error); //if connection fails, stop the script

//Gets employee ID from the form
$employee_ID = $_POST['employee_ID'];
// Gets whether user selected in or out
$action = $_POST['action']; 
$date = date('Y-m-d');
$time = date('H:i:s');

// Check if employee exists
$stmt = $conn->prepare("SELECT * FROM Employee WHERE employee_id = ?");
$stmt->bind_param("i", $employee_ID);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    die("Employee not found. <a href='index.php'>Back</a>");
}
$stmt->close();

//  Check if attendance record already exists today
$stmt = $conn->prepare("SELECT * FROM Attendance WHERE employee_id = ? AND date = ?");
$stmt->bind_param("is", $employee_ID, $date);
$stmt->execute();
$result = $stmt->get_result();

if($action == 'in') {

    if($result->num_rows > 0){
        die("Already timed in today. <a href='index.php'>Back</a>");
    }

    // Insert new attendance record
    $stmt = $conn->prepare("INSERT INTO Attendance (employee_id, date, time_in) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $employee_ID, $date, $time);

} else if($action == 'out') {

    if($result->num_rows == 0){
        die("No time-in record found for today. <a href='index.php'>Back</a>");
    }

    // Update time_out
    $stmt = $conn->prepare("UPDATE Attendance SET time_out = ? WHERE employee_id = ? AND date = ?");
    $stmt->bind_param("sis", $time, $employee_ID, $date);

} else {
    die("Invalid action.");
}

// Execute query
if($stmt->execute()){
    echo "<h2>Attendance recorded!</h2>";
    echo "<p>Employee ID: $employee_ID</p>";
    echo "<p>Action: Time $action</p>";
    echo "<p>Time: " . date("h:i:s A", strtotime($time)) . "</p>";
    echo "<a href='index.php'>Back</a>";
} else {
    echo "Error: ".$stmt->error;
}

$stmt->close();
$conn->close();
?>
