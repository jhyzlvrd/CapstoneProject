<?php
session_start(); // Start a session to store user data
require('../config/database.php'); // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input
    $EmployeeID = mysqli_real_escape_string($connection, trim($_POST['EmployeeID']));
    $Password = trim($_POST['password']);

    // Admin login check
    if ($EmployeeID === "Admin" && $Password === "123") {
        echo '<script>alert("Admin Login Successfully!"); window.location.href = "http://localhost/capstoneproject/admin/dashboard.php";</script>';
        exit;
    }

    // Check if EmployeeID or password is empty
    if (empty($EmployeeID) || empty($Password)) {
        echo '<script>alert("Please enter both Employee ID and password."); window.history.back();</script>';
        exit;
    }

    // Query to fetch the employee details
    $query = "SELECT ID, FullName, Password FROM srccapstoneproject.employeedb WHERE EmployeeID = ?";

    if ($stmt = mysqli_prepare($connection, $query)) {
        mysqli_stmt_bind_param($stmt, "s", $EmployeeID); // Bind EmployeeID parameter
        mysqli_stmt_execute($stmt); // Execute the query
        mysqli_stmt_bind_result($stmt, $user_id, $FName, $HashedPassword); // Bind results to variables

        // Check if an employee record is fetched
        if (mysqli_stmt_fetch($stmt)) {
            // Verify the entered password against the hashed password
            if (password_verify($Password, $HashedPassword)) {
                // Set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_employeeid'] = $EmployeeID;
                $_SESSION['user_fname'] = $FName;

                echo '<script>alert("Login Successfully!"); window.location.href = "index.php";</script>';
            } else {
                echo '<script>alert("Invalid Employee ID or password."); window.history.back();</script>';
            }
        } else {
            echo '<script>alert("No employee found with that Employee ID. Please enter valid credentials."); window.history.back();</script>';
        }

        mysqli_stmt_close($stmt); // Close the prepared statement
    } else {
        echo '<script>alert("Error in SQL preparation: ' . mysqli_error($connection) . '");</script>';
    }
}

// Close the database connection
mysqli_close($connection);
?>