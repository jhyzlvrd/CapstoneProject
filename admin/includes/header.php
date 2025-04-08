<?php
session_start(); // Start the session

// Check if the admin is logged in, otherwise redirect to login page
if (!isset($_SESSION['Email']) || !isset($_SESSION['FullName'])) {
    header("Location: ../user/employeelogin.php");
    exit();
}

// Get Admin details from the session
$adminEmail = $_SESSION['Email'];       
$adminFullName = $_SESSION['FullName']; 
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin Dashboard</title>
  
  <!-- Plugins: CSS -->
  <link rel="stylesheet" href="vendors/feather/feather.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">

  <!-- Plugin CSS for this page -->
  <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" type="text/css" href="js/select.dataTables.min.css">

  <!-- Custom styles -->
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="css/style.css">
  
  <!-- Favicon -->
  <link rel="shortcut icon" href="img/SRCLogoNB.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- Top navbar -->
    <?php include 'navbar.php'; ?>
    
    <div class="container-fluid page-body-wrapper">
      <!-- Fixed Sidebar -->
      <?php include 'sidebar.php'; ?>
      
      <!-- Scrollable Main Content -->
      <div class="main-panel">
        <div class="content-wrapper">
          <!-- Your content will go here -->
    