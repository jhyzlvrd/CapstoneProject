<?php
require('../config/database.php'); // Ensure database connection

$search = '';
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($connection, $_POST['search']);
    $sqlAccounts = mysqli_query($connection, "SELECT * FROM srccapstoneproject.employeedb WHERE FullName LIKE '%$search%' OR EmployeeID LIKE '%$search%' OR Email LIKE '%$search%' OR Department LIKE '%$search%' OR Designation LIKE '%$search%'");
} else {
    $sqlAccounts = mysqli_query($connection, "SELECT * FROM srccapstoneproject.employeedb");
}

// Handle AJAX request to update employee data
if (isset($_POST['updateEmployee'])) {
    $id = $_POST['editID'];
    $fullName = $_POST['fullName'];
    $employeeID = $_POST['employeeID'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $designation = $_POST['designation'];
    $department = $_POST['department'];
    $status = $_POST['status'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Update the database using prepared statements
    $updateQuery = $connection->prepare("UPDATE srccapstoneproject.employeedb SET FullName=?, EmployeeID=?, Email=?, Gender=?, Designation=?, Department=?, Status=?, Password=? WHERE ID=?");
    $updateQuery->bind_param("ssssssssi", $fullName, $employeeID, $email, $gender, $designation, $department, $status, $password, $id);

    if ($updateQuery->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $connection->error]);
    }
    exit;
}

// Handle AJAX request to delete employee
if (isset($_POST['deleteEmployee'])) {
    $id = $_POST['deleteID'];

    // Delete the employee using prepared statements
    $deleteQuery = $connection->prepare("DELETE FROM srccapstoneproject.employeedb WHERE ID=?");
    $deleteQuery->bind_param("i", $id);

    if ($deleteQuery->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $connection->error]);
    }
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Management</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow-lg p-4 border-0 rounded-4">
            <h2 class="text-center fw-bold mb-4 text-primary"><i class="fas fa-users"></i> Employee Accounts</h2>

            <!-- Search Bar -->
            <form method="post" class="mb-4 d-flex align-items-center gap-2">
                <input type="text" name="search" class="form-control shadow-sm" placeholder="Search..."
                    value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-dark px-4" type="submit"><i class="fas fa-search"></i> Search</button>
                <a href="" class="btn btn-outline-secondary px-4"><i class="fas fa-sync-alt"></i> Refresh</a>
            </form>

            <div class="table-responsive">
                <table class="table table-hover text-center border rounded-3">
                    <thead class="table-dark">
                        <tr>
                            <th>Full Name</th>
                            <th>Employee ID</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Password</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($results = mysqli_fetch_assoc($sqlAccounts)) { ?>
                            <tr id="row-<?php echo $results['ID']; ?>">
                                <td><?php echo htmlspecialchars($results['FullName']); ?></td>
                                <td><?php echo htmlspecialchars($results['EmployeeID']); ?></td>
                                <td><?php echo htmlspecialchars($results['Email']); ?></td>
                                <td><?php echo htmlspecialchars($results['Gender']); ?></td>
                                <td><?php echo htmlspecialchars($results['Designation']); ?></td>
                                <td><?php echo htmlspecialchars($results['Department']); ?></td>
                                <td>
                                    <span
                                        class="badge bg-<?php echo ($results['Status'] == 'Regular') ? 'success' : (($results['Status'] == 'Resigned') ? 'danger' : 'warning'); ?>">
                                        <?php echo htmlspecialchars($results['Status']); ?>
                                    </span>
                                </td>
                                <td>********</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal"
                                        data-bs-target="#editModal" data-id="<?php echo $results['ID']; ?>"
                                        data-fullname="<?php echo htmlspecialchars($results['FullName']); ?>"
                                        data-employeeid="<?php echo htmlspecialchars($results['EmployeeID']); ?>"
                                        data-email="<?php echo htmlspecialchars($results['Email']); ?>"
                                        data-gender="<?php echo htmlspecialchars($results['Gender']); ?>"
                                        data-designation="<?php echo htmlspecialchars($results['Designation']); ?>"
                                        data-department="<?php echo htmlspecialchars($results['Department']); ?>"
                                        data-status="<?php echo htmlspecialchars($results['Status']); ?>"
                                        data-status="<?php echo htmlspecialchars($results['Password']); ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-btn"
                                        data-id="<?php echo $results['ID']; ?>">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Landscape Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 600px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold" id="editModalLabel">Edit Employee Information</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" name="editID" id="editID">
                        <input type="hidden" name="updateEmployee" value="1">

                        <div class="row g-2">
                            <div class="col-md-6">
                                <b><label class="form-label">Full Name:</label></b>
                                <input type="text" class="form-control" name="fullName" id="editFullName" required>
                            </div>
                            <div class="col-md-6">
                                <b><label class="form-label">Employee ID:</label></b>
                                <input type="text" class="form-control" name="employeeID" id="editEmployeeID" required>
                            </div>
                            <div class="col-md-6">
                                <b><label class="form-label">Email:</label></b>
                                <input type="email" class="form-control" name="email" id="editEmail" required>
                            </div>
                            <div class="col-md-6">
                                <b><label class="form-label">Gender:</label></b>
                                <select class="form-select" name="gender" id="editGender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <b><label class="form-label">Designation:</label></b>
                                <select class="form-select" name="designation" id="editDesignation" required>
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
                            <div class="col-md-6">
                                <b><label class="form-label">Department:</label></b>
                                <select class="form-select" name="department" id="editDepartment" required>
                                    <option value="" disabled selected>Select Department</option>
                                    <option value="Administration">Administration</option>
                                    <option value="College">College</option>
                                    <option value="Senior HS">Senior HS</option>
                                    <option value="Junior HS">Junior HS</option>
                                    <option value="Elementary">Elementary</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <b><label class="form-label">Status:</label></b>
                                <select class="form-select" name="status" id="editStatus" required>
                                    <option value="Regular">Regular</option>
                                    <option value="Part-Time">Part-Time</option>
                                    <option value="Resigned">Resigned</option>
                                    <option value="Temporary">Temporary</option>
                                    <option value="Probationary">Probationary</option>
                                    <option value="Contractual">Contractual</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <b><label class="form-label">Password:</label></b>
                                <input type="password" class="form-control" name="password" id="editPassword" required>
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function () {
        document.getElementById('editID').value = this.dataset.id;
        document.getElementById('editFullName').value = this.dataset.fullname;
        document.getElementById('editEmployeeID').value = this.dataset.employeeid;
        document.getElementById('editEmail').value = this.dataset.email;
        document.getElementById('editGender').value = this.dataset.gender;
        document.getElementById('editDesignation').value = this.dataset.designation;
        document.getElementById('editDepartment').value = this.dataset.department;
        document.getElementById('editStatus').value = this.dataset.status;
        document.getElementById('editPassword').value = this.dataset.password;
    });
});

document.getElementById('editForm').addEventListener('submit', function (e) {
    e.preventDefault();
    fetch("", { method: "POST", body: new FormData(this) })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                setTimeout(() => {
                    location.reload();
                }, 500); // Delay to ensure update completion
            } else {
                alert("Error updating employee: " + data.message);
            }
        })
        .catch(error => console.error("Error:", error));
});

document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function () {
        if (confirm("Are you sure you want to delete this employee?")) {
            let formData = new FormData();
            formData.append('deleteEmployee', '1');
            formData.append('deleteID', this.dataset.id);

            fetch("", { method: "POST", body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        setTimeout(() => {
                            location.reload();
                        }, 500); // Slight delay for smooth reloading
                    } else {
                        alert("Error deleting employee: " + data.message);
                    }
                })
                .catch(error => console.error("Error:", error));
        }
    });
});

    </script>

</body>

</html>

<?php include 'includes/footer.php'; ?>