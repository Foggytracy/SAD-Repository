<?php
session_start();
if(!isset($_SESSION['admin'])){
    die("Access denied.");
}

$conn = new mysqli("localhost","root","","attendance_system");
if($conn->connect_error) die("Connection failed");

if(isset($_POST['add'])){

    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $dept = $_POST['department'];
    $rate = floatval($_POST['daily_rate']);
    $in = $_POST['schedule_in'];
    $out = $_POST['schedule_out'];

    $stmt = $conn->prepare("
        INSERT INTO employee 
        (first_name, last_name, department, daily_rate, schedule_in, schedule_out, status)
        VALUES (?, ?, ?, ?, ?, ?, 'Active')
    ");

    $stmt->bind_param("sssdss",
        $first, $last, $dept,
        $rate, $in, $out
    );

    $stmt->execute();
    $stmt->close();

    header("Location: employee.php");
    exit();
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
        <h2>Add Employee</h2>
        <form method="POST">

        First Name:
        <input type="text" name="first_name" required><br>

        Last Name:
        <input type="text" name="last_name" required><br>

        Department: <br>
        <select name="department" required>
            <option value="">--Select Department--</option>
            <option value="Finance" >Finance</option>
            <option value="HR" >HR</option>
            <option value="Operations">Operations</option>
        </select>
        <br>

        Daily Rate:
        <input type="number" step="0.01" name="daily_rate" required><br>

        Schedule In:
        <input type="time" name="schedule_in" required><br>

        Schedule Out:
        <input type="time" name="schedule_out" required><br><br>

        <button type="submit" name="add">Add Employee</button>

        </form>
            </div>
        </div>
</body>
</html>

<?php $conn->close(); ?>