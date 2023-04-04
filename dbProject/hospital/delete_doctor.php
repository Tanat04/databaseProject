<?php
include 'database.include.php';

if (isset($_POST['doctorId'])) {
  $doctorId = $_POST['doctorId'];

  try {
    $stmt = $pdo->prepare("DELETE FROM doctor WHERE doctorId = :doctorId");
    $stmt->bindParam(":doctorId", $doctorId);
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