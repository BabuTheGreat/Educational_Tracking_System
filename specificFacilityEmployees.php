<!DOCTYPE html>
<html lang="en">

<head>
    <title>Get Employee Details</title>

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
        <div style="text-align: center">
        <input type="submit" name="getDetails" value="Get Employee Details"/>
        </div>
    </form>
</div>

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
    "SELECT firstName, lastName, employmentStartDate as startDate, dateOfBirth, employees.medicareNumber, members.phoneNumber, members.address, 
    members.city, members.province, members.postalCode, citizenship, members.email
    FROM members, employees, facilities, employee_histories
    WHERE facilities.facilityID = $facilityID AND (members.medicareNumber = employees.medicareNumber) AND (members.facilityID = facilities.facilityID)
    GROUP BY employees.medicareNumber
    ORDER BY employeeType ASC, firstName ASC, lastName ASC;";

    $result = mysqli_query($conn, $sql1);
    if (mysqli_num_rows($result) > 0) {
        // output data of each 
        echo "<table>";
        echo "<caption>List of Employees in Facility #" . $facilityID . "</caption>";
        echo "<tr>";
        echo "<th> First Name </th>";
        echo "<th> Last Name </th>";
        echo "<th> Employment Start Date </th>";
        echo "<th> Date of Birth </th>";
        echo "<th> Medicare Number </th>";
        echo "<th> Phone Number </th>";
        echo "<th> Address </th>";
        echo "<th> City </th>";
        echo "<th> Province </th>";
        echo "<th> Postal Code </th>";
        echo "<th> Citizenship </th>";
        echo "<th> Email </th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";

            echo "<td>" . $row["firstName"] . "</td>";
            echo "<td>"  . $row["lastName"] . "</td>";
            echo "<td>"  . $row["startDate"] . "</td>";
            echo "<td>"  . $row["dateOfBirth"] . "</td>";
            echo "<td>"  . $row["medicareNumber"] . "</td>";
            echo "<td>"  . $row["phoneNumber"] . "</td>";
            echo "<td>"  . $row["address"] . "</td>";
            echo "<td>"  . $row["city"] . "</td>";
            echo "<td>"  . $row["province"] . "</td>";
            echo "<td>"  . $row["postalCode"] . "</td>";
            echo "<td>"  . $row["citizenship"] . "</td>";
            echo "<td>"  . $row["email"] . "</td>";

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
