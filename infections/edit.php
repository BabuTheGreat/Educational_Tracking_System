<?php require_once '../database.php';
    $stmt = mysqli_prepare($conn, "SELECT * FROM infections WHERE infectionID = ?");
    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $_GET["infectionID"]
    );

    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_fetch_assoc($result);

    if (isset(
        $_POST['infectionID'],
        $_POST['medicareNumber'],
        $_POST['infectionDate'],
        $_POST['infectionNature']
    ))
    {
        $stmt = mysqli_prepare($conn, "UPDATE infections
            SET 
                medicareNumber = ?,
                infectionDate = ?,
                infectionNature = ?
            WHERE infectionID = ?
        ");

        mysqli_stmt_bind_param(
            $stmt,
            'ssss', 
            $_POST['medicareNumber'],
            $_POST['infectionDate'],
            $_POST['infectionNature'],
            $_POST['infectionID']
        );

        if ($stmt->execute()) {
            echo "<script type='text/javascript'>alert('Successfully updated');</script>";
            header("Location: .");
        } else {
            echo "<script type='text/javascript'>alert('Update failed');</script>";
            header("Location: .");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Infections edit</title>

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
<form action ="./edit.php" method="post">
    <label for="infectionID">Infection ID:</label>
    <input type="number" id="infectionID" name="infectionID" value="<?= $row['infectionID']?>" readonly>
    <label for="medicareNumber">Medicare Number:</label>
    <input type="text" id="medicareNumber" name="medicareNumber" value="<?= $row['medicareNumber']?>">
    <br><br>
    <label for="infectionDate">Infection Date:</label>
    <input type="date" id="infectionDate" name="infectionDate" value="<?= $row['infectionDate']?>">
    <label for="infectionNature">Infection Nature:</label>
    <input type="text" id="infectionNature" name="infectionNature" value="<?= $row['infectionNature']?>">
    <br><br>
    <div style="text-align: center">
    <input type="submit" name="addInfectionRecord" value="Add Infection Record"/>
    </div>
</form>
</div>

</body>

</html>
