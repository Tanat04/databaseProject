<?php
include 'database.include.php';
?>

<?php

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID = $_POST['userID'];
    $password = $_POST['password'];

    if (strpos($userID, 'p') === 0) {
        $table = 'patient';
        $idField = 'patientId';
    } elseif (strpos($userID, 'd') === 0) {
        $table = 'doctor';
        $idField = 'doctorId';
    } elseif (strpos($userID, 'a') === 0) {
        $table = 'admin';
        $idField = 'adminId';
    } else {
        echo 'Invalid userID';
        exit;
    }
    
    $sql = "SELECT * FROM $table WHERE $idField='$userID' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        $_SESSION['sql'] = "SELECT * FROM appointment WHERE $idField='{$userID}'";
        if ($table == 'patient') {
            $_SESSION['userID'] = $row[$idField];
            header('Location: patient.php');
            exit;
        } elseif ($table == 'doctor') {
            $_SESSION['userID'] = $row[$idField];
            header('Location: doctor.php');
            exit;
        } elseif ($table == 'admin') {
            $_SESSION['userID'] = $row[$idField];
            header('Location: admin.php');
            exit;
        }
    } else {
        echo 'Invalid userID or password';
        exit;
    }
}
?>
