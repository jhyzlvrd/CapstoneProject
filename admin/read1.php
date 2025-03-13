<?php

require('../config/database.php');

$querryAccounts = "SELECT * FROM studentdb1";
$sqlAccounts = mysqli_query($connection,$querryAccounts);

?>