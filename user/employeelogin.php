<?php
session_start();
require("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loginInput = htmlspecialchars(strip_tags(trim($_POST['loginInput'])));
    $password = trim($_POST['password']);

    if (!empty($loginInput) && !empty($password)) {
        // Optimized Query: Check for both Admin (Email) & User (EmployeeID)
        $query = "SELECT ID, EmployeeID, FullName, Email, Password, Role FROM srccapstoneproject.employeedb WHERE (Email = ? AND Role = 'Admin') OR (EmployeeID = ? AND Role = 'User')";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ss", $loginInput, $loginInput);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['Password'])) {
                // Set session variables
                $_SESSION['EmployeeID'] = $row['EmployeeID'];
                $_SESSION['FullName'] = $row['FullName'];
                $_SESSION['Email'] = $row['Email'];
                $_SESSION['Role'] = $row['Role'];

                // Redirect based on role
                if ($row['Role'] == 'Admin') {
                    header("Location: ../admin/dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            }
        }

        // Store error message in session
        $_SESSION['login_error'] = "Invalid Employee ID or Password!";
        header("Location: employeelogin.php");
        exit();
    } else {
        $_SESSION['login_error'] = "All fields are required!";
        header("Location: employeelogin.php");
        exit();
    }
}

if (isset($_SESSION['login_error'])) {
    echo "<script>alert('" . $_SESSION['login_error'] . "');</script>";
    unset($_SESSION['login_error']);
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>
    <link href="img/SRCLogoNB.png" rel="icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url("img/newSRC.jpg") no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 25px;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 12px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            background-color: #5cb85c;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: 0.3s;
            margin-bottom: 10px;
            /* Added space below the button */
        }

        button:hover {
            background-color: #4cae4c;
        }

        .show-password {
            display: flex;
            align-items: center;
            margin-top: 5px;
            font-size: 14px;
            text-align: left;
        }

        .show-password input {
            margin-right: 5px;
        }
    </style>

    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- WOW.js for animation triggers -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
</head>

<body>
    <div class="form-container wow animate__animated animate__zoomIn" data-wow-delay="0.5s">
        <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
            <img src="img/SRCLogoNB.png" alt="SRC Logo" style="width: 50px; height: auto;">
            <h2 style="margin: 0;">Log In</h2>
        </div>
        <form id="loginForm" method="POST" action="">
            <input type="text" name="loginInput" placeholder="Employee ID" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <div class="show-password">
                <input type="checkbox" id="togglePassword"> Show Password
            </div>
            <br>
            <button type="submit">Login</button>
        </form>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('change', function () {
            document.getElementById('password').type = this.checked ? 'text' : 'password';
        });

    </script>
</body>

</html>