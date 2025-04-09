<?php
// Include database connection
require_once('../config/database.php'); // Ensure this file correctly defines $connection

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {

    // Sanitize and escape input data
    $fullName = mysqli_real_escape_string($connection, trim($_POST['fullName']));
    $eid = mysqli_real_escape_string($connection, trim($_POST['employeeID']));
    $email = mysqli_real_escape_string($connection, trim($_POST['email']));
    $date = mysqli_real_escape_string($connection, trim($_POST['date']));
    $assetTag = mysqli_real_escape_string($connection, trim($_POST['assetTag']));
    $subject = mysqli_real_escape_string($connection, trim($_POST['subject']));
    $specificProblem = mysqli_real_escape_string($connection, trim($_POST['specificProblem']));
    $department = mysqli_real_escape_string($connection, trim($_POST['department']));
    $location = mysqli_real_escape_string($connection, trim($_POST['location']));
    $currentUser = mysqli_real_escape_string($connection, trim($_POST['currentUser']));
    $remarks = mysqli_real_escape_string($connection, trim($_POST['remarks']));

    // SQL Query
    $queryCreate = "INSERT INTO srccapstoneproject.reports 
        (FullName, EmployeeID, Email, Date_time, Asset_tag, Subject, Specific_problem, Department, Location, c_user, Remarks) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($connection, $queryCreate)) {
        mysqli_stmt_bind_param($stmt, "sssssssssss", $fullName, $eid, $email, $date, $assetTag, $subject, $specificProblem, $department, $location, $currentUser, $remarks);

        if (mysqli_stmt_execute($stmt)) {
            echo '<script>alert("Successfully reported the problem."); window.location.href = "index.php";</script>';
            exit(); // Prevent further execution
        } else {
            echo '<script>alert("Error: ' . mysqli_stmt_error($stmt) . '"); window.history.back();</script>';
        }

        mysqli_stmt_close($stmt);
    } else {
        echo '<script>alert("Database Error: ' . mysqli_error($connection) . '"); window.history.back();</script>';
    }
}

// Close the database connection
mysqli_close($connection);
?>
