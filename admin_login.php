<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Attendance System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-box" id="attendance-form">
                <h1>Admin Login</h1>

               <form method="post" action="admin_process.php">
                <input type="text" name="username" placeholder="Username" required>
                <br><br>
                <input type="password" name="password" placeholder="Password" required>
                <br><br>
                <button type="submit" value="Login">Login</button>
                 </form>
            <br>
            <p><a href="index.php">Back to Attendance</a></p>
            
                </div>
    </div>

   
</body>
</html>
