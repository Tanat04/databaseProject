<?php
include 'database.include.php';
?>

<?php
session_start();


if (!isset($_SESSION['userID'])) {
  // user is not logged in, redirect to login page
  header('Location: login.php');
  exit;
}

// get the appointments for the patient
$patientID = $_SESSION['userID'];
if(isset($pdo)) {
  $stmt = $pdo->prepare("SELECT patientName FROM patient WHERE patientID = :patientID");
  $stmt->bindParam(":patientID", $patientID);
  $stmt->execute();
  $patient = $stmt->fetch(PDO::FETCH_ASSOC);
}

// get the appointments for the patient
if(isset($pdo)) {
  $stmt = $pdo->prepare("SELECT appointment.*, doctor.doctorName FROM appointment JOIN doctor 
  ON appointment.doctorId = doctor.doctorId WHERE appointment.patientID = :patientID");
  $stmt->bindParam(":patientID", $patientID);
  $stmt->execute();
  $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>


<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Patient</title>
    
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
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
          <a class="navbar-brand" href="/hospital/patient.php">Welcome Back - <?php echo $patient['patientName']; ?></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
            </div>
            <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="/hospital/">Log Out</a>
        </li>
      </ul>
          </div>
        </div>
      </nav>

      <br>
      <br>
      <div class="container">
      
      </div>
      <div class="container">
        <br>
        <div id="appointments">
        <table class="table table-striped" id="patientTable">
            <thead>
              <tr>
                <th scope="col">Patient ID</th>
                <th scope="col">Doctor Name</th>
                <th scope="col">Appointment Date</th>
                <th scope="col">Appointment Time</th>
                <th scope="col">Status</th>
                <th scope="col">Doctor's Note</th>
              </tr>
            </thead>
            <tbody id="patientTbody">
              <?php foreach ($appointments as $row): ?>
              <tr>
                <td><?php echo $row['patientId']; ?></td>
                <td><?php echo $row['doctorName']; ?></td>
                <td><?php echo $row['appointmentDate']; ?></td>
                <td><?php echo $row['appointmentTime']; ?></td>
                <td><?php echo $row['appointmentStatus']; ?></td>
                <td><button type="button" class="btn btn-primary btn-sm btn" data-bs-toggle="modal" data-bs-target="#exampleModal" data-note="<?php echo $row['doctorNote']; ?>">View Note</button></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table></div></div>

          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">Doctor's Note</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <script>
  var myModal = document.getElementById('exampleModal')
  myModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget
    var note = button.getAttribute('data-note')
    var modalBody = myModal.querySelector('.modal-body')
    modalBody.textContent = note
  })
</script>

          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
              <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

        <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    

</body>

</html>