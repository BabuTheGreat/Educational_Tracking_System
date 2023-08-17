<!DOCTYPE html>
<html lang="en">

<head>
    <title>Schedule</title>

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
    <form action ="./create.php" method="post">
        <label for="scheduleID">Schedule ID:</label>
        <input type="number" id="scheduleID" name="scheduleID" required>
        <label for="medicareNumber">Employee Medicare Number:</label>
        <input type="text" id="medicareNumber" name="medicareNumber" required>
        <label for="facilityID">Facility ID:</label>
        <input type="number" id="facilityID" name="facilityID" required>
        <br><br>
        <label for="scheduleDate">Date:</label>
        <input type="date" id="scheduleDate" name="scheduleDate" required>
        <label for="startTime">Start Time:</label>
        <input type="time" id="startTime" name="startTime" required>
        <label for="endTime">End Time:</label>
        <input type="time" id="endTime" name="endTime" required>
        <div style="text-align: center">
        <br><br>
        <input type="submit" name="assignSchedule" value="Assign Schedule"/>
        </div>
    </form>
</div>

<br>
<?php
    require_once '../database.php';

    $sql = "SELECT scheduleID, medicareNumber, facilityID, scheduleDate, startTime, endTime FROM schedule";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // output data of each 
        echo "<table>";
        echo "<caption>List of Employees</caption>";
        echo "<tr>";
        echo "<th> Schedule ID </th>";
        echo "<th> Medicare Number </th>";
        echo "<th> Facility ID </th>";
        echo "<th> Date </th>";
        echo "<th> Start Time </th>";
        echo "<th> End Time </th>";
        echo "<th></th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            $id = $row["scheduleID"];
            echo "<td>" . $row["scheduleID"] . "</td>";
            echo "<td>" . $row["medicareNumber"] . "</td>";
            echo "<td>" . $row["facilityID"] . "</td>";
            echo "<td>"  . $row["scheduleDate"] . "</td>";
            echo "<td>"  . $row["startTime"] . "</td>";
            echo "<td>"  . $row["endTime"] . "</td>";
            echo "<td> <form  action ='./delete.php' method='post'> 
                        <input type='hidden' id='scheduleID' name='scheduleID' value=$id> 
                        <input type='submit' name='deleteSchedule' value='Delete Schedule'/>
                        </form>
                        <form action ='./edit.php' method='get'> 
                        <input type='hidden' id='scheduleID' name='scheduleID' value=$id> 
                        <input type='submit' name='editSchedule' value='Edit Schedule'/>
                        </form>
                        </td>";

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }

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
    WHERE (members.medicareNumber = employees.medicareNumber) AND (members.facilityID = facilities.facilityID) AND
    (schedule.medicareNumber = employees.medicareNumber) AND (employees.medicareNumber = $medicare) AND (scheduleDate >= $start AND scheduleDate <= $end);";

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
        <a href="../index.php">Home</a>
    </h3>

</body>

</html>
