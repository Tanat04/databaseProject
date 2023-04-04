<?php
include 'database.include.php';
?>

<?php
session_start();


if(isset($pdo)) {
  $stmt = $pdo->prepare("SELECT * FROM doctor");
  $stmt->execute();
  $doctor = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
<?php
// check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // get the form data
  $doctorId = $_POST['id'];
  $doctorName = $_POST['name'];
  $doctorPhNo = $_POST['phone'];
  $doctorPassword = $_POST['password'];

  // insert the data into the admin table
  if (isset($pdo)) {
      $stmt = $pdo->prepare("INSERT INTO doctor (doctorId, doctorName, doctorPhNo, password) VALUES (:doctorId, :doctorName, :doctorPhNo, :doctorPassword)");
      $stmt->bindParam(":doctorId", $doctorId);
      $stmt->bindParam(":doctorName", $doctorName);
      $stmt->bindParam(":doctorPhNo", $doctorPhNo);
      $stmt->bindParam(":doctorPassword", $doctorPassword);
      $stmt->execute();

      // redirect to the admin list page
      header('Location: doctorList.php');
      // redirect to the admin list page
      // Display an alert message
      $_SESSION['success_message'] = 'Doctor added successfully';
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
        <title>DoctorList</title>
    
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
          <a class="navbar-brand" href="/hospital/admin.php">Admin</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link active" aria-current="page" href="/hospital/doctorList.php">Doctor</a>
                <a class="nav-link" href="/hospital/patientList.php">Patient</a>
                <a class="nav-link" aria-current="page" href="/hospital/makeAppointment.php">Appointment</a>
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
        <input class="form-control" type="text" name="id" placeholder="Doctor ID" style="margin-bottom: 15px;" required>
        <input class="form-control" type="text" name="name" placeholder="Doctor Name" style="margin-bottom: 15px;" required>
        <input class="form-control" type="password" name="password" placeholder="Doctor password" style="margin-bottom: 15px;" required>
        <input class="form-control" type="text" name="phone" placeholder="Doctor Phone Number" style="margin-bottom: 15px;">
        <button type="submit" class="btn btn-primary">Add</button>
      </form>
      </div>

      <div class="container">
        <br>
        <table class="table table-striped" id="doctor-table">
            <thead>
              <tr>
                <th scope="col">Doctor ID</th>
                <th scope="col">Doctor Name</th>
                <th scope="col">Phone Number</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody id="doctorTbody">
              <?php foreach ($doctor as $row): ?>
                <tr data-id="<?php echo $row['doctorId']; ?>">
                  <td><?php echo $row['doctorId']; ?></td>
                  <td><?php echo $row['doctorName']; ?></td>
                  <td><?php echo $row['doctorPhNo']; ?></td>
                  <td><button class="btn btn-outline-danger btn-sm remove-btn" onclick="removeDoctor('<?php echo $row['doctorId']; ?>')">Remove</button></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table></div>
          <script>
            function removeDoctor(doctorId) {
              if (confirm("Are you sure you want to delete this doctor?")) {
                // Send an AJAX request to the server to delete the doctor
                $.ajax({
                  url: 'delete_doctor.php',
                  type: 'POST',
                  data: { doctorId: doctorId },
                  success: function(response) {
                    // If the deletion was successful, remove the row from the table
                    if (response == 'success') {
                      $('#doctorTbody').find('tr[data-id="' + doctorId + '"]').remove();
                    }
                  }
                });
              }
            }
          </script>

          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
              <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

        <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    

</body>

</html>