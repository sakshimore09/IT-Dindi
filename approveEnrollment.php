<?php
/* Template Name: admin-panel template */
include_once('dbUtil/connect.php');

// Check if the 'approve' button and transaction ID were set
if (isset($_POST['approve_btn']) && isset($_POST['transaction_id'])) {
    $transactionID = $_POST['transaction_id'];

    // Update the 'confirmFlag' to 2 for the given transaction ID
    $query = "UPDATE tappaEnrollment SET confirmFlag = 2 WHERE transactionID = '$transactionID'";
    $result = mysqli_query($conn, $query);

    // Check if the record was successfully updated
    if (mysqli_affected_rows($conn) > 0) {
        // Get the details of the warkari and tappa enrollment for sending email
        $email_query = "SELECT w.email, w.firstName, w.lastName, t.tappa,t.tappaDate, te.registerDate , te.totalContribution,te.transactionID,u.user_email, u2.user_email as admin_email
    FROM warkari w
    JOIN tappaEnrollment te ON w.warkariID = te.warkariID
    JOIN tappaMaster t ON te.tappaID = t.tappaID
    JOIN gFs_users u ON w.userID = u.ID
    JOIN gFs_users u2 ON u2.ID = 1
    WHERE te.confirmFlag = 2 AND te.transactionID = '$transactionID'";

        $email_result = mysqli_query($conn, $email_query);

        if (!$email_result) {
            die(mysqli_error($conn));
        }

       
        $table = '<table style="border-collapse: collapse; width: 100%;">
<thead>
<tr>
<th style="border: 1px solid black; padding: 10px; text-align: center;">Warkari Name</th>
<th style="border: 1px solid black; padding: 10px; text-align: center;">Tappa Name</th>
<th style="border: 1px solid black; padding: 10px; text-align: center;">Tappa Date</th>
<th style="border: 1px solid black; padding: 10px; text-align: center;">Total Contribution</th>
<th style="border: 1px solid black; padding: 10px; text-align: center;">Transaction ID</th>
<th style="border: 1px solid black; padding: 10px; text-align: center;">Register Date</th>
</tr>
</thead>
<tbody>';

        // Loop through each row of the email query result set
        while ($row = mysqli_fetch_assoc($email_result)) {
            
            $userEmail = $row['user_email'];
            $adminEmail = $row['admin_email'];
            $warkari_name = $row['firstName'] . ' ' . $row['lastName'];
            $warkari_mail = $row['email'];
            $transaction_id = $row['transactionID'];
            $tappaa_name = $row['tappa'];
            $tappadate = $row['tappaDate'];
            $register_date = $row['registerDate'];
            $total_contribution = $row['totalContribution'];

            $table .= '<tr>';
            $table .= '<td style="border: 1px solid black; padding: 10px; text-align: center;" >' . $row['firstName'] . ' ' . $row['lastName'] . '</td>';
            $table .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">' . $tappaa_name . '</td>';
            $table .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">' . $tappadate . '</td>';
            $table .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">' . $total_contribution . '</td>';
            $table .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">' .  $transaction_id . '</td>';
            $table .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">' . $register_date . '</td>';
            $table .= '</tr>';
        }

        $table .= '</tbody></table>';
        $to = $warkari_mail;
        $subject = 'Your Enrollment has been approved';
        $message = 'Ram Krishna Hari ' . $warkari_name . ' Mauli! 🙏🏼 ,<br><br>';
        $message .= "Your Enrollment has been approved. Here are the details:<br><br>";
        $message .= $table;
        $headers = array('Content-Type: text/html; charset=UTF-8', 'Cc:' . $userEmail . ',' . $adminEmail);

        // Send the email using WordPress' wp_mail function
        wp_mail($to, $subject, $message, $headers);
    }
}

get_header();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.43">
    <style>
        <?php include_once "res/Style.css" ?>
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <style>
        @media only screen and (max-width: 850px) {

            .table-responsive {
                margin-left: -70px;
                margin-right: 20px;

            }

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

            .add-warkari-btn:hover {
                background-color: #ec971f;
                border-color: #d58512;
                color: white;
            }



            .add-warkari-btn span:before {
                content: "+";
                margin-right: 5px;
                color: black;
                font-weight: bold;
            }
        }

        @media (max-width: 576px) {
            .table {
                flex-wrap: wrap;
                justify-content: center;
            }

            .add-warkari-btn-container {
                margin-top: 10px;
            }
        }

        .btn-success,
        .btn-primary,
        .btn-danger {
            display: inline-flex;
            align-items: center;
        }

        .btn-success .btn-text,
        .btn-primary .btn-text,
        .btn-danger .btn-text {
            margin-left: 5px;
        }
    </style>


</head>

<body style="background-image: url('https://itdindee.org/wp-content/uploads/2023/03/shri.jpg'); background-repeat: no-repeat; background-size: cover;">

    <div class="container3">
        <div class="row mt-5">
            <div class="col">

                <div class="card-header3">
                    <h2 class="display-6 text-center">Admin Panel</h2>
                </div>
                <div>


                    <div class="table-admin">
                        <?php
                        // This function retrieves and returns data from the database
                        function display_data()
                        {
                            global $conn;

                            $query = "SELECT w.firstName, w.lastName, w.email,w.mobileNo, w.volunteer, t.tappa, te.returnFlag, te.totalContribution, te.transactionID, te.registerDate, u.user_email , te.confirmFlag
                            FROM warkari w
                            JOIN tappaEnrollment te ON w.warkariID = te.warkariID
                            JOIN tappaMaster t ON te.tappaID = t.tappaID
                            JOIN gFs_users u ON w.userID = u.ID Where confirmFlag = 1 ";

                            // If search parameter is provided in the URL, append search criteria to the SQL query
                            if (isset($_GET['search']) && !empty($_GET['search'])) {
                                $search = mysqli_real_escape_string($conn, $_GET['search']);
                                $query .= " AND CONCAT(w.firstName, ' ', w.lastName, ' ', w.mobileNo, ' ', t.tappa, ' ', te.transactionID, ' ', te.totalContribution, ' ', te.registerDate, ' ', u.user_email) LIKE '%$search%'";
                            }

                            // If sort parameter is provided in the URL, append sorting criteria to the SQL query
                            if (isset($_GET['sort']) && !empty($_GET['sort'])) {
                                $sort = mysqli_real_escape_string($conn, $_GET['sort']);
                                switch ($sort) {
                                    case 'Descending':
                                        $query .= " ORDER BY u.user_email DESC";
                                        break;
                                    case 'Ascending':
                                        $query .= " ORDER BY u.user_email ASC";
                                        break;
                                    case 'newest':
                                        $query .= " ORDER BY te.registerDate DESC";
                                        break;
                                    case 'oldest':
                                        $query .= " ORDER BY te.registerDate ASC";
                                        break;
                                    default:
                                        break;
                                }
                            }
                            $result = mysqli_query($conn, $query);
                            if (!$result) {
                                die(mysqli_error($conn)); 
                            }
                            return $result;
                        }

                        // Check if a search query is provided in the URL, else set an empty string
                        if (isset($_GET['search'])) {
                            $search_query = $_GET['search'];
                        } else {
                            $search_query = '';
                        }
                        // Retrieve data from the database
                        $result = display_data($search_query);
                        if (mysqli_num_rows($result) > 0) {
                        ?>

                            <div class="search">
                                <form method="GET">
                                    <input type="text" class="fa fa-search" name="search" placeholder="Search...">
                                </form>
                            </div>
                            <form method="GET">
                                <div class="filter-container">
                                    <label for="sort">Sort by:</label>
                                    <select id="sort" name="sort">
                                        <option value="">Select</option>
                                        <option value="Descending">Descending</option>
                                        <option value="Ascending">Ascending</option>
                                        <option value="newest">Newest</option>
                                        <option value="oldest">Oldest</option>
                                    </select>
                                    <button type="submit">Filter</button>
                                </div>

                            </form>

                            <table class="table table-bordered text-center" style="border: 1px solid silver;">
                                <thead>


                                <tbody>
                                    <tr style="background-color: #171717;">
                                        <td><strong> User Email </strong></td>
                                        <td><strong> Warkari Name </strong></td>
                                        <td> <strong>Tappa Name </strong></td>
                                        <td><strong> Return/Stay </strong></td>
                                        <td><strong> Amount </strong></td>
                                        <td><strong> Transaction ID </strong></td>
                                        <td><strong> Register Date </strong></td>
                                        <td><strong> Status </strong></td>
                                    </tr>

                                    <?php
                                    // PHP code to display transaction data in a table with rowspan for repeating data
                                    // $result is assumed to be a MySQLi object containing the results of a query

                                    $last_transaction_id = ''; // initialize variables for rowspan calculation
                                    $rowspan = 0;

                                    while ($row = mysqli_fetch_assoc($result)) { // loop through each row of results
                                        if ($row['transactionID'] !== $last_transaction_id) { // if this is a new transaction, reset rowspan and last transaction ID
                                            $last_transaction_id = $row['transactionID'];
                                            $rowspan = 1;
                                        } else { // if this is a repeating transaction, increment rowspan
                                            $rowspan++;
                                        }
                                    ?>

                                        <!-- HTML table row for each transaction -->
                                        <tr>
                                            <?php if ($rowspan === 1) { // if this is the first row for this transaction, display rowspan for repeating data 
                                            ?>
                                                <td rowspan="<?php echo $rowspan; ?>"><?php echo $row['user_email']; ?></td>
                                                <td rowspan="<?php echo $rowspan; ?>"><?php echo $name = $row['firstName'] . ' ' . $row['lastName']; ?></td>
                                                <td rowspan="<?php echo $rowspan; ?>"><?php echo $row['tappa']; ?></td>
                                                <td rowspan="<?php echo $rowspan; ?>"><?php echo $row['returnFlag'] == 1 ? "Return Stay" : "Travel With Stay"; ?></td>
                                                <td>
                                                    <?php echo $row['totalContribution']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['transactionID']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['registerDate']; ?>
                                                </td>

                                                <td>
                                                    <form method="POST">
                                                        <input type="hidden" name="transaction_id" value="<?php echo $row['transactionID']; ?>">
                                                        <?php if ($row['confirmFlag'] == 1) { ?>
                                                            <button type="button" class="btn btn-success" onclick="showConfirmation('<?php echo $row['transactionID']; ?>')">Approve</button>

                                                        <?php } else { ?>
                                                            <button type="button" class="btn btn-success disabled">Approved</button>
                                                        <?php } ?>
                                                    </form>
                                                </td>

                                            <?php } else { ?>
                                                <td></td>
                                                <td>
                                                    <?php echo $name = $row['firstName'] . ' ' . $row['lastName']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['tappa']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['returnFlag'] == 1 ? "Return Stay" : "Travel With Stay"; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['totalContribution']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['transactionID']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['registerDate']; ?>
                                                </td>
                                                <td></td>
                                            <?php } ?>
                                            <a href="./Home" style="color:#FFF;position: absolute;  left: 70; top:-60px; font-size: 54px; margin-right:100px">
                                                <i class="bi bi-arrow-left-short bi-2x"></i>
                                            </a>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            <?php } else { ?>
                                <p>No results found.</p>
                            <?php } ?>


                            </table>

                    </div>
                </div>
            </div>
        </div>


</body>
<!-- Add a script for showing the confirmation message when approving a transaction -->
<script>
    function showConfirmation(transactionID) {
        if (confirm("Do you really want to approve transaction " + transactionID + "?")) {
            // Create a form and submit it to the server with the transaction ID and approve button value
            var form = document.createElement("form");
            form.setAttribute("method", "POST");
            form.setAttribute("action", ""); // Set your PHP script URL here
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", "transaction_id");
            hiddenField.setAttribute("value", transactionID);
            form.appendChild(hiddenField);
            var approveField = document.createElement("input");
            approveField.setAttribute("type", "hidden");
            approveField.setAttribute("name", "approve_btn");
            approveField.setAttribute("value", "1");
            form.appendChild(approveField);
            document.body.appendChild(form);
            form.submit();
        } else {
            return false;
        }
    }
</script>

</html>