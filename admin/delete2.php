<?php
require('../config/database.php');

if (isset($_POST['delete'])){
    $deleteId = $_POST['deleteID'];

    $querrydelete = "DELETE FROM employeedb WHERE ID = $deleteId";
    $sqldelete = mysqli_query($connection,$querrydelete);

    echo '<script>alert("Succesfully Deleted")</script>';
    echo '<script>window.location.href = "/CapstoneProject/admin/employeeaccounts.php"</script>';
}

?>