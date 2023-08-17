<?php require_once '../database.php';
    $stmt = mysqli_prepare($conn, "DELETE FROM vaccinations WHERE vaccinationID = ?");
    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $_POST["vaccinationID"]
    );
    
    if ($stmt->execute()) {
        echo "<script type='text/javascript'>alert('Successfully inserted');</script>";
    } else {
        echo "<script type='text/javascript'>alert('Insert failed');</script>";
    }
    
    header("Location: .");
?>