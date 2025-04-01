<?php
session_start();
session_destroy();
header("Location: employeelogin.php"); // Redirect to home after logout
exit();
?>
