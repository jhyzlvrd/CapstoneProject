<?php
session_start(); // Start a session to store user data
require('../config/database.php'); // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input
    $Email = mysqli_real_escape_string($connection, trim($_POST['Email']));
    $Password = trim($_POST['password']);

    // Check if email or password is empty
    if (empty($Email) || empty($Password)) {
        echo '<script>alert("Please enter both email and password."); window.history.back();</script>';
        exit;
    }

    // Query to fetch the user details
    $query = "SELECT ID, FullName, Password FROM srccapstoneproject.studentdb1 WHERE Email = ?";
    
    if ($stmt = mysqli_prepare($connection, $query)) {
        mysqli_stmt_bind_param($stmt, "s", $Email); // Bind email parameter
        mysqli_stmt_execute($stmt); // Execute the query
        mysqli_stmt_bind_result($stmt, $user_id, $FName, $HashedPassword); // Bind results to variables

        // Check if a user record is fetched
        if (mysqli_stmt_fetch($stmt)) {
            // Verify the entered password against the hashed password
            if (password_verify($Password, $HashedPassword)) {
                // Set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_email'] = $Email;
                $_SESSION['user_fname'] = $FName;

                echo '<script>alert("Login Successful!"); window.location.href = "index.html";</script>';
            } else {
                echo '<script>alert("Invalid email or password."); window.history.back();</script>';
            }
        } else {
            echo '<script>alert("No user found with that email."); window.history.back();</script>';
        }

        mysqli_stmt_close($stmt); // Close the prepared statement
    } else {
        echo '<script>alert("Error in SQL preparation: ' . mysqli_error($connection) . '");</script>';
    }
}

// Close the database connection
mysqli_close($connection);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link href="img/SRCLogoNB.png" rel="icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url("img/RSbg2.jpg") no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="email"], input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #5cb85c;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #4cae4c;
        }
        .redirect {
            text-align: center;
            margin-top: 10px;
        }
        .redirect a {
            color: #007bff;
            text-decoration: none;
        }
        .redirect a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <form id="loginForm" method="POST" action="">
            <input type="email" name="Email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="redirect">
            <a href="forgotpass.php">Forgot Password?</a>
            <br><br>
            <label>Don't have an account? <a href="studentregistration.php">Register</a></label>
        </div>
    </div>
</body>
</html>
