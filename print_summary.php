<?php
session_start();
if(!isset($_SESSION['admin'])){
    die("Access denied");
}

$conn = new mysqli("localhost","root","","attendance_system");

$date_record = $_GET['date_record'] ?? date('Y-m-d');
$date = $date_record;

$stmt = $conn->prepare("
    SELECT 
        e.department,
        e.employee_id,
        CONCAT(e.first_name,' ',e.last_name) AS name,
        DATE_FORMAT(a.time_in, '%h:%i %p') AS time_in,
        DATE_FORMAT(a.time_out, '%h:%i %p') AS time_out,
        a.leave_status
    FROM Employee e
    LEFT JOIN Attendance a
        ON e.employee_id = a.employee_id
        AND a.date = ?
    WHERE e.status = 'Active'
    ORDER BY e.department, e.last_name
");

$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance Summary</title>
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

<h1>Attendance Records - <?php echo $date; ?></h1>

<form method="GET">
    <label>Date Record:</label>
    <input type="date" name="date_record" required value="<?= $date_record ?>">
     <button type="submit" class="payroll">Generate Record</button>
</form>

<table border="1">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Time In</th>
    <th>Time Out</th>
    <th>Status</th>
</tr>

<?php
$current_department = "";

while($row = $result->fetch_assoc()){

    // Show department header when department changes
    if($current_department != $row['department']){
        $current_department = $row['department'];

        echo "<tr style='background:#ddd; font-weight:bold;'>
                <td colspan='5'>$current_department Department</td>
              </tr>";
    }

    if($row['leave_status'] && $row['leave_status'] != 'None'){
        $status = $row['leave_status'];
    }
    elseif(!$row['time_in']){
        $status = "Absent";
    }
    else{
        $status = "Present";
    }

    echo "<tr>";
    echo "<td>".$row['employee_id']."</td>";
    echo "<td>".$row['name']."</td>";
    echo "<td>".($row['time_in'] ?? '-')."</td>";
    echo "<td>".($row['time_out'] ?? '-')."</td>";
    echo "<td>".$status."</td>";
    echo "</tr>";
}

?>

</table>

<button onclick="window.print()" style="margin-top:30px;">Print</button>
</div>
</body>
</html>
