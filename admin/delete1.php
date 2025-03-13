<?php
require('../config/database.php');

if (isset($_POST['delete'])){
    $deleteId = $_POST['deleteID'];

    $querrydelete = "DELETE FROM studentdb1 WHERE ID = $deleteId";
    $sqldelete = mysqli_query($connection,$querrydelete);

    echo '<script>alert("Succesfully Deleted")</script>';
    echo '<script>window.location.href = "/CapstoneProject/admin/studentaccounts.php"</script>';
}

?>