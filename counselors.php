<!DOCTYPE html>
<html lang="en">

<head>
    <title>Counselors</title>

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

    $sql = "SELECT firstName, lastName, employmentStartDate, 
                CASE
                    WHEN members.medicareNumber IN (SELECT medicareNumber FROM primary_teachers) THEN 'Elementary School Teacher'
                    WHEN members.medicareNumber IN (SELECT medicareNumber FROM secondary_teachers) THEN 'Secondary School Teacher'
                END AS 'role',
                dateOfBirth, email,
                CASE
                    WHEN members.medicareNumber IN (SELECT medicareNumber FROM schedule) THEN
                        (SELECT SUM(TIME_TO_SEC(TIMEDIFF(endTime, startTime))/3600) FROM schedule WHERE members.medicareNumber = schedule.medicareNumber)
                    ELSE '0'
                END AS 'sumHours'
        FROM members, secondary_teachers st, employee_histories
        WHERE members.medicareNumber = st.medicareNumber AND members.medicareNumber = employee_histories.medicareNumber AND st.counselor = 1 AND
            (employee_histories.employmentEndDate IS NULL OR employee_histories.employmentEndDate > CURDATE()) AND
            (SELECT COUNT(medicareNumber) FROM infections WHERE infections.medicareNumber = st.medicareNumber AND infectionNature = 'COVID-19') >= 3
        GROUP BY st.medicareNumber
        ORDER BY 'role' ASC, firstName ASC, lastName ASC;";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each 
        echo "<table>";
        echo "<caption>List of Counselors Who Were Infected By COVID-19 Minimum 3 Times</caption>";
        echo "<tr>";
        echo "<th> First Name </th>";
        echo "<th> Last Name </th>";
        echo "<th> First Day of Work </th>";
        echo "<th> Role </th>";
        echo "<th> Date of Birth </th>";
        echo "<th> Email</th>";
        echo "<th> Number of Hours Scheduled </th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";

            echo "<td>" . $row["firstName"] . "</td>";
            echo "<td>"  . $row["lastName"] . "</td>";
            echo "<td>"  . $row["employmentStartDate"] . "</td>";
            echo "<td>"  . $row["role"] . "</td>";
            echo "<td>"  . $row["dateOfBirth"] . "</td>";
            echo "<td>"  . $row["email"] . "</td>";
            echo "<td>"  . $row["sumHours"] . "</td>";

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
//mysqli_close($conn);
?>

    <h3 style="text-align:center">
        <a href="index.php">Home</a>
    </h3>

</body>

</html>
