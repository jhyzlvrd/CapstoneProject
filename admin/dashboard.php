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

<style>
    body {
        background-image: url("img/RSbg2.jpg"); no-repeat center center fixed;
        background-size: cover;
        margin: 0;
        padding: 0;
        height: 100vh;
        overflow: hidden;
    }

    .dashboard-container {
        background-image: url("img/RSbg2.jpg");
        background-size: cover;
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        height: 90vh;
        text-align: center;
        margin-top: -30px;
    }

    .dashboard-title {
        font-size: 24px;
        font-weight: bold;
        color: purple;
        margin-bottom: 20px;
    }

    .stats-boxes {
        display: flex;
        gap: 20px;
    }

    .stats-box {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 200px;
    }

    .stats-box h2 {
        font-size: 40px;
        margin: 0;
        color: black;
    }

    .stats-box p {
        font-size: 14px;
        color: purple;
        font-weight: bold;
    }

    .stats-box a {
        display: inline-block;
        margin-top: 10px;
        font-size: 14px;
        color: black;
        text-decoration: none;
    }
</style>

<div class="dashboard-container">
    <div class="dashboard-title"><h1>Requisition Management System</h1></div>

    <div class="stats-boxes">
        <div class="stats-box">
            <h2>0</h2>
            <p>Facility Reservation</p>
            <a href="#">View Details ▶</a>
        </div>

        <div class="stats-box">
            <h2><?php echo $total_reports; ?></h2>
            <p>Service Request</p>
            <a href="Complaints.php">View Details ▶</a>
        </div>

        <div class="stats-box">
            <h2>0</h2>
            <p>Facility Schedule</p>
            <a href="#">View Details ▶</a>
        </div>

        <div class="stats-box">
            <h2>0</h2>
            <p>Recent Reservation</p>
            <a href="#">View Details ▶</a>
        </div>

        <div class="stats-box">
            <h2><?php echo $total_employees; ?></h2>
            <p>Employee Accounts</p>
            <a href="employeeaccounts.php">View Details ▶</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
