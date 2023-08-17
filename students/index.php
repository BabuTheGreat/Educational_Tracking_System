<!DOCTYPE html>
<html lang="en">

<head>
    <title>Students</title>

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
        #labelMedicareNumber{
            color: green;
        }
        #labelLevel{
            color: green;
        }
    </style>
</head>

<body>

<div style="display:flex; justify-content: center;">
<form action ="./create.php" method="post">
    <label id="labelMedicareNumber" for="medicareNumber">Medicare Number:</label>
    <input type="text" id="medicareNumber" name="medicareNumber" required>
    <label for="medicareExpiryDate">Date:</label>
    <input type="date" id="medicareExpiryDate" name="medicareExpiryDate">
    <label for="facilityID">Facility ID:</label>
    <input type="number" id="facilityID" name="facilityID">
    <br><br>
    <label for="firstName">First Name:</label>
    <input type="text" id="firstName" name="firstName">
    <label for="lastName">Last Name:</label>
    <input type="text" id="lastName" name="lastName">
    <label for="email">Email:</label>
    <input type="text" id="email" name="email">
    <br><br>
    <label for="phoneNumber">Phone Number:</label>
    <input type="text" id="phoneNumber" name="phoneNumber">
    <label for="dateOfBirth">Date of Birth:</label>
    <input type="date" id="dateOfBirth" name="dateOfBirth">
    <label for="address">Address:</label>
    <input type="text" id="address" name="address">
    <br><br>
    <label for="city">City:</label>
    <input type="text" id="city" name="city">
    <label for="postalCode">Postal Code:</label>
    <input type="text" id="postalCode" name="postalCode">
    <label for="province">Province:</label>
    <input type="text" id="province" name="province">
    <label for="citizenship">Citizenship:</label>
    <input type="text" id="citizenship" name="citizenship">
    <br><br>
    <label id="labelLevel" for="level">Level:</label>
    <select id="level" name="level">
        <option value="Elementary 1">Elementary 1</option>
        <option value="Elementary 2">Elementary 2</option>
        <option value="Elementary 3">Elementary 3</option>
        <option value="Elementary 4">Elementary 4</option>
        <option value="Elementary 5">Elementary 5</option>
        <option value="Elementary 6">Elementary 6</option>
        <option value="Middle 1">Middle 1</option>
        <option value="Middle 2">Middle 2</option>
        <option value="Middle 3">Middle 3</option>
        <option value="High 1">High 1</option>
        <option value="High 2">High 2</option>
        <option value="High 3">High 3</option>
        <option value="High 4">High 4</option>
        <option value="High 5">High 5</option>
    </select>
    <br><br>
    <div style="text-align: center">
    <input type="submit" name="addStudent" value="Add Student"/>
    </div>
</form>
</div>
    
    <?php
    require_once '../database.php';

    $sql = "SELECT * FROM members JOIN students ON members.medicareNumber = students.medicareNumber";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // output data of each 
        echo "<table>";
        echo "<caption>List of Students</caption>";
        echo "<tr>";
        echo "<th> Medicare Number </th>";
        echo "<th> Medicare Expiry Date </th>";
        echo "<th> Facility ID </th>";
        echo "<th> First Name </th>";
        echo "<th> Last Name </th>";
        echo "<th> Email </th>";
        echo "<th> Phone Number </th>";
        echo "<th> Date of Birth </th>";
        echo "<th> Address </th>";
        echo "<th> City </th>";
        echo "<th> Postal Code </th>";
        echo "<th> Province </th>";
        echo "<th> Citizenship </th>";
        echo "<th> Level </th>";
        echo "<th></th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";

            $medicare = $row["medicareNumber"];
            echo "<td>" . $row["medicareNumber"] . "</td>";
            echo "<td>" . $row["medicareExpiryDate"] . "</td>";
            echo "<td>" . $row["facilityID"] . "</td>";
            echo "<td>" . $row["firstName"] . "</td>";
            echo "<td>" . $row["lastName"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["phoneNumber"] . "</td>";
            echo "<td>" . $row["dateOfBirth"] . "</td>";
            echo "<td>" . $row["address"] . "</td>";
            echo "<td>" . $row["city"] . "</td>";
            echo "<td>" . $row["postalCode"] . "</td>";
            echo "<td>" . $row["province"] . "</td>";
            echo "<td>" . $row["citizenship"] . "</td>";
            echo "<td>"  . $row["level"] . "</td>";
            echo "<td> <form  action ='./delete.php' method='post'> 
            <input type='hidden' id='medicareNumber' name='medicareNumber' value=$medicare> 
            <input type='submit' name='deleteStudent' value='Delete Student'/>
            </form>
            <form  action ='./edit.php' method='get'> 
            <input type='hidden' id='medicareNumber' name='medicareNumber' value=$medicare> 
            <input type='submit' name='editStudent' value='Edit Student'/>
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

    <h3 style="text-align:center">
        <a href="../index.php">Home</a>
    </h3>

</body>

</html>
