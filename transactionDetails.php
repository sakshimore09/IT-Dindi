<?php
include_once('dbUtil/connect.php');

session_start();

get_header();

// Check if the database connection is successful.
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Retrieve the enrollmentID stored in the session variable $_SESSION['maxEID'].
$enrollmentID = $_SESSION['maxEID'];

$query = "SELECT w.firstName, w.lastName, t.tappa, t.tappaDate, te.transactionID, te.registerDate, te.totalContribution
FROM warkari w
INNER JOIN tappaEnrollment te ON w.warkariID = te.warkariID
INNER JOIN tappaMaster t ON te.tappaID = t.tappaID
WHERE te.enrollmentID = '$enrollmentID'";

$result = mysqli_query($conn, $query);

if (!$result) {
  die("Error: " . mysqli_error($conn));
}

// Add CSS for the table
echo '<style>
table {
  border-collapse: separate;
  border-spacing: 0;
  width: 100%;
  margin-top: 20px;
  background-color: #28282B;
}
.card-header {
  margin-top: 20px;
  margin-left:540px;
}
th, td {
  text-align: center;
  padding: 8px;
}
body {
  color: #fff;
}

th {
  background-color: #ffd60bf5;
  color: #333;
}

h1 {
  font-size: 24px;
  color: #333;
  margin-top: 40px;
}


p {
  font-size: 16px;
  color: #666;
  margin-top: 20px;
}

.container {
  margin-top: 70px;
  max-width: 960px;
  padding: 0 15px;
  position:absolute;
  margin-left:250px
}
.confirmation-msg {
  display: none;
  color: green;
  text-align: center;
  font-size: 16px;
  margin-top: 20px;
}

.redirect-msg {
  display: none;
  color: red;
  text-align: center;
  font-size: 16px;
  margin-top: 20px;

}

@media (min-width: 258px) and (max-width: 750px) {
  
  .table-responsive {
    margin-left: -270px;
    
  
  }
  table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    margin: 0 auto;
    background-color: #28282B;
  }
  
  .card-header {
    margin-top: 20px;
    margin-left:540px;
  }
  th, td {
    text-align: center;
    padding: 8px;
  }
  body {
    color: #fff;
  }
  
  th {
    background-color: #ffd60bf5;
    color: #333;
  }
 
  
  
  p {
    font-size: 16px;
    color: #666;
    margin-top: 20px;
  }
  
  .container {
    margin-top: 70px;
    max-width: 960px;
    padding: 0 15px;
    position:absolute;
    margin-left:250px
  }
  .confirmation-msg {
    display: none;
    color: green;
    text-align: center;
    font-size: 16px;
    margin-top: 20px;
  }
  
  .redirect-msg {
    display: none;
    color: red;
    text-align: center;
    font-size: 16px;
    margin-top: 20px;
  
  }
  }
  

@media (min-width: 768px) and (max-width: 991px) {
  
  .table-responsive {
    margin-left: -270px;
    
  
  }
table {
  border-collapse: separate;
  border-spacing: 0;
  width: 100%;
  margin: 0 auto;
  background-color: #28282B;
}

.card-header {
  margin-top: 20px;
  margin-left:540px;
}
th, td {
  text-align: center;
  padding: 8px;
}
body {
  color: #fff;
}

th {
  background-color: #ffd60bf5;
  color: #333;
}
  



p {
  font-size: 16px;
  color: #666;
  margin-top: 20px;
}

.container {
  margin-top: 70px;
  max-width: 960px;
  padding: 0 15px;
  position:absolute;
  margin-left:250px
}
.confirmation-msg {
  display: none;
  color: green;
  text-align: center;
  font-size: 16px;
  margin-top: 20px;
}

.redirect-msg {
  display: none;
  color: red;
  text-align: center;
  font-size: 16px;
  margin-top: 20px;

}
}

@media (min-width: 992px) {
 
  .table-responsive {
    margin-left: -270px;
    
  
  }
table {
  border-collapse: separate;
  border-spacing: 0;
  width: 100%;
  margin: 0 auto;
  background-color: #28282B;
}

.card-header {
  margin-top: 20px;
  margin-left:540px;
}
th, td {
  text-align:center;
  padding: 8px;
}
body {
  color: #fff;
}

th {
  background-color: #ffd60bf5;
  color: #333;
}
  



p {
  font-size: 16px;
  color: #666;
  margin-top: 20px;
}

.container {
  margin-top: 70px;
  max-width: 960px;
  padding: 0 15px;
  position:absolute;
  margin-left:250px
}
.confirmation-msg {
  display: none;
  color: green;
  text-align: center;
  font-size: 16px;
  margin-top: 20px;
}

.redirect-msg {
  display: none;
  color: red;
  text-align: center;
  font-size: 16px;
  margin-top: 20px;

}
}


</style>';

echo '<div class="card-header">

    <h2>Enrolled Tappas</h2>
</div>';
// Add container for table and make it responsive
echo '<div class="container">';
echo '<div class="table-responsive">';
echo '<table style="margin-left:100px; margin-right:70px; " class="table table-bordered text-center">';
echo '<thead>
                <tr>
                    <th>Warkari Name</th>
                    <th>Tappa Name</th>
                    <th>Tappa Date</th>
                    <th>Total Contribution</th>
                    <th>Transaction ID</th>
                    <th>Register Date</th>
                </tr>
            </thead>';
echo '<tbody>';

$firstRow = true; // initialize variable to track first row

// Loop through the result and display data in table
while ($row = mysqli_fetch_assoc($result)) {
  echo '<tr>';

  if ($firstRow) {
    echo '<td rowspan="' . mysqli_num_rows($result) . '">' . $row['firstName'] . ' ' . $row['lastName']
      . '</td>';
    $firstRow = false; // set variable to false after first row
  }

  echo '<td>' . $row['tappa'] . '</td>';
  echo '<td>' . $row['tappaDate'] . '</td>';
  echo '<td>' . $row['totalContribution'] . '</td>';
  echo '<td>' . $row['transactionID'] . '</td>';
  echo '<td>' . $row['registerDate'] . '</td>';
  echo '</tr>';
}

echo '</tbody>';
echo '</table>';
echo '<div>';

echo '<div class="messages">';
echo '<div class="confirmation-msg" id="confirmation-msg" style="display:none;">You will receive
                    confirmation email and further instructions once your enrollment is processed.</div>';
echo '<div class="redirect-msg" id="redirect-msg" style="display:none;">You will be redirected to the
                    enrollment page in 30 seconds...</div>';
echo '</div>';

echo '</div>';

// Add script to show confirmation message and redirect after a delay
echo '
        <script>
            document.getElementById("confirmation-msg").classList.add("confirmation-msg");
            document.getElementById("redirect-msg").classList.add("redirect-msg");

            document.querySelector(".confirmation-msg").style.display = "block";
            setTimeout(function () {
                document.querySelector(".confirmation-msg").style.display = "none";
            }, 10000); // 10 second delay

            var redirectDisplayed = false;
            setTimeout(function () {
                if (!redirectDisplayed) {
                    document.querySelector(".redirect-msg").style.display = "block";
                    redirectDisplayed = true;
                }
            }, 10000); // 10 second delay

            setTimeout(function () {
                window.location.href = "https://itdindee.org/staging/7363/joinwaari/";
            }, 30000); // 30 second delay

        </script>';

?>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=0.48">
  <style>
    body {
      background-image: url('https://itdindee.org/wp-content/uploads/2023/04/evening-at-garden-in-pandharpur.webp');
      background-repeat: no-repeat;
      background-size: cover;
    }
  </style>
</head>

<body>
</body>

</html>