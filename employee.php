<?php
session_start();
if(!isset($_SESSION['admin'])){
    die("Access denied");
}

$conn = new mysqli("localhost","root","","attendance_system");
if($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Deactivate employee
if(isset($_GET['remove'])){
    $id = $_GET['remove'];

    $stmt = $conn->prepare("UPDATE Employee SET status='Inactive' WHERE employee_id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $stmt->close();

    header("Location: employee_list.php");
    exit();
}

$sql = "SELECT 
            e.employee_id,
            CONCAT(e.first_name,' ',e.last_name) AS name,
            e.department,
            e.schedule_in,
            e.schedule_out,
            e.daily_rate
        FROM Employee e
        WHERE status='Active'
        ORDER BY e.department, e.last_name";

$result = $conn->query($sql);
if(!$result){
    die("Query Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Currently Employed Personnel</title>
    <link rel="stylesheet" href="admin.css?v=2">
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
<h1>Employee Management</h1>
    


<table border="1" style="margin-top: 15px;">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Department</th>
    <th>Schedule In</th>
    <th>Schedule Out</th>
    <th>Daily Rate</th>
    <th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['employee_id']; ?></td>
    <td><?= $row['name']; ?></td>
    <td><?= $row['department']; ?></td>
    <td><?= date("h:i A", strtotime($row['schedule_in'])); ?></td>
    <td><?= date("h:i A", strtotime($row['schedule_out'])); ?></td>
    <td><?= $row['daily_rate']; ?></td>
    <td>
        <a href="edit_employee.php?id=<?= $row['employee_id']; ?>">Edit</a>
        <a href="?remove=<?= $row['employee_id']; ?>"
           onclick="return confirm('Remove this employee?');"
           style="color:red;">
           Remove
        </a>
    </td>
    
    
</tr>
<?php endwhile; ?>

</table>
    <a href="add_employee.php" class="btn">Add Employee</a>
</div>
</body>
</html>

<?php $conn->close(); ?>