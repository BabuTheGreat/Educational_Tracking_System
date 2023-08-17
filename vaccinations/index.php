<!DOCTYPE html>
<html lang="en">

<head>
    <title>Vaccinations</title>

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
    <label for="vaccinationID">Vaccination ID:</label>
    <input type="number" id="vaccinationID" name="vaccinationID" required>
    <label for="medicareNumber">Medicare Number:</label>
    <input type="text" id="medicareNumber" name="medicareNumber">
    <br><br>
    <label for="date">Date:</label>
    <input type="date" id="date" name="date">
    <label for="type">Type:</label>
    <select id="type" name="type">
        <option value="AstraZeneca">AstraZeneca</option>
        <option value="Johnson & Johnson">Johnson & Johnson</option>
        <option value="Moderna">Moderna</option>
        <option value="Pfizer">Pfizer</option>
    </select>
    <label for="doseNumber">Dose Number:</label>
    <input type="number" id="doseNumber" name="doseNumber">
    <br><br>
    <div style="text-align: center">
    <input type="submit" name="addVaxRecord" value="Add Vaccination Record"/>
    </div>
</form>
</div>
    
    <?php
    require_once '../database.php';

    $sql = "SELECT vaccinationID, medicareNumber, date, type, doseNumber FROM vaccinations";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // output data of each 
        echo "<table>";
        echo "<caption>List of Vaccinations</caption>";
        echo "<tr>";
        echo "<th> ID </th>";
        echo "<th> Medicare Number </th>";
        echo "<th> Date </th>";
        echo "<th> Type </th>";
        echo "<th> Dose Number </th>";
        echo "<th> </th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";

            $id = $row["vaccinationID"];
            echo "<td>" . $row["vaccinationID"] . "</td>";
            echo "<td>" . $row["medicareNumber"] . "</td>";
            echo "<td>"  . $row["date"] . "</td>";
            echo "<td>"  . $row["type"] . "</td>";
            echo "<td>"  . $row["doseNumber"] . "</td>";
            echo "<td> <form  action ='./delete.php' method='post'> 
            <input type='hidden' id='vaccinationID' name='vaccinationID' value=$id> 
            <input type='submit' name='deleteVaccination' value='Delete Vaccination Record'/>
            </form>
            <form  action ='./edit.php' method='get'> 
            <input type='hidden' id='vaccinationID' name='vaccinationID' value=$id> 
            <input type='submit' name='editVaccination' value='Edit Vaccination Record'/>
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
