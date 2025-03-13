<?php
require('../config/database.php'); // Ensure the database connection is included

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["editID"];
    $fullName = $_POST["FullName"];
    $employeeID = $_POST["EmployeeID"];
    $email = $_POST["Email"];
    $subject = $_POST["Subject"];
    $specificProblem = $_POST["Specific_problem"];
    $department = $_POST["Department"];
    $location = $_POST["Location"];
    $currentUser = $_POST["c_user"];
    $remarks = $_POST["Remarks"];

    // Use the correct connection variable ($connection instead of $conn)
    $query = "UPDATE reports SET 
              FullName = ?, 
              EmployeeID = ?, 
              Email = ?, 
              Subject = ?, 
              Specific_problem = ?, 
              Department = ?, 
              Location = ?, 
              c_user = ?, 
              Remarks = ? 
              WHERE ID = ?";

    $stmt = mysqli_prepare($connection, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssssssi", 
            $fullName, 
            $employeeID, 
            $email, 
            $subject, 
            $specificProblem, 
            $department, 
            $location, 
            $currentUser, 
            $remarks, 
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Complaint updated successfully!'); window.location.href='complaints.php';</script>";
        } else {
            echo "<script>alert('Error updating complaint: " . mysqli_stmt_error($stmt) . "');</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error preparing statement: " . mysqli_error($connection) . "');</script>";
    }

    mysqli_close($connection); // Close the connection
}
?>
