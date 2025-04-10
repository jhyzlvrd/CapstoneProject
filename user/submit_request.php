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
    $concern = mysqli_real_escape_string($connection, trim($_POST['subject']));
    $specificProblem = mysqli_real_escape_string($connection, trim($_POST['specificProblem']));
    $department = mysqli_real_escape_string($connection, trim($_POST['department']));
    $location = mysqli_real_escape_string($connection, trim($_POST['location']));
    $currentUser = mysqli_real_escape_string($connection, trim($_POST['currentUser']));
    $remarks = mysqli_real_escape_string($connection, trim($_POST['remarks']));

    // Generate a unique ReportID (e.g., REP202504090001)
    $datePrefix = date("Ymd");
    $reportPrefix = "REP" . $datePrefix;

    // Get count of today's reports to increment ID
    $queryCount = "SELECT COUNT(*) AS total FROM srccapstoneproject.reports WHERE ReportID LIKE ?";
    $likePattern = $reportPrefix . '%';

    $stmtCount = $connection->prepare($queryCount);
    $stmtCount->bind_param("s", $likePattern);
    $stmtCount->execute();
    $resultCount = $stmtCount->get_result();
    $countRow = $resultCount->fetch_assoc();
    $sequence = str_pad(($countRow['total'] + 1), 4, "0", STR_PAD_LEFT); // e.g., 0001
    $reportID = $reportPrefix . $sequence;
    $stmtCount->close();


    $queryCreate = "INSERT INTO srccapstoneproject.reports 
    (ReportID, FullName, EmployeeID, Email, Date_time, Asset_tag, Concern, Specific_problem, Department, Location, c_user, Remarks, Status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($connection, $queryCreate)) {
        $status = "Pending"; // Default status
        mysqli_stmt_bind_param($stmt, "sssssssssssss", $reportID, $fullName, $eid, $email, $date, $assetTag, $concern, $specificProblem, $department, $location, $currentUser, $remarks, $status);

        if (mysqli_stmt_execute($stmt)) {
            echo '<script>alert("Report submitted successfully. Report ID: ' . $reportID . '"); window.location.href = "index.php";</script>';
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