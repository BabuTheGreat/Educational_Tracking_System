<!DOCTYPE html>
<html lang="en">

<head>
    <title>Ministries</title>

    <style>
        table,
        th,
        td {
            border: 2px solid black;
            border-collapse: collapse;
            width: 600px;
            text-align: center;
            margin: auto;
        }
    </style>
</head>

<body>
    <?php

    require_once 'database.php';

    $sql = "SELECT ministries.ministryID, firstName, lastName, members.city, COUNT(DISTINCT m.facilityID) as NumMan, COUNT(DISTINCT e.facilityID) as NumEduc
            FROM ministries
            JOIN members ON (headOffice = members.facilityID)
            JOIN employees ON (members.medicareNumber = employees.medicareNumber AND employeeType='president')
            LEFT JOIN facilities m ON (m.facilityType = 'management_facility' AND m.ministryID = ministries.ministryID) 
            LEFT JOIN facilities e ON (e.facilityType = 'educational_facility' AND e.ministryID = ministries.ministryID )
            GROUP BY ministries.ministryID
            ORDER BY members.city ASC, COUNT(e.facilityID) DESC;";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // output data of each 
        echo "<table>";
        echo "<caption>List of Ministries</caption>";
        echo "<tr>";
        echo "<th> Minister's First Name </th>";
        echo "<th> Minister's Last Name </th>";
        echo "<th> Minister's City </th>";
        echo "<th> Number of Management Facilities </th>";
        echo "<th> Number of Educational Facilities </th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";

            echo "<td>" . $row["firstName"] . "</td>";
            echo "<td>"  . $row["lastName"] . "</td>";
            echo "<td>"  . $row["city"] . "</td>";
            echo "<td>"  . $row["NumMan"] . "</td>";
            echo "<td>"  . $row["NumEduc"] . "</td>";

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
