<?php
require_once '../database.php';

if (
    isset(
        $_POST['infectionID'],
        $_POST['medicareNumber'],
        $_POST['infectionDate'],
        $_POST['infectionNature']
    )
) {
    $query = "INSERT INTO infections VALUES (?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param(
        $stmt,
        'ssss', 
        $_POST['infectionID'],
        $_POST['medicareNumber'],
        $_POST['infectionDate'],
        $_POST['infectionNature']
    );
    
    if ($stmt->execute()) {
        echo "<script type='text/javascript'>alert('Successfully inserted');</script>";
    } else {
        echo "<script type='text/javascript'>alert('Insert failed');</script>";
    }
    header("Location: .");
}
?>
