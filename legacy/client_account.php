<?php
include "db/dblink.php";

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("location:signout.php");
} else {
    $q = mysqli_query($conn, "SELECT * FROM administrators WHERE user_id='" . $_SESSION['user_id'] . "'");
    $found = mysqli_num_rows($q);
    if ($found) {
        $user = mysqli_fetch_assoc($q);
    } else {
        header("location:signout.php");
    }
}
$client_id = mysqli_real_escape_string($conn, $_GET['client_id']);

$q = mysqli_query($conn, "SELECT * FROM users WHERE user_id='" . $client_id . "'");
$found = mysqli_num_rows($q);
if (!$found) {
    header("location:clients.php");
} else {
    $client = mysqli_fetch_assoc($q);
}
$q = mysqli_query($conn, "SELECT * FROM pricing_schemes WHERE scheme_id='" . $client['scheme_id'] . "'");
$scheme = mysqli_fetch_assoc($q);
$pricing = $scheme['scheme_name'];


$q = mysqli_query($conn, "SELECT * FROM resellers WHERE user_id='" . $client['reseller_id'] . "'");
$reseller = mysqli_fetch_assoc($q);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $app['app_name']; ?></title>
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
</head>

<body>
    <?php include "header.php"; ?>
    <div class="container">
        <div class="menu">
            <?php include "menu.php"; ?>
        </div>
        <div class="content-wrapper">
            <div class="page-title">Client Account</div>
            <div class="page-header">
                <div class="page-options">
                    <span class="account-name"><i class="fas fa-id-card fa-lg"></i><?php echo $client['client_name']; ?></span>
                </div>

                <ul class="page-menu">
                    
                    <?php
                    if ($client['reseller_id'] == "Administrator") {
                    ?>
                        <li onclick="edit_client(<?php echo $client['user_id']; ?>)"><label><i class="fas fa-edit fa-s"></i>Edit Client</label></li>
                        <li onclick="delete_client('<?php echo $client['user_id']; ?>')"><label><i class="fas fa-trash fa-s"></i>Delete Client</label></li>
                    <?php } ?>
                    <li style="background-color:var(--gray-color);"><label><b>Reseller</b>: <?php echo $reseller['business_name']; ?></label></li>

                    <li onclick="transfer_client(<?php echo $client['user_id']; ?>)"><label><i class="fas fa-exchange-alt fa-s"></i>Transfer</label></li>
                </ul>
            </div>

            <input type="checkbox" name="edit_client" id="edit_client">
            <div id="edit_client_modal">
                <div class="modal-wrapper">
                    <div class="modal-header">
                        <div class="modal-title">
                            Edit Client
                        </div>
                        <div class="modal-close">
                            <label for="edit_client"><i class="fas fa-times fa-2x"></i></label>
                        </div>
                    </div>
                    <div class="modal-content" id="edit_client_modal_content">

                    </div>
                </div>
            </div>

            <input type="checkbox" name="transfer_client" id="transfer_client">
            <div id="transfer_client_modal">
                <div class="modal-wrapper">
                    <div class="modal-header">
                        <div class="modal-title">
                            Transfer Client
                        </div>
                        <div class="modal-close">
                            <label for="transfer_client"><i class="fas fa-times fa-2x"></i></label>
                        </div>
                    </div>
                    <div class="modal-content" id="transfer_client_modal_content">

                    </div>
                </div>
            </div>
            <input type="checkbox" name="client_change_password" id="client_change_password">
            <div id="client_change_password_modal">
                <div class="modal-wrapper">
                    <div class="modal-header">
                        <div class="modal-title">
                            Change Password
                        </div>
                        <div class="modal-close">
                            <label for="client_change_password"><i class="fas fa-times fa-2x"></i></label>
                        </div>
                    </div>
                    <div class="modal-content" id="client_change_password_modal_content">
                        <form id="client_password_form" action="client_change_password.php?client_id=<?php echo $client['user_id']; ?>" method="post">
                            <div class="form-field">
                                <label for="">New Password</label>
                                <input type="password" name="client_new_password" id="client_new_password">
                            </div>
                            <div class="form-field">
                                <label for="">Confirm Password</label>
                                <input type="password" name="client_confirm_password" id="client_confirm_password">
                            </div>
                            <div class="form-field">
                                <div class="send-button" onclick="client_change_password()">Change Password</div>
                            </div>
                            <div class="form-field">
                                <div class="form-errors" id="password_form_errors"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="page-content" id="page-content">
                <div class="profile-info">
                    <div><label>Phone Number:</label></div>
                    <div><?php echo $client['contact_phone']; ?></div>
                </div>
                <div class="profile-info">
                    <div><label>Email Address:</label></div>
                    <div><?php echo $client['email']; ?></div>
                </div>
                <div class="profile-info">
                    <div><label>Status:</label></div>
                    <div><?php echo $client['status']; ?></div>
                </div>
                <div class="profile-info">
                    <div><label>Pricing Scheme:</label></div>
                    <div><?php echo $pricing; ?></div>
                </div>

                <div class="profile-info">
                    <div><label>Password:</label></div>
                    <div>
                        <label for="client_change_password">
                            <span class="change-password">
                                <i class="fas fa-edit fa-small"></i>Change Password
                            </span>
                        </label>
                        <?php
                        if (isset($_GET['r']) && $_GET['r'] == "Incorrect password") {
                        ?>
                            <span class="password-error">Incorrect Password</span>
                        <?php } ?>


                        <?php
                        if (isset($_GET['r']) && $_GET['r'] == "Invalid password") {
                        ?>
                            <span class="password-error">Invalid Password</span>
                        <?php } ?>

                        <?php
                        if (isset($_GET['r']) && $_GET['r'] == "Password changed") {
                        ?>
                            <span class="password-changed">Password changed</span>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-field" style="margin:40px 0px;">
                    <label for="" style="font-weight:bold; font-size:1rem; margin:15px 0px;">Purchase History</label>
                    <table>
                        <tr class="table-header">
                            <td>Order No.</td>
                            <td>Date</td>
                            <td>Quantity</td>
                            <td style="text-align:right;">Price</td>
                            <td style="text-align:right;">Amount (TSH)</td>
                            <td style="text-align:center;">Status</td>
                            <td>Receipt No.</td>
                            <td>Payment Method</td>
                        </tr>
                        <?php
                        $q = mysqli_query($conn, "SELECT * FROM sms_orders WHERE user_id='" . $client['user_id'] . "' ORDER BY order_date DESC");

                        while ($order = mysqli_fetch_assoc($q)) {
                        ?>
                            <tr>
                                <td><?php echo $order['reference']; ?></td>
                                <td><?php echo date("d-m-Y H:i", $order['order_date']); ?></td>
                                <td><?php echo $order['quantity']; ?></td>
                                <td align="right"><?php echo round($order['price']); ?></td>
                                <td align="right"><?php echo number_format($order['amount']); ?></td>
                                <td align="center"><?php echo $order['order_status']; ?></td>
                                <td><?php echo $order['receipt']; ?></td>
                                <td><?php echo $order['payment_method']; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include "footer.php";?>
</body>

</html>