<?php
require('../config/database.php'); // Ensure the database connection is included

$search = '';
$sqlReports = null; // Initialize to prevent errors

if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($connection, $_POST['search']);
    $query = "SELECT * FROM srccapstoneproject.reports WHERE FullName LIKE '%$search%' OR EmployeeID LIKE '%$search%' OR Email LIKE '%$search%' OR Department LIKE '%$search%' OR Location LIKE '%$search%' OR Asset_tag LIKE '%$search%' OR Subject LIKE '%$search%' OR Remarks LIKE '%$search%'";
} else {
    $query = "SELECT * FROM srccapstoneproject.reports";
}

$sqlReports = mysqli_query($connection, $query);

// Check for errors in SQL query
if (!$sqlReports) {
    die("Query failed: " . mysqli_error($connection));
}

if (isset($_POST['delete']) && isset($_POST['deleteID'])) {
    $deleteID = mysqli_real_escape_string($connection, $_POST['deleteID']);

    // SQL query to delete the report
    $deleteQuery = "DELETE FROM srccapstoneproject.reports WHERE ID = '$deleteID'";

    if (mysqli_query($connection, $deleteQuery)) {
        echo "<script>alert('Report deleted successfully!'); window.location.href='complaints.php';</script>";
    } else {
        echo "<script>alert('Error deleting report: " . mysqli_error($connection) . "');</script>";
    }
}

?>

<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Management</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body class="bg-light">

    <div class="container mt-5" style="max-width: 100%;">
        <div class="card shadow-lg p-4 border-0 rounded-4">
            <h2 class="text-center fw-bold mb-4 text-primary"><i class="fas fa-clipboard-list"></i> Service Requests
            </h2>

            <!-- Search Bar with Refresh Button -->
            <form method="post" class="mb-4 d-flex align-items-center gap-2">
                <input type="text" name="search" class="form-control shadow-sm" placeholder="Search reports..."
                    value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-dark px-4" type="submit"><i class="fas fa-search"></i> Search</button>
                <a href="" class="btn btn-outline-secondary px-4"><i class="fas fa-sync-alt"></i> Refresh</a>
            </form>

            <!-- Reports Displayed as Boxes -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Subject</th>
                            <th>Complainant</th>
                            <th>Email</th>
                            <th>Date and Time</th>
                            <th>Specific Problem</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($results = mysqli_fetch_assoc($sqlReports)) { ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($results['Subject']); ?></strong></td>
                                <td><?php echo htmlspecialchars($results['FullName']); ?></td>
                                <td><?php echo htmlspecialchars($results['Email']); ?></td>
                                <td><?php echo htmlspecialchars($results['Date_time']); ?></td>
                                <td><?php echo htmlspecialchars($results['Specific_problem']); ?></td>
                                <td><?php echo htmlspecialchars($results['Location']); ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" type="button" onclick="editComplaint(
                                '<?php echo $results['ID']; ?>',
                                '<?php echo addslashes($results['FullName']); ?>',
                                '<?php echo addslashes($results['EmployeeID']); ?>',
                                '<?php echo addslashes($results['Email']); ?>',
                                '<?php echo addslashes($results['Date_time']); ?>',
                                '<?php echo addslashes($results['Subject']); ?>',
                                '<?php echo addslashes($results['Remarks']); ?>',
                                '<?php echo addslashes($results['Department']); ?>',
                                '<?php echo addslashes($results['Location']); ?>',
                                '<?php echo addslashes($results['c_user']); ?>',
                                '<?php echo addslashes($results['Asset_tag']); ?>',
                                '<?php echo addslashes($results['Specific_problem']); ?>'
                            )" data-bs-toggle="modal" data-bs-target="#editComplaintModal">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>

                                        <form method="post"
                                            onsubmit="return confirm('Are you sure you want to delete this report?');">
                                            <input type="hidden" name="deleteID"
                                                value="<?php echo htmlspecialchars($results['ID']); ?>">
                                            <button class="btn btn-sm btn-outline-danger" type="submit" name="delete">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>

                                        <button class="btn btn-sm btn-outline-success"
                                            onclick="printReport(<?php echo $results['ID']; ?>)">
                                            <i class="fas fa-print"></i> Print
                                        </button>
                                        <a href="../PHPMailer/index.php?email=<?php echo urlencode($results['Email']); ?>&subject=<?php echo urlencode($results['Subject']); ?>&name=<?php echo urlencode($results['FullName']); ?>&date=<?php echo urlencode($results['Date_time']); ?>"
                                            class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-envelope"></i> Email
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Complaint Modal -->
    <div class="modal fade" id="editComplaintModal" tabindex="-1" aria-labelledby="editComplaintModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Enlarged modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editComplaintModalLabel">Edit Complaint Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="update_report.php" method="post">
                        <input type="hidden" id="editID" name="editID">

                        <div class="row">
                            <!-- First Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editFullName" class="form-label">Complainant Name</label>
                                    <input type="text" class="form-control" id="editFullName" name="FullName" required>
                                </div>

                                <div class="mb-3">
                                    <label for="editEmployeeID" class="form-label">Employee ID</label>
                                    <input type="text" class="form-control" id="editEmployeeID" name="EmployeeID">
                                </div>

                                <div class="mb-3">
                                    <label for="editEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="editEmail" name="Email">
                                </div>

                                <div class="mb-3">
                                    <label for="editDateTime" class="form-label">Date and Time</label>
                                    <input type="datetime-local" class="form-control" id="editDateTime" name="Date and Time">
                                </div>

                                <div class="mb-3">
                                    <label for="editSubject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="editSubject" name="Subject" required>
                                </div>
                            </div>

                            <!-- Second Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editDepartment" class="form-label">Department</label>
                                    <input type="text" class="form-control" id="editDepartment" name="Department">
                                </div>

                                <div class="mb-3">
                                    <label for="editLocation" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="editLocation" name="Location">
                                </div>

                                <div class="mb-3">
                                    <label for="editCurrentUser" class="form-label">Current User</label>
                                    <input type="text" class="form-control" id="editCurrentUser" name="c_user">
                                </div>

                                <div class="mb-3">
                                    <label for="editAssetTag" class="form-label">Asset Tag</label>
                                    <input type="text" class="form-control" id="editAssetTag" name="Asset Tag">
                                </div>

                                <div class="mb-3">
                                    <label for="editSpecificProblem" class="form-label">Specific Problem</label>
                                    <input type="text" class="form-control" id="editSpecificProblem" name="Specific_problem">
                                </div>

                            </div>
                        </div>

                        <!-- Full Width Fields -->
                        <div class="mb-3">
                            <label for="editRemarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="editRemarks" name="Remarks"
                                rows="2"></textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Complaint</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printReport(id) {
            var content = document.getElementById("printSection" + id).innerHTML;
            var printWindow = window.open('', '', 'width=800,height=600');
            printWindow.document.write('<html><head><title>Service Request</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<div class="container mt-4">' + content + '</div>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>

    <script>
        function editComplaint(id, fullName, employeeID, email, dateTime, subject, remarks, department, location, currentUser, assetTag, specificProblem) {
            document.getElementById("editID").value = id;
            document.getElementById("editFullName").value = fullName;
            document.getElementById("editEmployeeID").value = employeeID;
            document.getElementById("editEmail").value = email;
            document.getElementById("editDateTime").value = dateTime;
            document.getElementById("editSubject").value = subject;
            document.getElementById("editRemarks").value = remarks;
            document.getElementById("editDepartment").value = department;
            document.getElementById("editLocation").value = location;
            document.getElementById("editCurrentUser").value = currentUser;
            document.getElementById("editAssetTag").value = assetTag;
            document.getElementById("editSpecificProblem").value = specificProblem;
        }

    </script>

</body>

</html>
<?php include 'includes/footer.php'; ?>