<!DOCTYPE html>
<html lang="en">

<head>
    <title>High School Infections Over the Past 2 Weeks</title>

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

    $sql = "SELECT f.province, f.name, f.capacity, 
    COUNT((SELECT DISTINCT(medicareNumber) FROM infections i WHERE i.infectionDate > ADDDATE(CURDATE(), -14) AND t.medicareNumber = i.medicareNumber)) AS TeachersCount, 
    COUNT((SELECT DISTINCT(medicareNumber) FROM infections i WHERE i.infectionDate > ADDDATE(CURDATE(), -14) AND s.medicareNumber = i.medicareNumber)) AS StudentCount
    FROM facilities f
    JOIN educational_facility_to_types eftt ON eftt.educationalFacilityID = f.facilityID
    LEFT JOIN members m ON eftt.educationalFacilityID = m.facilityID
    LEFT JOIN teachers t ON m.medicareNumber = t.medicareNumber
    LEFT JOIN students s ON m.medicareNumber = s.medicareNumber
    WHERE eftt.typeID = 3 
    GROUP BY f.facilityID
    ORDER BY f.province ASC, TeachersCount ASC;";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each 
        echo "<table>";
        echo "<caption>List of High School Infections Over the Past 2 Weeks</caption>";
        echo "<tr>";
        echo "<th> Location </th>";
        echo "<th> Name </th>";
        echo "<th> Capacity </th>";
        echo "<th> Number of Infected Teachers </th>";
        echo "<th> Number of Infceted Students </th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";

            echo "<td>" . $row["province"] . "</td>";
            echo "<td>"  . $row["name"] . "</td>";
            echo "<td>"  . $row["capacity"] . "</td>";
            echo "<td>"  . $row["TeachersCount"] . "</td>";
            echo "<td>"  . $row["StudentCount"] . "</td>";

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
