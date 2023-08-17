<!DOCTYPE html>
<html lang="en">

<head>
    <title>Registration</title>

    <style>
        table,
        th,
        td {
            border: 2px solid black;
            border-collapse: collapse;
            width: 800px;
            text-align: center;
            margin: auto;
        }
    </style>
</head>

<body>

<div style="display:flex; justify-content: center;">
<form action="./create.php" method="post">
    <label for="medicareNumber">Medicare Number:</label>
    <input type="text" id="medicareNumber" name="medicareNumber" required>
    <label for="registrationStartDate">Registration Start Date:</label>
    <input type="date" id="registrationStartDate" name="registrationStartDate" required>
    <br><br>
    <label for="registrationEndDate">Registration End Date:</label>
    <input type="date" id="registrationEndDate" name="registrationEndDate">
    <br><br>
    <label for="facilityID">Facility ID:</label>
    <input type="number" id="facilityID" name="facilityID">
    <div style="text-align: center">
    <input type="submit" name="addStudent" value="Register Student"/>
    </div>
</form>
</div>

    <?php
    require_once '../database.php';

    $sql = "SELECT medicareNumber, registrationStartDate, registrationEndDate, facilityID FROM student_histories";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // output data of each 
        echo "<table>";
        echo "<caption>List of Registered Students</caption>";
        echo "<tr>";
        echo "<th> Medicare Number </th>";
        echo "<th> Registration Start Date </th>";
        echo "<th> Registration End Date </th>";
        echo "<th> FacilityID </th>";
        echo "<th></th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            $medicare = $row["medicareNumber"];
            $registrationStartDate = $row["registrationStartDate"];
            echo "<td>" . $row["medicareNumber"] . "</td>";
            echo "<td>"  . $row["registrationStartDate"] . "</td>";
            echo "<td>"  . $row["registrationEndDate"] . "</td>";
            echo "<td>"  . $row["facilityID"] . "</td>";
            echo "<td> <form  action ='./delete.php' method='post'> 
            <input type='hidden' id='medicareNumber' name='medicareNumber' value=$medicare>
            <input type='hidden' id='registrationStartDate' name='registrationStartDate' value=$registrationStartDate> 
            <input type='submit' name='deleteStudent' value='Cancel Registration'/>
            </form>
            <form action ='./edit.php' method='get'> 
                <input type='hidden' id='medicareNumber' name='medicareNumber' value=$medicare>
                <input type='hidden' id='registrationStartDate' name='registrationStartDate' value=$registrationStartDate>
                <input type='submit' name='editStudent' value='Modify Registration'/>
            </form>
            </td>";

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }

    mysqli_close($conn);  
    ?>

</h4>

    <h3 style="text-align:center">
        <a href="../index.php">Home</a>
    </h3>


</body>

</html>
