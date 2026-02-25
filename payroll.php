<?php
session_start();
if(!isset($_SESSION['admin'])){
    die("Access denied. <a href='admin_login.php'>Login</a>");
}

date_default_timezone_set('Asia/Manila');

$conn = new mysqli("localhost","root","","attendance_system");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

// Get selected dates or default to today
$start_date = $_GET['start_date'] ?? date('Y-m-d');
$end_date = $_GET['end_date'] ?? date('Y-m-d');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payroll</title>
    <link rel="stylesheet" href="admin.css?v=2.0">
</head>
<body>
<div class="navbar">
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="print_summary.php">Attendance Records</a>
    <a href="employee.php">Employee</a>
    <a href="payroll.php">Payroll</a>
    <a href="admin_login.php">Logout</a>
</div>



<div class="container">
<form method="GET">
    <label>Start Date:</label>
    <input type="date" name="start_date" required value="<?= $start_date ?>">

    <label>End Date:</label>
    <input type="date" name="end_date" required value="<?= $end_date ?>">
    <button type="submit" class="payroll">Generate Payroll</button>
</form>

<?php
// Query attendance in selected date range
$sql = "
SELECT 
    e.employee_id,
    CONCAT(e.first_name,' ',e.last_name) AS name,
    e.daily_rate,
    e.schedule_in,
    a.date,
    a.time_in,
    a.leave_status,

    CASE
        WHEN a.leave_status IN ('Sick','Vacation') THEN 'Paid Leave'
        WHEN a.time_in IS NULL THEN 'Absent'
        WHEN a.time_in > e.schedule_in THEN 'Late'
        ELSE 'Present'
    END AS status,
    
    CASE 
        WHEN a.time_in > e.schedule_in THEN TIMESTAMPDIFF(MINUTE, e.schedule_in, a.time_in)
        ELSE 0
    END AS late_minutes, 

     CASE
        WHEN a.time_out IS NOT NULL 
             AND a.time_out < e.schedule_out
        THEN TIMESTAMPDIFF(MINUTE, a.time_out, e.schedule_out)
        ELSE 0
    END AS undertime_minutes

FROM Employee e
LEFT JOIN Attendance a
    ON e.employee_id = a.employee_id
    AND a.date BETWEEN '$start_date' AND '$end_date'
WHERE e.status = 'Active'
ORDER BY e.employee_id, a.date
";

$result = $conn->query($sql);
if(!$result) die("Query failed: " . $conn->error);

// Prepare array to sum per employee
$payroll = [];
$work_minutes = 540; // 9 hours/day

while($row = $result->fetch_assoc()){
    $id = $row['employee_id'];
    $per_minute = $row['daily_rate'] / $work_minutes;

    $late_deduction = $row['late_minutes'] * $per_minute;
    $undertime_deduction = $row['undertime_minutes'] * $per_minute;

    if($row['status'] == 'Absent'){
        $salary = 0;
    }
    elseif($row['status'] == 'Paid Leave'){
        $salary = $row['daily_rate'];
    }
    else { // Present or Late
        $salary = $row['daily_rate'] - $late_deduction - $undertime_deduction;
    }

    $salary = round($salary,2);

    if(!isset($payroll[$id])){
        $payroll[$id] = [
            'name' => $row['name'],
            'total_salary' => 0,
            'total_late' => 0,
            'total_undertime' => 0,
            'days' => 0
        ];
    }

    $payroll[$id]['total_salary'] += $salary;
    $payroll[$id]['total_late'] += $row['late_minutes'];
    $payroll[$id]['total_undertime'] += $row['undertime_minutes'];
    $payroll[$id]['days']++;
}

// Display payroll table
$total_payroll = 0;

echo "<h1>Payroll from $start_date to $end_date</h1>";
echo "<table border='1' cellpadding='5'>";
echo "<tr>
        <th>Employee ID</th>
        <th>Name</th>
        <th>Days Worked</th>
        <th>Total Late Minutes</th>
        <th>Total Undertime Minutes</th>
        <th>Total Salary</th>
      </tr>";

foreach($payroll as $id => $data){
    echo "<tr>";
    echo "<td>$id</td>";
    echo "<td>{$data['name']}</td>";
    echo "<td>{$data['days']}</td>";
    echo "<td>{$data['total_late']}</td>";
    echo "<td>{$data['total_undertime']}</td>";
    echo "<td>₱ ".number_format($data['total_salary'],2)."</td>";
    echo "</tr>";
    $total_payroll += $data['total_salary'];
}

echo "<tr>
        <td colspan='5' style='text-align:right'><strong>Total Payroll</strong></td>
        <td><strong>₱ ".number_format($total_payroll,2)."</strong></td>
      </tr>";

echo "</table>";
?>

<button onclick="window.print()" style="margin-top:15px;">Print Payroll</button>

</div>
</body>
</html>

<?php $conn->close(); ?>