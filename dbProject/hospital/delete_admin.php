<?php
include 'database.include.php';

if (isset($_POST['adminId'])) {
  $adminId = $_POST['adminId'];

  try {
    $stmt = $pdo->prepare("DELETE FROM admin WHERE adminId = :adminId");
    $stmt->bindParam(":adminId", $adminId);
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