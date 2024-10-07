<?php
// include header file
get_header();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include_once "res/Style.css" ?>
    </style>
    <!-- Include Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <title>Warkari Details</title>
</head>

<body>
    <div class="container my-4 view-container">
        <header>
            <h1>Warkari Details</h1>
            <div>
                <!-- Link to go back to joinwaari page -->
                <a href="./joinwaari" style="color:#FFF; font-size: 54px;">
                    <i class="bi bi-arrow-left-short bi-2x"></i>
                </a>
            </div>
        </header>
        <div class="warkari-details p-5 my-4">
            <?php
            /* Template Name: View template */

            include_once('dbUtil/connect.php');

            // Get the warkari ID from URL parameter
            $id = $_GET['warkariID'];
            if ($id) {
               
                $sql = "SELECT w.*, c.cityName as city_name, a.areaName as area_name,cm.companyName as company_name
                    FROM warkari w
                    JOIN cityMaster c ON w.cityID = c.cityID
                    JOIN areaMaster a ON w.areaID = a.areaID
                    JOIN companyMaster cm ON w.companyID = cm.companyID
                    WHERE w.warkariID = $id";

                $result = mysqli_query($conn, $sql);

                
                if (!$result) {
                    
                    echo "Error executing query: " . mysqli_error($conn);
                    exit;
                }

                // Check if any rows were returned by the SQL query
                if (mysqli_num_rows($result) == 0) {
                    // Show error message and exit
                    echo "No records found for ID $id";
                    exit;
                }

                // Fetch the row from the query result
                $row = mysqli_fetch_array($result);
                // Rest of the code to display data goes here
            
                ?>
                <style>
                    h5,
                    p {
                        display: inline-block;
                        margin: 50;
                        padding-right: 10px;
                    }

                    .underline {
                        text-decoration: underline;
                    }
                </style>

              
                <div class="details-container">
                    <h5> First Name:</h5>
                    <h5>
                        <?php echo $row["firstName"]; ?>
                    </h5>
                </div>
          
                <div class="details-container">
                    <h5>Last Name:</h5>
                    <h5>
                        <?php echo $row["lastName"]; ?>
                    </h5>
                </div>

       
                <div class="details-container">
                    <h5>Email:</h5>
                    <h5>
                        <?php echo $row["email"]; ?>
                    </h5>
                </div>

            
                <div class="details-container">
                    <h5>Mobile Number:</h5>
                    <h5>
                        <?php echo $row["mobileNo"]; ?>
                    </h5>
                </div>

             
                <div class="details-container">
                    <h5>WhatsApp Number:</h5>
                    <h5>
                        <?php echo $row["whatsAppNo"]; ?>
                    </h5>
                </div>

              
                <div class="details-container">
                    <h5>Telegram Number:</h5>
                    <h5>
                        <?php echo $row["telegramNo"]; ?>
                    </h5>
                </div>

              
                <div class="details-container">
                    <h5>Gender:</h5>
                    <h5>
                        <?php echo $row["gender"]; ?>
                    </h5>
                </div>

               
                <div class="details-container">
                    <h5>City:</h5>
                    <h5>
                        <?php echo $row["city_name"]; ?>
                    </h5>
                </div>

               
                <div class="details-container">
                    <h5>Area:</h5>
                    <h5>
                        <?php echo $row["area_name"]; ?>
                    </h5>
                </div>

                
                <div class="details-container">
                    <h5>Address:</h5>
                    <h5>
                        <?php echo $row["address"]; ?>
                    </h5>
                </div>

                
                <div class="details-container">
                    <h5>Company:</h5>
                    <h5>
                        <?php echo $row["company_name"]; ?>
                    </h5>
                </div>

                
                <div class="details-container">
                    <h5>Are You Interested In Volunteering ?</h5>
                    <h5>
                        <?php echo $row["volunteer"]; ?>
                    </h5>
                </div>

                <?php
            }
            ?>
        </div>
    </div>
</body>

</html>