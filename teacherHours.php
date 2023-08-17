<!DOCTYPE html>
<html lang="en">

<head>
    <title>Employee Schedule Details</title>

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
        <label for="startDate">Start Day:</label>
        <input type="date" id="startDate" name="startDate" required>
        <label for="endDate">End Day:</label>
        <input type="date" id="endDate" name="endDate" required>
        <div style="text-align: center"> <br>
        <input type="submit" name="getScheduleDetails" value="Get Employee Schedule Details"/>
        </div>
    </form>
</div>

<br>
<?php
   require_once 'database.php';

if(isset($_GET['getScheduleDetails'])) {
    $facilityID =  $_GET['facilityID'];
    $start = $_GET['startDate'];
    $start = "\"$start \"";
    $end = $_GET['endDate'];
    $end = "\"$end\"";
    $sql1 = 
    "SELECT firstName, lastName, SUM(TIME_TO_SEC(TIMEDIFF(endTime, startTime))/3600) as numHours
    FROM members, teachers, schedule
    WHERE schedule.facilityID = $facilityID AND (members.medicareNumber = teachers.medicareNumber) AND (teachers.medicareNumber = schedule.medicareNumber) 
    AND (members.facilityID = schedule.facilityID) AND (scheduleDate >= $start AND scheduleDate <= $end)
    GROUP BY teachers.medicareNumber
    ORDER BY firstName ASC, lastName ASC;";

    $result = mysqli_query($conn, $sql1);
    if (mysqli_num_rows($result) > 0) {
        // output data of each 
        echo "<table>";
        echo "<caption>Schedule for Employees at Facility #" . $facilityID . "</caption>";
        echo "<tr>";
        echo "<th> First Name </th>";
        echo "<th> Last Name </th>";
        echo "<th> Number of Hours </th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";

            echo "<td>" . $row["firstName"] . "</td>";
            echo "<td>"  . $row["lastName"] . "</td>";
            echo "<td>"  . $row["numHours"] . "</td>";

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
