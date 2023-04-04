<?php

// Connect to database
$conn = new mysqli('localhost', 'root', '', 'dbmsProject');

// Check for errors
if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}

// Get appointment ID and new status from POST data
$appointmentId = $_POST['appointmentId'];
$status = $_POST['status'];

// Update appointment status in database
$sql = "UPDATE appointment SET appointmentStatus = '$status' WHERE appointmentId = $appointmentId";

if ($conn->query($sql) === TRUE) {
  echo "Appointment status updated successfully";
} else {
  echo "Error updating appointment status: " . $conn->error;
}

// Close database connection
$conn->close();

?>
