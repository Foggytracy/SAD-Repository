<?php
session_start();
if(!isset($_SESSION['admin'])){
    die("Access denied. <a href='admin_login.php'>Login</a>");
}

$conn = new mysqli("localhost","root","","attendance_system");
if($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Make sure ID is provided
if(!isset($_GET['id'])){
    die("No employee selected.");
}

$id = (int)$_GET['id'];

// Handle form submission
if(isset($_POST['update'])){
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $dept = $_POST['department'];
    $rate = $_POST['daily_rate'];
    $in = $_POST['schedule_in'];
    $out = $_POST['schedule_out'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("
        UPDATE employee 
        SET first_name=?, last_name=?, department=?, daily_rate=?, schedule_in=?, schedule_out=?, status=? 
        WHERE employee_id=?
    ");
    $stmt->bind_param("sssddssi", $first, $last, $dept, $rate, $in, $out, $status, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: employee.php");
    exit();
}

// Fetch employee data
$stmt = $conn->prepare("SELECT * FROM employee WHERE employee_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$emp = $result->fetch_assoc();
$stmt->close();

if(!$emp){
    die("Employee not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Employee</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<div class="form-box">
<h2>Edit Employee</h2>
<form method="POST">
    First Name:<br>
    <input type="text" name="first_name" value="<?= htmlspecialchars($emp['first_name']); ?>" required><br><br>

    Last Name:<br>
    <input type="text" name="last_name" value="<?= htmlspecialchars($emp['last_name']); ?>" required><br><br>

    Department:<br>
    <input type="text" name="department" value="<?= htmlspecialchars($emp['department']); ?>" required><br><br>

    Daily Rate:<br>
    <input type="number" step="0.01" name="daily_rate" value="<?= $emp['daily_rate']; ?>" required><br><br>

    Schedule In:<br>
    <input type="time" name="schedule_in" value="<?= $emp['schedule_in']; ?>" required step="60"><br><br>

    Schedule Out:<br>
    <input type="time" name="schedule_out" value="<?= $emp['schedule_out']; ?>" required step="60"><br><br>

    Status:<br>
    <select name="status">
        <option value="Active" <?= $emp['status']=='Active'?'selected':'' ?>>Active</option>
        <option value="Inactive" <?= $emp['status']=='Inactive'?'selected':'' ?>>Inactive</option>
    </select><br><br>

    <button type="submit" name="update">Update Employee</button>
</form>
</div>
</div>
</body>
</html>

<?php $conn->close(); ?>