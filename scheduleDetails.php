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
        <label for="medicareNumber">Employee Medicare Number:</label>
        <input type="text" id="medicareNumber" name="medicareNumber" required>
        <label for="startDate">Start Day:</label>
        <input type="date" id="startDate" name="startDate" required>
        <label for="endDate">End Day:</label>
        <input type="date" id="endDate" name="endDate" required>
        <div style="text-align: center">
        <input type="submit" name="getScheduleDetails" value="Get Employee Schedule Details"/>
        </div>
    </form>
</div>

  <br>
  
<?php
    require_once 'database.php';

if(isset($_GET['getScheduleDetails'])) {
    $medicare =  $_GET['medicareNumber'];
    $medicare = "\"$medicare\"";
    $start = $_GET['startDate'];
    $start = "\"$start \"";
    $end = $_GET['endDate'];
    $end = "\"$end\"";
    $sql1 = 
    "SELECT name, scheduleDate, startTime, endTime
    FROM schedule, facilities, employees, members
    WHERE (members.medicareNumber = employees.medicareNumber) AND (schedule.facilityID = facilities.facilityID) AND
    (schedule.medicareNumber = employees.medicareNumber) AND (employees.medicareNumber = $medicare) AND (scheduleDate >= $start AND scheduleDate <= $end)
    ORDER BY name ASC, scheduleDate ASC, startTime ASC;";

    $result = mysqli_query($conn, $sql1);
    if (mysqli_num_rows($result) > 0) {
        // output data of each 
        echo "<table>";
        echo "<caption>Schedule for Employee" . $medicare . "</caption>";
        echo "<tr>";
        echo "<th> Facility Name </th>";
        echo "<th> Date </th>";
        echo "<th> Start Time </th>";
        echo "<th> End Time </th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";

            echo "<td>" . $row["name"] . "</td>";
            echo "<td>"  . $row["scheduleDate"] . "</td>";
            echo "<td>"  . $row["startTime"] . "</td>";
            echo "<td>"  . $row["endTime"] . "</td>";

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
