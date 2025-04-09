<?php
session_start();
require("../config/database.php");

if (!isset($_SESSION['EmployeeID'])) {
    header("Location: employeelogin.php");
    exit();
}

$employeeID = $_SESSION['EmployeeID'];

// Fetch personal Reports
$reports_query = "SELECT ReportID, Concern, Date_time, status FROM srccapstoneproject.reports WHERE EmployeeID = ?";
$stmt = $connection->prepare($reports_query);
$stmt->bind_param("i", $employeeID);
$stmt->execute();
$reports_result = $stmt->get_result();

// Fetch all Reservations (optional: filter by EmployeeID if needed too)
// $reservations_query = "SELECT reservation_id, facility, date, status FROM srccapstoneproject.reservations WHERE EmployeeID = ?";
// $stmt2 = $connection->prepare($reservations_query);
// $stmt2->bind_param("i", $employeeID);
// $stmt2->execute();
// $reservations_result = $stmt2->get_result();
// ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Transactions</title>  
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            padding-top: 50px; /* Added space for the header */
        }

        .container {
            width: 100%;
            max-width: 1200px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            margin: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 2rem;
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        h2 {
            color: #555;
            margin-bottom: 15px;
            font-size: 1.4rem;
            border-bottom: 2px solid #f1f1f1;
            padding-bottom: 5px;
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
            font-size: 1.1rem;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td {
            color: #555;
            font-size: 1rem;
        }

        .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .status.pending {
            background-color: #ffcc00;
            color: #333;
        }

        .status.resolved {
            background-color: #28a745;
            color: white;
        }

        .status.confirmed {
            background-color: #007BFF;
            color: white;
        }

        .status.cancelled {
            background-color: #dc3545;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
                margin: 10px;
            }

            h1 {
                font-size: 1.6rem;
            }

            h2 {
                font-size: 1.2rem;
            }

            table th, table td {
                padding: 8px;
                font-size: 0.9rem;
            }

            table {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.4rem;
            }

            h2 {
                font-size: 1rem;
            }

            table th, table td {
                padding: 6px;
                font-size: 0.8rem;
            }

            .status {
                font-size: 0.8rem;
                padding: 4px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Transactions</h1>

        <!-- Reports Table -->
        <h2>Reports</h2>
        <table>
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>Concern</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through and display reports data
                if ($reports_result->num_rows > 0) {
                    while ($report = $reports_result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $report['ReportID'] . "</td>
                                <td>" . $report['Concern'] . "</td>
                                <td>" . $report['Date_time'] . "</td>
                                <td><span class='status " . strtolower($report['status']) . "'>" . $report['status'] . "</span></td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No reports found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Reservations Table -->
        <h2>Reservations</h2>
        <table>
            <thead>
                <tr>
                    <th>Reservation ID</th>
                    <th>Facility</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through and display reservations data
                if ($reservations_result->num_rows > 0) {
                    while ($reservation = $reservations_result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $reservation['reservation_id'] . "</td>
                                <td>" . $reservation['facility'] . "</td>
                                <td>" . $reservation['date'] . "</td>
                                <td><span class='status " . strtolower($reservation['status']) . "'>" . $reservation['status'] . "</span></td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No reservations found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <a href="javascript:history.back()" class="back-button">Back</a>
    </div>
</body>
</html>
<?php
// Close the database connection
$connection->close();
?>
