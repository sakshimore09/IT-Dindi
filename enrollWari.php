<?php

/* Template Name: Enroll */


include_once('dbUtil/connect.php');
require_once 'functions.php';


function get_stay_contribution($tappaID)
{
    global $conn;
    $stayContri = 0;
    $stayC = mysqli_query($conn, "SELECT stayContri FROM tappaMaster WHERE tappaID=$tappaID");

    if ($stayC) {
        $row = mysqli_fetch_assoc($stayC);
        $stayContri = $row['stayContri'];
    }

    return $stayContri;
}


function get_return_contribution($tappaID)
{
    global $conn;
    $returnContri = 0;
    $returnC = mysqli_query($conn, "SELECT returnContri FROM tappaMaster WHERE tappaID=$tappaID");

    if ($returnC) {
        $row = mysqli_fetch_assoc($returnC);
        $returnContri = $row['returnContri'];
    }

    return $returnContri;
}


function get_tappa_dates($tappaID)
{
    global $conn;
    $query = "SELECT tappaDate FROM tappaMaster WHERE tappaID = $tappaID";
    $tappaD = mysqli_query($conn, $query);
    $tappaDates = [];

    while ($row = mysqli_fetch_assoc($tappaD)) {
        $tappaDates[] = $row['tappaDate'];
    }

    return $tappaDates;
}

if (isset($_POST['action'])) {
    $tappaID = $_POST['tappaID'];

    if ($_POST['action'] == 'get_stay_contribution') {
     
        $stayContri = get_stay_contribution($tappaID);
        echo $stayContri;
        exit();
    } else if ($_POST['action'] == 'get_return_contribution') {
      
        $returnContri = get_return_contribution($tappaID);
        echo $returnContri;
        exit();
    }
}


$totalContribution = 0;


if (isset($_POST['submit'])) {
    $warkariID = $_GET['warkariID'];
    $tappaIDs = isset($_POST['checkbox']) ? $_POST['checkbox'] : [];

    if (empty($tappaIDs)) {
        die("No Tappa selected");
    }

    // Initialize arrays to hold data for each selected Tappa
    $tappaData = array();
    $tappaIDsStr = implode(',', $tappaIDs);

    // Loop through each selected Tappa
    foreach ($tappaIDs as $tappaID) {
        // Fetch tappa dates from function
        $tappaDates = get_tappa_dates($tappaID);

        if (empty($tappaDates)) {
            die("No tappa dates found for tappaID: $tappaID");
        }


        // Set $selectedOption variable to the value of the "option$tappaID" POST parameter if it exists, otherwise set it to 0
        $selectedOption = isset($_POST["option$tappaID"]) ? $_POST["option$tappaID"] : 0;

   
        $returnFlag = 0;

        // Set $returnFlag to 1 if $selectedOption is equal to 1
        if ($selectedOption == 1) {
            $returnFlag = 1;
        }

        // Loop through each Tappa date in the $tappaDates array
        foreach ($tappaDates as $tappaDate) {

            // Get the return and stay contributions for this Tappa
            $returnContri = get_return_contribution($tappaID);
            $stayContri = get_stay_contribution($tappaID);


            $contribution = 0;

            // Set $contribution to the return contribution if $selectedOption is equal to 1, otherwise set it to the stay contribution if $selectedOption is equal to 2
            if ($selectedOption == 1) {
                $contribution = $returnContri;
            } else if ($selectedOption == 2) {
                $contribution = $stayContri;
            }

            // Add data for this Tappa to the $tappaData array
            $tappaData[] = array(
                'warkariID' => $warkariID,
                'tappaID' => $tappaID,
                'tappaDate' => $tappaDate,
                'registerDate' => date('Y-m-d H:i:s'),
                'contribution' => $contribution,
                'returnFlag' => $returnFlag,
            );

            // Add contribution for this Tappa to the total contribution
            $totalContribution += $contribution;
        }
    }

    // Add total contribution to the $tappaData array
    foreach ($tappaData as &$data) {
        $data['totalContribution'] = $totalContribution;
    }

    // Encode the Tappa data as JSON and add it to the URL query string
    session_start();
    $_SESSION['warkariID'] = $warkariID;
    $_SESSION['tappaIDs'] = $tappaIDs;
    $_SESSION['tappaData'] = $tappaData;


    wp_redirect('https://itdindee.org/staging/7363/payment/');
    exit();

}
get_header();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.50">
    <style>
        <?php include_once "res/Style.css" ?>
        /* Style for container1 */
        .container1 {
            margin-top: 50px;
            margin-bottom: 50px;
        }

        

        /* Make table-responsive */
        .table-responsive {
            overflow-x: auto;
        }

        /* Style for table-bordered1 */
        .table-bordered1 {
            border-collapse: collapse;
        }

        .table-bordered1 th,
        .table-bordered1 td {
            border: 1px solid silver;
            padding: 8px;
        }

        /* Style for btn-back */
        .btn-back {
            margin-top: 10px;
        }

        /* Media query for small screens */
        @media (max-width: 576px) {
            .container1 {
                margin-top: 10px;
                margin-bottom: 10px;
            }

            .table-bordered1 th,
            .table-bordered1 td {
                padding: 5px;
                font-size: 12px;
            }

            .btn-back {
                margin-top: 5px;
            }
        }
    </style>

    <style>
        <?php include_once "res/Style.css" ?>
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
  $(document).ready(function() {
    $('select').change(function() {
      var tappaID = $(this).attr('name').replace('option', '');
      var contributionType = $(this).val();
      var spanID = '#contribution_' + tappaID;
      var contributionAmount;

      if (contributionType == 1) {
        // Calculate return travel contribution
        $.ajax({
          url: window.location.href,
          type: 'POST',
          data: { 
            action: 'get_return_contribution',
            tappaID: tappaID
          },
          success: function(data) {
            contributionAmount = data;
            $(spanID).text(contributionAmount);
            // update the corresponding input field with the contribution value
            $('input[name="contribution_' + tappaID + '"]').val(contributionAmount);
            calculateTotalContribution();
          }
        });
      } else if (contributionType == 2) {
 
        $.ajax({
          url: window.location.href,
          type: 'POST',
          data: { 
            action: 'get_stay_contribution',
            tappaID: tappaID
          },
          success: function(data) {
            contributionAmount = data;
            $(spanID).text(contributionAmount);
            // update the corresponding input field with the contribution value
            $('input[name="contribution_' + tappaID + '"]').val(contributionAmount);
            calculateTotalContribution();
          }
        });
      } else {
        // Reset contribution value
        contributionAmount = '';
        $(spanID).text(contributionAmount);
        // update the corresponding input field with the empty value
        $('input[name="contribution_' + tappaID + '"]').val(contributionAmount);
        calculateTotalContribution();
      }
    });
  });
  $(document).ready(function() {
  $('input[name="checkbox[]"]').on('change', function() {
    calculateTotalContribution();
  });
});

function calculateTotalContribution() {
  var totalContribution = 0;
  $('input[name="checkbox[]"]:checked').each(function() {
    var tappaID = $(this).val();
    var contribution = $("#contribution_" + tappaID).text().trim();
    if (contribution && $(this).is(':not(:disabled)')) {
      totalContribution += parseInt(contribution);
    }
  });

  $("#totalContribution").text(totalContribution);
}

</script>

</head>

<body
    style="background-image: url('https://itdindee.org/wp-content/uploads/2023/03/wari.jpg'); background-repeat: no-repeat; background-size: cover;">

    <form action="" class=".enroll-card" method="post">

        <?php
        $warkariID = $_GET['warkariID']; // Get the Warkari ID from the URL parameter
        $sql = "SELECT firstName, lastName FROM warkari WHERE warkariID = $warkariID";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result); // Fetch the result as an associative array
        $name = $row['firstName'] . ' ' . $row['lastName'];
        ?>
       
        <div class="container1">
            <h1 class="display-6-1 text-center">Warkari Enrollment</h1>
            <div class="card-header1">
                <h4 style="color:#ffffff;"> Enrollment For
                    <?php echo $name; ?>
                </h4>
                <div class="table-responsive">
                    <table style="border: 1px solid silver" class="table table-bordered1 text-center">
                        <tr style="background-color: #E49B0F;">
                            <td><strong> Select </strong></td>
                            <td><strong>Waari Year</strong></td>
                            <td><strong> Tappa</strong></td>
                            <td><strong>Tappa Date</strong></td>
                            <td><strong>Distance</strong></td>
                            <td><strong>Tithi</strong></td>
                            <td><strong>Tappa Day</strong></td>
                            <td><strong>Difficulty Level</strong></td>
                            <td><strong>Return/Stay</strong></td>
                            <td><strong>Remark</strong></td>
                            <td><strong>Contribution</strong></td>
                        </tr>

                   
                        <?php
                        $warkariID_s = $_GET['warkariID']; // Get the Warkari ID from the URL parameter
                        $selectedTappaIDs = array(); // Initialize an empty array to store the selected tappa IDs
                        
                        $sql = "SELECT * FROM tappaEnrollment WHERE warkariID = $warkariID_s"; // SQL query to retrieve the tappa enrollment details for the Warkari
                        $result = mysqli_query($conn, $sql); // Execute the query
                        while ($row = mysqli_fetch_assoc($result)) {
                            $selectedTappaIDs[] = $row['tappaID'];
                        }

                        $previousSelected = array(); // Initialize an empty array to store the contribution amounts for the previously selected tappas
                        foreach ($selectedTappaIDs as $tappaID) {
                            $tappa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT returnFlag, contribution FROM tappaEnrollment WHERE tappaID = $tappaID and warkariID = $warkariID_s")); // Retrieve the contribution amount for the selected tappa from the database
                            $previousSelected[$tappaID] = $tappa['contribution'];
                        }

                        $sql = "SELECT * FROM tappaMaster"; // Query to select all tappas
                        $result = mysqli_query($conn, $sql); 
                        while ($row = mysqli_fetch_assoc($result)) { // Loop through the tappas
                            $tappaID = $row['tappaID']; 
                            $isChecked = in_array($tappaID, $selectedTappaIDs); // Check if the tappa is already selected
                        

                  
                            $transactionID = null;
                            $registerDate = null;

                            // check if the tappa is already selected
                            if (in_array($tappaID, $selectedTappaIDs)) {
                                $tappa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT transactionID,registerDate,returnFlag, contribution FROM tappaEnrollment WHERE tappaID = $tappaID and warkariID = $warkariID_s"));

                                $transactionID = $tappa['transactionID']; 
                                $registerDate = $tappa['registerDate']; 
                        
                                if ($tappa['returnFlag'] == 1) {
                                    $selectedOption = 1; // set selected option to 'Return Travel'
                                } else {
                                    $selectedOption = 2; // set selected option to 'Travel with Stay'
                                }
                                $isChecked = true;
                            } else {
                                $selectedOption = 0; // set selected option to 'Select'
                                $isChecked = false;
                            }

                            $isDisabled = $isChecked; // disable if already selected
                            if (!$isDisabled && $row['isActiveFlag'] == 0) {
                                $isDisabled = true; // disable if inactive
                            }

                            // display the contribution for previously selected tappas and newly selected tappas
                            if (array_key_exists($tappaID, $previousSelected)) {
                                $contribution = $previousSelected[$tappaID];
                            } else {
                                $contribution = null;
                            }
                            ?>

                            <tr <?php echo $isDisabled ? 'class="disabled-row1" title="Transaction ID: ' . $transactionID . '  Register Date: ' . $registerDate . '"' : ''; ?>>
                                <td>
                                    <input type="checkbox" name="checkbox[]" value="<?php echo $tappaID; ?>" <?php echo $isChecked ? 'checked' : ''; ?>     <?php echo $isDisabled ? 'disabled' : ''; ?>>
                                </td>
                                <td>
                                    <?php echo $row['waariYear']; ?>
                                </td>
                                <td>
                                    <?php echo $row['tappa']; ?>
                                </td>
                                <td>
                                    <?php echo $row['tappaDate']; ?>
                                </td>
                                <td>
                                    <?php echo $row['distance']; ?>
                                </td>
                                <td>
                                    <?php echo $row['tithi']; ?>
                                </td>
                                <td>
                                    <?php echo $row['tappaDay']; ?>
                                </td>
                                <td>
                                    <?php echo $row['difficultyLevel']; ?>
                                </td>
                                <td>
                                    <select name="option<?php echo $row['tappaID']; ?>" <?php echo $isChecked ? 'disabled' : ''; ?>>
                                        <option value="0" <?php echo (!$isChecked && $selectedOption == 0) ? 'selected' : ''; ?>>Select</option>
                                        <option value="1" <?php echo ($isChecked && $selectedOption == 1) ? 'selected' : ''; ?>>Return Travel</option>
                                        <option value="2" <?php echo ($isChecked && $selectedOption == 2) ? 'selected' : ''; ?>>Travel with Stay</option>
                                    </select>
                                </td>
                                <td>
                                    <?php echo $row['remark']; ?>
                                </td>


                                <td>
                                    <span name="contribution_" id="contribution_<?php echo $tappaID; ?>"><?php echo $contribution; ?></span>
                                </td>

                            <?php
                        }
                        ?>
                    </table>
                </div>
                <div class="row mt-3">
                    <div class="col-5 ml-auto">
                        <div class="bg-dark p-1 rounded shadow-sm">
                            <h5 style="color: white;">Total Contribution: <span id="totalContribution"></span></h5>
                        </div>
                    </div>
                </div>
                <a href="./joinwaari"
                    style="color:#FFF;position: absolute;  left: 70; top:50px; font-size: 54px; margin-right:100px">
                    <i class="bi bi-arrow-left-short bi-2x"></i>
                </a>
                <button type="submit" name="submit" class="btn btn-warning"
                    style="margin-left: 1px; margin-top: -70px ;">Click Here To Make Payment</button>
    </form>
</body>


</html>