<?php
session_start();
require_once("../config/database.php");

// Ensure the user is logged in
if (!isset($_SESSION['EmployeeID'])) {
    header("Location: employeelogin.php"); 
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
date_default_timezone_set("Asia/Manila"); // Change this to your region if necessary

// Get current date and time in proper format
$currentDateTime = date("Y-m-d\TH:i");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Request Form</title>
    <!-- Favicon -->
    <link href="img/SRCLogoNB.png" rel="icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-image: url('img/RSbg2.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-container {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            text-align: center;
            animation: fadeInUp 1s ease-out;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        textarea {
            height: 100px;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            width: 48%;
        }

        button:hover {
            background-color: #218838;
        }

        .back-button {
            background-color: #dc3545;
        }

        .back-button:hover {
            background-color: #c82333;
        }
    </style>

    <script>
        function toggleSpecificProblem() {
            var subject = document.getElementById("subject").value;
            var specificProblemContainer = document.getElementById("specificProblemContainer");
            var specificProblem = document.getElementById("specificProblem");

            specificProblem.innerHTML = "";

            if (subject === "PC") {
                specificProblemContainer.style.display = "block";
                var problems = ["Overheating", "Power Supply", "HDD Failure", "Network Issue", "SSD Failure", "Blue Screen", "Software Malfunction", "Virus Attack"];
            } else if (subject === "Printer") {
                specificProblemContainer.style.display = "block";
                var problems = ["Paper jams", "Low Ink", "Printer not responding", "Poor print quality", "Cartridge or toner issue", "Printer offline", "Slow printing"];
            } else {
                specificProblemContainer.style.display = "none";
                return;
            }

            var defaultOption = document.createElement("option");
            defaultOption.text = "Select Problem";
            defaultOption.disabled = true;
            defaultOption.selected = true;
            specificProblem.appendChild(defaultOption);

            problems.forEach(function (problem) {
                var option = document.createElement("option");
                option.value = problem;
                option.text = problem;
                specificProblem.appendChild(option);
            });
        }
    </script>

    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- WOW.js for animation triggers -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
</head>

<body>
    <div class="form-container wow animate__animated animate__zoomIn" data-wow-delay="0.5s">
        <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
            <img src="img/SRCLogoNB.png" alt="SRC Logo" style="width: 50px; height: auto;">
            <h2>Service Request Form</h2>
        </div>
        <form action="submit_request.php" method="POST">
            <div class="form-group">
                <label for="fullName">Full Name:</label>
                <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($fullName); ?>"
                    readonly>
            </div>

            <div class="form-group">
                <label for="employeeID">Employee ID:</label>
                <input type="number" id="employeeID" name="employeeID"
                    value="<?php echo htmlspecialchars($employeeID); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="date">Date & Time:</label>
                <input type="datetime-local" id="date" name="date"
                    value="<?php echo htmlspecialchars($currentDateTime); ?>" required readonly>
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
                <input type="text" id="department" name="department"
                    value="<?php echo htmlspecialchars($department); ?>" readonly>
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
</body>

</html>