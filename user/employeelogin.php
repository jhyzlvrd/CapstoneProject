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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Employee Login | Santa Rita College</title>
    <link href="img/SRCLogoNB.png" rel="icon" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- FontAwesome link -->
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --accent-color: #3EC1B3;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f9fafb;
            --border-radius: 12px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body,
        html {
            height: 100%;
            width: 100%;
            background-color: var(--bg-light);
        }

        .container {
            display: flex;
            height: 100vh;
            min-height: 600px;
        }

        .left {
            flex: 1.2;
            background: linear-gradient(to bottom right,
                    rgba(0, 0, 128, 0.4),
                    rgba(70, 130, 180, 0.5)), url("img/newSRC.jpg") no-repeat center center;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 4rem;
            color: white;
            position: relative;
        }

        .left-content {
            position: relative;
            z-index: 2;
            max-width: 600px;
        }

        .left h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
        }

        .left p {
            font-size: 1.1rem;
            font-weight: 400;
            line-height: 1.6;
            opacity: 0.9;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        .right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-color: white;
        }

        .form-container {
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            background-color: #E3F2FD;
            /* Light Blue Shade */
        }

        .form-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .form-header-content {
            flex: 1;
        }

        .form-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        .logo {
            width: 60px;
            height: auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            font-size: 0.95rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            transition: var(--transition);
            background-color: #f9fafb;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background-color: white;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-light);
            font-size: 1.2rem;
        }

        .btn {
            width: 100%;
            padding: 0.875rem;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: var(--text-light);
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                min-height: 100vh;
            }

            .left {
                flex: none;
                height: 200px;
                padding: 1.5rem;
                justify-content: center;
                background: linear-gradient(to bottom right,
                        rgba(0, 0, 128, 0.4),
                        rgba(70, 130, 180, 0.5)), url("img/newSRC.jpg") no-repeat center center;
                background-size: cover;
            }

            .left h1 {
                font-size: 1.75rem;
                margin-bottom: 1rem;
            }

            .left p {
                font-size: 1rem;
            }

            .right {
                flex: 1;
                padding: 1.5rem;
            }

            .form-container {
                padding: 1.5rem;
                box-shadow: none;
            }

            .form-header {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }

            .logo {
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left">
            <div class="left-content">
                <h1>Santa Rita College of Pampanga</h1>
                <p>Quick Reports, Faster Actions, Better Campus Life.</p>
            </div>
        </div>

        <div class="right">
            <div class="form-container">
                <div class="form-header">
                    <img src="img/SRCLogoNB.png" alt="SRC Logo" class="logo" />
                    <div class="form-header-content">
                        <h2>Welcome Back!</h2>
                        <p>Sign in to your employee account</p>
                    </div>
                </div>

                <form id="loginForm" method="POST" action="">
                    <div class="form-group">
                        <label for="loginInput" class="form-label">Employee ID</label>
                        <input type="text" name="loginInput" id="loginInput" class="form-input"
                            placeholder="Enter your employee ID" required />
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="password-container">
                            <input type="password" id="password" name="password" class="form-input"
                                placeholder="Enter your password" required />
                            <button type="button" class="toggle-password" id="togglePassword">
                                <i class="fas fa-eye"></i> <!-- FontAwesome eye icon -->
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Log In
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    </script>
</body>

</html>
