<?php
/* Template Name: Waritable template */
include_once('dbUtil/connect.php'); // include the database connection file

// Check if the delete button is clicked
if (isset($_POST['delete_btn'])) {

  // Sanitize the input to prevent SQL injection
  $id = mysqli_real_escape_string($conn, $_POST['warkariID']);

  // Check if the record exists before attempting to delete it
  $sql_check = "SELECT * FROM warkari WHERE warkariID='$id'";
  $result_check = mysqli_query($conn, $sql_check);

  if (mysqli_num_rows($result_check) == 0) { // Check if the record exists in the table
    echo "Record does not exist";
  } else {
    // Prepare the SQL statement to delete the record
    $sql = "DELETE FROM warkari WHERE warkariID=?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the parameter to the prepared statement
    mysqli_stmt_bind_param($stmt, "i", $id);

    // Execute the prepared statement to delete the record
    mysqli_stmt_execute($stmt);

    // Delete any child records associated with the deleted record
    $sql = "DELETE FROM tappaEnrollment WHERE warkariID=?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_affected_rows($conn) > 0) { // Check if the record has been deleted
      // Set a session variable to store the success message
      session_start();
      $_SESSION["delete"] = "Record Deleted Successfully!";

      // Redirect to the Warkari page after deleting the record
      header("Location:./Warkari");
    } else {
      // If record cannot be deleted due to some reason, store the error message in a variable
      $error = "Unable to delete the record.";
      // Redirect to the Warkari page with the error message
      header("Location: ./Warkari?error=" . $error);
    }
  }
}

// Include the header file
get_header();
?>


<!-- Start of HTML code -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=0.43">
  <!-- Including custom stylesheets and fonts -->
  <style>
    <?php include_once "res/Style.css" ?>
  </style>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
  <script src="jquery.fittext.js"></script>
  <script>
    jQuery("#responsive_headline").fitText();
  </script>
</head>

<body
  style="background-image: url('https://itdindee.org/wp-content/uploads/2023/03/shri.jpg'); background-repeat: no-repeat; background-size: cover;">
  <!-- Adding a button to add a new warkari -->
  <div class="add-warkari-btn-container">
    <button type="button" class="btn btn-warning add-warkari-btn"
      onClick="document.location.href='./Register-Warkari/'">
      <span class="fas fa-plus"></span> Add New Warkari
    </button>
  </div>
  <div class="container">
    <div class="row mt-5">
      <div class="col">

        <div class="card-header2">
          <h2 class="display-6 text-center">Registered Warkari</h2>
        </div>

        <!-- Adding a table to display the registered warkaris -->
        <div>
          <div class="table-responsive">
            <table class="table table-bordered text-center" style="border: 1px solid silver;">
              <thead>
              <tbody>
                <tr style="background-color: #171717;">

                  <td><strong> First Name </strong></td>
                  <td><strong> Last Name </strong></td>
                  <td><strong> Email </strong></td>
                  <td> <strong>Phone Number </strong></td>
                  <td> <strong>Edit </strong></td>
                  <td> <strong>View </strong></td>
                  <td><strong> Delete </strong></td>
                  <td> <strong>Join Waari 2023 </strong></td>

                </tr>
                <tr>

                  <?php
                  // Function to display the registered warkaris
                  function display_data()
                  {
                    global $conn;

                    // Getting the user ID
                    $ID = get_current_user_id();
                    $query = "SELECT ID FROM `gFs_users` WHERE user_email='$ID'";
                    $result = mysqli_query($conn, $query);
                    $user_id = get_current_user_id();

                    // Fetching the registered warkaris from the database
                    $query = "SELECT * FROM `warkari` WHERE userID='$user_id'";
                    $result = mysqli_query($conn, $query);
                    return $result;
                  }


                  // Call display_data() function and store result in $result variable
                  $result = display_data();

                  // Loop through each row returned by mysqli_fetch_assoc() function
                  while ($row = mysqli_fetch_assoc($result)) {

                    ?>

                    <td>
                      <?php echo $row['firstName']; ?>
                    </td>
                    <td>
                      <?php echo $row['lastName']; ?>
                    </td>
                    <td>
                      <?php echo $row['email']; ?>
                    </td>
                    <td>
                      <?php echo $row['mobileNo']; ?>
                    </td>

                    <!-- Create a link to edit warkari data, using warkariID as a query parameter -->
                    <td>
                      <a href="Edit?warkariID=<?php echo $row['warkariID']; ?>" class="btn btn-success">
                        <i class="fas fa-edit fa-sm"></i> <span class="btn-text"></span>
                      </a>
                    </td>

                    <!-- Create a link to view warkari data, using warkariID as a query parameter -->
                    <td>
                      <a href="View?warkariID=<?php echo $row['warkariID']; ?>" class="btn btn-primary">
                        <i class="fas fa-eye fa-sm"></i> <span class="btn-text"></span>
                      </a>
                    </td>

                    <!-- Create a form to delete warkari data, using warkariID as a hidden input value -->
                    <td>
                      <form method="post">
                        <input type="hidden" name="warkariID" value="<?php echo $row['warkariID']; ?>">
                        <button type="submit" name="delete_btn" class="btn btn-danger" disabled
                          onclick="return confirmDelete();">
                          <i class="fas fa-trash fa-sm"></i>
                        </button>
                      </form>
                    </td>

                    <!-- Add CSS styles for responsiveness -->
                    <style>
                      @media only screen and (max-width: 850px) {

                        /* Adjust the margin of the table wrapper to fit smaller screens */
                        .table-responsive {
                          margin-left: -70px;
                          margin-right: 20px;
                        }

                        /* Style the "Add Warkari" button */
                        .add-warkari-btn {
                          position: absolute;
                          bottom: 620px;
                          left: 50%;
                          transform: translateX(-50%);
                          color: white;
                          background-color: #f0ad4e;
                          border-color: #eea236;
                          color: black;
                          font-size: 18px;
                        }

                        /* Add hover effects to the "Add Warkari" button */
                        .add-warkari-btn:hover {
                          background-color: #ec971f;
                          border-color: #d58512;
                          color: white;
                        }

                        /* Style the "+" symbol before the "Add Warkari" button text */
                        .add-warkari-btn span:before {
                          content: "+";
                          margin-right: 5px;
                          color: black;
                          font-weight: bold;
                        }
                      }

                      /* Media query for smaller screens /
              @media (max-width: 576px) {
              / Wrap the table items and center them */
                      .table {
                        flex-wrap: wrap;
                        justify-content: center;
                      }


                      /* Adjust the margin of the "Add Warkari" button container */
                      .add-warkari-btn-container {
                        margin-top: 10px;
                      }


                      /* Style the success, primary, and danger buttons */
                      .btn-success,
                      .btn-primary,
                      .btn-danger {
                        display: inline-flex;
                        align-items: center;
                      }

                      /* Adjust the margin of the button text */
                      .btn-success .btn-text,
                      .btn-primary .btn-text,
                      .btn-danger .btn-text {
                        margin-left: 5px;
                      }
                    </style>
                    <td><a href="enroll-wari?warkariID=<?php echo $row['warkariID']; ?>">Join Waari
                        2023</a></td>

                    <a href="./Home"
                      style="color:#FFF;position: absolute;  left: 70; top:-60px; font-size: 54px; margin-right:100px">
                      <i class="bi bi-arrow-left-short bi-2x"></i>
                    </a>




                  </tr>
                  <?php
                  }

                  ?>
              </tbody>

            </table>
          </div>
        </div>
      </div>
    </div>


</body>

<script>
  function confirmDelete() {
    var result = confirm("Are you sure you want to delete this record?");
    return result;
  }
</script>


</html>