<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <title>Warkari Details</title>
    <style>
        .warkari-details{
            background-color:#f5f5f5;
        }
    </style>
</head>
<body>
    <div class="container my-4">
        <header class="d-flex justify-content-between my-4">
            <h1>Warkari Details</h1>
            <div>
            <a href="welcome.php" class="btn btn-primary">Back</a>
            </div>
        </header>
        <div class="warkari-details p-5 my-4">
            <?php
          require_once 'dbUtil/connect.php';
            $id = $_GET['warkariID'];
            if ($id) {
                $sql = "SELECT w.*, c.cityName as city_name, a.areaName as area_name
                FROM warkari w
                JOIN cityMaster c ON w.cityID = c.cityID
                JOIN areaMaster a ON w.areaID = a.areaID
                WHERE w.warkariID = $id";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_array($result)) {
                 ?>
                 <h5>First Name :</h5>
                 <p><?php echo $row["firstName"]; ?></p>
                 <h5>Last Name:</h5>
                 <p><?php echo $row["lastName"]; ?></p>
                 <h5>Email:</h5>
                 <p><?php echo $row["email"]; ?></p>
                 <h5>Mobile Number:</h5>
                 <p><?php echo $row["mobileNo"]; ?></p>
                 <h5>WhatsApp Number:</h5>
                 <p><?php echo $row["whatsAppNo"]; ?></p>
                 <h5>Telegram Number:</h5>
                 <p><?php echo $row["telegramNo"]; ?></p>
                 <h5>Gender:</h5>
                 <p><?php echo $row["gender"]; ?></p>
                 <h5>Address:</h5>
                 <p><?php echo $row["address"]; ?></p>
                 <h5>City:</h5>
                 <p><?php echo $row["City_name"]; ?></p>
                 <h5>Area:</h5>
                 <p><?php echo $row["Area_name"]; ?></p>
                 <h5>Company:</h5>
                 <p><?php echo $row["companyID"]; ?></p>
                 <h5>Are You Interested In Volunteering ? </h5>
                 <p><?php echo $row["volunteer"]; ?></p>
                 <?php
                }
            }
            else{
                echo "<h5>No record found</h5>";
            }
            ?>
            
        </div>
    </div>
</body>
</html>