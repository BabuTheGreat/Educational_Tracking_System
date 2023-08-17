<?php require_once '../database.php';

    $stmt = mysqli_prepare($conn, "SELECT * FROM members JOIN students ON members.medicareNumber = students.medicareNumber WHERE members.medicareNumber = ?");
    
    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $_GET["medicareNumber"]
    );
    
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_fetch_assoc($result);
    
    $expectedFieldNames = array(
        'medicareNumber', 'medicareExpiryDate', 'facilityID', 'firstName', 'lastName', 
        'email', 'phoneNumber', 'dateOfBirth', 'address', 'city', 
        'postalCode', 'province', 'citizenship', 'level'
    );
    
    $allValuesReceived = true;

    foreach ($expectedFieldNames as $fieldName) {
        if (empty($_POST[$fieldName])) {
            $allValuesReceived = false;
            break; 
        }
    }
    
    if ($allValuesReceived)
    {
        
        $stmt = mysqli_prepare($conn, "UPDATE students
            SET level = ?
            WHERE medicareNumber = ?
        ");

        mysqli_stmt_bind_param(
            $stmt,
            'ss', 
            $_POST['level'],
            $_POST['medicareNumber']
        );
        
        try {
            if (!$stmt->execute()){
                echo "<script type='text/javascript'>alert('Update failed');</script>";
                return;
            }
        } catch (Exception $e) {
            echo $e;
            return;
        }

        $stmt = mysqli_prepare($conn, "UPDATE members
            SET 
                medicareExpiryDate = ?,
                facilityID = ?,
                firstName = ?,
                lastName = ?,
                email = ?,
                phoneNumber = ?,
                dateOfBirth = ?,
                address = ?,
                city = ?,
                postalCode = ?,
                province = ?,
                citizenship = ?
            WHERE medicareNumber = ?
        ");
        
        mysqli_stmt_bind_param(
            $stmt,
            'sssssssssssss', 
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
            $_POST['citizenship'],
            $_POST['medicareNumber']
        );
        
        try {
            if (!$stmt->execute()){
                echo "<script type='text/javascript'>alert('Update failed');</script>";
                return;
            }
        } catch (Exception $e) {
            echo $e;
            return;
        }

        header("Location: .");
    }
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Student edit</title>

    <style>
        table,
        th,
        td {
            border: 2px solid black;
            border-collapse: collapse;
            width: 800px;
            text-align: center;
            margin: auto;
        }
    </style>
</head>

<body>

<div style="display:flex; justify-content: center;">
<form action ="./edit.php" method="post">
    <label id="labelMedicareNumber" for="medicareNumber">Medicare Number:</label>
    <input type="text" id="medicareNumber" name="medicareNumber" value="<?= $row['medicareNumber']?>" readonly>
    <label for="medicareExpiryDate">Date:</label>
    <input type="date" id="medicareExpiryDate" name="medicareExpiryDate" value="<?= $row['medicareExpiryDate']?>">
    <label for="facilityID">Facility ID:</label>
    <input type="number" id="facilityID" name="facilityID" value="<?= $row['facilityID']?>">
    <br><br>
    <label for="firstName">First Name:</label>
    <input type="text" id="firstName" name="firstName" value="<?= $row['firstName']?>">
    <label for="lastName">Last Name:</label>
    <input type="text" id="lastName" name="lastName" value="<?= $row['lastName']?>">
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" value="<?= $row['email']?>">
    <br><br>
    <label for="phoneNumber">Phone Number:</label>
    <input type="text" id="phoneNumber" name="phoneNumber" value="<?= $row['phoneNumber']?>">
    <label for="dateOfBirth">Date of Birth:</label>
    <input type="date" id="dateOfBirth" name="dateOfBirth" value="<?= $row['dateOfBirth']?>">
    <label for="address">Address:</label>
    <input type="text" id="address" name="address" value="<?= $row['address']?>">
    <br><br>
    <label for="city">City:</label>
    <input type="text" id="city" name="city" value="<?= $row['city']?>">
    <label for="postalCode">Postal Code:</label>
    <input type="text" id="postalCode" name="postalCode" value="<?= $row['postalCode']?>">
    <label for="province">Province:</label>
    <input type="text" id="province" name="province" value="<?= $row['province']?>">
    <label for="citizenship">Citizenship:</label>
    <input type="text" id="citizenship" name="citizenship" value="<?= $row['citizenship']?>">
    <br><br>
    <label id="labelLevel" for="level">Level:</label>
    <select id="level" name="level">
        <option selected="selected" value="<?= $row['level']?>"><?= $row['level']?></option>
        <option value="Elementary 1">Elementary 1</option>
        <option value="Elementary 2">Elementary 2</option>
        <option value="Elementary 3">Elementary 3</option>
        <option value="Elementary 4">Elementary 4</option>
        <option value="Elementary 5">Elementary 5</option>
        <option value="Elementary 6">Elementary 6</option>
        <option value="Middle 1">Middle 1</option>
        <option value="Middle 2">Middle 2</option>
        <option value="Middle 3">Middle 3</option>
        <option value="High 1">High 1</option>
        <option value="High 2">High 2</option>
        <option value="High 3">High 3</option>
        <option value="High 4">High 4</option>
        <option value="High 5">High 5</option>
    </select>
    <br><br>
    <div style="text-align: center">
    <input type="submit" name="addStudent" value="Add Student"/>
    </div>
</form>
</div>
</body>

</html>

