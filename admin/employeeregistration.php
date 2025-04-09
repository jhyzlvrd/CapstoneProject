<?php
// Include database connection
require('../config/database.php'); // Make sure this file defines $connection correctly

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create'])) {
    // Retrieve and sanitize form data
    $FName = mysqli_real_escape_string($connection, trim($_POST['FName']));
    $EmployeeID = mysqli_real_escape_string($connection, trim($_POST['EmployeeID']));
    $Email = mysqli_real_escape_string($connection, trim($_POST['Email']));
    $Gender = mysqli_real_escape_string($connection, trim($_POST['Gender']));
    $Designation = mysqli_real_escape_string($connection, trim($_POST['Designation']));
    $Department = mysqli_real_escape_string($connection, trim($_POST['Department']));
    $Status = mysqli_real_escape_string($connection, trim($_POST['Status']));
    $Role = 'User';
    $Password = mysqli_real_escape_string($connection, trim($_POST['Password']));
    $ConfirmPassword = mysqli_real_escape_string($connection, trim($_POST['ConfirmPassword']));
    

    // Validate Full Name
    if (!preg_match('/^[A-Za-z\s]+$/', $FName)) {
        echo '<script>alert("Full Name must contain only letters and spaces."); window.history.back();</script>';
        exit;
    }

    // Validate Employee ID (Ensure it's alphanumeric)
    if (!preg_match('/^[a-zA-Z0-9]+$/', $EmployeeID)) {
        echo '<script>alert("Invalid Employee ID. Use only letters and numbers."); window.history.back();</script>';
        exit;
    }

    // Validate required fields
    if (empty($FName) || empty($EmployeeID) || empty($Gender) || empty($Designation) || empty($Department) || empty($Status) || empty($Password) || empty($ConfirmPassword)) {
        echo '<script>alert("All fields are required."); window.history.back();</script>';
        exit;
    }

    // Check if passwords match
    if ($Password !== $ConfirmPassword) {
        echo '<script>alert("Passwords do not match."); window.history.back();</script>';
        exit;
    }

    // Hash the password
    $HashedPassword = password_hash($Password, PASSWORD_BCRYPT);

    // Check if Employee ID is already in use
    $idCheckQuery = "SELECT EmployeeID FROM srccapstoneproject.employeedb WHERE EmployeeID = ?";
    if ($idStmt = mysqli_prepare($connection, $idCheckQuery)) {
        mysqli_stmt_bind_param($idStmt, "s", $EmployeeID);
        mysqli_stmt_execute($idStmt);
        mysqli_stmt_store_result($idStmt);

        if (mysqli_stmt_num_rows($idStmt) > 0) {
            echo '<script>alert("Employee ID is already in use. Please use a different one."); window.history.back();</script>';
            mysqli_stmt_close($idStmt);
            exit;
        }
        mysqli_stmt_close($idStmt);
    } else {
        echo '<script>alert("Error checking Employee ID: ' . mysqli_error($connection) . '");</script>';
        exit;
    }

    // Retrieve and sanitize email input
    // $Email = mysqli_real_escape_string($connection, trim($_POST['Email']));

    // Validate Email Format
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid email format."); window.history.back();</script>';
        exit;
    }

    // Check if email is already in use
    $emailCheckQuery = "SELECT Email FROM srccapstoneproject.employeedb WHERE Email = ?";
    if ($emailStmt = mysqli_prepare($connection, $emailCheckQuery)) {
        mysqli_stmt_bind_param($emailStmt, "s", $Email);
        mysqli_stmt_execute($emailStmt);
        mysqli_stmt_store_result($emailStmt);

        if (mysqli_stmt_num_rows($emailStmt) > 0) {
            echo '<script>alert("Email is already in use. Please use a different one."); window.history.back();</script>';
            mysqli_stmt_close($emailStmt);
            exit;
        }
        mysqli_stmt_close($emailStmt);
    } else {
        echo '<script>alert("Error checking Email: ' . mysqli_error($connection) . '");</script>';
        exit;
    }

    // Insert new employee record with email
    $queryCreate = "INSERT INTO srccapstoneproject.employeedb (FullName, EmployeeID, Email, Gender, Designation, Department, Status, Role, Password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($connection, $queryCreate)) {
        mysqli_stmt_bind_param($stmt, "sssssssss", $FName, $EmployeeID, $Email, $Gender, $Designation, $Department, $Status, $Role, $HashedPassword);

        if (!mysqli_stmt_execute($stmt)) {
            echo '<script>alert("Registration failed: ' . mysqli_stmt_error($stmt) . '");</script>';
        } else {
            echo '<script>alert("Successfully Created"); window.location.href = "employeeaccounts.php";</script>';
        }

        mysqli_stmt_close($stmt);
    } else {
        echo '<script>alert("Error in SQL preparation: ' . mysqli_error($connection) . '");</script>';
    }
}


// Close the database connection
mysqli_close($connection);
?>



<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADD NEW EMPLOYEE</title>
    <link href="img/SRCLogoNB.png" rel="icon">
    <style>
        body {
            /* font-family: Arial, sans-serif;
            background: url("img/RSbg2.jpg") no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #333; */

            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: scroll;
            background-size: cover;

        }

        .form-container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        label {
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="password"],
        input[type="file"],
        input[type="radio"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        input[type="radio"] {
            width: auto;
        }

        .form-container input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }

        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .redirect {
            text-align: center;
            margin-top: 15px;
        }

        .redirect a {
            color: #4CAF50;
            text-decoration: none;
        }

        .redirect a:hover {
            text-decoration: underline;
        }

        /* Style the gender radio buttons */
        .gender-options {
            display: flex;
            justify-content: space-around;
            margin-bottom: 16px;
        }

        .gender-options input[type="radio"] {
            margin-right: 6px;
        }

        .gender-options label {
            font-size: 14px;
            margin-right: 20px;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
            background-color: #fff;
            /* Background color to match */
            color: #333;
            /* Text color */
        }

        select:focus {
            border-color: black;
            /* Highlight border on focus */
            outline: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }       
    </style>
</head>

<body>

    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Add New Employee</h4>
                    </div>
                    <div class="card-body">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
                    
                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="FName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="FName" id="FName"
                                    placeholder="Enter full name" required pattern="[A-Za-z\s]+"
                                    title="Only letters and spaces allowed.">
                            </div>

                            <!-- Employee ID -->
                            <div class="mb-3">
                                <label for="EmployeeID" class="form-label">Employee ID</label>
                                <input type="text" class="form-control" name="EmployeeID" id="EmployeeID"
                                    placeholder="Enter Employee ID" required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="Email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="Email" id="Email"
                                    placeholder="Enter email" required>
                            </div>


                            <!-- Gender -->
                            <div class="mb-3">
                                <label for="Gender" class="form-label">Gender</label>
                                <select name="Gender" id="Gender" class="form-select" required>
                                    <option value="" disabled selected>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>

                            <!-- Designation -->
                            <div class="mb-3">
                                <label for="Designation" class="form-label">Designation</label>
                                <select name="Designation" id="Designation" class="form-select" required>
                                    <option value="" disabled selected>Select Designation</option>
                                    <option value="IT Head">IT Head</option>
                                    <option value="ELEM Faculty">ELEM Faculty</option>
                                    <option value="ELEM Teacher">ELEM Teacher</option>
                                    <option value="HS Faculty">HS Faculty</option>
                                    <option value="HS Teacher">HS Teacher</option>
                                    <option value="COL Faculty">COL Faculty</option>
                                    <option value="Registrar">Registrar</option>
                                    <option value="Assistant Registrar">Assistant Registrar</option>
                                    <option value="Purchasing Officer">Purchasing Officer</option>
                                    <option value="Office Staff">Office Staff</option>
                                    <option value="Accounting Clerk">Accounting Clerk</option>
                                    <option value="Library Clerk">Library Clerk</option>
                                    <option value="Librarian">Librarian</option>
                                    <option value="Guidance Councilor">Guidance Councilor</option>
                                    <option value="Maintenance">Maintenance</option>
                                    <option value="Chief Safety & Security Officer">Chief Safety & Security Officer
                                    </option>
                                    <option value="Coach">Coach</option>
                                    <option value="Cashier">Cashier</option>
                                    <option value="Principal">Principal</option>
                                </select>
                            </div>


                            <!-- Department -->
                            <div class="mb-3">
                                <label for="Department" class="form-label">Department</label>
                                <select name="Department" id="Department" class="form-select" required>
                                    <option value="" disabled selected>Select Department</option>
                                    <option value="Administration">Administration</option>
                                    <option value="College">College</option>
                                    <option value="Senior HS">Senior HS</option>
                                    <option value="Junior HS">Junior HS</option>
                                    <option value="Elementary">Elementary</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label for="Status" class="form-label">Status</label>
                                <select name="Status" id="Status" class="form-select" required>
                                    <option value="" disabled selected>Select Status</option>
                                    <option value="Regular">Regular</option>
                                    <option value="Part-Time">Part-Time</option>
                                    <option value="Probationary">Probationary</option>
                                    <option value="Temporary">Temporary</option>
                                    <option value="Contractual">Contractual</option>
                                    <option value="Resigned">Resigned</option>
                                </select>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="Password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="Password" id="Password"
                                    placeholder="Enter password" required>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="ConfirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="ConfirmPassword" id="ConfirmPassword"
                                    placeholder="Confirm password" required>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" name="create" class="btn btn-primary w-100">Register</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Form Validation Scripts -->
    <script>
        document.getElementById('FName').addEventListener('input', function () {
            const inputField = this;
            const value = inputField.value;

            // Regex for letters and whitespace
            const valid = /^[A-Za-z\s]*$/.test(value);

            if (!valid) {
                inputField.setCustomValidity('Full Name must contain only letters and spaces.');
                inputField.reportValidity();
            } else {
                inputField.setCustomValidity('');
            }
        });

        document.getElementById('StudentIDNo').addEventListener('input', function () {
            const inputField = this;
            const value = inputField.value;

            if (value.length > 12 || value.length < 9) {
                inputField.setCustomValidity('Invalid LRN or Student ID Number.');
                inputField.reportValidity();
            } else {
                inputField.setCustomValidity('');
            }
        });


        document.getElementById('Email').addEventListener('input', function () {
            const inputField = this;
            const value = inputField.value;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(value)) {
                inputField.setCustomValidity('Enter a valid email address.');
                inputField.reportValidity();
            } else {
                inputField.setCustomValidity('');
            }
        });


    </script>

</body>

</html>

<?php include 'includes/footer.php'; ?>