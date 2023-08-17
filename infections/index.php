<!DOCTYPE html>
<html lang="en">

<head>
    <title>Infections</title>

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
    <label for="infectionID">Infection ID:</label>
    <input type="number" id="infectionID" name="infectionID" required>
    <label for="medicareNumber">Medicare Number:</label>
    <input type="text" id="medicareNumber" name="medicareNumber">
    <br><br>
    <label for="infectionDate">Infection Date:</label>
    <input type="date" id="infectionDate" name="infectionDate">
    <label for="infectionNature">Infection Nature:</label>
    <input type="text" id="infectionNature" name="infectionNature">
    <br><br>
    <div style="text-align: center">
    <input type="submit" name="addInfectionRecord" value="Add Infection Record"/>
    </div>
</form>
</div>
    
    <?php
    require_once '../database.php';

    $sql = "SELECT infectionID, medicareNumber, infectionDate, infectionNature FROM infections";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // output data of each 
        echo "<table>";
        echo "<caption>List of Infections</caption>";
        echo "<tr>";
        echo "<th> ID </th>";
        echo "<th> Medicare Number </th>";
        echo "<th> Date </th>";
        echo "<th> Nature </th>";
        echo "<th></th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";

            $id = $row["infectionID"];
            echo "<td>" . $row["infectionID"] . "</td>";
            echo "<td>" . $row["medicareNumber"] . "</td>";
            echo "<td>"  . $row["infectionDate"] . "</td>";
            echo "<td>"  . $row["infectionNature"] . "</td>";
            echo "<td> <form  action ='./delete.php' method='post'> 
            <input type='hidden' id='infectionID' name='infectionID' value=$id> 
            <input type='submit' name='deleteInfection' value='Delete Infection Record'/>
            </form>
            <form  action ='./edit.php' method='get'> 
            <input type='hidden' id='infectionID' name='infectionID' value=$id> 
            <input type='submit' name='editInfection' value='Edit Infection Record'/>
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
