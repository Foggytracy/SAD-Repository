<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missile | Attendance Monitoring System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-box" id="attendance-form">
                 <h1>Attendance Monitoring System</h1>

              <form method="post" action="attendance_register.php">
                <input type="text" name="employee_ID" placeholder="Enter Employee ID" required>
                <br><br>

                <!-- Time In / Time Out buttons submit the form -->
                <button type="submit" name="action" value="in">Time In</button>
                <button type="submit" name="action" value="out">Time Out</button>
            </form>
            
            <p><a href="admin_login.php">Admin Login</a></p>
        </div>
    </div>

    <script src="script.js">
        
    </script>
</body>
</html>
