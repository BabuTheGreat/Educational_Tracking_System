<?php require_once '../database.php';
    $stmt = mysqli_prepare($conn, "SELECT * FROM student_histories WHERE medicareNumber = ? AND registrationStartDate = ?");
    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $_GET["medicareNumber"],
        $_GET["registrationStartDate"]
    );

    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_fetch_assoc($result);

    if (isset(
        $_POST['medicareNumber'],
        $_POST['registrationStartDate'],
        $_POST['registrationEndDate'],
        $_POST['facilityID']
    ))
    {
        $stmt = mysqli_prepare($conn, "UPDATE student_histories
            SET 
                registrationEndDate = ?,
                facilityID = ?
            WHERE medicareNumber = ? AND registrationStartDate = ?
        ");

        mysqli_stmt_bind_param(
            $stmt,
            'ssss', 
            $_POST['registrationEndDate'],
            $_POST['facilityID'],
            $_POST['medicareNumber'],
            $_POST['registrationStartDate']
        );

        try  {
            if ($stmt->execute()) {
                echo "<script type='text/javascript'>alert('Successfully updated');</script>";
            } else {
                echo "<script type='text/javascript'>alert('Update failed" + mysqli_stmt_error($stmt) + "');</script>";
            }
            header("Location: .");
        }
        catch(Exception $e) {
            echo $e;
        }
    }
?>
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
<form action="./edit.php" method="post">
    <label for="medicareNumber">Medicare Number:</label>
    <input type="text" id="medicareNumber" name="medicareNumber" value="<?= $row['medicareNumber']?>" readonly>
    <label for="registrationStartDate">Registration Start Date:</label>
    <input type="date" id="registrationStartDate" name="registrationStartDate" value="<?= $row['registrationStartDate']?>" required>
    <br><br>
    <label for="registrationEndDate">Registration End Date:</label>
    <input type="date" id="registrationEndDate" name="registrationEndDate" value="<?= $row['registrationEndDate']?>">
    <br><br>
    <label for="facilityID">Facility ID:</label>
    <input type="number" id="facilityID" name="facilityID" value="<?= $row['facilityID']?>">
    <div style="text-align: center">
    <input type="submit" name="addStudent" value="Register Student"/>
    </div>
</form>
</div>

</body>

</html>
