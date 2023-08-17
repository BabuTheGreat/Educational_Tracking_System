<?php require_once '../database.php';
    $stmt = mysqli_prepare($conn, "SELECT * FROM schedule WHERE scheduleID = ?");
    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $_GET["scheduleID"]
    );

    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_fetch_assoc($result);

    if (isset(
        $_POST['scheduleID'],
        $_POST['medicareNumber'],
        $_POST['facilityID'],
        $_POST['scheduleDate'],
        $_POST['startTime'],
        $_POST['endTime']
    ))
    {
        $stmt = mysqli_prepare($conn, "UPDATE schedule
            SET 
                medicareNumber = ?,
                facilityID = ?,
                scheduleDate = ?,
                startTime = ?,
                endTime = ?
            WHERE scheduleID = ?
        ");

        mysqli_stmt_bind_param(
            $stmt,
            'ssssss', 
            $_POST['medicareNumber'],
            $_POST['facilityID'],
            $_POST['scheduleDate'],
            $_POST['startTime'],
            $_POST['endTime'],
            $_POST['scheduleID']
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
    <title>Schedule edit</title>

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
        <label for="scheduleID">Schedule ID:</label>
        <input type="number" id="scheduleID" name="scheduleID" value="<?= $row['scheduleID']?>" readonly>
        <label for="medicareNumber">Employee Medicare Number:</label>
        <input type="text" id="medicareNumber" name="medicareNumber" value="<?= $row['medicareNumber']?>" required>
        <label for="facilityID">Facility ID:</label>
        <input type="number" id="facilityID" name="facilityID" value="<?= $row['facilityID']?>" required>
        <br><br>
        <label for="scheduleDate">Date:</label>
        <input type="date" id="scheduleDate" name="scheduleDate" value="<?= $row['scheduleDate']?>" required>
        <label for="startTime">Start Time:</label>
        <input type="time" id="startTime" name="startTime" value="<?= $row['startTime']?>" required>
        <label for="endTime">End Time:</label>
        <input type="time" id="endTime" name="endTime" value="<?= $row['endTime']?>" required>
        <div style="text-align: center">
        <br><br>
        <input type="submit" name="assignSchedule" value="Assign Schedule"/>
        </div>
    </form>
</div>

<br>

</body>

</html>

