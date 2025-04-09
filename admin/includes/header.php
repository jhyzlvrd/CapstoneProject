<?php
session_start(); // Start the session

// Check if session is not set, if not, redirect to login
if (!isset($_SESSION['EmployeeID']) || !isset($_SESSION['Role'])) {
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
  
  <!-- <style>
    .sidebar-offcanvas {
      position: fixed;
      top: 70px;
      bottom: 0;
      left: 0;
      width: 240px; 
      overflow-y: hidden; 
      z-index: 100;
      background: #fff; 
    }
    
    .main-panel {
      margin-left: 240px; 
      width: calc(100% - 240px);
      height: 100vh;
      overflow-y: auto;
    }
    
    @media (max-width: 991px) {
      .sidebar-offcanvas {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
      }
      .sidebar-offcanvas.show {
        transform: translateX(0);
      }
      .main-panel {
        margin-left: 0;
        width: 100%;
      }
    }
  </style> -->
  
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