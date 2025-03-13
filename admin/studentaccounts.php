<?php
require('./read1.php'); // Ensure read1.php includes the necessary database connection and query

$search = '';
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($connection, $_POST['search']);
    $sqlAccounts = mysqli_query($connection, "SELECT * FROM srccapstoneproject.studentdb1 WHERE FullName LIKE '%$search%' OR Email LIKE '%$search%' OR StudentIDNo LIKE '%$search%' OR YearLevel LIKE '%$search%'");
    if (!$sqlAccounts) {
        die('Error executing query: ' . mysqli_error($connection));
    }
} else {
    $sqlAccounts = mysqli_query($connection, "SELECT * FROM srccapstoneproject.studentdb1");
    if (!$sqlAccounts) {
        die('Error executing query: ' . mysqli_error($connection));
    }
}
?>

<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Management | Sky Dash</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg p-4 border-0 rounded-4">
        <h2 class="text-center fw-bold mb-4 text-primary"><i class="fas fa-user-graduate"></i> Student Accounts</h2>
        
        <!-- Search Bar with Refresh Button -->
        <form method="post" class="mb-4 d-flex align-items-center gap-2">
            <input type="text" name="search" class="form-control shadow-sm" placeholder="Search by Name, Email, ID No., or Year Level" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-dark px-4" type="submit"><i class="fas fa-search"></i> Search</button>
            <a href="" class="btn btn-outline-secondary px-4"><i class="fas fa-sync-alt"></i> Refresh</a>
        </form>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center border rounded-3">
                <thead class="table-dark">
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Year Level</th>
                        <th>Student ID No.</th>
                        <th>Student ID Photo</th>
                        <th>Gender</th>
                        <th>Password</th>
                        <th>Actions</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php while ($results = mysqli_fetch_assoc($sqlAccounts)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($results['FullName']); ?></td>
                            <td><?php echo htmlspecialchars($results['Email']); ?></td>
                            <td><?php echo htmlspecialchars($results['YearLevel']); ?></td>
                            <td><?php echo htmlspecialchars($results['StudentIDNo']); ?></td>
                            <td>
                                <?php if (!empty($results['StudentIDPhoto'])) { ?>
                                    <img src="<?php echo htmlspecialchars($results['StudentIDPhoto']); ?>" alt="Student ID" class="img-thumbnail" style="width: 50px; height: 50px;">
                                <?php } else { ?>
                                    <span class="text-muted">No photo</span>
                                <?php } ?>
                            </td>
                            <td><?php echo htmlspecialchars($results['Gender']); ?></td>
                            <td>********</td>
                            <td>
                                <form action="edit1.php" method="post" style="display:inline;">
                                    <input type="hidden" name="editID" value="<?php echo htmlspecialchars($results['ID']); ?>">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" type="submit" name="edit"><i class="fas fa-edit"></i> Edit</button>
                                </form>
                                <form action="delete1.php" method="post" style="display:inline;">
                                    <input type="hidden" name="deleteID" value="<?php echo $results['ID']; ?>">
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3" type="submit" name="delete"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            </td> 
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>

<?php include 'includes/footer.php'; ?>
