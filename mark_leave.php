<?php
session_start();
if(!isset($_SESSION['admin'])) exit;

$employee_id = intval($_POST['employee_id']);
$leave_type = $_POST['leave_type'];
$date = date('Y-m-d');

$conn = new mysqli("localhost","root","","attendance_system");

// First delete today's record (if exists)
$conn->query("DELETE FROM Attendance 
              WHERE employee_id = $employee_id 
              AND date = '$date'");

// Insert fresh leave record
$conn->query("INSERT INTO Attendance (employee_id, date, leave_status)
              VALUES ($employee_id, '$date', '$leave_type')");

header("Location: admin_dashboard.php");
exit;
?>
