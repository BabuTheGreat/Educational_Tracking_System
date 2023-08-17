<!DOCTYPE html>
<html lang="en">

<head>
    <title>Teachers Scheduled to Work in the Last 2 Weeks</title>

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

<div style="display:flex; justify-content: center;">
    <form method="get">
        <label for="facilityID">Facility ID:</label>
        <input type="number" id="facilityID" name="facilityID" required>
        <input type="submit" name="getDetails" value="Get Scheduled Teachers List"/>
    </form>
</div>
<br><br>

<?php
   require_once 'database.php';

    $sql = "SELECT facilityID, name FROM facilities";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // output data of each 
        echo "<table>";
        echo "<caption>List of Facilities</caption>";
        echo "<tr>";
        echo "<th> ID </th>";
        echo "<th> Name </th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";

            echo "<td>" . $row["facilityID"] . "</td>";
            echo "<td>"  . $row["name"] . "</td>";

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }

    echo "<br><br><br>";

if(isset($_GET['getDetails'])) {
    $facilityID = $_GET['facilityID'];
    $sql1 = 
    "SELECT firstName, lastName, 
        CASE
        WHEN members.medicareNumber IN (SELECT medicareNumber FROM primary_teachers) THEN 'Elementary Teacher'
        WHEN members.medicareNumber IN (SELECT medicareNumber FROM secondary_teachers) THEN 'Secondary Teacher'
        ELSE 'Middle School Teacher'
        END AS 'role'
    FROM members, teachers, schedule, facilities
    WHERE 
        members.medicareNumber = teachers.medicareNumber AND
        members.medicareNumber = schedule.medicareNumber AND
        schedule.scheduleDate > ADDDATE(CURDATE(), -14) AND
        members.facilityID = facilities.facilityID AND
        members.facilityID = $facilityID
    ORDER BY role ASC, firstName ASC;";

    $result = mysqli_query($conn, $sql1);
    if (mysqli_num_rows($result) > 0) {
        // output data of each 
        echo "<table>";
        echo "<caption>List of Teachers Scheduled to Work in the Last 2 Weeks</caption>";
        echo "<tr>";
        echo "<th> First Name </th>";
        echo "<th> Last Name </th>";
        echo "<th> Role </th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";

            echo "<td>" . $row["firstName"] . "</td>";
            echo "<td>"  . $row["lastName"] . "</td>";
            echo "<td>"  . $row["role"] . "</td>";

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }    

}
mysqli_close($conn);
?>

    <h3 style="text-align:center">
        <a href="index.php">Home</a>
    </h3>

</body>

</html>
