<?php

// check if the form has been submitted
if(isset($_POST['note']) && isset($_POST['appointmentId'])) {

  // retrieve the note and appointment ID from the form
  $note = $_POST['note'];
  $appointmentId = $_POST['appointmentId'];

  // connect to the database
  $dsn = 'mysql:host=localhost;dbname=dbmsProject';
  $username = 'root';
  $password = '';

  try {
    $pdo = new PDO($dsn, $username, $password);
  } catch(PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
  }

  // update the doctor's note in the appointment table
  $stmt = $pdo->prepare("UPDATE appointment SET doctorNote = :note WHERE appointmentId = :appointmentId");
  $stmt->bindParam(":note", $note);
  $stmt->bindParam(":appointmentId", $appointmentId);

  if($stmt->execute()) {
    // redirect back to the appointment list with a success message
    header("Location: doctor.php?success=note_updated");
  } else {
    // redirect back to the appointment list with an error message
    header("Location: doctor.php?error=note_update_failed");
  }

} else {
  // if the form has not been submitted, redirect back to the appointment list
  header("Location: doctor.php");
}

?>