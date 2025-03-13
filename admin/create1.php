<?php

require('../config/database.php');

if(isset($_POST['create'])){
    $FName = mysqli_real_escape_string($connection, $_POST['FName']);
    $Email = mysqli_real_escape_string($connection, $_POST['Email']);
    $Yearlevel = mysqli_real_escape_string($connection, $_POST['YearL']);
    $StudentIDN = mysqli_real_escape_string($connection, $_POST['StudentIDN']);
    $Password = $_POST['Password'];

    // Hash the password
    $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

    // Corrected the spelling of 'queryCreate'
    $queryCreate = "INSERT INTO studentdb1 (FullName, Email, YearLevel, StudentIDNo, Password) VALUES('$FName', '$Email', '$YearL', '$StudentIDN', '$hashedPassword')";

    $sqlCreate = mysqli_query($connection, $queryCreate);

    if($sqlCreate) {
        echo '<script>alert("Successfully Created")</script>';
        echo '<script>window.location.href = "/CapstoneProject/index.html"</script>';
    } else {
        // Add error handling to help diagnose problems
        echo '<script>alert("Error creating account: ' . mysqli_error($connection) . '")</script>';
    }
}

?>
