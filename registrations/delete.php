<?php require_once '../database.php';
    $stmt = mysqli_prepare($conn, "DELETE FROM student_histories WHERE medicareNumber = ? AND registrationStartDate = ?");
    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $_POST["medicareNumber"],
        $_POST["registrationStartDate"]
    );
    
    try  {
        if ($stmt->execute()) {
            echo "<script type='text/javascript'>alert('Successfully deleted');</script>";
        } else {
            echo "<script type='text/javascript'>alert('Delete failed" + mysqli_stmt_error($stmt) + "');</script>";
        }
        header("Location: .");
    }
    catch(Exception $e) {
        echo $e;
    }
?>