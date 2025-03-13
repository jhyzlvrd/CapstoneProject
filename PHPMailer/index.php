<?php
require 'mail.php'; // Include the mail function

$response = null; // Initialize response variable

// Predefined messages
$templates = [
    "acceptance" => [
        "subject" => "Report Accepted – [Report ID]",
        "message" => "Dear [Reporter’s Name],\n\nWe have received your report regarding [issue description]. Your report has been successfully logged in our system, and we will take the necessary steps to address it. Thank you for bringing this to our attention.\n\nBest regards,\n[Admin Name]\nSanta Rita College - MIS Office"
    ],
    "in_progress" => [
        "subject" => "Service Request in Progress – [Report ID]",
        "message" => "Dear [Reporter’s Name],\n\nWe want to inform you that your reported issue regarding [issue description] is now being processed. Our team is currently working on resolving it, and we will update you once the issue has been addressed.\n\nBest regards,\n[Admin Name]\nSanta Rita College - MIS Office"
    ],
    "completed" => [
        "subject" => "Service Request Completed – [Report ID]",
        "message" => "Dear [Reporter’s Name],\n\nWe are pleased to inform you that the issue you reported regarding [issue description] has been successfully resolved. If you have any further concerns, please don’t hesitate to report them through our system.\n\nThank you for your cooperation!\n\nBest regards,\n[Admin Name]\nSanta Rita College - MIS Office"
    ],
    "reservation_accepted" => [
        "subject" => "Facility Reservation Approved – [Reservation ID]",
        "message" => "Dear [Employee’s Name],\n\nWe are pleased to inform you that your reservation request for [Facility Name] on [Reservation Date] has been approved. You may now proceed with using the facility at the scheduled time. Please ensure that all guidelines and policies are followed during usage.\n\nIf you have any concerns or need further assistance, feel free to contact us.\n\nBest regards,\n[Admin Name]\nFacility Management Team"
    ],
    "facility_usage_reminder" => [
        "subject" => "Important Guidelines for Facility Usage – [Reservation ID]",
        "message" => "Dear [Employee’s Name],\n\nAs you use the [Facility Name] for your scheduled reservation, we kindly remind you to:\n\n✔ Maintain cleanliness – Keep the facility neat and dispose of any trash properly.\n✔ Preserve the equipment – Avoid mishandling or damaging any items within the facility.\n✔ Follow the rules – Adhere to the facility guidelines to ensure a smooth and safe experience for everyone.\n\nYour cooperation in keeping the facility in excellent condition is greatly appreciated. Thank you for your responsibility and professionalism.\n\nBest regards,\n[Admin Name]\nFacility Management Team"
    ],
    "thank_you" => [
        "subject" => "Thank You for Using the Facility Responsibly – [Reservation ID]",
        "message" => "Dear [Employee’s Name],\n\nWe sincerely appreciate your responsible use of [Facility Name] during your reservation on [Reservation Date]. Your cooperation in maintaining cleanliness and preserving the facility ensures that it remains in great condition for everyone.\n\nShould you need to reserve a facility in the future, we are always here to assist you. Thank you once again for your professionalism and adherence to our guidelines!\n\nBest regards,\n[Admin Name]\nFacility Management Team"
    ]
];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (empty($email) || empty($subject) || empty($message)) {
        $response = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = "Invalid email format!";
    } else {
        $response = sendMail($email, $subject, $message);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Notification Form</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="../admin/img/SRCLogoNB.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
            font-family: Arial, sans-serif;
        }

        .email-container {
            margin: 50px auto;
            width: 50%;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control {
            border: 2px solid #ccc;
            border-radius: 4px;
            padding: 12px;
        }

        .btn-submit {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #218838;
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h2 {
            font-size: 28px;
            color: #333;
        }

        @media screen and (max-width: 768px) {
            .email-container {
                width: 90%;
            }
        }
    </style>
    <script>
        function fillTemplate() {
            let template = document.getElementById("template").value;

            let subjects = {
                "acceptance": "Report Accepted – [Report ID]",
                "in_progress": "Service Request in Progress – [Report ID]",
                "completed": "Service Request Completed – [Report ID]",
                "reservation_accepted": "Facility Reservation Approved – [Reservation ID]",
                "facility_usage_reminder": "Important Guidelines for Facility Usage – [Reservation ID]",
                "thank_you": "Thank You for Using the Facility Responsibly – [Reservation ID]"
            };

            let messages = {
                "acceptance": "Dear [Reporter’s Name],\n\nWe have received your report regarding [issue description]. Your report has been successfully logged in our system, and we will take the necessary steps to address it. Thank you for bringing this to our attention.\n\nBest regards,\n[Admin Name]\nSanta Rita College - MIS Office",

                "in_progress": "Dear [Reporter’s Name],\n\nWe want to inform you that your reported issue regarding [issue description] is now being processed. Our team is currently working on resolving it, and we will update you once the issue has been addressed.\n\nBest regards,\n[Admin Name]\nSanta Rita College - MIS Office",

                "completed": "Dear [Reporter’s Name],\n\nWe are pleased to inform you that the issue you reported regarding [issue description] has been successfully resolved. If you have any further concerns, please don’t hesitate to report them through our system.\n\nThank you for your cooperation!\n\nBest regards,\n[Admin Name]\nSanta Rita College - MIS Office",

                "reservation_accepted": "Dear [Employee’s Name],\n\nWe are pleased to inform you that your reservation request for [Facility Name] on [Reservation Date] has been approved. You may now proceed with using the facility at the scheduled time. Please ensure that all guidelines and policies are followed during usage.\n\nIf you have any concerns or need further assistance, feel free to contact us.\n\nBest regards,\n[Admin Name]\nFacility Management Team",

                "facility_usage_reminder": "Dear [Employee’s Name],\n\nAs you use the [Facility Name] for your scheduled reservation, we kindly remind you to:\n\n✔ Maintain cleanliness – Keep the facility neat and dispose of any trash properly.\n✔ Preserve the equipment – Avoid mishandling or damaging any items within the facility.\n✔ Follow the rules – Adhere to the facility guidelines to ensure a smooth and safe experience for everyone.\n\nYour cooperation in keeping the facility in excellent condition is greatly appreciated. Thank you for your responsibility and professionalism.\n\nBest regards,\n[Admin Name]\nFacility Management Team",

                "thank_you": "Dear [Employee’s Name],\n\nWe sincerely appreciate your responsible use of [Facility Name] during your reservation on [Reservation Date]. Your cooperation in maintaining cleanliness and preserving the facility ensures that it remains in great condition for everyone.\n\nShould you need to reserve a facility in the future, we are always here to assist you. Thank you once again for your professionalism and adherence to our guidelines!\n\nBest regards,\n[Admin Name]\nFacility Management Team"
            };

            if (template !== "") {
                document.getElementById("subject").value = subjects[template];
                document.getElementById("message").value = messages[template];
            }
        }
    </script>
</head>

<body>

    <div class="email-container">
        <div class="form-header">
            <h2>Send Email Notification</h2>
        </div>

        <form action="" method="post">
            <div class="form-group">
                <label for="email">Recipient Email:</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter recipient email"
                    required>
            </div>

            <div class="form-group">
                <label for="template">Select a Predefined Message:</label>
                <select id="template" class="form-control" onchange="fillTemplate()">
                    <option value="">-- Select a Message Template --</option>
                    <option value="acceptance">Report Accepted</option>
                    <option value="in_progress">Service Request In Progress</option>
                    <option value="completed">Service Request Completed</option>
                    <option value="reservation_accepted">Facility Reservation Approved</option>
                    <option value="facility_usage_reminder">Facility Usage Reminder</option>
                    <option value="thank_you">Thank You for Using the Facility</option>
                </select>

            </div>

            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" class="form-control" placeholder="Enter email subject"
                    required>
            </div>

            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" class="form-control" rows="5" placeholder="Enter your message"
                    required></textarea>
            </div>

            <div class="form-group text-center">
                <button type="submit" name="submit" class="btn-submit">Send Email</button>
            </div>

            <?php if ($response !== null): ?>
                <p class="<?= $response == 'success' ? 'text-success' : 'text-danger'; ?>">
                    <?= $response == 'success' ? 'Email sent successfully.' : $response; ?>
                </p>
            <?php endif; ?>
        </form>

        <div class="text-center mt-3">
            <a href="../admin/complaints.php" class="btn btn-secondary">Back</a>
        </div>
    </div>

</body>

</html>