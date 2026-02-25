<?php
session_start();
if(!isset($_SESSION['admin'])){
    die("Access denied. <a href='admin_login.php'>Login</a>");
}

$today = date('Y-m-d');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css?v=2.0">
</head>
<body>

<div class="navbar">
    <a href="admin_dashboard.php"> Dashboard</a>
    <a href="print_summary.php">Attendance Records</a>
    <a href="employee.php">Employee</a>
    <a href="payroll.php">Payroll</a>
    <a href="admin_login.php">Logout</a>

</div>


<div class="container">
    <h1>Admin Dashboard</h1>
    
<?php
echo "<h1>$today</h1>";
$conn = new mysqli("localhost","root","","attendance_system");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

// Today's attendance query
$sql = "
SELECT 
    e.employee_id,
    CONCAT(e.first_name, ' ', e.last_name) AS name,
    DATE_FORMAT(a.time_in, '%h:%i %p') AS time_in,
    DATE_FORMAT(a.time_out, '%h:%i %p') AS time_out,
    a.leave_status,
    CASE
        WHEN a.leave_status IS NOT NULL AND a.leave_status != 'None' THEN a.leave_status
        WHEN a.employee_id IS NULL THEN 'Absent'
        WHEN a.time_in IS NOT NULL AND a.time_in > ADDTIME(e.schedule_in, '00:15:00') THEN 'Late'
        WHEN a.time_in IS NOT NULL THEN 'Present'
        ELSE 'Absent'
    END AS attendance_status,

    ROUND(
        IF(a.time_in IS NOT NULL AND a.time_out IS NOT NULL,
           TIMESTAMPDIFF(MINUTE, a.time_in, a.time_out)/60,
           0),
    2) AS total_hours
FROM employee e 
LEFT JOIN Attendance a
    ON e.employee_id = a.employee_id
    AND a.date = CURDATE()
WHERE e.status = 'Active'
ORDER BY e.employee_id
";

$result = $conn->query($sql);
if(!$result) die("Query failed: " . $conn->error);

// Table header


echo "<table border='1' cellpadding='5'>";
echo "<tr>
        <th>Employee</th>
        <th>Time In</th>
        <th>Time Out</th>
        <th>Status</th>
        <th>Total Hours</th>
        <th>Mark Leave</th>
      </tr>";

// Loop through employees
while($row = $result->fetch_assoc()){
    echo "<tr>";
    echo "<td>".$row['employee_id']." - ".$row['name']."</td>";
    echo "<td>".($row['time_in'] ?? '-')."</td>";
    echo "<td>".($row['time_out'] ?? '-')."</td>";
    echo "<td>".$row['attendance_status']."</td>";
    echo "<td>".($row['total_hours'] ?? '0')."</td>";

    // Leave dropdown with automatic submission
    echo "<td>
            <form method='POST' action='mark_leave.php' style='margin:0;'>
                <input type='hidden' name='employee_id' value='".$row['employee_id']."'>
                <select name='leave_type' required onchange='this.form.submit()'>
                    <option value=''>Select Leave</option>
                    <option value='Sick'>Sick</option>
                    <option value='Vacation'>Vacation</option>
                    <option value='Other'>Other</option>
                </select>
            </form>
          </td>";

    echo "</tr>";
}

echo "</table>";

$conn->close();
?>

</body>
</html>