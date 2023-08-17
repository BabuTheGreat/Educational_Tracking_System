<!DOCTYPE html>
<html lang="en">

<head>
    <title>Display Facilities</title>

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

    $sql = "SELECT facilities.name, facilities.address, facilities.city, facilities.province, facilities.postalCode, facilities.phoneNumber, 
    facilities.webAddress, facilityType, facilities.capacity, y.firstName, y.lastName, COUNT(m.medicareNumber) as numEmployees
    FROM facilities, members x, members y, employees m, employees s
    WHERE (x.medicareNumber = m.medicareNumber AND x.facilityID = facilities.facilityID)
    AND ((y.medicareNumber = s.medicareNumber AND y.facilityID = facilities.facilityID) AND ((s.employeeType = 'president' AND facilityType='management_facility') OR 
        (s.employeeType='principal' AND facilityType='educational_facility')))
    GROUP BY facilities.facilityID
    ORDER BY facilities.province ASC, facilities.city ASC, facilityType ASC, COUNT(m.medicareNumber) ASC; ";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // output data of each 
        echo "<table>";
        echo "<caption>List of Facilities</caption>";
        echo "<tr>";
        echo "<th> Name </th>";
        echo "<th> Address </th>";
        echo "<th> City </th>";
        echo "<th> Province </th>";
        echo "<th> Postal Code </th>";
        echo "<th> Phone Number </th>";
        echo "<th> Web Address </th>";
        echo "<th> Facility Type </th>";
        echo "<th> Capacity </th>";
        echo "<th> President/Principal First Name </th>";
        echo "<th> President/Principal Last Name </th>";
        echo "<th> Number of Employees </th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";

            echo "<td>" . $row["name"] . "</td>";
            echo "<td>"  . $row["address"] . "</td>";
            echo "<td>"  . $row["city"] . "</td>";
            echo "<td>"  . $row["province"] . "</td>";
            echo "<td>"  . $row["postalCode"] . "</td>";
            echo "<td>"  . $row["phoneNumber"] . "</td>";
            echo "<td>"  . $row["webAddress"] . "</td>";
            echo "<td>"  . $row["facilityType"] . "</td>";
            echo "<td>"  . $row["capacity"] . "</td>";
            echo "<td>"  . $row["firstName"] . "</td>";
            echo "<td>"  . $row["lastName"] . "</td>";
            echo "<td>"  . $row["numEmployees"] . "</td>";

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
