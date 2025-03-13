<?php
require('../config/database.php'); // Ensure this includes the database connection

// Check if the form was submitted from index1.php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $editID = isset($_POST['editID']) ? $_POST['editID'] : '';
    $editFN = isset($_POST['editFN']) ? $_POST['editFN'] : '';
    $editEID = isset($_POST['editEID']) ? $_POST['editEID'] : '';
    $editEmail = isset($_POST['editEmail']) ? $_POST['editEmail'] : '';
    $editG = isset($_POST['editG']) ? $_POST['editG'] : '';
    $editD = isset($_POST['editD']) ? $_POST['editD'] : '';
    $editDept = isset($_POST['editDept']) ? $_POST['editDept'] : '';
    $editS = isset($_POST['editS']) ? $_POST['editS'] : '';
}

// Handle the update process
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateID'])) {
    $updateID = $_POST['updateID'];
    $updateFN = $_POST['updateFN'];
    $updateEID = $_POST['updateEID'];
    $updateEmail = $_POST['updateEmail'];
    $updateG = $_POST['updateG'];
    $updateD = $_POST['updateD'];
    $updateDept = $_POST['updateDept'];
    $updateS = $_POST['updateS'];
    $updateP = $_POST['updateP'];

    if (empty($updateP)) {
        $query = "SELECT Password FROM employeedb WHERE ID = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $updateID);
        $stmt->execute();
        $stmt->bind_result($currentPassword);
        $stmt->fetch();
        $stmt->close();
        $updateP = $currentPassword;
    } else {
        $updateP = password_hash($updateP, PASSWORD_DEFAULT);
    }

    $query = "UPDATE employeedb SET FullName = ?, EmployeeID = ?, Gender = ?, Designation = ?, Department = ?, Status = ?, Email = ?, Password = ? WHERE ID = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ssssssssi", $updateFN, $updateEID, $updateG, $updateD, $updateDept, $updateS, $updateEmail, $updateP, $updateID);


    if ($stmt->execute()) {
        echo "<script>alert('Record updated successfully.'); window.location.href='employeeaccounts.php';</script>";
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
    <title>Edit Employee Information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
        }

        .card {
            padding: 30px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .card h3 {
            text-align: center;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            padding: 10px;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <h3>Edit Employee Information</h3>
            <form action="edit2.php" method="post">
                <input type="hidden" name="updateID" value="<?php echo htmlspecialchars($editID); ?>" />
                <label for="updateFN" class="form-label">Full Name</label>
                <input type="text" class="form-control" name="updateFN" id="updateFN"
                    value="<?php echo htmlspecialchars($editFN); ?>" required />

                <label for="updateEID" class="form-label">Employee ID</label>
                <input type="text" class="form-control" name="updateEID" id="updateEID"
                    value="<?php echo htmlspecialchars($editEID); ?>" required />

                <label for="updateEmail" class="form-label">Email</label>
                <input type="email" class="form-control" name="updateEmail" id="updateEmail"
                    value="<?php echo htmlspecialchars($editEmail); ?>" required />


                <label for="updateG" class="form-label">Gender</label>
                <input type="text" class="form-control" name="updateG" id="updateG"
                    value="<?php echo htmlspecialchars($editG); ?>" required />

                <label for="updateD" class="form-label">Designation</label>
                <input type="text" class="form-control" name="updateD" id="updateD"
                    value="<?php echo htmlspecialchars($editD); ?>" required />

                <label for="updateDept" class="form-label">Department</label>
                <input type="text" class="form-control" name="updateDept" id="updateDept"
                    value="<?php echo htmlspecialchars($editDept); ?>" required />

                <label for="updateS" class="form-label">Status</label>
                <input type="text" class="form-control" name="updateS" id="updateS"
                    value="<?php echo htmlspecialchars($editS); ?>" required />

                <label for="updateP" class="form-label">New Password</label>
                <input type="password" class="form-control" name="updateP" id="updateP"
                    placeholder="Leave blank to keep current password" />

                <button type="submit" name="update" class="btn btn-primary mt-3">UPDATE</button>
            </form>
        </div>
    </div>
</body>

</html>