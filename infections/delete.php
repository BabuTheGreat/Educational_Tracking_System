<?php require_once '../database.php';
    $stmt = mysqli_prepare($conn, "DELETE FROM infections WHERE infectionID = ?");
    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $_POST["infectionID"]
    );
    
    if ($stmt->execute()) {
        echo "<script type='text/javascript'>alert('Successfully inserted');</script>";
    } else {
        echo "<script type='text/javascript'>alert('Insert failed');</script>";
    }
    
    header("Location: .");
?>