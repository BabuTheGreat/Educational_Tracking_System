<?php
require_once '../database.php';

if (
    isset(
        $_POST['scheduleID'],
        $_POST['medicareNumber'],
        $_POST['facilityID'],
        $_POST['scheduleDate'],
        $_POST['startTime'],
        $_POST['endTime']
    )
) {
    $query = "INSERT INTO schedule VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param(
        $stmt,
        'ssssss', 
        $_POST['scheduleID'],
        $_POST['medicareNumber'],
        $_POST['facilityID'],
        $_POST['scheduleDate'],
        $_POST['startTime'],
        $_POST['endTime']
    );

    try  {
        if ($stmt->execute()) {
            echo "<script type='text/javascript'>alert('Successfully inserted');</script>";
        } else {
            echo "<script type='text/javascript'>alert('Insert failed" + mysqli_stmt_error($stmt) + "');</script>";
        }
        header("Location: .");
    }
    catch(Exception $e) {
        echo $e;
    }
}
?>
´