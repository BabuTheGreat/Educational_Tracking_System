<?php
require_once '../database.php';

if (
    isset(
        $_POST['vaccinationID'],
        $_POST['medicareNumber'],
        $_POST['date'],
        $_POST['type'],
        $_POST['doseNumber']
    )
) {
    $query = "INSERT INTO vaccinations VALUES (?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param(
        $stmt,
        'sssss', 
        $_POST['vaccinationID'],
        $_POST['medicareNumber'],
        $_POST['date'],
        $_POST['type'],
        $_POST['doseNumber']
    );
    
    if ($stmt->execute()) {
        echo "<script type='text/javascript'>alert('Successfully inserted');</script>";
    } else {
        echo "<script type='text/javascript'>alert('Insert failed');</script>";
    }
    header("Location: .");
}
?>
