<!DOCTYPE html>
<html lang="en">

<head>
    <title>Infected Teachers</title>

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

    $sql = "SELECT firstName, lastName, infectionDate, facilities.name
            FROM members, teachers, infections, facilities
            WHERE members.medicareNumber = teachers.medicareNumber AND
            members.medicareNumber = infections.medicareNumber AND
            members.facilityID = facilities.facilityID AND
            infectionNature = 'COVID-19' AND
            infectionDate > ADDDATE(CURDATE(), -14)
            ORDER BY facilities.name ASC, firstName ASC;";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // output data of each 
        echo "<table>";
        echo "<caption>List of Teachers Who Were Infected In the Last 2 Weeks</caption>";
        echo "<tr>";
        echo "<th> First Name </th>";
        echo "<th> Last Name </th>";
        echo "<th> Infection Date </th>";
        echo "<th> Facility Name </th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";

            echo "<td>" . $row["firstName"] . "</td>";
            echo "<td>"  . $row["lastName"] . "</td>";
            echo "<td>"  . $row["infectionDate"] . "</td>";
            echo "<td>"  . $row["name"] . "</td>";

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
