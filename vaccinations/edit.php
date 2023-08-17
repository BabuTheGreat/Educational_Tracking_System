<?php require_once '../database.php';
    $stmt = mysqli_prepare($conn, "SELECT * FROM vaccinations WHERE vaccinationID = ?");
    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $_GET["vaccinationID"]
    );

    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_fetch_assoc($result);

    if (isset(
        $_POST['vaccinationID'],
        $_POST['medicareNumber'],
        $_POST['date'],
        $_POST['type'],
        $_POST['doseNumber']
    ))
    {
        $stmt = mysqli_prepare($conn, "UPDATE vaccinations
            SET 
                medicareNumber = ?,
                date = ?,
                type = ?,
                doseNumber = ?
            WHERE vaccinationID = ?
        ");

        mysqli_stmt_bind_param(
            $stmt,
            'sssss', 
            $_POST['medicareNumber'],
            $_POST['date'],
            $_POST['type'],
            $_POST['doseNumber'],
            $_POST['vaccinationID']
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
    <title>Vaccinations edit</title>

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
    <label for="vaccinationID">Vaccination ID:</label>
    <input type="number" id="vaccinationID" name="vaccinationID" value="<?= $row['vaccinationID']?>" readonly>
    <label for="medicareNumber">Medicare Number:</label>
    <input type="text" id="medicareNumber" name="medicareNumber" value="<?= $row['medicareNumber']?>">
    <br><br>
    <label for="date">Date:</label>
    <input type="date" id="date" name="date" value="<?= $row['date']?>">
    <label for="type">Type:</label>
    <select id="type" name="type">
        <option selected="selected" value="<?= $row['type']?>"><?= $row['type']?></option>
        <option value="AstraZeneca">AstraZeneca</option>
        <option value="Johnson & Johnson">Johnson & Johnson</option>
        <option value="Moderna">Moderna</option>
        <option value="Pfizer">Pfizer</option>
    </select>
    <label for="doseNumber">Dose Number:</label>
    <input type="number" id="doseNumber" name="doseNumber" value="<?= $row['doseNumber']?>">
    <br><br>
    <div style="text-align: center">
    <input type="submit" name="addVaxRecord" value="Add Vaccination Record"/>
    </div>
</form>
</div>
</body>

</html>
