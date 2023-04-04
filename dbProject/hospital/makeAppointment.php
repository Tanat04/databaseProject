<?php
include 'database.include.php';
?>

<?php
session_start();


if(isset($pdo)) {
    $stmt = $pdo->prepare("
    SELECT a.*, p.patientName, d.doctorName
    FROM appointment a
    JOIN patient p ON a.patientId = p.patientId
    JOIN doctor d ON a.doctorId = d.doctorId
  ");
  $stmt->execute();
  $appointment = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($pdo)) {
    $stmt = $pdo->prepare("
        SELECT doctorId FROM doctor
    ");
    $stmt->execute();
    $doctorIds = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
        SELECT patientId FROM patient
    ");
    $stmt->execute();
    $patientIds = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
}

?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $AppointmentId = $_POST["AppointmentId"];
  $doctorId = $_POST["doctorId"];
  $patientId = $_POST["patientId"];
  $appointmentDate = $_POST["AppointmentDate"];
  $appointmentTime = $_POST["AppointmentTime"];
  $appointmentStatus = $_POST["status"];

  // Check if the selected appointment date exists in the appointment table
  $stmt = $pdo->prepare("
    SELECT COUNT(*) AS count FROM appointment
    WHERE doctorId = :doctorId AND appointmentDate = :appointmentDate AND appointmentStatus = 'Admitted'
  ");
  $stmt->execute([
    ':doctorId' => $doctorId,
    ':appointmentDate' => $appointmentDate
  ]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $count = $result['count'];

  // If count > 0, display an error message and ask the user to select the appointment date again
  if ($count > 0) {
    echo "<script>alert('Appointment with the selected doctor on the selected date is not available. Please select a different date.');</script>";
  } elseif (isset($pdo)) {
    $stmt = $pdo->prepare("INSERT INTO appointment (AppointmentId, doctorId, patientId, appointmentDate, appointmentTime, appointmentStatus, doctorNote) 
    VALUES (:AppointmentId, :doctorId, :patientId, :appointmentDate, :appointmentTime, :appointmentStatus, :doctorNote)");
    $stmt->bindParam(":AppointmentId", $AppointmentId);
    $stmt->bindParam(":doctorId", $doctorId);
    $stmt->bindParam(":patientId", $patientId);
    $stmt->bindParam(":appointmentDate", $appointmentDate);
    $stmt->bindParam(":appointmentTime", $appointmentTime);
    $stmt->bindParam(":appointmentStatus", $appointmentStatus);
    $doctorNote = ""; // set the doctorNote to an empty string
    $stmt->bindParam(":doctorNote", $doctorNote);
    $stmt->execute();

    // redirect to the admin list page
    header('Location: makeAppointment.php');
    // redirect to the admin list page
    // Display an alert message
    $_SESSION['success_message'] = 'Appointment has been made successfully';
    exit;
} else {
    echo "Error: PDO is not set.";
}
}
?>


<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Appointment</title>
    
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
              <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

        <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
          <script src="https://cdn.jsdelivr.net/npm/respond.js@1.4.2/dest/respond.min.js"></script>
        <![endif]-->
    </head>
  
  <body ><!--Call loadData from products.js-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="/hospital/admin.php">Admin</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link" aria-current="page" href="/hospital/doctorList.php">Doctor</a>
                <a class="nav-link" href="/hospital/patientList.php">Patient</a>
                <a class="nav-link active" aria-current="page" href="/hospital/makeAppointment.php">Appointment</a>
            </div>
            
            <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="/hospital/">Log Out</a>
        </li>
      </ul>
          </div>
        </div>
      </nav>

      <?php
      // check if the success message is set and display it
      if (isset($_SESSION['success_message'])) {
          echo '<div class="alert alert-success" role="alert" style="margin-bottom:0px;">' . $_SESSION['success_message'] . '</div>';
          echo '<script>setTimeout(function(){ $(".alert-success").remove(); }, 3000);</script>';
          // unset the success message to prevent it from displaying again
          unset($_SESSION['success_message']);
      }
      ?>

      <br>
      <br>
      <div class="container">
      <form method="post">
        <input class="form-control" type="number" name="AppointmentId" placeholder="AppointmentId" style="margin-bottom: 15px;" required>
        <select class="form-control" name="doctorId" style="margin-bottom: 15px;" required>
            <option value="">Select a doctor</option>
            <?php foreach ($doctorIds as $doctor) { ?>
            <option value="<?php echo $doctor['doctorId']; ?>"><?php echo $doctor['doctorId']; ?></option>
            <?php } ?>
        </select>      
        <select class="form-control" name="patientId" style="margin-bottom: 15px;" required>
            <option value="">Select a patient</option>
            <?php foreach ($patientIds as $patient) { ?>
            <option value="<?php echo $patient['patientId']; ?>"><?php echo $patient['patientId']; ?></option>
            <?php } ?>
        </select>       
        <input class="form-control" type="date" name="AppointmentDate" placeholder="AppointmentDate" style="margin-bottom: 15px;" required>
        <input class="form-control" type="time" name="AppointmentTime" placeholder="AppointmentTime" style="margin-bottom: 15px;" required>
        <input class="form-control" type="text" id="status" name="status" value="admitted" style="margin-bottom: 15px;" readonly>
        <button type="submit" class="btn btn-primary">Add</button>
      </form>
      </div>

      <div class="container">
        <br>
        <table class="table table-striped" id="appointment-table">
            <thead>
              <tr>
                <th scope="col">Appointment ID</th>
                <th scope="col">Patient ID</th>
                <th scope="col">Patient Name</th>
                <th scope="col">Doctor ID</th>
                <th scope="col">Doctor Name</th>
                <th scope="col">Appointment Date</th>
                <th scope="col">Appointment Time</th>
                <th scope="col">Appointment Status</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody id="appointmentTbody">
              <?php foreach ($appointment as $row): ?>
                <tr data-id="<?php echo $row['appointmentId']; ?>">
                  <td><?php echo $row['appointmentId']; ?></td>
                  <td><?php echo $row['patientId']; ?></td>
                  <td><?php echo $row['patientName']; ?></td>
                  <td><?php echo $row['doctorId']; ?></td>
                  <td><?php echo $row['doctorName']; ?></td>
                  <td><?php echo $row['appointmentDate']; ?></td>
                  <td><?php echo $row['appointmentTime']; ?></td>
                  <td><?php echo $row['appointmentStatus']; ?></td>
                  <td><button class="btn btn-outline-primary btn-sm update-btn">Change Status</button></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table></div>

          <!-- Modal for changing appointment status -->
<div class="modal fade" id="status-modal" tabindex="-1" role="dialog" aria-labelledby="status-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="status-modal-label">Change Appointment Status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Select new appointment status:</p>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="status" id="admitted-radio" value="admitted" checked>
          <label class="form-check-label" for="admitted-radio">
            admitted
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="status" id="discharged-radio" value="discharged">
          <label class="form-check-label" for="discharged-radio">
            discharged
          </label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirm-btn">Confirm</button>
      </div>
    </div>
  </div>
</div>


          <script> 
         // get all the buttons with class "update-btn"
// Add click event listener to update button
$('#appointment-table').on('click', '.update-btn', function() {
  var appointmentId = $(this).closest('tr').data('id');
  
  // Display modal with options for admitted or discharged
  $('#status-modal').modal('show');
  
  // Set click event listener for confirm button
  $('#status-modal').on('click', '#confirm-btn', function() {
    var status = $('input[name=status]:checked').val();
    
    // Make AJAX request to update appointment status in database
    $.ajax({
      type: 'POST',
      url: 'updateAppointmentStatus.php',
      data: { appointmentId: appointmentId, status: status },
      success: function(response) {
   // Update table with new appointment status
   var tr = $("tr[data-id='" + appointmentId + "']");
   tr.find("td:eq(7)").html(status);
   $('#updateAppointmentModal').modal('hide');
},
      error: function(xhr, status, error) {
        console.log(error);
      }
    });
    
    // Hide modal
    $('#status-modal').modal('hide');
  });
});

        </script>

          
</body>

</html>