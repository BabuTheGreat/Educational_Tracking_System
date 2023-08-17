<?php require_once '../database.php';
    $stmt = mysqli_prepare($conn, "DELETE FROM schedule WHERE scheduleID = ?");
    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $_POST["scheduleID"]
    );
    
    if ($stmt->execute()) {
        echo "<script type='text/javascript'>alert('Successfully deleted');</script>";
    } else {
        echo "<script type='text/javascript'>alert('Delete failed');</script>";
    }
    
    header("Location: .");
?>