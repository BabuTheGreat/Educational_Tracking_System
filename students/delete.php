<?php require_once '../database.php';
    $stmt = mysqli_prepare($conn, "DELETE FROM students WHERE medicareNumber = ?");
    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $_POST["medicareNumber"]
    );

    try {
        if (!$stmt->execute()) {
            echo "<script type='text/javascript'>alert('Insert failed');</script>";
            return;
        }
    } catch (Exception $e) {
        echo $e;
        return;
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM members WHERE medicareNumber = ?");
    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $_POST["medicareNumber"]
    );
    try {
        if ($stmt->execute()) {
            echo "<script type='text/javascript'>alert('Successfully inserted');</script>";
        } else {
            echo "<script type='text/javascript'>alert('Insert failed');</script>";
        }
    } catch (Exception $e) {
        echo $e;
    }
    
    header("Location: .");
?>