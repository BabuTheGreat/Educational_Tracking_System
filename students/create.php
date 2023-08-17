<?php
require_once '../database.php';

$expectedFieldNames = array(
    'medicareNumber', 'medicareExpiryDate', 'facilityID', 'firstName', 'lastName', 
    'email', 'phoneNumber', 'dateOfBirth', 'address', 'city', 
    'postalCode', 'province', 'citizenship'
);

$allValuesReceived = true;

foreach ($expectedFieldNames as $fieldName) {
    if (empty($_POST[$fieldName])) {
        $allValuesReceived = false;
        break; 
    }
}

if ($allValuesReceived) {
    $query = "INSERT INTO members VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param(
        $stmt,
        'sssssssssssss', 
        $_POST['medicareNumber'],
        $_POST['medicareExpiryDate'],
        $_POST['facilityID'],
        $_POST['firstName'],
        $_POST['lastName'],
        $_POST['email'],
        $_POST['phoneNumber'],
        $_POST['dateOfBirth'],
        $_POST['address'],
        $_POST['city'],
        $_POST['postalCode'],
        $_POST['province'],
        $_POST['citizenship']
    );
    try {
        if (!$stmt->execute()){
            echo "<script type='text/javascript'>alert('Insert failed in members table');</script>";
            return;
        }
    } catch (Exception $e) {
        echo $e;
        return;
    }
    
}

if (!empty($_POST['medicareNumber']) && !empty($_POST['level'])){
    $query = "INSERT INTO students VALUES (?, ?)";
    
    $stmt = mysqli_prepare($conn, $query);
    
    mysqli_stmt_bind_param(
        $stmt,
        'ss', 
        $_POST['medicareNumber'],
        $_POST['level']
    );
    try {
        if ($stmt->execute()) {
            echo "<script type='text/javascript'>alert('Successfully inserted');</script>";
        } else {
            echo "<script type='text/javascript'>alert('Insert failed');</script>";
            echo "test";
        }
    } catch (Exception $e) {
        echo $e;
        return;
    }
    header("Location: .");
}
?>
