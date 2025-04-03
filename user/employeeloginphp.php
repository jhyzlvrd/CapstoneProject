<?php
session_start();
require("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loginInput = trim($_POST['loginInput']); // Can be Email (for Admin) or EmployeeID (for User)
    $password = trim($_POST['password']);

    if (!empty($loginInput) && !empty($password)) {
        // Query to check if input matches an Admin's email
        $adminQuery = "SELECT ID, FullName, Email, Password, Role FROM srccapstoneproject.employeedb WHERE Email = ? AND Role = 'Admin'";
        $adminStmt = $connection->prepare($adminQuery);
        $adminStmt->bind_param("s", $loginInput);
        $adminStmt->execute();
        $adminResult = $adminStmt->get_result();

        if ($adminResult->num_rows == 1) {
            $row = $adminResult->fetch_assoc();
            if (password_verify($password, $row['Password'])) {
                // Admin login
                $_SESSION['EmployeeID'] = $row['EmployeeID']; 
                $_SESSION['FullName'] = $row['FullName'];
                $_SESSION['Email'] = $row['Email'];
                $_SESSION['Role'] = $row['Role'];

                header("Location: ../admin/dashboard.php");
                exit();
            }
        }

        // Query to check if input matches a User's EmployeeID
        $userQuery = "SELECT ID, FullName, Email, Password, Role FROM srccapstoneproject.employeedb WHERE EmployeeID = ? AND Role = 'User'";
        $userStmt = $connection->prepare($userQuery);
        $userStmt->bind_param("s", $loginInput);
        $userStmt->execute();
        $userResult = $userStmt->get_result();

        if ($userResult->num_rows == 1) {
            $row = $userResult->fetch_assoc();
            if (password_verify($password, $row['Password'])) {
                // User login
                $_SESSION['EmployeeID'] = $row['EmployeeID']; 
                $_SESSION['FullName'] = $row['FullName'];
                $_SESSION['Email'] = $row['Email'];
                $_SESSION['Role'] = $row['Role'];

                header("Location: index.php");
                exit();
            }
        }

        // If login fails
        echo "<script>alert('Invalid Email/Employee ID or Password!'); window.location.href='employeelogin.php';</script>";
    } else {
        echo "<script>alert('All fields are required!'); window.location.href='employeelogin.php';</script>";
    }
}
?>