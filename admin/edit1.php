<?php
require('../config/database.php'); // Ensure this includes the database connection

// Check if the form was submitted from index1.php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $editID = isset($_POST['editID']) ? $_POST['editID'] : '';
    $editFN = isset($_POST['editFN']) ? $_POST['editFN'] : '';
    $editEm = isset($_POST['editEm']) ? $_POST['editEm'] : '';
    $editYL = isset($_POST['editYL']) ? $_POST['editYL'] : '';
    $editSIDNO = isset($_POST['editSIDNO']) ? $_POST['editSIDNO'] : '';
    $editG = isset($_POST['editG']) ? $_POST['editG'] : '';
    $editP = isset($_POST['editP']) ? $_POST['editP'] : '';

// } else {
//     // Redirect if accessed directly
//     echo "<script>alert('Invalid access. Please select a user to edit.'); window.location.href='studentaccounts.php';</script>";
//     exit;
}

// Handle the update process
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateID'])) {
    // Retrieve data from the form
    $updateID = $_POST['updateID'];
    $updateFN = $_POST['updateFN'];
    $updateEm = $_POST['updateEm'];
    $updateYL = $_POST['updateYL'];
    $updateSIDNO = $_POST['updateSIDNO'];
    $updateG = $_POST['updateG'];
    $updateP = $_POST['updateP']; // New password (optional)

    // If no new password is provided, keep the old one
    if (empty($updateP)) {
        $query = "SELECT Password FROM studentdb1 WHERE ID = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $updateID);
        $stmt->execute();
        $stmt->bind_result($currentPassword);
        $stmt->fetch();
        $stmt->close();
        $updateP = $currentPassword; // Retain existing password
    } else {
        $updateP = password_hash($updateP, PASSWORD_DEFAULT); // Hash new password
    }

    // Update the student record
    $query = "UPDATE studentdb1 SET FullName = ?, Email = ?, YearLevel = ?, StudentIDNo = ?, Gender = ?, Password = ? WHERE ID = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ssssssi", $updateFN, $updateEm, $updateYL, $updateSIDNO, $updateG, $updateP, $updateID);

    if ($stmt->execute()) {
        echo "<script>alert('Record updated successfully.'); window.location.href='studentaccounts.php';</script>";
    } else {
        echo "<script>alert('Error updating record: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .card {
            margin: 30px auto;
            padding: 25px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }

        .card h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-control {
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .container {
            max-width: 800px;
        }
    </style>
</head>

<body>

    <center>
        <h1 class="mt-3">Santa Rita College Reporting System</h1>
    </center>

    <div class="container">
        <div class="card">
            <form action="edit1.php" method="post">
                <h3>Edit Student Info</h3>
                <input type="hidden" name="updateID" value="<?php echo htmlspecialchars($editID); ?>" />
                <input type="text" class="form-control" name="updateFN" placeholder="Full Name"
                    value="<?php echo htmlspecialchars($editFN); ?>" required />
                <input type="email" class="form-control" name="updateEm" placeholder="Email"
                    value="<?php echo htmlspecialchars($editEm); ?>" required />
                <input type="text" class="form-control" name="updateYL" placeholder="Year Level"
                    value="<?php echo htmlspecialchars($editYL); ?>" required />
                <input type="text" class="form-control" name="updateSIDNO" placeholder="Student ID Number"
                    value="<?php echo htmlspecialchars($editSIDNO); ?>" required />
                <input type="text" class="form-control" name="updateG" placeholder="Gender"
                    value="<?php echo htmlspecialchars($editG); ?>" required />
                <input type="password" class="form-control" name="updateP"
                    placeholder="New Password (Leave blank to keep current password)" />
                <input type="submit" name="update" value="UPDATE" class="btn btn-primary mt-3" />
            </form>
        </div>
    </div>

</body>

</html>