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

$adminID = $_SESSION['userID'];
if(isset($pdo)) {
  $stmt = $pdo->prepare("SELECT adminName FROM admin WHERE adminId = :adminID");
  $stmt->bindParam(":adminID", $adminID);
  $stmt->execute();
  $admin = $stmt->fetch(PDO::FETCH_ASSOC);
}

if(isset($pdo)) {
  $stmt = $pdo->prepare("SELECT * FROM admin");
  $stmt->execute();
  $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
<?php
// check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // get the form data
  $adminId = $_POST['id'];
  $adminName = $_POST['name'];
  $adminPhNo = $_POST['phone'];
  $adminPassword = $_POST['password'];

  // insert the data into the admin table
  if (isset($pdo)) {
      $stmt = $pdo->prepare("INSERT INTO admin (adminId, adminName, adminPhNo, password) VALUES (:adminId, :adminName, :adminPhNo, :adminPassword)");
      $stmt->bindParam(":adminId", $adminId);
      $stmt->bindParam(":adminName", $adminName);
      $stmt->bindParam(":adminPhNo", $adminPhNo);
      $stmt->bindParam(":adminPassword", $adminPassword);
      $stmt->execute();

      // redirect to the admin list page
      header('Location: admin.php');
      // redirect to the admin list page
      // Display an alert message
      $_SESSION['success_message'] = 'Admin added successfully';
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
        <title>Admin</title>
    
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
          <a class="navbar-brand" href="/hospital/admin.php">Welcome Back - <?php echo $admin['adminName']; ?></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link" aria-current="page" href="/hospital/doctorList.php">Doctor</a>
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
        <input class="form-control" type="text" name="id" placeholder="Admin ID" style="margin-bottom: 15px;" required>
        <input class="form-control" type="text" name="name" placeholder="Admin Name" style="margin-bottom: 15px;" required>
        <input class="form-control" type="password" name="password" placeholder="Admin password" style="margin-bottom: 15px;" required>
        <input class="form-control" type="text" name="phone" placeholder="Admin Phone Number" style="margin-bottom: 15px;">
        <button type="submit" class="btn btn-primary">Add</button>
      </form>
      </div>

      <div class="container">
        <br>
        <table class="table table-striped" id="admin-table">
            <thead>
              <tr>
                <th scope="col">Admin ID</th>
                <th scope="col">Admin Name</th>
                <th scope="col">Phone Number</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody id="adminTbody">
              <?php foreach ($admins as $row): ?>
                <tr data-id="<?php echo $row['adminId']; ?>">
                  <td><?php echo $row['adminId']; ?></td>
                  <td><?php echo $row['adminName']; ?></td>
                  <td><?php echo $row['adminPhNo']; ?></td>
                  <td>
                    <?php if ($row['adminId'] != $adminID): ?>
                      <button class="btn btn-outline-danger btn-sm remove-btn" onclick="removeAdmin('<?php echo $row['adminId']; ?>')">Remove</button>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table></div>
          <script>
            function removeAdmin(adminId) {
              if (confirm("Are you sure you want to delete this admin?")) {
                // Send an AJAX request to the server to delete the admin
                $.ajax({
                  url: 'delete_admin.php',
                  type: 'POST',
                  data: { adminId: adminId },
                  success: function(response) {
                    // If the deletion was successful, remove the row from the table
                    if (response == 'success') {
                      $('#adminTbody').find('tr[data-id="' + adminId + '"]').remove();
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