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
$doctorID = $_SESSION['userID'];
if(isset($pdo)) {
  $stmt = $pdo->prepare("SELECT doctorName FROM doctor WHERE doctorId = :doctorID");
  $stmt->bindParam(":doctorID", $doctorID);
  $stmt->execute();
  $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
}

// get the appointments for the patient
if(isset($pdo)) {
    $stmt = $pdo->prepare("SELECT appointment.*, doctor.doctorName, patient.patientName FROM appointment JOIN doctor
                           ON appointment.doctorId = doctor.doctorId JOIN patient ON appointment.patientId = patient.patientId
                           WHERE appointment.doctorID = :doctorID AND appointment.appointmentStatus = 'admitted'");
    $stmt->bindParam(":doctorID", $doctorID);
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

// appointmetn showing dischared patient 
if(isset($pdo)) {
    $stmt = $pdo->prepare("SELECT appointment.*, doctor.doctorName, patient.patientName FROM appointment JOIN doctor
                           ON appointment.doctorId = doctor.doctorId JOIN patient ON appointment.patientId = patient.patientId
                           WHERE appointment.doctorID = :doctorID AND appointment.appointmentStatus = 'discharged'");
    $stmt->bindParam(":doctorID", $doctorID);
    $stmt->execute();
    $appointmentsDischarged = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
?>


<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Doctor</title>
    
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
          <a class="navbar-brand" href="/hospital/patient.html">Welcome Back - <?php echo $doctor['doctorName']; ?></a>
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
    <?php
    if(isset($_GET['success']) && $_GET['success'] == 'note_updated') {
        echo '<div class="alert alert-success" role="alert" style="margin-bottom:0px;">Note updated successfully!</div>';
        echo '<script>setTimeout(function(){ $(".alert-success").remove(); }, 3000);</script>';
    }
    ?>

    <?php
    if(isset($_GET['error']) && $_GET['error'] == 'note_update_failed') {
        echo '<div class="alert alert-danger" role="alert" style="margin-bottom:0px;">Note update failed!</div>';
        echo '<script>setTimeout(function(){ $(".alert-danger").remove(); }, 3000);</script>';
    }
    ?>

      <br>
      <br>

      <div class="container">
        <br>
        <div id="appointments">
        <table class="table table-striped" id="patientTable">
            <thead>
              <tr>
                <th scope="col">Patient ID</th>
                <th scope="col">Patient Name</th>
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
                <td><?php echo $row['patientName']; ?></td>
                <td><?php echo $row['appointmentDate']; ?></td>
                <td><?php echo $row['appointmentTime']; ?></td>
                <td><?php echo $row['appointmentStatus']; ?></td>   
                <td>
                    <button type="button" class="btn btn-primary btn-sm btn" data-bs-toggle="modal" data-bs-target="#exampleModal" data-note="<?php echo $row['doctorNote']; ?>">Edit Note</button>
                    <input type="hidden" class="appointment-id" value="<?php echo $row['appointmentId']; ?>">
                </td>             
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table></div></div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Doctor's Note</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="note-form" method="post" action="update_doctor_note.php">

                <div class="mb-3">
                    <label for="note" class="form-label">Note:</label>
                    <textarea class="form-control" name="note" id="note" rows="5"></textarea>
                    <input type="hidden" name="appointmentId" id="appointmentIdInput" value="">
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveNoteBtn">Save Changes</button>
            </div></form>
            </div>
        </div>
        </div>

        <div class="container">
        <br><br>
        <div class="row" style="width:fit-content; margin-left: 2px;">
        <button type="button" class="btn btn-primary btn-sm btn" id="toggleTableBtn" style="padding: 0.5em;">Show Discharged Patients</button>
        </div>        
        <br>
        <br>
        <div class="row table-container d-none">
        <table class="table table-striped" id="discharedTable">
            <thead>
              <tr>
                <th scope="col">Patient ID</th>
                <th scope="col">Patient Name</th>
                <th scope="col">Appointment Date</th>
                <th scope="col">Appointment Time</th>
                <th scope="col">Status</th>
                <th scope="col">Doctor's Note</th>
              </tr>
            </thead>
            <tbody id="patientTbody">
              <?php foreach ($appointmentsDischarged as $row): ?>
              <tr>
                <td><?php echo $row['patientId']; ?></td>
                <td><?php echo $row['patientName']; ?></td>
                <td><?php echo $row['appointmentDate']; ?></td>
                <td><?php echo $row['appointmentTime']; ?></td>
                <td><?php echo $row['appointmentStatus']; ?></td>   
                <td>
                    <button type="button" class="btn btn-primary btn-sm btn" onclick="openModal(this)" data-bs-toggle="modal" data-bs-target="#exampleModal1" data-note1="<?php echo $row['doctorNote']; ?>">View Note</button>
                </td>             
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table></div></div>

          <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
  const toggleTableBtn = document.getElementById("toggleTableBtn");
  const tableContainer = document.querySelector(".table-container");

  toggleTableBtn.addEventListener("click", () => {
    tableContainer.classList.toggle("d-none");
  });
</script>

<script>
  function openModal(button) {
    var note = button.getAttribute('data-note1')
    var modalBody = document.querySelector('#exampleModal1 .modal-body')
    modalBody.textContent = note
    $('#exampleModal1').modal('show')
  }
</script>
<!-- <script>//incase it doesn't work, uncomment this and remove the onclick in table #exampleModal1
  var myModal = document.getElementById('exampleModal1')
  myModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget
    var note = button.getAttribute('data-note1')
    var modalBody = myModal.querySelector('.modal-body')
    modalBody.textContent = note
  })
</script> -->

        <script>
            var myModal = document.getElementById('exampleModal')
            myModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget
                var note = button.getAttribute('data-note')
                var appointmentId = button.parentNode.querySelector('.appointment-id').value
                var modalBody = myModal.querySelector('.modal-body')
                modalBody.querySelector('#note').value = note
                modalBody.querySelector('#appointmentIdInput').value = appointmentId
            })

            var saveNoteBtn = document.getElementById('saveNoteBtn')
            saveNoteBtn.addEventListener('click', function () {
                var form = document.getElementById('note-form')
                form.submit()
            })
        </script>


          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
              <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

        <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    

</body>

</html>