<!DOCTYPE html>
<html lang="en">

<head>
    <title>Email Logs</title>

    <style>
        table,
        th,
        td {
            border: 2px solid black;
            border-collapse: collapse;
            width: 1200px;
            text-align: center;
            margin: auto;
        }
    </style>
</head>

<body>
<?php

    require_once 'database.php';

    $sql = "SELECT logID, emailDate, receiver, emailSubject, emailBody, facilityName FROM email_log ORDER BY emailDate ASC";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // output data of each 
        echo "<table>";
        echo "<caption>Email Log</caption>";
        echo "<tr>";
        echo "<th> Log ID </th>";
        echo "<th> Date Sent </th>";
        echo "<th> Receiver </th>";
        echo "<th> Subject </th>";
        echo "<th> Body </th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";

            echo "<td>" . $row["logID"] . "</td>";
            echo "<td>"  . $row["emailDate"] . "</td>";
            echo "<td>"  . $row["receiver"] . "</td>";
            echo "<td>"  . $row["emailSubject"] . "</td>";
            echo "<td>"  . $row["emailBody"] . "</td>";
            echo "<td>"  . $row["facilityName"] . "</td>";

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }

mysqli_close($conn);
?>

    <h3 style="text-align:center">
        <a href="index.php">Home</a>
    </h3>

</body>

</html>
