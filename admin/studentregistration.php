<?php
// Include database connection
require('../config/database.php'); // Make sure this file defines $connection correctly

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create'])) {
    // Retrieve and sanitize form data
    $FName = mysqli_real_escape_string($connection, trim($_POST['FName']));
    $Email = mysqli_real_escape_string($connection, trim($_POST['Email']));
    $Yearlevel = mysqli_real_escape_string($connection, trim($_POST['Yearlevel']));
    $StudentIDN = mysqli_real_escape_string($connection, trim($_POST['StudentIDNo']));
    $Password = trim($_POST['password']);
    $ConfirmPassword = trim($_POST['confirm_password']);
    $Gender = mysqli_real_escape_string($connection, trim($_POST['Gender']));

    // Validate Full Name
    if (!preg_match('/^[A-Za-z\s]+$/', $FName)) {
        echo '<script>alert("Full Name must contain only letters and spaces."); window.history.back();</script>';
        exit;
    }

    // Validate Student ID Number
    if (!preg_match('/^\d{9}$|^\d{12}$/', $StudentIDN)) {
        echo '<script>alert("Invalid LRN or Student ID Number."); window.history.back();</script>';
        exit;
    }

    // Validate required fields
    if (empty($FName) || empty($Email) || empty($Yearlevel) || empty($StudentIDN) || empty($Password) || empty($ConfirmPassword) || empty($Gender)) {
        echo '<script>alert("All fields are required."); window.history.back();</script>';
        exit;
    }

    // Check if passwords match
    if ($Password !== $ConfirmPassword) {
        echo '<script>alert("Passwords do not match."); window.history.back();</script>';
        exit;
    }

    // Check if email is already in use
    $emailCheckQuery = "SELECT Email FROM srccapstoneproject.studentdb1 WHERE Email = ?";
    if ($emailStmt = mysqli_prepare($connection, $emailCheckQuery)) {
        mysqli_stmt_bind_param($emailStmt, "s", $Email);
        mysqli_stmt_execute($emailStmt);
        mysqli_stmt_store_result($emailStmt);

        if (mysqli_stmt_num_rows($emailStmt) > 0) {
            echo '<script>alert("Email is already in use. Please use a different email."); window.history.back();</script>';
            mysqli_stmt_close($emailStmt);
            exit;
        }
        mysqli_stmt_close($emailStmt);
    } else {
        echo '<script>alert("Error checking email: ' . mysqli_error($connection) . '");</script>';
        exit;
    }

    // Hash the password
    $HashedPassword = password_hash($Password, PASSWORD_BCRYPT);

    // Handle file upload
    if (isset($_FILES['StudentIDPhoto']) && $_FILES['StudentIDPhoto']['error'] == 0) {
        // Get file details
        $fileTmpPath = $_FILES['StudentIDPhoto']['tmp_name'];
        $fileName = basename($_FILES['StudentIDPhoto']['name']);
        $fileSize = $_FILES['StudentIDPhoto']['size'];
        $fileType = $_FILES['StudentIDPhoto']['type'];

        // Ensure the file is an image
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($fileType, $allowedTypes)) {
            echo '<script>alert("Please upload a valid image file (JPEG, PNG, GIF)."); window.history.back();</script>';
            exit;
        }

        // Check file size (limit to 5MB)
        if ($fileSize > 5 * 1024 * 1024) {
            echo '<script>alert("File size must be less than 5MB."); window.history.back();</script>';
            exit;
        }

        // Create a unique file name to avoid overwriting
        $uploadDir = 'uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Ensure the directory exists
        }
        $filePath = $uploadDir . time() . '_' . $fileName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($fileTmpPath, $filePath)) {
            echo '<script>alert("Failed to upload the image."); window.history.back();</script>';
            exit;
        }
    } else {
        // If no photo is uploaded, set $filePath to NULL
        $filePath = NULL;
    }

    // Insert new student record
    $queryCreate = "INSERT INTO srccapstoneproject.studentdb1 (FullName, Email, YearLevel, StudentIDNo, Password, Gender, StudentIDPhoto) VALUES (?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($connection, $queryCreate)) {
        mysqli_stmt_bind_param($stmt, "sssssss", $FName, $Email, $Yearlevel, $StudentIDN, $HashedPassword, $Gender, $filePath);

        if (!mysqli_stmt_execute($stmt)) {
            echo '<script>alert("Registration failed: ' . mysqli_stmt_error($stmt) . '");</script>';
        } else {
            echo '<script>alert("Successfully Created"); window.location.href = "studentregistration.php";</script>';
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
    <title>Student Registration Form</title>
    <link href="img/SRCLogoNB.png" rel="icon">
    <style>
        body {
         
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
                    <h4 class="mb-0">Add New Student</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
                        
                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="FName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="FName" id="FName" placeholder="Enter full name" required pattern="[A-Za-z\s]+" title="Only letters and spaces allowed.">
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="Email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="Email" id="Email" placeholder="Enter email" required>
                        </div>

                        <!-- Year Level -->
                        <div class="mb-3">
                            <label for="Yearlevel" class="form-label">Year Level</label>
                            <select name="Yearlevel" id="Yearlevel" class="form-select" required>
                                <option value="" disabled selected>Select year level</option>
                                <option value="Grade 7">Grade 7</option>
                                <option value="Grade 8">Grade 8</option>
                                <option value="Grade 9">Grade 9</option>
                                <option value="Grade 10">Grade 10</option>
                                <option value="Grade 11">Grade 11</option>
                                <option value="Grade 12">Grade 12</option>
                                <option value="1st Year College">1st Year College</option>
                                <option value="2nd Year College">2nd Year College</option>
                                <option value="3rd Year College">3rd Year College</option>
                                <option value="4th Year College">4th Year College</option>
                            </select>
                        </div>

                        <!-- Gender Dropdown -->
                        <div class="mb-3">
                            <label for="Gender" class="form-label">Gender</label>
                            <select name="Gender" id="Gender" class="form-select" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>

                        <!-- Student ID -->
                        <div class="mb-3">
                            <label for="StudentIDNo" class="form-label">LRN or Student ID No.</label>
                            <input type="text" class="form-control" name="StudentIDNo" id="StudentIDNo" placeholder="Enter Student ID" required pattern="^\d{9}$|^\d{12}$">
                        </div>

                        <!-- Student ID Photo -->
                        <div class="mb-3">
                            <label for="StudentIDPhoto" class="form-label">Student ID Photo</label>
                            <input type="file" class="form-control" name="StudentIDPhoto" id="StudentIDPhoto" accept="image/*" required>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="Password" placeholder="Enter password" required>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password" id="ConfirmPassword" placeholder="Confirm password" required>
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
</script>
</body>
</html>
<?php include 'includes/footer.php'; ?>
