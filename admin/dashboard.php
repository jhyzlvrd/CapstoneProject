<?php
include 'includes/header.php';
require('../config/database.php');

// Query to count the number of employees
$queryEmployees = "SELECT COUNT(*) AS total_employees FROM srccapstoneproject.employeedb";
$resultEmployees = mysqli_query($connection, $queryEmployees);
$rowEmployees = mysqli_fetch_assoc($resultEmployees);
$total_employees = $rowEmployees['total_employees'];

// Query to count the number of service requests (reports)
$queryReports = "SELECT COUNT(*) AS total_reports FROM srccapstoneproject.reports";
$resultReports = mysqli_query($connection, $queryReports);
$rowReports = mysqli_fetch_assoc($resultReports);
$total_reports = $rowReports['total_reports'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisition Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .dashboard-container {
            background-image: url("img/RSbg2.jpg");
            background-size: cover;
            padding: 40px 20px;
            text-align: center;
            border-radius: 10px;
        }
        .dashboard-title {
            font-size: 24px;
            font-weight: bold;
            color: white;
            background: navy;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);
            display: inline-block;
        }
        .stats-box {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stats-box:hover {
            transform: scale(1.05);
            box-shadow: 8px 8px 20px rgba(0, 0, 0, 0.3);
        }
        .stats-box i {
            font-size: 40px;
            color: navy;
            margin-bottom: 10px;
        }
        .stats-box h2 {
            font-size: 40px;
            margin: 0;
            color: black;
        }
        .stats-box p {
            font-size: 16px;
            color: navy;
            font-weight: bold;
        }
        .stats-box a {
            display: inline-block;
            margin-top: 10px;
            font-size: 14px;
            color: black;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        .stats-box a:hover {
            color: turquoise;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="dashboard-container text-center">
            <h1 class="dashboard-title">Requisition Management System</h1>
            <div class="row mt-4 g-4 justify-content-center">
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="stats-box">
                        <i class="fas fa-calendar-check"></i>
                        <h2>0</h2>
                        <p>Facility Reservation</p>
                        <a href="#">View Details ▶</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="stats-box">
                        <i class="fas fa-tools"></i>
                        <h2><?php echo $total_reports; ?></h2>
                        <p>Service Request</p>
                        <a href="Complaints.php">View Details ▶</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="stats-box">
                        <i class="fas fa-clock"></i>
                        <h2>0</h2>
                        <p>Facility Schedule</p>
                        <a href="#">View Details ▶</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="stats-box">
                        <i class="fas fa-calendar-alt"></i>
                        <h2>0</h2>
                        <p>Recent Reservation</p>
                        <a href="#">View Details ▶</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="stats-box">
                        <i class="fas fa-users"></i>
                        <h2><?php echo $total_employees; ?></h2>
                        <p>Employee Accounts</p>
                        <a href="employeeaccounts.php">View Details ▶</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'includes/footer.php'; ?>
