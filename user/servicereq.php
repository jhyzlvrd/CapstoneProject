<?php
session_start();
require_once("../config/database.php");

// Ensure the user is logged in
if (!isset($_SESSION['EmployeeID'])) {
    header("Location: employeelogin.php"); // Redirect to login if not authenticated
    exit();
}

// Get the logged-in employee's ID
$employeeID = $_SESSION['EmployeeID'];

// Fetch employee details from the database
$query = "SELECT FullName, EmployeeID, Email, Department FROM srccapstoneproject.employeedb WHERE EmployeeID = ?";
$stmt = $connection->prepare($query);   
$stmt->bind_param("i", $employeeID);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

// Set user details
if ($employee) {
    $fullName = $employee['FullName'];
    $employeeID = $employee['EmployeeID'];
    $email = $employee['Email'];
    $department = $employee['Department']; 
} else {
    $fullName = '';
    $employeeID = '';
    $email = '';
    $department = '';
}

// Set the timezone (adjust as needed)
date_default_timezone_set("Asia/Manila");

// Get current date and time for pre-filling
$currentDateTime = date("Y-m-d\TH:i"); // Format for datetime-local input

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Request Form</title>
    <link href="img/SRCLogoNB.png" rel="icon">
    <link rel="stylesheet" href="css/servicereq.css">
    <script>
        function toggleSpecificProblem() {
            var subject = document.getElementById("subject").value;
            var specificProblemContainer = document.getElementById("specificProblemContainer");
            var specificProblem = document.getElementById("specificProblem");

            specificProblem.innerHTML = "";

            var problemOptions = {
                "PC": ["Overheating", "Power Supply", "HDD Failure", "Network Issue", "SSD Failure", "Blue Screen", "Software Malfunction", "Virus Attack"],
                "Printer": ["Paper jams", "Low Ink", "Printer not responding", "Poor print quality", "Cartridge issue", "Printer offline", "Slow printing"]
            };

            if (problemOptions[subject]) {
                specificProblemContainer.style.display = "block";
                specificProblem.innerHTML = "<option disabled selected>Select Problem</option>";

                problemOptions[subject].forEach(function (problem) {
                    var option = document.createElement("option");
                    option.value = problem;
                    option.text = problem;
                    specificProblem.appendChild(option);
                });
            } else {
                specificProblemContainer.style.display = "none";
            }
        }
    </script>
</head>

<body>
    <div class="form-container">
        <div style="text-align: center;">
            <img src="img/SRCLogoNB.png" alt="SRC Logo" style="width: 50px;">
            <h2>Service Request Form</h2>
        </div>

        <form action="submit_request.php" method="POST" onsubmit="updateDateTime()">
            <div class="form-group">
                <label for="fullName">Full Name:</label>
                <input type="text" id="fullName" name="fullName" value="<?= htmlspecialchars($fullName); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="employeeID">Employee ID:</label>
                <input type="number" id="employeeID" name="employeeID" value="<?= htmlspecialchars($employeeID); ?>"
                    readonly>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="date">Date & Time:</label>
                <input type="datetime-local" id="date" name="date" value="<?= htmlspecialchars($currentDateTime); ?>"
                    required readonly>
            </div>

            <div class="form-group">
                <label for="assetTag">Asset Tag/SN:</label>
                <input type="text" id="assetTag" name="assetTag">
            </div>

            <div class="form-group">
                <label for="subject">Concern:</label>
                <select id="subject" name="subject" required onchange="toggleSpecificProblem()">
                    <option value="" disabled selected>Select Subject</option>
                    <option value="PC">PC</option>
                    <option value="Printer">Printer</option>
                    <option value="Projector">Projector</option>
                    <option value="White Screen">White Screen</option>
                    <option value="Microphone">Microphone</option>
                    <option value="Sound System">Sound System</option>
                    <option value="Speaker">Speaker</option>
                    <option value="AirCon">Air Conditioner</option>
                </select>
            </div>

            <div id="specificProblemContainer" style="display: none;" class="form-group">
                <label for="specificProblem">Specific Problem:</label>
                <select id="specificProblem" name="specificProblem"></select>
            </div>

            <div class="form-group">
                <label for="department">Department:</label>
                <input type="text" id="department" name="department" value="<?= htmlspecialchars($department); ?>"
                    readonly>
            </div>

            <div class="form-group">
                <label for="location">Location:</label>
                <select id="location" name="location" required>
                    <option value="" disabled selected>Select Location</option>
                    <option value="College Faculty">College Faculty</option>
                    <option value="Library">Library</option>
                    <option value="Canteen">Canteen</option>
                    <option value="Computer Lab A">Computer Lab A</option>
                    <option value="Computer Lab B">Computer Lab B</option>
                </select>
            </div>

            <div class="form-group">
                <label for="currentUser">Current User:</label>
                <input type="text" id="currentUser" name="currentUser" required>
            </div>

            <div class="form-group">
                <label for="remarks">Remarks:</label>
                <textarea id="remarks" name="remarks" required></textarea>
            </div>

            <div class="button-container">
                <button type="submit" name="submit">Submit Request</button>
                <button type="button" class="back-button" onclick="window.location.href='index.php'">Back</button>
            </div>
        </form>
    </div>

    <script>
        // Function to update the date and time when the form is submitted
        function updateDateTime() {
            var currentDateTime = new Date().toISOString().slice(0, 16); 
            document.getElementById("date").value = currentDateTime; 
        }
    </script>

</body>

</html>