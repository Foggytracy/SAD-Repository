<?php
session_start();

// Connect to database
$conn = new mysqli("localhost", "root", "", "attendance_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debug: check POST data
// print_r($_POST);

$username = $conn->real_escape_string($_POST['username']);
$password = $conn->real_escape_string($_POST['password']);

// Query the admin table
$sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result && $result->num_rows == 1) {
    $_SESSION['admin'] = $username;  // store admin username in session
    header("Location: admin_dashboard.php");
    exit;
} else {
    echo "<h2>Invalid login!</h2>";
    echo "<p><a href='admin_login.php'>Back to Login</a></p>";
}
?>
