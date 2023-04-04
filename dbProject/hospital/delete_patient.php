<?php
include 'database.include.php';

if (isset($_POST['patientId'])) {
  $patientId = $_POST['patientId'];

  try {
    $stmt = $pdo->prepare("DELETE FROM patient WHERE patientId = :patientId");
    $stmt->bindParam(":patientId", $patientId);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      echo 'success';
    } else {
      echo 'error';
    }
  } catch (PDOException $e) {
    echo 'error';
  }
} else {
  echo 'error';
}
?>