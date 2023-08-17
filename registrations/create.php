<?php
require_once '../database.php';

if (
    isset(
        $_POST['medicareNumber'],
        $_POST['registrationStartDate'],
        $_POST['registrationEndDate'],
        $_POST['facilityID']
    )
) {
    $query = "INSERT INTO student_histories VALUES (?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param(
        $stmt,
        'ssss', 
        $_POST['medicareNumber'],
        $_POST['registrationStartDate'],
        $_POST['registrationEndDate'],
        $_POST['facilityID']
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