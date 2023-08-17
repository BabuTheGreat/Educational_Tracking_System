<?php
    $servername = "ldc353.encs.concordia.ca";
    $username = "ldc353_1";
    $password = "datab4s3";
    $dbname = "ldc353_1";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>